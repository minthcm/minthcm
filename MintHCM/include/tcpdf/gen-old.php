<?php

require_once('include/tcpdf/config/lang/eng.php');
require_once('include/tcpdf/tcpdf.php');
require_once('modules/PDFGenerator/simple_html_dom.php');

class PDF {

   //get name of module related to $bean by specified $relationship 
   function get_related_module($bean, $relationship) {
      $query = "SELECT * FROM relationships WHERE relationship_name = '" . $relationship . "' AND deleted = 0";
      $row = $bean->db->fetchByAssoc($bean->db->query($query));
      return ($row['lhs_module'] == $bean->object_name) ? $row['rhs_module'] : $row['lhs_module'];
   }

   //find repetitive blocks
   function parse($str, $bean, $relationship = "", $count = 1) {
      global $sugar_config, $beanList;
      $key = "\$";      //field token symbol
      $tag = 'repeat';     //html tag for repetitive block of linked beans

      $html = str_get_html($str);
      $block = $html->find($tag, 0);

      $bean->load_relationships();

      if ( $relationship != '' )
         $rel = $relationship . "_"; //add _ between relationship name and field name in a composed token 
      else
         $rel = '';

      while ( $block != null ) {
         $new_block = null;
         $c = 1;

         if ( isset($block->relationship) && isset($block->type) && $block->type == "link" ) {

            $beanName = $beanList[$this->get_related_module($bean, $bean->field_defs[$block->relationship]['relationship'])];
            $records = $bean->get_linked_beans($block->relationship, $beanName);

            foreach ( $records as $element )
               $new_block .= $this->parse($block->innertext, $element, $block->relationship, $c++);

            $block->outertext = $new_block;
         } else if ( isset($block->field) && isset($block->type) && $block->type == "multienum" ) {
            $str = str_replace($rel, "", $block->field);
            if ( isset($bean->$str) ) {
               $items = unencodeMultienum($bean->$str);
               foreach ( $items as &$item )
                  $new_block .= str_replace($key . "ITEM", translate($bean->field_defs[$str]['options'], $bean->object_name, $item), $block->innertext);
               //$new_block .= preg_replace("/".$key."ITEM/", translate($bean->field_defs[$str]['options'], $bean->object_name, $item), $block->innertext);

               $block->outertext = $new_block;
            }
         } else {
            $block->outertext = $block->innertext;
         }
         $html = str_get_html($html->save()); //reload Html DOM object
         $block = $html->find($tag, 0);
      }

      $str = $html->save();
      $bean->fixUpFormatting();
      foreach ( $bean->field_defs as &$field ) {
         //omit relate and link field types
         if ( array_search('link', $field) !== 'type' &&
                 array_search('relate', $field) !== 'type' ) {

            if ( $field['type'] == 'currency' ) {
               $curr = new Currency();
               $curr->retrieve($bean->currency_id);
               $value = number_format($bean->$field['name'], 2, $sugar_config['default_decimal_separator'], $sugar_config['default_number_grouping_seperator']);
            } else if ( $field['type'] == 'enum' )
               $value = translate($field['options'], $bean->object_name, $bean->$field['name']);
            else
               $value = $bean->$field['name'];

            //$str = preg_replace("/".$key.$rel.$field['name']."/", $value, $str);
            $str = str_replace($key . $rel . $field['name'], $value, $str);
         }
      }

      //parse special fields
      //$str = preg_replace("/".$key."COUNT_".$relationship."/", $count, $str);
      $str = str_replace($key . "COUNT_" . $relationship, $count, $str);
      if ( isset($curr) ) {
         //$str = preg_replace("/".$key."CURRENCY_ISO_".$relationship."/", $curr->iso4217, $str);
         $str = str_replace($key . "CURRENCY_ISO_" . $relationship, $curr->iso4217, $str);
         //$str = preg_replace("/".$key."CURRENCY_SYMBOL_".$relationship."/", $curr->symbol, $str);
         $str = str_replace($key . "CURRENCY_SYMBOL_" . $relationship, $curr->symbol, $str);
      }
      return $str;
   }

   function repl($str) {
      //	return 'preg_replace(array(\'/\<\!\-\-relationship/\', \'/\-\-\>/\'), array("<repeat", ">"), $0)'."aaa";
   }

   function Output() {
      global $beanList, $beanFiles, $focus, $pdftemplate, $app_strings;

      $key = '\$';

      $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

      $pdf->SetCreator(PDF_CREATOR);
      $pdf->SetAuthor('Mint');
      $pdf->SetTitle($focus->name);

      $pdf->SetHeaderData("mint.png", "25", $focus->name, $pdftemplate->name);

      $pdf->setHeaderFont(Array( 'dejavusans', '', PDF_FONT_SIZE_MAIN ));
      $pdf->setFooterFont(Array( PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA ));

      $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

      $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
      $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
      $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

      $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
      $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

      $pdf->setLanguageArray($l);
      $pdf->SetFont('dejavusans', '', 8);

      $pdf->AddPage();

      $tpl = $pdftemplate->template;
      $tpl = preg_replace(array( '/<!--repeat[="_ A-Za-z]+-->/e', '/<!--endrepeat-->/' ), array( 'preg_replace(array("/<!--repeat/", "/-->/"), array("<repeat", ">"), "$0")', '</repeat>' ), $tpl);
      $tpl = html_entity_decode($tpl);
      $tpl = $this->parse($tpl, $focus);

      $pdf->writeHTML($tpl, true, false, false, false, '');
      $pdf->Output('template.pdf', 'I');
   }

}

?>