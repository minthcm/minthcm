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

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

require_once('include/SugarLogger/LoggerManager.php');
require_once('include/SugarLogger/LoggerTemplate.php');

class DeveloperLogger implements LoggerTemplate {

   private $logSize;
   private $full_log_file;
   private $initialized;
   private $fp;

   public function __construct() {
      $this->_doInitialization();
      LoggerManager::setLogger('dev', 'DeveloperLogger');
   }

   protected function _doInitialization() {
      $this->logSize = 1024 * 1024 * 10; // 10 MBytes
      $this->full_log_file = 'developer.log';
      $this->dateFormat = '%c';
      $this->rollLog();
      $this->initialized = $this->_fileCanBeCreatedAndWrittenTo();
   }

   /**
    * Checks to see if the SugarLogger file can be created and written to
    */
   protected function _fileCanBeCreatedAndWrittenTo() {
      $this->_attemptToCreateIfNecessary();
      return file_exists($this->full_log_file) && is_writable($this->full_log_file);
   }

   /**
    * Creates the SugarLogger file if it doesn't exist
    */
   protected function _attemptToCreateIfNecessary() {
      if ( file_exists($this->full_log_file) ) {
         return;
      }
      @touch($this->full_log_file);
   }

   protected function rollLog($force = false) {
      if ( empty($this->logSize) ) {
         return;
      }

      if ( filesize($this->full_log_file) >= $this->logSize ) {
         unlink($this->full_log_file);
      }
   }

   public function log($method, $message) {
      if ( !$this->initialized ) {
         return;
      }

      $userID = (!empty($GLOBALS['current_user']->id)) ? $GLOBALS['current_user']->id : '-none-';
      if ( !$this->fp ) {
         $this->fp = fopen($this->full_log_file, 'a');
      }

      if ( is_array($message) && count($message) == 1 ) {
         $message = array_shift($message);
      }

      if ( is_array($message) ) {
         $message = print_r($message, true);
      }

      fwrite($this->fp, strftime($this->dateFormat) . ' [' . getmypid() . '][' . $userID . '][' . strtoupper($method) . '] ' . $message . "\n");
   }

}
