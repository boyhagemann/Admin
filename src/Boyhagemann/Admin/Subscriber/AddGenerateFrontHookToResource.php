<?php

namespace Boyhagemann\Admin\Subscriber;

use Illuminate\Events\Dispatcher as Events;
use Illuminate\Database\Eloquent\Model;
use Boyhagemann\Crud\CrudController;
use Boyhagemann\Form\FormBuilder;
use Boyhagemann\Pages\Model\Page;
use Input, Artisan;

class AddGenerateFrontHookToResource
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

		// When the form is posted, we need this field.
		// If it is not checked, then we don't have to do anything.
		if(!Input::get('create_front')) {
			return;
		}

		// Determine the controller class name
		$controller = Str::studly($model->title) . 'Controller';

		// Use a layout
		$layout = 'layouts.default';

		Artisan::call('controller:make', array(
			'name' => $controller,
			'--only' => 'index,show'
		));

		$urlIndex = str_replace('admin/', '', $model->url);
		$aliasIndex = str_replace('admin.', '', $model->alias);

		$urlShow = $urlIndex . '/{id}';
		$aliasShow = $urlIndex . 'show';

		$zone = 'content';
		$method = 'get';

		Page::createWithContent($model->title, $urlIndex, $controller . '@index', $layout, $zone, $method, $aliasIndex);
		Page::createWithContent($model->title, $urlShow, $controller . '@show', $layout, $zone, $method, $aliasShow);

	}

	/**
	 * @param FormBuilder $fb
	 */
	public function onBuildForm(FormBuilder $fb)
	{
		if($fb->getName() != 'Boyhagemann\Admin\Controller\ResourceController') {
			return;
		}

		$fb->checkbox('create_front')->label('Create front end pages?')->useModel(false);
	}

}