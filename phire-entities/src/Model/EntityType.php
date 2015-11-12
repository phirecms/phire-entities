<?php

namespace Phire\Entities\Model;

use Phire\Entities\Table;
use Phire\Model\AbstractModel;

class EntityType extends AbstractModel
{

    /**
     * Get all entity types
     *
     * @param  int    $limit
     * @param  int    $page
     * @param  string $sort
     * @return array
     */
    public function getAll($limit = null, $page = null, $sort = null)
    {
        $order = (null !== $sort) ? $this->getSortOrder($sort, $page) : 'order ASC';

        if (null !== $limit) {
            $page = ((null !== $page) && ((int)$page > 1)) ?
                ($page * $limit) - $limit : null;

            $rows = Table\EntityTypes::findAll([
                'offset' => $page,
                'limit'  => $limit,
                'order'  => $order
            ])->rows();
        } else {
            $rows = Table\EntityTypes::findAll(['order' => $order])->rows();
        }

        return $rows;
    }

    /**
     * Get entity type by ID
     *
     * @param  int $id
     * @return void
     */
    public function getById($id)
    {
        $type = Table\EntityTypes::findById($id);
        if (isset($type->id)) {
            $data = $type->getColumns();
            $this->data = array_merge($this->data, $data);
        }
    }

    /**
     * Save new entity type
     *
     * @param  array $fields
     * @return void
     */
    public function save(array $fields)
    {
        $type = new Table\EntityTypes([
            'name'      => $fields['name'],
            'order'     => (int)$fields['order'],
            'field_num' => (int)$fields['field_num']
        ]);
        $type->save();

        $this->data = array_merge($this->data, $type->getColumns());
    }

    /**
     * Update an existing entity type
     *
     * @param  array $fields
     * @return void
     */
    public function update(array $fields)
    {
        $type = Table\EntityTypes::findById((int)$fields['id']);
        if (isset($type->id)) {
            $type->name      = $fields['name'];
            $type->order     = (int)$fields['order'];
            $type->field_num = (int)$fields['field_num'];
            $type->save();

            $this->data = array_merge($this->data, $type->getColumns());
        }
    }

    /**
     * Remove an entity type
     *
     * @param  array $fields
     * @return void
     */
    public function remove(array $fields)
    {
        if (isset($fields['rm_entity_types'])) {
            foreach ($fields['rm_entity_types'] as $id) {
                $type = Table\EntityTypes::findById((int)$id);
                if (isset($type->id)) {
                    $type->delete();
                }
            }
        }
    }

    /**
     * Determine if list of entity types has pages
     *
     * @param  int $limit
     * @return boolean
     */
    public function hasPages($limit)
    {
        return (Table\EntityTypes::findAll()->count() > $limit);
    }

    /**
     * Get count of entity types
     *
     * @return int
     */
    public function getCount()
    {
        return Table\EntityTypes::findAll()->count();
    }

}
