<?php
namespace MintHCM\MintCLI\Installer;

class FileManager
{

    public function createInstanceSubDirectories()
    {
        // config.php
        make_writable('./config.php');
        // custom dir
        make_writable('./custom');
        // modules dir
        recursive_make_writable('./modules');
        // public dir
        recursive_make_writable('./public');

        if (file_exists('custom/modules/Home/dashlets.php')) {
            unlink('custom/modules/Home/dashlets.php');
        }
        //Check if the folder is in place
        if (!file_exists('custom/modules/Home')) {
            sugar_mkdir('custom/modules/Home', 0775);
        }
        //Check if the folder is in place
        if (!file_exists('custom/include')) {
            sugar_mkdir('custom/include', 0775);
        }
    }

    public function createCacheDirectories()
    {
        create_writable_dir(sugar_cached('custom_fields'));
        create_writable_dir(sugar_cached('dyn_lay'));
        create_writable_dir(sugar_cached('images'));
        create_writable_dir(sugar_cached('modules'));
        create_writable_dir(sugar_cached('layout'));
        create_writable_dir(sugar_cached('pdf'));
        create_writable_dir(sugar_cached('upload/import'));
        create_writable_dir(sugar_cached('xml'));
        create_writable_dir(sugar_cached('include/javascript'));
        recursive_make_writable(sugar_cached('modules'));
    }
}