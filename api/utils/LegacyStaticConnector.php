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
 * Copyright (C) 2018-2026 MintHCM
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

namespace MintHCM\Utils;

/**
 * Connector for legacy classes that use a singleton pattern (private constructor).
 * Unlike LegacyConnector, this does not instantiate the class directly.
 * Instead, it obtains the instance via a static factory method (e.g. getInstance())
 * and proxies instance method calls with automatic chdir wrapping.
 */
class LegacyStaticConnector
{
    protected string $className;
    protected $instance = null;

    /**
     * @param string $class_name  Fully qualified legacy class name
     * @param string|null $link   Path to require_once (relative to legacy root)
     * @param string $factoryMethod  Static method name to obtain the singleton instance
     * @param array $factoryArgs  Arguments to pass to the factory method
     */
    public function __construct(string $class_name, ?string $link = null, string $factoryMethod = 'getInstance', array $factoryArgs = [])
    {
        $this->className = $class_name;

        $old_cwd = getcwd();
        chdir('../legacy/');
        try {
            if ($link !== null) {
                require_once $link;
            }
            $this->instance = call_user_func_array([$class_name, $factoryMethod], $factoryArgs);
        } finally {
            chdir($old_cwd);
        }
    }

    public function __get($name)
    {
        $old_cwd = getcwd();
        chdir('../legacy/');
        try {
            return $this->instance->$name;
        } finally {
            chdir($old_cwd);
        }
    }

    public function __set($name, $value)
    {
        $old_cwd = getcwd();
        chdir('../legacy/');
        try {
            $this->instance->$name = $value;
        } finally {
            chdir($old_cwd);
        }
    }

    public function __call($name, $arguments)
    {
        $old_cwd = getcwd();
        chdir('../legacy/');
        try {
            return $this->instance->$name(...$arguments);
        } finally {
            chdir($old_cwd);
        }
    }
}
