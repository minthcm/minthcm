<?php

class LastNextContactsPanelsEditor {

   const TYPE_TABS = 1;
   const TYPE_PANELS = 2;

   protected $new_fields = array( 'last_time_contact', 'date_planned_contact' );
   protected $CRM_version;
   protected $record;
   protected $type;

   public function __construct() {
      $this->CRM_version = isset($GLOBALS['sugar_config']['suitecrm_version']) && !empty($GLOBALS['sugar_config']['suitecrm_version']) ? 'SuiteCRM' : 'SugarCRM';
   }

   public function addLastNextContactPanelForModule($module, $type) {
      $this->type = $type;

      $this->ParseRecordView($module);
      $this->ParseListView($module);
      $this->ParseFiltersView($module);
   }

   protected function ParseRecordView($module) {
      $view = 'recordview';
      if ( $this->CRM_version == 'SuiteCRM' ) {
         $view = 'detailview';
      }
      $this->record = ParserFactory::getParser($view, $module);

      if ( $this->CRM_version == 'SuiteCRM' ) {
         if ( array_key_exists('LBL_LAST_NEXT_CONTACT_PANEL', $this->record->_viewdefs['templateMeta']['tabDefs']) ) {
            $this->record->_viewdefs['templateMeta']['tabDefs']['LBL_LAST_NEXT_CONTACT_PANEL']['newTab'] = ($this->type == self::TYPE_TABS);
         } else {
            $new_record_content = array(
               'newTab' => ($this->type == self::TYPE_TABS),
               'panelDefault' => 'expanded',
            );
            $this->record->_viewdefs['templateMeta']['tabDefs']['LBL_LAST_NEXT_CONTACT_PANEL'] = $new_record_content;
         }
      }

      $panel_fields = array(
         array(
            'name' => 'last_time_contact',
            'type' => 'DLNCdatetimecombo',
            'label' => 'LBL_LAST_TIME_CONTACT',
         ),
         array(
            'name' => 'date_planned_contact',
            'type' => 'DLNCdatetimecombo',
            'label' => 'LBL_DATE_PLANNED_CONTACT',
         ),
      );
      if ( $this->CRM_version == 'SuiteCRM' ) {
         $new_record_content = array( $panel_fields );
      } else {
         $new_record_content = array(
            'newTab' => ($this->type == self::TYPE_TABS),
            'panelDefault' => 'expanded',
            'fields' => $panel_fields
         );

         $tab_defs = $this->record->getTabDefs();
         if ( $this->type == self::TYPE_TABS ) {
            $tab_defs['LBL_RECORD_BODY']['newTab'] = true;
            $tab_defs['LBL_RECORD_BODY']['panelDefault'] = 'expanded';
            $tab_defs['LBL_LAST_NEXT_CONTACT_PANEL']['newTab'] = true;
            $tab_defs['LBL_LAST_NEXT_CONTACT_PANEL']['panelDefault'] = 'expanded';
         } else {
            $tab_defs['LBL_LAST_NEXT_CONTACT_PANEL']['newTab'] = false;
            $tab_defs['LBL_LAST_NEXT_CONTACT_PANEL']['panelDefault'] = 'expanded';
         }
         $this->record->setTabDefs($tab_defs);
      }
      $this->record->_viewdefs['panels']['LBL_LAST_NEXT_CONTACT_PANEL'] = $new_record_content;
      $this->record->handleSave(false);
   }

   protected function ParseListView($module) {
      $this->record = ParserFactory::getParser('listview', $module);

      if ( $this->CRM_version == 'SuiteCRM' ) {
         $this->parseListViewSuite();
      } else {
         $this->parseListViewSugar();
      }

      $this->record->handleSave(false);
   }

   protected function parseListViewSuite() {
      foreach ( $this->new_fields as $field ) {
         $this->record->_viewdefs[$field] = array(
            'label' => 'LBL_' . strtoupper($field),
            'type' => 'DLNCdatetimecombo',
            'width' => '10%',
            'default' => false,
         );
      }
   }

   protected function parseListViewSugar() {
      foreach ( $this->new_fields as $field ) {
         $this->record->_viewdefs['base']['view']['list']['panels'][0]['fields'][$field] = array(
            'name' => $field,
            'enabled' => true,
            'default' => false,
         );
      }
   }

   protected function ParseFiltersView($module) {
      $this->record = ParserFactory::getParser('advanced_search', $module);

      if ( $this->CRM_version == 'SuiteCRM' ) {
         $this->parseFiletrsViewSuite();
      } else {
         $this->parseFiltersViewSugar();
      }

      $this->record->handleSave(false);
   }

   protected function parseFiletrsViewSuite() {
      foreach ( $this->new_fields as $new_field ) {
         $this->record->_viewdefs[$new_field] = array(
            'label' => 'LBL_' . strtoupper($new_field),
            'name' => $new_field,
            'width' => '10%',
            'default' => true,
         );
      }
   }

   protected function parseFiltersViewSugar() {
      foreach ( $this->new_fields as $new_field ) {
         $this->record->_viewdefs['fields'][$new_field] = array();
      }
   }

}
