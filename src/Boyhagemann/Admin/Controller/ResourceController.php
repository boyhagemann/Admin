<?php

namespace Boyhagemann\Admin\Controller;

use Boyhagemann\Crud\CrudController;
use Boyhagemann\Form\FormBuilder;
use Boyhagemann\Model\ModelBuilder;
use Boyhagemann\Overview\OverviewBuilder;
use Route, View, Input, App;

class ResourceController extends CrudController
{

    public function scan()
    {
        $scanner = new \Boyhagemann\Crud\Scanner;
        $controllers = $scanner->scanForControllers(array('../app/controllers', '../workbench', '../vendor'));
        
        return View::make('admin::resource/scan', compact('controllers'));
    }
    
    public function import($class)
    {
        $controller = $this->getController($class);  
        $this->formBuilder->get('title')->value($controller->getModelBuilder()->getName());
        $this->formBuilder->get('controller')->value(get_class($controller));
        
        $form = $this->getForm();
        $model = $this->getModel();
        $route = 'admin.resources';
        
        return View::make('crud::crud/create', compact('form', 'model', 'route'));
    }
    
    /**
     * 
     * @param type $title
     * @param type $url
     * @param type $controller
     */
    public function save($title, $url, $controller)
    {        
        // Add it to the database
        Input::replace(compact('title', 'url', 'controller'));         
        $model = $this->getModel();
        $this->prepare($model);
        $model->save();
    }

    /**
     * @param FormBuilder $fb
     */
    public function buildForm(FormBuilder $fb)
    {
        $fb->text('title')->label('Title');
        $fb->text('url')->label('Url');
        $fb->text('controller')->label('Controller');
    }

    /**
     * @param ModelBuilder $mb
     */
    public function buildModel(ModelBuilder $mb)
    {
        $mb->name('Admin\Resource')->table('resources');
    }

    /**
     * @param OverviewBuilder $ob
     */
    public function buildOverview(OverviewBuilder $ob)
    {
        $ob->fields(array('title', 'url', 'controller'));
    }


    /**
     * 
     * @param string $key
     * @return CrudController
     */
    protected function getController($key)
    {
        $class = str_replace('/', '\\', $key);
        return \App::make($class);        
    }

}

