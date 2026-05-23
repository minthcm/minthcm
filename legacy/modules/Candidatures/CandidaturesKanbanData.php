<?php

require_once 'include/KanbanView/ModuleKanbanViewController.php';
class CandidaturesKanbanData implements ModuleKanbanViewController
{

    private $project_status_list_kanban_order = array(
        'open',
        'plan',
        'for_approval',
        'close',
    );

    public function getAdditionalKanbanData($items)
    {
        $recruitments_ids = [];
        foreach (array_merge(...array_values($items)) as $item) {
            $recruitments_ids[] = $item['recruitment_id'];
        }
        $project_status_list_order = "'" . implode("','", $this->project_status_list_kanban_order) . "'";
        $db = DBManagerFactory::getInstance();
        $result = $db->query("SELECT
                                id, name, FIELD(project_status, {$project_status_list_order}) as project_status_order
                            FROM
                                recruitments
                            WHERE
                                deleted = 0
                                AND id IN ('" . implode("','", $recruitments_ids) . "')
                            ORDER BY
                                project_status_order ASC,
                                start_date DESC"
        );
        while ($row = $db->fetchByAssoc($result)) {
            $items['recruitments'][] = [
                'id' => $row['id'],
                'name' => $row['name'],
            ];
        }
        return $items;
    }

}
