<?php

require_once 'include/LastNextContacts/LastNextContactsBase.php';




class LastNextContactsEmail extends LastNextContactsBase
{
    public function getEmailRelatedBeans($bean, $tmp_beans)
    {
        $this->tmp_beans = $tmp_beans;

        $result = $beans_with_same_email = array();
        if ('Emails' == $bean->module_name) {

            foreach (LastNextContactsConfig::get('last_next_modules') as $lnm) {
                $rel_name = LastNextContactsConfig::get('relationship_map', $lnm);
                if ($bean->load_relationship($rel_name)) {
                    $this->tmp_beans = $bean->$rel_name->getBeans();
                    foreach ($this->tmp_beans as $rBean) {
                        $result[$rBean->id] = $rBean;
                    }
                }
            }
            $matches = array();
            if (preg_match_all('/[a-zA-Z0-9\.\_\-]+@[a-zA-Z0-9\.\_\-]+/', $bean->from_addr, $matches)) {
                $beans_with_same_email = $this->getRecordsWithEmail($matches[0], $beans_with_same_email);
            }
            if (preg_match_all('/[a-zA-Z0-9\.\_\-]+@[a-zA-Z0-9\.\_\-]+/', $bean->cc_addrs, $matches)) {
                $beans_with_same_email = $this->getRecordsWithEmail($matches[0], $beans_with_same_email);
            }
            if (preg_match_all('/[a-zA-Z0-9\.\_\-]+@[a-zA-Z0-9\.\_\-]+/', $bean->to_addrs, $matches)) {
                $beans_with_same_email = $this->getRecordsWithEmail($matches[0], $beans_with_same_email);
            }
        } else {
            if (isset($bean->emailAddress->addresses[0]['email_address'])) {
                foreach ($bean->emailAddress->addresses as $addr) {
                    $addresses[] = $addr['email_address'];
                }
                $beans_with_same_email = $this->getRecordsWithEmail($addresses);
            }

            if ('Candidates' == $bean->module_name) {
                $beans_with_same_email[] = array(
                    'module_name' => 'Candidates',
                    'id' => $bean->id,
                );
            }
        }
        foreach ($beans_with_same_email as $b) {
            if (!isset($result[$b['id']])) {
                $result[$b['id']] = BeanFactory::getBean($b['module_name'], $b['id']);
            }
        }
        return $result;
    }

    protected function getRecordsWithEmail($emails, $result = array())
    {
        $db = $this->getDB();

        $email_list = strtoupper(implode("', '", $emails));

        $sql = "SELECT er.bean_id, er.bean_module
              FROM email_addr_bean_rel er
              LEFT JOIN email_addresses ea ON ea.id = er.email_address_id
              WHERE email_address_caps IN ('{$email_list}')
              AND er.deleted = 0 AND ea.deleted = 0
              GROUP BY bean_id, bean_module";

        $query_result = $db->query($sql);

        while ($row = $db->fetchByAssoc($query_result)) {
            if ('Contacts' == $row['bean_module']) {
                $bean = BeanFactory::getBean('Contacts', $row['bean_id']);
                $bean->retrieve($bean->id);
                if ($bean->load_relationship('candidates')) {
                    $candidates = $bean->candidates->getBeans();
                    foreach ($candidates as $a) {
                        $result['Candidates' . $a->id] = array(
                            'module_name' => 'Candidates',
                            'id' => $a->id,
                        );
                    }
                }
            }

            $result[$row['bean_module'] . $row['bean_id']] = array(
                'module_name' => $row['bean_module'],
                'id' => $row['bean_id'],
            );
        }

        $this->log(print_r([$result, basename(__FILE__), __METHOD__, __FUNCTION__], 1));
        return $result;
    }

}
