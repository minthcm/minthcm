<?php

/* * *******************************************************************************
 * SugarCRM is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2011 SugarCRM Inc.
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
 * FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License for more
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
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo. If the display of the logo is not reasonably feasible for
 * technical reasons, the Appropriate Legal Notices must display the words
 * "Powered by SugarCRM".
 * ****************************************************************************** */

/**
 * THIS CLASS IS FOR DEVELOPERS TO MAKE CUSTOMIZATIONS IN
 */
require_once 'modules/PDFTemplates/PDFTemplates_sugar.php';
require_once 'modules/Administration/QuickRepairAndRebuild.php';

class PDFTemplates extends PDFTemplates_sugar
{

    public function save($check_notify = false)
    {
        if (empty($this->id)) {
            $this->new_with_id = true;
            $this->id = create_guid();
        }
        $this->resetDefaultTemplate();
        $this->saveTemplateFile();
        parent::save($check_notify);
        require_once 'modules/PDFGenerator/ButtonParser.php';
        $genController = new ButtonParser();
        $genController->rebuild($this->relatedmodule);
    }

    public function resetDefaultTemplate()
    {
        if ($this->is_default) {
            $query = "UPDATE pdftemplates SET is_default = '0' WHERE relatedmodule='" . $this->relatedmodule . "' AND is_default=1 AND deleted=0";
            $this->db->query($query);
        }
    }

    public function saveTemplateFile()
    {
        //Unset previous default template if this one was set to be default
        header('Content-type: text/html; charset=utf-8');
        mb_internal_encoding('UTF-8');
        //fix polish letter
        $this->template = str_replace(array('&Oacute;', '&oacute;'), array('Ó', 'ó'), $this->template);
        $this->template = str_replace('§', '&sect;', $this->template);

        //save template as file
        $handle = fopen("modules/PDFTemplates/templates/template-" . $this->id . ".html", "w+");
        $tmp = html_entity_decode($this->template);

        //fix &nbsp;
        $tmp = html_entity_decode($tmp, ENT_COMPAT | ENT_HTML401, 'UTF-8');
        $tmp = str_replace(chr(194), "", str_replace(chr(160), "&nbsp;", $tmp));
        fwrite($handle, $tmp);
        fclose($handle);
    }

    public function retrieve($id = -1, $encode = true, $deleted = true)
    {
        parent::retrieve($id, $encode, $deleted);
        //load template file
        if (!empty($this->id)) {
            $filename = "modules/PDFTemplates/templates/template-" . $this->id . ".html";
            $content = file_get_contents($filename);
            $this->template = mb_convert_encoding($content, 'HTML-ENTITIES', "UTF-8");
        }
        return $this;
    }

    public function getFilename()
    {
        $filename = "";
        if (!empty($this->id)) {
            $filename = "modules/PDFTemplates/templates/template-" . $this->id . ".html";
        }
        return $filename;
    }

}
