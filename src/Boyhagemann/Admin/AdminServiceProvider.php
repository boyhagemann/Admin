<?php namespace Boyhagemann\Admin;

use Illuminate\Support\ServiceProvider;
use Route, View, Artisan, Schema, Config, Redirect;

class AdminServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
	}

	public function boot()
	{
		$this->package('boyhagemann/admin', 'admin');

		Route::get('admin', array(
			'uses' => 'Boyhagemann\Admin\Controller\IndexController@dashboard',
			'as' => 'admin',
		));


		View::composer('admin::layouts.admin', function($layout) {

			$nav = (array) Config::get('admin/navigation.' . Route::currentRouteName());
			$params = Route::getCurrentRoute()->getParameters();

			if(!isset($nav['left'])) $nav['left'] = array();
			if(!isset($nav['right'])) $nav['right'] = array();

			foreach($nav['left'] as &$item) {

				if(!isset($item['method'])) $item['method'] = 'get';
				$item['params'] = $params;
				$item['form'] = array(
					'route' => array($item['route']) + $params,
					'method' => $item['method'],
				);
			}

			foreach($nav['right'] as &$item) {

				if(!isset($item['method'])) $item['method'] = 'get';
				$item['params'] = $params;
				$item['form'] = array(
					'route' => array($item['route']) + $params,
					'method' => $item['method'],
				);
			}

			$layout->menuLeft = $nav['left'];
			$layout->menuRight = $nav['right'];

		});

	}
        
    /**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('admin');
	}

}