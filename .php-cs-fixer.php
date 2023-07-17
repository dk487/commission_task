<?php

declare(strict_types=1);

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__ . '/src', __DIR__ . '/tests')
    ->files()
    ->name('*.php')
    ->exclude('vendor')
    ->ignoreDotFiles(false)
    ->ignoreVCS(true);

$rules = [
    '@Symfony' => true,
    'concat_space' => ['spacing' => 'one'],
];

$config = new PhpCsFixer\Config();

return $config->setRules($rules)->setFinder($finder);
