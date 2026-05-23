<?php

require_once 'include/MVC/Controller/SugarController.php';

class CandidatesController extends SugarController
{
    public function action_showduplicates()
    {
        $this->view = "showduplicates";
    }
}
