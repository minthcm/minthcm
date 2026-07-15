<?php

declare (strict_types=1);
namespace VendorPatches202602\cweagans\Composer\Command;

use VendorPatches202602\Composer\Command\BaseCommand;
use VendorPatches202602\cweagans\Composer\Plugin\Patches;
abstract class PatchesCommandBase extends BaseCommand
{
    /**
     * Get the Patches plugin
     *
     * @return Patches|null
     */
    protected function getPatchesPluginInstance() : ?Patches
    {
        foreach ($this->requireComposer()->getPluginManager()->getPlugins() as $plugin) {
            if ($plugin instanceof Patches) {
                return $plugin;
            }
        }
        return null;
    }
}
