<?php

namespace Phire\Entities\Controller;

use Phire\Entities\Model;
use Phire\Entities\Form;
use Phire\Entities\Table;
use Phire\Controller\AbstractController;
use Pop\Data\Data;
use Pop\Paginator\Paginator;

class IndexController extends AbstractController
{

    /**
     * Index action method
     *
     * @param  int $tid
     * @return void
     */
    public function index($tid = null)
    {
        if (null === $tid) {
            $this->prepareView('entities/types.phtml');
            $type = new Model\EntityType();

            if ($type->hasPages($this->config->pagination)) {
                $limit = $this->config->pagination;
                $pages = new Paginator($type->getCount(), $limit);
                $pages->useInput(true);
            } else {
                $limit = null;
                $pages = null;
            }

            $this->view->title = 'Entities';
            $this->view->pages = $pages;
            $this->view->types = $type->getAll(
                $limit, $this->request->getQuery('page'), $this->request->getQuery('sort')
            );
        } else {
            $this->prepareView('entities/index.phtml');
            $entities = new Model\Entity(['tid' => $tid]);
            $type     = new Model\EntityType();
            $type->getById($tid);

            if (!isset($type->id)) {
                $this->redirect(BASE_PATH . APP_URI . '/entities');
            }

            if ($this->services['acl']->isAllowed($this->sess->user->role, 'entity-type-' . $type->id, 'index')) {
                if ($entities->hasPages($this->config->pagination)) {
                    $limit = $this->config->pagination;
                    $pages = new Paginator($entities->getCount(), $limit);
                    $pages->useInput(true);
                } else {
                    $limit = null;
                    $pages = null;
                }

                $ents = $entities->getAll(
                    $limit, $this->request->getQuery('page'), $this->request->getQuery('sort')
                );

                $this->view->title    = 'Entities : ' . $type->name;
                $this->view->pages    = $pages;
                $this->view->tid      = $tid;
                $this->view->fieldNum = $type->field_num;
                $this->view->fields   = $ents['fields'];
                $this->view->entities = $ents['rows'];
            } else {
                $this->redirect(BASE_PATH . APP_URI . '/entities');
            }
        }

        $this->send();
    }

    /**
     * Add action method
     *
     * @param  int $tid
     * @return void
     */
    public function add($tid)
    {
        $this->prepareView('entities/add.phtml');
        $this->view->title = 'Entities : Add';
        $this->view->tid   = $tid;

        $fields = $this->application->config()['forms']['Phire\Entities\Form\Entity'];
        $fields[1]['type_id']['value'] = $tid;

        $this->view->form = new Form\Entity($fields);

        if ($this->request->isPost()) {
            $this->view->form->addFilter('htmlentities', [ENT_QUOTES, 'UTF-8'])
                 ->setFieldValues($this->request->getPost());

            if ($this->view->form->isValid()) {
                $this->view->form->clearFilters()
                     ->addFilter('html_entity_decode', [ENT_QUOTES, 'UTF-8'])
                     ->filter();
                $entity = new Model\Entity();
                $entity->save($this->view->form->getFields());
                $this->view->id = $entity->id;
                $this->sess->setRequestValue('saved', true);
                $this->redirect(BASE_PATH . APP_URI . '/entities/edit/'. $tid . '/' . $entity->id);
            }
        }

        $this->send();
    }

    /**
     * Edit action method
     *
     * @param  int $tid
     * @param  int $id
     * @return void
     */
    public function edit($tid, $id)
    {
        $entity = new Model\Entity();
        $entity->getById($id);

        if (!isset($entity->id)) {
            $this->redirect(BASE_PATH . APP_URI . '/entities');
        }

        $this->prepareView('entities/edit.phtml');
        $this->view->title       = 'Entities';
        $this->view->entity_name = $entity->name;
        $this->view->tid         = $tid;

        $fields = $this->application->config()['forms']['Phire\Entities\Form\Entity'];
        $fields[1]['type_id']['value']              = $tid;
        $fields[0]['name']['attributes']['onkeyup'] = 'phire.changeTitle(this.value);';

        $this->view->form = new Form\Entity($fields);
        $this->view->form->addFilter('htmlentities', [ENT_QUOTES, 'UTF-8'])
             ->setFieldValues($entity->toArray());

        if ($this->request->isPost()) {
            $this->view->form->setFieldValues($this->request->getPost());

            if ($this->view->form->isValid()) {
                $this->view->form->clearFilters()
                     ->addFilter('html_entity_decode', [ENT_QUOTES, 'UTF-8'])
                     ->filter();
                $entity = new Model\Entity();

                $entity->update($this->view->form->getFields());
                $this->view->id = $entity->id;
                $this->sess->setRequestValue('saved', true);
                $this->redirect(BASE_PATH . APP_URI . '/entities/edit/'. $tid . '/' . $entity->id);
            }
        }

        $this->send();
    }

    /**
     * Export action method
     *
     * @param  int $tid
     * @return void
     */
    public function export($tid)
    {
        $entities = new Model\Entity(['tid' => $tid]);
        $type     = new Model\EntityType();
        $type->getById($tid);

        if (!isset($type->id)) {
            $this->redirect(BASE_PATH . APP_URI . '/entities');
        }

        if ($this->services['acl']->isAllowed($this->sess->user->role, 'entity-type-' . $type->id, 'export')) {
            $rows = $entities->getAllForExport();
            $data = new Data($rows);
            $data->serialize('csv', ['omit' => 'type_id']);
            $data->outputToHttp($type->name . '_' . date('Y-m-d') . '.csv');
        } else {
            $this->redirect(BASE_PATH . APP_URI . '/entities/'. $tid);
        }
    }

    /**
     * Remove action method
     *
     * @param  int $tid
     * @return void
     */
    public function remove($tid)
    {
        if ($this->request->isPost()) {
            $entity = new Model\Entity();
            $entity->remove($this->request->getPost());
        }
        $this->sess->setRequestValue('removed', true);
        $this->redirect(BASE_PATH . APP_URI . '/entities/' . $tid);
    }

    /**
     * Prepare view
     *
     * @param  string $entity
     * @return void
     */
    protected function prepareView($entity)
    {
        $this->viewPath = __DIR__ . '/../../view';
        parent::prepareView($entity);
    }

}
