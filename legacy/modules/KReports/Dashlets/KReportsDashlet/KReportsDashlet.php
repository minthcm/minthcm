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

if ( !defined('sugarEntry') || !sugarEntry )
   die('Not A Valid Entry Point');

require_once('include/Dashlets/Dashlet.php');
require_once('modules/KReports/KReport.php');

class KReportsDashlet extends Dashlet {

   const MIN_DASHLET_HEIGHT = 150;
   const MAX_DASHLET_HEIGHT = 500;

   public $report_id;
   public $show_chart = true;
   public $show_data = true;
   public $show_filters = true;
   public $height = self::MAX_DASHLET_HEIGHT;

   public function __construct($id, $def = null) {
      $this->loadLanguage('KReportsDashlet', 'modules/KReports/Dashlets/');

      parent::__construct($id);
      $this->title = (empty($def['title'])) ? $this->dashletStrings['LBL_DEFAULT_TITLE'] : $def['title'];
      $this->report_id = $def['report_id'];

      $this->show_chart = (isset($def['show_chart'])) ? $def['show_chart'] : true;
      $this->show_data = (isset($def['show_data'])) ? $def['show_data'] : true;
      $this->show_filters = (isset($def['show_filters'])) ? $def['show_filters'] : true;
      $this->height = (isset($def['height'])) ? $def['height'] : $this->height;
      $this->autoRefresh = (isset($def['autoRefresh'])) ? $def['autoRefresh'] : '0';

      $this->seedBean = new KReport();
      $this->isConfigurable = true;
      $this->hasScript = false;
   }

   public function display() {
      $ss = new Sugar_Smarty();

      $height = intval($this->height);
      if ( $height < self::MIN_DASHLET_HEIGHT ) {
         $height = self::MIN_DASHLET_HEIGHT;
      } else if ( $height > self::MAX_DASHLET_HEIGHT ) {
         $height = self::MAX_DASHLET_HEIGHT;
      }

      $ss->assign('id', $this->id);
      $ss->assign('title', $this->title);
      $ss->assign('report_id', $this->report_id);
      $ss->assign('show_chart', $this->show_chart);
      $ss->assign('show_data', $this->show_data);
      $ss->assign('show_filters', $this->show_filters);
      $ss->assign('height', $height);
      $ss->assign('dashletStrings', $this->dashletStrings);

      return $ss->fetch('modules/KReports/Dashlets/KReportsDashlet/KReportsDashlet.tpl');
   }

   public function displayOptions() {
      global $app_strings;

      $ss = new Sugar_Smarty();

      $ss->assign('report_id', '');
      $ss->assign('report_name', '');
      if ( !empty($this->report_id) ) {
         $report = BeanFactory::getBean('KReports', $this->report_id);

         if ( $report->id === $this->report_id ) {
            $ss->assign('report_id', $report->id);
            $ss->assign('report_name', $report->name);
         }
      }

      $ss->assign('id', $this->id);
      $ss->assign('title', $this->title);
      $ss->assign('show_chart', $this->show_chart);
      $ss->assign('show_data', $this->show_data);
      $ss->assign('show_filters', $this->show_filters);
      $ss->assign('height', $this->height);

      if ( $this->isAutoRefreshable() ) {
         $ss->assign('isRefreshable', true);
         $ss->assign('autoRefreshOptions', $this->getAutoRefreshOptions());
         $ss->assign('autoRefreshSelect', $this->autoRefresh);
      }

      $this->dashletStrings['LBL_DASHLET_HEIGHT_HELP'] = sprintf($this->dashletStrings['LBL_DASHLET_HEIGHT_HELP'], self::MIN_DASHLET_HEIGHT, self::MAX_DASHLET_HEIGHT);
      $ss->assign('dashletStrings', $this->dashletStrings);
      $ss->assign('APP', $app_strings);

      return $ss->fetch('modules/KReports/Dashlets/KReportsDashlet/KReportsDashletConfigure.tpl');
   }

   public function saveOptions($req) {
      $options = array();

      $options['title'] = $req['title'];
      $options['report_id'] = $req['report_id'];
      $options['show_chart'] = (isset($req['show_chart'])) ? true : false;
      $options['show_data'] = (isset($req['show_data'])) ? true : false;
      $options['show_filters'] = (isset($req['show_filters'])) ? true : false;
      $options['height'] = $req['height'];
      $options['autoRefresh'] = $req['autoRefresh'];

      return $options;
   }

}
