<?php

require_once 'include/LastNextContacts/LastNextContactsConfig.php';
require_once 'include/LastNextContacts/LastNextContactsBase.php';

class LastNextContactsQueries extends LastNextContactsBase
{
    protected $date_sent_field;
    public function __construct()
    {
        parent::__construct();
        $this->date_sent_field = $this->getDateSentFieldName();
    }

    private function getDateSentFieldName()
    {
        if (!isset($this->date_sent_field)) {
            global $dictionary;
            $email = BeanFactory::getBean('Emails');
            if (isset($email->field_defs['date_sent_received'])) {
                $this->date_sent_field = 'date_sent_received';
            } elseif (isset($email->field_defs['date_sent'])) {
                $this->date_sent_field = 'date_sent';
            }
        }
        return $this->date_sent_field;
    }

    protected function addUserfilter($parent, $alias, $id)
    {
        $sql= "";
        if (LastNextContactsConfig::get('only_owner_activities_enabled')) {
            $sql= " AND {$alias}.assigned_user_id IN (" .
                "SELECT assigned_user_id FROM {$parent} WHERE id='{$id}'  " .
                # "UNION SELECT after_value_string FROM {$parent}_audit WHERE parent_id = '{$id}' AND field_name = 'assigned_user_id'".
                ")";
        }
        $this->log(__FILE__.":".__FUNCTION__."//".$sql);
        return $sql;
    }
    public function Candidates($status, $beanId, $activity, $activities, $sort)
    {
        $sql = "SELECT date_start FROM (
            SELECT a.date_start
               FROM {$activities} AS a
               WHERE
                  a.parent_type='Candidates'
                  AND a.parent_id='{$beanId}'
                  AND a.deleted=0
                  AND a.status='{$status}'
                {$this->addUserfilter('candidates', 'a', $beanId)}
            UNION
               SELECT a2.date_start
               FROM {$activities} AS a2
                  INNER JOIN {$activities}_candidates AS cl ON a2.id=cl.{$activity}_id
               WHERE
                  cl.candidate_id='{$beanId}'
                  AND cl.deleted=0
                  AND a2.status='{$status}'
                  AND a2.deleted=0
              {$this->addUserfilter('candidates', 'a2', $beanId)}
            ) AS tmp
            ORDER BY date_start " . $sort;
            $this->log(__FILE__.":".__FUNCTION__."//".$sql);
        return $sql;

    }
    public function Emails($beanId, $moduleName, $emails)
    {
        $sql = "SELECT MAX( {$this->date_sent_field} )
      FROM emails e
      WHERE
         e.deleted = 0
         AND e.status!='draft'
         AND id IN ( SELECT email_id FROM emails_beans eb
            WHERE eb.deleted = 0 AND (
               ( eb.bean_module = '{$moduleName}'
               AND eb.bean_id='{$beanId}' )";
        $sql .= ")";
        if (!empty($emails)) {
            $email_list = implode("', '", $emails);
            $sql .= "
         UNION SELECT email_id
            FROM emails_email_addr_rel er
            LEFT JOIN email_addresses ea ON ea.id = er.email_address_id
            WHERE
               email_address IN ('{$email_list}')
               AND ea.deleted=0
               AND er.deleted=0 ";
        }
        $sql .= ")
        {$this->addUserfilter(strtolower($moduleName), 'e', $beanId)}";
        $this->log(__FILE__.":".__FUNCTION__."//".$sql);
        return $sql;

    }
}
