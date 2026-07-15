<?php

declare (strict_types=1);
namespace Symplify\VendorPatches\Console;

use VendorPatches202602\Entropy\Console\Output\OutputPrinter;
use Symplify\VendorPatches\ValueObject\OldAndNewFile;
final class GenerateCommandReporter
{
    /**
     * @readonly
     * @var \Entropy\Console\Output\OutputPrinter
     */
    private $outputPrinter;
    public function __construct(OutputPrinter $outputPrinter)
    {
        $this->outputPrinter = $outputPrinter;
    }
    public function reportIdenticalNewAndOldFile(OldAndNewFile $oldAndNewFile) : void
    {
        $message = \sprintf('Files "%s" and "%s" have the same content. Did you forgot to change it?', $oldAndNewFile->getOldFilePath(), $oldAndNewFile->getNewFilePath());
        $this->outputPrinter->orangeBackground($message);
    }
}
