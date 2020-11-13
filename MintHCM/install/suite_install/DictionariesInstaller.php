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
        ('f03c622c-7878-39eb-c7d4-5ea6c367d07a', 'Management', '2020-04-27 11:37:30', '2020-04-27 11:37:30', '1', '1', NULL, 0, 'SpentTime-category', 'SpentTime', 1),
        ('101c7a1d-68ac-abd4-5b92-5eb17dda1459', 'Other', '2020-05-05 14:49:57', '2020-05-05 14:49:57', '1', '1', NULL, 0,  'Candidatures-source', 'Candidatures', 1),
        ('2c05351d-70ab-a454-2f4a-5eb17ccf32e2', 'Company website', '2020-05-05 14:48:13', '2020-05-05 14:48:13', '1', '1', NULL, 0,  'Candidatures-source', 'Candidatures', 1),
        ('5431dc21-43bb-dccd-a6f8-5eb17c1a0c13', 'Job fairs', '2020-05-05 14:48:56', '2020-05-05 14:48:56', '1', '1', NULL, 0,  'Candidatures-source', 'Candidatures', 1),
        ('6569fd91-e751-e8cc-6b2d-5eb17c858eb8', 'LinkedIn', '2020-05-05 14:49:08', '2020-05-05 14:49:08', '1', '1', NULL, 0,  'Candidatures-source', 'Candidatures', 1),
        ('854006d0-e9b9-bdff-f04c-5eb17d40ac53', 'Indeed', '2020-05-05 14:49:38', '2020-05-05 14:49:38', '1', '1', NULL, 0,  'Candidatures-source', 'Candidatures', 1),
        ('c4ba0266-f0ab-8540-d543-5eb17ce41615', 'Careers Office', '2020-05-05 14:48:39', '2020-05-05 14:48:39', '1', '1', NULL, 0,  'Candidatures-source', 'Candidatures', 1),
        ('d7e51783-6f9a-a91b-aec0-5eb17cf093d0', 'Facebook', '2020-05-05 14:49:22', '2020-05-05 14:49:22', '1', '1', NULL, 0,  'Candidatures-source', 'Candidatures', 1),
        ('f30e8596-0769-42e9-92c8-c7d4710123f0', 'RocketJobs', '2020-05-05 14:49:22', '2020-05-05 14:49:22', '1', '1', NULL, 0,  'Candidatures-source', 'Candidatures', 1),
        ('681ce4fe-2e48-f092-9077-5f7af730b9a6', 'Mail Merge', '2020-10-05 10:36:32', '2020-10-05 10:36:32', '1', '1', 'NULL', '0', 'Documents-type', 'Documents','1'),
        ('590a8602-ce79-d8b6-653d-5f7afb9dd592', 'EULA', '2020-10-05 10:56:34', '2020-10-05 10:56:34', '1', '1', 'NULL', '0', 'Documents-type', 'Documents','1'),
        ('9527717f-64c0-06a0-3610-5f7afb8db76f', 'NDA', '2020-10-05 10:56:48', '2020-10-05 10:56:48', '1', '1', 'NULL', '0', 'Documents-type', 'Documents','1'),
        ('f27326ff-8cc3-28a5-395f-5f7afb4f57ae', 'License Agreement', '2020-10-05 10:56:59', '2020-10-05 10:56:59', '1', '1', 'NULL', '0', 'Documents-type', 'Documents','1'),
        ('e2324f8d-0a14-8ec3-164f-5eb17c3b28b3', 'Recommendation', '2020-05-05 14:47:57', '2020-05-05 14:47:57', '1', '1', NULL, 0,  'Candidatures-source', 'Candidatures', 1);";
        return $dictionaries_data;
    }
}
