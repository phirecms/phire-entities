<?php
/**
 * Module Name: phire-entities
 * Author: Nick Sagona
 * Description: This is the entities module for Phire CMS 2, to be used in conjunction with the fields module
 * Version: 1.0
 */
return [
    'phire-entities' => [
        'prefix'     => 'Phire\Forms\\',
        'src'        => __DIR__ . '/../src',
        'routes'     => include 'routes.php',
        'resources'  => include 'resources.php',
        'forms'      => include 'forms.php',
        'nav.phire'  => [
            'entities' => [
                'name' => 'Entities',
                'href' => '/entities',
                'acl' => [
                    'resource'   => 'entities',
                    'permission' => 'index'
                ],
                'attributes' => [
                    'class' => 'entities-nav-icon'
                ]
            ]
        ],
        'nav.module' => [
            'name' => 'Entity Types',
            'href' => '/entities/types',
            'acl'  => [
                'resource'   => 'entity-types',
                'permission' => 'index'
            ]
        ],
        'models' => [
            'Phire\Entities\Model\Entity' => []
        ],
        'events' => [
            [
                'name'     => 'app.route.pre',
                'action'   => 'Phire\Entities\Event\Entity::bootstrap',
                'priority' => 1000
            ]
        ],
        'field_list_limit' => 3
    ]
];
