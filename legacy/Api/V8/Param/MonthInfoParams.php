<?php
namespace Api\V8\Param;

use Api\V8\Param\Options as ParamOption;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MonthInfoParams extends BaseParam
{
    public function getEmployeeId()
    {
        return $this->parameters['employeeId'];
    }

    public function getDate()
    {
        return $this->parameters['date'];
    }

    protected function configureParameters(OptionsResolver $resolver)
    {
        $this->setOptions(
            $resolver,
            [
                ParamOption\EmployeeId::class,
                ParamOption\Date::class
            ]
        );
    }
}
