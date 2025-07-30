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

namespace MintHCM\Api\Controllers;

use Doctrine\ORM\EntityManagerInterface;
use MintHCM\Api\Entities\Kudos;
use Slim\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class KudosController
{
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getInitialData(Request $request, Response $response, array $args): Response
    {
        global $current_user;
        $response = $response->withHeader('Content-type', 'application/json');

        chdir('../legacy');
        $db = \DBManagerFactory::getInstance();
        $sql = "SELECT id,
                CONCAT_WS(' ', first_name, last_name) full_name,
                photo
                FROM users
                WHERE deleted = 0
                    AND users.status = 'active'
                    AND users.id != " . "'$current_user->id'
                ORDER BY first_name ASC, last_name ASC";
        $result = $db->query($sql);
        $users = [];
        while ($row = $db->fetchByAssoc($result)) {
            $users[] = $row;
        }
        chdir('../api');

        $kudos = $this->entityManager->getRepository(Kudos::class)->get(1, 'all');

        $response->getBody()->write(json_encode([
        'kudos' => $kudos,
        'users' => $users,]));
        
        return $response;
    }
    public function get(Request $request, Response $response, array $args): Response
    {
        $response = $response->withHeader('Content-type', 'application/json');
        $page = $request->getAttribute('page');
        $list_type = $request->getAttribute('listType');

        $kudos = $this->entityManager->getRepository(Kudos::class)->get($page, $list_type);

        $response->getBody()->write(json_encode([
        'kudos' => $kudos]));
        
        return $response;
    }
    public function post(Request $request, Response $response, array $args): Response
    {
        global $current_user;
        $id = $request->getAttribute('id');
        $gifted_user_id = $request->getAttribute('gifted_user_id');
        $message = $request->getAttribute('message');
        $private = $request->getAttribute('private');
        $response = $response->withHeader('Content-type', 'application/json');

        chdir('../legacy');
        if (!$id) {
            $kudos = \BeanFactory::newBean('Kudos');
            $kudos->description = $message;
            $kudos->assigned_user_id = $current_user->id;
            $kudos->employee_id = $gifted_user_id;
            $kudos->private = $private;
            $kudos->save(false);
        }
        else {
            $kudos = \BeanFactory::getBean('Kudos', $id);
            if (empty($kudos->id)) {
                $response = $response->withStatus(404);
                return $response;
            }
            if (!$kudos->ACLAccess('edit')) {
                $response = $response->withStatus(403);
                return $response;
            }
            $kudos->description = $message;
            $kudos->private = $private;
            $kudos->save(false);
        }
        chdir('../api');
        return $response;
    }
    public function delete(Request $request, Response $response, array $args): Response
    {
        $id = $request->getAttribute('id');
        $response = $response->withHeader('Content-type', 'application/json');

        chdir('../legacy');
        if ($id) {
            $kudos = \BeanFactory::getBean('Kudos', $id);
            if (empty($kudos->id)) {
                $response = $response->withStatus(404);
                return $response;
            }
            if (!$kudos->ACLAccess('delete')) {
                $response = $response->withStatus(403);
                return $response;
            }
            $kudos->mark_deleted($id);
        }
        chdir('../api');
        return $response;
    }

}
