<?php

namespace Boyhagemann\Admin\Subscriber;

use Boyhagemann\Crud\CrudController;
use Boyhagemann\Admin\Controller\ResourceController;
use Illuminate\Events\Dispatcher as Events;
use Boyhagemann\Navigation\Model\Node;
use Boyhagemann\Pages\Model\Page;
use NavigationContainersTableSeeder;
use Input, Str;

class AddDashboardNavigationForResource
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

        // Add a dashboard app pointing to the 'create' route of this resource
		Node::create(array(
			'title' => sprintf('Create %s', Str::lower($model->title)),
			'description' => Input::get('description'),
			'page_id' => Page::whereAlias(sprintf('admin.%s.create', Str::slug($model->title)))->first()->id,
			'icon_class' => 'icon-file',
			'container_id' => NavigationContainersTableSeeder::DASHBOARD,
		));

	}

}