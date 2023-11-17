<?php
namespace Api\V8\Controller;

use Api\V8\Param\ImagePreviewParams;
use Api\V8\Param\UploadFileParams;
use Api\V8\Service\FileService;
use Slim\Http\Request;
use Slim\Http\Response;

class FileController extends BaseController
{
    private $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function uploadFile(Request $request, Response $response, array $args, UploadFileParams $params)
    {
        try {
            $jsonResponse = $this->fileService->uploadFile($params, $request);
            return $this->generateResponse($response, $jsonResponse, 201);
        } catch (\Exception $exception) {
            return $this->generateErrorResponse($response, $exception, 400);
        }
    }

    public function imagePreview(Request $request, Response $response, array $args, ImagePreviewParams $params)
    {
        try {
            $this->fileService->imagePreview($params, $request);
        } catch (\Exception $exception) {
            return $this->generateErrorResponse($response, $exception, 400);
        }
    }
}
