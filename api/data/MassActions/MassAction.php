<?php

namespace MintHCM\Data\MassActions;

abstract class MassAction
{
    const ICON = '';
    const LABEL = '';

    protected $module_name;
    protected $ids;
    protected $filter;

    public function __construct($module_name, $ids, $filter = [])
    {
        $this->module_name = $module_name;
        $this->filter = $filter;
        $this->ids = in_array('all', $ids) ? MassActionDataHelper::getRecordIds($module_name, $filter) : $ids;
    }

    abstract public function execute();
    abstract public function hasAccess();

    public function getFrontendData()
    {
        return [
            'icon' => static::ICON,
            'label' => static::LABEL,
            'action' => (new \ReflectionClass($this))->getShortName(),
        ];
    }
    protected function getBeans()
    {
        if (empty($this->ids)) {
            return [];
        }
        chdir('../legacy');
        $seed = \BeanFactory::getBean($this->module_name);
        $db = \DBManagerFactory::getInstance();
        $beans = $seed->get_full_list('', "{$seed->table_name}.id IN ({$db->implodeQuoted($this->ids)})");
        chdir('../api');
        return $beans ?? [];
    }
}
