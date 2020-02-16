<?php
if (PHP_SAPI !== 'cli') {
    die('This script supports command line usage only. Please check your command.');
}

$finder = PhpCsFixer\Finder::create();
$finder->in([
    'packages/contracts/**/src',
    'packages/development/**/src',
    'packages/component/**/src',

    'tests'
]);

return PhpCsFixer\Config::create()
    ->setRiskyAllowed(true)
    ->setRules(array (
        '@PSR2' => true,
        'no_leading_import_slash' => true,
        'no_trailing_comma_in_singleline_array' => true,
        'no_singleline_whitespace_before_semicolons' => true,
        'no_unused_imports' => true,
        'concat_space' =>
            array (
                'spacing' => 'one',
            ),
        'no_whitespace_in_blank_line' => true,
        'ordered_imports' => true,
        'single_quote' => true,
        'no_empty_statement' => true,

        'phpdoc_no_package' => true,
        'phpdoc_scalar' => true,
        'no_blank_lines_after_phpdoc' => true,
        'array_syntax' =>
            array (
                'syntax' => 'short',
            ),
        'whitespace_after_comma_in_array' => true,
        'function_typehint_space' => true,

        'no_alias_functions' => true,
        'lowercase_cast' => true,
        'no_leading_namespace_whitespace' => true,
        'native_function_casing' => true,
        'self_accessor' => true,
        'no_short_bool_cast' => true,
        'no_unneeded_control_parentheses' => true,
    ))
    ->setFinder($finder);
