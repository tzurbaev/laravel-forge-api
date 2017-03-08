<?php

$finder = PhpCsFixer\Finder::create()->in(__DIR__.'/src');

return PhpCsFixer\Config::create()
    ->setUsingCache(false)
    ->setRules([
        '@PSR2' => true,
        'phpdoc_align' => true,
        'phpdoc_no_empty_return' => true,
        'phpdoc_order' => true,
        'phpdoc_separation' => true,
        'array_syntax' => ['syntax' => 'short'],
        'no_unused_imports' => true,
        'phpdoc_trim' => true,
    ])
    ->setFinder($finder);
