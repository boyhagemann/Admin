<?php

namespace Boyhagemann\Admin\Subscriber;

use Boyhagemann\Crud\CrudController;
use Boyhagemann\Admin\Controller\ResourceController;
use Illuminate\Events\Dispatcher as Events;
use Illuminate\Database\Eloquent\Model;
use Boyhagemann\Form\FormBuilder;
use Boyhagemann\Form\Element;
use Boyhagemann\Navigation\Model\Node;
use Boyhagemann\Pages\Model\Page;
use NavigationContainersTableSeeder;
use Input, App, Str;

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
//		if(!Input::get('create_admin')) {
//			return;
//		}

		Node::create(array(
			'title' => sprintf('Create %s', Str::lower($model->title)),
			'description' => Input::get('description'),
			'page_id' => Page::whereAlias(sprintf('admin.%s.create', Str::slug($model->title)))->first()->id,
			'icon_class' => 'icon-file',
			'container_id' => NavigationContainersTableSeeder::DASHBOARD,
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

		$fb->textarea('description')
			->label('Description')
			->rows(3)
			->help('This will be presented in the admin dashboard to help you explain what this resource is about.');
	}

}