<?php

class ESListACLHelper
{
    protected $db;

    public function __construct()
    {
        $this->db = DBManagerFactory::getInstance();
    }
    
    public function doesModuleUseTemplate(string $module, string $template)
    {
        $bean = BeanFactory::newBean($module);
        $module_templates = $GLOBALS["dictionary"][$bean->object_name]["templates"];
        return isset($module_templates[$template]);
    }

    public function doesModuleUseEmployeeRelationship(string $module)
    {
        include 'modules/Employees/access_config.php';
        $bean = BeanFactory::newBean($module);
        return $this->doesModuleUseTemplate($module, 'employee_related')
            && !in_array($bean->module_dir, $employee_related_exclude_modules);
    }

    public function getSecurityGroupIdsRelatedWithRecord(string $module, string $record_id): array
    {
        $module = $this->db->quote($module);
        $record_id = $this->db->quote($record_id);

        $result = $this->db->query("SELECT secg.id AS group_id
           FROM securitygroups secg
           INNER JOIN securitygroups_records secr
              ON secg.id = secr.securitygroup_id
              AND secr.deleted = 0
              AND secr.module = '{$module}'
              AND secg.deleted = 0
           WHERE secr.record_id = '{$record_id}'
        ");
        $group_ids = [];
        while ($row = $this->db->fetchByAssoc($result)) {
            $group_ids[] = $row['group_id'];
        }

        return $group_ids;
    }

    public function getSecurityGroupIdsRelatedWithMultipleRecords(string $module, array $beans): array
    {
        $ids = array_map(function ($bean) { return $bean->id; }, $beans);
        $ids_encoded = "'" . implode("','", $ids) . "'";
        $result = $this->db->query("SELECT secr.record_id AS record_id, secg.id AS group_id
            FROM securitygroups secg
            INNER JOIN securitygroups_records secr
               ON secg.id = secr.securitygroup_id
               AND secr.deleted = 0
               AND secr.module = '{$module}'
               AND secg.deleted = 0
            WHERE secr.record_id IN ({$ids_encoded})
        ");

        $groups = [];
        while ($row = $this->db->fetchByAssoc($result)) {
            $record_id = $row['record_id'];
            $group_id = $row['group_id'];

            if (empty($groups[$record_id])) {
                $groups[$record_id] = [];
            }
            $groups[$record_id][] = $group_id;
        }

        return $groups;
    }
}
