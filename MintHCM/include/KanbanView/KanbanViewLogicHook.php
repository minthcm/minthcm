<?php

require_once 'include/KanbanView/KanbanViewController.php';

class KanbanViewLogicHook
{

    public function beforeSave($bean)
    {
        $KVC = (new KanbanViewController($bean));
        if(!isset($bean->from_kanban) && $KVC->shouldReorder()) {
            $KVC->setOrderFieldNull();
        }
    }

    public function afterSave($bean)
    {
        $KVC = (new KanbanViewController($bean));
        if($KVC->shouldReorder()) {
            $KVC->reorder();
        }
    }

    public function afterDelete($bean)
    {
        (new KanbanViewController($bean))->reorder();
    }
}
