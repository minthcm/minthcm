<?php

/**
 * @file
 * Dispatch events when patches are applied.
 */
namespace VendorPatches202602\cweagans\Composer\Event;

use VendorPatches202602\Composer\Composer;
use VendorPatches202602\Composer\EventDispatcher\Event;
use VendorPatches202602\Composer\IO\IOInterface;
use VendorPatches202602\cweagans\Composer\Patch;
use Exception;
class PatchEvent extends Event
{
    /**
     * @var Patch $patch
     */
    protected $patch;
    /**
     * @var Composer $composer
     */
    protected $composer;
    /**
     * @var IOInterface $io
     */
    protected $io;
    /**
     * @var ?Exception $error
     */
    protected $error;
    /**
     * Constructs a PatchEvent object.
     *
     * @param string $eventName
     * @param Patch $patch
     */
    public function __construct(string $eventName, Patch $patch, Composer $composer, IOInterface $io, ?Exception $error = null)
    {
        parent::__construct($eventName);
        $this->patch = $patch;
        $this->composer = $composer;
        $this->io = $io;
        $this->error = $error;
    }
    /**
     * Returns the Patch object.
     *
     * @return Patch
     */
    public function getPatch() : Patch
    {
        return $this->patch;
    }
    /**
     * Returns the Composer object.
     *
     * @return Composer
     */
    public function getComposer() : Composer
    {
        return $this->composer;
    }
    /**
     * Returns the IOInterface.
     *
     * @return IOInterface
     */
    public function getIO() : IOInterface
    {
        return $this->io;
    }
    /**
     * Returns the exception about to be thrown when a patch cannot be applied.
     *
     * @return Exception
     */
    public function getError() : ?Exception
    {
        return $this->error;
    }
}
