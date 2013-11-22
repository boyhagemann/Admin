<?php

namespace Boyhagemann\Admin\Subscriber;

use Boyhagemann\Admin\Controller\ResourceController;
use Boyhagemann\Admin\Model\ResourceRepository;
use Illuminate\Events\Dispatcher as Events;
use Illuminate\Database\Eloquent\Model;
use Boyhagemann\Crud\CrudController;
use Boyhagemann\Form\FormBuilder;
use Boyhagemann\Pages\Model\PageRepository;
use DeSmart\ResponseException\Exception as ResponseException;
use Input, App, Redirect, Route;

class AddGenerateAdminHookToResource
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
		if(!Input::get('create_admin')) {
			return;
		}

		// Start up the generator
		$generator = App::make('Boyhagemann\Crud\ControllerGenerator');
		$generator->setClassName(str_replace(' ', '', $model->title));
		$generator->setNamespace('Admin');

		// Determine the file name
		$filename = $model->path . '/' . str_replace('\\', '/', $model->controller) . '.php';

		// Write the new controller file to the controller folder
		@mkdir(dirname($filename), 0755, true);
		file_put_contents($filename, $generator->generate());

		// Create the resource pages
		$pages = PageRepository::createResourcePages($model->title, $model->controller);

		// Redirect to the newly created resource
		ResponseException::chain(Redirect::route($pages['create']['alias']))->fire();
	}

	/**
	 * @param FormBuilder $fb
	 */
	public function onBuildForm(FormBuilder $fb)
	{
		if($fb->getName() != 'Boyhagemann\Admin\Controller\ResourceController') {
			return;
		}

		$fb->checkbox('create_admin')
			->choices(array(1 => 'Create admin pages'))
			->map(false)
			->value(array(1))
			->help('This will generate the admin controller and route to manage your resource.
					This is the place where you can add form elements for the resource.
					When checked, you will be redirected to your resource page after saving.');
	}
}