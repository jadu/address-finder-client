<?php

use PhpCsFixer\Finder;
use PhpCsFixer\Config;

$finder = Finder::create();
$finder->in([
    __DIR__ . '/src',
    __DIR__ . '/tests',
]);

$config = Config::create();
$config->setFinder($finder);
$config->setRules([
    '@Symfony' => true,
    'psr0' => false,
    'array_syntax' => ['syntax' => 'short'],
    'concat_space' => ['spacing' => 'one'],
    'ordered_imports' => true,
    'phpdoc_align' => false,
]);

return $config;
