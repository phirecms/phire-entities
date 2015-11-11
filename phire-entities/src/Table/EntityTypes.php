<?php

namespace Phire\Entities\Table;

use Pop\Db\Record;

class EntityType extends Record
{

    /**
     * Table prefix
     * @var string
     */
    protected $prefix = DB_PREFIX;

    /**
     * Primary keys
     * @var array
     */
    protected $primaryKeys = ['id'];

}