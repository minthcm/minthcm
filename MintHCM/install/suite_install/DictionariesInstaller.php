<?php

class DictionariesInstaller
{
    public function run()
    {
        $db = DBManagerFactory::getInstance();
        $dictionaries_data = $this->getDictionariesData();
        $query = "
        INSERT IGNORE INTO `dictionaries` (`id`, `name`, `date_entered`, `date_modified`, `modified_user_id`, `created_by`, `description`, `deleted`, `list_type`, `list_module`, `is_active`)
        VALUES " . $dictionaries_data;
        $db->query($query);
    }

    protected function getDictionariesData()
    {
        $dictionaries_data = "('12dc8378-2c54-575e-9d6e-5ea6c383165b', 'Internal Meeting', '2020-04-27 11:36:32', '2020-04-27 11:36:32', '1', '1', NULL, 0, 'Meetings-type', 'Meetings', 1),
        ('2860a67d-06f1-61be-9927-5ea6c3dc3738', 'Other', '2020-04-27 11:37:13', '2020-04-27 11:37:13', '1', '1', NULL, 0, 'Meetings-type', 'Meetings', 1),
        ('30e50be6-9b31-dfa8-e17d-5ea6c4c60fe8', 'Operations Work', '2020-04-27 11:38:13', '2020-04-27 11:38:13', '1', '1', NULL, 0, 'SpentTime-category', 'SpentTime', 1),
        ('7c337ff9-3f44-f58c-7467-5ea6c48426a9', 'Project Work', '2020-04-27 11:37:58', '2020-04-27 11:37:58', '1', '1', NULL, 0, 'SpentTime-category', 'SpentTime', 1),
        ('8cf2dae3-6ecf-609c-5025-5ea6c3af35bf', 'Sales Meeting', '2020-04-27 11:36:15', '2020-04-27 11:36:15', '1', '1', NULL, 0, 'Meetings-type', 'Meetings', 1),
        ('9d30cd3b-0955-c994-9c4e-5ea6c3db03e0', 'Project Meeting', '2020-04-27 11:36:49', '2020-04-27 11:36:49', '1', '1', NULL, 0, 'Meetings-type', 'Meetings', 1),
        ('f03c622c-7878-39eb-c7d4-5ea6c367d07a', 'Management', '2020-04-27 11:37:30', '2020-04-27 11:37:30', '1', '1', NULL, 0, 'SpentTime-category', 'SpentTime', 1);";
        return $dictionaries_data;
    }
}
