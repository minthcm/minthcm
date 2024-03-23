<?php

namespace Codeception\Step;

use Codeception\Lib\ModuleContainer;
use Codeception\Util\Template;

class AsJson extends Action implements GeneratedStep
{
    public function run(ModuleContainer $container = null)
    {
        $container->getModule('REST')->haveHttpHeader('Content-Type', 'application/json');
        $resp = parent::run($container);
        $container->getModule('REST')->seeResponseIsJson();
        return json_decode($resp, true);
    }

    public static function getTemplate(Template $template)
    {
        $action = $template->getVar('action');

        // should only be applied to send* methods
        if (strpos($action, 'send') !== 0) return;

        $conditionalDoc = "* JSON response will be automatically decoded \n     " . $template->getVar('doc');

        return $template
            ->place('doc', $conditionalDoc)
            ->place('action', $action . 'AsJson')
            ->place('step', 'AsJson');
    }
}