<?php


/**
 *
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by SalesAgility Ltd.
 * Copyright (C) 2011 - 2018 SalesAgility Ltd.
 *
 * MintHCM is a Human Capital Management software based on SuiteCRM developed by MintHCM, 
 * Copyright (C) 2018-2023 MintHCM
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by SugarCRM" 
 * logo and "Supercharged by SuiteCRM" logo and "Reinvented by MintHCM" logo. 
 * If the display of the logos is not reasonably feasible for technical reasons, the 
 * Appropriate Legal Notices must display the words "Powered by SugarCRM" and 
 * "Supercharged by SuiteCRM" and "Reinvented by MintHCM".
 */

namespace MintHCM\Api\Repositories;

use ACLController;

class SchedulerRepository
{
    /** @var \Doctrine\DBAL\Connection */
    private $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function getData($date_from, $date_to, $parent, $participants)
    {
        $sql = [];
        $participant_modules = $this->getParticipantModules();
        chdir('../legacy');
        if (in_array('Employees', $participant_modules)) {
            $sql[] = $this->getUsersQuery($parent, $participants);
        }
        if (in_array('Candidates', $participant_modules)) {
            $sql[] = $this->getCandidatesQuery($parent, $participants);
        }
        if (in_array('Resources', $participant_modules)) {
            $sql[] = $this->getResourcesQuery($parent, $participants);
        }
        chdir('../api');
        if (empty($sql)) {
            return [];
        }
        $sql = implode(' UNION ALL ', $sql);

        $stmt = $this->connection->prepare($sql);
        $result = $stmt->executeQuery([
            'parent_id' => $parent->id ?? '',
            'date_from' => $date_from,
            'date_to' => $date_to,
        ]);
        $data = $result->fetchAllAssociative();
        foreach ($data as &$row) {
            $row['meta'] = json_decode($row['meta'], true);
            $row['workschedules'] = json_decode($row['workschedules'], true);
            $row['activities'] = array_merge(
                json_decode($row['meetings'], true) ?? [],
                json_decode($row['calls'], true) ?? []
            );
            unset($row['meetings']);
            unset($row['calls']);
        }
        return $data;
    }

    public function getParticipantModules()
    {
        $modules = [];
        chdir('../legacy');
        if (ACLController::checkAccess('Employees', 'list')) {
            $modules[] = 'Employees';
        }
        if (ACLController::checkAccess('Candidates', 'list')) {
            $modules[] = 'Candidates';
        }
        if (ACLController::checkAccess('Resources', 'list')) {
            $modules[] = 'Resources';
        }
        chdir('../api');
        return $modules;
    }

    private function getUsersQuery($parent, $participants = null)
    {
        $parentSQL = '';
        if (!empty($parent->id) && $parent->load_relationship('users')) {
            $relDef = $parent->users->relationship->def;
            $parentSQL = "INNER JOIN {$relDef['table']} ON {$relDef['table']}.{$relDef['join_key_lhs']} = :parent_id AND {$relDef['table']}.{$relDef['join_key_rhs']} = users.id AND {$relDef['table']}.deleted = 0";
        }
        $idsSQL = '';
        if (is_array($participants)) {
            $users = [];
            foreach ($participants as $participant) {
                if ($participant['module'] === 'Users' || $participant['module'] === 'Employees') {
                    $users[] = $participant['id'];
                }
            }
            if (!empty($users)) {
                $quoted = implode(',', array_map(fn($id) => $this->connection->quote($id), $users));
                $idsSQL = "AND users.id IN ({$quoted})";
            } else {
                $idsSQL = 'AND 1=0';
            }
        }
        return "SELECT users.id
            , CONCAT_WS(' ', users.first_name, users.last_name) name
            , 'Employees' module
            , 'users' link
            , JSON_OBJECT(
                'photo', users.photo
                , 'description', positions.name
            ) meta
            , ({$this->getWorkschedulesQuery()}) workschedules 
            , ({$this->getMeetingsQuery('users', 'user_id')}) meetings
            , ({$this->getCallsQuery('users', 'user_id')}) calls
        FROM users
        {$parentSQL}
        LEFT JOIN positions
            ON positions.id = users.position_id
            AND positions.deleted = 0
        WHERE users.deleted = 0
            AND users.status = 'Active'
        {$idsSQL}";
    }

    private function getCandidatesQuery($parent, $participants = null)
    {
        $parentSQL = '';
        if (!empty($parent->id) && $parent->load_relationship('candidates')) {
            $relDef = $parent->candidates->relationship->def;
            $parentSQL = "INNER JOIN {$relDef['table']} ON {$relDef['table']}.{$relDef['join_key_lhs']} = :parent_id AND {$relDef['table']}.{$relDef['join_key_rhs']} = candidates.id AND {$relDef['table']}.deleted = 0";
        }
        $idsSQL = '';
        if (is_array($participants)) {
            $candidates = [];
            foreach ($participants as $participant) {
                if ($participant['module'] === 'Candidates') {
                    $candidates[] = $participant['id'];
                }
            }
            if (!empty($candidates)) {
                $quoted = implode(',', array_map(fn($id) => $this->connection->quote($id), $candidates));
                $idsSQL = "AND candidates.id IN ({$quoted})";
            } else {
                $idsSQL = 'AND 1=0';
            }
        }
        return "SELECT candidates.id
            , CONCAT_WS(' ', candidates.first_name, candidates.last_name) name
            , 'Candidates' module
            , 'candidates' link
            , JSON_OBJECT(
                'description', email_addresses.email_address
            ) meta
            , (NULL) workschedules 
            , ({$this->getMeetingsQuery('candidates', 'candidate_id')}) meetings
            , ({$this->getCallsQuery('candidates', 'candidate_id')}) calls
        FROM candidates
        {$parentSQL}
        LEFT JOIN email_addr_bean_rel eabr
            ON eabr.bean_id = candidates.id
            AND eabr.bean_module = 'Candidates'
            AND eabr.primary_address = 1
            AND eabr.deleted = 0
        LEFT JOIN email_addresses
            ON email_addresses.id = eabr.email_address_id
            AND email_addresses.deleted = 0
        WHERE candidates.deleted = 0
        {$idsSQL}";
    }

    private function getResourcesQuery($parent, $participants = null)
    {
        $parentSQL = '';
        if (!empty($parent->id) && $parent->load_relationship('resources')) {
            $relDef = $parent->resources->relationship->def;
            $parentSQL = "INNER JOIN {$relDef['table']} ON {$relDef['table']}.{$relDef['join_key_lhs']} = :parent_id AND {$relDef['table']}.{$relDef['join_key_rhs']} = resources.id AND {$relDef['table']}.deleted = 0";
        }
        $idsSQL = '';
        if (is_array($participants)) {
            $resources = [];
            foreach ($participants as $participant) {
                if ($participant['module'] === 'Resources') {
                    $resources[] = $participant['id'];
                }
            }
            if (!empty($resources)) {
                $quoted = implode(',', array_map(fn($id) => $this->connection->quote($id), $resources));
                $idsSQL = "AND resources.id IN ({$quoted})";
            } else {
                $idsSQL = 'AND 1=0';
            }
        }
        return "SELECT resources.id
            , resources.name
            , 'Resources' module
            , 'resources' link
            , JSON_OBJECT() meta
            , (NULL) workschedules 
            , ({$this->getMeetingsQuery('resources', 'resource_id')}) meetings
            , ({$this->getCallsQuery('resources', 'resource_id')}) calls
        FROM resources
        {$parentSQL}
        WHERE resources.deleted = 0
            AND resources.type = 'for_reservation'
            {$idsSQL}";
    }

    private function getCallsQuery($table, $field)
    {
        return "SELECT JSON_ARRAYAGG(JSON_OBJECT(
			'id', calls.id
			, 'name', calls.name
            , 'module', 'Calls'
			, 'date_start', calls.date_start
			, 'date_end', calls.date_end
		))
		FROM calls
		INNER JOIN calls_{$table}
			ON calls_{$table}.call_id = calls.id
			AND calls_{$table}.{$field} = {$table}.id
			AND calls_{$table}.deleted = 0
		WHERE calls.deleted = 0
			AND calls.date_end > :date_from
			AND calls.date_start < :date_to";
    }

    private function getMeetingsQuery($table, $field)
    {
        return "SELECT JSON_ARRAYAGG(JSON_OBJECT(
			'id', meetings.id
			, 'name', meetings.name
            , 'module', 'Meetings'
			, 'date_start', meetings.date_start
			, 'date_end', meetings.date_end
		))
		FROM meetings
		INNER JOIN meetings_{$table}
			ON meetings_{$table}.meeting_id = meetings.id
			AND meetings_{$table}.{$field} = {$table}.id
			AND meetings_{$table}.deleted = 0
		WHERE meetings.deleted = 0
			AND meetings.date_end > :date_from
			AND meetings.date_start < :date_to";
    }

    private function getWorkschedulesQuery()
    {
        return "SELECT JSON_ARRAYAGG(JSON_OBJECT(
			'id', workschedules.id
			, 'date_start', workschedules.date_start
			, 'date_end', workschedules.date_end
			, 'type', workschedules.type
		))
		FROM workschedules
		WHERE workschedules.assigned_user_id = users.id
			AND workschedules.deleted = 0
			AND workschedules.date_end >= :date_from
			AND workschedules.date_start <= :date_to";
    }
}
