<?php

namespace MintHCM\MintCLI\Services;

class ServerService
{
    public function getHostName()
    {
        exec("hostname --fqdn", $result);
        return $result[0];
    }

    // TODO to jest bardzo silne założenie - w CLI nie ma sposobu na odczytanie DOCUMENT_ROTa
    public function getScriptLocation()
    {
        return dirname(__DIR__, 4);
    }

    public function getScriptDirectory()
    {
        $pathParts = explode(DIRECTORY_SEPARATOR, $this->getScriptLocation());
        return array_pop($pathParts);
    }

    public function getSystemBasePath($applicationRootPath)
    {
        $applicationRootParts = explode(DIRECTORY_SEPARATOR, $applicationRootPath);
        $lastDirectory = '';
        do {
            $lastDirectory = array_pop($applicationRootParts);
        } while (empty($lastDirectory) && !empty($applicationRootParts));
        return $lastDirectory == $this->getScriptDirectory() ? '/' : '/' . $this->getScriptDirectory();
    }
}
