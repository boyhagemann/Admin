<?php

namespace Boyhagemann\Admin\Model;

use Cartalyst\Sentry\Users\UserInterface;
use Config, Str, App;

class PageFavoriteRepository
{

	static public function createDefaultsForUser(UserInterface $user)
	{
		$config = Config::get('admin::config.favorites');
		$matcher = App::make('Boyhagemann\Matcher\Container')->fromArray($config);
		$matcher->setDefault('user_id', $user->id);

		foreach(Page::all() as $page) {

			foreach($matcher->match($page) as $favorite) {
				PageFavorite::create($favorite);
			}

		}
	}

}

