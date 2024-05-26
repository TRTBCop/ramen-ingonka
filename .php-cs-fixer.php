<?php

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR12' => true,
        'concat_space' => ['spacing' => 'none'], // 문자열 합칠때 빈칸처리
        'array_syntax' => ['syntax' => 'short'], // 짧은 배열
        'single_quote' => true, // 싱글쿼터 사용
        'braces' => [
            'allow_single_line_closure' => true,
        ],
        'whitespace_after_comma_in_array' => true, // 콤마뒤 공백추가
    ]);
