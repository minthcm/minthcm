<?php

namespace MintHCM\Modules\Employees;

use MintHCM\Lib\Search\ElasticSearch\BaseNestedQuery;

class EmployeesNestedQuery extends BaseNestedQuery
{
    protected $queries = ['getNestedSecurityGroupQuery'];
}
