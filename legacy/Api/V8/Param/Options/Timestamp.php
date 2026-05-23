<?php
// MintHCM #87887
namespace Api\V8\Param\Options;

use Symfony\Component\OptionsResolver\OptionsResolver;

class Timestamp extends BaseOption
{
    /**
     * @inheritdoc
     */
    public function add(OptionsResolver $resolver)
    {
        $resolver
            ->setDefined('timestamp')
            ->setAllowedTypes('timestamp', 'string');
    }
}
