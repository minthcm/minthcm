<?php

namespace VendorPatches202602\cweagans\Composer\Capability\Patcher;

use VendorPatches202602\Composer\Composer;
use VendorPatches202602\Composer\IO\IOInterface;
use VendorPatches202602\Composer\Plugin\Capability\Capability;
use VendorPatches202602\Composer\Plugin\PluginInterface;
/**
 * Patcher provider interface.
 *
 * This capability will receive an array with 'composer' and 'io' keys as
 * constructor arguments. It also contains a 'plugin' key containing the
 * plugin instance that declared the capability.
 */
abstract class BasePatcherProvider implements Capability, PatcherProvider
{
    /**
     * @var Composer
     */
    protected $composer;
    /**
     * @var IOInterface
     */
    protected $io;
    /**
     * @var PluginInterface
     */
    protected $plugin;
    /**
     * BasePatcherProvider constructor.
     *
     * Store values passed by the plugin manager for later use.
     *
     * @param array $args
     *   An array of args passed by the plugin manager.
     */
    public function __construct(array $args)
    {
        $this->composer = $args['composer'];
        $this->io = $args['io'];
        $this->plugin = $args['plugin'];
    }
    /**
     * @inheritDoc
     */
    public abstract function getPatchers() : array;
}
