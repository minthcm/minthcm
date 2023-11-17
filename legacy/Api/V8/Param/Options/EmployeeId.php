<?php
namespace Api\V8\Param\Options;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class EmployeeId extends BaseOption
{
    /**
     * @inheritdoc
     */
    public function add(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired('employeeId')
            ->setAllowedTypes('employeeId', 'string')
            ->setAllowedValues('employeeId', $this->validatorFactory->createClosure([
                new Assert\Regex('/^(\d+|[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12})$/i')
            ]));
    }
}
