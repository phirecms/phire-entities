<?php

namespace Phire\Entities\Controller;

use Phire\Entities\Model;
use Phire\Entities\Form;
use Phire\Entities\Table;
use Phire\Controller\AbstractController;
use Pop\Paginator\Paginator;

class IndexController extends AbstractController
{

    /**
     * Index action method
     *
     * @return void
     */
    public function index()
    {
        $this->prepareView('entities/index.phtml');
        $entities = new Model\Entity();

        if ($entities->hasPages($this->config->pagination)) {
            $limit = $this->config->pagination;
            $pages = new Paginator($entities->getCount(), $limit);
            $pages->useInput(true);
        } else {
            $limit = null;
            $pages = null;
        }

        $this->view->title    = 'Entities';
        $this->view->pages    = $pages;
        $this->view->entities = $entities->getAll(
            $limit, $this->request->getQuery('page'), $this->request->getQuery('sort'), $this->application->modules()
        );

        $this->send();
    }

    /**
     * Add action method
     *
     * @return void
     */
    public function add()
    {
        $this->prepareView('entities/add.phtml');
        $this->view->title = 'Entities : Add';

        $fields = $this->application->config()['forms']['Phire\Entities\Form\Entity'];

        $this->view->form = new Form\Entity($fields);

        if ($this->request->isPost()) {
            $this->view->form->addFilter('htmlentities', [ENT_QUOTES, 'UTF-8'])
                 ->setFieldValues($this->request->getPost());

            if ($this->view->form->isValid()) {
                $this->view->form->clearFilters()
                     ->addFilter('html_entity_decode', [ENT_QUOTES, 'UTF-8'])
                     ->filter();
                $entity = new Model\Form();
                $entity->save($this->view->form->getFields());
                $this->view->id = $entity->id;
                $this->sess->setRequestValue('saved', true);
                $this->redirect(BASE_PATH . APP_URI . '/entities/edit/'. $entity->id);
            }
        }

        $this->send();
    }

    /**
     * Edit action method
     *
     * @param  int $id
     * @return void
     */
    public function edit($id)
    {
        $entity = new Model\Entity();
        $entity->getById($id);

        if (!isset($entity->id)) {
            $this->redirect(BASE_PATH . APP_URI . '/entities');
        }

        $this->prepareView('entities/edit.phtml');
        $this->view->title         = 'Entities';
        $this->view->entity_name = $entity->name;

        $fields = $this->application->config()['forms']['Phire\Entities\Form\Entity'];
        $fields[1]['name']['attributes']['onkeyup'] = 'phire.changeTitle(this.value);';

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
                $this->redirect(BASE_PATH . APP_URI . '/entities/edit/'. $entity->id);
            }
        }

        $this->send();
    }

    /**
     * Remove action method
     *
     * @return void
     */
    public function remove()
    {
        if ($this->request->isPost()) {
            $entity = new Model\Entity();
            $entity->remove($this->request->getPost());
        }
        $this->sess->setRequestValue('removed', true);
        $this->redirect(BASE_PATH . APP_URI . '/entities');
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
