<?php

namespace Phire\Entities\Form;

use Pop\Form\Form;
use Pop\Validator;

class Entity extends Form
{

    /**
     * Constructor
     *
     * Instantiate the form object
     *
     * @param  array  $fields
     * @param  string $action
     * @param  string $method
     * @return Entity
     */
    public function __construct(array $fields, $action = null, $method = 'post')
    {
        parent::__construct($fields, $action, $method);
        $this->setAttribute('id', 'entity-form');
        $this->setIndent('    ');
    }

}