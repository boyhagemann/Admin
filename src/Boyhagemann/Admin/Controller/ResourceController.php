<?php

namespace Boyhagemann\Admin\Controller;

use Boyhagemann\Crud\CrudController;
use Boyhagemann\Form\FormBuilder;
use Boyhagemann\Model\ModelBuilder;
use Boyhagemann\Overview\OverviewBuilder;
use Route, View, Input, App, Str, Artisan;

use Boyhagemann\Pages\Model\Page;
use Boyhagemann\Admin\Model\Resource;
use Boyhagemann\Admin\Model\App as AdminApp;


class ResourceController extends CrudController
{
	/**
	 * @param FormBuilder $fb
	 */
	public function buildForm(FormBuilder $fb)
	{
		$fb->text('title')->label('Title');
		$fb->text('controller')->rules('unique:resources');
		$fb->text('path');
	}

	/**
	 * @param ModelBuilder $mb
	 */
	public function buildModel(ModelBuilder $mb)
	{
		$mb->name('Boyhagemann\Admin\Model\Resource')->table('resources');
	}

	/**
	 * @param OverviewBuilder $ob
	 */
	public function buildOverview(OverviewBuilder $ob)
	{
		$ob->fields(array('title', 'controller', 'path'));
	}

	/**
	 * @return array
	 */
	public function config()
	{
		return array(
			'title' => 'Resource',
		);
	}

	/**
	 * @param Resource $resource
	 */
//	public function onSaved(Resource $resource)
//	{
//		if(Input::get('create_admin')) {
//			Event::fire('admin.resources.createAdmin', compact('resource'));
////			$pages = $this->generateAdmin($resource);
//		}
//
//		if(Input::get('create_app')) {
//			Event::fire('admin.resources.createApp', compact('resource'));
////			$alias = $pages['index']->alias;
////			if(!AdminApp::whereRoute($alias)->first()) {
////				AdminApp::create(array(
////					'title' => $resource->title,
////					'route' => $alias,
////					'icon_class' => 'icon-file',
////				));
////			}
//		}
//
//		if(Input::get('create_front')) {
//			Event::fire('admin.resources.createFront', compact('resource'));
////			$pages = $this->generateFront($resource);
//		}
//	}

}

