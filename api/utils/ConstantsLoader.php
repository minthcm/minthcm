<?php

namespace MintHCM\Utils;

class ConstantsLoader
{
    private const NORMAL_PATH = 'constants/';
    private const CUSTOM_PATH = 'custom/constants/';

    public static function getConstants(string $name): array | false {
        $file_path = self::NORMAL_PATH . $name . '.php';
        $custom_files_path = self::CUSTOM_PATH . $name .'/';

        if(file_exists($file_path)) {
            $include_content = include $file_path;
            $custom_files = scandir($custom_files_path);
            if(!empty($custom_files)) {
                foreach($custom_files as $custom_file) {
                    if(substr($custom_file, -4) !== '.php') {
                        continue;
                    }

                    $custom_content = include $custom_files_path . $custom_file;
                    $include_content = array_merge($include_content, $custom_content);
                }
            }
        } else {
            return false;
        }
        return $include_content;
    }
}