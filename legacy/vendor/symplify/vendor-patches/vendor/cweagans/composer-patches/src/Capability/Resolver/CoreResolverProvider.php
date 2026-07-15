<?php

namespace VendorPatches202602\cweagans\Composer\Capability\Resolver;

use VendorPatches202602\cweagans\Composer\Resolver\Dependencies;
use VendorPatches202602\cweagans\Composer\Resolver\PatchesFile;
use VendorPatches202602\cweagans\Composer\Resolver\RootComposer;
class CoreResolverProvider extends BaseResolverProvider
{
    /**
     * @inheritDoc
     */
    public function getResolvers() : array
    {
        return [new RootComposer($this->composer, $this->io, $this->plugin), new PatchesFile($this->composer, $this->io, $this->plugin), new Dependencies($this->composer, $this->io, $this->plugin)];
    }
}
