<?php namespace Boyhagemann\Admin;

use Illuminate\Support\ServiceProvider;
use Route, View, Artisan;

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
            $this->package('Boyhagemann\Admin', 'admin');
            
            $this->app->register('Boyhagemann\Pages\PagesServiceProvider');
            $this->app->register('Boyhagemann\Navigation\NavigationServiceProvider');  
	}

        public function boot()
        {    
            Route::get('admin', function() {
                return View::make('admin::index.index');
            });

	    foreach(\Admin\Resource::get() as $resource) {
		Route::resource($resource->url, $resource->controller);
	    }
            
            Route::get('admin/resources/import/{class}', 'Boyhagemann\Admin\Controller\ResourceController@import')->where('class', '(.*)');
            Route::get('admin/resources/scan', 'Boyhagemann\Admin\Controller\ResourceController@scan');         
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