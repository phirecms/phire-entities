<?php

namespace Phire\Entities\Model;

use Phire\Entities\Table;
use Phire\Model\AbstractModel;

class Entity extends AbstractModel
{

    /**
     * Get all entities
     *
     * @param  int    $limit
     * @param  int    $page
     * @param  string $sort
     * @return array
     */
    public function getAll($limit = null, $page = null, $sort = null)
    {
        $order = (null !== $sort) ? $this->getSortOrder($sort, $page) : 'id ASC';

        if (null !== $limit) {
            $page = ((null !== $page) && ((int)$page > 1)) ?
                ($page * $limit) - $limit : null;

            $rows = Table\Entities::findBy(['type_id' => $this->tid], [
                'offset' => $page,
                'limit'  => $limit,
                'order'  => $order
            ])->rows();
        } else {
            $rows = Table\Entities::findBy(['type_id' => $this->tid], ['order' => $order])->rows();
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
                    if ($field->storage == 'eav') {
                        $fv = \Phire\Fields\Table\FieldValues::findBy([
                            'field_id' => $field->id,
                            'model_id' => $row->id,
                            'model'    => 'Phire\Entities\Model\Entity'
                        ]);
                        foreach ($fv->rows() as $fv) {
                            if (!array_key_exists($field->name, $fieldNames)) {
                                $fieldNames[$field->name] = $field->type;
                            }
                            $rows[$i][$field->name]   = json_decode($fv->value, true);
                        }
                    } else {
                        $fv = new \Pop\Db\Record();
                        $fv->setPrefix(DB_PREFIX)
                            ->setPrimaryKeys(['id'])
                            ->setTable('field_' . $field->name);

                        $fv->findRecordsBy([
                            'model_id' => $row->id,
                            'model'    => 'Phire\Entities\Model\Entity',
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

        return ['rows' => $rows, 'fields' => $fieldNames];
    }


    /**
     * Get all entities for export
     *
     * @return array
     */
    public function getAllForExport()
    {
        $rows = $this->getAll()['rows'];

        foreach ($rows as $key => $value) {
            foreach($value as $k => $v) {
                if (is_array($v)) {
                    $value[$k] = implode(', ', $v);
                }
            }
            $rows[$key] = (array)$value;
        }

        return $rows;
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
            $this->tid = $entityType->id;
            $entities  = $this->getAll($limit, $page, $sort);
            $rows      = $entities['rows'];
        }

        return $rows;
    }

    /**
     * Get entity by ID
     *
     * @param  int   $id
     * @param  array $filters
     * @return void
     */
    public function getById($id, array $filters = [])
    {
        $entity = Table\Entities::findById($id);
        if (isset($entity->id)) {
            $data = $entity->getColumns();
            $this->data = array_merge($this->data, $data);

            if (class_exists('Phire\Fields\Model\FieldValue')) {
                $entity     = \Phire\Fields\Model\FieldValue::getModelObjectValues($this, null, $filters);
                $data       = $entity->toArray();
                $this->data = array_merge($this->data, $data);
            }
        }
    }

    /**
     * Get entity by name
     *
     * @param  string $name
     * @param  array  $filters
     * @return void
     */
    public function getByName($name, array $filters = [])
    {
        $entity = Table\Entities::findBy(['name' => $name]);
        if (isset($entity->id)) {
            $this->getById($entity->id);

            if (class_exists('Phire\Fields\Model\FieldValue')) {
                $entity     = \Phire\Fields\Model\FieldValue::getModelObjectValues($this, null, $filters);
                $data       = $entity->toArray();
                $this->data = array_merge($this->data, $data);
            }
        }
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
            'name'    => $fields['name'],
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
            $entity->name    = $fields['name'];
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
     * @return boolean
     */
    public function hasPages($limit)
    {
        return (isset($this->data['tid'])) ?
            (Table\Entities::findBy(['type_id' => $this->data['tid']])->count() > $limit) :
            (Table\Entities::findAll()->count() > $limit);
    }

    /**
     * Get count of entities
     *
     * @return int
     */
    public function getCount()
    {
        return (isset($this->data['tid'])) ?
            Table\Entities::findBy(['type_id' => $this->data['tid']])->count() :
            Table\Entities::findAll()->count();
    }

}
