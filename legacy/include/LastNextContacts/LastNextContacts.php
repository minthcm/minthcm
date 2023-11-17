<?php

require_once 'include/LastNextContacts/LastNextContactsQueue.php';
require_once 'include/LastNextContacts/LastNextContactsDeadCode.php';

require_once 'include/LastNextContacts/LastNextContactsQueries.php';
require_once 'include/LastNextContacts/LastNextContactsEmail.php';
require_once 'include/LastNextContacts/LastNextContactsConfig.php';

class LastNextContacts extends LastNextContactsDeadCode
{
    protected $tmp_beans = array();

    // protected $debug = 'fatal';
    public $debug = false;

    public function updateLastNextContactDate($bean, $related_beans)
    {
        $this->tmp_beans = array();
        if (
            in_array($bean->module_name, LastNextContactsConfig::get('last_next_activities_std_modules')) 
        && 'Emails' != $bean->module_name
        ) {
            $this->log(print_r([basename(__FILE__), __METHOD__, __FUNCTION__, __LINE__, $bean->module_name], 1));

            $bean->load_relationship('candidates');
            if (isset($bean->candidates)) {
                $beans = $bean->candidates->getBeans();
                if (count($beans) > 0) {
                    $this->getCandidates($beans);
                }
            }

            $this->addBeansFromRelatedModule($related_beans);
            $this->addParentBean($bean);
            if (property_exists($bean, 'contacts_arr') && empty($bean->contacts_arr) && $bean->load_relationship('contacts')) {
                $bean->contacts_arr = $bean->contacts->get();
            }
            $this->addRelatedBeans($bean->contacts_arr, 'Contacts');
            if (property_exists($bean, 'leads_arr') && empty($bean->leads_arr) && $bean->load_relationship('leads')) {
                $bean->leads_arr = $bean->leads->get();
            }
            $this->addRelatedBeans($bean->leads_arr, 'Leads');

        } else if ('Emails' == $bean->module_name) {
            $this->tmp_beans = $this->getLNCEmail()->getEmailRelatedBeans($bean, $this->tmp_beans);
        }

        foreach ($this->tmp_beans as $rBean) {
            if (in_array($rBean->module_name, LastNextContactsConfig::get('last_next_modules'))) {
                $this->updateLastNextDatesInBean($rBean);
            }
        }
    }

    public function updateBean($arguments)
    {
        if (
            isset($arguments['related_module']) 
        && in_array($arguments['related_module'], LastNextContactsConfig::get('last_next_modules'))
        ) {
            $bean_to_update = $this->getBean($arguments['related_module'], $arguments['related_id']);
            $bean_to_update->retrieve($bean_to_update->id);
            $this->updateLastNextDatesInBean($bean_to_update);
            if ('Contacts' == $bean_to_update->module_name && strlen($bean_to_update->account_id) > 0) {
                $this->updateCandidate($bean_to_update->account_id);
            }
        }
    }

    protected function updateCandidate($id)
    {
        $account_bean = $this->getBean('Candidates', $id);
        $account_bean->retrieve($id);
        if (null !== $account_bean) {
            $this->updateLastNextDatesInBean($account_bean);
        }
    }

    protected function getCandidates($candidates)
    {
        if (is_array($candidates)) {
            foreach ($candidates as $a) {
                $this->setTmpBean($a);
            }
        }
    }

    protected function setTmpBean($bean)
    {
        if (!isset($this->tmp_beans[$bean->id])) {
            $this->tmp_beans[$bean->id] = $bean;
        }
    }

    protected function addBeansFromRelatedModule($related_beans)
    {
        foreach ($related_beans as $related_bean) {
            $this->setTmpBean($related_bean);
        }
    }

    protected function getParentFromMainRelation($bean)
    {
        if (isset($bean->parent_id) && strlen($bean->parent_id) > 0) {
            $parent_bean = $this->getBean($bean->parent_type, $bean->parent_id);
            $parent_bean->retrieve($parent_bean->id);
            if ($parent_bean->id) {
                $this->setTmpBean($parent_bean);
            }
        }
    }

    protected function getParentFromRelation($bean)
    {
        if (!empty($bean->new_rel_relname) && !empty($bean->new_rel_id)) {
            $parent_bean = $this->getBean(ucfirst($bean->new_rel_relname), $bean->new_rel_id);
            $parent_bean->retrieve($parent_bean->id);
            if (null !== $parent_bean) {
                $this->setTmpBean($parent_bean);
            }
        }
    }

    protected function addParentBean($bean)
    {
        $this->getParentFromMainRelation($bean);
        $this->getParentFromRelation($bean);
    }

    protected function addRelatedBeans($ids, $module_name)
    {
        if (!empty($ids) && is_array($ids)) {
            foreach ($ids as $id) {
                $bean = $this->getBean($module_name, $id);
                $bean->retrieve($bean->id);
                $this->setTmpBean($bean);
                if (strlen($bean->account_id) > 0) {
                    $account_bean = $this->getBean('Candidates', $bean->account_id);
                    $account_bean->retrieve($account_bean->id);
                    if (null !== $account_bean) {
                        $this->setTmpBean($account_bean);
                    }
                }
            }
        }
    }

    protected function updateLastNextDatesInBean($bean)
    {
        $last_time_contact = $this->findLastContactDate($bean);
        $date_planned_contact = $this->findNextContactDate($bean);

        $last_time_contact_where = $this->getUpdatePart('last_time_contact', $last_time_contact);
        $date_planned_contact_where = $this->getUpdatePart('date_planned_contact', $date_planned_contact);
        if ($this->needUpdateLastNextDatesInBean($bean, $last_time_contact_where, $date_planned_contact_where)) {
            $bean->last_time_contact = (null == $last_time_contact) ? '' : $last_time_contact;
            $bean->date_planned_contact = (null == $date_planned_contact ? '' : $date_planned_contact);
            $bean->save();
        }
    }

    protected function needUpdateLastNextDatesInBean($bean, $last_time_contact, $date_planned_contact)
    {
        $db = $this->getDB();
        $p1 = $this->eqNullToIsNull($last_time_contact);
        $p2 = $this->eqNullToIsNull($date_planned_contact);
        $query = "SELECT if({$p1} AND {$p2},0,1) FROM {$bean->table_name} WHERE id='{$bean->id}'";
        return $db->getOne($query);
    }

    protected function eqNullToIsNull($subject)
    {
        return str_replace(" = null", " is NULL ", $subject);
    }

    protected function getUpdatePart($column_name, $value)
    {
        if (null === $value) {
            $update_part = " $column_name = null";
        } else {
            $update_part = " $column_name = '{$value}' ";
        }

        return $update_part;
    }

    protected function unsetInvalidValues($array)
    {
        foreach ($array as $key => $value) {
            if (null === $value or false === $value) {
                unset($array[$key]);
            }
        }

        return $array;
    }

    protected function findLastContactDate($bean)
    {
        $dates[] = $this->findLastContactDateEmails($bean);
        $dates[] = $this->findContactDateActivity($bean, 'calls', 'call', 'MAX');
        $dates[] = $this->findContactDateActivity($bean, 'meetings', 'meeting', 'MAX');
        $dates = $this->unsetInvalidValues($dates);

        sort($dates);
        return count($dates) > 0 ? $dates[count($dates) - 1] : null;
    }

    protected function getFirstEmailFromBean($bean)
    {
        $email = false;
        if (
            isset($bean->emailAddress) && isset($bean->emailAddress->addresses) && isset($bean->emailAddress->addresses[0])
            && !empty($bean->emailAddress->addresses[0]['email_address'])
        ) {
            $email = $bean->emailAddress->addresses[0]['email_address'];
        }
        return $email;
    }

    protected function findLastContactDateEmails($bean)
    {
        $emails = [];
        $email = $this->getFirstEmailFromBean($bean);
        if ($email) {
            $emails[] = $email;
        }
        if ('Candidates' == $bean->module_name) {
            if ($bean->load_relationship('contacts')) {
                $beans = $bean->contacts->getBeans();
                foreach ($beans as $b) {
                    $email = $this->getFirstEmailFromBean($b);
                    if ($email) {
                        $emails[] = $email;
                    }
                }
            }
        }
        $sql = $this->getLNCQueries()->Emails($bean->id, $bean->module_name, $emails);
        $db = $this->getDB();
        return $db->getOne($sql);
    }

    protected function findContactDateActivity($bean, $activities, $activity, $func)
    {
        $sql = '';
        $db = $this->getDB();

        if ('MAX' == $func) {
            $status = 'Held';
            $sort = 'DESC';
        } else {
            $status = 'Planned';
            $sort = 'ASC';
        }

        switch ($bean->module_name) {
            // fabryka
            case 'Candidates':
                $sql = $this->getLNCQueries()->Candidates($status, $bean->id, $activity, $activities, $sort);
                break;
        }
        $this->log(print_r([$bean->id, $sql, basename(__FILE__), __METHOD__, __FUNCTION__], 1));
        return $db->getOne($sql);
    }

    protected function findNextContactDate($bean)
    {
        $dates = array();
        $dates[] = $this->findContactDateActivity($bean, 'calls', 'call', 'MIN');
        $dates[] = $this->findContactDateActivity($bean, 'meetings', 'meeting', 'MIN');

        $dates = $this->unsetInvalidValues($dates);

        sort($dates);
        return count($dates) > 0 ? $dates[0] : null;
    }
    protected function getLNCQueries()
    {
        return new LastNextContactsQueries;
    }
    protected function getLNCEmail()
    {
        return new LastNextContactsEmail;
    }
    protected function getBean($module, $id)
    {
        return BeanFactory::getBean($module, $id);
    }
}
