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
        $config = $application->module('phire-forms');
        $models = (isset($config['models'])) ? $config['models'] : null;
        $types  = Table\EntityTypes::findAll();

        foreach ($types->rows() as $type) {
            if (null !== $models) {
                if (!isset($models['Phire\Entities\Model\Entity'])) {
                    $models['Phire\Entities\Model\Entity'] = [];
                }

                $models['Phire\Entities\Model\Entity'][] = [
                    'type_field' => 'id',
                    'type_value' => $type->id,
                    'type_name'  => $type->name
                ];
            }
        }

        if (null !== $models) {
            $application->module('phire-forms')->mergeConfig(['models' => $models]);
        }
    }

}
