<?php

$finder = \PhpCsFixer\Finder::create()
    ->in(__DIR__.'/src')
    ->in(__DIR__.'/tests');

return \PhpCsFixer\Config::create()
    ->setRules(
        [
            '@PSR2' => true,
            '@PHP56Migration' => true,
            '@DoctrineAnnotation' => true,
            'array_syntax' => ['syntax' => 'short'],
            'binary_operator_spaces' => true,
            'blank_line_after_opening_tag' => true,
            'blank_line_before_return' => true,
            'blank_line_before_statement' => true,
            'cast_spaces' => ['space' => 'single'],
            'combine_consecutive_issets' => true,
            'combine_consecutive_unsets' => true,
            'concat_space' => ['spacing' => 'none'],
            'declare_equal_normalize' => ['space' => 'none'],
            'function_typehint_space' => true,
            'include' => true,
            'linebreak_after_opening_tag' => true,
            'list_syntax' => ['syntax' => 'long'],
            'lowercase_cast' => true,
            'magic_constant_casing' => true,
            'mb_str_functions' => false,
            'method_chaining_indentation' => true,
            'method_separation' => true,
            'multiline_whitespace_before_semicolons' => ['strategy' => 'no_multi_line'],
            'native_function_casing' => true,
            'new_with_braces' => true,
            'no_blank_lines_after_class_opening' => true,
            'no_blank_lines_after_phpdoc' => true,
            'no_empty_comment' => true,
            'no_empty_phpdoc' => true,
            'no_empty_statement' => true,
            'no_leading_import_slash' => true,
            'no_mixed_echo_print' => ['use' => 'echo'],
            'no_multiline_whitespace_around_double_arrow' => true,
            'no_multiline_whitespace_before_semicolons' => true,
            'no_null_property_initialization' => true,
            'no_short_bool_cast' => true,
            'no_singleline_whitespace_before_semicolons' => true,
            'no_spaces_after_function_name' => true,
            'no_spaces_around_offset' => true,
            'no_spaces_inside_parenthesis' => true,
            'no_superfluous_elseif' => true,
            'no_trailing_comma_in_list_call' => true,
            'no_trailing_comma_in_singleline_array' => true,
            'no_unneeded_control_parentheses' => true,
            'no_unneeded_curly_braces' => true,
            'no_unneeded_final_method' => true,
            'no_unreachable_default_argument_value' => false,
            'no_unused_imports' => true,
            'no_useless_else' => true,
            'no_useless_return' => true,
            'no_whitespace_before_comma_in_array' => true,
            'no_whitespace_in_blank_line' => true,
            'non_printable_character' => false,
            'object_operator_without_whitespace' => true,
            'phpdoc_no_access' => true,
            'phpdoc_no_empty_return' => true,
            'phpdoc_no_package' => true,
            'phpdoc_no_useless_inheritdoc' => true,
            'phpdoc_return_self_reference' => true,
            'phpdoc_scalar' => true,
            'phpdoc_single_line_var_spacing' => true,
            'phpdoc_to_comment' => true,
            'phpdoc_types' => true,
            'phpdoc_var_without_name' => true,
            'increment_style' => ['style' => 'post'],
            'return_type_declaration' => true,
            'semicolon_after_instruction' => true,
            'short_scalar_cast' => true,
            'silenced_deprecation_error' => false,
            'single_blank_line_before_namespace' => true,
            'single_line_comment_style' => false,
            'single_quote' => true,
            'space_after_semicolon' => true,
            'standardize_not_equals' => true,
            'strict_comparison' => false,
            'strict_param' => false,
            'ternary_operator_spaces' => true,
            'trim_array_spaces' => true,
            'unary_operator_spaces' => true,
            'void_return' => false,
            'whitespace_after_comma_in_array' => true,
        ]
    )
    ->setRiskyAllowed(false)
    ->setUsingCache(false)
    ->setIndent("    ")
    ->setLineEnding("\n")
    ->setFinder($finder);
