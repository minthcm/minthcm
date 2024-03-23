<?php
namespace Api\V8\Service;

use Api\V8\BeanDecorator\BeanManager;
use Api\V8\JsonApi\Helper\AttributeObjectHelper;
use Api\V8\JsonApi\Helper\RelationshipObjectHelper;
use Api\V8\JsonApi\Response\DataResponse;
use Api\V8\JsonApi\Response\DocumentResponse;
use Api\V8\Param\ImagePreviewParams;
use Api\V8\Param\UploadFileParams;
use Slim\Http\Request;
use UploadFile;

class FileService
{
    protected $beanManager;
    protected $attributeHelper;
    protected $relationshipHelper;

    public function __construct(
        BeanManager $beanManager,
        AttributeObjectHelper $attributeHelper,
        RelationshipObjectHelper $relationshipHelper
    ) {
        $this->beanManager = $beanManager;
        $this->attributeHelper = $attributeHelper;
        $this->relationshipHelper = $relationshipHelper;
    }

    public function uploadFile(UploadFileParams $params, Request $request)
    {
        $response = new DocumentResponse();
        $id = $params->getId();
        $module_name = $params->getModuleName();

        require_once 'include/upload_file.php';

        $file = new UploadFile('file');
        if ($file->confirm_upload()) {
            $bean = $this->beanManager->getBeanSafe($module_name, $id);

            if (in_array($module_name, ['Users', 'Employees'])) {
                $bean->photo = $file->get_stored_file_name();
                $id .= '_photo';
            } elseif ($module_name === 'Rooms') {
                $bean->room_plan = $file->get_stored_file_name();
                $id .= '_room_plan';
            } else {
                $bean->uploadfile = $file->get_stored_file_name();
                $bean->file_mime_type = $file->get_mime_type();
            }

            if (!($file->final_move($id) && $bean->save(false))) {
                throw new \InvalidArgumentException('Cannot save file');
            }

            $response->setData($this->getDataResponse(
                $bean,
                null,
                $request->getUri()->getPath() . '/' . $employee_bean->id
            ));
        }
        return $response;
    }

    public function imagePreview(ImagePreviewParams $params, Request $request)
    {
        $id = $params->getId();
        $module = $params->getModuleName();

        $bean = $this->beanManager->getBeanSafe($module, $id);

        $download_location = "upload://{$bean->id}" . $this->getFileType($module);
        header("Pragma: public");
        header("Cache-Control: maxage=1, post-check=0, pre-check=0");
        header('Content-type: ' . $this->getContentType($module, $bean));
        header('Content-Disposition: inline; filename="' . $this->getFilename($bean) . '"');
        header("X-Content-Type-Options: nosniff");
        header("Content-Length: " . filesize($download_location));
        header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time() + 2592000));
        set_time_limit(0);
        while (ob_get_level() && @ob_end_clean()) {}
        readfile($download_location);
    }

    protected function getFilename($bean)
    {
        switch ($bean->module_name) {
            case (in_array($bean->module_name, ['Users', 'Employees'])):
                return $bean->photo;
                break;
            case 'Rooms': 
                return $bean->room_plan;
                break;    
            default:
                return $bean->filename;
                break;
        }
    }

    protected function getFileType($module)
    {
        switch ($module) {
            case (in_array($module, ['Users', 'Employees'])):
                return '_photo';
                break;
            case 'Rooms':
                return '_room_plan';
                break;
            default:
                return '';
                break;
        }
    }

    protected function getContentType($module, $bean)
    {
        switch ($module) {
            case (in_array($module, ['Users', 'Employees', 'Rooms'])):
                return 'image/jpeg';
                break;
            default:
                return $bean->file_mime_type;
                break;
        }
    }

    public function getDataResponse(\SugarBean $bean, $fields = null, $path = null)
    {
        $dataResponse = new DataResponse($bean->getObjectName(), $bean->id);
        $dataResponse->setAttributes($this->attributeHelper->getAttributes($bean, $fields));
        $dataResponse->setRelationships($this->relationshipHelper->getRelationships($bean, $path));
        return $dataResponse;
    }

}
