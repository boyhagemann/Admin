<?php

namespace Boyhagemann\Admin\Subscriber;

use Illuminate\Events\Dispatcher as Events;
use Boyhagemann\Admin\Model\Resource;
use DeSmart\ResponseException\Exception as ResponseException;
use Redirect, Route;

class RedirectToResource
{
	/**
	 * Register the listeners for the subscriber.
	 *
	 * @param Events $events
	 */
	public function subscribe(Events $events)
	{
		$events->listen('admin.model.resourceRepository.createWithPages', array($this, 'onCreatedResourcePages'));
	}

	/**
	 * @param Resource $resource
	 * @param array    $pages
	 */
	public function onCreatedResourcePages(Resource $resource, Array $pages)
	{
		// Don't use this in an artisan command
		if(!Route::getCurrentRoute()) {
			return;
		}

		\dd('test');

		ResponseException::chain(Redirect::route($pages['create']['alias']))->fire();
	}

}