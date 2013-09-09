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
		require_once __DIR__ . '/../../events.php';

		if(Config::get('database.connections.mysql.database') == 'database') {
			var_dump('No database set'); exit;
		}

		$this->package('Boyhagemann\Admin', 'admin');
            
        $this->app->register('Boyhagemann\Pages\PagesServiceProvider');
        $this->app->register('Boyhagemann\Navigation\NavigationServiceProvider');
	}

        public function boot()
        {
			Route::get('admin/resources/import/{class}', 'Boyhagemann\Admin\Controller\ResourceController@import')->where('class', '(.*)');
			Route::get('admin/resources/scan', 'Boyhagemann\Admin\Controller\ResourceController@scan');

			if(Schema::hasTable('resources')) {
				foreach(\Boyhagemann\Admin\Model\Resource::get() as $resource) {
					Route::resource($resource->url, $resource->controller);
				}
			}


			Route::filter('installed', function() {


				if(!Schema::hasTable('resources')) {

					Artisan::call('admin:install');

					return Redirect::to('admin');
				}

			});

			Route::when('admin/*', array('installed'));
			Route::when('*', array('blocks'));
        }
        
        /**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}