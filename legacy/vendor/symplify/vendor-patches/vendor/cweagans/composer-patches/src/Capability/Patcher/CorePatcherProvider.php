<?php

namespace VendorPatches202602\cweagans\Composer\Capability\Patcher;

use VendorPatches202602\cweagans\Composer\Patcher\FreeformPatcher;
use VendorPatches202602\cweagans\Composer\Patcher\GitPatcher;
use VendorPatches202602\cweagans\Composer\Patcher\GitInitPatcher;
class CorePatcherProvider extends BasePatcherProvider
{
    /**
     * @inheritDoc
     */
    public function getPatchers() : array
    {
        return [new GitPatcher($this->composer, $this->io, $this->plugin), new GitInitPatcher($this->composer, $this->io, $this->plugin), new FreeformPatcher($this->composer, $this->io, $this->plugin)];
    }
}
