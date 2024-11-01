<?php

return [
    'layout' => [],
    'content' => [
        'topbar_style_default' => [
            'msg' => [
                'label' => 'Message',
                'type' => 'text',
                'value' => 'Get up to 80% off on your first purchase!',
            ],
        ],
        'topbar_style_1' => [
            'msg' => [
                'label' => 'Message',
                'type' => 'loop_text',
                'value' => '',
            ],
            'msg_interval' => [
                'label' => 'Message Interval (in second)',
                'type' => 'number',
                'value' => '',
            ],
        ],
    ],
    'styles' => [
        'topbar_style_default' => [
            'height' => [
                'label' => 'Height (px)',
                'type' => 'number',
                'value' => '',
            ],
            'position' => [
                'label' => 'Position',
                'type' => 'select',
                'value' => '',
                'options' => [
                    'top' => 'Top',
                    'bottom' => 'Bottom',
                ],
            ],
            'text_color' => [
                'label' => 'Text Color',
                'type' => 'color',
                'value' => '#ffffff',
            ],
            'text_font_size' => [
                'label' => 'Text Font Size (px)',
                'type' => 'number',
                'value' => '',
            ],
            'btn_text_font_size' => [
                'label' => 'Button Font Size (px)',
                'type' => 'number',
                'value' => '',
            ],
            'is_sticky' => [
                'label' => 'Is Sticky?',
                'type' => 'select',
                'value' => '',
                'options' => [
                    'yes' => 'Yes',
                    'no' => 'No',
                ],
            ],
            'is_sticky_mobile' => [
                'label' => 'Is Sticky? (Mobile)',
                'type' => 'select',
                'value' => '',
                'options' => [
                    'yes' => 'Yes',
                    'no' => 'No',
                ],
            ],
            'bg_color' => [
                'label' => 'Background Color',
                'type' => 'color',
                'value' => '#000000',
            ],
            'btn_bg_color' => [
                'label' => 'Button Background Color',
                'type' => 'color',
                'value' => '#ffffff',
            ],
            'btn_text_color' => [
                'label' => 'Button Text Color',
                'type' => 'color',
                'value' => '#000000',
            ],
            'close_btn_text_color' => [
                'label' => 'Close Button Text Color',
                'type' => 'color',
                'value' => '#949494',
            ],
            'close_btn_bg_color' => [
                'label' => 'Close Button Background Color',
                'type' => 'color',
                'value' => '#333333',
            ],
        ],
        'topbar_style_1' => [
            'height' => [
                'label' => 'Height (px)',
                'type' => 'number',
                'value' => '',
            ],
            'position' => [
                'label' => 'Position',
                'type' => 'select',
                'value' => '',
                'options' => [
                    'top' => 'Top',
                    'bottom' => 'Bottom',
                ],
            ],
            'text_color' => [
                'label' => 'Text Color',
                'type' => 'color',
                'value' => '#ffffff',
            ],
            'text_font_size' => [
                'label' => 'Text Font Size (px)',
                'type' => 'number',
                'value' => '',
            ],
            'btn_text_font_size' => [
                'label' => 'Button Font Size (px)',
                'type' => 'number',
                'value' => '',
            ],
            'is_sticky' => [
                'label' => 'Is Sticky?',
                'type' => 'select',
                'value' => '',
                'options' => [
                    'yes' => 'Yes',
                    'no' => 'No',
                ],
            ],
            'is_sticky_mobile' => [
                'label' => 'Is Sticky? (Mobile)',
                'type' => 'select',
                'value' => '',
                'options' => [
                    'yes' => 'Yes',
                    'no' => 'No',
                ],
            ],
            'bg_color' => [
                'label' => 'Background Color',
                'type' => 'color',
                'value' => '#000000',
            ],
            'btn_bg_color' => [
                'label' => 'Button Background Color',
                'type' => 'color',
                'value' => '#ffffff',
            ],
            'btn_text_color' => [
                'label' => 'Button Text Color',
                'type' => 'color',
                'value' => '#000000',
            ],
            'close_btn_text_color' => [
                'label' => 'Close Button Text Color',
                'type' => 'color',
                'value' => '#949494',
            ],
            'close_btn_bg_color' => [
                'label' => 'Close Button Background Color',
                'type' => 'color',
                'value' => '#333333',
            ],
        ],
    ],
    'settings' => [
        'topbar_style_default' => [
            'fixed_header_selector' => [
                'label' => 'CSS Selector (If you have fixed header)',
                'type' => 'text',
                'value' => 'header',
                'note' => 'Add the CSS selector if the banner is overlapping with your fixed header section.',
            ],
        ],
        'topbar_style_1' => [
            'fixed_header_selector' => [
                'label' => 'CSS Selector (If you have fixed header)',
                'type' => 'text',
                'value' => 'header',
                'note' => 'Add the CSS selector if the banner is overlapping with your fixed header section.',
            ],
        ],
    ]
];