<?php

//require_once('include/tcpdf/config/lang/eng.php');
require_once('include/tcpdf/tcpdf.php');
require_once('include/tcpdf/MintPDF.php');
require_once('modules/KReports/simple_html_dom.php'); //MintHCM
//require_once('include/tcpdf/config/lang/eng.php');
//require_once('include/tcpdf/tcpdf.php');
//require_once 'include/Sugarpdf/Sugarpdf.php';

class PDF {

   //get name of module related to $bean by specified $relationship 
   function get_related_module($bean, $relationship) {
      $query = "SELECT * FROM relationships WHERE relationship_name = '" . $relationship . "' AND deleted = 0";
      $GLOBALS['log']->debug($query);

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

      $custom_logic_arguments['check_notify'] = $check_notify;
      //$bean->call_custom_logic("before_pdf_generate", $custom_logic_arguments);
      global $sugar_config, $beanList, $app_list_strings;
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
               /*
                 $q  =  $bean->{$relate}->getQuery();
                 //$query =  $q['select'].$q['from']." join ". $table." on (".$table.".".$xkey."=)".$q['where'];

                 $query =  "Select id from ".$table." where id in(".$q.")";
                 //check ordering
                 if(isset($block->orderby)){
                 $mod = new $beanName();
                 if ($mod->getFieldDefinition($block->orderby)){
                 if (isset($block->dir) && in_array($block->dir,array("ASC","asc","DESC","desc")))
                 $dir = $block->dir;
                 else
                 $dir = "ASC";
                 $query .= "ORDER BY $block->orderby $dir";
                 }
                 }

                 $result = $bean->db->query($query); */
               global $timedate, $sugar_config, $app_list_strings;
               if ( true ) {
                  for ( $preview = 0; $preview < 3; $preview++ ) {
                     $newbean = new $beanName();
                     foreach ( $newbean->field_defs as $field_name ) {
                        switch ( $field_name['type'] ) {
                           case 'varchar':
                              if ( strpos($field_name['name'], 'street') !== false ) {
                                 $newbean->$field_name['name'] = "ul.Druskiennicka 8/10";
                              } elseif ( strpos($field_name['name'], 'country') !== false ) {
                                 $newbean->$field_name['name'] = "Polska";
                              } elseif ( strpos($field_name['name'], 'postalcode') !== false ) {
                                 $newbean->$field_name['name'] = "61-345";
                              } elseif ( strpos($field_name['name'], 'state') !== false ) {
                                 $newbean->$field_name['name'] = "Wielkopolska";
                              } elseif ( strpos($field_name['name'], 'city') !== false ) {
                                 $newbean->$field_name['name'] = "Poznań";
                              } else {
                                 $newbean->$field_name['name'] = translate($field_name['vname'], $module_name);
                              }
                              break;
                           case 'name':
                              $newbean->$field_name['name'] = translate($field_name['vname'], $module_name);
                              break;
                           case 'text':
                              $newbean->$field_name['name'] = 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa.';
                              break;

                           case 'int':
                           case 'decimal':
                           case 'float':

                              if ( $field_name['disable_format_number'] = '1' ) {
                                 $newbean->$field_name['name'] = rand(1, 100);
                              } else {
                                 $p = (isset($field_name['precision'])) ? $field_name['precision'] : 2;
                                 $v = ($field_name['type'] == 'float') ? (rand(1, 100) + (rand(1, 99) / 100)) : rand(1, 100);

                                 $newbean->$field_name['name'] = number_format(( double ) $v, $p, $sugar_config['default_decimal_seperator'], $sugar_config['default_number_grouping_seperator']);
                              }
                              break;

                           case 'date':
                           case 'datetime':

                              $newbean->$field_name['name'] = $timedate->to_display_date(date("Y-m-d"));
                              break;

                           case 'currency':
                              $value = number_format(( double ) (rand(100, 50000) + (rand(1, 99) / 100)), 2, $sugar_config['default_decimal_seperator'], $sugar_config['default_number_grouping_seperator']);
                              $newbean->$field_name['name'] = $value;

                              break;

                           case 'bool':
                              $newbean->$field_name['name'] = translate($field_name['vname'], $module_name);
                              break;

                           case 'relate':
                              $newbean->$field_name['name'] = translate($field_name['vname'], $_REQUEST['module_name']);
                              ;
                              break;
                           case 'enum':
                              if ( $field_name['name'] == 'vat' && isset($field_name['function']['name']) && isset($field_name['function']['include']) ) {
                                 include_once $field_name['function']['include'];

                                 $taxRates = $field_name['function']['name']();

                                 $newbean->$field_name['name'] = key(array_slice($taxRates, rand(0, count($taxRates)), 1));
                                 ;
                              } else if ( isset($field_name['options']) && isset($app_list_strings[$field_name['options']]) ) {
                                 $list_count = count($app_list_strings[$field_name['options']]);
                                 $loop = 0;

                                 foreach ( $app_list_strings[$field_name['options']] as $list_key => $list_value ) {

                                    if ( $list_count > 1 && $loop == 1 ) {
                                       $newbean->$field_name['name'] = $list_key;
                                       break;
                                    } elseif ( $list_count == 1 ) {
                                       $newbean->$field_name['name'] = $list_key;
                                       break;
                                    } elseif ( $loop > 0 ) {
                                       $newbean->$field_name['name'] = $field_name['options'];
                                       break;
                                    }
                                    $loop++;
                                 }
                              }
                              break;

                           case 'multienum':
                              if ( isset($field_name['options']) && isset($app_list_strings[$field_name['options']]) ) {
                                 $list_count = count($app_list_strings[$field_name['options']]);

                                 if ( $list_count > 2 ) {
                                    $loop = 3;
                                 } elseif ( $list_count > 1 ) {
                                    $loop = 2;
                                 } else {
                                    $loop = 1;
                                 }
                                 $temp = '';
                                 foreach ( $app_list_strings[$field_name['options']] as $list_key => $list_value ) {
                                    $temp .= '^' . $list_key . '^';
                                    $loop--;
                                    if ( !$loop ) {
                                       break;
                                    } else {
                                       $temp .=',';
                                    }
                                 }


                                 $newbean->$field_name['name'] = $temp;
                              }
                              break;
                        }
                     }
                     //var_dump($block_after->innertext);

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
               } else {
                  if ( isset($block->intable) && !$outside_table )
                     $block->outertext = '<tr><td></td></tr>';
                  else
                     $block->outertext = '';
               }
            }
         }
         else if ( isset($block->field) && isset($block->type) && $block->type == "multienum" ) {
            global $app_list_strings;
            $str = str_replace($rel, "", $block->field);

            if ( isset($bean->field_defs[$str]) ) {
               $field_name = $bean->field_defs[$str];
               if ( isset($field_name['options']) && isset($app_list_strings[$field_name['options']]) ) {
                  $list_count = count($app_list_strings[$field_name['options']]);

                  if ( $list_count > 2 ) {
                     $loop = 3;
                  } elseif ( $list_count > 1 ) {
                     $loop = 2;
                  } else {
                     $loop = 1;
                  }
                  $temp = '';
                  foreach ( $app_list_strings[$field_name['options']] as $list_key => $list_value ) {
                     $temp .= '^' . $list_key . '^';
                     $loop--;
                     if ( !$loop ) {
                        break;
                     } else {
                        $temp .=',';
                     }
                  }

                  $bean->$str = $temp;
               }
            }

            if ( isset($bean->$str) ) {
               $items = unencodeMultienum($bean->$str);
               foreach ( $items as &$item )
                  $new_block .= str_replace($key . "ITEM", translate($bean->field_defs[$str]['options'], $bean->object_name, $item), $block->innertext);
               //$new_block .= preg_replace("/".$key."ITEM/", translate($bean->field_defs[$str]['options'], $bean->object_name, $item), $block->innertext);

               $block->outertext = $new_block;
            }
         } else {
            $block_after->outertext = $block_after->innertext;
         }

         $html = str_get_html($html->save()); //reload Html DOM object
         $block = $html->find($tag, 0);
      }

      $str = $html->save();

      global $sugar_config, $beanList, $db;

      //$bean->fixUpFormatting();
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
               $value = $bean->$field['name'];
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
      return $str;
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

      $tpl = preg_replace
              (
              array(
         '/<!--repeat[="_ A-Za-z0-9]+-->/e',
         '/<!--endrepeat-->/' ), array(
         'preg_replace(array("/<!--repeat/", "/-->/"), array("<repeat", ">"), "$0")',
         '</repeat>'
              ), $tpl
      );


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
            $pdf->AddPage();
            $pdf->writeHTML($tt, true, false, false, false, '');
         }
      } else { //for one record
         //$focus->retrieve($focus->id);
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
      global $sugar_config, $beanList, $app_list_strings;
      $r = explode('__', $name);
      //echo $name;
      if ( count($r) == 1 ) {
         $field_defs = $bean->field_defs;
         $type = $field_defs[$name]['type'];
         if ( $type == 'currency' ) {
            $curr = new Currency();
            $curr->retrieve($bean->currency_id);

            //$value = number_format((double)$bean->$name, 2, $sugar_config['default_decimal_separator'], $sugar_config['default_number_grouping_seperator']);
         } else if ( $type == 'enum' ) {
            $value = translate($field_defs[$name]['options'], $bean->module_dir, $bean->$name);

            if ( is_array($value) )
               $value = '';
         } else
            $value = $bean->getFieldValue($r[0]);

         return $value;
      }
      if ( count($r) > 1 ) {
         global $timedate, $sugar_config, $app_list_strings;
         $name = substr($name, strpos($name, '__') + 2);
         $link_defs = $bean->get_linked_fields();
         $field = $link_defs[$r[0]];
         $rel_module = $this->get_related_module($bean, $field["relationship"]);
         $rel_module_long = $rel_module;
         $rel_module = $beanList[$rel_module];

         $rel_bean = new $rel_module();
         foreach ( $rel_bean->field_defs as $field_name ) {
            switch ( $field_name['type'] ) {
               case 'varchar':
                  if ( strpos($field_name['name'], 'street') !== false ) {
                     $rel_bean->$field_name['name'] = "ul.Druskiennicka 8/10";
                  } elseif ( strpos($field_name['name'], 'country') !== false ) {
                     $rel_bean->$field_name['name'] = "Polska";
                  } elseif ( strpos($field_name['name'], 'postalcode') !== false ) {
                     $rel_bean->$field_name['name'] = "61-345";
                  } elseif ( strpos($field_name['name'], 'state') !== false ) {
                     $rel_bean->$field_name['name'] = "Wielkopolska";
                  } elseif ( strpos($field_name['name'], 'city') !== false ) {
                     $rel_bean->$field_name['name'] = "Poznań";
                  } else {
                     $rel_bean->$field_name['name'] = translate($field_name['vname'], $rel_module_long);
                  }
                  break;
               case 'name':
                  $rel_bean->$field_name['name'] = translate($field_name['vname'], $rel_module_long);
                  break;
               case 'text':
                  $rel_bean->$field_name['name'] = 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa.';
                  break;

               case 'int':
               case 'decimal':
               case 'float':

                  if ( $field_name['disable_format_number'] = '1' ) {
                     $rel_bean->$field_name['name'] = rand(1, 100);
                  } else {
                     $p = (isset($field_name['precision'])) ? $field_name['precision'] : 2;
                     $v = ($field_name['type'] == 'float') ? (rand(1, 100) + (rand(1, 99) / 100)) : rand(1, 100);

                     $rel_bean->$field_name['name'] = number_format(( double ) $v, $p, $sugar_config['default_decimal_seperator'], $sugar_config['default_number_grouping_seperator']);
                  }
                  break;

               case 'date':
               case 'datetime':

                  $rel_bean->$field_name['name'] = $timedate->to_display_date(date("Y-m-d"));
                  break;

               case 'currency':
                  $value = number_format(( double ) (rand(100, 50000) + (rand(1, 99) / 100)), 2, $sugar_config['default_decimal_seperator'], $sugar_config['default_number_grouping_seperator']);

                  $rel_bean->$field_name['name'] = $value;

                  break;

               case 'bool':
                  $rel_bean->$field_name['name'] = translate($field_name['vname'], $rel_module_long);
                  break;

               case 'relate':
                  $focus->$field_name['name'] = translate($field_name['vname'], $_REQUEST['module_name']);
                  ;
                  break;

               case 'enum':

                  if ( isset($field_name['options']) && isset($app_list_strings[$field_name['options']]) ) {
                     $list_count = count($app_list_strings[$field_name['options']]);
                     $loop = 0;

                     foreach ( $app_list_strings[$field_name['options']] as $list_key => $list_value ) {
                        if ( $list_count > 1 && $loop == 1 ) {
                           $focus->$field_name['name'] = $list_key;
                           break;
                        } elseif ( $list_count == 1 ) {
                           $focus->$field_name['name'] = $list_key;
                           break;
                        } elseif ( $loop > 0 ) {
                           $focus->$field_name['name'] = $field_name['options'];
                           break;
                        }
                        $loop++;
                     }
                  }
                  break;

               case 'multienum':
                  if ( isset($field_name['options']) && isset($app_list_strings[$field_name['options']]) ) {
                     $list_count = count($app_list_strings[$field_name['options']]);

                     if ( $list_count > 2 ) {
                        $loop = 3;
                     } elseif ( $list_count > 1 ) {
                        $loop = 2;
                     } else {
                        $loop = 1;
                     }
                     $temp = '';
                     foreach ( $app_list_strings[$field_name['options']] as $list_key => $list_value ) {
                        $temp .= '^' . $list_key . '^';
                        $loop--;
                        if ( !$loop ) {
                           break;
                        } else {
                           $temp .=',';
                        }
                     }

                     $focus->$field_name['name'] = $temp;
                  }
                  break;
            }
         }

         if ( !empty($rel_bean) ) {
            $custom_logic_arguments['check_notify'] = $check_notify;
            //$rel_bean->call_custom_logic("before_pdf_generate", $custom_logic_arguments);

            return $this->getFieldValue($name, $rel_bean);
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