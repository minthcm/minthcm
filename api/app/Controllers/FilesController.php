<?php

namespace MintHCM\Api\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response;

class FilesController
{
    public function saveFile(Request $request, Response $response, array $args): Response
    {
        global $current_user;
        try {
            $files = $request->getUploadedFiles();
            $attr = $request->getAttributes();
            $module = $attr['module_name'] ?? '';
            $record_id = $attr['record_id'] ?? '';
            if (!empty($record_id) && !empty($module) && !empty($files) && !empty($files['file'])) {
                chdir('../legacy');
                require_once('include/upload_file.php');
                $fileManager = new \UploadFile('file');
                if ($fileManager->confirm_upload()) {
                    $file = \BeanFactory::newBean('Files');
                    $file->document_name = $fileManager->get_stored_file_name();
                    $file->parent_type = $module;
                    $file->parent_id = $record_id;
                    $file->filename = $fileManager->get_stored_file_name();
                    $file->file_mime_type = $fileManager->get_mime_type();
                    $file->assigned_user_id = $current_user->id;
                    if ($file->save(false) && $fileManager->final_move($file->id)) {
                        $result = [
                            'id' => $file->id,
                            'canDelete' => $file->ACLAccess('delete'),
                        ];
                    }
                }
            }
            chdir('../api');
            return $this->successResponse($response, $result);
        } catch (\Exception $e) {
            return $this->errorResponse($response, $e);
        }
    }

    public function deleteFile(Request $request, Response $response, array $args): Response
    {
        try {
            $attr = $request->getAttributes();
            chdir('../legacy');
            $file = \BeanFactory::getBean('Files', $attr['file_id'] ?? null);
            if ($file && !empty($file->id)) {
                $file->deleteAttachment();
                $file->mark_deleted($file->id);
            }
            chdir('../api');
            return $this->successResponse($response, true);
        } catch (\Exception $e) {
            return $this->errorResponse($response, $e);
        }
    }

    public function getFiles(Request $request, Response $response, array $args): Response
    {
        try {
            $files = [];
            chdir('../legacy');
            $bean = \BeanFactory::getBean($args['module_name'], $args['record_id'] ?? null);
            if ($bean && !empty($bean->id) && $bean->load_relationship('files')) {
                foreach ($bean->files->getBeans() as $file) {
                    if (!$file->ACLAccess('detail')) {
                        continue;
                    }

                    $can_delete = $file->ACLAccess('delete');

                    $files[] = [
                        'id' => $file->id,
                        'name' => $file->filename,
                        'size' => filesize("upload://{$file->id}"),
                        'accepted' => true,
                        'canDelete' => $can_delete,
                    ];
                }
            }
            chdir('../api');
            return $this->successResponse($response, $files);
        } catch (\Exception $e) {
            return $this->errorResponse($response, $e);
        }
    }

    private function errorResponse(Response $response, \Exception $e): Response
    {
        $response->getBody()->write(json_encode(['message' => 'Internal Server Error', 'error' => $e->getMessage()]));
        return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
    }

    private function successResponse(Response $response, $data): Response
    {
        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
