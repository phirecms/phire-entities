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
        $order = (null !== $sort) ? $this->getSortOrder($sort, $page) : 'id ASC';

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

        return $rows;
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
            'name' => $fields['name']
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
            $entity->name = $fields['name'];
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
        return (Table\Entities::findAll()->count() > $limit);
    }

    /**
     * Get count of entities
     *
     * @return int
     */
    public function getCount()
    {
        return Table\Entities::findAll()->count();
    }

}
