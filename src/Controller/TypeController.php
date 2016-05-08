<?php

namespace Phire\Entities\Controller;

use Phire\Entities\Model;
use Phire\Entities\Form;
use Phire\Entities\Table;
use Phire\Controller\AbstractController;
use Pop\Paginator\Paginator;

class TypeController extends AbstractController
{

    /**
     * Index action method
     *
     * @return void
     */
    public function index()
    {
        $this->prepareView('entities/types/index.phtml');
        $types = new Model\EntityType();

        if ($types->hasPages($this->config->pagination)) {
            $limit = $this->config->pagination;
            $pages = new Paginator($types->getCount(), $limit);
            $pages->useInput(true);
        } else {
            $limit = null;
            $pages = null;
        }

        $this->view->title = 'Entity Types';
        $this->view->pages = $pages;
        $this->view->types = $types->getAll(
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
        $this->prepareView('entities/types/add.phtml');
        $this->view->title = 'Entity Types : Add';

        $fields = $this->application->config()['forms']['Phire\Entities\Form\EntityType'];

        $this->view->form = new Form\EntityType($fields);

        if ($this->request->isPost()) {
            $this->view->form->addFilter('htmlentities', [ENT_QUOTES, 'UTF-8'])
                 ->setFieldValues($this->request->getPost());

            if ($this->view->form->isValid()) {
                $this->view->form->clearFilters()
                     ->addFilter('html_entity_decode', [ENT_QUOTES, 'UTF-8'])
                     ->filter();
                $type = new Model\EntityType();
                $type->save($this->view->form->getFields());
                $this->view->id = $type->id;
                $this->sess->setRequestValue('saved', true);
                $this->redirect(BASE_PATH . APP_URI . '/entities/types/edit/'. $type->id);
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
        $type = new Model\EntityType();
        $type->getById($id);

        if (!isset($type->id)) {
            $this->redirect(BASE_PATH . APP_URI . '/entities');
        }

        $this->prepareView('entities/types/edit.phtml');
        $this->view->title            = 'Entity Types';
        $this->view->entity_type_name = $type->name;

        $fields = $this->application->config()['forms']['Phire\Entities\Form\EntityType'];
        $fields[1]['name']['attributes']['onkeyup'] = 'phire.changeTitle(this.value);';

        $this->view->form = new Form\EntityType($fields);
        $this->view->form->addFilter('htmlentities', [ENT_QUOTES, 'UTF-8'])
             ->setFieldValues($type->toArray());

        if ($this->request->isPost()) {
            $this->view->form->setFieldValues($this->request->getPost());

            if ($this->view->form->isValid()) {
                $this->view->form->clearFilters()
                     ->addFilter('html_entity_decode', [ENT_QUOTES, 'UTF-8'])
                     ->filter();
                $type = new Model\EntityType();

                $type->update($this->view->form->getFields());
                $this->view->id = $type->id;
                $this->sess->setRequestValue('saved', true);
                $this->redirect(BASE_PATH . APP_URI . '/entities/types/edit/'. $type->id);
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
            $type = new Model\EntityType();
            $type->remove($this->request->getPost());
        }
        $this->sess->setRequestValue('removed', true);
        $this->redirect(BASE_PATH . APP_URI . '/entities/types');
    }

    /**
     * Prepare view
     *
     * @param  string $type
     * @return void
     */
    protected function prepareView($type)
    {
        $this->viewPath = __DIR__ . '/../../view';
        parent::prepareView($type);
    }

}
