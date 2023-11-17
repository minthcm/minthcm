<?php

require_once 'include/SugarQueue/SugarJobQueue.php';
require_once 'modules/SchedulersJobs/SchedulersJob.php';
require_once 'include/LastNextContacts/LastNextContacts.php';
require_once 'include/LastNextContacts/LastNextContactsConfig.php';

class LastNextContactsQueue
{
    const STATUS_WAITING = 0;
    const STATUS_IN_PROCESS = 1;
    const STATUS_PROCESSED = 2;
    const STATUS_ERROR = 3;
    const TYPE_RELATIONS = 'relations';
    const TYPE_BEAN = 'bean';

    protected $table_name = 'last_next_contact_queue';
    protected $target = 'class::LastNextContactsQueueJob';
    protected $job_name = 'Process Last Next Contact Queue';
    protected $job_execution_delay = 1;
    protected $process_records_limit = 50;

    public function __construct()
    {
        $job_execution_delay = 1;
        $process_records_limit = 50;
        $conf_path = LastNextContactsConfig::getConfigPath();
        include $conf_path;

        if (!empty((int) $job_execution_delay)) {
            $this->job_execution_delay = (int) $job_execution_delay;
        }
        if (!empty((int) $process_records_limit)) {
            $this->process_records_limit = (int) $process_records_limit;
        }
    }

    public function process()
    {
        $records = $this->getRecords();
        foreach ($records as $record) {
            $this->setRecordInProcess($record['id']);
            try {
                $this->processRecord($record);
                $this->setRecordProcessed($record['id']);
            } catch (Exception $e) {
                $this->setRecordError($record['id'], $e->getMessage());
            }
        }
        if (!$this->isQueueEmpty()) {
            $this->createJob();
        }
    }

    protected function processRecord($record)
    {
        switch ($record['type']) {
            case self::TYPE_RELATIONS:
                $bean = BeanFactory::getBean($record['related_module'], $record['related_id']);
                $related_modules = json_decode(html_entity_decode($record['related_modules'], ENT_QUOTES), true);
                $related_beans = array();
                if (!empty($related_modules)) {
                    foreach ($related_modules as $related_module) {
                        $related_beans[] = BeanFactory::getBean($related_module['bean_module_name'], $related_module['bean_id']);
                    }
                }
                $last_next_contacts = new LastNextContacts();
                $last_next_contacts->updateLastNextContactDate($bean, $related_beans);
                break;
            case self::TYPE_BEAN:
                $last_next_contacts = new LastNextContacts();
                $last_next_contacts->updateBean($record);
                break;
            default:
                throw new Exception('LastNextContactsQueue::processRecord: Unrecognized type');
        }
    }

    public function createJobRelations($arguments)
    {
        $data = array(
            'related_module' => $arguments['module'],
            'related_id' => $arguments['id'],
            'related_modules' => array(
                array(
                    'bean_module_name' => $arguments['related_module'],
                    'bean_id' => $arguments['related_id'],
                ),
            ),
        );
        $this->createJob($data, self::TYPE_RELATIONS);
    }
    public function createJobBeanReleted($arguments)
    {
        $this->createJob($arguments, self::TYPE_RELATIONS);
    }

    public function createJobBean($arguments)
    {
        $this->createJob($arguments, self::TYPE_BEAN);
    }

    protected function createJob($arguments = array(), $type = '')
    {
        if (!empty($arguments) && !empty($type)) {
            $this->insertRecord($arguments, $type);
        }

        if (!$this->isJobExist()) {
            $datetime = new SugarDateTime();
            $datetime->modify('+' . $this->job_execution_delay . ' minutes');
            $job = new SchedulersJob();
            $job->name = $this->job_name;
            $job->data = '';
            $job->target = $this->target;
            $job->assigned_user_id = 1;
            $job->execute_time = $datetime->asDb();
            $job_queue = new SugarJobQueue();
            $job_queue->submitJob($job);
        }
    }

    protected function isJobExist()
    {
        $db = DBManagerFactory::getInstance();
        $sql = "
            SELECT
                id
            FROM
                job_queue
            WHERE
                name = '{$this->job_name}'
                AND target = '{$this->target}'
                AND status = 'queued'
                AND resolution = 'queued'
                AND deleted = '0'
        ";
        return $db->getOne($sql);
    }

    protected function insertRecord($data, $type)
    {
        $db = DBManagerFactory::getInstance();
        $related_modules = json_encode($data['related_modules']);
        $related_modules = $related_modules == 'null' ? '' : $related_modules;
        $query = "
            INSERT INTO
                {$this->table_name}
                (
                    id
                    , date_entered
                    , date_modified
                    , status
                    , type
                    , related_module
                    , related_id
                    , related_modules
                ) VALUES (
                    UUID()
                    , NOW()
                    , NOW()
                    , '" . self::STATUS_WAITING . "'
                    , '{$type}'
                    , '{$data['related_module']}'
                    , '{$data['related_id']}'
                    , '{$related_modules}'
                )
        ";
        $db->query($query);
    }

    protected function isQueueEmpty()
    {
        $db = DBManagerFactory::getInstance();
        $sql = "
            SELECT
                id
            FROM
                {$this->table_name}
            WHERE
                status = '" . self::STATUS_WAITING . "'
        ";
        $result = $db->getOne($sql);
        return empty($result);
    }

    protected function getRecords()
    {
        $db = DBManagerFactory::getInstance();
        $sql = "
            SELECT
                id
                , type
                , related_module
                , related_id
                , related_modules
            FROM
                {$this->table_name}
            WHERE
                status = '" . self::STATUS_WAITING . "'
            ORDER BY
                date_entered ASC
            LIMIT {$this->process_records_limit}
        ";
        $query = $db->query($sql);
        $result = array();
        while ($row = $db->fetchByAssoc($query)) {
            $result[] = $row;
        }
        return $result;
    }

    protected function setRecordStatus($queue_record_id, $status, $error_message = '')
    {
        $db = DBManagerFactory::getInstance();
        if (!empty($queue_record_id) && isset($status)) {
            $query = "
                UPDATE
                    {$this->table_name}
                SET
                    date_modified = NOW()
                    , status = '{$status}'
            ";
            if (!empty($error_message)) {
                $query .= "
                    , error_message = '{$error_message}'
                ";
            }
            $query .= "
                WHERE
                    id = '{$queue_record_id}'
                LIMIT 1
            ";
        }
        $db->query($query);
    }

    protected function setRecordInProcess($queue_record_id = '')
    {
        $this->setRecordStatus($queue_record_id, self::STATUS_IN_PROCESS);
    }

    protected function setRecordProcessed($queue_record_id = '')
    {
        $this->setRecordStatus($queue_record_id, self::STATUS_PROCESSED);
    }

    protected function setRecordError($queue_record_id = '', $error_message = '')
    {
        $this->setRecordStatus($queue_record_id, self::STATUS_ERROR, $error_message);
    }
}
