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

use MintHCM\Api\Controllers\AuthController;
use MintHCM\Api\Middlewares\Params\ParamTypes\EmailType;
use MintHCM\Api\Middlewares\Params\ParamTypes\StringType;

$routes = array(
    "login" => array(
        "method" => "POST",
        "path" => "/login",
        "class" => AuthController::class,
        "function" => 'login',
        "desc" => "Auth user in MintHCM",
        "options" => array(
            'auth' => false,
        ),
        "pathParams" => array(),
        "queryParams" => array(),
        "bodyParams" => array(
            "username" => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "Username",
                "example" => 'user',
            ),
            "password" => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "User password",
                "example" => 'p4$$w0rd',
            ),
            "login_language" => array(
                "type" => StringType::class,
                "required" => false,
                "desc" => "System language",
                "example" => 'pl_PL',
            ),
        ),
    ),
    "logout" => array(
        "method" => array("POST", 'get'),
        "path" => "/logout",
        "class" => AuthController::class,
        "function" => 'logout',
        "desc" => "Logout user from MintHCM",
        "options" => array(
            'auth' => false,
        ),
        "pathParams" => array(),
        "queryParams" => array(),
        "bodyParams" => array(),
    ),
    "forget_password" => array(
        "method" => "POST",
        "path" => "/forget_password",
        "class" => AuthController::class,
        "function" => 'forgetPassword',
        "desc" => "Send mail with link to reset password",
        "options" => array(
            'auth' => false,
        ),
        "pathParams" => array(),
        "queryParams" => array(),
        "bodyParams" => array(
            "username" => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "Username",
                "example" => 'user',
            ),
            "email" => array(
                "type" => EmailType::class,
                "required" => true,
                "desc" => "Primary email address",
                "example" => 'user@example.com',
            ),
        ),
    ),
    "valid_token" => array(
        "method" => "GET",
        "path" => "/validation_token",
        "class" => AuthController::class,
        "function" => 'validToken',
        "desc" => "Valid forget password token",
        "options" => array(
            'auth' => false,
        ),
        "pathParams" => array(),
        "queryParams" => array(
            "token" => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "Guid to reset password",
                "example" => '?token=d41448ca-7d9e-1b9f-d826-64621b33ffb1',
            ),
        ),
        "bodyParams" => array(
        ),
    ),
    "reset_forget_password" => array(
        "method" => "POST",
        "path" => "/reset_forget_password",
        "class" => AuthController::class,
        "function" => 'resetForgetPassword',
        "desc" => "Set new password from forget password action",
        "options" => array(
            'auth' => false,
        ),
        "pathParams" => array(),
        "queryParams" => array(),
        "bodyParams" => array(
            "token" => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "Guid to reset password",
                "example" => '?token=d41448ca-7d9e-1b9f-d826-64621b33ffb1',
            ),
            "username" => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "Username",
                "example" => 'user',
            ),
            "new_password" => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "New password",
                "example" => 'P4$$w0rd',
            ),
        ),
    ),
    "confirm_login_wizard" => array(
        "method" => "POST",
        "path" => "/confirm_login_wizard",
        "class" => AuthController::class,
        "function" => 'confirmLoginWizard',
        "desc" => "Confirm wizard login view and save selected options to user",
        "options" => array(
            'auth' => true,
        ),
        "pathParams" => array(),
        "queryParams" => array(),
        "bodyParams" => array(
            "first_name" => array(
                "type" => StringType::class,
                "required" => false,
                "desc" => "First name of user",
                "example" => 'Sam',
            ),
            "last_name" => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "Surname of user",
                "example" => 'Smith',
            ),
            "email" => array(
                "type" => StringType::class,
                "required" => false,
                "desc" => "Email of user",
                "example" => 'demo@email.com',
            ),
            "time_zone" => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "Time zone of user",
                "example" => 'Europe/London',
            ),
            "time_format" => array(
                "type" => StringType::class,
                "required" => false,
                "desc" => "Time Format of user",
                "example" => 'H:i',
            ),
            "date_format" => array(
                "type" => StringType::class,
                "required" => false,
                "desc" => "Date Format of user",
                "example" => 'm/d/Y',
            ),
            "display_name_format" => array(
                "type" => StringType::class,
                "required" => false,
                "desc" => "Information how to display names of people",
                "example" => 's f l',
            ),
        ),
    ),
);
