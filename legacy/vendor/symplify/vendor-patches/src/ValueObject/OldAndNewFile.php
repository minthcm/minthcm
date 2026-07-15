<?php

declare (strict_types=1);
namespace Symplify\VendorPatches\ValueObject;

use VendorPatches202602\Entropy\Utils\FileSystem;
final class OldAndNewFile
{
    /**
     * @readonly
     * @var string
     */
    private $oldFilePath;
    /**
     * @readonly
     * @var string
     */
    private $newFilePath;
    /**
     * @readonly
     * @var string
     */
    private $packageName;
    public function __construct(string $oldFilePath, string $newFilePath, string $packageName)
    {
        $this->oldFilePath = $oldFilePath;
        $this->newFilePath = $newFilePath;
        $this->packageName = $packageName;
    }
    public function getOldFilePath() : string
    {
        return $this->oldFilePath;
    }
    public function getOldFileContents() : string
    {
        return FileSystem::read($this->oldFilePath);
    }
    public function getNewFileContents() : string
    {
        return FileSystem::read($this->newFilePath);
    }
    public function getNewFilePath() : string
    {
        return $this->newFilePath;
    }
    public function areContentsIdentical() : bool
    {
        $newFileContents = FileSystem::read($this->newFilePath);
        $oldFileContents = FileSystem::read($this->oldFilePath);
        return $newFileContents === $oldFileContents;
    }
    public function getPackageName() : string
    {
        return $this->packageName;
    }
}
