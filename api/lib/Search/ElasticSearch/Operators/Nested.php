<?php
namespace MintHCM\Lib\Search\ElasticSearch\Operators;

use MintHCM\Lib\Search\ElasticSearch\ElasticOperator;
use MintHCM\Lib\Search\ElasticSearch\ModulePrefixer;

class Nested extends ElasticOperator
{
    protected $data;

    public function __construct(array $data)
    {
        parent::__construct($data);
        $this->value = $data;
    }

    protected function getDataArray(ModulePrefixer $prefixer): array
    {
        return [
            'nested' => $this->value
        ];
    }

    protected function validateData(): bool
    {
        return !empty($this->value);
    }
}