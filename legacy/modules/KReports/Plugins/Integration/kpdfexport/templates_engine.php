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

/**
 * Klasa służy do generowania plików PDF. Współpracuje z modułem KReport. Wykorzystywana jest przez KTemplates oraz z poziomu KReportera.
 */
class TemplateEngine {

   private $all_text;

   /**
    * Do fukncji dodajemy szablon, wczytany już z pliku.
    * @param unknown $text
    */
   function __construct($text) {
      $this->all_text = $text;
   }

   function getTop() {
      return substr($this->all_text, 0, strpos($this->all_text, "[header]"));
   }

   function getEnd() {
      $text = substr($this->all_text, strpos($this->all_text, "[/row]") + 6);
      return $text;
   }

   /**
    * Funkcja tworzy część nagłówkową bazując na szablonie domyślnym
    * @return Array string
    */
   function getHeader() {
      $text = strstr($this->all_text, "[header]");
      $text = substr($text, 8);
      $text = substr($text, 0, strpos($text, "[/header]"));
      $header['start'] = substr($text, 0, strpos($text, "[cell]"));
      $header['cell_start'] = strstr($text, "[cell]");
      $header['cell_start'] = substr($header['cell_start'], 6);
      $header['cell_start'] = substr($header['cell_start'], 0, strpos($header['cell_start'], "[value]"));
      $header['cell_end'] = strstr($text, "[value]");
      $header['cell_end'] = substr($header['cell_end'], 7);
      $header['cell_end'] = substr($header['cell_end'], 0, strpos($header['cell_end'], "[/cell]"));
      $header['end'] = strstr($text, "[/cell]");
      $header['end'] = substr($header['end'], 7);
      return $header;
   }

   /**
    * Funkcja generuje szablonik dla każdego wiersza, bazuje na szablonie domyślnym
    * @return Array string
    */
   function getRow() {
      $text = strstr($this->all_text, "[row]");
      $text = substr($text, 5);
      $text = substr($text, 0, strpos($text, "[/row]"));
      $header['start'] = substr($text, 0, strpos($text, "[cell]"));
      $header['cell_start'] = strstr($text, "[cell]");
      $header['cell_start'] = substr($header['cell_start'], 6);
      $header['cell_start'] = substr($header['cell_start'], 0, strpos($header['cell_start'], "[value]"));
      $header['cell_end'] = strstr($text, "[value]");
      $header['cell_end'] = substr($header['cell_end'], 7);
      $header['cell_end'] = substr($header['cell_end'], 0, strpos($header['cell_end'], "[/cell]"));
      $header['end'] = strstr($text, "[/cell]");
      $header['end'] = substr($header['end'], 7);
      return $header;
   }

   /**
    * Funkcja zamienia w szablonie komentarze na znaczniki htmlowskie.
    * @return array
    */
   function getVariables() {
      $tpl = $this->all_text;
      $tpl = html_entity_decode($tpl);
      $tpl = str_replace('&nbsp;', ' ', $tpl);

      $tpl = str_replace(array( '<!--repeat-->', '<!--endrepeat-->' ), array( '<repeat>', '</repeat>' ), $tpl);

      require_once('modules/KReports/simple_html_dom.php');

      // Wykryte błędy w ułożeniu zmiennych
      $this->parse_errors = array();

      // Funkcja parseSyntax zwraca informacje o strukturze i położeniu zmiennych
      $variables = $this->parseSytnax($tpl);
      return $variables;
   }

   /**
    * Funkcja analizuje położenie poszczególnych zmiennych i zwraca tablicę.
    * @param $tpl szablon pdf z zamienionymi komentarzami <!-- --> na znaczniki <>
    * @return array
    */
   function parseSytnax($tpl) {
      $html = $tpl;

      $html = str_get_html($tpl);

      $block = $html->find('repeat', 0);

      $var_array = array();

      $tpl = $html;
      preg_match_all('/\$([a-z_0-9A-Z]+)?/', $tpl, $matches);

      // Clear duplicates
      $variables = array_unique($matches[0]);


      if ( count($matches[0]) > 0 ) {
         foreach ( $matches[0] as $key => $variable ) {
            $tpl = str_replace($variable, $matches[1][$key], $tpl);
            $var_array[] = $variable;
         }
      }

      return $var_array;
   }

   /**
    * Funkcja wykonywana przed odpalenie hooka pdf. Z racji, iż w pliku obsługującym bean dany raport nie posiada rekordów. Dlatego funkcja ta wyciąga je i przypisuje się potem rekordy do $focus->records
    * @return array
    */
   function getRecords($fieldIdArray, $fieldArray, $results) {
      if ( count($results) > 0 ) {
         foreach ( $results as $record_key => $record ) {
            foreach ( $record as $key => $value ) {
               $arrayIndex = array_search($key, $fieldIdArray);
               $records[$record_key][$fieldArray[$arrayIndex]['name']] = $value;
            }
         }
      }
      return $records;
   }

   /**
    * Funkcja służy do wygenerowania całego htmla na podstawie własnego szablonu
    * @param Array $variables, tablica, w której znajdują się zmienne znalezione w szablonie pdf
    * @param Array $fieldIdArray, tablica z indeksami pól
    * @param Array $fieldArray, tablica z definicjami pól
    * @param Array $results, tablica rekordów.
    * @param Array $pdf_var, zmienna w której przechowywane są zmienne z hooka. Gdzie za klucz podaje się nazwę zmiennej bez $ a wartość znajdzie się w miejscu tej zmiennej
    * @return HTML
    */
   function getFullHtml($variables, $fieldIdArray, $fieldArray, $results, $pdf_var = null) {
      $tpl = str_replace(array( '<!--repeat-->', '<!--endrepeat-->' ), array( '<repeat>', '</repeat>' ), $this->all_text);
      $repeat = strstr($tpl, "<repeat>");
      $repeat = substr($repeat, 8);
      $repeat = substr($repeat, 0, strpos($repeat, "</repeat>")); //wyciagamy fragment, który będzie miał się powtarzać
      $html_records = array();
      if ( count($results) > 0 ) {
         foreach ( $results as $record_key => $record ) {
            $html_records[$record_key] = $repeat; //tworzymy nowy wiersz w tablicy, który domyślnie otrzymuje html z niezamienionymi zmiennymi
            foreach ( $record as $key => $value ) {
               $arrayIndex = array_search($key, $fieldIdArray);
               if ( array_search($key, $fieldIdArray) !== false ) {
                  $fieldname = $fieldArray[$arrayIndex]['name'];
                  $search = "\$$fieldname";
                  $html_records[$record_key] = str_replace($search, $value, $html_records[$record_key]); //podmieniamy zmienną na wartość tego rekordu
                  $thisReport->records[$record_key][$fieldname] = $value;
               }
            }
         }
      }
      $html = substr($tpl, 0, strpos($tpl, "<repeat>"));
      foreach ( $html_records as $h_r ) {
         $html .= $h_r; //wciskamy nasze zebrane rekordy pomiędzy znaczniki repeat w szablonie, które zostaną usunięte
      }
      $end = strstr($tpl, "</repeat>");
      $html .= substr($end, 9);
      if ( $pdf_var != null ) {
         foreach ( $pdf_var as $pdf_var_key => $var )
            $html = str_replace("\$$pdf_var_key", $var, $html); //zamieniamy zmienne, które znalazy się z hooka
      }
      return $html;
   }

   /**
    * Function is used in field widget in KTemplates.
    * Służy do wygenerowania pdfa w widgecie KTemplates.
    */
   function getPreview($vardefs) {
      global $timedate;
      $tpl = str_replace(array( '<!--repeat-->', '<!--endrepeat-->' ), array( '<repeat>', '</repeat>' ), $this->all_text);
      $repeat = strstr($tpl, "<repeat>");
      $repeat = substr($repeat, 8);
      $repeat = substr($repeat, 0, strpos($repeat, "</repeat>"));
      $repeat2 = $repeat;
      foreach ( $vardefs as $vardef ) {//podmieniamy zmienne na jakieś losowe wartości, lub poprostu usuwamy dolara. 
         switch ( $vardef['type'] ) {
            case 'currency':
               $currency = new Currency();
               $currency->retrieve('-99');
               $rand = format_number((rand(50000, 1000000) / 100), 2, 2);
               $return_value = "{$currency->symbol}{$rand}";
               $repeat2 = str_replace('$' . $vardef['name'], $return_value, $repeat2);
               break;
            case 'name':
            case 'varchar':
            case 'enum':
            case 'multienum':
               $repeat2 = str_replace('$' . $vardef['name'], $vardef['name'], $repeat2);
               break;
            case 'phone':
               $rand = rand(100000000, 999999999);
               $repeat2 = str_replace('$' . $vardef['name'], $rand, $repeat2);
               break;
            case 'datetime':
            case 'date':
            case 'time':
               $now = $timedate->getNow();
               $datetime = $timedate->to_display_date_time(date("Y-m-d H:i:s", strtotime($now)), true, true);
               $repeat2 = str_replace('$' . $vardef['name'], $datetime, $repeat2);
               break;
            case 'int':
               $rand = rand(500, 10000);
               $repeat2 = str_replace('$' . $vardef['name'], $rand, $repeat2);
               break;
            case 'text':
               $repeat2 = str_replace('$' . $vardef['name'], "text text text text text text text text text text text text text text text text text text", $repeat2);
               break;
            case 'id':
               $repeat2 = str_replace('$' . $vardef['name'], create_guid(), $repeat2);
               break;
            default:
               $repeat2 = str_replace('$' . $vardef['name'], $vardef['name'], $repeat2);
               break;
         }
      }
      $repeat_ = str_replace('_', ' ', $repeat2);
      $repeat_ = $repeat_ . $repeat_ . $repeat_;
      $html = str_replace($repeat, $repeat_, $tpl);
      $html = str_replace("<repeat>", '', $html);
      $html = str_replace("</repeat>", '', $html);
      return $html;
   }

   /**
    * Funkcja służy do stworzenia PDF'a z już wypełnionymi danymi, nagłówkami, lecz bez "body"
    * @param object $report - Zakłada się, że dodajemy bean KReporter, na podstawie niego zostaną wypełnione nagłówki
    * @return TCPDF
    */
   function getPDFHeader($report = null) {
      require_once 'include/tcpdf/tcpdf.php';
      $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
      $pdf->SetCreator(PDF_CREATOR);
      if ( $report == null ) {
         $title = "Title";
         $author = "Mint";
         $subject = "Subject";
      } else {
         $title = $report->name;
         $author = "Mint";
         $subject = $report->report_status;
      }
      $pdf->SetAuthor($author);
      $pdf->SetTitle($title);
      $pdf->SetSubject($subject);
      $pdf->SetKeywords('TCPDF, PDF, example, test, guide');
      $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, $title, $subject);
      // set header and footer fonts
      $pdf->setHeaderFont(Array( 'dejavusans', '', 10, '', false ));
      $pdf->setFooterFont(Array( 'dejavusans', '', 8, '', false ));
      // set default monospaced font
      $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
      // set margins
      $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
      $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
      $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
      // set auto page breaks
      $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
      // set image scale factor
      $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
      // set some language-dependent strings (optional)
      if ( @file_exists(dirname(__FILE__) . '/lang/eng.php') ) {
         require_once(dirname(__FILE__) . '/lang/eng.php');
         $pdf->setLanguageArray($l);
      }
      $pdf->SetFont('dejavusans', '', 8, '', false);
      return $pdf;
   }

   /**
    * Na podstawie KRaportu wyciągane są nazwy pól, ich właściwości, klucze i zwrócone w formie tablicy. Klucze tablicy są takie same jak nazwy zmiennych w kontrolerze KReportera.
    * @param KReports $thisReport
    * @return Array
    */
   function getFieldArray($thisReport) {
      global $beanList, $beanFiles;
      $arrayList = json_decode_kinamu(html_entity_decode($thisReport->listfields, ENT_QUOTES, 'UTF-8'));

      //see if we have dynamic cols in the Request ...
      $dynamicolsOverrid = array();
      if ( isset($_REQUEST['dynamicols']) && $_REQUEST['dynamicols'] != '' ) {
         $dynamicolsOverride = json_decode_kinamu(html_entity_decode($_REQUEST['dynamicols'], ENT_QUOTES, 'UTF-8'));
         $overrideMap = array();
         foreach ( $dynamicolsOverride as $thisOverrideKey => $thisOverrideEntry ) {
            $overrideMap[$thisOverrideEntry['dataIndex']] = $thisOverrideKey;
         }
         //loop over the listfields
         for ( $i = 0; $i < count($arrayList); $i++ ) {
            if ( isset($overrideMap[$arrayList[$i]['fieldid']]) ) {
               // set the display flag
               if ( $dynamicolsOverride[$overrideMap[$arrayList[$i]['fieldid']]]['isHidden'] == 'true' )
                  $arrayList[$i]['display'] = 'no';
               else
                  $arrayList[$i]['display'] = 'yes';
               // set the width
               $arrayList[$i]['width'] = $dynamicolsOverride[$overrideMap[$arrayList[$i]['fieldid']]]['width'];
               // set the sequence
               $arrayList[$i]['sequence'] = $dynamicolsOverride[$overrideMap[$arrayList[$i]['fieldid']]]['sequence'];
            }
         }
         // resort the array
         usort($arrayList, 'sortFieldArrayBySequence');
      }

      $fieldArray = array();
      $fieldIdArray = array();
      foreach ( $arrayList as $thisList ) {
         if ( $thisList ['display'] == 'yes' ) {
            $patch_array = array();
            $name = '';

            $paths = explode('::', $thisList['path']);
            foreach ( $paths as $path ) {
               $track = explode(':', $path);
               switch ( $track[0] ) {
                  case 'root':
                     $module = $track[1];
                     break;
                  case 'link':
                     $module = $track[1];
                     $link = $track[2];
                     $patch_array[] = $track[2];
                     break;
                  case 'field':
                     $field = $track[1];
                     break;
               }
            }
            if ( isset($link) ) {
               $bean = BeanFactory::getBean($module);
               if ( $bean && $bean->field_defs[$link] ) {
                  $module = isset($bean->field_defs[$link]['module']) ? $bean->field_defs[$link]['module'] : null;
               }
            }
            foreach ( $patch_array as $patch ) {
               $name .= $patch . '__';
            }
            $name .= substr($thisList['path'], strpos($thisList['path'], "field:") + 6);

            if ( isset($module) ) {
               $focus = BeanFactory::getBean($module);
               if ( isset($focus->field_defs[$field]) ) {
                  $field_type = $focus->field_defs[$field]['type'];
               }
            }
            if ( !isset($field_type) ) {
               $field_type = 'varchar';
            }
            $fieldArray [] = array(
               'label' => $thisList ['name'],
               'width' => (isset($thisList ['width']) && $thisList ['width'] != '' && $thisList ['width'] != '0') ? $thisList ['width'] : '100',
               'display' => $thisList ['display'],
               'name' => $name,
               'type' => $field_type,
            );
            $fieldIdArray [] = $thisList ['fieldid'];
         }
      }
      $return_array = array();
      $return_array['fieldIdArray'] = $fieldIdArray;
      $return_array['fieldArray'] = $fieldArray;
      $return_array['arrayList'] = $arrayList;
      return $return_array;
   }

}
