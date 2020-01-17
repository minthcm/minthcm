<?php

require_once('include/tcpdf/config/lang/eng.php');
require_once('include/tcpdf/tcpdf.php');
require_once('include/tcpdf/MintPDF.php');
require_once('modules/PDFGenerator/simple_html_dom.php');

//require_once('include/tcpdf/config/lang/eng.php');
//require_once('include/tcpdf/tcpdf.php');
//require_once 'include/Sugarpdf/Sugarpdf.php';

class PDF {

   //get name of module related to $bean by specified $relationship 
   function get_related_module($bean, $relationship) {
      $query = "SELECT * FROM relationships WHERE relationship_name = '" . $relationship . "' AND deleted = 0";
      $row = $bean->db->fetchByAssoc($bean->db->query($query));
      $module_name = $bean->module_name;
      if ( $module_name == null ) {//BEFORE 6.4
         $module_name = $bean->module_dir;
      }
      return ($row['lhs_module'] == $module_name) ? $row['rhs_module'] : $row['lhs_module'];
   }

   //find repetitive blocks
   function findHeader($str) {
      $tagHeader = 'div#pdf_header';     //html tag for repetitive block of linked beans
      $tagFooter = 'div#pdf_footer';
      $html = str_get_html($str);
      $block = $html->find($tagHeader, 0);
      if ( $block != null ) {
         $header = $block->outertext;
         $block->outertext = '';
         if ( isset($block->style) ) {
            $style = $block->style;
            $styles = explode(";", $style);
            foreach ( $styles as $s ) {
               $ss = explode(":", $s);
               if ( $ss[0] == "h" ) {
                  $header_h = $ss[1];
               }
            }
         }
      }
      $str = $html->save();
      $html = str_get_html($str);
      $block = $html->find($tagFooter, 0);
      if ( $block != null ) {
         $footer = $block->outertext;
         $block->outertext = '';
         if ( isset($block->style) ) {
            $style = $block->style;
            $styles = explode(";", $style);
            foreach ( $styles as $s ) {
               $ss = explode(":", $s);
               if ( $ss[0] == "h" ) {
                  $footer_h = $ss[1];
               }
            }
         }
      }
      $str = $html->save();
      return array( 'header' => $header, 'header_h' => $header_h, 'footer_h' => $footer_h, 'footer' => $footer, 'body' => $str );
   }

   function parse($str, $bean, $relationship = "", $count = 1, $d = 0) {
      global $sugar_config, $beanList, $db;

      $custom_logic_arguments['check_notify'] = $check_notify;
      $bean->call_custom_logic("before_pdf_generate", $custom_logic_arguments);

      $key = "\$";      //field token symbol
      $tag = 'repeat';     //html tag for repetitive block of linked beans
      //$tag='div[repeat_type]';
      $html = str_get_html($str);
      $block = $html->find($tag, 0);

      $bean->load_relationships();

      if ( $relationship != '' )
         $rel = $relationship . "__"; //add _ between relationship name and field name in a composed token 
      else
         $rel = '';
      while ( $block != null ) {
         $new_block = null;
         $c = 1;

         if ( isset($block->relationship) && isset($block->type) && $block->type == "link" ) {
            //var_dump($block->innertext);
            $outside_table = false;
            if ( $block->table_outside == true ) {
               $outside_table = true;
            }

            if ( $outside_table ) {
               $block_after = $block->find("#" . $block->relationship, 0);
            } else {
               $block_after = $block;
            }



            //var_dump($block->innertext);die();

            $records = null;
            $relate = explode('__', $block_after->relationship);
            $relate = $relate[$d];
            $beanName = $beanList[$this->get_related_module($bean, $bean->field_defs[$relate]['relationship'])];
            if ( $beanName != null ) {
               // FIXME do poprawki
               $query = "SELECT * FROM relationships WHERE relationship_name = '" . $bean->field_defs[$relate]['relationship'] . "' AND deleted = 0";
               $row = $bean->db->fetchByAssoc($bean->db->query($query));

               //check and define table sides
               $module_name = $bean->module_name;
               if ( $module_name == null ) {//BEFORE 6.4
                  $module_name = $bean->module_dir;
               }
               $xhs = ($row['lhs_module'] == $module_name) ? 'lhs' : 'rhs';
               $yhs = ($xhs == 'lhs') ? 'rhs' : 'lhs';
               $table = $row[$yhs . "_table"];
               $rtable = $row['join_table'];


               //	$xkey= $row["{$xhs}_key"]; 
               //	$join_key1 = $row["join_key_$xhs"]; 
               //	$join_key2 = $row["join_key_$yhs"];


               /* $query = "SELECT $table.id ".
                 "FROM   $rtable,$table ".
                 "WHERE  $rtable.$join_key1='$bean->id' ".
                 "AND    $rtable.$join_key2= $table.id ".
                 "AND    $table.deleted = 0 "; */
               $q = $bean->{$relate}->getQuery();
               //$query =  $q['select'].$q['from']." join ". $table." on (".$table.".".$xkey."=)".$q['where'];

               $query = "Select id from " . $table . " where id in(" . $q . ")";
               //check ordering
               if ( isset($block->orderby) ) {
                  $mod = new $beanName();
                  if ( $mod->getFieldDefinition($block->orderby) ) {
                     if ( isset($block->dir) && in_array($block->dir, array( "ASC", "asc", "DESC", "desc" )) )
                        $dir = $block->dir;
                     else
                        $dir = "ASC";
                     $query .= "ORDER BY $block->orderby $dir";
                  }
               }

               $result = $bean->db->query($query);
               if ( $bean->db->getAffectedRowCount($result) > 0 ) {
                  while ( ($row = $bean->db->fetchByAssoc($result) ) != null ) {
                     $newbean = new $beanName();
                     $newbean->retrieve($row['id']);
                     $newbean->load_relationships();
                     $new_block .= $this->parse($block_after->innertext, $newbean, $block_after->relationship, $c++, $d + 1);
                  }

                  if ( $outside_table ) {
                     $block = $block->find("#" . $block->relationship, 0);
                     $block->innertext = $new_block;
                     $block = $block->parent();
                     $block = $block->parent();
                     $block->outertext = $block->innertext;
                  } else {
                     $block->outertext = $new_block;
                  }
                  //$block->outertext = $new_block;
                  //var_dump($block->outertext);die();
               } else {
                  if ( isset($block->intable) && !$outside_table )
                     $block->outertext = '<tr><td></td></tr>';
                  else
                     $block->outertext = '';
               }
            }
         }
         else if ( isset($block->field) && isset($block->type) && $block->type == "multienum" ) {
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
      $field_defs = $bean->field_defs;
      usort($field_defs, 'PDF::sortByNameLength');
      foreach ( $field_defs as &$field ) {
         //omit relate and link field types
         if ( (array_search('link', $field) !== 'type') ||
                 ($field['name'] == "created_by_name" ||
                 $field['name'] == "modified_by_name" ||
                 $field['name'] == "assigned_user_name")
         ) {

            if ( $field['type'] == 'currency' ) {
               $curr = new Currency();
               $curr->retrieve($bean->currency_id);
               $value = number_format(( double ) $bean->$field['name'], 2, $sugar_config['default_decimal_separator'], $sugar_config['default_number_grouping_seperator']);
            } else if ( $field['type'] == 'enum' ) {
               if ( $field['name'] == 'vat' && isset($field['function']['name']) && isset($field['function']['include']) ) {
                  include_once $field['function']['include'];

                  $taxRates = $field['function']['name']();
                  $value = array_key_exists($bean->$field['name'], $taxRates) ? $taxRates[$bean->$field['name']] : '';
               } else {
                  $value = translate($field['options'], $bean->module_dir, $bean->$field['name']);
                  if ( is_array($value) )
                     $value = '';
               }
            } else
               $value = $bean->$field['name'];
            //$str = str_replace($key.$rel.$field['name'], $value, $str);
            $occ = 0;
            while ( ($pos = strpos($str, $key . $rel . $field['name'], $occ) ) ) {
               if ( $str[$pos + strlen($key . $rel . $field['name'])] != '_' )
                  $str = substr_replace($str, $value, $pos, strlen($key . $rel . $field['name']));
               else
                  $occ++;
            }
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

      $this->getCurrentDate($str);

      return $str;
   }

   function getCurrentDate(&$str) {
      global $timedate;
      $date = date($timedate->get_date_format());
      $str = str_replace('$_CURRENT_DATE', $date, $str);
   }

   function repl($str) {
      //	return 'preg_replace(array(\'/\<\!\-\-relationship/\', \'/\-\-\>/\'), array("<repeat", ">"), $0)'."aaa";
   }

   function Output() {
      global $beanList, $beanFiles, $focus, $pdftemplate, $app_strings;

      $key = '\$';
      //$pdf = new TCPDF(null, array(), PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
      $pdf = new MintPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
      $pdf->SetCreator(PDF_CREATOR);

      $pdf->setHeaderFont(Array( 'dejavusans', '', PDF_FONT_SIZE_MAIN ));
      $pdf->setFooterFont(Array( 'dejavusans', '', PDF_FONT_SIZE_DATA ));

      $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

      //$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
      //$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
      $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

      $pdf->setLanguageArray($l);
      $pdf->SetFont('dejavusans', '', 8);
      $tpl = str_replace('&nbsp;', ' ', $pdftemplate->template);
      //$tpl = preg_replace(array('/<!--repeat[="_ A-Za-z0-9]+-->/e', '/<!--endrepeat-->/'), array('preg_replace(array("/<!--repeat/", "/-->/"), array("<repeat", ">"), "$0")', '</repeat>'), $tpl);

      $tpl = preg_replace(array( '/<!--repeat[="_ A-Za-z0-9]+-->/e', '/<!--endrepeat-->/' ), array( 'preg_replace(array("/<!--repeat/", "/-->/"), array("<repeat", ">"), "$0")', '</repeat>' ), $tpl);
      $tpl = str_replace(array( '<style><!--', '--></style>' ), array( '<style>', '</style>' ), $tpl);

      $tpl = html_entity_decode($tpl);
      $arr = $this->findHeader($tpl);

      $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
      $pdf->SetFooterMargin(empty($arr['footer_h']) ? PDF_MARGIN_FOOTER : $arr['footer_h']);

      $bottom = empty($arr['footer_h']) ? PDF_MARGIN_BOTTOM : $arr['footer_h'] + 15;
      $top = empty($arr['header_h']) ? PDF_MARGIN_TOP : $arr['header_h'] + 15;

      $pdf->SetMargins(PDF_MARGIN_LEFT, $top, PDF_MARGIN_RIGHT);
      $pdf->SetAutoPageBreak(TRUE, $bottom);
      $tpl = $arr['body'];
      $tt = '';
      $once = true;
      if ( is_array($focus) ) { //for array of records generate one pdf
         foreach ( $focus as $f ) {
            $f->retrieve($f->id);
            $tt = $this->parse($tpl, $f);
            $tt = $this->analize($tt, $f);
            $footer = $this->parse($arr['footer'], $f);
            $footer = $this->parse($footer, $f);
            $header = $this->parse($arr['header'], $f);
            $header = $this->parse($header, $f);
            $params = $pdf->serializeTCPDFtagParameters(array( 'format' => 'of' ));
            //$header =	str_replace('#PAGE','<tcpdf method="printPage" params="'.$params.'"/>',$header);
            //$footer =	str_replace('#PAGE','<tcpdf method="printPage" params="'.$params.'"/>',$footer);
            $header = str_replace('#CURRENT_PAGE', '{{pnb}}', $header);
            $header = str_replace('#NO_PAGES', '{{nb}}', $header);

            $footer = str_replace('#CURRENT_PAGE', '{{pnb}}', $footer);
            $footer = str_replace('#NO_PAGES', '{{nb}}', $footer);
            $pdf->setHeaderBody($header);
            $pdf->setFooterBody($footer);
            $params = $pdf->serializeTCPDFtagParameters(array( 'format' => 'of' ));
            //$tt =	str_replace('#PAGE','<tcpdf method="printPage" params="'.$params.'"/>',$tt);
            $tt = str_replace('#CURRENT_PAGE', '{{pnb}}', $tt);
            $tt = str_replace('#NO_PAGES', '{{nb}}', $tt);

            //if($once)
            $pdf->AddPage();
            $once = false;
            $pdf->writeHTML($tt, true, false, false, false, '');
         }
      } else { //for one record
         $focus->retrieve($focus->id);
         $tpl = $this->parse($tpl, $focus);
         $tt = $this->analize($tpl, $focus);
         $footer = $this->parse($arr['footer'], $focus);
         $footer = $this->parse($footer, $focus);
         $header = $this->parse($arr['header'], $focus);
         $header = $this->parse($header, $focus);
         $params = $pdf->serializeTCPDFtagParameters(array( 'format' => 'of' ));
         //$header =	str_replace('#PAGE','<tcpdf method="printPage" params="'.$params.'"/>',$header);
         //$footer =	str_replace('#PAGE','<tcpdf method="printPage" params="'.$params.'"/>',$footer);
         $header = str_replace('#CURRENT_PAGE', '{{pnb}}', $header);
         $header = str_replace('#NO_PAGES', '{{nb}}', $header);

         $footer = str_replace('#CURRENT_PAGE', '{{pnb}}', $footer);
         $footer = str_replace('#NO_PAGES', '{{nb}}', $footer);
         $pdf->setHeaderBody($header);
         $pdf->setFooterBody($footer);
         $params = $pdf->serializeTCPDFtagParameters(array( 'format' => '/' ));
         //$tt =	str_replace('#PAGES','<tcpdf method="printPage" params="'.$params.'"/>',$tt);
         $tt = str_replace('#CURRENT_PAGE', '{{pnb}}', $tt);
         $tt = str_replace('#NO_PAGES', '{{nb}}', $tt);
         $pdf->AddPage();
         file_put_contents('html', $tt);
         $pdf->writeHTML($tt, true, false, false, false, '');
      }

      if ( isset($_GET["file"]) ) {
         $pdf->Output($_GET["file"], 'F');
      } else {

         if ( isset($focus->pdf_name) ) {
            $filename = $focus->pdf_name . '.pdf';
         } else {
            $filename = $focus->name . '.pdf';
         }
         $pdf->Output($filename, 'I');
      }
   }

   /**
    * Fill fields from one-to-one relations    
    */
   protected function analize($tpl, $bean) {
      $pattern = '/\$[a-zA-Z0-9_-]+/';
      preg_match_all($pattern, $tpl, $matches, PREG_OFFSET_CAPTURE);
      foreach ( $matches[0] as $m ) {
         $name = substr($m[0], 1);
         $val = $this->getFieldValue($name, $bean);
         $tpl = str_replace($m[0], $val, $tpl);
      }
      return $tpl;
   }

   private function getFieldValue($name, $bean) {
      global $sugar_config, $beanList;
      $r = explode('__', $name);
      if ( count($r) == 1 ) {
         $field_defs = $bean->field_defs;
         $type = $field_defs[$name]['type'];
         if ( $type == 'currency' ) {
            $curr = new Currency();
            $curr->retrieve($bean->currency_id);
            $value = number_format(( double ) $bean->$name, 2, $sugar_config['default_decimal_separator'], $sugar_config['default_number_grouping_seperator']);
         } else if ( $type == 'enum' ) {
            $value = translate($field_defs[$name]['options'], $bean->module_dir, $bean->$name);
            if ( is_array($value) )
               $value = '';
         } else
            $value = $bean->getFieldValue($r[0]);
         return $value;
      }
      if ( count($r) > 1 ) {
         $name = substr($name, strpos($name, '__') + 2);
         $link_defs = $bean->get_linked_fields();
         $field = $link_defs[$r[0]];
         $rel_module = $this->get_related_module($bean, $field["relationship"]);
         $rel_module = $beanList[$rel_module];
         $rel_bean = $bean->get_linked_beans($r[0], $rel_module);
         if ( !empty($rel_bean) ) {
            $custom_logic_arguments['check_notify'] = $check_notify;
            $rel_bean[0]->call_custom_logic("before_pdf_generate", $custom_logic_arguments);
            return $this->getFieldValue($name, $rel_bean[0]);
         } else
            return '';
      }
   }

   protected static function sortByNameLength($a, $b) {
      if ( (!isset($a['name'])) || (!isset($b['name'])) )
         return 0;
      return (strlen($b['name']) - strlen($a['name']));
   }

}

?>