<?php

$finder = PhpCsFixer\Finder::create()
    ->exclude([
        'bootstrap',
        'Docker',
        'node_modules',
        'public',
        'resources',
        'storage',
        'vendor',
    ])
    ->in(__DIR__);

return (new PhpCsFixer\Config())->setRules([
    '@PSR12' => true,
    'align_multiline_comment' => true,
    'array_indentation' => true,
    'array_push' => true,
    'array_syntax' => ['syntax' => 'short'],
    'binary_operator_spaces' => [
        'default' => 'single_space',
    ],
    'blank_line_after_namespace' => true,
    'blank_line_after_opening_tag' => true,
    'blank_line_before_statement' => [
        'statements' => [
            'break',
            'continue',
            'declare',
            'for',
            'foreach',
            'if',
            'return',
            'throw',
            'try',
        ],
    ],
    'braces' => [
        'allow_single_line_anonymous_class_with_empty_body' => true,
        'position_after_anonymous_constructs' => 'next',
    ],
    'cast_spaces' => [
        'space' => 'single'
    ],
    'class_attributes_separation' => [
        'elements' => [
            'const' => 'none',
            'method' => 'one',
            'property' => 'one',
            'trait_import' => 'none',
            'case' => 'one'
        ],
    ],
    'class_definition' => [
        'inline_constructor_arguments' => true,
    ],
    'class_reference_name_casing' => true,
    'clean_namespace' => true,
    'combine_consecutive_unsets' => true,
    'compact_nullable_typehint' => true,
    'concat_space' => [
        'spacing' => 'none',
    ],
    'constant_case' => [
        'case' => 'lower',
    ],
    'declare_parentheses' => true,
    'declare_strict_types' => false, // confirmar
    'echo_tag_syntax' => [
        'format' => 'long',
    ],
    'elseif' => true,
    'encoding' => true,
    'ereg_to_preg' => true,
    'explicit_string_variable' => true,
    'final_class' => false, // confirmar
    'fopen_flag_order' => true,
    'header_comment' => [
        'header' => <<<'HEADER'
            This file is part of Consultorio Medico Application.
            (c) The devcsuarez Team <devcsuarez@gmail.com>
            HEADER,
        'location' => 'after_open',
    ],
    'increment_style' => [
        'style' => 'pre',
    ],
    'list_syntax' => true,
    'logical_operators' => true,
    'method_chaining_indentation' => true,
    'is_null' => true,
    'new_with_braces' => true,
    'strict_comparison' => true,
    'strict_param' => true,
    'ordered_class_elements' => true,
    'ordered_imports' => [
        'sort_algorithm' => 'alpha',
    ],
    'single_quote' => true,
    'single_line_after_imports' => true,
    'static_lambda' => false, // validar
    'trailing_comma_in_multiline' => true,
    'void_return' => true,
    'whitespace_after_comma_in_array' => [
        'ensure_single_space' => true,
    ],
    'yoda_style' => [
        'always_move_variable' => true,
        'equal' => true,
        'identical' => true,
        'less_and_greater' => true,
    ],
])
    ->setRiskyAllowed(true)
    ->setFinder($finder);
