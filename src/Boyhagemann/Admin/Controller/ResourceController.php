<?php

namespace Boyhagemann\Admin\Controller;

use Boyhagemann\Crud\CrudController;
use Boyhagemann\Form\FormBuilder;
use Boyhagemann\Model\ModelBuilder;
use Boyhagemann\Overview\OverviewBuilder;
use Route, View, Input, App, Str;

use Pages\Layout;
use Pages\Section;
use Pages\Page;
use Pages\Block;
use Pages\Content;
use Navigation\Container;
use Navigation\Node;
use Admin\Resource;

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
        $resource = $this->getModel();
        $this->prepare($resource);
        $resource->save();        
    }
    
    /**
     * 
     * @param \Admin\Resource $resource
     */
    public function savePages(Resource $resource)
    {
        $controller = $resource->controller;
        $title = $resource->title;
        
        // Create pages
        foreach(array('index', 'create', 'store', 'edit', 'update', 'delete') as $action) {
                        
            $route = '/' . trim($resource->url, '/');
            if($action != 'index') {
                 $route .= '/' . $action;
		 $title = $action;
            }
	    else {
 		 $title = Str::plural($resource->title);
	    }

            
            $layout = Layout::whereName('admin::layouts.admin')->first();
            $section = Section::whereName('content')->first();
            
            $page = new Page;
            $page->title = $title;
            $page->route = $route;
            $page->layout()->associate($layout);
            $page->resource()->associate($resource);
            $page->save();
            
            $block = new Block;
            $block->title = $page->title;
            $block->controller = $controller . '@' . $action;
            $block->save();
            
            $content = new Content;
            $content->page()->associate($page);
            $content->section()->associate($section);
            $content->block()->associate($block);
            $content->save();
        }
        
    }
    
    public function saveNavigation(Resource $resource) 
    {
        $container = Container::whereName('admin')->first();
        $pages = $resource->pages;
        
        foreach($pages as $page) {
            
            $node = new Node;
            $node->title = $page->title;
            $node->route = $page->route;
            $node->container()->associate($container);            
            $node->save();
                        
            if(trim($page->route, '/') == $resource->url) {
                $root = $node;
            }
            else {
                $node->makeChildOf($root);                
            }
        }
        
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
        $mb->hasMany('Pages\Page')->alias('pages');
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

