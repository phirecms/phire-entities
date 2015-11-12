<?php

namespace Phire\Entities\Form;

use Pop\Form\Form;
use Pop\Validator;

class EntityType extends Form
{

    /**
     * Constructor
     *
     * Instantiate the form object
     *
     * @param  array  $fields
     * @param  string $action
     * @param  string $method
     * @return EntityType
     */
    public function __construct(array $fields, $action = null, $method = 'post')
    {
        parent::__construct($fields, $action, $method);
        $this->setAttribute('id', 'entity-type-form');
        $this->setIndent('    ');
    }

}