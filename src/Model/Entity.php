<?php
/**
 * Phire Entities Module
 *
 * @link       https://github.com/phirecms/phire-entities
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2016 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.phirecms.org/license     New BSD License
 */

/**
 * @namespace
 */
namespace Phire\Entities\Model;

use Phire\Entities\Table;
use Phire\Model\AbstractModel;

/**
 * Entity Model class
 *
 * @category   Phire\Entities
 * @package    Phire\Entities
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2016 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.phirecms.org/license     New BSD License
 * @version    1.0.0
 */
class Entity extends AbstractModel
{

    /**
     * Get all entities
     *
     * @param  int    $typeId
     * @param  int    $limit
     * @param  int    $page
     * @param  string $sort
     * @return array
     */
    public function getAll($typeId = null, $limit = null, $page = null, $sort = null)
    {
        $order = (null !== $sort) ? $this->getSortOrder($sort, $page) : 'id ASC';

        if (null !== $typeId) {
            if (null !== $limit) {
                $page = ((null !== $page) && ((int)$page > 1)) ?
                    ($page * $limit) - $limit : null;

                $rows = Table\Entities::findBy(['type_id' => $typeId], [
                    'offset' => $page,
                    'limit'  => $limit,
                    'order'  => $order
                ])->rows();
            } else {
                $rows = Table\Entities::findBy(['type_id' => $typeId], ['order' => $order])->rows();
            }
        } else {
            if (null !== $limit) {
                $page = ((null !== $page) && ((int)$page > 1)) ?
                    ($page * $limit) - $limit : null;

                $rows = Table\Entities::findAll([
                    'offset' => $page,
                    'limit'  => $limit,
                    'order'  => $order
                ])->rows();
            } else {
                $rows = Table\Entities::findAll(['order' => $order])->rows();
            }
        }

        $fieldNames = [];

        foreach ($rows as $i => $row) {
            if (class_exists('Phire\Fields\Model\FieldValue')) {
                $class = 'Phire\Entities\Model\Entity';
                $sql   = \Phire\Fields\Table\Fields::sql();
                $sql->select()->where('models LIKE :models');
                $sql->select()->orderBy('order');

                $value  = ($sql->getDbType() == \Pop\Db\Sql::SQLITE) ? '%' . $class . '%' : '%' . addslashes($class) . '%';
                $fields = \Phire\Fields\Table\Fields::execute((string)$sql, ['models' => $value]);

                foreach ($fields->rows() as $field) {
                    $field->models = unserialize($field->models);
                    if ($this->isFieldAllowed($field->models, $row)) {
                        if ($field->storage == 'eav') {
                            $fv = \Phire\Fields\Table\FieldValues::findBy([
                                'field_id' => $field->id,
                                'model_id' => $row->id,
                                'model' => 'Phire\Entities\Model\Entity'
                            ]);
                            foreach ($fv->rows() as $fv) {
                                if (!array_key_exists($field->name, $fieldNames)) {
                                    $fieldNames[$field->name] = $field->type;
                                }
                                $rows[$i][$field->name] = json_decode($fv->value, true);
                            }
                        } else {
                            $fv = new \Pop\Db\Record();
                            $fv->setPrefix(DB_PREFIX)
                                ->setPrimaryKeys(['id'])
                                ->setTable('field_' . $field->name);

                            $fv->findRecordsBy([
                                'model_id' => $row->id,
                                'model' => 'Phire\Entities\Model\Entity',
                                'revision' => 0
                            ]);

                            if (!array_key_exists($field->name, $fieldNames)) {
                                $fieldNames[$field->name] = $field->type;
                            }

                            if ($fv->count() > 1) {
                                $rows[$i][$field->name] = [];
                                foreach ($fv->rows() as $f) {

                                    $rows[$i][$field->name][] = $f->value;
                                }
                            } else {
                                $rows[$i][$field->name] = $fv->value;
                            }
                        }
                    }
                }
            }
        }

        return $rows;
    }

    /**
     * Get all entity field names
     *
     * @param  array $rows
     * @return array
     */
    public function getAllFields(array $rows)
    {
        $fieldNames = [];

        foreach ($rows as $i => $row) {
            if (class_exists('Phire\Fields\Model\FieldValue')) {
                $class = 'Phire\Entities\Model\Entity';
                $sql   = \Phire\Fields\Table\Fields::sql();
                $sql->select()->where('models LIKE :models');
                $sql->select()->orderBy('order');

                $value  = ($sql->getDbType() == \Pop\Db\Sql::SQLITE) ? '%' . $class . '%' : '%' . addslashes($class) . '%';
                $fields = \Phire\Fields\Table\Fields::execute((string)$sql, ['models' => $value]);

                foreach ($fields->rows() as $field) {
                    $field->models = unserialize($field->models);
                    if ($this->isFieldAllowed($field->models, $row)) {
                        if ($field->storage == 'eav') {
                            $fv = \Phire\Fields\Table\FieldValues::findBy([
                                'field_id' => $field->id,
                                'model_id' => $row->id,
                                'model' => 'Phire\Entities\Model\Entity'
                            ]);
                            foreach ($fv->rows() as $fv) {
                                if (!array_key_exists($field->name, $fieldNames)) {
                                    $fieldNames[$field->name] = $field->type;
                                }
                                $rows[$i][$field->name] = json_decode($fv->value, true);
                            }
                        } else {
                            $fv = new \Pop\Db\Record();
                            $fv->setPrefix(DB_PREFIX)
                                ->setPrimaryKeys(['id'])
                                ->setTable('field_' . $field->name);

                            $fv->findRecordsBy([
                                'model_id' => $row->id,
                                'model' => 'Phire\Entities\Model\Entity',
                                'revision' => 0
                            ]);

                            if (!array_key_exists($field->name, $fieldNames)) {
                                $fieldNames[$field->name] = $field->type;
                            }

                            if ($fv->count() > 1) {
                                $rows[$i][$field->name] = [];
                                foreach ($fv->rows() as $f) {

                                    $rows[$i][$field->name][] = $f->value;
                                }
                            } else {
                                $rows[$i][$field->name] = $fv->value;
                            }
                        }
                    }
                }
            }
        }

        return $fieldNames;
    }


    /**
     * Get all entities for export
     *
     * @param  int $typeId
     * @return array
     */
    public function getAllForExport($typeId = null)
    {
        $rows      = $this->getAll($typeId);
        $arrayRows = [];
        foreach ($rows as $value) {
            $value = $value->getColumns();
            foreach($value as $k => $v) {
                if (is_array($v)) {
                    $value[$k] = implode(', ', $v);
                }
            }
            $arrayRows[] = (array)$value;
        }

        return $arrayRows;
    }

    /**
     * Get entity by type
     *
     * @param  string $type
     * @param  int    $limit
     * @param  int    $page
     * @param  string $sort
     * @return array
     */
    public function getByType($type, $limit = null, $page = null, $sort = null)
    {
        $entityType = (is_numeric($type)) ?
            Table\EntityTypes::findById($type) :
            Table\EntityTypes::findBy(['name' => $type]);

        $rows = [];

        if (isset($entityType->id)) {
            $rows = $this->getAll($entityType->id, $limit, $page, $sort);
        }

        return $rows;
    }

    /**
     * Get entity by ID
     *
     * @param  int   $id
     * @param  array $filters
     * @return \ArrayObject
     */
    public function getById($id, array $filters = [])
    {
        $entity = Table\Entities::findById($id);
        $data   = [];
        if (isset($entity->id)) {
            $data = $entity->getColumns();
            $this->data = array_merge($this->data, $data);

            if (class_exists('Phire\Fields\Model\FieldValue')) {
                $entity     = \Phire\Fields\Model\FieldValue::getModelObjectValues($this, null, $filters);
                $data       = $entity->toArray();
                $this->data = array_merge($this->data, $data);
            }
        }

        return new \ArrayObject($this->data, \ArrayObject::ARRAY_AS_PROPS);
    }

    /**
     * Get entity by title
     *
     * @param  string $title
     * @param  array  $filters
     * @return \ArrayObject
     */
    public function getByTitle($title, array $filters = [])
    {
        $entity = Table\Entities::findBy(['title' => $title]);
        if (isset($entity->id)) {
            $this->getById($entity->id);

            if (class_exists('Phire\Fields\Model\FieldValue')) {
                $entity     = \Phire\Fields\Model\FieldValue::getModelObjectValues($this, null, $filters);
                $data       = $entity->toArray();
                $this->data = array_merge($this->data, $data);
            }
        }

        return new \ArrayObject($this->data, \ArrayObject::ARRAY_AS_PROPS);
    }

    /**
     * Save new entity
     *
     * @param  array $fields
     * @return void
     */
    public function save(array $fields)
    {
        $entity = new Table\Entities([
            'title'   => $fields['title'],
            'type_id' => $fields['type_id']
        ]);
        $entity->save();

        $this->data = array_merge($this->data, $entity->getColumns());
    }

    /**
     * Update an existing entity
     *
     * @param  array $fields
     * @return void
     */
    public function update(array $fields)
    {
        $entity = Table\Entities::findById((int)$fields['id']);
        if (isset($entity->id)) {
            $entity->title   = $fields['title'];
            $entity->type_id = $fields['type_id'];
            $entity->save();

            $this->data = array_merge($this->data, $entity->getColumns());
        }
    }

    /**
     * Remove an entity
     *
     * @param  array $fields
     * @return void
     */
    public function remove(array $fields)
    {
        if (isset($fields['rm_entities'])) {
            foreach ($fields['rm_entities'] as $id) {
                $entity = Table\Entities::findById((int)$id);
                if (isset($entity->id)) {
                    $entity->delete();
                }
            }
        }
    }

    /**
     * Determine if list of entities has pages
     *
     * @param  int $limit
     * @param  int $typeId
     * @return boolean
     */
    public function hasPages($limit, $typeId = null)
    {
        return (null !== $typeId) ?
            (Table\Entities::findBy(['type_id' => $typeId])->count() > $limit) :
            (Table\Entities::findAll()->count() > $limit);
    }

    /**
     * Get count of entities
     *
     * @param  int $typeId
     * @return int
     */
    public function getCount($typeId = null)
    {
        return (null !== $typeId) ?
            Table\Entities::findBy(['type_id' => $typeId])->count() :
            Table\Entities::findAll()->count();
    }

    /**
     * Determine if the field is allowed for the entity type
     *
     * @param  array $models
     * @param  mixed $entity
     * @return boolean
     */
    public function isFieldAllowed($models, $entity)
    {
        $result = false;
        foreach ($models as $model) {
            if (!empty($model['type_field']) && !empty($model['type_value']) &&
                isset($entity[$model['type_field']]) && ($entity[$model['type_field']] == $model['type_value'])) {
                $result = true;
            } else if (empty($model['type_field']) && empty($model['type_value'])) {
                $result = true;
            }
        }

        return $result;
    }

}
