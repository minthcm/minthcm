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

namespace MintHCM\Api\Controllers\User;

use Doctrine\ORM\EntityManagerInterface;
use MintHCM\Api\Entities\UserPreferences;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response;

class UserThemeController
{
    private const ALLOWED_THEMES = ['system', 'light', 'dark'];

    public function __construct(private EntityManagerInterface $entityManager) {}

    public function save(Request $request, Response $response, array $args): Response
    {
        global $current_user;

        $theme = $request->getAttribute('theme');

        if (!in_array($theme, self::ALLOWED_THEMES, true)) {
            $response->getBody()->write(json_encode(['error' => 'Invalid theme value']));
            return $response->withStatus(422)->withHeader('Content-Type', 'application/json');
        }

        $this->saveThemePreference($current_user->id, $current_user->user_name, $theme);

        $response->getBody()->write(json_encode(['theme' => $theme]));
        return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
    }

    private function saveThemePreference(string $userId, string $userName, string $theme): void
    {
        $repository = $this->entityManager->getRepository(UserPreferences::class);
        /** @var UserPreferences|null $pref */
        $pref = $repository->findOneBy(['assigned_user_id' => $userId, 'category' => 'global', 'deleted' => 0]);

        if ($pref === null) {
            $pref = new UserPreferences();
            $pref->assigned_user_id = $userId;
            $pref->category = 'global';
            $pref->deleted = 0;
            $this->entityManager->persist($pref);
        }

        $contents = $pref->getContentsAsArray();
        $contents['theme'] = $theme;
        $pref->contents = base64_encode(serialize($contents));
        $pref->date_modified = new \DateTime();

        $this->entityManager->flush();

        // Keep PHP session in sync so legacy Save.php doesn't overwrite this value
        // when it serialises session preferences back to the database.
        $sessionKey = "{$userName}_PREFERENCES";
        if (isset($_SESSION[$sessionKey]['global'])) {
            $_SESSION[$sessionKey]['global']['theme'] = $theme;
        }
    }
}
