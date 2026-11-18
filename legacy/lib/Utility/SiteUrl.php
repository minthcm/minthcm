<?php
/**
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

namespace SuiteCRM\Utility;

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

/**
 * Builds absolute URLs of this instance from the configured `site_url`.
 *
 * `site_url` may be configured with or without a trailing slash; plain concatenation of
 * `site_url . '/path'` then yields `https://host//path`. Use this class instead of hand-written
 * `rtrim($sugar_config['site_url'], '/')` so the join always contains exactly one slash.
 */
final class SiteUrl
{
    private const SEPARATOR = '/';

    /**
     * Returns the site URL without trailing slashes.
     *
     * @param string|null $siteUrl URL to normalize; null = `$sugar_config['site_url']`
     * @return string Base URL without trailing slash, '' when site_url is not configured
     */
    public static function base(?string $siteUrl = null): string
    {
        $siteUrl ??= (string) ($GLOBALS['sugar_config']['site_url'] ?? '');

        return rtrim($siteUrl, self::SEPARATOR);
    }

    /**
     * Returns an absolute URL: site URL + exactly one slash + path.
     *
     * Leading slashes of `$path` are ignored, so `build('/index.php')` and `build('index.php')`
     * are equivalent. `build()` returns the site URL with a single trailing slash.
     *
     * @param string $path Path, query and/or fragment relative to the site root (e.g. `#/auth/reset?token=1`)
     * @param string|null $siteUrl Base URL; null = `$sugar_config['site_url']`
     */
    public static function build(string $path = '', ?string $siteUrl = null): string
    {
        return self::base($siteUrl) . self::SEPARATOR . ltrim($path, self::SEPARATOR);
    }
}
