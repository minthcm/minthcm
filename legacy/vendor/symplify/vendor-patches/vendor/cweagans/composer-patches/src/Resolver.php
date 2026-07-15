<?php

namespace VendorPatches202602\cweagans\Composer;

use VendorPatches202602\Composer\Composer;
use VendorPatches202602\Composer\IO\IOInterface;
use VendorPatches202602\Composer\IO\NullIO;
use VendorPatches202602\cweagans\Composer\Capability\Resolver\ResolverProvider;
use VendorPatches202602\cweagans\Composer\Event\PluginEvent;
use VendorPatches202602\cweagans\Composer\Event\PluginEvents;
use VendorPatches202602\cweagans\Composer\Resolver\ResolverInterface;
use LogicException;
use UnexpectedValueException;
class Resolver
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
     * @var \Composer\IO\IOInterface
     */
    protected $inactive_io;
    /**
     * @var mixed[]
     */
    protected $disabledResolvers = [];
    public function __construct(Composer $composer, IOInterface $io, array $disabledResolvers)
    {
        $this->composer = $composer;
        $this->io = $io;
        $this->disabledResolvers = $disabledResolvers;
    }
    /**
     * Gather patches that need to be applied to the current set of packages.
     *
     * Note that this work is done unconditionally if this plugin is enabled,
     * even if patching is disabled in any way. The point where patches are applied
     * is where the work will be skipped. It's done this way to ensure that
     * patching can be disabled temporarily in a way that doesn't affect the
     * contents of composer.lock.
     */
    public function loadFromResolvers() : PatchCollection
    {
        $patchCollection = new PatchCollection();
        // Let each resolver discover patches and add them to the PatchCollection.
        /** @var ResolverInterface $resolver */
        foreach ($this->getPatchResolvers() as $resolver) {
            $class = "\\" . \get_class($resolver);
            if (\in_array($class, $this->disabledResolvers, \true)) {
                $this->io->write('<info>  - Skipping resolver ' . $class . '</info>', \true, IOInterface::VERBOSE);
                continue;
            }
            $resolver->resolve($patchCollection);
        }
        return $patchCollection;
    }
    public function silenceOutput() : void
    {
        $this->inactive_io = $this->io;
        $this->io = new NullIO();
    }
    public function unsilenceOutput() : void
    {
        $this->io = $this->inactive_io;
    }
    /**
     * Gather a list of all patch resolvers from all enabled Composer plugins.
     *
     * @return ResolverInterface[]
     *   A list of PatchResolvers to be run.
     */
    protected function getPatchResolvers() : array
    {
        $resolvers = [];
        $plugin_manager = $this->composer->getPluginManager();
        $capabilities = $plugin_manager->getPluginCapabilities(ResolverProvider::class, ['composer' => $this->composer, 'io' => $this->io]);
        foreach ($capabilities as $capability) {
            /** @var ResolverProvider $capability */
            $newResolvers = $capability->getResolvers();
            foreach ($newResolvers as $resolver) {
                if (!$resolver instanceof ResolverInterface) {
                    throw new UnexpectedValueException('Plugin capability ' . \get_class($capability) . ' returned an invalid value.');
                }
            }
            $resolvers = \array_merge($resolvers, $newResolvers);
        }
        $event = new PluginEvent(PluginEvents::POST_DISCOVER_RESOLVERS, $resolvers, $this->composer, $this->io);
        $this->composer->getEventDispatcher()->dispatch(PluginEvents::POST_DISCOVER_RESOLVERS, $event);
        $resolvers = $event->getCapabilities();
        return $resolvers;
    }
}
