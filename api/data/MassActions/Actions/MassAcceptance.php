<?php

namespace MintHCM\Data\MassActions\Actions;

use Exception;
use MintHCM\Data\BeanFactory;
use MintHCM\Data\MassActions\MassAction;
use MintHCM\Utils\LegacyConnector;

class MassAcceptance extends MassAction
{
    const ICON = 'mdi-check-all';
    const LABEL = 'LBL_MASS_ACCEPTANCE';

    public function execute()
    {
        $accepted = 0;
        $skipped = 0;
        $errors = [];

        foreach ($this->ids as $id) {
            $bean = BeanFactory::getBean($this->module_name, $id);
            if (empty($bean) || !$bean->canBeAccepted()) {
                $skipped++;
                continue;
            }
            try {
                $bean->accept();
                $accepted++;
            } catch (Exception $e) {
                $GLOBALS['log']->error("Mass acceptance failed for {$bean->id}: " . $e->getMessage());
                $errors[] = $bean->id;
                $skipped++;
            }
        }

        return [
            'accepted' => $accepted,
            'skipped' => $skipped,
            'errors' => $errors,
        ];
    }

    public function hasAccess()
    {
        $connector = new LegacyConnector('ACLController');
        return $connector::checkAccess($this->module_name, 'edit', true);
    }
}
