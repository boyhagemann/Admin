<?php

namespace Boyhagemann\Admin\Subscriber;

use Illuminate\Events\Dispatcher as Events;
use Illuminate\Database\Eloquent\Model;
use Boyhagemann\Admin\Controller\ResourceController;
use Boyhagemann\Crud\CrudController;

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
class AddUniqueRuleToResource
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
		if(!$controller instanceof ResourceController) {
			return;
		}

		// Resource controller is unique, but can update itself, allow its id
		$model->rules['controller'] .= ',' . $model->id;
		$model->save();

	}


}