<?php

namespace Boyhagemann\Admin\Controller;

use Boyhagemann\Crud\CrudController;
use Boyhagemann\Form\FormBuilder;
use Boyhagemann\Model\ModelBuilder;
use Boyhagemann\Overview\OverviewBuilder;
use Route, View, Input, App, Str, Artisan;

use Boyhagemann\Pages\Model\Page;
use Boyhagemann\Admin\Model\Resource;
use Boyhagemann\Admin\Model\App as AdminApp;


class ResourceController extends CrudController
{
	/**
	 * @param FormBuilder $fb
	 */
	public function buildForm(FormBuilder $fb)
	{
		$fb->text('title')->label('Title');
		$fb->hidden('controller')->rules('unique:resources');
		$fb->hidden('url');
		$fb->hidden('path');

		$fb->checkbox('create_admin')->label('Create admin pages?')->useModel(false)->value(1);
		$fb->checkbox('create_front')->label('Create front end pages?')->useModel(false);
		$fb->checkbox('create_app')->label('Create app?')->useModel(false);
	}

	/**
	 * @param ModelBuilder $mb
	 */
	public function buildModel(ModelBuilder $mb)
	{
		$mb->name('Boyhagemann\Admin\Model\Resource')->table('resources');
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
		$title = Input::get('title');

		Input::merge(array(
			'controller' => 'Admin\\' . Str::studly($title) . 'Controller',
			'url' => 'admin/' . Str::slug($title),
			'path' => '../app/controllers',
		));

	}

	public function onUpdate(Resource $resource)
	{
		$resource->rules['controller'] .= ',' . $resource->id;
	}

	/**
	 * @param Resource $resource
	 */
	public function onSaved(Resource $resource)
	{
		if(Input::get('create_admin')) {
			$pages = $this->generateAdmin($resource);
		}

		if(Input::get('create_app')) {
			$alias = $pages['index']->alias;
			if(!AdminApp::whereRoute($alias)->first()) {
				AdminApp::create(array(
					'title' => $resource->title,
					'route' => $alias,
					'icon_class' => 'icon-file',
				));
			}
		}

		if(Input::get('create_front')) {
			$pages = $this->generateFront($resource);
		}
	}

	/**
	 * @param Resource $resource
	 * @return array
	 */
	protected function generateAdmin(Resource $resource)
	{
		$generator = App::make('Boyhagemann\Crud\ControllerGenerator');
		$generator->setClassName(str_replace(' ', '', $resource->title));
		$generator->setNamespace('Admin');

		$filename = $resource->path . '/' . str_replace('\\', '/', $resource->controller) . '.php';

		// Write the new controller file to the controller folder
		@mkdir(dirname($filename), 0755, true);
		file_put_contents($filename, $generator->generate());

		return Page::createResourcePages($resource->title, $resource->controller, $resource->url);
	}

	/**
	 * @param Resource $resource
	 * @return array
	 */
	protected function generateFront(Resource $resource)
	{
		$controller = Str::studly($resource->title) . 'Controller';
		$layout = 'layouts.default';

		Artisan::call('controller:make', array(
			'name' => $controller,
			'--only' => 'index,show'
		));

		$urlIndex = str_replace('admin/', '', $resource->url);
		$aliasIndex = str_replace('admin.', '', $resource->alias);

		$urlShow = $urlIndex . '/{id}';
		$aliasShow = $urlIndex . 'show';

		$zone = 'content';
		$method = 'get';

		Page::createWithContent($resource->title, $urlIndex, $controller, $layout, $zone, $method, $aliasIndex);
		Page::createWithContent($resource->title, $urlShow, $controller, $layout, $zone, $method, $aliasShow);
	}

}

