<?php

namespace Phire\Entities\Model;

use Phire\Entities\Table;
use Phire\Model\AbstractModel;

class Entity extends AbstractModel
{

    /**
     * Get all entities
     *
     * @param  int                 $limit
     * @param  int                 $page
     * @param  string              $sort
     * @param  \Pop\Module\Manager $modules
     * @return array
     */
    public function getAll($limit = null, $page = null, $sort = null, \Pop\Module\Manager $modules = null)
    {
        $selectFields = [
            'id'      => DB_PREFIX . 'entities.id',
            'type_id' => DB_PREFIX . 'entities.type_id',
            'name'    => DB_PREFIX . 'entities.name'
        ];

        $sql = Table\Entities::sql();
        $sql->select($selectFields);

        $params = [
            'type_id' => $this->tid
        ];

        $order  = (null !== $sort) ? $this->getSortOrder($sort) : 'id DESC';
        $by     = explode(' ', $order);
        $sql->select()->orderBy($by[0], $by[1]);
        $sql->select()->where('type_id = :type_id');

        $rows = Table\Entities::execute((string)$sql, $params)->rows();

        $fieldNames = [];
        foreach ($rows as $i => $row) {
            $fieldNames = [];
            if ((null !== $modules) && ($modules->isRegistered('phire-fields'))) {
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
                            $fieldNames[$field->name] = $field->type;
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

                        $fieldNames[$field->name] = $field->type;

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
     * Get entity by ID
     *
     * @param  int $id
     * @return void
     */
    public function getById($id)
    {
        $entity = Table\Entities::findById($id);
        if (isset($entity->id)) {
            $data = $entity->getColumns();
            $this->data = array_merge($this->data, $data);
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
