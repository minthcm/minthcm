<?php

namespace VendorPatches202602\cweagans\Composer\Event;

use VendorPatches202602\Composer\Composer;
use VendorPatches202602\Composer\EventDispatcher\Event;
use VendorPatches202602\Composer\IO\IOInterface;
class PluginEvent extends Event
{
    /**
     * @var array $capabilities
     */
    protected $capabilities;
    /**
     * @var Composer $composer
     */
    protected $composer;
    /**
     * @var IOInterface $io
     */
    protected $io;
    /**
     * Constructs a PluginEvent object.
     *
     * @param string $eventName
     * @param array $capabilities
     */
    public function __construct(string $eventName, array $capabilities, Composer $composer, IOInterface $io)
    {
        parent::__construct($eventName);
        $this->capabilities = $capabilities;
        $this->composer = $composer;
        $this->io = $io;
    }
    /**
     * Get the list of capabilities that were discovered.
     *
     * @return array
     */
    public function getCapabilities() : array
    {
        return $this->capabilities;
    }
    /**
     * Replace the list of capabilities that were discovered.
     *
     * You should take care to only include the correct type of capability classes here. e.g. If you're responding to
     * the POST_DISCOVER_DOWNLOADERS event, you should only include implementations of DownloaderInterface.
     *
     * @param array $capabilities
     *   A complete list of capability objects.
     */
    public function setCapabilities(array $capabilities) : void
    {
        $this->capabilities = $capabilities;
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
}
