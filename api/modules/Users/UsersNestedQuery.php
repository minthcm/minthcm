<?php

namespace MintHCM\Modules\Users;

use MintHCM\Lib\Search\ElasticSearch\BaseNestedQuery;

class UsersNestedQuery extends BaseNestedQuery
{
    protected $queries = ['getNestedSecurityGroupQuery'];
}
