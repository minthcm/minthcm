<?php

use MintHCM\MintCLI\Services\DatabaseService;

class InstallService
{
    const STATUSFILE = __DIR__ . '/assets/status.json';

    public function clearStatusJson()
    {
        $statusFile = __DIR__ . '/assets/status.json';
        if (file_exists($statusFile)) {
            $file = fopen($statusFile, 'w');
            fclose($file);
        }
    }

    /**
     * Adds another step to the status.json file in the format:
     * $step_number (int): $message (string) 
     */
    public function setMintInstallStatus(int $step, string $message): void
    {
        $statusFile = self::STATUSFILE;
        $statusData = [];

        if (file_exists($statusFile)) {
            $existingData = json_decode(file_get_contents($statusFile), true);
            if (is_array($existingData)) {
                $statusData = $existingData;
            }
        }

        $statusData[$step] = $message;
        $encodedStatusData = json_encode($statusData, JSON_PRETTY_PRINT);
        file_put_contents($statusFile, $encodedStatusData);
    }

    public function readMintInstallStatus(): array
    {
        $statusFile = self::STATUSFILE;

        if (file_exists($statusFile)) {
            $existingData = json_decode(file_get_contents($statusFile), true);
            if (is_array($existingData)) {
                $latestStepKey = max(array_keys($existingData));
                $latestStepValue = $existingData[$latestStepKey];

                return ["step" => $latestStepKey, "message" => $latestStepValue];
            }
        }
        return ["step" => null, "message" => ''];
    }
}
