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

class NoticeEmailUtils {

   protected $db;
   var $emailAddress;
   var $email_template_subject;
   var $email_template_body;
   private $bean_buffor;

   function __construct() {
      $this->bean_buffor = array();
      $this->db = $GLOBALS['db'];
   }

   function sendEmail($emailTemplate_, $emailAddress, $emailSourceModule, $emailSourceRowId, $additionalVars, $focus, $parent_type = null, $parent_id = null, $other_relationship = null, $additional_attachments = null) {
      global $current_user;
      //jozwiakowskis:  zamiana -99 na ZŁ/USD
      if ( isset($additionalVars['currency_id']) ) {
         $cur = new Currency();
         $cur->retrieve($additionalVars['currency_id']);
         $additionalVars['currency_id'] = $cur->iso4217;
      }
      //

      if ( is_string($emailTemplate_) ) {
         $query_template = "SELECT * from email_templates where id = '";
         $query_template .= $emailTemplate_ .= "'";
         $result_email_template = $this->db->query($query_template);
         $row_email_template = $this->db->fetchByAssoc($result_email_template);

         if ( $row_email_template["body_html"] != "" ) {
            $email_template_body = $row_email_template["body_html"];
            $email_type = 'HTML';
         } else {
            $email_template_body = $row_email_template["body"];
         }
         $email_template_subject = $row_email_template["subject"];
      } elseif ( is_array($emailTemplate_) ) {

         $email_template_body = $emailTemplate_["message"];
         $email_template_subject = $emailTemplate_["subject"];
      }
      require_once('modules/EmailTemplates/EmailTemplate.php');
      $emailTemplate = new EmailTemplate();
      $object_arr = array();
      $object_arr[$emailSourceModule] = $emailSourceRowId;

      $email_template_body = $emailTemplate->parse_template_bean($email_template_body, $emailSourceModule, $focus);
      $email_template_subject = $emailTemplate->parse_template_bean($email_template_subject, $emailSourceModule, $focus);

      if ( $additionalVars != null ) {

         foreach ( $additionalVars as $name => $value ) {
            if ( $value != '' && is_string($value) ) {
               $email_template_body = str_replace("\$$name", $value, $email_template_body);
               $email_template_subject = str_replace("\$$name", $value, $email_template_subject);
            } elseif ( is_array($value) && count($value) == 1 && current($value) != '' ) {
               $email_template_body = str_replace("\$$name", current($value), $email_template_body);
               $email_template_subject = str_replace("\$$name", current($value), $email_template_subject);
            } elseif ( is_array($value) && count($value) > 1 && current($value) != '' ) {
               // value is an array -> making a list:
               $list_temp_string = "<ul>";
               foreach ( $value as $l_value ) {
                  $list_temp_string .= "<li>" . $l_value . "</li>";
               }
               $list_temp_string .= "</ul>";

               $email_template_body = str_replace("\$$name", $list_temp_string, $email_template_body);
            } else {

               $email_template_body = str_replace("\$$name", $value, $email_template_body);
               $email_template_subject = str_replace("\$$name", $value, $email_template_subject);
            }
         }
      }

      $this->email_template_subject = $email_template_subject;
      $this->email_template_body = $email_template_body;

      //$fromAccountID = $this->getEmailAccountForSending($userId);
      $request = array();
      $files_to_attach = array();
      if ( $additional_attachments != null ) {
         foreach ( $additional_attachments as $attachment ) {
            $files_to_attach[] = $attachment;
         }
      }
      $attachments_cache_path = sugar_cached("modules/Emails/{$current_user->id}");
      $cached_attachments = [];
      foreach ( $files_to_attach as $file_name ) {
         copy('upload/' . $file_name, $attachments_cache_path . '/' . $file_name);
         $note = BeanFactory::getBean('Notes', $file_name);
         $cached_attachments[] = $file_name . $note->filename;
      }

      $request['attachments'] = implode("::", $cached_attachments);
      $_REQUEST['attachments'] = implode("::", $cached_attachments);
      $request['setEditor'] = 1;

      $request['sendSubject'] = $email_template_subject;
      $request['sendDescription'] = $email_template_body;

      if ( empty($emailAddress['to']) ) {
         $request['sendTo'] = '';
      } else {
         $request['sendTo'] = implode(',', $emailAddress['to']);
      }

      if ( empty($emailAddress['to']) ) {
         $request['addressTo1'] = '';
      } else {
         $request['addressTo1'] = implode(',', $emailAddress['to']);
      }

      if ( empty($emailAddress['cc']) ) {
         $request['sendCc'] = '';
      } else {
         $request['sendCc'] = implode(',', $emailAddress['cc']);
      }

      if ( empty($emailAddress['bcc']) ) {
         $request['sendBcc'] = '';
      } else {
         $request['sendBcc'] = implode(',', $emailAddress['bcc']);
      }

      $request['parent_id'] = !empty($parent_id) ? $parent_id : $emailSourceRowId;
      $request['parent_type'] = !empty($parent_type) ? $parent_type : $emailSourceModule;
      $request['saveToSugar'] = "1";

      $request['sendCharset'] = 'UTF-8'; //TODO

      $_REQUEST['sendSubject'] = $email_template_subject;
      $_REQUEST['sendDescription'] = $email_template_body;

      $_REQUEST['setEditor'] = "1";
      $_REQUEST['sendCharset'] = "UTF-8";
      //$_REQUEST['addressTo2'] = implode(',',$emailAddress);
      $_REQUEST['emailUIAction'] = "sendEmail";
      //$_REQUEST['addressFrom1'] = $fromAccountID;
      //$_REQUEST['fromAccount'] = $fromAccountID;
      $_REQUEST['saveToSugar'] = "1";

      if ( empty($emailAddress['to']) ) {
         $_REQUEST['sendTo'] = '';
      } else {
         $_REQUEST['sendTo'] = implode(',', $emailAddress['to']);
      }
      if ( empty($emailAddress['to']) ) {
         $_REQUEST['addressTo1'] = '';
      } else {
         $_REQUEST['addressTo1'] = implode(',', $emailAddress['to']);
      }
      if ( empty($emailAddress['cc']) ) {
         $_REQUEST['sendCc'] = '';
      } else {
         $_REQUEST['sendCc'] = implode(',', $emailAddress['cc']);
      }
      if ( empty($emailAddress['bcc']) ) {
         $_REQUEST['sendBcc'] = '';
      } else {
         $_REQUEST['sendBcc'] = implode(',', $emailAddress['bcc']);
      }
      //XXX other parent	
      $_REQUEST['parent_id'] = !empty($parent_id) ? $parent_id : $emailSourceRowId;
      $_REQUEST['parent_type'] = !empty($parent_type) ? $parent_type : $emailSourceModule;
      //$_REQUEST['sendCharset'] = '';
      require_once('modules/Emails/Email.php');
      $new_email = new Email();
      $new_email->email2init();
      $res = $new_email->email2Send($request);
      if ( isset($other_relationship) ) {
         $new_email->load_relationship($other_relationship);
         $new_email->$other_relationship->add($emailSourceRowId);
      }
      return $res;
   }

}

// filter_var wymaga php 5.2

class NoticeGenerator {

   var $launch_type;
   var $error = false;
   var $error_message = '';
   var $config = null;
   var $notice_template = null;
   var $db;
   private $bean;
   private $email_addreses;
   private $organized_ea;
   private $current_notice;
   private $notice_limits;

   function __construct($launch_type = 'time') {

      $this->notice_limits = new NoticeLimits();
      $this->db = $GLOBALS['db'];
      $this->launch_type = $launch_type;
      // Sprawdzamy czy plik z konfiguracja istnieje
      if ( file_exists("custom/config/notice_config.php") ) {

         require("custom/config/notice_config.php");

         if ( isset($notice_config) && is_array($notice_config) && count($notice_config) > 0 ) {
            foreach ( $notice_config as $key => $value ) {
               //$r = $this->db->query("SELECT id FROM email_templates WHERE id='".$value['notice_template']."' AND deleted='0'");
               //if (mysqli_num_rows($r)==0 || $launch_type!=$value['notice_type'])
               if ( $launch_type != $value['notice_type'] ) {
                  // Wyrzucamy z listy bo nie mozna znaleźć templejta, albo nie jest wywołaniem tego typu
                  unset($notice_config[$key]);
               }
            }

            if ( count($notice_config) > 0 ) {
               $this->config = $notice_config;
            } else {
               $this->error = true;
               $this->error_message = "Notice configuration doesnt exists";
            }
         } else {
            // konfiguracja nie istnieje, nie wysyłamy niczego;
            $this->error = true;
            $this->error_message = "Notice configuration doesnt exists";
            return;
         }
         $this->config = $notice_config;
      } else {
         $GLOBALS['log']->warning("NOTICE GENERATOR: Configuration file not found!");
      }
   }

   /*
    * Funkcja wysyłająca powiadomienia
    */

   function generate_notices($pre_saved_bean = null) {
      if ( $this->error ) {
         $GLOBALS['log']->fatal("NOTICE GENERATOR: Error while generating notices: " . $this->error_message);
         return;
      }


      foreach ( $this->config as $notice ) {
         // Clear old values before processing each notice config array

         $this->email_addreses = null;
         $this->organized_ea = null;
         $this->current_notice = null;



         if ( $this->launch_type == 'time' ) {
            //too old Sugar for using BeanFactory
            //$bean = BeanFactory::getBean($notice['notice_module']);
            $bean = SugarModule::get($notice['notice_module'])->loadBean();
            if ( empty($bean) ) {
               // bean could not be loaded, is it installed ?
               continue;
            }
            if ( $this->db->tableExists($bean->table_name . '_cstm') ) {
               $beans_query = "SELECT id FROM " . $bean->table_name . " JOIN " . $bean->table_name . "_cstm ON(" . $bean->table_name . "_cstm.id_c=" . $bean->table_name . ".id) WHERE 1";
            } else {
               $beans_query = "SELECT id FROM " . $bean->table_name . " WHERE 1";
            }
            //should it search throwugh deleted records?
            if ( !isset($notice['show_deleted']) || $notice['show_deleted'] == "false" ) {
               $beans_query .= " and deleted='0'";
            } else if ( $notice['show_deleted'] == "only" ) {
               $beans_query .= " and deleted='1'";
            }
            // let's find our beans
            // check time_difference field
            if ( isset($notice['time_difference']) && isset($notice['time_comparsion_field']) ) {
               $date_timestamp = strtotime($notice['time_difference'], strtotime(date("Y-m-d")));
               $date = date("Y-m-d", $date_timestamp);
               $beans_query .= " AND DATE(" . $notice['time_comparsion_field'] . ")='" . $date . "'"; //TODO
            }
            //any additional where
            if ( !empty($notice['additional_where']) ) {
               $beans_query .= " " . $notice['additional_where'];
            }

            if ( isset($notice['function_where']) ) {
               $NotEmpty = !empty($notice['function_where']);
               $isSetFile = isset($notice['function_where']['include']);
               $fileExist = file_exists($notice['function_where']['include']);
               $functionExist = function_exists($notice['function_where']['function_name']);


               if ( $NotEmpty && $isSetFile && ($functionExist || $fileExist ) ) {
                  if ( !function_exists($notice['function_where']['function_name']) ) {
                     require $notice['function_where']['include'];
                  }
                  if ( function_exists($notice['function_where']['function_name']) ) {
                     $ids_table = $notice['function_where']['function_name']($notice, $bean);
                     $where_ = '';
                     foreach ( $ids_table as $id_ ) {
                        if ( $where_ != '' )
                           $where_ .= ",";
                        $where_ .= "'" . $id_ . "'";
                     }
                     $beans_query .= " and id in ($where_)";
                  }
               }
            }
            $beans_result = $this->db->query($beans_query);

            while ( $bean_row = $this->db->fetchByAssoc($beans_result) ) {
               $bean->retrieve($bean_row['id']);
               $this->_prep_and_send($notice, $bean);
            }
         } elseif ( $this->launch_type == 'value' ) {
            if ( !empty($notice['notice_module']) && $notice['notice_module'] == $pre_saved_bean->module_dir ) {
               $this->_prep_and_send($notice, $pre_saved_bean);
            }
         }
      }
   }

   function _prep_and_send($notice, $bean) {
      $this->current_notice = $notice;
      $_additional_fields = array();



      if ( !$this->notice_limits->check($notice, $bean->id) ) {
         # Osiagnieto limit wysłanych powiadomień dla tego wpisu
         return;
      }


      // check if required fields are there
      if ( isset($notice['required_fields']) && is_array($notice['required_fields']) && count($notice['required_fields']) > 0 ) {
         $break_the_loop = false;

         foreach ( $notice['required_fields'] as $required_field ) {
            if ( empty($bean->$required_field) ) {
               $break_the_loop = true;
               break;
            }
         }

         if ( $break_the_loop ) {
            $GLOBALS['log']->fatal("NOTICE GENERATOR: missing required fields for module : " . $notice['notice_module'] . " id: " . $bean->id);
            return;
         }
      }

      if ( !$this->checkConditions($bean, $notice) )
         return;


      if ( isset($notice['emails']) ) {
         $GLOBALS['log']->info("NOTICE GENERATOR: Parsing email addresses definitions:  " . print_r($this->current_notice['emails'], true));

         // Przekazanie tablicy maili do funkcji prasuącej
         if ( !$this->parseEmailsAddresses($notice['emails'], $bean) ) {
            # Brak adresów email
            return;
         }
      } else {
         // No email - nowhere to send - skippind
         $GLOBALS['log']->fatal("NOTICE GENERATOR: no email addresses found for module: " . $notice['notice_module'] . " id: " . $bean->id);
         return;
      }




      if ( isset($notice['additional_fields']) && is_array($notice['additional_fields']) && count($notice['additional_fields']) > 0 ) {
         foreach ( $notice['additional_fields'] as $add_field ) {
            if ( !empty($add_field['value']) ) {
               $_additional_fields[$add_field['name']] = $this->prepToDisplay($bean, $add_field['value']);
            } elseif ( !empty($add_field['get_from']) && $add_field['get_from']['type'] == "related_module" ) {
               $related_bean = SugarModule::get($add_field['get_from']['related_module'])->loadBean();
               $related_bean->retrieve($bean->$add_field['get_from']['related_id_from_field']);

               $temp = $this->prepToDisplay($related_bean, $add_field['get_from']['related_field_name']);

               $_additional_fields[$add_field['name']] = $temp;
            } elseif ( !empty($add_field['path']) && is_array($add_field['path']) ) {
               $response = $this->walkPath($bean, $add_field['path']);

               $GLOBALS['log']->debug("NOTICE GENERATOR: Additional fields path parsing: " . print_r($response, true));
               $_additional_fields = array_merge_recursive($_additional_fields, $response);
            }
         }
      }
      global $sugar_config;
      $url = rtrim($sugar_config['site_url'], '/');
      $_additional_fields['link_to_bean'] = "<a href='" . $url . '/index.php' . "?module=" . $bean->module_dir . "&action=DetailView&record=" . $bean->id . "'>" . $bean->name . "</a>";
      $_additional_fields['pure_link'] = $url . '/index.php' . "?module=" . $bean->module_dir . "&action=DetailView&record=" . $bean->id;

      /*
       * 
       */
      /* 	if(isset($notice['subject']) && $notice['subject']!='')
        {
        $_additional_fields['subject'] =  $notice['subject'];

        if(isset($_email_fields))
        {
        foreach ($_email_fields as $name)
        {
        $_additional_fields['subject'] = str_replace("\$$name", $this->prepToDisplay($bean,$name), $_additional_fields['subject']);
        }
        }
        }

        if(isset($notice['message']) && $notice['message']!='')
        {
        $_additional_fields['message'] =  $notice['message'];
        if(isset($_email_fields))
        {
        foreach ($_email_fields as $name)
        {
        $_additional_fields['message'] = str_replace("\$$name", $this->prepToDisplay($bean,$name), $_additional_fields['message']);
        }
        }

        $_additional_fields['message'] = str_replace("\$link_to_bean", $_additional_fields['link_to_bean'], $_additional_fields['message']);

        }
       */
      $email = new NoticeEmailUtils();
      $parent_id = null;
      $parent_type = null;
      if ( isset($notice['parent']) && is_array($notice['parent']) ) {
         $parent_id = $this->findParent($bean, $notice);
         if ( is_string($notice['parent']['parent_type']) ) {
            $parent_type = $notice['parent']['parent_type'];
         } else if ( isset($notice['parent']['parent_type']['field']) ) {//dynamic z pola (w przypadku gdy dziedziczymy z elastycznej relacji w innym module typ wydażenie
            $parent_type = $bean->{$notice['parent']['parent_type']['field']};
         }
      }
      $bean_relationship = isset($notice['bean_relationship']) ? $notice['bean_relationship'] : null;
      //reset $this->dynamic_attachments[] 
      $this->dynamic_attachments = array();
      $additional_attachments = $this->generateAttachments($notice, $bean->id);

      $email->sendEmail($notice['notice_template'], $this->email_addreses, $notice['notice_module'], $bean->id, $_additional_fields, $bean, $parent_type, $parent_id, $bean_relationship, $additional_attachments);

      //too Old Sugar for using BeanFactory
      //$note = BeanFactory::getBean('Notes');
      $note = SugarModule::get('Notes')->loadBean();
      foreach ( $this->dynamic_attachments as $note_id ) {
         $note->mark_deleted($note_id);
         $removeFile = "upload://$id";
         if ( file_exists($removeFile) ) {
            if ( !unlink($removeFile) ) {
               $GLOBALS['log']->error("*** Could not unlink() file: [ {$removeFile} ]");
            }
         }
      }
      $this->dynamic_attachments = array();
      # SaveLog
      $this->notice_limits->saveLog($notice['notice_id'], $bean->id);

      if ( !empty($notice['after_send']) ) {
         $isSetFile = isset($notice['after_send']['file']);
         $fileExist = file_exists($notice['after_send']['file']);
         $functionExist = function_exists($notice['after_send']['function']);
         if ( $isSetFile && ($functionExist || $fileExist ) ) {
            if ( !function_exists($notice['after_send']['function']) ) {
               require $notice['after_send']['file'];
            }
            if ( function_exists($notice['after_send']['function']) ) {
               $notice['after_send']['function']($notice, $bean);
            }
         }
      }
   }

   private function checkConditions($bean, $notice) {
      if ( isset($notice['conditions']) && is_array($notice['conditions']) && count($notice['conditions']) > 0 ) {
         $conditions_break = false;

         foreach ( $notice['conditions'] as $condition ) {
            if ( $condition['type'] == 'value_change' ) {
               if ( isset($condition['to_value']) ) {
                  if ( !empty($bean->fetched_row) && $bean->$condition['field'] == $condition['to_value'] && $bean->fetched_row[$condition['field']] != $condition['to_value'] ) {
                     //warunek działa
                  } else {
                     $conditions_break = true;
                  }
               } else {
                  if ( !empty($bean->fetched_row) && $bean->$condition['field'] != $bean->fetched_row[$condition['field']] ) {
                     
                  } else {
                     $conditions_break = true;
                  }
               }
            } elseif ( $condition['type'] == 'related_bean_value' ) {
               $related_bean = SugarModule::get($condition['related_bean'])->loadBean();
               $related_bean->retrieve($bean->$condition['related_id']);

               if ( is_array($condition['value']) ) {
                  if ( empty($related_bean) || !in_array($related_bean->$condition['related_field'], $condition['value']) ) {
                     $conditions_break = true;
                  }
               } else {
                  if ( empty($related_bean) || $related_bean->$condition['related_field'] != $condition['value'] ) {
                     $conditions_break = true;
                  }
               }
            } elseif ( $condition['type'] == 'value' ) {
               // Pole beana ma jakas wartosc
               if ( isset($condition['negate']) ) {//nie jest równy, nie jest jednym z
                  if ( is_array($condition['value']) ) {//nie jest jednym z 
                     foreach ( $condition['value'] as $val ) {
                        if ( $bean->$condition['field'] == $val )
                           $conditions_break = true;
                     }
                  } elseif ( $bean->$condition['field'] == $condition['value'] ) {//nie równa się
                     $conditions_break = true;
                  }
               } else {
                  if ( is_array($condition['value']) ) {//jeden z 
                     $conditions_break = true;
                     foreach ( $condition['value'] as $val ) {
                        if ( $bean->$condition['field'] == $val )
                           $conditions_break = false;
                     }
                  } elseif ( $bean->$condition['field'] != $condition['value'] ) {
                     $conditions_break = true;
                  }
               }
            } elseif ( $condition['type'] == 'new_bean' ) {
               if ( !empty($bean->fetched_row) && $bean->new_with_id == false ) {
                  $conditions_break = true;
               }
            } elseif ( $condition['type'] == 'function' ) {
               $NotEmpty = !empty($condition['function_name']);
               $isSetFile = isset($condition['include']);
               $fileExist = file_exists($condition['include']);
               $functionExist = function_exists($condition['function_name']);


               if ( $NotEmpty && $isSetFile && $fileExist ) {
                  if ( !function_exists($condition['function_name']) ) {
                     require $condition['include'];
                  }
                  if ( function_exists($condition['function_name']) ) {
                     if ( !$condition['function_name']() ) {
                        $conditions_break = true;
                     }
                  }
               }
            }
         }

         // Warunki niespełnione
         return !$conditions_break;
      }
      return true;
   }

   private function findParent($bean, $notice) {
      if ( is_array($notice['parent']) ) {

         if ( isset($notice['parent']['path']) ) {//sciezka
            $result = $this->walkPath($bean, $notice['parent']['path']);
            return $result['parent_id'];
         } elseif ( isset($notice['parent']['related_id']) ) {//pole w beanie
            return $bean->{$notice['parent']['related_id']};
         } elseif ( isset($notice['parent']['static']) ) {// statuczne id
            return $notice['parent']['static'];
         }
      }
      return null;
   }

   private function generateAttachments($notice, $bean_id) {
      if ( isset($notice['attachments']) ) {
         $attachments = array();

         foreach ( $notice['attachments'] as $attachment_def ) {
            $type = isset($attachment_def['type']) ? $attachment_def['type'] : 'note';
            switch ( $type ) {
               case 'note':
                  if ( isset($attachment_def['note_id']) )
                     $attachments[] = $attachment_def['note_id'];
                  else
                     $GLOBALS['log']->fatal('NOTICE GENERATOR: Attachment Definition Lacks note_id');
                  break;
               case 'PDF' :
                  if ( isset($attachment_def['tempalate_id']) ) {
                     $filename = !empty($attachment_def['filename']) ? $attachment_def['filename'] : null;
                     $note_id = $this->generatePDFAttachment($attachment_def['tempalate_id'], $bean_id, $notice['notice_module'], $filename);
                     if ( $note_id ) {
                        $attachments[] = $note_id;
                        $this->dynamic_attachments[] = $note_id;
                     } else
                        $GLOBALS['log']->fatal('NOTICE GENERATOR: PDF generation error');
                  } else
                     $GLOBALS['log']->fatal('NOTICE GENERATOR: Attachment Definition Lacks tempalate_id');
                  break;
               case 'function' :
                  if ( isset($attachment_def['function_name']) ) {

                     if ( isset($attachment_def['include']) ) {
                        if ( file_exists($attachment_def['include']) ) {
                           include $attachment_def['include'];
                           if ( function_exists($attachment_def['function_name']) ) {
                              $attachments_ = $attachment_def['function_name']($bean_id); //TODO PArams
                              if ( is_array($attachments_) ) {
                                 foreach ( $attachments_ as $a ) {
                                    $attachments[] = $a;
                                    if ( isset($attachment_def['dynamic_content']) && $attachment_def['dynamic_content'] == true )
                                       $this->dynamic_attachments[] = $a;
                                 }
                              } else {
                                 if ( isset($attachment_def['dynamic_content']) && $attachment_def['dynamic_content'] == true )
                                    $this->dynamic_attachments[] = $attachments_;
                                 $attachments[] = $attachments_;
                              }
                           } else
                              $GLOBALS['log']->fatal('NOTICE GENERATOR: Attachemnt generation by function error: Function does not exist:' . $notice['function_name']);
                        } else
                           $GLOBALS['log']->fatal('NOTICE GENERATOR: Attachemnt generation by function error: File does not exist:' . $notice['include']);
                     } else
                        $GLOBALS['log']->fatal('NOTICE GENERATOR: Attachment Definition Lacks include');
                  } else
                     $GLOBALS['log']->fatal('NOTICE GENERATOR: Attachment Definition Lacks function_name');
                  break;
            }
         }
         return $attachments;
      }
      return null;
   }

   private function generatePDFAttachment($template_id, $bean_id, $module, $filename = null) {
      if ( file_exists('include/tcpdf/gen.php') ) {//TODO w pdf generatorze
         require_once 'include/tcpdf/gen.php';
         global $focus, $pdftemplate;
         //too old Sugar for using BeanFactory
         //$focus = BeanFactory::getBean($module, $bean_id);
         //$pdftemplate = BeanFactory::getBean('PDFTemplates', $template_id);
         $focus = SugarModule::get($module)->loadBean();
         $focus->retrieve($bean_id);
         $pdftemplate = SugarModule::get('PDFTemplates')->loadBean();
         $pdftemplate->retrieve($template_id);
         if ( isset($pdftemplate) && isset($focus) && !empty($pdftemplate->id) && !empty($focus->id) ) {
            if ( !empty($filename) && !str_end($filename, ".pdf") ) {
               $filename .= ".pdf";
            }
            $filename = !empty($filename) ? $filename : "attachment.pdf";
            $file_id = create_guid();
            $_GET['file'] = 'modules/PDFGenerator/tmp/attachment' . $file_id . '.pdf';
            $pdf = new PDF();
            $pdf->Output();
            $content = file_get_contents($_GET['file']);
            $upload_file = new UploadFile('filename_file');
            $upload_file->set_for_soap($filename, $content);
            $note = new Note();
            $note->filename = $filename;
            $note->file_mime_type = 'application/pdf';
            $note->file_ext = 'pdf';
            $note->name = $note->filename;
            $note->id = $file_id;
            $note->new_with_id = true;
            $upload_file->final_move($note->id);
            $note_id = $note->save();
            return $note_id;
         } else {
            $GLOBALS['log']->fatal('NOTICE GENERATOR: Problem with bean or template!'); //TODO
            return null;
         }
      } else {
         $GLOBALS['log']->fatal('NOTICE GENERATOR: PDF Engine not installed!');
         return null;
      }
   }

   private function prepToDisplay($bean, $field) {
      global $app_list_strings;
      if ( isset($bean->$field) ) {
         $name = $field;
         $type = $bean->field_defs[$name]['type'];
         switch ( $type ) {
            //brak sprawdzenia $bean->field_defs[$field]['options']!=''
            case 'enum':
               global $app_list_strings;
               $dropdown_name = $bean->field_defs[$name]['options'];
               $return = $app_list_strings[$dropdown_name][$bean->$name];
               break;
            case 'multienum':
               $options = $this->GetMultienumArray($bean->$name);
               $dropdown_name = $bean->field_defs[$name]['options'];
               $return = $this->translateMultienumFromAppListStrings($options, $dropdown_name);
               break;
            case 'currency':
               $return = format_number($bean->$name);
               break;
            default:
               $return = $bean->$name;
         }
      } else {
         return '';
      }
      return $return;
   }

   private function get_related_module($bean, $relationship) {
      $query = "SELECT * FROM relationships WHERE relationship_name = '" . $relationship . "' AND deleted = 0";

      $row = $bean->db->fetchByAssoc($bean->db->query($query));

      $module_name = $bean->module_name;

      if ( $module_name == null ) {
         //BEFORE 6.4

         $module_name = $bean->module_dir;
      }
      $related_module = ($row['lhs_module'] == $module_name) ? $row['rhs_module'] : $row['lhs_module'];

      $relationship_type = $row['relationship_type'];

      return $related_module;
   }

   /**
    * Funkcja przechodzi zadeklarowaną ścieżke i wyciąga z poszczególnych beanów wartości wskazanych pól
    * @param object $bean - Bean od którego rozpoczynamy drogę
    * @param array $path - Definicja ścieżki do przejścia
    * @return multitype:array|false
    */
   private function walkPath($bean, $path) {
      # Inicjalizacja tablicy, w ktorej przechowywać będziemy wyciągnięte wartości zmiennych
      $return_array = array();

      # Flaga pozwalająca na przerwanie głównej pętli w przypadku rozpoczęcia nowy
      $skip = false;

      # Sprawdzamy czy ścieżka jest tablicą i czy bean nie jest pusty
      if ( is_array($path) && !empty($bean) ) {
         # Przechodzimy przez każdy element ścieżki
         foreach ( $path as $key => $step ) {
            # Klucz 'relationship' oznacza, że należy wczytać daną relację naszego beana 
            # Warto zaznaczyć, że wartością tego klucza nie jest nazwa samej relacji a nazwa pola tej relacji w danym beanie
            if ( isset($step['relationship']) ) {
               // relationship - nazwa pola relacji w module, nie nazwa samej relacji
               $loaded = $bean->load_relationship($step['relationship']);

               # Sprawdzamy czy relacja została wczytana
               if ( $loaded ) {
                  # Domyślny limit dla liczby zwracanych beanów jest równy 1
                  /*
                    $params = array('limit' => '1');

                    # Jeśli potrzeba pobrać więcej lub wszystkie wystarczy w konfiguracji ścieżki dodać tablicę parameters
                    # pustą lub z kluczem limit wskazującym ile rekordów ma zostać pobranych
                    if(isset($step['parameters']))
                    {
                    $params = $step['parameters'];
                    }
                   */
                  // Wczytanie wszystkich beanów z relacji spełniących warunek (jeśli został podany)
                  /* jozwiakowskis wczytywanie beanów relacji */
                  $_relate_module_name = $this->get_related_module($bean, $bean->field_defs[$step['relationship']]['relationship']);
                  $_template = SugarModule::get($_relate_module_name)->loadBean();
                  $alist = $bean->{$step['relationship']}->getBeans($_template);
                  /* jozwiakowskis */
                  # W zależności od ilości zwróconych obiektów inicjujemy oddzielne ścieżki gdy jest więcej niż 1 
                  if ( count($alist) > 1 ) {
                     $adjusted_path = array();

                     # Wycinamy część scieżki, która już przeszliśmy
                     foreach ( $path as $sub_key => $sub_step ) {
                        if ( $sub_key > $key ) {
                           $adjusted_path[] = $sub_step;
                        }
                     }

                     # Dla każdego powiązanego beana inicjujemy oddzielną ścieżkę aby kontynuował drogę
                     foreach ( $alist as $sub_bean ) {
                        # Rozpoczęcie nowej ścieżki
                        $response = $this->walkPath($sub_bean, $adjusted_path);

                        # Sprawdzamy czy nie wystąpił błąd
                        if ( $response !== false ) {
                           $return_array = array_merge_recursive($response, $return_array);
                        }

                        $temp_array = array();

                        # Jeśli dla danej ścieżki istnieje definicja tablicy variables to próbujemy uzupełnić zadeklarowane w niej zmienne
                        # wartościami z aktualnego powiązanego beana
                        if ( isset($step['variables']) && is_array($step['variables']) ) {
                           foreach ( $step['variables'] as $variable ) {
                              # $variable['name'] - nazwa zmiennej, która chcemy użyć w szablonie
                              # $variable['field'] - nazwa pola, którego wartość mamy podstawić do zmiennej
                              $temp_array[$variable['name']][] = $this->prepToDisplay($sub_bean, $variable['field']);
                           }
                        }

                        # Mergeujemy tablice tymczasową z wynikową
                        $return_array = array_merge_recursive($return_array, $temp_array);
                     }

                     # Relacja do wiele, rozpoczęto nowe ścieżki, zmieniamy flagę przerwania pętli na true
                     $skip = true;
                  }
                  # Tylko jeden obiekt danego typu, kontynuujemy ścieżkę
                  elseif ( count($alist) == 1 ) {
                     reset($alist);
                     # Pobieramy aktualny obiekt
                     $bean = current($alist);

                     # Tymczasowa tablica do zmiennych
                     $temp_array = array();

                     # Jeśli dla danej ścieżki istnieje definicja tablicy variables to próbujemy uzupełnić zadeklarowane w niej zmienne
                     # wartościami z aktualnego beana
                     if ( isset($step['variables']) && is_array($step['variables']) ) {
                        foreach ( $step['variables'] as $variable ) {
                           # $variable['name'] - nazwa zmiennej, która chcemy użyć w szablonie
                           # $variable['field'] - nazwa pola, którego wartość mamy podstawić do zmiennej

                           $temp_array[$variable['name']] = $this->prepToDisplay($bean, $variable['field']);
                        }
                     }

                     # Mergeujemy tablice tymczasową z wynikową
                     $return_array = array_merge_recursive($return_array, $temp_array);
                  } else {
                     # Dana relacja jest pusta
                     break;
                  }
               } else {
                  # Relacja nie istnieje lub był problem z jej wczytaniem 
                  break;
               }
            }
            # Drugą możliwością jest rozdzielenie ścieżki 
            elseif ( isset($step['split']) && is_array($step['split']) ) {
               # Rozdzielenie ścieżki na dwie lub więcej dróg
               foreach ( $step['split'] as $split_path ) {
                  # Rozpoczęcie nowej ścieżki 
                  $response = $this->walkPath($bean, $split_path);

                  # Sprawdzamy czy nie wystąpił błąd
                  if ( $response !== false ) {
                     # Mergujemy tablice wynikowe
                     $return_array = array_merge_recursive($response, $return_array);
                  }
               }

               # Teoretycznie po rozdzieleniu ścieżki możemy wymuszać koniec pętli - do rozważenia
               # $skip = true;
            }

            if ( $skip ) {
               # Poprzednia relacja była relacją do wiele, od tego momentu rozpoczęto
               # nowe przejście ścieżki, nie ma sensu kontynuować pętli, przerywamy
               break;
            }
         }
      }

      # Zwracamy tablicę z pobranymi zmiennymi
      return $return_array;
   }

   /**
    * Funkcja zwracająca adres email po przeprasowaniu konfiguracji danego powiadomienia
    * @param array $emails - tablica z adresami email lub wskazaniami skąd adres ma zostać pobrany
    * @param string $target - Wskazuje na typ adresata to - domyślny, głowny, cc - kopia , bcc - kopia ukryta
    */
   private function parseEmailsAddresses($emails, $bean, $target = 'to') {

      if ( !empty($emails) && is_array($emails) && count($emails) > 0 ) {
         //generowanie zestawu adresów z funkcji;
         if ( isset($emails['function']) ) {

            if ( isset($emails['function']['function_name']) && isset($emails['function']['include']) && file_exists($emails['function']['include'])
            ) {
               require_once $emails['function']['include'];
               if ( function_exists($emails['function']['function_name']) ) {
                  if ( $to = $emails['function']['function_name']('to', $bean->id) )
                     $this->email_addreses['to'] = $to;
                  if ( $cc = $emails['function']['function_name']('cc', $bean->id) )
                     $this->email_addreses['cc'] = $cc;
                  if ( $bcc = $emails['function']['function_name']('bcc', $bean->id) )
                     $this->email_addreses['bcc'] = $bcc;
               }
            }
            return true;
         }
         if ( isset($emails['to']) ) {
            //reports_to_id
            $this->parseEmailsAddresses($emails['to'], $bean);
         }

         if ( isset($emails['cc']) ) {
            $this->parseEmailsAddresses($emails['cc'], $bean, "cc");
         }

         if ( isset($emails['bcc']) ) {
            $this->parseEmailsAddresses($emails['bcc'], $bean, "bcc");
         }


         foreach ( $emails as $key => $email_addres ) {
            if ( isset($email_addres['path']) ) {
               # Przechodzimy ścieżke
               $path = $email_addres['path'];
               $response = $this->walkPath($bean, $path);

               if ( isset($response['id']) && !empty($response['id']) ) {
                  # Jeśli zwrócony został pojedyńczy identyfikator to konwertujemy na tablicę 
                  if ( !is_array($response['id']) )
                     $response['id'] = ( array ) $response['id'];

                  foreach ( $response['id'] as $email_ad ) {
                     # Wyszukiwanie adresu email dla zadanego identyfikatora beana
                     $temp = $this->idToEmail($email_ad);

                     # Sprawdzenie poprawności wyciągniętego adresu
                     if ( !empty($temp) && filter_var($temp, FILTER_VALIDATE_EMAIL) !== false ) {
                        # Jest poprawny -> dodajemy
                        $this->email_addreses[$target][] = $temp;
                     }
                  }
               }
            }
            /* jozwiakowskis walidacja poprawności emaila */ elseif ( $key !== 'to' && $key !== 'cc' && $key !== 'bcc' && !self::filter_email($email_addres) )
            /* jozwiakowskis */ {
               if ( is_array($email_addres) && $email_addres['field_name'] ) {
                  $this->email_addreses[$target][] = $bean->$email_addres['field_name'];
               } elseif ( is_array($email_addres) && isset($email_addres['related_id']) && !isset($email_addres['related_bean']) && !isset($email_addres['related_bean_id']) ) {
                  $temp = $this->idToEmail($bean->$email_addres['related_id']);

                  if ( !empty($temp) && filter_var($temp, FILTER_VALIDATE_EMAIL) !== false ) {
                     $this->email_addreses[$target][] = $temp;
                  }
               } elseif ( is_array($email_addres) && isset($email_addres['related_bean']) && isset($email_addres['related_bean_id']) ) {
                  $related_bean = SugarModule::get($email_addres['related_bean'])->loadBean();
                  $related_bean->retrieve($bean->$email_addres['related_bean_id']);

                  if ( isset($email_addres['related_id']) ) {
                     $temp = $this->idToEmail($related_bean->$email_addres['related_id']);

                     if ( !empty($temp) && filter_var($temp, FILTER_VALIDATE_EMAIL) !== false ) {
                        $this->email_addreses[$target][] = $temp;
                     }
                  } elseif ( isset($email_addres['relationship']) ) {
                     $alist = array();
                     $related_bean->load_relationship($email_addres['relationship']);
                     $alist = $related_bean->{$email_addres['relationship']}->get();
                     if ( count($alist) > 0 ) {
                        foreach ( $alist as $aid ) {
                           $temp = $this->idToEmail($aid);

                           if ( !empty($temp) && filter_var($temp, FILTER_VALIDATE_EMAIL) !== false ) {
                              $this->email_addreses[$target][] = $temp;
                           }
                        }
                     }
                  }
               }
               // Klucz relationship zostaje ze względu na kompatybilność wsteczną
               elseif ( is_array($email_addres) && isset($email_addres['relationship']) ) {
                  $alist = array();
                  $bean->load_relationship($email_addres['relationship']);
                  $alist = $bean->{$email_addres['relationship']}->get();
                  if ( count($alist) > 0 ) {
                     foreach ( $alist as $aid ) {
                        $temp = $this->idToEmail($aid);

                        if ( !empty($temp) && filter_var($temp, FILTER_VALIDATE_EMAIL) !== false ) {
                           $this->email_addreses[$target][] = $temp;
                        }
                     }
                  }
               } elseif ( !empty($email_addres) && filter_var($email_addres, FILTER_VALIDATE_EMAIL) !== false ) {
                  # Pojedyńczy adres email
                  $this->email_addreses[$target][] = $email_addres;
               }
               # Emaile mają zostać wyciagniete po przejściu zadeklarowanej ścieżki
            } elseif ( $key !== 'to' && $key !== 'cc' && $key !== 'bcc' && self::filter_email($email_addres) ) {
               // String is a single email address
               $this->email_addreses[$target][] = $email_addres[0];
            }
         }
      } elseif ( is_string($emails) && strstr($emails, ',') === false && strstr($emails, ';') === false && filter_var($emails, FILTER_VALIDATE_EMAIL) !== false ) {
         // String is a single email address
         $this->email_addreses[$target][] = $emails;
      } elseif ( is_string($emails) && strstr($emails, ',') !== false && strstr($emails, ';') === false ) {
         // String is possibly multiple email addresses separeted by a comma
         $ea = explode(',', $emails);

         if ( count($ea) > 0 ) {
            foreach ( $ea as $email_ad ) {
               if ( !empty($email_ad) && filter_var($email_ad, FILTER_VALIDATE_EMAIL) !== false ) {
                  // string is not empty and validates as email address, adding
                  $this->email_addreses[$target][] = $email_ad;
               }
            }
         }
      } elseif ( is_string($emails) && strstr($emails, ';') !== false && strstr($emails, ',') === false ) {
         // String is possibly multiple email addresses separeted by a semicolon
         $ea = explode(';', $emails);

         if ( count($ea) > 0 ) {
            foreach ( $ea as $email_ad ) {
               if ( !empty($email_ad) && filter_var($email_ad, FILTER_VALIDATE_EMAIL) !== false ) {
                  // string is not empty and validates as email address, adding
                  $this->email_addreses[$target][] = $email_ad;
               }
            }
         }
      } else {
         # Nie znaleziono żadnego adresu email, zwracamy bład
         $GLOBALS['log']->fatal("NOTICE GENERATOR: no email addresses found for module: " . $this->current_notice['notice_module'] . " id: " . $bean->id);
         return false;
      }

      # Prasowanie wykonane poprawnie
//                $emails_to_send = array();
//                
//                if(isset($emails['to'])){
//                    foreach($emails['to'] as $email_addres_array){
//                            foreach($email_addres_array as $email){
//                                    $this->email_addreses['to'][] = $email;
//                            }   
//                        }
//                }
//                var_dump($this->email_addreses); die;    
      return true;
   }

   /*
    *
    *  Zamienia id bean'a na jego email o ile taki jest zdefiniowany w przeciwnym wypadku zwraca false
    */

   function idToEmail($id) {
      $emailQuery = "Select * from email_addresses adr join email_addr_bean_rel re on (re.email_address_id = adr.id) where bean_id ='" . $id . "' and re.primary_address=1 and adr.deleted='0' AND re.deleted='0'"; //TODO
      $resultEmail = $this->db->query($emailQuery);
      if ( mysqli_num_rows($resultEmail) > 0 ) {
         $emailAddressRow = $this->db->fetchByAssoc($resultEmail);
         $emailAddress = $emailAddressRow['email_address'];
         if ( $emailAddress != "" && $emailAddress != null ) {
            return $emailAddress;
         } else {
            // puste pole nie ma co wysyłać;
            return false;
         }
      } else {
         // nie znaleziono przypisanego maila
         return false;
      }
   }

   function filter_email($array_with_email) {
      $result = false;
      if ( is_array($array_with_email) ) {
         $result = ( bool ) filter_var($array_with_email[0], FILTER_VALIDATE_EMAIL);
      }
      return $result;
   }

   function GetMultienumArray($string) {
      $value = str_replace("^", "", $string);
      $enum_list = explode(",", $value);
      return $enum_list;
   }

   function translateMultienumFromAppListStrings($options, $dropdown_name) {
      global $app_list_strings;
      $options_fixed = array();
      foreach ( $options as $option ) {
         $options_fixed[] = $app_list_strings[$dropdown_name][$option];
      }
      $return = implode(',', $options_fixed);
      return $return;
   }

}

class NoticeLimits {

   private $db;

   public function __construct() {

      $this->db = $GLOBALS['db'];
   }

   public function check($notice_data, $bean_id) {

      $this->checkTable();
      $query = "Select count(id) c from notice_log where notice_id='{$notice_data['notice_id']}' and bean_id='$bean_id'";
      $result = $this->db->query($query);
      if ( $row = $this->db->fetchByAssoc($result) ) {
         $current_global_count = $row['c'];
         $query = "Select count(id) c from notice_log where notice_id='{$notice_data['notice_id']}' and bean_id='$bean_id' and DATEDIFF(CURDATE(),date_entered)=0";
         $result = $this->db->query($query);
         if ( $row = $this->db->fetchByAssoc($result) ) {
            $current_daily_count = $row['c'];
         } else
            $current_daily_count = 0;
      } else {
         $current_global_count = 0;
         $current_daily_count = 0;
      }
      if ( !isset($notice_data['global_limit']) && !isset($notice_data['daily_limit']) ) {
         # Nie ma potrzeby sprawdzać limitów
         return true;
      }


      if ( isset($notice_data['global_limit']) ) {
         if ( isset($notice_data['daily_limit']) ) {
            if ( $current_global_count < $notice_data['global_limit'] && $current_daily_count < $notice_data['daily_limit'] ) {
               return true;
            } else {
               return false;
            }
         } else {
            if ( $current_global_count < $notice_data['global_limit'] ) {
               return true;
            } else {
               return false;
            }
         }
      } elseif ( isset($notice_data['daily_limit']) ) {
         if ( $current_daily_count < $notice_data['daily_limit'] ) {
            return true;
         } else {
            return false;
         }
      }
   }

   public function saveLog($notice_id, $bean_id) {//TODO statusy i opsiy błędów
      $this->checkTable();
      $query = "INSERT INTO notice_log (id, deleted, date_entered, notice_id, bean_id, status) VALUES (
					UUID(),0,NOW(),'$notice_id', '$bean_id', 'done')";
      //TODO status...
      $GLOBALS['log']->debug('NOTICE GENERATOR| GLOBAL LOG QUERY |' . $query);
      $this->db->query($query);
   }

   private function checkTable() {
      if ( !$this->db->tableExists('notice_log') ) {
         $this->db->query("CREATE TABLE IF NOT EXISTS notice_log (
  				id char(36) NOT NULL,
  				deleted tinyint(1) DEFAULT '0',
  				date_entered datetime DEFAULT NULL,
  				notice_id varchar(36) NOT NULL,
  				bean_id varchar(36) NOT NULL,
  				status varchar(25) DEFAULT NuLL,
  				PRIMARY KEY (id),
  				KEY idx_notice_id (bean_id,notice_id,date_entered)
				)");
      }
   }

}
