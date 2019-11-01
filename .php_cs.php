<?php
/**
 * Created by PhpStorm.
 * User: fisher
 * Date: 2018/10/12
 * Time: 上午11:00
 */
return PhpCsFixer\Config::create()
    ->setRiskyAllowed(true)
    ->setRules([
        // 基础规则 PSR2
        '@PSR2' => true,

        // 对齐
        'align_multiline_comment' => ['comment_type' => 'all_multiline'],

        // 禁止使用简写输出标签 "<?="
        'no_short_echo_tag' => true,

        // 在某些关键字前加空行 例如: return
        'blank_line_before_statement' => true,

        // 清除未使用的引用
        'no_unused_imports' => true,

        // 在叹号!前后加空格
        'not_operator_with_space' => true,

        // 数组使用简写格式
        'array_syntax' => ['syntax' => 'short'],

        // 按照长度排列引用
        'ordered_imports' => ['sortAlgorithm' => 'length'],

        // namespace 前有且仅有一行空行
        'single_blank_line_before_namespace' => true,

        // 清除多余空行
        'no_extra_blank_lines' => true,
        // 多余空白行
        'no_extra_consecutive_blank_lines' => true,

        /**
         * 单行数组定义, 清除最后一个逗号
         * [1,2,] -> [1,2]
         */
        'no_trailing_comma_in_singleline_array' => true,

        // 多行数组定义, 最后一行需有逗号
        'trailing_comma_in_multiline_array' => true,

        // 格式数组定义, 就像函数的参数
        'trim_array_spaces' => true,

        // 点连接符 两边各一个空格
        'concat_space' => ['spacing' => 'one'],

        // 替换不等于符号 <> -> !=
        'standardize_not_equals' => true,

        // 等号(等号箭头,数字箭头)垂直对齐
        'binary_operator_spaces' => ['default' => 'align_single_space'],

        //
        //'braces' => true,

        //
        'class_definition'      => true,

        'elseif'                => true,

        'function_declaration'  => true,

        'indentation_type'      => true,

        'line_ending'           => true,

        'lowercase_constants'   => true,

        'lowercase_keywords'    => true,

        'method_argument_space' => [
            'ensure_fully_multiline' => true,
        ],

        'no_break_comment'                   => true,

        'no_closing_tag'                     => true,

        'no_spaces_after_function_name'      => true,

        'no_spaces_inside_parenthesis'       => true,

        'no_trailing_whitespace'             => true,

        'no_trailing_whitespace_in_comment'  => true,

        'single_blank_line_at_eof'           => true,

        'single_class_element_per_statement' => [
            'elements' => ['property'],
        ],

        'single_import_per_statement'    => true,

        'single_line_after_imports'      => true,

        'switch_case_semicolon_to_colon' => true,

        'switch_case_space'              => true,

        'visibility_required'            => true,

        'encoding'                       => true,

        'full_opening_tag'               => true,

        'cast_spaces'                    => true,

        'single_blank_line_before_namespace' => false,

        'no_blank_lines_before_namespace'    => true,

        'blank_line_after_opening_tag'       => false,

        'phpdoc_align'                       => false,

        // 简单字符串应该使用单引号代替双引号
        'single_quote' => true,

        // 禁止只有单行空格和分号的写法
        'no_singleline_whitespace_before_semicolons' => true,

        // 在当前类中使用 self 代替类名
        'self_accessor' => true,

        // 多余的分号
        'no_empty_statement' => true,

        // 类开始标签后不应该有空白行
        'no_blank_lines_after_class_opening' => true,

        // include 和文件路径之间需要有一个空格，文件路径不需要用括号括起来
        'include' => true,

        // 删除 list 语句中多余的逗号
        'no_trailing_comma_in_list_call'             => true,

        // 命名空间前面不应该有空格
        'no_leading_namespace_whitespace'            => true,
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->exclude('tests')
            ->in(__DIR__)
    )
;
