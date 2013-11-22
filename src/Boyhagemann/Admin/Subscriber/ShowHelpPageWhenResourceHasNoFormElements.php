<?php

namespace Boyhagemann\Admin\Subscriber;

use Illuminate\Events\Dispatcher as Events;
use Boyhagemann\Crud\CrudController;
use View, Config;

class ShowHelpPageWhenResourceHasNoFormElements
{
	/**
	 * Register the listeners for the subscriber.
	 *
	 * @param Events $events
	 */
	public function subscribe(Events $events)
	{
		$events->listen('crudController.init', array($this, 'showHelp'));
	}

	/**
	 * @param CrudController $controller
	 */
	public function showHelp(CrudController $controller)
	{
		if($controller->getFormBuilder()->getElements()) {
			return;
		}

		Config::set('crud::config.view.create', 'admin::crud.help');
	}


}