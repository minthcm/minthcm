<?php

/**
 * @file
 * Contains \cweagans\Composer\Resolvers\ResolverInterface.
 */
namespace VendorPatches202602\cweagans\Composer\Downloader;

use VendorPatches202602\Composer\Composer;
use VendorPatches202602\Composer\IO\IOInterface;
use VendorPatches202602\Composer\Plugin\PluginInterface;
use VendorPatches202602\cweagans\Composer\Patch;
interface DownloaderInterface
{
    /**
     * DownloaderInterface constructor.
     *
     * @param Composer $composer
     *   The current composer object from the main plugin. Used to locate/read
     *   package metadata and configuration.
     * @param IOInterface $io
     *   IO object to use for resolver input/output.
     * @param PluginInterface $plugin
     *   The main plugin class.
     */
    public function __construct(Composer $composer, IOInterface $io, PluginInterface $plugin);
    /**
     * Apply a patch.
     *
     * This method sets Patch->localPath to wherever the patch was downloaded to.
     *
     * @param Patch $patch
     *   The patch to apply.
     */
    public function download(Patch $patch) : void;
}
