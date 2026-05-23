<?php

require_once 'include/MVC/View/views/view.edit.php';

#[\AllowDynamicProperties]
class KReportsViewEdit extends ViewEdit
{

    public function display()
    {
        $this->ev->ss->assign('SCRIPT_ID', rand(100, 900));
        parent::display();
        echo '<script type="text/javascript">'
            . '$(\'div.buttons\').hide();'
            . '</script>';
    }

}
