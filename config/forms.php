<?php
/**
 * phire-entities form configuration
 */
return [
    'Phire\Entities\Form\Entity' => [
        [
            'title' => [
                'type'       => 'text',
                'label'      => 'Title',
                'required'   => true,
                'attributes' => [
                    'size'   => 60,
                    'style'  => 'width: 98%'
                ]
            ],
            'submit' => [
                'type'       => 'submit',
                'value'      => 'Save',
                'attributes' => [
                    'class'  => 'save-btn wide'
                ]
            ]
        ],
        [
            'type_id' => [
                'type'  => 'hidden',
                'value' => 0
            ],
            'id' => [
                'type'  => 'hidden',
                'value' => 0
            ]
        ],
        []
    ],
    'Phire\Entities\Form\EntityType' => [
        [
            'submit' => [
                'type'       => 'submit',
                'value'      => 'Save',
                'attributes' => [
                    'class'  => 'save-btn wide'
                ]
            ],
            'order' => [
                'type'  => 'text',
                'label' => 'Order',
                'value' => 0,
                'attributes' => [
                    'size'  => 2,
                    'class' => 'order-field'
                ]
            ],
            'field_num' => [
                'type'  => 'text',
                'label' => 'Display # of Fields',
                'value' => 5,
                'attributes' => [
                    'size'  => 2,
                    'class' => 'order-field'
                ]
            ],
            'id' => [
                'type'  => 'hidden',
                'value' => 0
            ]
        ],
        [
            'name' => [
                'type'       => 'text',
                'label'      => 'Name',
                'required'   => true,
                'attributes' => [
                    'size'   => 60,
                    'style'  => 'width: 98%'
                ]
            ]
        ]
    ]
];
