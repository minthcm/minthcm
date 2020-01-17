<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

require_once('include/MVC/View/views/view.edit.php');

class AOS_ProductsViewEdit extends ViewEdit {

   public function __construct() {
      parent::__construct();
      //View Tools BEGIN #36858 (SuiteFixes_7_7_X [7.7.8] Brak powiązania produktu z kategorią)
      $this->useForSubpanel = true;
      //View Tools END #36858 (SuiteFixes_7_7_X [7.7.8] Brak powiązania produktu z kategorią)
   }

   public function display() {
      global $app_strings, $sugar_config;

      isset($this->bean->product_image) ? $image = $this->bean->product_image : $image = '';


      $temp = str_replace($sugar_config['site_url'] . '/' . $sugar_config['upload_dir'], "", $image);
      $html = '<span id=\'new_attachment\' style=\'display:';
      if ( !empty($this->bean->product_image) ) {
         $html .= 'none';
      }
      $html .= '\'><input name="uploadimage" tabindex="3" type="file" size="60"/>
        	</span>
		<span id=\'old_attachment\' style=\'display:';
      if ( empty($image) ) {
         $html .= 'none';
      }
      $html .= '\'><input type=\'hidden\' id=\'deleteAttachment\' name=\'deleteAttachment\' value=\'0\'>
		' . $temp . '<input type=\'hidden\' name=\'old_product_image\' value=\'' . $image . '\'/>
		<input type=\'button\' class=\'button\' value=\'' . $app_strings['LBL_REMOVE'] . '\' onclick=\'deleteProductImage();\' >
		</span>';

      $this->ss->assign('PRODUCT_IMAGE', $html);
      parent::display();
   }

}
