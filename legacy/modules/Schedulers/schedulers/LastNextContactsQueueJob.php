<?php

class LastNextContactsQueueJob implements RunnableSchedulerJob
{

    public function setJob(SchedulersJob $job)
    {
        $this->job = $job;
    }

    public function run($data)
    {
        require_once 'include/LastNextContacts/LastNextContacts.php';
        $last_next_contacts_queue = new LastNextContactsQueue();
        $last_next_contacts_queue->process();
        return true;
    }
}
