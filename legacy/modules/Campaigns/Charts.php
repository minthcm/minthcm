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

require_once('include/SugarCharts/SugarChartFactory.php');

class campaign_charts
{
    /**
    * Creates opportunity pipeline image as a VERTICAL accumlated bar graph for multiple users.
    * param $datax- the month data to display in the x-axis
    * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
    * All Rights Reserved..
    * Contributor(s): ______________________________________..
    */

    /**
     * @param array $datay
     * @param array $targets
     * @param null|string $campaign_id
     * @param string $cache_file_name
     * @param bool $refresh
     * @param string $marketing_id
     * @return string
     */
    public function campaign_response_by_activity_type($datay= array(), $targets=array(), $campaign_id= null, $cache_file_name='a_file', $refresh=false, $marketing_id='')
    {
        global $app_strings, $mod_strings, $charset, $lang, $barChartColors,$app_list_strings;

        if ($campaign_id) {
            $sugarChart = SugarChartFactory::getInstance('', 'Reports');
            if (!$sugarChart) {
                return false;
            }
            $xmlFile = $sugarChart->getXMLFileName($campaign_id);

            if (!file_exists($xmlFile) || $refresh == true) {
                $GLOBALS['log']->debug("datay is:");
                $GLOBALS['log']->debug($datay);
                $GLOBALS['log']->debug("user_id is: ");
                $GLOBALS['log']->debug("cache_file_name is: $xmlFile");

                $focus = BeanFactory::newBean('Campaigns');

                $query = "SELECT activity_type,target_type, count(*) hits ";
                $query .= " FROM campaign_log ";
                $query .= " WHERE campaign_id = '$campaign_id' AND archived=0 AND deleted=0";

                //if $marketing id is specified, then lets filter the chart by the value
                if (!empty($marketing_id)) {
                    $query .= " AND marketing_id ='$marketing_id'";
                }

                $query .= " GROUP BY  activity_type, target_type";
                $query .= " ORDER BY  activity_type, target_type";
                $result = $focus->db->query($query);
                //$camp_data=$focus->db->fetchByAssoc($result);
                $camp_data = array();
                $leadSourceArr = array();
                $total = 0;
                $total_targeted = 0;
                $rowTotalArr = array();
                $rowTotalArr[] = 0;
                while ($row = $focus->db->fetchByAssoc($result)) {
                    if (!isset($leadSourceArr[$row['activity_type']]['row_total'])) {
                        $leadSourceArr[$row['activity_type']]['row_total'] = 0;
                    }

                    $leadSourceArr[$row['activity_type']][$row['target_type']]['hits'][] = $row['hits'];
                    $leadSourceArr[$row['activity_type']][$row['target_type']]['total'][] = $row['hits'];
                    $leadSourceArr[$row['activity_type']]['outcome'][$row['target_type']] = $row['target_type'];
                    $leadSourceArr[$row['activity_type']]['row_total'] += $row['hits'];

                    if (!isset($leadSourceArr['all_activities'][$row['target_type']])) {
                        $leadSourceArr['all_activities'][$row['target_type']] = array('total' => 0);
                    }

                    $leadSourceArr['all_activities'][$row['target_type']]['total'] += $row['hits'];

                    $total += $row['hits'];
                    if ($row['activity_type'] == 'targeted') {
                        $targeted[$row['target_type']] = $row['hits'];
                        $total_targeted += $row['hits'];
                    }
                }

                foreach ($datay as $key => $translation) {
                    if ($key == '') {
                        //$key = $mod_strings['NTC_NO_LEGENDS'];
                        $key = 'None';
                        $translation = $mod_strings['NTC_NO_LEGENDS'];
                    }
                    if (!isset($leadSourceArr[$key])) {
                        $leadSourceArr[$key] = $key;
                    }


                    if (is_array($leadSourceArr[$key]) && isset($leadSourceArr[$key]['row_total'])) {
                        $rowTotalArr[] = $leadSourceArr[$key]['row_total'];
                    }
                    if (is_array($leadSourceArr[$key]) && isset($leadSourceArr[$key]['row_total']) && $leadSourceArr[$key]['row_total'] > 100) {
                        $leadSourceArr[$key]['row_total'] = round($leadSourceArr[$key]['row_total']);
                    }
                    $camp_data[$translation] = array();
                    foreach ($targets as $outcome => $outcome_translation) {
                        //create alternate text.
                        $alttext = ' ';
                        if (isset($targeted) && isset($targeted[$outcome]) && !empty($targeted[$outcome])) {
                            $alttext = $targets[$outcome] . ': ' . $mod_strings['LBL_TARGETED'] . ' ' . $targeted[$outcome] . ', ' . $mod_strings['LBL_TOTAL_TARGETED'] . ' ' . $total_targeted . ".";
                        }
                        if ($key != 'targeted') {
                            $hits = (isset($leadSourceArr[$key][$outcome]) && is_array($leadSourceArr[$key][$outcome]) && is_array($leadSourceArr[$key][$outcome]['hits'])) ? array_sum($leadSourceArr[$key][$outcome]['hits']) : 0;
                            $alttext .= " $translation " . $hits;
                        }
                        $count = (isset($leadSourceArr[$key][$outcome]) && is_array($leadSourceArr[$key][$outcome]) && is_array($leadSourceArr[$key][$outcome]['total'])) ? array_sum($leadSourceArr[$key][$outcome]['total']) : 0;
                        $camp_data[$translation][$outcome] =
                            array(
                                "numerical_value" => $count,
                                "group_text" => $translation,
                                "group_key" => "",
                                "count" => (string)($count),
                                "group_label" => $alttext,
                                "numerical_label" => "Hits",
                                "numerical_key" => "hits",
                                "module" => 'Campaigns',
                                "group_base_text" => $outcome,
                                "link" => $key
                            );
                    }
                }

                // Since this isn't a standard report chart (with report defs), set the group_by manually so the chart bars show properly
                $sugarChart->group_by = array('activity_type', 'target_type');

                if ($camp_data) {
                    $sugarChart->setData($camp_data);
                } else {
                    $sugarChart->setData(array());
                }

                $sugarChart->setProperties($mod_strings['LBL_CAMPAIGN_RESPONSE_BY_RECIPIENT_ACTIVITY'], "", 'horizontal group by chart');
                $sugarChart->saveXMLFile($xmlFile, $sugarChart->generateXML());
            }

            $width = '100%';
            $return = '';
            $return .= $sugarChart->display($campaign_id, $xmlFile, $width, '480', "");
        } else {
            $GLOBALS['log']->fatal('no campaign id');
            $return = false;
        }
        return $return;
    }

}// end charts class
