<?php

namespace Boyhagemann\Admin\Controller;

use Boyhagemann\Crud\CrudController;
use Boyhagemann\Form\FormBuilder;
use Boyhagemann\Model\ModelBuilder;
use Boyhagemann\Overview\OverviewBuilder;
use Route, View, Input, App, Str;

//use Boyhagemann\Pages\Model\Layout;
//use Boyhagemann\Pages\Model\Section;
//use Boyhagemann\Pages\Model\Page;
//use Boyhagemann\Pages\Model\Block;
//use Boyhagemann\Pages\Model\Content;
use Boyhagemann\Admin\Model\Resource;

//use Boyhagemann\Navigation\Model\Container;
//use Boyhagemann\Navigation\Model\Node;

class ResourceController extends CrudController
{
	/**
	 * @param FormBuilder $fb
	 */
	public function buildForm(FormBuilder $fb)
	{
		$fb->text('title')
		   ->label('Title');

		$fb->text('controller')
			->label('Controller');

		$fb->text('url')
			->label('Url to the resource overview')
			->placeholder('What should be the url pointing to your resource? An example would be \'admin/news\'.');

		$fb->text('path')
			->label('Path')
			->value('../app/controllers')
			->help('This is the folder where the controller is being generated in. It defaults to
					the application controllers folder');
	}

	/**
	 * @param ModelBuilder $mb
	 */
	public function buildModel(ModelBuilder $mb)
	{
		$mb->name('Boyhagemann\Admin\Model\Resource')->table('resources');
		$mb->autoGenerate();
	}

	/**
	 * @param OverviewBuilder $ob
	 */
	public function buildOverview(OverviewBuilder $ob)
	{
		$ob->fields(array('title', 'url', 'controller'));
	}

	/**
	 * @return array
	 */
	public function config()
	{
		return array(
			'title' => 'Resource',
		);
	}

	/**
	 * @param Resource $resource
	 */
	public function onCreate(Resource $resource)
	{
		$generator = App::make('Boyhagemann\Crud\ControllerGenerator');
		$generator->setClassName(str_replace(' ', '', $resource->title));

		$filename = $resource->path . '/' . str_replace('\\', '/', $resource->controller) . '.php';

		// Write the new controller file to the controller folder
		@mkdir(dirname($filename), 0755, true);
		file_put_contents($filename, $generator->generate());

		// Add resource route to routes.php
		$line = sprintf(PHP_EOL . 'Route::resource(\'%s\', \'%s\');', $resource->url, $resource->controller);
		file_put_contents(app_path() . '/routes.php', $line, FILE_APPEND);

	}

//    public function scan()
//    {
//        $scanner = new \Boyhagemann\Crud\Scanner;
//        $controllers = $scanner->scanForControllers(array('../app/controllers', '../workbench', '../vendor'));
//
//        return View::make('admin::resource/scan', compact('controllers'));
//    }
//
//    public function import($class)
//    {
//        $controller = $this->getController($class);
//        $this->formBuilder->get('title')->value($controller->getModelBuilder()->getName());
//        $this->formBuilder->get('controller')->value(get_class($controller));
//
//        $form = $this->getForm();
//        $model = $this->getModel();
//        $route = 'admin.resources';
//
//        return View::make('admin::resource/import', compact('form', 'model', 'route'));
//    }
//
//	public function copy($id)
//	{
//		return View::make('admin::resource/copy', compact('id'));
//	}
//
//    /**
//     *
//     * @param type $title
//     * @param type $url
//     * @param type $controller
//     */
//    public function save($title, $url, $controller)
//    {
//        // Add it to the database
//        Input::replace(compact('title', 'url', 'controller'));
//        $resource = $this->getModel();
//        $this->prepare($resource);
//        $resource->save();
//    }
//
//    /**
//     *
//     * @param \Boyhagemann\Admin\Model\Resource $resource
//     */
//    public function savePages(Resource $resource)
//    {
//        $controller = $resource->controller;
//        $title = $resource->title;
//        $var = substr($resource->url, strrpos($resource->url, '/') + 1);
//
//        // Create pages
//        foreach(array('index', 'create', 'store', 'edit', 'update', 'delete') as $action) {
//
//			$route = '/' . trim($resource->url, '/');
//			$title = $action;
//			$match = null;
//			$method = 'get';
//
//			switch($action) {
//
//				case 'index':
//					$title = Str::plural($resource->title);
//					break;
//
//				case 'create':
//					$route .= sprintf('/%s', $action);
//					break;
//
//				case 'store':
//					$method = 'post';
//					$route .= sprintf('/{%s}/%s', $var, $action);
//					break;
//
//				case 'edit':
//					$route .= sprintf('/{%s}/%s', $var, $action);
//					$match = array('id' => $var);
//					break;
//
//				case 'update':
//					$method = 'patch';
//					$route .= sprintf('/{%s}/%s', $var, $action);
//					$match = array('id' => $var);
//					break;
//
//				case 'delete':
//					$method = 'delete';
//					$route .= sprintf('/{%s}/%s', $var, $action);
//					$match = array('id' => $var);
//					break;
//			}
//
//			$page = Page::createWithContent($title, $route, $controller . '@' . $action, $method, 'admin::layouts.admin', 'content', null, $match);
//			$page->resource()->associate($resource);
//			$page->save();
//        }
//
//    }
//
//    public function saveNavigation(Resource $resource, Node $baseNode = null)
//    {
//        $container = Container::whereName('admin')->first();
//        $pages = $resource->pages;
//
//        foreach($pages as $page) {
//
//            $node = new Node;
//            $node->title = $page->title;
//			$node->page()->associate($page);
//			$node->container()->associate($container);
//            $node->save();
//
//            if(trim($page->route, '/') == $resource->url) {
//				$root = $node;
//
//				if($baseNode) {
//					$root->makeChildOf($baseNode);
//				}
//            }
//            else {
//                $node->makeChildOf($root);
//            }
//        }
//
//    }
//
//
//    /**
//     *
//     * @param string $key
//     * @return CrudController
//     */
//    protected function getController($key)
//    {
//        $class = str_replace('/', '\\', $key);
//        return \App::make($class);
//    }

}

