<?php
namespace Api\V8\Param\Options;

use Symfony\Component\OptionsResolver\OptionsResolver;

class Date extends BaseOption
{
    /**
     * @inheritdoc
     */
    public function add(OptionsResolver $resolver)
    {
        $resolver
            ->setDefined('date')
            ->setAllowedTypes('date', 'string');
    }
}
