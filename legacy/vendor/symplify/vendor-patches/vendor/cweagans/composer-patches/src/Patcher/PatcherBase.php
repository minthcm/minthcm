<?php

namespace VendorPatches202602\cweagans\Composer\Patcher;

use VendorPatches202602\Composer\Composer;
use VendorPatches202602\Composer\IO\IOInterface;
use VendorPatches202602\Composer\Plugin\PluginInterface;
use VendorPatches202602\Composer\Util\ProcessExecutor;
use VendorPatches202602\cweagans\Composer\Patch;
use VendorPatches202602\Symfony\Component\Process\Process;
abstract class PatcherBase implements PatcherInterface
{
    /**
     * The main Composer object.
     *
     * @var Composer
     */
    protected $composer;
    /**
     * An array of operations that will be executed during this composer execution.
     *
     * @var IOInterface
     */
    protected $io;
    /**
     * An instance of the main plugin class.
     *
     * @var PluginInterface
     */
    protected $plugin;
    /**
     * If set, the Patcher object will use this path instead of a $PATH lookup to execute the appropriate tool.
     *
     * @var string
     */
    public $toolPathOverride;
    /**
     * The tool executable that the Patcher object should use (for internal use).
     *
     * @var string
     */
    protected $tool;
    /**
     * Executes commands.
     *
     * @var ProcessExecutor $executor
     */
    protected $executor;
    /**
     * {@inheritDoc}
     */
    public function __construct(Composer $composer, IOInterface $io, PluginInterface $plugin)
    {
        $this->composer = $composer;
        $this->io = $io;
        $this->plugin = $plugin;
        $this->executor = new ProcessExecutor($io);
    }
    /**
     * Return the tool to run when applying patches (when applicable).
     *
     * @return string
     */
    protected function patchTool() : string
    {
        if (isset($this->toolPathOverride) && !empty($this->toolPathOverride)) {
            return $this->toolPathOverride;
        }
        return $this->tool;
    }
    /**
     * Executes a shell command with escaping.
     *
     * @param string $cmd
     * @return bool
     */
    protected function executeCommand($cmd)
    {
        // Shell-escape all arguments except the command.
        $args = \func_get_args();
        foreach ($args as $index => $arg) {
            if ($index !== 0 && !\is_int($arg)) {
                $args[$index] = \escapeshellarg($arg);
            }
        }
        // And replace the arguments.
        $command = \call_user_func_array('VendorPatches202602\\sprintf', $args);
        $output = '';
        if ($this->io->isVerbose()) {
            $this->io->write('<comment>' . $command . '</comment>');
            $io = $this->io;
            $output = function ($type, $data) use($io) {
                if ($type === Process::ERR) {
                    $io->write('<error>' . $data . '</error>');
                } else {
                    $io->write('<comment>' . $data . '</comment>');
                }
            };
        }
        return $this->executor->execute($command, $output) === 0;
    }
    /**
     * @inheritDoc
     */
    public abstract function apply(Patch $patch, string $path) : bool;
    /**
     * @inheritDoc
     */
    public abstract function canUse() : bool;
}
