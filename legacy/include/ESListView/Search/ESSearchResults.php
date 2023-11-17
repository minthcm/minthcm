<?php

class ESSearchResults extends \SuiteCRM\Search\SearchResults {

    protected $hits_after_acl = [];

    /**
     * Fetches the results (originally just module->id) as Beans.
     *
     * @see getHits()
     * @return array
     */
    public function getHitsAsBeans(): array  {
        $hits = $this->getHits();
        $parsed = [];

        foreach ($hits as $module => $beans) {
            foreach ((array) $beans as $bean) {
                $obj = BeanFactory::getBean($module, $bean);

                // if a search found a bean but MintHCM does not, it could happens
                // maybe the bean is deleted but elsasticsearch is not re-indexing yet.
                // so at this point we trying to rebuild the index and try again to get bean:
                if (!$obj) {
                    SuiteCRM\Search\ElasticSearch\ElasticSearchIndexer::repairElasticsearchIndex();
                    $obj = BeanFactory::getBean($module, $bean);
                }

                if (!$obj) { // record is probably deleted but still indexed
                    $parsed[$module][] = false;
                    continue; 
                }
                $obj->load_relationships();

                if (!$this->handleOwner($obj)) {
                    $parsed[$module][] = false;
                    continue;
                }
                $fieldDefs = $obj->getFieldDefinitions();
                $objUpdatedLinks = $this->updateFieldDefLinks($obj, $fieldDefs);
                $objDecodedEntities = $this->decodeEntities($objUpdatedLinks, $fieldDefs);

                $parsed[$module][] = $objDecodedEntities;
            }
            $parsed[$module] = $this->handleSG($parsed[$module], $module);
        }
        $this->hits_after_acl = $parsed;
        $this->addACLAccessInfo();
        return $parsed;
    }
        
    protected function addACLAccessInfo(){
        foreach ($this->hits_after_acl as $module => $beans) {
            foreach ((array) $beans as $bean) {
                if ($bean) {
                    $bean->acl_access = [
                        'edit' => $bean->ACLAccess('edit'),
                        'view' => $bean->ACLAccess('view'),
                        'delete' => $bean->ACLAccess('delete')
                    ];
                }
            }
        }
    }

    public function getTotal():  ?int{
        $total = 0;
        foreach ($this->hits_after_acl as $module_name => $beans) {
            $total += count($beans);
        }
        return $total;
    }

    protected function handleOwner($obj) {
        global $current_user;
        if ($obj->bean_implements('ACL') && ACLController::requireOwner($obj->module_dir, 'list')) {
            $is_owner = $obj->isOwner($current_user->id);
            if (!$is_owner) {
                return false;
            }
        }
        return true;
    }
    
    protected function handleSG($beans, $module) {
        if (count($beans) > 0) {
            $obj = BeanFactory::newBean($module);
            if ($obj->bean_implements('ACL') && ACLController::requireSecurityGroup($obj->module_dir, 'list')) {
                require_once('modules/SecurityGroups/SecurityGroup.php');
                global $current_user;
                $owner_where = $obj->getOwnerWhere($current_user->id);
                $group_where = SecurityGroup::getGroupWhere($obj->table_name, $obj->module_dir, $current_user->id);
                if (!empty($owner_where)) {
                    if (empty($where)) {
                        $where = " (" . $owner_where . " or " . $group_where . ") ";
                    } else {
                        $where .= " AND (" . $owner_where . " or " . $group_where . ") ";
                    }
                } else {
                    $where .= ' AND ' . $group_where;
                }
                $limited_ids = [];
                foreach($beans as $bean){
                    $limited_ids[] = $bean->id;
                }
                $sql = "SELECT id FROM {$obj->table_name} WHERE {$where} AND {$obj->table_name}.id IN ('". implode("','", $limited_ids)."')";
                global $db;
                $query_result = $db->query($sql);
                $new_beans_array = [];
                $access_id = [];
                if ($query_result) {
                    while ($row = $db->fetchByAssoc($query_result)) {
                        $access_id[] = $row['id'];
                    }
                }
                foreach($beans as $bean){
                    if(in_array($bean->id, $access_id)){
                        $new_beans_array[] = $bean;
                    } else {
                        $new_beans_array[] = false;
                    }
                }
                return $new_beans_array;
            }
        }
        return $beans;
    }
    protected function updateObjLinks(SugarBean $obj, $fieldDef): SugarBean
    {
        if ($fieldDef['type'] == 'relate' && isset($fieldDef['link']) && isset($fieldDef['id_name']) && $fieldDef['id_name']) {
            $relId = $this->getRelatedId($obj, $fieldDef['id_name'], $fieldDef['link']);
            $obj->{$fieldDef['name']."_link"} = $this->getHrefLink($obj->{$fieldDef['name']}, $fieldDef['module'], $relId, 'DetailView');
        } elseif ($fieldDef['name'] == 'name') {
            $obj->{$fieldDef['name']."_link"} = $this->getHrefLink($obj->{$fieldDef['name']}, $obj->module_name, $obj->id, 'DetailView');
        }
        return $obj;
    }

    protected function getHrefLink($label, $module, $record, $action)
    {
        $link = "index.php?action={$action}&module={$module}&record={$record}";
        return $link;
    }

    protected function decodeEntities(SugarBean $obj, $fieldDefs)
    {
        foreach ($fieldDefs as &$fieldDef) {
            if (isset($fieldDef['type']) && in_array($fieldDef['type'], ['name', 'varchar'])) {
                $field = $fieldDef['name'];
                $obj->$field = html_entity_decode($obj->$field, ENT_QUOTES);
            }
        }

        return $obj;
    }
}
