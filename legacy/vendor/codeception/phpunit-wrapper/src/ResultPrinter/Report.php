<?php
namespace Codeception\PHPUnit\ResultPrinter;

use Codeception\Lib\Console\Output;
use Codeception\PHPUnit\ConsolePrinter;
use Codeception\PHPUnit\ResultPrinter;
use Codeception\Test\Descriptor;

class Report extends ResultPrinter implements ConsolePrinter
{
    /**
     * @param \PHPUnit\Framework\Test $test
     * @param float $time
     */
    public function endTest(\PHPUnit\Framework\Test $test, float $time) : void
    {
        $name = Descriptor::getTestAsString($test);
        $success = ($this->testStatus == \PHPUnit\Runner\BaseTestRunner::STATUS_PASSED);
        if ($success) {
            $this->successful++;
        }

        if ($this->testStatus == \PHPUnit\Runner\BaseTestRunner::STATUS_FAILURE) {
            $status = "\033[41;37mFAIL\033[0m";
        } elseif ($this->testStatus == \PHPUnit\Runner\BaseTestRunner::STATUS_SKIPPED) {
            $status = 'Skipped';
        } elseif ($this->testStatus == \PHPUnit\Runner\BaseTestRunner::STATUS_INCOMPLETE) {
            $status = 'Incomplete';
        } elseif ($this->testStatus == \PHPUnit\Runner\BaseTestRunner::STATUS_RISKY) {
            $status = 'Useless';
        } elseif ($this->testStatus == \PHPUnit\Runner\BaseTestRunner::STATUS_ERROR) {
            $status = 'ERROR';
        } else {
            $status = 'Ok';
        }

        if (strlen($name) > 75) {
            $name = substr($name, 0, 70);
        }
        $line = $name . str_repeat('.', 75 - strlen($name));
        $line .= $status;

        $this->write($line . "\n");
    }

    protected function endRun() : void
    {
    }

    public function printResult(\PHPUnit\Framework\TestResult $result): void
    {
        $this->write("\nCodeception Results\n");
        $this->write(sprintf(
                "Successful: %d. Failed: %d. Incomplete: %d. Skipped: %d. Useless: %d",
                $this->successful,
                $this->failed,
                $this->incomplete,
                $this->skipped,
                $this->risky
            ) . "\n");
    }

    public function write(string $buffer) : void
    {
        parent::write($buffer);
    }
}
