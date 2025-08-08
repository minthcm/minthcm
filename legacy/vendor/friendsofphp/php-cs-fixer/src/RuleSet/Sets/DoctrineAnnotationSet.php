<?php

/*
 * This file is part of PHP CS Fixer.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PhpCsFixer\RuleSet\Sets;

use PhpCsFixer\RuleSet\AbstractRuleSetDescription;

/**
 * @internal
 */
final class DoctrineAnnotationSet extends AbstractRuleSetDescription
{
    public function getRules()
    {
        return [
            'doctrine_annotation_array_assignment' => [
                'operator' => ':',
            ],
            'doctrine_annotation_braces' => true,
            'doctrine_annotation_indentation' => true,
            'doctrine_annotation_spaces' => [
                'before_array_assignments_colon' => false,
            ],
        ];
    }

    public function getDescription()
    {
        return 'Rules covering Doctrine annotations with configuration based on examples found in `Doctrine Annotation documentation <http://docs.doctrine-project.org/projects/doctrine-common/en/latest/reference/annotations.html>`_ and `Symfony documentation <http://symfony.com/doc/master/bundles/SensioFrameworkExtraBundle/annotations/routing.html>`_.';
    }
}
