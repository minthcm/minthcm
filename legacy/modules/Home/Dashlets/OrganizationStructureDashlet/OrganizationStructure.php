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
 * the terms of the GNU Affero General Public License version 3 AS published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 AS permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
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
 * of this program must display Appropriate Legal Notices, AS required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by SugarCRM"
 * logo and "Supercharged by SuiteCRM" logo and "Reinvented by MintHCM" logo.
 * If the display of the logos is not reasonably feasible for technical reasons, the
 * Appropriate Legal Notices must display the words "Powered by SugarCRM" and
 * "Supercharged by SuiteCRM" and "Reinvented by MintHCM".
 */

class OrganizationStructure
{
    public $id;

    /**
     * Returns a JSON string of flat employee nodes for d3-org-chart.
     *
     * Replaces the old nested-tree format used by Treant.js.
     * CustomOrganizationStructure can still override this method.
     *
     * JSON_HEX_TAG  — escapes < and > so </script> never appears in the output.
     * JSON_PARTIAL_OUTPUT_ON_ERROR — returns valid JSON even when a field
     *   contains an invalid UTF-8 sequence (replaces bad bytes with null),
     *   preventing the silent json_encode(false) that causes a JS SyntaxError.
     */
    public function getTree(): string
    {
        require_once 'modules/Home/Dashlets/OrganizationStructureDashlet/OrganizationStructureDataProvider.php';
        $strings  = $this->getDashletStrings();
        $provider = new OrganizationStructureDataProvider();
        $encoded  = json_encode(
            $provider->getData($strings['LBL_VIRTUAL_ROOT_NAME'] ?? 'Organisation'),
            JSON_HEX_TAG | JSON_PARTIAL_OUTPUT_ON_ERROR
        );
        return $encoded !== false ? $encoded : '[]';
    }

    /**
     * Loads and returns the dashlet language array for the current language,
     * falling back to en_us when the requested language file is absent.
     */
    private function getDashletStrings(): array
    {
        $language = $GLOBALS['current_language'] ?? 'en_us';
        $dashletStrings = [];
        $path = "modules/Home/Dashlets/OrganizationStructureDashlet/OrganizationStructureDashlet.{$language}.lang.php";
        if (!file_exists($path)) {
            $path = 'modules/Home/Dashlets/OrganizationStructureDashlet/OrganizationStructureDashlet.en_us.lang.php';
        }
        require $path;
        return $dashletStrings['OrganizationStructureDashlet'] ?? [];
    }

    /**
     * @deprecated Since migration to d3-org-chart. getTree() no longer calls
     *             this method — it delegates entirely to
     *             OrganizationStructureDataProvider::getData().
     *             Kept for backward compatibility with CustomOrganizationStructure
     *             subclasses that may still override it.
     */
    protected function getQuery()
    {
        $siteURL = $GLOBALS['sugar_config']['site_url'];
        return "SELECT
                CONCAT('{\"name\":\"',ou.name,'\"}') AS text
                , ou.group_type AS 'HTMLclass'
                , CONCAT('_',MD5(ou.id)) AS oid
                , IF(ou.parent_id IS NULL OR ou.parent_id='', '', CONCAT('_',MD5(ou.parent_id))) AS parent_id
                , IF(ou.parent_id IS NULL OR ou.parent_id='', '', CONCAT('_',MD5(ou.parent_id))) AS parent_id2
                , IF(ou.parent_id='', '', ou.group_type='department')  AS  collapsed
                , '' AS image
            FROM securitygroups ou
            WHERE ou.deleted=0 AND ou.group_type IN ('department', 'team')
        UNION ALL
            SELECT
                CONCAT('{\"name\":\"',p.name,'\",\"title\": {\"val\": \"',u.first_name, ' ',u.last_name,'\", \"href\":\"{$siteURL}legacy/index.php?module=Employees&action=DetailView&record=',u.id,'\"}}') AS text
                , ' '  AS 'HTMLclass'
                , CONCAT('_',MD5(CONCAT(ou.id,u.id))) AS oid
                , CONCAT('_',MD5( ou.id)) AS parent_id
                , CONCAT('_',MD5( ou.id)) AS parent_id2
                , '' AS  collapsed
                , IF(u.photo IS NOT NULL AND u.photo!='', CONCAT('{$siteURL}/index.php?entryPoint=download&type=Users&id=',u.id,'_photo&time=',now()),'') AS image
            FROM securitygroups ou
            INNER JOIN users u ON ou.current_manager_id=u.id and u.deleted=0
            INNER JOIN positions p on u.position_id = p.id
            WHERE ou.deleted=0  AND ou.group_type IN ('department', 'team')
        UNION ALL
            SELECT
                
                CONCAT('{\"name\":\"',p.name,'\",\"title\": {\"val\": \"',u.first_name, ' ',u.last_name,'\", \"href\":\"{$siteURL}legacy/index.php?module=Employees&action=DetailView&record=',u.id,'\"}}') AS text
                , '-' AS 'HTMLclass'
                , CONCAT('_',MD5(CONCAT(ou.id,u.id))) AS oid
                , CONCAT('_',MD5(CONCAT(ou.id,u.reports_to_id))) AS parent_id
                , CONCAT('_',MD5(CONCAT(ou.id,ou.current_manager_id))) AS parent_id2
                , '' AS  collapsed
                
                , IF(u.photo IS NOT NULL AND u.photo!='', CONCAT('{$siteURL}/index.php?entryPoint=download&type=Users&id=',u.id,'_photo&time=',now()),'') AS image
            FROM
                securitygroups ou
            INNER JOIN users u
                ON u.securitygroup_id = ou.id  and u.id !=ou.current_manager_id
            INNER JOIN positions p on u.position_id = p.id
            WHERE u.employee_status IN ('Active', 'during_termination')  AND ou.group_type IN ('department', 'team')
";
    }
    /**
     * @deprecated Since migration to d3-org-chart. getTree() no longer calls
     *             this method — it delegates entirely to
     *             OrganizationStructureDataProvider::getData().
     *             Kept for backward compatibility with CustomOrganizationStructure
     *             subclasses that may still override it.
     */
    protected function getDataBySQL()
    {
        global $db;
        $sql = $this->getQuery();
        $sql_result = $db->query($sql);
        $ous = [];
        while ($row = $db->fetchByAssoc($sql_result)) {
            $ous[] = $row;
        };
        return $ous;
    }
    /**
     * @deprecated Since migration to d3-org-chart. getTree() no longer calls
     *             this method — it delegates entirely to
     *             OrganizationStructureDataProvider::getData().
     *             Kept for backward compatibility with CustomOrganizationStructure
     *             subclasses that may still override it.
     */
    protected function getData()
    {
        return $this->getDataBySQL();
    }

    /**
     * @deprecated Since migration to d3-org-chart. getTree() no longer calls
     *             this method — it delegates entirely to
     *             OrganizationStructureDataProvider::getData().
     *             Kept for backward compatibility with CustomOrganizationStructure
     *             subclasses that may still override it.
     */
    protected function buildTree(array &$elements, $parentId = '', $parent2 = false)
    {
        $branch = array();
        foreach ($elements as $k => $element) {
            if ($element['parent_id'] == $parentId) {
                $children = $this->buildTree($elements, $element['oid']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[] = $element;
                $elements[$k]['assigned'] = 1;
            }
        }
        if ($parentId == '' && $parent2 === false && array_sum(array_column($elements, 'assigned')) != count($elements)) {
            foreach ($elements as $k => $element) {
                if (!empty($element['parent_id2']) && $element['parent_id2'] == $parentId) {
                    $children = $this->buildTree($elements, $element['oid']);
                    if ($children) {
                        $element['children'] = $children;
                    }
                    $branch[] = $element;
                    $elements[$k]['assigned'] = 1;
                }
            }
        }
        return $branch;
    }

    public function displayFullScreen($id)
    {
        $ss = new Sugar_Smarty();
        $ss->assign('id', $id);
        $ss->assign('height', '1500');
        $ss->assign('fullscreen', true);
        $ss->assign('DASHLET_STRINGS', $this->getDashletStrings());

        $jsonTree = $this->getTree();
        $ss->assign('jsonTree', $jsonTree);

        $themeObject = SugarThemeRegistry::current();
        $logoUrl = explode('?', $themeObject->getImageURL('company_logo.png'))[0];
        $ss->assign('logoUrl', $logoUrl);
        $ss->assign('systemName', $GLOBALS['system_config']->settings['system_name'] ?? '');

        $body = $ss->fetch('modules/Home/Dashlets/OrganizationStructureDashlet/OrganizationStructureDashlet.tpl');

        $siteUrl = rtrim($GLOBALS['sugar_config']['site_url'] ?? '', '/');
        $faviconUrl = htmlspecialchars($siteUrl . '/favicon.ico', ENT_QUOTES, 'UTF-8');

        return '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8">'
            . '<link rel="icon" href="' . $faviconUrl . '">'
            . '</head><body>' . $body . '</body></html>';
    }
}
