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
 * Copyright (C) 2018-2024 MintHCM
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
use MintHCM\Api\Entities\Comments;
use Slim\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use MintHCM\Modules\Comments\AccessChecker;
use MintHCM\Api\Entities\Users;

#[\AllowDynamicProperties]
class CommentsController
{
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getInitialData(Request $request, Response $response, array $args): Response
    {
        $response = $response->withHeader('Content-type', 'application/json');
        $parent_id = $request->getAttribute('parent_id');
        $parent_type = $request->getAttribute('parent_type');

        chdir('../legacy');
        $parent = \BeanFactory::getBean($parent_type, $parent_id);
        if (empty($parent->id)) {
            $response = $response->withStatus(404);
            return $response;
        }
        if (!$parent->ACLAccess('view')) {
            return $response->withStatus(403);
        }
        chdir('../api');

        /** @var Users[] */
        $users = $this->entityManager->getRepository(Users::class)->getActiveUsers();
        $users = array_map(fn(Users $user) => [
            'id' => $user->id,
            'user_name' => $user->user_name,
            'full_name' => $user->getFullName(),
            'status' => $user->status,
            'photo' => $user->photo,
        ], $users);

        $init_controller = new Init\Init($this->entityManager);
        $languages_controller = new Init\Languages();

        $response->getBody()->write(json_encode([
            'user' => $init_controller->getCurrentUserData(),
            'languages' => $languages_controller->getLanguages(),
            'access' => $this->getAccess($parent),
            'comments' => $this->getParentComments($parent_id, $parent_type),
            'users' => $users,
        ]));

        return $response;
    }

    public function get(Request $request, Response $response, array $args): Response
    {
        $response = $response->withHeader('Content-type', 'application/json');
        $parent_id = $request->getAttribute('parent_id');
        $parent_type = $request->getAttribute('parent_type');

        chdir('../legacy');
        $parent = \BeanFactory::getBean($parent_type, $parent_id);
        if (empty($parent->id)) {
            $response = $response->withStatus(404);
            return $response;
        }
        if (!$parent->ACLAccess('view')) {
            return $response->withStatus(403);
        }
        chdir('../api');

        $comments = $this->getParentComments($parent_id, $parent_type);

        $response->getBody()->write(json_encode($comments));
        return $response;
    }

    public function create(Request $request, Response $response, array $args): Response
    {
        global $current_user;

        $response = $response->withHeader('Content-type', 'application/json');
        $parent_id = $request->getAttribute('parent_id');
        $parent_type = $request->getAttribute('parent_type');
        $description = $request->getAttribute('description');
        $reply_to_id = $request->getAttribute('reply_to_id');

        chdir('../legacy');
        $parent = \BeanFactory::getBean($parent_type, $parent_id);
        if (empty($parent->id)) {
            $response = $response->withStatus(404);
            return $response;
        }
        $access = $this->getAccess($parent);
        if (!$access['add']) {
            $response = $response->withStatus(403);
            return $response;
        }
        $comment = \BeanFactory::newBean('Comments');
        $comment->description = $description;
        $comment->assigned_user_id = $current_user->id;
        $comment->parent_id = $parent_id;
        $comment->parent_type = $parent_type;
        if (!empty($reply_to_id)) {
            $comment->reply_to_id = $reply_to_id;
        }
        $comment->save(false);
        chdir('../api');

        return $response;
    }

    public function update(Request $request, Response $response, array $args)
    {
        $response = $response->withHeader('Content-type', 'application/json');
        $parent_id = $request->getAttribute('parent_id');
        $parent_type = $request->getAttribute('parent_type');
        $comment_id = $request->getAttribute('id');
        $attributes = $request->getAttribute('attributes');

        chdir('../legacy');
        $parent = \BeanFactory::getBean($parent_type, $parent_id);
        $comment = \BeanFactory::getBean('Comments', $comment_id);
        if (empty($parent->id) || empty($comment->id)) {
            $response = $response->withStatus(404);
            return $response;
        }
        if (!$comment->ACLAccess('edit')) {
            return $response->withStatus(403);
        }
        foreach ($attributes as $field => $value) {
            if (isset($comment->field_defs[$field]) && $field !== 'id') {
                $comment->$field = $value;
            }
        }
        $comment->save(false);
        chdir('../api');

        $response = $response->withStatus(200);
        return $response;
    }

    protected function getAccess($parent)
    {
        $class_path = "\MintHCM\Modules\Comments\AccessChecker\\{$parent->module_name}AccessChecker";
        $access_checker = class_exists($class_path) ? new $class_path($parent) : new AccessChecker\AccessChecker($parent);
        return $access_checker->get();
    }

    private function getParentComments(string $parent_id, string $parent_type): array
    {
        /** @var Comments[] */
        $comments = $this->entityManager->getRepository(Comments::class)->getParentCommentsWithReactions($parent_type, $parent_id);
        $parsed_comments = [];
        foreach ($comments as $comment) {
            $parsed_comment = [
                'id' => $comment->id,
                'description' => $comment->description,
                'reply_to_id' => $comment->reply_to_id,
                'date_entered' => !empty($comment->date_entered) ? $comment->date_entered->format('Y-m-d H:i:s') : null,
                'pinned' => boolval($comment->pinned),
                'date_edited' => !empty($comment->date_edited) ? $comment->date_edited->format('Y-m-d H:i:s'): null,
                'removed' => boolval($comment->removed),
            ];

            if ($comment->assigned_user_link instanceof Users) {
                $parsed_comment['assigned_user'] = [
                    'id' => $comment->assigned_user_link->id,
                    'name' => $comment->assigned_user_link->getFullName(),
                    'photo' => $comment->assigned_user_link->photo,
                ];
            }

            foreach ($comment->reactions->getValues() as $reaction) {
                $parsed_comment['reactions'][] = [
                    'type' => $reaction->reaction_type,
                    'user' => [
                        'id' => $reaction->assigned_user_link->id,
                        'name' => $reaction->assigned_user_link->getFullName(),
                    ],
                ];
            }

            $parsed_comments[] = $parsed_comment;
        }

        return $parsed_comments;
    }
}
