<?php

require_once 'include/MVC/View/views/view.edit.php';

class KReportsViewEdit extends ViewEdit {

   public function display() {
      parent::display();
      echo '<script type="text/javascript">'
      . '$(\'div.buttons\').hide();'
      . '</script>';
   }

}
