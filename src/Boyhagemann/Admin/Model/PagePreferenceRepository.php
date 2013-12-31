<?php

namespace Boyhagemann\Admin\Model;

use Boyhagemann\Pages\Model\Page;
use Cartalyst\Sentry\Users\UserInterface;
use Config, Str, App;

class PagePreferenceRepository
{
	/**
	 * @param UserInterface $user
	 */
	static public function createDefaultsForUser(UserInterface $user)
	{
		$config = Config::get('admin::config.preferences');
		$matcher = App::make('Boyhagemann\Matcher\Container')->fromArray($config);
		$matcher->setDefault('user_id', $user->id);

		foreach(Page::all() as $page) {

			foreach($matcher->match($page) as $preference) {
				PagePreference::create($preference);
			}

		}
	}

}

