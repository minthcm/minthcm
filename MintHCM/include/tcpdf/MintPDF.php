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
 * Copyright (C) 2018-2019 MintHCM
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

//require_once('include/tcpdf/config/lang/eng.php');
require_once('include/tcpdf/tcpdf.php');

class MintPDF extends TCPDF {

   var $footer;
   var $header;

   function setHeaderBody($h) {
      $this->header = $h;
   }

   function setFooterBody($f) {
      $this->footer = $f;
   }

   function Header() {
      /* if($this->getPage()==$this->numpages){
        $this->writeHTML("<div>jakis footer</div>");
        }else */
      if ( $this->header != null ) {
         $this->writeHTMLCell(0, '', '', '', $this->header, 0, 1);
         //   $this->Ln('',ture);
      } else {
         parent::Header();
      }
   }

   function Footer() {
      global $current_user, $timedate;
      //  $userCurrentTime = time();
      //  $offset = $timedate->getUserTimeZone($current_user);
      $userCurrentDateforamat = $timedate->get_date_time_format($current_user);

      //$user_time = gmdate('Y-m-d H:i', $userCurrentTime); 
      // $userCurrentTime += $offset['gmtOffset']*60+$offset['dstOffset'] ;
      $curentDate = Date($userCurrentDateforamat);

      $pagenumtxt = $curentDate;
      $this->Cell(0, 0, $pagenumtxt, 'T', 0, 'L');

      if ( $this->footer != null ) {
         $this->writeHTML($this->footer);
      } else {
         parent::Footer();
      }
      //$pagenumtxt = $this->l['w_page'].' '.$this->getAliasNumPage().' of '.$this->getAliasNbPages();
      //$this->writeHTML("<div>jakis footer  ".$pagenumtxt."</div>");
      //parent::Footer();
   }

   function printPageNO() {

      $pagenumtxt = $this->getAliasNbPages();
      //$this->Cell(0, 0, $pagenumtxt, 'T', 0, 'L');

      $this->writeHTML($pagenumtxt, false);
   }

   function printPageCO() {

      $pagenumtxt = $this->getAliasNumPage();
      //$this->Cell(0, 0, $pagenumtxt, 'T', 0, 'L');

      $this->writeHTML($pagenumtxt, false);
   }

   function printPage($format) {

      $pagenumtxt = $this->l['w_page'] . ' ' . $this->getAliasNumPage() . " " . $format . "a " . $this->getAliasNbPages();
      $this->Cell(0, 0, $pagenumtxt, 'T', 0, 'L');

      //$this->writeHTML($p."  {pnb} / {nb}");
   }

}
