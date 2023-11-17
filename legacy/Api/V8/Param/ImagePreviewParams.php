<?php
namespace Api\V8\Param;

use Api\V8\Param\Options as ParamOption;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImagePreviewParams extends BaseParam
{
    public function getId()
    {
        return $this->parameters['id'];
    }

    public function getModuleName()
    {
        return $this->parameters['moduleName'];
    }

    protected function configureParameters(OptionsResolver $resolver)
    {
        $this->setOptions(
            $resolver,
            [
                ParamOption\Id::class,
                ParamOption\ModuleName::class,
            ]
        );
    }
}
