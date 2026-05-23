<?php

require_once 'include/MVC/View/views/view.detail.php';

#[\AllowDynamicProperties]
class KReportsViewDetail extends ViewDetail
{

    public function display()
    {
        $this->dv->ss->assign('SCRIPT_ID', rand(100, 900));
        parent::display();
    }

}
