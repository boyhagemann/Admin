<?php

namespace Boyhagemann\Admin\Subscriber;

use Illuminate\Events\Dispatcher as Events;
use Route, Request, Session, Redirect;

class SwitchContentMode
{
	/**
	 * Register the listeners for the subscriber.
	 *
	 * @param Events $events
	 */
	public function subscribe(Events $events)
	{
		if(Request::ajax()) {
			return;
		}

		Route::before(function() {

			switch(Request::get('mode')) {

				case 'view':
					Session::put('mode', 'view');
					return Redirect::refresh();
					break;

				case 'content':
					Session::put('mode', 'content');
					return Redirect::refresh();
					break;
			}

			if(!Session::get('mode')) {
				Session::put('mode', 'view');
			}

		});

	}



}