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

if (file_exists('custom/include/tcpdf/config/lang/eng.php')) {
    require_once 'custom/include/tcpdf/config/lang/eng.php';
} else {
    require_once 'include/tcpdf/config/lang/eng.php';
}
if (file_exists('custom/include/tcpdf/tcpdf.php')) {
    require_once 'custom/include/tcpdf/tcpdf.php';
} else {
    require_once 'include/tcpdf/tcpdf.php';
}
require_once 'modules/PDFGenerator/lib/MintPDF.php';
require_once 'modules/PDFGenerator/lib/simple_html_dom.php';

class BasePDFGenerator
{

    protected $key = "\$"; //field token symbol
    protected $tag = 'repeat'; //html tag for repetitive block of linked beans
    protected $page_orientation;
    protected $page_format = 'A4'; //TODO
    protected $tagHeader = 'div#pdf_header'; //html tag for repetitive block of linked beans
    protected $tagFooter = 'div#pdf_footer';
    protected $pdf;
    protected $pdftemplate;

    public function __construct($template_id = null, $module_name = null, $params = null)
    {
        $this->initiate($template_id, $module_name, $params);
        $this->pdf = new MintPDF($this->page_orientation, PDF_UNIT, $this->page_format);
    }

    protected function initiate($template_id = null, $module_name = null, $params = null)
    {
        global $pdftemplate;
        $this->pdftemplate = $template_id ? BeanFactory::getBean('PDFTemplates', $template_id) : $pdftemplate; //TODO
        $this->page_orientation = empty($this->pdftemplate->orientation) ? 'P' : $this->pdftemplate->orientation;
    }

    public function Output($root_ids = null, $filename_regex = null, $mode = 'I')
    {
        $tpl = $this->prepareTplCode($this->pdftemplate);
        $arr = $this->findHeader($tpl);
        $this->pdf->setPdfParamsOutput($arr);
        $tpl_body = $arr['body'];
        if ($root_ids) {
            foreach ($root_ids as $bean_id) {
                $bean = $this->getBean($this->pdftemplate->relatedmodule, $bean_id);
                $this->printToPdfPage($bean, $tpl_body, $arr);
                $filename = $this->prepareFileName($bean, $filename_regex); //TMP zadziala dla ostatniego jesli wiecej
            }
            $this->returnPdf($filename, $mode);
        }
    }

    protected function getBean($module, $id, $parent_bean = null)
    {
        global $beanList;
        $bean_list_flipped = array_flip($beanList);
        if (in_array($module, $beanList) && isset($bean_list_flipped[$module])) {
            $module = $bean_list_flipped[$module];
        }
        $bean = BeanFactory::getBean($module, $id);
        $bean->load_relationships();
        $bean->call_custom_logic("before_pdf_generate", array('parent_bean' => $parent_bean));
        return $bean;
    }

    /**
     *
     * @param SugarBean $bean
     * @param type $regex
     * @return type
     */
    protected function prepareFileName($bean, $regex)
    {
        $parts = explode('/', $regex);
        $filename_index = count($parts) - 1;
        $filename = $parts[$filename_index];
        $file_name_tmp = !empty($filename) ? $filename : '$name';
        foreach ($bean->field_defs as $field_def) {
            $val = $bean->{$field_def['name']};
            if ((is_string($val))) {
                $file_name_tmp = str_replace('$' . $field_def['name'], $val, $file_name_tmp);
            }
        }
        $file_name = str_replace(':', '_', trim($file_name_tmp) . '.pdf');
        $parts[$filename_index] = str_replace('/', '_', $file_name);
        return implode('/', $parts);
    }

    /**
     *
     * @param SugarBean $bean
     * @param type $tpl_body
     * @param array $params
     */
    protected function printToPdfPage($bean, $tpl_body, $params)
    {
        $footer = $this->parseAndAnalizeCode($params['footer'], $bean);
        $header = $this->parseAndAnalizeCode($params['header'], $bean);
        $this->pdf->setHeaderBody($header);
        $this->pdf->setFooterBody($footer);
        if (empty($footer)) {
            $this->pdf->SetPrintFooter(false);
        }
        $content = $this->parseAndAnalizeCode($tpl_body, $bean);
        $this->pdf->AddPage($this->page_orientation);
        $this->pdf->writeHTML($content, true, false, false, false, '');
    }

    protected function parseAndAnalizeCode($tpl_str, $bean)
    {
        $bean->call_custom_logic("before_pdf_generate", array());
        $bean->load_relationships();
        $tpl_str = $this->parse($tpl_str, $bean);
        $tpl_str = $this->parseStaticPlaceholders($tpl_str);
        $tpl_str = $this->analize($tpl_str, $bean);
        return $tpl_str;
    }

    protected function parse($tpl_str, $bean, $relationship = "", $counter = 1, $depth = 0)
    {
        if ($tpl_str != '') {
            $field_defs = $bean->field_defs;
            usort($field_defs, 'BasePDFGenerator::sortByNameLength');
            $rel = $this->getRelationshipForParse($relationship);
            $tpl_str = $this->parseRepeatTags($tpl_str, $bean, $depth, $rel);
            if ($tpl_str != '') {
                $bean->fixUpFormatting();
                $field_defs = $bean->field_defs;
                usort($field_defs, 'BasePDFGenerator::sortByNameLength');
                $tpl_str = $this->parseSmarty($field_defs, $tpl_str);
                foreach ($field_defs as &$field) {
                    if ($field['type'] == 'currency') {
                        $currency = new Currency();
                        $currency->retrieve($bean->currency_id);
                    }
                    $tpl_str = $this->prepareField($field, $bean, $tpl_str, $rel);
                }
                $tpl_str = $this->replaceCountAndCurrency($tpl_str, $relationship, $counter, $currency);
            }
        }
        return $tpl_str;
    }

    protected function parseSmarty($field_defs, $tpl_str)
    {

        require_once 'include/Sugar_Smarty.php';
        $ss = new Sugar_Smarty();

        foreach ($field_defs as &$field) {
            $ss->assign($field['name'], $this->prepareFieldValue($field, $bean));
        }
        $tpl_str = $ss->fetch($this->pdftemplate->getFilename());
        return $tpl_str;
    }

    protected function analize($tpl, $bean)
    {
        $pattern = '/\$[a-zA-Z0-9_-]+/';
        $matches = array();
        preg_match_all($pattern, $tpl, $matches, PREG_OFFSET_CAPTURE);
        foreach ($matches[0] as $m) {
            $name = substr($m[0], 1);
            $val = $this->getFieldValue($name, $bean);
            $tpl = str_replace($m[0], $val, $tpl);
        }
        return $tpl;
    }

    protected function parseStaticPlaceholders($tpl_str)
    {
        $tpl_str = str_replace('#CURRENT_PAGE', '{{:pnp:}}', $tpl_str);
        $tpl_str = str_replace('#NO_PAGES', '{{:ptp:}}', $tpl_str);
        $paramsP = $this->pdf->serializeTCPDFtagParameters(array('P'));
        $tpl_str = str_replace('#ADD_PAGE_P', '<tcpdf method="AddPage" params="' . $paramsP . '" />', $tpl_str);
        $paramsL = $this->pdf->serializeTCPDFtagParameters(array('L'));
        $tpl_str = str_replace('#ADD_PAGE_L', '<tcpdf method="AddPage" params="' . $paramsL . '" />', $tpl_str);
        $tpl_str = str_replace('#ADD_PAGE', '<tcpdf method="AddPage" />', $tpl_str);
        global $timedate;
        $date = date($timedate->get_date_format());
        $tpl_str = str_replace('$_CURRENT_DATE', $date, $tpl_str);
        return $tpl_str;
    }

    protected function parseRepeatTags($tpl_str, $bean, $depth, $rel)
    {
        $html = str_get_html($tpl_str);
        $block = $html->find($this->tag, 0);
        while ($block != null) {
            if (isset($block->relationship) && isset($block->type) && $block->type == "link") {
                $block = $this->prepareBlockLink($block, $bean, $depth);
            } else if (isset($block->field) && isset($block->type) && $block->type == "multienum") {
                $block = $this->prepareBlockMultienum($block, $bean, $rel);
            } else {
                $block->outertext = $block->innertext;
            }
            $html_tmp = $html->save();
            $html = str_get_html($html_tmp); //reload Html DOM object
            $block = $html->find($this->tag, 0);
        }
        $tpl_str = $html->save();
        return $tpl_str;
    }

    protected function prepareBlockMultienum($block, $bean, $rel)
    {
        $field_name = str_replace($rel, "", $block->field);
        $new_block = '';
        if (isset($bean->$field_name)) {
            $items = unencodeMultienum($bean->$field_name);
            foreach ($items as &$item) {
                $new_block .= str_replace($this->key . "ITEM", translate($bean->field_defs[$field_name]['options'], $bean->object_name, $item), $block->innertext);
            }
            $block->outertext = $new_block;
        } else {
            $block->outertext = '';
        }
        return $block;
    }

    protected function prepareBlockLink($block, $bean, $depth)
    {
        $block_after = $this->getBlockAfter($block);
        $relate = explode('__', $block_after->relationship);
        $relation_field_name = $relate[$depth];
        return $this->getNewBlockForBlockLink($block, $bean, $relation_field_name, $depth, $block_after);
    }

    protected function setNewBlockForBlockLink($block, $count, $new_block)
    {
        if ($count > 0) {
            if ($this->isTableOutside($block->table_outside)) {
                $block = $block->find("#" . $block->relationship, 0);
                $block->innertext = $new_block;
                $block = $block->parent();
                $block = $block->parent();
                $block->outertext = $block->innertext;
            } else {
                $block->outertext = $new_block;
            }
        } else {
            if (isset($block->intable) && !$this->isTableOutside($block->table_outside)) {
                $block->outertext = '<tr><td></td></tr>';
            } else {
                $block->outertext = '';
            }
        }
        return $block;
    }

    protected function findHeader($str)
    {
        $html = str_get_html($str);
        $block_head = $html->find($this->tagHeader, 0);
        $header = $this->getPartAndHeight($block_head);
        $str_after_head = $html->save();
        $html_foot = str_get_html($str_after_head);
        $block_foot = $html->find($this->tagFooter, 0);
        $footer = $this->getPartAndHeight($block_foot);
        $str_body = $html_foot->save();
        return array('header' => $header[0], 'header_h' => $header[1], 'footer' => $footer[0], 'footer_h' => $footer[1], 'body' => $str_body);
    }

    protected function getPartAndHeight($block)
    {
        $part = '';
        $height = '';
        if ($block != null) {
            $part = $block->outertext;
            $block->outertext = '';
            if (isset($block->style)) {
                $style = $block->style;
                $styles = explode(";", $style);
                $height = $this->getHeightForPart($styles);
            }
        }
        return array($part, $height);
    }

    protected function getHeightForPart($styles)
    {
        $height = '';
        foreach ((array) $styles as $s) {
            $ss = explode(":", $s);
            if ("height" == $ss[0]) {
                $height = $ss[1];
            }
        }
        return $height;
    }

    protected function getModuleName($bean)
    {
        $module_name = $bean->module_name;
        if ($module_name == null) { //BEFORE 6.4
            $module_name = $bean->module_dir;
        }
        return $module_name;
    }

    protected function getFieldValue($name, $bean)
    {
        $r = explode('__', $name);
        if (count($r) == 1) {
            $value = $this->getFieldValueForOne($name, $bean, $r);
        }
        if (count($r) > 1) {
            $value = $this->getFieldValueForRelated($name, $bean, $r);
        }
        return $value;
    }

    public function getFieldValueForOne($name, $bean, $r)
    {
        global $sugar_config;
        $field_defs = $bean->field_defs;
        $type = $field_defs[$name]['type'];
        if ($type == 'currency') {
            $curr = new Currency();
            $curr->retrieve($bean->currency_id);
            $value = number_format((double) $bean->$name, 2, $sugar_config['default_decimal_separator'], $sugar_config['default_number_grouping_seperator']);
        } else if ($type == 'enum') {
            $value = translate($field_defs[$name]['options'], $bean->module_dir, $bean->$name);
            if (is_array($value)) {
                $value = '';
            }
        } else {
            $value = $bean->getFieldValue($r[0]);
        }
        return $value;
    }

    protected function getFieldValueForRelated($name, $bean, $r)
    {
        $value = '';
        $name_tmp = substr($name, strpos($name, '__') + 2);
        $link_defs = $bean->get_linked_fields();
        $field = $link_defs[$r[0]];
        $bean_name = $field["bean_name"];
        $rel_bean = $bean->get_linked_beans($r[0], $bean_name);
        if (!empty($rel_bean)) {
            $rel_bean[0]->call_custom_logic("before_pdf_generate", array());
            $value = $this->getFieldValue($name_tmp, $rel_bean[0]);
        }
        return $value;
    }

    public function prepareTplCode($pdftemplate)
    {
        //Contrain #72254 START
        require_once 'include/Sugar_Smarty.php';
        $ss = new Sugar_Smarty();
        $ss_html = $ss->fetch($pdftemplate->getFilename());
        //Contrain #72254 END
        $template = str_replace('&nbsp;', ' ', $ss_html);

        //$tpl2 = preg_replace(array('/<!--repeat[="_ A-Za-z0-9]+-->/e', '/<!--endrepeat-->/'), array('preg_replace(array("/<!--repeat/", "/-->/"), array("<repeat", ">"), "$0")', '</repeat>'), $template);
        $tpl2 = preg_replace_callback(array('/<!--repeat[="_ A-Za-z0-9]+-->/'), function ($matches) {
            return preg_replace(array("/<!--repeat/", "/-->/"), array("<repeat", ">"), $matches);
        }, $template);
        $tpl3 = preg_replace(array('/<!--endrepeat-->/'), '</repeat>', $tpl2);
        $tpl4 = str_replace(array('<style><!--', '--></style>'), array('<style>', '</style>'), $tpl3);

        $tpl = html_entity_decode($tpl4);
        return $tpl;
    }

    public function returnPdf($filename, $mode = 'I')
    {
        if (isset($_GET["file"])) {
            return $this->pdf->Output($_GET["file"], 'F');
        } else {
            return $this->pdf->Output($filename, $mode);
        }
    }

    public function replaceCountAndCurrency($tpl_str, $relationship, $counter, $currency)
    {
        $tpl_str = str_replace($this->key . "COUNT_" . $relationship, $counter, $tpl_str);
        if (isset($currency)) {
            $tpl_str = str_replace($this->key . "CURRENCY_ISO_" . $relationship, $currency->iso4217, $tpl_str);
            $tpl_str = str_replace($this->key . "CURRENCY_SYMBOL_" . $relationship, $currency->symbol, $tpl_str);
        }
        return $tpl_str;
    }

    public function getRelationshipForParse($relationship)
    {
        if ($relationship != '') {
            $rel = $relationship . "__"; //add _ between relationship name and field name in a composed token
        } else {
            $rel = '';
        }
        return $rel;
    }

    public function isTableOutside($table_outside)
    {
        if ($table_outside == true) {
            return true;
        }
        return false;
    }

    public function getBlockAfter($block)
    {
        if ($this->isTableOutside($block->table_outside)) {
            $block_after = $block->find("#" . $block->relationship, 0);
        } else {
            $block_after = $block;
        }
        return $block_after;
    }

    public function prepareField(&$field, $bean, $str, $rel)
    {

        //omit relate and link field types
        if ((array_search('link', $field) !== 'type') ||
            ($field['name'] == "created_by_name" ||
                $field['name'] == "modified_by_name" ||
                $field['name'] == "assigned_user_name")
        ) {
            $value = $this->prepareFieldValue($field, $bean);

            $occ = 0;
            while (($pos = strpos($str, $this->key . $rel . $field['name'], $occ))) {
                if ($str[$pos + strlen($this->key . $rel . $field['name'])] != '_') {
                    $str = substr_replace($str, $value, $pos, strlen($this->key . $rel . $field['name']));
                } else {
                    $occ++;
                }
            }
        }
        return $str;
    }

    public function prepareFieldValue(&$field, $bean)
    {
        global $sugar_config;
        if ($field['type'] == 'currency') {
            $curr = new Currency();
            $curr->retrieve($bean->currency_id);
            $value = number_format((double) $bean->{$field['name']}, 2, $sugar_config['default_decimal_separator'], $sugar_config['default_number_grouping_seperator']);
        } else if ($field['type'] == 'enum') {
            $value = $this->prepareEnumField($field, $bean);
        } else if ($field['type'] == 'date') {
            $value = $this->getFormattedDate($bean->{$field['name']});
        } else if ($field['type'] == 'datetime' || $field['type'] == 'datetimecombo') {
            $value = $this->getFormattedDateTime($bean->{$field['name']});
        } else if ($field['type'] == 'time') {
            $value = $this->getFormattedTime($bean->{$field['name']});
        } else {
            $value = $bean->{$field['name']};
        }
        return $value;
    }

    protected function prepareEnumField($field, $bean)
    {
        if ($field['name'] == 'vat' && isset($field['function']['name']) && isset($field['function']['include'])) {
            include_once $field['function']['include'];
            $taxRates = $field['function']['name']();
            $value = array_key_exists($bean->$field['name'], $taxRates) ? $taxRates[$bean->{$field['name']}] : '';
        } else {
            $value = translate($field['options'], $bean->module_dir, $bean->{$field['name']});
            if (is_array($value)) {
                $value = '';
            }
        }
        return $value;
    }

    protected function getFormattedDate($date_string)
    {
        global $timedate;
        $date = $this->getDateTimeObject($date_string);
        return ($date) ? $date->format($timedate->get_date_format()) : '';
    }

    protected function getFormattedDateTime($date_string)
    {
        global $timedate;
        $date = $this->getDateTimeObject($date_string);
        return ($date) ? $date->format($timedate->get_date_time_format()) : '';
    }

    protected function getFormattedTime($date_string)
    {
        global $timedate;
        $date = $this->getDateTimeObject($date_string);
        return ($date) ? $date->format($timedate->get_time_format()) : '';
    }

    protected function getDateTimeObject($date_string)
    {
        global $timedate;
        $date_object = DateTime::createFromFormat($timedate->get_date_format(), $date_string);
        if (!$date_object) {
            $date_object = DateTime::createFromFormat($timedate->get_db_date_format(), $date_string);
        }
        if (!$date_object) {
            $date_object = DateTime::createFromFormat($timedate->get_date_time_format(), $date_string);
        }
        if (!$date_object) {
            $date_object = DateTime::createFromFormat($timedate->get_db_date_time_format(), $date_string);
        }
        return $date_object;
    }

    protected static function sortByNameLength($a, $b)
    {
        if ((!isset($a['name'])) || (!isset($b['name']))) {
            return 0;
        }
        return (strlen($b['name']) - strlen($a['name']));
    }

}
