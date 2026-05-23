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

namespace MintHCM\Data;

use MintHCM\Utils\LegacyConnector;

require_once '../legacy/data/SugarBean.php';

#[\AllowDynamicProperties]
class MintBean
{
    protected static $static_legacy_bean;

    public function __construct(protected $legacy_bean)
    {
        static::$static_legacy_bean = $legacy_bean;
    }

    public function __get($name)
    {
        $old_cwd = getcwd();
        chdir('../legacy/');
        try {
            return $this->legacy_bean->$name;
        } finally {
            chdir($old_cwd);
        }
    }

    public function __set($name, $value)
    {
        if ('legacy_bean' === $name) {
            $this->legacy_bean = $value;
            return;
        }

        $old_cwd = getcwd();
        chdir('../legacy/');
        try {
            $this->legacy_bean->$name = $value;
        } finally {
            chdir($old_cwd);
        }
    }

    public function __isset($name)
    {
        $old_cwd = getcwd();
        chdir('../legacy/');
        try {
            return isset($this->legacy_bean->$name);
        } finally {
            chdir($old_cwd);
        }
    }

    public function __unset($name)
    {
        $old_cwd = getcwd();
        chdir('../legacy/');
        try {
            unset($this->legacy_bean->$name);
        } finally {
            chdir($old_cwd);
        }
    }

    public function __call($name, $arguments)
    {
        $old_cwd = getcwd();
        chdir('../legacy/');
        try {
            return $this->legacy_bean->$name(...$arguments);
        } finally {
            chdir($old_cwd);
        }
    }

    public static function __callStatic($name, $arguments)
    {
        $old_cwd = getcwd();
        chdir('../legacy/');
        try {
            return static::$static_legacy_bean::$name(...$arguments);
        } finally {
            chdir($old_cwd);
        }
    }

    public function load_relationship(string $link_name): bool
    {
        $old_cwd = getcwd();
        chdir('../legacy/');
        try {
            if ($this->legacy_bean->load_relationship($link_name)) {
                $link = new LegacyConnector('Link2', null, [$link_name, $this->legacy_bean]);
                $this->$link_name = $link;
                return true;
            }
            return false;
        } finally {
            chdir($old_cwd);
        }
    }
}
