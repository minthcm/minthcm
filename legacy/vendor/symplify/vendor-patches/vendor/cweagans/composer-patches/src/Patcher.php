<?php

namespace VendorPatches202602\cweagans\Composer;

use VendorPatches202602\Composer\Composer;
use VendorPatches202602\cweagans\Composer\Event\PluginEvent;
use VendorPatches202602\cweagans\Composer\Event\PluginEvents;
use VendorPatches202602\cweagans\Composer\Patcher\PatcherInterface;
use VendorPatches202602\Composer\IO\IOInterface;
use VendorPatches202602\cweagans\Composer\Capability\Patcher\PatcherProvider;
use UnexpectedValueException;
use Exception;
class Patcher
{
    /**
     * @var \Composer\Composer
     */
    protected $composer;
    /**
     * @var \Composer\IO\IOInterface
     */
    protected $io;
    /**
     * @var mixed[]
     */
    protected $disabledPatchers;
    public function __construct(Composer $composer, IOInterface $io, array $disabledPatchers)
    {
        $this->composer = $composer;
        $this->io = $io;
        $this->disabledPatchers = $disabledPatchers;
    }
    /**
     * Apply a patch using the available Patchers.
     *
     * @param Patch $patch
     *   The patch to apply.
     *
     * @param string $path
     *   The path to where the package was installed by Composer.
     *
     * @return bool
     *   true if the patch was applied successfully.
     */
    public function applyPatch(Patch $patch, string $path) : bool
    {
        foreach ($this->getPatchers() as $patcher) {
            $class = "\\" . \get_class($patcher);
            if (\in_array($class, $this->disabledPatchers, \true)) {
                $this->io->write('<info>  - Skipping patcher ' . $class . '</info>', \true, IOInterface::VERBOSE);
                continue;
            }
            $result = $patcher->apply($patch, $path);
            if ($result === \true) {
                return \true;
            }
        }
        return \false;
    }
    /**
     * Gather a list of all Patchers from all enabled Composer plugins.
     *
     * @return PatcherInterface[]
     *   A list of Patchers that are available.
     */
    protected function getPatchers() : array
    {
        static $patchers;
        if (!\is_null($patchers)) {
            return $patchers;
        }
        $patchers = [];
        $plugin_manager = $this->composer->getPluginManager();
        $capabilities = $plugin_manager->getPluginCapabilities(PatcherProvider::class, ['composer' => $this->composer, 'io' => $this->io]);
        foreach ($capabilities as $capability) {
            /** @var PatcherProvider $capability */
            $newPatchers = $capability->getPatchers();
            foreach ($newPatchers as $i => $patcher) {
                if (!$patcher instanceof PatcherInterface) {
                    throw new UnexpectedValueException('Plugin capability ' . \get_class($capability) . ' returned an invalid value.');
                }
                $usable = $patcher->canUse();
                $this->io->write(\get_class($patcher) . " usable: " . ($usable ? "yes" : "no"), \true, IOInterface::DEBUG);
                if (!$usable) {
                    unset($newPatchers[$i]);
                }
            }
            $patchers = \array_merge($patchers, $newPatchers);
        }
        $event = new PluginEvent(PluginEvents::POST_DISCOVER_PATCHERS, $patchers, $this->composer, $this->io);
        $this->composer->getEventDispatcher()->dispatch(PluginEvents::POST_DISCOVER_PATCHERS, $event);
        $patchers = $event->getCapabilities();
        if (\count($patchers) === 0) {
            throw new Exception('No patchers available.');
        }
        return $patchers;
    }
}
