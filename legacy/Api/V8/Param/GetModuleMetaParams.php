<?php
namespace Api\V8\Param;

use Api\V8\Param\Options as ParamOption;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GetModuleMetaParams extends BaseParam
{
    public function getModuleName()
    {
        return $this->parameters['moduleName'];
    }

    protected function configureParameters(OptionsResolver $resolver)
    {
        $this->setOptions(
            $resolver,
            [
                ParamOption\ModuleName::class,
            ]
        );
    }
}
