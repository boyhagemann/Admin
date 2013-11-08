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
		$events->listen('formBuilder.build.post', array($this, 'onBuildForm'));
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
		$model->controller = 'Admin\\' . Str::studly($model->title) . 'Controller';
		$model->path = '../app/controllers';
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