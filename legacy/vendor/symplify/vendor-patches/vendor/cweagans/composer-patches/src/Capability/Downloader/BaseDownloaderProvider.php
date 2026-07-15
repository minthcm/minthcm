<?php

namespace VendorPatches202602\cweagans\Composer\Capability\Downloader;

use VendorPatches202602\Composer\Composer;
use VendorPatches202602\Composer\IO\IOInterface;
use VendorPatches202602\Composer\Plugin\Capability\Capability;
use VendorPatches202602\Composer\Plugin\PluginInterface;
/**
 * Downloader provider interface.
 *
 * This capability will receive an array with 'composer' and 'io' keys as
 * constructor arguments. It also contains a 'plugin' key containing the
 * plugin instance that declared the capability.
 */
abstract class BaseDownloaderProvider implements Capability, DownloaderProvider
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
     * BaseDownloaderProvider constructor.
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
    public abstract function getDownloaders() : array;
}
