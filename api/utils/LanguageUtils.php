<?php

/**
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
 */

namespace MintHCM\Utils;

class LanguageUtils
{
    /**
     * Normalize the `disabled_languages` config value into a flat array of language keys.
     *
     * $sugar_config['disabled_languages'] may be stored as a comma-separated string
     * or, in some installations, as an array. This method handles both forms.
     *
     * @param string|array<int, string> $raw
     * @return array<int, string>
     */
    public static function parseDisabledLanguages(string|array $raw): array
    {
        if (is_array($raw)) {
            return $raw;
        }
        return array_values(array_filter(explode(',', $raw)));
    }
}
