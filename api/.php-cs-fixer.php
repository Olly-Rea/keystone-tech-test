<?php

declare(strict_types=1);

$finder = (new PhpCsFixer\Finder())
    ->in([
        __DIR__ . '/app',
        __DIR__ . '/config',
        __DIR__ . '/database',
        __DIR__ . '/resources',
        __DIR__ . '/routes',
        __DIR__ . '/tests',
    ])
    ->name('*.php')
    ->notName('*.blade.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return (new PhpCsFixer\Config())
    ->setParallelConfig(PhpCsFixer\Runner\Parallel\ParallelConfigFactory::detect())
    ->setFinder($finder)
    ->setRiskyAllowed(true)
    ->setRules([
        // Risky fixes
        'comment_to_phpdoc' => [
            'ignored_tags' => ['todo'],
        ],
        'declare_strict_types' => false,
        'is_null' => true,
        'native_function_invocation' => true,
        // 'phpdoc_to_property_type' => true,
        'phpdoc_to_return_type' => true,
        'strict_comparison' => true,
        // Regular ruleset
        '@PHP83Migration' => true,
        '@Symfony' => true,
        'binary_operator_spaces' => [
            'operators' => [
                '=>' => 'single_space',
                '|' => 'no_space',
            ],
        ],
        'concat_space' => [
            'spacing' => 'one',
        ],
        'no_empty_comment' => false,
        'no_superfluous_phpdoc_tags' => false,
        'no_useless_return' => true,
        'phpdoc_add_missing_param_annotation' => true,
        'phpdoc_order_by_value' => true,
        'return_assignment' => true,
        'single_trait_insert_per_statement' => false,
        'yoda_style' => false,
    ]);
