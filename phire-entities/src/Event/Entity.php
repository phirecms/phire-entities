<?php

namespace Phire\Entities\Event;

use Phire\Entities\Table;
use Pop\Application;
use Pop\Web\Mobile;
use Pop\Web\Session;
use Phire\Controller\AbstractController;

class Entity
{

    /**
     * Bootstrap the module
     *
     * @param  Application $application
     * @return void
     */
    public static function bootstrap(Application $application)
    {
        $resources = $application->config()['resources'];
        $params    = $application->services()->getParams('nav.phire');
        $config    = $application->module('phire-entities');
        $models    = (isset($config['models'])) ? $config['models'] : null;
        $types     = Table\EntityTypes::findAll(['order' => 'order ASC']);

        foreach ($types->rows() as $type) {
            if (null !== $models) {
                if (!isset($models['Phire\Entities\Model\Entity'])) {
                    $models['Phire\Entities\Model\Entity'] = [];
                }

                $models['Phire\Entities\Model\Entity'][] = [
                    'type_field' => 'type_id',
                    'type_value' => $type->id,
                    'type_name'  => $type->name
                ];
            }

            $resources['entity-type-' . $type->id . '|entity-type-' . str_replace(' ', '-', strtolower($type->name))] = [
                'index', 'add', 'edit', 'export', 'remove'
            ];

            if (!isset($params['tree']['entities']['children'])) {
                $params['tree']['entities']['children'] = [];
            }

            $params['tree']['entities']['children']['entity-type-' . $type->id] = [
                'name' => $type->name,
                'href' => '/entities/' . $type->id,
                'acl'  => [
                    'resource'   => 'entity-type-' . $type->id,
                    'permission' => 'index'
                ]
            ];
        }

        $application->mergeConfig(['resources' => $resources]);
        $application->services()->setParams('nav.phire', $params);
        if (null !== $models) {
            $application->module('phire-entities')->mergeConfig(['models' => $models]);
        }

    }

}
