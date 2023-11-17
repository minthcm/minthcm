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
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once 'include/Dashlets/Dashlet.php';
require_once 'include/Sugar_Smarty.php';

class OrganizationStructureDashlet extends Dashlet
{
    protected $url = 'http://www.sugarcrm.com/crm/aggregator/rss/1';
    protected $height = '680'; // height of the pad
    protected $use_image = false;
    protected $images_dir = 'modules/Home/Dashlets/OrganizationStructureDashlet/images';

    /**
     * Constructor
     *
     * @global string current language
     * @param guid $id id for the current dashlet (assigned from Home module)
     * @param array $def options saved for this dashlet
     */
    public function __construct($id, $def)
    {
        $this->loadLanguage('OrganizationStructureDashlet', 'modules/Home/Dashlets/'); // load the language strings here

        if (!empty($def['height'])) {
            $this->height = $def['height'];
        }

        if (!empty($def['url'])) {
            $this->url = $def['url'];
        }
        if (!empty($def['use_image'])) {
            $this->use_image = $def['use_image'];
        }

        if (!empty($def['title'])) {
            $this->title = $def['title'];
        } else {
            $this->title = $this->dashletStrings['LBL_TITLE'];
        }

        $this->autoRefresh = false;
        parent::__construct($id);

        $this->isConfigurable = false;
        $this->hasScript = true;
    }

    /**
     * Displays the dashlet
     *
     * @return string html to display dashlet
     */
    public function display()
    {
        $ss = new Sugar_Smarty();
        $ss->assign('saving', $this->dashletStrings['LBL_SAVING']);
        $ss->assign('saved', $this->dashletStrings['LBL_SAVED']);
        $ss->assign('DASHLET_STRINGS', $this->dashletStrings);
        $ss->assign('id', $this->id);
        $ss->assign('use_image', $this->use_image);
        $ss->assign('height', $this->height);
        $ss->assign('rootElement', $this->getRootElement());
        $ss->assign('fullscreen', false);

        $lang = strtolower(substr($GLOBALS['current_language'], 0, 2));
        SugarAutoLoader::requireWithCustom('modules/Home/Dashlets/OrganizationStructureDashlet/OrganizationStructure.php');
        $class_name = 'OrganizationStructure';
        if(class_exists('Custom'.$class_name)){
            $class_name = 'CustomOrganizationStructure';
        }
        $jsonTree = (new $class_name)->getTree();
        $ss->assign('jsonTree', $jsonTree);
        $str = $ss->fetch('modules/Home/Dashlets/OrganizationStructureDashlet/OrganizationStructureDashlet.tpl');
        return parent::display($this->dashletStrings['LBL_DBLCLICK_HELP']) . $str;
    }

    public function displayScript()
    {

    }
    public function getRootElement()
    {
        if ($this->use_image) {
            $logo = $this->getLogo();
            if (!empty($logo)) {
                return 'image: "' . $logo . '",HTMLclass: "rootWithImage" ';
            }
        }
        $text = $this->getBrowserTitle();
        if (!empty($text)) {
            return 'text: {name: "' . $text . '" },HTMLclass: "rootNoImage" ';
        }
    }
    public function getBrowserTitle()
    {
        return (!empty($GLOBALS['system_config']->settings['system_name']) ? urlencode($GLOBALS['system_config']->settings['system_name']) : '');
    }
    public function getLogo()
    {
        $themeObject = SugarThemeRegistry::current();
        $companyLogoURL = $themeObject->getImageURL('company_logo.png');
        $companyLogoURL_arr = explode('?', $companyLogoURL);
        $companyLogoURL = $companyLogoURL_arr[0];
        return $companyLogoURL;
    }

    /**
     * @see Dashlet::displayOptions()
     */
    public function displayOptions()
    {
        global $app_strings, $mod_strings;
        $ss = new Sugar_Smarty();
        $ss->assign('id', $this->id);
        $ss->assign('DASHLET_STRINGS', $this->dashletStrings);
        $ss->assign('titleLbl', $this->dashletStrings['LBL_CONFIGURE_TITLE']);
        $ss->assign('heightLbl', $this->dashletStrings['LBL_CONFIGURE_HEIGHT']);
        $ss->assign('imageLbl', $this->dashletStrings['LBL_USE_IMAGE']);
        $ss->assign('saveLbl', $this->dashletStrings['LBL_SAVE_BUTTON_LABEL']);
        $ss->assign('title', $this->title);
        $ss->assign('use_image', $this->use_image);
        $ss->assign('height', $this->height);
        return parent::displayOptions() .
        $ss->fetch('modules/Home/Dashlets/OrganizationStructureDashlet/OrganizationStructureDashletOptions.tpl');
    }

    /**
     * called to filter out $_REQUEST object when the user submits the configure dropdown
     *
     * @param array $req $_REQUEST
     * @return array filtered options to save
     */
    public function saveOptions($req)
    {
        $options = array();
        $options['title'] = $req['title'];
        $options['url'] = $req['url'];
        $options['height'] = $req['height'];
        $options['use_image'] = $req['use_image'];
        $options['autoRefresh'] = 0;
        return $options;
    }

    public function getHeader($text = '')
    {
        $template = new Sugar_Smarty();

        $template->assign('is_locked', $this->is_locked);
        $title = $this->title;
        if (!empty($GLOBALS['app_strings'][$title])) {
            $title = $GLOBALS['app_strings'][$title];
        }
        $template->assign('DASHLET_TITLE', $title);
        $template->assign('DASHLET_ID', $this->id);
        $template->assign('DASHLET_MODULE', 'Calendar');
        $template->assign('DASHLET_BUTTON_ARIA_EDIT', translate('LBL_DASHLET_EDIT', 'Home'));
        $template->assign('DASHLET_BUTTON_ARIA_REFRESH', translate('LBL_DASHLET_REFRESH', 'Home'));
        $template->assign('DASHLET_BUTTON_ARIA_DELETE', translate('LBL_DASHLET_DELETE', 'Home'));

        return $template->fetch('include/Dashlets/DashletHeader.tpl');
    }
}
