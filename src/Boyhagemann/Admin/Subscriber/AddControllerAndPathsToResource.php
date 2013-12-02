<?php

namespace Boyhagemann\Admin\Subscriber;

use Boyhagemann\Admin\Controller\ResourceController;
use Illuminate\Events\Dispatcher as Events;
use Illuminate\Database\Eloquent\Model;
use Boyhagemann\Crud\CrudController;
use Boyhagemann\Form\FormBuilder;
use Str;

class AddControllerAndPathsToResource
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

		// Resource controller is unique, but can update itself, allow its id
		$model->rules['controller'] .= ',' . $model->id;

		// Add data
		$model->controller = Str::studly($model->title) . 'Resource';
		$model->path = '../app/resources';
		$model->save();
	}

	/**
	 * @param FormBuilder $fb
	 */
	public function onBuildForm(FormBuilder $fb)
	{
		if($fb->getName() != 'Boyhagemann\Admin\Controller\ResourceController') {
			return;
		}

		$fb->hidden('controller');
		$fb->hidden('path');
	}

}