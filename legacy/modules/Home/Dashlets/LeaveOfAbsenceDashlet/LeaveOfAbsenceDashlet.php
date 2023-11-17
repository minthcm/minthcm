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

class LeaveOfAbsenceDashlet extends Dashlet
{
    protected $url = 'http://www.sugarcrm.com/crm/aggregator/rss/1';
    protected $height = '200'; // height of the pad
    protected $images_dir = 'modules/Home/Dashlets/LeaveOfAbsenceDashlet/images';
    protected $show_days_of_week = [
        'sunday' => false, // 0 - sunday
        'monday' => true,
        'tuesday' => true,
        'wednesday' => true,
        'thursday' => true,
        'friday' => true,
        'saturday' => false, // 6 - saturday
    ];
    protected $first_day_of_week = 1;

    /**
     * Constructor
     *
     * @global string current language
     * @param guid $id id for the current dashlet (assigned from Home module)
     * @param array $def options saved for this dashlet
     */
    public function __construct($id, $def)
    {
        $this->loadLanguage('LeaveOfAbsenceDashlet', 'modules/Home/Dashlets/'); // load the language strings here

        if (!empty($def['height'])) { // set a default height if none is set
            $this->height = $def['height'];
        }

        if (!empty($def['url'])) {
            $this->url = $def['url'];
        }

        if (!empty($def['title'])) {
            $this->title = $def['title'];
        } else {
            $this->title = $this->dashletStrings['LBL_TITLE'];
        }

        $this->first_day_of_week = $GLOBALS['current_user']->get_first_day_of_week(); // 0 - sunday, ..., 6 - saturday

        if (!empty($def['show_days_of_week']) && is_array($def['show_days_of_week'])) {
            $this->show_days_of_week = $def['show_days_of_week'];
        }
        foreach ($this->show_days_of_week as $day => $showed) {
            $this->show_days_of_week[$day] = !!$showed;
        }

        $this->autoRefresh = false;

        parent::__construct($id); // call parent constructor

        $this->isConfigurable = false; // dashlet is configurable
        $this->hasScript = true; // dashlet has javascript attached to it
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
        $ss->assign('height', $this->height);
        $lang = strtolower(substr($GLOBALS['current_language'], 0, 2));
        $ss->assign('lang', $lang);
        $ss->assign('first_day_of_week', $this->first_day_of_week);
        $ss->assign('show_days_of_week', $this->show_days_of_week);
        $str = $ss->fetch('modules/Home/Dashlets/LeaveOfAbsenceDashlet/LeaveOfAbsenceDashlet.tpl');
        return parent::display($this->dashletStrings['LBL_DBLCLICK_HELP']) . $str;
    }

    public function displayScript()
    {

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
        $ss->assign('title', $this->title);
        $ss->assign('show_days_of_week', $this->show_days_of_week);

        return parent::displayOptions() .
        $ss->fetch('modules/Home/Dashlets/LeaveOfAbsenceDashlet/LeaveOfAbsenceDashletOptions.tpl');
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
        $options['autoRefresh'] = 0;
        $options['show_days_of_week'] = $this->show_days_of_week;

        foreach (array_keys($options['show_days_of_week']) as $day) {
            $options['show_days_of_week'][$day] = false;
            if (isset($req['show_' . $day])) {
                $options['show_days_of_week'][$day] = true;
            }
        }
        if (!in_array(true, array_values($options['show_days_of_week']))) {
            $i = 0;
            foreach (array_keys($options['show_days_of_week']) as $day) {
                if ($i == $this->first_day_of_week) {
                    $options['show_days_of_week'][$day] = true;
                    break;
                }
                $i++;
            }
        }
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
