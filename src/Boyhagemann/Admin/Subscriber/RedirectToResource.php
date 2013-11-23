<?php

namespace Boyhagemann\Admin\Subscriber;

use Illuminate\Events\Dispatcher as Events;
use Illuminate\Database\Eloquent\Model;
use Boyhagemann\Crud\CrudController;
use Boyhagemann\Admin\Controller\ResourceController;
use DeSmart\ResponseException\Exception as ResponseException;
use Redirect;

class RedirectToResource
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

		// Redirect to the newly created resource
		$route = $controller->getBaseRoute() . '.create';
		ResponseException::chain(Redirect::route($route))->fire();
	}

}