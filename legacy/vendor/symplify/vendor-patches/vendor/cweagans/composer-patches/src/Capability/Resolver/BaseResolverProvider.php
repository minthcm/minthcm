<?php

namespace VendorPatches202602\cweagans\Composer\Capability\Resolver;

use VendorPatches202602\Composer\Composer;
use VendorPatches202602\Composer\IO\IOInterface;
use VendorPatches202602\Composer\Plugin\Capability\Capability;
use VendorPatches202602\Composer\Plugin\PluginInterface;
abstract class BaseResolverProvider implements Capability, ResolverProvider
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
     * BaseResolverProvider constructor.
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
    public abstract function getResolvers() : array;
}
