<?php

return (new PhpCsFixer\Config())
    ->setRules([
        'braces' => [
            'position_after_functions_and_oop_constructs' => 'same',
            'position_after_control_structures' => 'same',
            'position_after_anonymous_constructs' => 'same',
        ],
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in(__DIR__)
    );