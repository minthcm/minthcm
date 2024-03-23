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

class CommentRepository extends EntityRepository
{
    public function get($parent_type, $parent_id)
    {
        $sql = "SELECT comments.id
                    , comments.description
                    , comments.reply_to_id
                    , comments.date_entered
                    , comments.pinned
                    , comments.date_edited
                    , comments.removed
                    , JSON_OBJECT(
                        'id', users.id,
                        'name', CONCAT_WS(' ', users.first_name, users.last_name),
                        'photo', users.photo
                    ) assigned_user
                    , IF(reactions.id IS NULL, NULL, JSON_ARRAYAGG(JSON_OBJECT(
                        'type', reactions.reaction_type,
                        'user', JSON_OBJECT(
                            'id', reactions.assigned_user_id,
                            'name', CONCAT_WS(' ', reaction_user.first_name, reaction_user.last_name)
                        )
                    ))) reactions
                FROM comments
                INNER JOIN users
                    ON users.id = comments.assigned_user_id
                    AND users.deleted = 0
                LEFT JOIN reactions
                    ON reactions.parent_type = 'Comments'
                    AND reactions.parent_id = comments.id
                    AND reactions.deleted = 0
                LEFT JOIN users reaction_user
                    ON reaction_user.id = reactions.assigned_user_id
                    AND reaction_user.deleted = 0
                WHERE comments.deleted = 0
                    AND comments.parent_id = :parent_id
                    AND comments.parent_type = :parent_type
                GROUP BY comments.id
                ORDER BY comments.date_entered ASC
        ";
        $em = $this->getEntityManager();
        $stmt = $em->getConnection()->prepare($sql);
        $result = $stmt->executeQuery([
            'parent_type' => $parent_type,
            'parent_id' => $parent_id,
        ]);
        $comments = $result->fetchAllAssociative();
        foreach ($comments as $index => $comment) {
            $comments[$index]['assigned_user'] = json_decode($comment['assigned_user'], true);
            $comments[$index]['reactions'] = json_decode($comment['reactions'], true);
            $comments[$index]['pinned'] = boolval($comment['pinned']);
            $comments[$index]['removed'] = boolval($comment['removed']);
        }
        return $comments;
    }
}
