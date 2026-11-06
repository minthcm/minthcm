# KREST — mandatory security rules for handlers

> Applies to the whole `legacy/KREST/` directory and to module REST handlers
> (`legacy/modules/*/KREST/`, `legacy/modules/KReports/KReportRESTHandler.php`).

## What you need to know before writing a handler

**Application input sanitization does not apply to KREST.**

`clean_incoming_data()` (`legacy/include/utils.php`) rewrites only `$_REQUEST`,
`$_POST` and `$_GET`. Slim builds the URI from `$_SERVER['QUERY_STRING']`
(`legacy/KREST/Slim/Http/Uri.php`), and `Request::getQueryParams()` calls `parse_str()` on it
(`legacy/KREST/Slim/Http/Request.php`). That is a different superglobal — `securexss()`
never touches it.

KREST loads `include/entryPoint.php`, so `securexss()` **does run** — but on data
that no handler reads. The same applies to:

- query parameters (`$request->getQueryParams()`),
- path arguments (`$args[...]` from Slim routes),
- the request body (`$request->getParsedBody()`, `json_decode($request->getBody())`).

Moreover, even if it did apply, `securexss()` is an **XSS** filter, not an SQL one. It encodes
`' " < > \``, but leaves digits, spaces, commas and SQL keywords untouched. Relying on
it as protection against SQL injection is an architectural mistake, not a safeguard.

## Rule

**Every request value that reaches SQL must go through `KRESTSqlGuard`**
(`legacy/KREST/KRESTSqlGuard.php`). The class is loaded in `legacy/KREST/index.php`,
so it is available in every handler without an additional `require`.

Two cases that **must not be confused**:

### 1. Values — escaping

A value compared in `WHERE` or inserted into a string literal:

```php
$where = "users.status = '" . KRESTSqlGuard::escapeValue($params['status']) . "'";
$limit = KRESTSqlGuard::toInt($params['limit']);
```

| Method | Use |
|---|---|
| `escapeValue($v)` | string literal (without quotes — those stay in the SQL) |
| `escapeValueList($v)` | list for `IN (...)`, returns a ready `'a','b'` |
| `toInt($v, $min = 0)` | `LIMIT`, `OFFSET`, counters |
| `toFloat($v)` | number in an SQL expression |

### 2. Identifiers — allowlist

Column name, table name, operator, sort direction, JOIN type.

**Escaping and prepared statements DO NOT WORK HERE.** A prepared statement binds
values, not identifiers. The only protection is an allowlist.

```php
$column = KRESTSqlGuard::assertColumn($bean, $params['sortfield']);
$direction = KRESTSqlGuard::assertSortDirection($params['sortdirection']);
$orderBy = $bean->table_name . '.' . $column . ' ' . $direction;
```

| Method | Allowlist source |
|---|---|
| `assertColumn($bean, $name)` | `$bean->field_defs` |
| `assertQualifiedColumn($bean, $name)` | as above, supports `table.column` |
| `assertTableColumn($name, $table)` | actual table columns (`$db->get_columns()`) |
| `assertTable($name)` | database schema (`$db->tableExists()`) |
| `assertOperator($op)` | `KRESTSqlGuard::ALLOWED_OPERATORS` |
| `assertSortDirection($dir)` | `ASC` / `DESC` |
| `assertJoinType($type)` | `INNER` / `LEFT` / `RIGHT` |
| `decodeStringList($json)` | safe `json_decode` into a list of strings |

### `searchfields` conditions with `addjoin`

`addjoin` joins a table validated by `assertTable()`, i.e. **any table in the
schema**. That is why the condition `field` is deliberately validated only against the bean table
(`assertQualifiedColumn()`) — a condition on a column of the joined table (`jointable.column`)
returns HTTP 400. Allowing it would give every session a way to probe arbitrary data
in other tables. If a client needs a filter on another table's column, add a closed allowlist
of table/column pairs instead of opening `assertTableColumn()` to request data.

## Handling rejection

The `assert*()` methods throw `KRESTInvalidInputException`. The middleware in
`legacy/KREST/index.php` turns it into **HTTP 400** with a JSON body and a log entry at the
`security` level. **Do not catch this exception** in a handler — the rejection must reach the client.

**Never silently drop a condition that failed validation.** A silent drop
changes the query semantics and can widen the set of returned records — turning
an SQL injection issue into an access control issue.

## What this mechanism does not cover

- **ACL.** `KRESTManager::authenticate()` only checks that the session is valid — no role
  or permission. Data access control must be done in the handler
  (`ACLController::checkAccess()`), as `get_bean_detail()` does.
- **Blocking probes.** The middleware that detects SQLi patterns only **logs**, it does not block —
  KReports legitimately sends values full of punctuation, and blocking
  on a pattern would break saved filters.
