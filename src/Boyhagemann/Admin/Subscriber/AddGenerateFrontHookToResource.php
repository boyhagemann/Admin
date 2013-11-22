<?php

namespace Boyhagemann\Admin\Subscriber;

use Illuminate\Events\Dispatcher as Events;
use Illuminate\Database\Eloquent\Model;
use Boyhagemann\Admin\Controller\ResourceController;
use Boyhagemann\Pages\Model\PageRepository;
use Boyhagemann\Content\Model\Block;
use Boyhagemann\Crud\CrudController;
use Boyhagemann\Form\FormBuilder;
use Input, Artisan, Str;

/**
 * Class AddGenerateFrontHookToResource
 *
 * With this event listener we can do several things:
 * - Generate an index page and a show page with the according content
 * - Generate a controller for the resource
 * - Generate an index view
 * - Generate a show view
 *
 * @package Boyhagemann\Admin\Subscriber
 */
class AddGenerateFrontHookToResource
{
	/**
	 * Register the listeners for the subscriber.
	 *
	 * @param Events $events
	 */
	public function subscribe(Events $events)
	{
		$events->listen('crud::saved', array($this, 'onSaved'));
		$events->listen('form.formBuilder.build.before', array($this, 'onBuildForm'));
	}

	/**
	 * @param Model          $model
	 * @param CrudController $controller
	 */
	public function onSaved(Model $model, CrudController $controller)
	{
		// We are only interested in a resource controller
		if(!$controller instanceof ResourceController) {
			return;
		}

		// When the form is posted, we need this field.
		// If it is not checked, then we don't have to do anything.
		if(!Input::get('create_front')) {
			return;
		}

		$this->generateController($model);
		$this->generateViewIndex($model);
		$this->generateViewShow($model);
		$this->generatePages($model);
	}

	/**
	 * @param Model $model
	 */
	protected function generateController(Model $model)
	{
		// Determine the controller class name
		$controller = Str::studly($model->title) . 'Controller';

		$template = file_get_contents(__DIR__ . '/../../../views/template/controller.txt');
		$template = str_replace('{repositoryClass}', Str::studly($model->title . 'Repository'), $template);
		$template = str_replace('{model}', lcfirst(Str::studly($model->title)), $template);
		$template = str_replace('{viewIndex}', Str::slug($model->title) . '.index', $template);
		$template = str_replace('{viewShow}', Str::slug($model->title) . '.show', $template);

		$filename = app_path('controllers/' . $controller . '.php');

		// Write the new controller file to the controller folder
		@mkdir(dirname($filename), 0755, true);
		file_put_contents($filename, $template);
	}

	/**
	 * @param Model $model
	 */
	protected function generateViewIndex(Model $model)
	{
		$template = file_get_contents(__DIR__ . '/../../../views/template/index.txt');
		$template = str_replace('{model}', lcfirst(Str::studly($model->title)), $template);
		$template = str_replace('{route}', Str::slug($model->title) . '.show', $template);

		$filename = app_path('views/' . Str::slug($model->title) . '/index.blade.php');

		// Write the new view file to the views folder
		@mkdir(dirname($filename), 0755, true);
		file_put_contents($filename, $template);
	}

	/**
	 * @param Model $model
	 */
	protected function generateViewShow(Model $model)
	{
		$template = file_get_contents(__DIR__ . '/../../../views/template/show.txt');
		$template = str_replace('{model}', lcfirst(Str::studly($model->title)), $template);
		$template = str_replace('{route}', Str::slug($model->title), $template);

		$filename = app_path('views/' . Str::slug($model->title) . '/show.blade.php');

		// Write the new view file to the views folder
		file_put_contents($filename, $template);
	}

	/**
	 * @param Model $model
	 */
	protected function generatePages(Model $model)
	{
		// Determine the controller class name
		$controller = Str::studly($model->title) . 'Controller';

		// Use a layout
		$layout = 'layouts.default';

		$urlIndex = Str::slug($model->title);
		$aliasIndex = str_replace('.', '', $urlIndex);

		$urlShow = $urlIndex . '/{id}';
		$aliasShow = $urlIndex . '.show';

		$zone = 'content';
		$method = 'get';


		PageRepository::createWithContent($model->title, $urlIndex, $controller . '@index', $layout, $method, $aliasIndex);
		PageRepository::createWithContent($model->title, $urlShow, $controller . '@show', $layout, $method, $aliasShow);

		Block::create(array(
			'title' => sprintf('List %s items', $model->title),
			'controller' => $controller . '@index',
		));

		Block::create(array(
			'title' => sprintf('Show %s', $model->title),
			'controller' => $controller . '@show',
		));
	}

	/**
	 * @param FormBuilder $fb
	 */
	public function onBuildForm(FormBuilder $fb)
	{
		if($fb->getName() != 'Boyhagemann\Admin\Controller\ResourceController') {
			return;
		}

		$fb->checkbox('create_front')
		    ->choices(array(1 => 'Create front end pages'))
			->map(false)
			->value(array(1))
			->help('This option will generate the appropriate controllers and views to display the resource on the website.
				It is a skeleton controller with just the basics to get you started.');
	}

}