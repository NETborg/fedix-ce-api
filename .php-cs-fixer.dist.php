<?php

$finder = (new PhpCsFixer\Finder())
    ->in([
        'src',
        'tests'
    ])
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
        'no_unused_imports' => true,
    ])
    ->setFinder($finder)
;
