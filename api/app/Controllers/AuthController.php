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
use MintHCM\Api\Entities\UsersPasswordLink;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Psr7\Response;

class AuthController
{
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function login(Request $request, Response $response, array $args): Response
    {
        $username = trim($request->getAttribute('username'));
        $password = trim($request->getAttribute('password'));
        $login_language = $request->getAttribute('login_language');

        chdir('../legacy/');
        require_once 'include/MVC/SugarApplication.php';
        $app = new \SugarApplication();
        $app->startSession();
        require_once 'modules/Users/authentication/SugarAuthenticate/SugarAuthenticateUser.php';
        require_once 'modules/Users/authentication/AuthenticationController.php';

        if($this->IsLdapOn() && (new \AuthenticationController())->authController->loginAuthenticate($username, $password, false, [])){
            $loginSuccess = true;
        }
        if(!$loginSuccess){
            $sugar_auth = \AuthenticationController::getInstance();
            $loginSuccess = $sugar_auth->login($username, $password);
        }
        chdir('../api/');

        if (!$loginSuccess) {
            throw new HttpUnauthorizedException($request);
        }

        if (!empty($login_language)) {
            $_SESSION['authenticated_user_language'] = $login_language;
        }
        $response = $response->withHeader('Content-type', 'application/json');
        $data = json_encode(['message' => 'Login success']);
        $response->getBody()->write($data);
        return $response;
    }

    private function IsLdapOn(){
        global $system_config;
        return !empty($system_config->settings['system_ldap_enabled']) && $system_config->settings['system_ldap_enabled'] == true;
    }


    public function logout(Request $request, Response $response, array $args): Response
    {
        session_start();
        session_destroy();
        ob_clean();
        sugar_cleanup(true);
        return $response;
    }

    public function forgetPassword(Request $request, Response $response, array $args): Response
    {
        global $sugar_config;

        $response = $response->withHeader('Content-type', 'application/json');

        $username = $request->getAttribute('username');
        $email = $request->getAttribute('email');

        chdir('../legacy/');
        $user = new \User();
        $user_id = $user->retrieve_user_id($username);
        $user->retrieve($user_id);
        $is_primary_email = !empty($user) ? $user->isPrimaryEmail($email) : false;
        chdir('../api/');

        if (empty($user->id)
            || $user->id !== $user_id
            || !$is_primary_email
            || $user->portal_only
            || $user->is_group
        ) {
            $response = $response->withStatus(400);
            $response->getBody()->write(json_encode(array('message' => "LBL_PROVIDE_USERNAME_AND_EMAIL")));
            return $response;
        }

        $usersPasswordLink = new UsersPasswordLink();
        $usersPasswordLink->username = $username;
        $this->entityManager->persist($usersPasswordLink);
        $this->entityManager->flush();

        $emailTemp_id = $sugar_config['passwordsetting']['lostpasswordtmpl'];

        $url = $sugar_config['site_url'] . "/#/auth/reset?token={$usersPasswordLink->id}";
        $additionalData = array(
            'link' => true,
            'password' => '',
            'url' => $url,
        );

        chdir('../legacy/');
        $result = $user->sendEmailForPassword($emailTemp_id, $additionalData);
        chdir('../api/');

        if (true !== $result['status']) {
            $response = $response->withStatus(500);
            $response->getBody()->write(json_encode(array('message' => 'LBL_EMAIL_NOT_SENT')));
            return $response;
        }

        return $response;
    }

    public function validToken(Request $request, Response $response, array $args): Response
    {
        global $sugar_config;

        $response = $response->withHeader('Content-type', 'application/json');

        $token = $request->getAttribute('token');

        $usersPasswordLink = $this->entityManager->getRepository(UsersPasswordLink::class)
            ->findOneById($token);
        if (!$usersPasswordLink) {
            $response = $response->withStatus(400);
            return $response;
        }

        $pwd_settings = $sugar_config['passwordsetting'];
        $expired = false;
        if ($pwd_settings['linkexpiration']) {
            $delay = $pwd_settings['linkexpirationtime'] * $pwd_settings['linkexpirationtype'];
            $stim = $usersPasswordLink->date_generated->getTimestamp() + date('Z');
            $expiretime = \TimeDate::getInstance()->fromTimestamp($stim)->get("+$delay  minutes")->asDb();
            $timenow = \TimeDate::getInstance()->nowDb();
            if ($timenow > $expiretime) {
                $expired = true;
            }
        }

        if ($expired) {
            $response = $response->withStatus(403);
            $response->getBody()->write(json_encode(array('message' => 'LBL_TOKEN_EXPIRED')));
            return $response;
        }

        if ($usersPasswordLink->deleted) {
            $response = $response->withStatus(403);
            $response->getBody()->write(json_encode(array('message' => 'LBL_TOKEN_USED')));
            return $response;
        }

        $response->getBody()->write(json_encode(array(
            'username' => $usersPasswordLink->username,
            'password_settings' => array(
                "oneupper" => !empty($pwd_settings['oneupper']) ? true : false,
                "onelower" => !empty($pwd_settings['onelower']) ? true : false,
                "onenumber" => !empty($pwd_settings['onenumber']) ? true : false,
                "onespecial" => !empty($pwd_settings['onespecial']) ? true : false,
                "minpwdlength" => !empty($pwd_settings['minpwdlength']) ? (int) $pwd_settings['minpwdlength'] : false,
            ),

        )));

        return $response;
    }

    public function resetForgetPassword(Request $request, Response $response, array $args): Response
    {
        global $mod_strings;

        $username = $request->getAttribute('username');
        $new_password = $request->getAttribute('new_password');

        $response = $this->validToken($request, $response, $args);
        if ($response->getStatusCode() !== 200) {
            return $response;
        }

        $response = new Response();
        $response = $response->withHeader('Content-type', 'application/json');

        chdir('../legacy/');
        $user = new \User();
        $mod_strings = return_module_language($GLOBALS['current_language'], 'Users');
        $errors = $user->passwordValidationCheck($new_password);
        chdir('../api/');

        if (!empty($errors)) {
            $response = $response->withStatus(400);
            $response->getBody()->write(json_encode(array('message' => $errors)));
            return $response;
        }

        chdir('../legacy/');
        $user_id = $user->retrieve_user_id($username);
        $user->retrieve($user_id);
        $user->setNewPassword($new_password);
        chdir('../api/');

        $this->entityManager->getRepository(UsersPasswordLink::class)
            ->markAllAsDeletedByUsername($username);

        return $response;
    }
    
    public function confirmLoginWizard(Request $request, Response $response, array $args): Response
    {
        $first_name = $request->getAttribute('first_name');
        $last_name = $request->getAttribute('last_name');
        $email = $request->getAttribute('email');
        $preferences = [];
        $preferences['timezone'] = $request->getAttribute('time_zone');
        $preferences['timef'] = $request->getAttribute('time_format');
        $preferences['datef'] = $request->getAttribute('date_format');
        $preferences['default_locale_name_format'] = $request->getAttribute('display_name_format');

        $response = new Response();
        $response = $response->withHeader('Content-type', 'application/json');

        global $current_user;
        if (empty($current_user->id)) {
            $response = $response->withStatus(403);
            return $response;
        }
        chdir('../legacy/');
        $current_user->first_name = $first_name;
        $current_user->last_name = $last_name;
        $current_user->email1 = $email;

        $current_user->save(false);

        foreach($preferences as $k => $v){
            if(!empty($v)){
                $current_user->setPreference($k, $v, 0, 'global');        
            }
        }
        $current_user->setPreference('ut', '1', 0, 'global');

        chdir('../api/');
        return $response;
    }
}
