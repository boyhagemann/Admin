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

		$fb->checkbox('create_admin')->label('Create admin pages?')->useModel(false)->value(array(1));
		$fb->checkbox('create_front')->label('Create front end pages?')->useModel(false);
		$fb->checkbox('create_app')->label('Create app?')->useModel(false);
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
	public function onSaved(Resource $resource, Array $input)
	{
		if(isset($input['create_admin'])) {
			$pages = $this->generateAdmin($resource);
		}

		if(isset($input['create_app'])) {
			AdminApp::create(array(
				'title' => $resource->title,
				'route' => $pages['index']->alias,
				'icon_class' => 'icon-file',
			));
		}

		if(isset($input['create_front'])) {
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

