# Sidepanel Endpoints

The Vue frontend's record-view sidepanel widgets (see the frontend docs) are backed by small, read-only summary endpoints. Two things about them are worth documenting: where their routes live, and how they authorize access to a specific record's HR data.

## Per-module route file

Rather than adding these routes to the central `app/Routes/routes/`, each module declares them in its own `api/routes/` directory — a location the `RouteManager` already scans (`modules/{ModuleName}/api/routes/`, see [Routing System](./05-routing.md)). Sidepanel routes are grouped into a dedicated file per module:

```
api/modules/Employees/api/routes/sidepanel.php
api/modules/Candidatures/api/routes/sidepanel.php
```

These use the standard `$routes` array format, so no new registration mechanism is involved. Because they are module-owned, their paths are automatically prefixed with the module name — `'path' => '/{id}/sidepanel/tenure'` is served as `GET /api/Employees/{id}/sidepanel/tenure`. Keeping them in a separate `sidepanel.php` (instead of the module's main route file) simply keeps the feature's routes together. All are `GET`, `auth => true`, and take the record id as a `StringType` path parameter.

## Controller convention

Each module has a single sidepanel controller (`EmployeeSidepanelController`, `CandidaturesSidepanelController`) with one action per widget. The actions are deliberately thin: check access, run one read query (or delegate to a service), and write JSON. Non-trivial aggregation is extracted into `lib/Services/` (e.g. `LeaveService`, `KudosSummaryService`) so it stays unit-testable and out of the HTTP layer, and is injected via the constructor. Responses are a plain `json_encode` of the array/DTO the widget expects — there is no wrapping envelope.

## Per-record access check

These endpoints expose one employee's or candidate's data to whoever asks for it by id, so they must authorize on the **viewer's ACL for that specific record**, not merely on being authenticated. `EmployeeSidepanelController::checkEmployeeAccess()` implements the sanctioned pattern, mirroring `KudosController`, `CommentsController`, and `FilesController`:

```php
private function checkEmployeeAccess(string $employeeId): ?Response
{
    chdir('../legacy');
    $employee  = \BeanFactory::getBean('Employees', $employeeId);
    $notFound  = empty($employee->id);
    $forbidden = !$notFound && !$employee->ACLAccess('view');
    chdir('../api');

    if ($notFound)  return (new Response())->withStatus(404);
    if ($forbidden) return (new Response())->withStatus(403);
    return null; // access granted
}
```

The `chdir('../legacy')` … `chdir('../api')` bracket is required because `BeanFactory` and ACL evaluation depend on the legacy working directory (see [Legacy Integration](./07-legacy-integration.md)). Each action calls the helper first and returns early on a non-null result:

```php
public function getTenureSummary(Request $request, Response $response, array $args): Response
{
    if ($accessError = $this->checkEmployeeAccess($args['id'])) {
        return $accessError;
    }
    // ... query + json_encode
}
```

**Any sidepanel endpoint that returns a particular record's data must apply this check before querying.** The Doctrine queries themselves are not ACL-aware, so the bean-level `ACLAccess('view')` gate is the only thing preventing one user from reading another record's HR summary by guessing or enumerating ids.
