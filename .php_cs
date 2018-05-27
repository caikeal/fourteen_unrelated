<?php
$header = <<<EOF
This file is part of the caikeal/fourteen_unrelated .

(c) caikeal <caiyuezhang@gmail.com>

This source file is subject to the MIT license that is bundled
with this source code in the file LICENSE.
EOF;

return PhpCsFixer\Config::create()
    ->setRiskyAllowed(true)
    ->setRules(array(
        '@Symfony' => true,
        'header_comment' => array('header' => $header),
        'array_syntax' => array('syntax' => 'short'),
        'binary_operator_spaces' => array('default' => 'align'),
        'ordered_imports' => true,
        'no_useless_else' => true,
        'no_useless_return' => true,
        'php_unit_construct' => true,
        'php_unit_strict' => true,
        'yoda_style' => false,
    ))
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->exclude('vendor')
            ->exclude('storage')
            ->exclude('routes')
            ->exclude('resources')
            ->exclude('public')
            ->exclude('bootstrap')
            ->notPath('server.php')
            ->in(__DIR__)
    )
;
