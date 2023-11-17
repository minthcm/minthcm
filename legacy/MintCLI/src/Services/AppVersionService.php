<?php

namespace MintHCM\MintCLI\Services;

use MintHCM\MintCLI\SystemRequirements\SystemRequirements;

class AppVersionService
{
    private $response = [
        'correctVersions' => true,
        'message' => [],
    ];

    public function verifyAppVersions(bool $checkFrontend = false)
    {
        $sysRequirements = SystemRequirements::$sysRequirements;

        if($checkFrontend){
            $sysRequirements = array_merge($sysRequirements, SystemRequirements::$frontendRequirements);
        }

        foreach ($sysRequirements as $appName => $appInfo) {
            $checkStatus = $this->checkAppVersion($appName, $appInfo['version'], $appInfo['command']);
            if (!$checkStatus) {
                $this->response['correctVersions'] = false;
                break;
            }
        }
    
        return $this->response;
    }
    
    private function checkAppVersion($appName, $requiredVersion, $command)
    {
        $version = shell_exec($command);
        $version = $this->extractNumericVersion(trim($version));
    
        if ($version < $requiredVersion) {
            $this->response['correctVersions'] = false;
            $this->response['messages'][] = "$appName version is not sufficient. Required: $requiredVersion";
            return false;
        } 
    
        return true;
    }
    
    private function extractNumericVersion($version)
    {
        return floatval(substr($version, 1));
    }
}