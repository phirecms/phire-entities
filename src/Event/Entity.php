<?php

namespace Phire\Entities\Event;

use Phire\Entities\Model;
use Phire\Entities\Table;
use Pop\Application;
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

    /**
     * Init the entity model and parse any entity placeholders
     *
     * @param  AbstractController $controller
     * @param  Application        $application
     * @return void
     */
    public static function init(AbstractController $controller, Application $application)
    {
        if ($application->isRegistered('phire-templates') && ($controller->hasView()) && ($controller->view()->isStream())) {
            $ents = [];
            preg_match_all('/\[\{entity_.*\}\]/', $controller->view()->getTemplate()->getTemplate(), $ents);
            if (isset($ents[0]) && isset($ents[0][0])) {
                foreach ($ents[0] as $ent) {
                    $id = str_replace('}]', '', substr($ent, (strpos($ent, '_') + 1)));
                    $controller->view()->{'entity_' . $id} = (new Model\Entity())->getByType($id);
                }
            }
        }

        if ($controller->hasView()) {
            $controller->view()->phire->entity = new Model\Entity();
        }
    }

}
