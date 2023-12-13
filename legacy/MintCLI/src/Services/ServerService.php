<?php

namespace MintHCM\MintCLI\Services;

class ServerService
{

    public function getHostName()
    {
        exec("hostname --fqdn", $result);
        return $result[0];
    }

    // TODO this is a very strong assumption - there is no way to read DOCUMENT_ROOT in the CLI
    public function getScriptLocation()
    {
        return dirname(__DIR__, 4);
    }

    public function getDirectorySeparator($path = null)
    {
        if(empty($path)){
            return DIRECTORY_SEPARATOR;
        } else {
            if(str_contains($path, DIRECTORY_SEPARATOR)){
                return DIRECTORY_SEPARATOR;    
            } else {
                //it means that we are probably in windows so we need to change directory separator
                return '/';
            }
        }
    }

    protected function explodePath($path)
    {
        return explode($this->getDirectorySeparator($path), $path);
    }

    public function getScriptDirectory()
    {
        $pathParts = $this->explodePath($this->getScriptLocation());
        return array_pop($pathParts);
    }

    public function getSystemBasePath($applicationRootPath)
    {
        $applicationRootParts = $this->explodePath($applicationRootPath);
        $lastDirectory = '';
        do {
            $lastDirectory = array_pop($applicationRootParts);
        } while (empty($lastDirectory) && !empty($applicationRootParts));
        return $lastDirectory == $this->getScriptDirectory() ? '/' : '/' . $this->getScriptDirectory();
    }
}
