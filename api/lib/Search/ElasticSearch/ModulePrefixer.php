<?php

namespace MintHCM\Lib\Search\ElasticSearch;

use MintHCM\Lib\Search\ElasticSearch\ElasticMapperParser;

class ModulePrefixer
{
    protected $module;

    public function __construct(string $module)
    {
        $this->module = $module;
    }

    public function modify(string $field_name, bool $without_keyword = false): string
    {
        $parser = ElasticMapperParser::getInstance();
        $field_name = $parser->getFieldAttributePath($this->module, $field_name);
        if($without_keyword){
            return str_replace('.keyword', '', $field_name);
        }
        return $field_name;
    }

}
