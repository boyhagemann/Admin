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
use Input, App, Redirect, Route, Event;

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

		// Get the newly create controller and get the modelBuilder
		// We need to trigger the model generate event so that the model is
		// actually generated
		$crud = App::make($model->controller);
		Event::fire('model.builder.generate', $crud->init('create')->getModelBuilder());

	}
}