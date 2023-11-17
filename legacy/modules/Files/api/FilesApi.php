<?php


class FilesApi
{
    public function getFiles($args)
    {
        $files = [];
        $bean = BeanFactory::getBean($args['module_name'], $args['record'] ?? null);
        if ($bean && !empty($bean->id) && $bean->load_relationship('files')) {
            foreach ($bean->files->getBeans() as $file) {
                $files[] = [
                    'id' => $file->id,
                    'name' => $file->filename,
                    'size' => filesize("upload://{$file->id}"),
                    'accepted' => true,
                ];
            }
        }
        return $files;
    }

    public function removeFile($args)
    {
        $file = BeanFactory::getBean('Files', $args['record'] ?? null);
        if ($file && !empty($file->id)) {
            $file->deleteAttachment();
            $file->mark_deleted($file->id);
        }
    }
}
