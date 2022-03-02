<?php

return [
    'suffixes' => [
        'table' => \Fatihirday\Suffixed\Models\Suffix::class,
        'column' => 'code',
        'auto_create_suffixed_tables' => true,
    ],

    'suffix_auto_check' => true,

    'merge_operator' => '_',

    'suffix_max_length' => 3,
];
