<?php

declare (strict_types=1);
namespace Symplify\VendorPatches\Command;

use VendorPatches202602\Entropy\Console\Contract\CommandInterface;
use VendorPatches202602\Entropy\Console\Enum\ExitCode;
use VendorPatches202602\Entropy\Console\Output\OutputPrinter;
use VendorPatches202602\Entropy\Utils\FileSystem;
use Symplify\VendorPatches\Composer\ComposerPatchesConfigurationUpdater;
use Symplify\VendorPatches\Console\GenerateCommandReporter;
use Symplify\VendorPatches\Differ\PatchDiffer;
use Symplify\VendorPatches\Finder\OldToNewFilesFinder;
use Symplify\VendorPatches\PatchFileFactory;
use Symplify\VendorPatches\VendorDirProvider;
final class GenerateCommand implements CommandInterface
{
    /**
     * @readonly
     * @var \Symplify\VendorPatches\Finder\OldToNewFilesFinder
     */
    private $oldToNewFilesFinder;
    /**
     * @readonly
     * @var \Symplify\VendorPatches\Differ\PatchDiffer
     */
    private $patchDiffer;
    /**
     * @readonly
     * @var \Symplify\VendorPatches\Composer\ComposerPatchesConfigurationUpdater
     */
    private $composerPatchesConfigurationUpdater;
    /**
     * @readonly
     * @var \Symplify\VendorPatches\PatchFileFactory
     */
    private $patchFileFactory;
    /**
     * @readonly
     * @var \Symplify\VendorPatches\Console\GenerateCommandReporter
     */
    private $generateCommandReporter;
    /**
     * @readonly
     * @var \Entropy\Console\Output\OutputPrinter
     */
    private $outputPrinter;
    public function __construct(OldToNewFilesFinder $oldToNewFilesFinder, PatchDiffer $patchDiffer, ComposerPatchesConfigurationUpdater $composerPatchesConfigurationUpdater, PatchFileFactory $patchFileFactory, GenerateCommandReporter $generateCommandReporter, OutputPrinter $outputPrinter)
    {
        $this->oldToNewFilesFinder = $oldToNewFilesFinder;
        $this->patchDiffer = $patchDiffer;
        $this->composerPatchesConfigurationUpdater = $composerPatchesConfigurationUpdater;
        $this->patchFileFactory = $patchFileFactory;
        $this->generateCommandReporter = $generateCommandReporter;
        $this->outputPrinter = $outputPrinter;
    }
    /**
     * @param string|null $patchesFile Path to the patches file, relative to project root
     * @param string|null $patchesOutput Folder to output the patches to.
     *
     * @return \Entropy\Console\Enum\ExitCode::*
     */
    public function run(?string $patchesFile = null, ?string $patchesOutput = null) : int
    {
        $projectVendorDirectory = $this->resolveProjectVendorDirectory();
        $oldAndNewFiles = $this->oldToNewFilesFinder->find($projectVendorDirectory);
        $composerExtraPatches = [];
        $addedPatchFilesByPackageName = [];
        if (\is_string($patchesOutput)) {
            $this->patchFileFactory->setOutputFolder($patchesOutput);
        }
        foreach ($oldAndNewFiles as $oldAndNewFile) {
            if ($oldAndNewFile->areContentsIdentical()) {
                $this->generateCommandReporter->reportIdenticalNewAndOldFile($oldAndNewFile);
                continue;
            }
            // write into patches file
            $patchFileRelativePath = $this->patchFileFactory->createPatchFilePath($oldAndNewFile, $projectVendorDirectory);
            $composerExtraPatches[$oldAndNewFile->getPackageName()][] = $patchFileRelativePath;
            $patchFileAbsolutePath = \dirname($projectVendorDirectory) . \DIRECTORY_SEPARATOR . $patchFileRelativePath;
            // dump the patch
            $patchDiff = $this->patchDiffer->diff($oldAndNewFile);
            if (\is_file($patchFileAbsolutePath)) {
                $message = \sprintf('File "%s" was updated', $patchFileRelativePath);
                $this->outputPrinter->yellow($message);
            } else {
                $message = \sprintf('File "%s" was created', $patchFileRelativePath);
                $this->outputPrinter->yellow($message);
            }
            FileSystem::write($patchFileAbsolutePath, $patchDiff);
            $addedPatchFilesByPackageName[$oldAndNewFile->getPackageName()][] = $patchFileRelativePath;
        }
        if ($composerExtraPatches !== []) {
            if (\is_string($patchesFile)) {
                // remove starting '/' if present
                $patchesFile = \ltrim($patchesFile, '/\\');
                $absolutePatchesFilePath = \getcwd() . '/' . $patchesFile;
                $this->composerPatchesConfigurationUpdater->updatePatchesFileJsonAndPrint($absolutePatchesFilePath, $composerExtraPatches);
            } else {
                $this->composerPatchesConfigurationUpdater->updateComposerJsonAndPrint(\getcwd() . '/composer.json', $composerExtraPatches);
            }
        }
        if ($addedPatchFilesByPackageName !== []) {
            $message = \sprintf('Great! %d new patch files added', \count($addedPatchFilesByPackageName));
            $this->outputPrinter->greenBackground($message);
        } else {
            $this->outputPrinter->greenBackground('No new patches were added');
        }
        return ExitCode::SUCCESS;
    }
    public function getName() : string
    {
        return 'generate';
    }
    public function getDescription() : string
    {
        return 'Generate patches from /vendor directory';
    }
    private function resolveProjectVendorDirectory() : string
    {
        $projectVendorDirectory = \getcwd() . '/vendor';
        if (\file_exists($projectVendorDirectory)) {
            return $projectVendorDirectory;
        }
        return VendorDirProvider::provide();
    }
}
