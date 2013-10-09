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
		Config::package('boyhagemann/admin', 'admin');
	}

	public function boot()
	{
		$this->package('boyhagemann/admin', 'admin');

		Route::get('admin', array(
			'uses' => 'Boyhagemann\Admin\Controller\IndexController@dashboard',
			'as' => 'admin',
		));


		$me = $this;

		View::composer('admin::layouts.admin', function($layout) use($me) {

			$me->assignNavigation('menuLeft', $layout);
			$me->assignNavigation('menuRight', $layout);

		});

	}

	public function assignNavigation($name, $layout)
	{
		$key = 'admin::navigation.' . Route::currentRouteName();
		$nav = (array) Config::get($key);
		$params = Route::getCurrentRoute()->getParameters();

		if(!isset($nav[$name])) $nav[$name] = array();

		foreach($nav[$name] as &$item) {

			if(!isset($item['method'])) $item['method'] = 'get';
			$item['params'] = $params;
			$item['form'] = array(
				'route' => array($item['route']) + $params,
				'method' => $item['method'],
			);
		}

		$layout->$name = $nav[$name];
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