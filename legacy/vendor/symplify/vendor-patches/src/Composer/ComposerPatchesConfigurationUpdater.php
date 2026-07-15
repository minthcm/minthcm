<?php

declare (strict_types=1);
namespace Symplify\VendorPatches\Composer;

use VendorPatches202602\Entropy\Attributes\RelatedTest;
use VendorPatches202602\Entropy\Utils\FileSystem;
use Symplify\VendorPatches\Tests\Composer\ComposerPatchesConfigurationUpdater\ComposerPatchesConfigurationUpdaterTest;
use Symplify\VendorPatches\Utils\ParametersMerger;
final class ComposerPatchesConfigurationUpdater
{
    /**
     * @readonly
     * @var \Symplify\VendorPatches\Utils\ParametersMerger
     */
    private $parametersMerger;
    public function __construct(ParametersMerger $parametersMerger)
    {
        $this->parametersMerger = $parametersMerger;
    }
    /**
     * @api
     * @param mixed[] $composerExtraPatches
     * @return mixed[]
     */
    public function updatePatchesFileJson(string $patchesFilePath, array $composerExtraPatches) : array
    {
        $patchesFileJson = FileSystem::loadFileToJson($patchesFilePath);
        return $this->parametersMerger->merge($patchesFileJson, ['patches' => $composerExtraPatches]);
    }
    /**
     * @api
     * @param mixed[] $composerExtraPatches
     * @return mixed[]
     */
    public function updateComposerJson(string $composerJsonFilePath, array $composerExtraPatches) : array
    {
        $composerJson = FileSystem::loadFileToJson($composerJsonFilePath);
        // merge "extra" section - deep merge is needed, so original patches are included
        if (isset($composerJson['extra'])) {
            $composerJson['extra'] = $this->parametersMerger->merge($composerJson['extra'], ['patches' => $composerExtraPatches]);
            return $composerJson;
        }
        // new, put the patches last
        $composerJson['extra'] = ['patches' => $composerExtraPatches];
        return $composerJson;
    }
    /**
     * @param mixed[] $composerExtraPatches
     */
    public function updateComposerJsonAndPrint(string $composerJsonFilePath, array $composerExtraPatches) : void
    {
        $composerJson = $this->updateComposerJson($composerJsonFilePath, $composerExtraPatches);
        // force JSON_UNESCAPED_SLASHES to create valid composer.json files
        $jsonContents = \json_encode($composerJson, \JSON_PRETTY_PRINT | \JSON_UNESCAPED_SLASHES) . \PHP_EOL;
        // print composer.json
        FileSystem::write($composerJsonFilePath, $jsonContents);
    }
    /**
     * @param mixed[] $composerExtraPatches
     */
    public function updatePatchesFileJsonAndPrint(string $patchesFilePath, array $composerExtraPatches) : void
    {
        $patchesFileJson = $this->updatePatchesFileJson($patchesFilePath, $composerExtraPatches);
        FileSystem::saveJsonToFile($patchesFileJson, $patchesFilePath);
    }
}
