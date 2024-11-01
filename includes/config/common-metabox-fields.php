<?php 

return [
    'layout' => [
        'layout' => [
            'label' => 'Choose a layout ',
            'type' => 'image_selector',
            'value' => 'topbar_style_1',
            'options' => WPANN_LAYOUT_INPUT_OPTIONS,
        ]
    ],
    'content' => [
        'cta_text' => [
            'label' => 'Button Text',
            'type' => 'text',
            'value' => 'Get Started',
        ],
        'cta_url' => [
            'label' => 'Button URL',
            'type' => 'text',
            'value' => '#',
        ],
    ],
    'styles' => [],
    'settings' => [
        'start_date' => [
            'label' => 'Start At',
            'type' => 'date',
            'value' => '',
        ],
        'deadline' => [
            'label' => 'Expire At',
            'type' => 'date',
            'value' => '',
        ],
        'page_ids' => [
            'label' => 'Page (Optional)',
            'type' => 'multi_dropdown_pages',
            'value' => '',
        ],
        'hide_mobile' => [
            'label' => 'Hide on Mobile?',
            'type' => 'select',
            'value' => '',
            'options' => [
                'yes' => 'Yes',
                'no' => 'No',
            ],
        ],
        'hide_close_btn' => [
            'label' => 'Hide Close Button?',
            'type' => 'select',
            'value' => '',
            'options' => [
                'no' => 'No',
                'yes' => 'Yes',
            ],
        ],
        'startup_delay' => [
            'label' => 'Delay Before Showing (in second)',
            'type' => 'number',
            'value' => '',
        ],
        'auto_close_delay' => [
            'label' => 'Auto Close After (in second)',
            'type' => 'number',
            'value' => '',
        ],
        'allowed_days' => [
            'label' => 'Show On Specific Days',
            'type' => 'multiselect',
            'value' => '',
            'options' => [
                '7' => 'Everyday',
                '0' => 'Sunday',
                '1' => 'Monday',
                '2' => 'Tuesday',
                '3' => 'Wednesday',
                '4' => 'Thursday',
                '5' => 'Friday',
                '6' => 'Saturday',
            ],
            'value' => json_encode(['7'])
        ],
        
    ]
];