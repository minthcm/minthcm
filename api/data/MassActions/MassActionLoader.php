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
 * Copyright (C) 2018-2025 MintHCM
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

namespace MintHCM\Data\MassActions;

use InvalidArgumentException;

class MassActionLoader
{
    private const CORE_NAMESPACE_PREFIX = 'MintHCM\\';
    private const CUSTOM_NAMESPACE_PREFIX = 'MintHCM\\Custom\\';
    private const CORE_ACTIONS_NAMESPACE = 'MintHCM\\Data\\MassActions\\Actions\\';
    private const CUSTOM_ACTIONS_NAMESPACE = 'MintHCM\\Custom\\Data\\MassActions\\Actions\\';

    public static function getAction($class, ...$args)
    {
        $resolved_class = self::resolveClass($class);

        return new $resolved_class(...$args);
    }

    /**
     * Resolves an action name -- a short name or a fully qualified one -- to the class to instantiate.
     *
     * Custom classes always win over their core counterparts, both when overriding an existing core
     * action and when defining a brand new one that has no core counterpart at all.
     */
    private static function resolveClass(string $class): string
    {
        // A caller may pass a fully qualified name with a leading separator; without normalising it
        // the prefix comparisons below would miss and the custom override would be skipped.
        $class = ltrim($class, '\\');

        $resolved_class = strpos($class, '\\') === false
            ? self::resolveShortName($class)
            : self::resolveCustomOverride($class);

        if (!class_exists($resolved_class) || !is_subclass_of($resolved_class, MassAction::class)) {
            throw new InvalidArgumentException("Class $resolved_class does not exist or is not a subclass of " . MassAction::class);
        }

        return $resolved_class;
    }

    private static function resolveShortName(string $short_name): string
    {
        $core_class = self::CORE_ACTIONS_NAMESPACE . $short_name;
        $custom_class = self::CUSTOM_ACTIONS_NAMESPACE . $short_name;

        if (class_exists($custom_class)) {
            return $custom_class;
        }

        // Falling back to the core name even when it does not exist keeps the error message
        // pointing at the action the caller actually asked for.
        return $core_class;
    }

    private static function resolveCustomOverride(string $class): string
    {
        if (strpos($class, self::CUSTOM_NAMESPACE_PREFIX) === 0 || strpos($class, self::CORE_NAMESPACE_PREFIX) !== 0) {
            return $class;
        }

        $custom_class = self::CUSTOM_NAMESPACE_PREFIX . substr($class, strlen(self::CORE_NAMESPACE_PREFIX));
        if (class_exists($custom_class) && is_subclass_of($custom_class, $class)) {
            return $custom_class;
        }

        return $class;
    }
}
