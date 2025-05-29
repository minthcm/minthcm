<?php
namespace MintHCM\MintCLI\Services;

class VardefsService
{
    protected $dictionary;
    protected $relationships;
    protected $tables;
    const CACHE_FILE_MODULE_PATH = 'legacy/cache/modules';
    const CACHE_FILE_RELATIONSHIP_PATH = 'legacy/cache/Relationships/relationships.cache.php';
    public function __construct()
    {
        require static::CACHE_FILE_RELATIONSHIP_PATH;
        $this->relationships = $relationships;

        $files = $this->getPhpFiles(static::CACHE_FILE_MODULE_PATH);
        foreach ($files as $file) {
            require $file;
        }
        $this->dictionary = $GLOBALS["dictionary"];
        foreach ($this->dictionary as $module) {
            $this->tables[$module['table']] = $module['fields'];
        }
        foreach ($this->relationships as $relationship) {
            if(isset($relationship['table'])){
                $this->tables[$relationship['table']] = $relationship['fields'];
            }
        }
    }

    public function getTablesKeys()
    {
        return array_keys($this->tables);
    }

    public function getFields($table): array
    {
        return $this->tables[$table] ?? [];
    }
    
    function getPhpFiles($dir)
    {
        $files = [];

        if ($handle = opendir($dir)) {
            while (false !== ($entry = readdir($handle))) {
                if ("." != $entry && ".." != $entry) {
                    $path = $dir . DIRECTORY_SEPARATOR . $entry;

                    if (is_dir($path)) {
                        $files = array_merge($files, $this->getPhpFiles($path));
                    } elseif (pathinfo($path, PATHINFO_EXTENSION) === 'php' && strpos($path, 'vardefs') !== false) {
                        $files[] = $path;
                    }
                }
            }
            closedir($handle);
        }

        return $files;
    }
}
