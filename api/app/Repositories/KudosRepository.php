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

use Doctrine\ORM\EntityRepository;

class KudosRepository extends EntityRepository
{
    public function get($page, $list_type)
    {
        global $current_user;
        $list_type_where = $current_user->isAdmin() ? ''
                                                    : 'AND ((kudos.announced IS NULL OR kudos.announced = 0) AND kudos.assigned_user_id = ' ."'$current_user->id'" .')
                                                       OR kudos.announced = 1';
        if($list_type === 'received') {
            $list_type_where = 'AND kudos.announced = 1 AND kudos.employee_id = ' ."'$current_user->id'" .'';
        } elseif($list_type === 'given') {
            $list_type_where = 'AND kudos.assigned_user_id = ' ."'$current_user->id'" .'';
        }
        $rows_to_skip = $page == 1 ? 0 : 20 * ($page - 1);
        $sql = "SELECT kudos.id
                    , kudos.description
                    , kudos.date_entered
                    , kudos.created_by
                    , kudos.announced
                    , kudos.announcement_date
                    , kudos.private
                    , JSON_OBJECT(
                        'id', assigned_user_data.id,
                        'first_name', assigned_user_data.first_name,
                        'full_name', CONCAT_WS(' ', assigned_user_data.first_name, assigned_user_data.last_name),
                        'photo', assigned_user_data.photo
                    ) assigned_user
                    , JSON_OBJECT(
                        'id', employee_user_data.id,
                        'first_name', employee_user_data.first_name,
                        'full_name', CONCAT_WS(' ', employee_user_data.first_name, employee_user_data.last_name),
                        'photo', employee_user_data.photo
                    ) employee
                    , IF(reactions.id IS NULL, NULL, JSON_ARRAYAGG(JSON_OBJECT(
                        'type', reactions.reaction_type,
                        'user', JSON_OBJECT(
                            'id', reactions.assigned_user_id,
                            'name', CONCAT_WS(' ', reaction_user.first_name, reaction_user.last_name)
                        )
                    ))) reactions
                FROM kudos
                INNER JOIN users employee_user_data
                    ON employee_user_data.id = kudos.employee_id
                    AND employee_user_data.deleted = 0
                    AND employee_user_data.status = 'active'
                INNER JOIN users assigned_user_data
                    ON assigned_user_data.id = kudos.assigned_user_id
                    AND assigned_user_data.deleted = 0
                    AND assigned_user_data.status = 'active'
                LEFT JOIN reactions
                    ON reactions.parent_type = 'Kudos'
                    AND reactions.parent_id = kudos.id
                    AND reactions.deleted = 0
                LEFT JOIN users reaction_user
                    ON reaction_user.id = reactions.assigned_user_id
                    AND reaction_user.deleted = 0
                WHERE kudos.deleted = 0
                $list_type_where
                GROUP BY kudos.id
                ORDER BY
                    CASE
                        WHEN kudos.announcement_date IS NULL THEN 1
                        ELSE 2
                    END,
                    CASE
                        WHEN kudos.announcement_date IS NULL THEN kudos.date_entered
                        ELSE kudos.announcement_date
                    END DESC,
                    employee_user_data.last_name ASC
                LIMIT 20
                OFFSET $rows_to_skip
        ";
        
        $em = $this->getEntityManager();
        $stmt = $em->getConnection()->prepare($sql);
        $result = $stmt->executeQuery();
        $kudos = $result->fetchAllAssociative();

        foreach ($kudos as $index => $kudos_item) {
            $kudos[$index]['announced'] = boolval($kudos_item['announced']);
            $kudos[$index]['private'] = boolval($kudos_item['private']);
            $kudos[$index]['assigned_user'] = json_decode($kudos_item['assigned_user'], true);
            $kudos[$index]['employee'] = json_decode($kudos_item['employee'], true);
            $kudos[$index]['reactions'] = json_decode($kudos_item['reactions'], true);
            $kudos[$index]['current_user_is_gifted'] = $current_user->id === $kudos[$index]['employee']['id'];
            $kudos[$index]['current_user_is_author'] = $current_user->id === $kudos[$index]['assigned_user']['id'];
            $kudos[$index]['current_user_is_admin'] = $current_user->isAdmin();
            $kudos[$index]['current_user_access'] = $kudos[$index]['current_user_is_admin'] || $kudos[$index]['current_user_is_gifted'] || $kudos[$index]['current_user_is_author'];
  

            if($kudos[$index]['private'] && !$kudos[$index]['current_user_access']) 
            {
                $kudos[$index]['description'] = null;
            }
        }
        return $kudos;
    }
}
