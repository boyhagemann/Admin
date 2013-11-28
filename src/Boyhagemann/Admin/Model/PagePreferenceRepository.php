<?php

namespace Boyhagemann\Admin\Model;

use Boyhagemann\Pages\Model\Page;
use Cartalyst\Sentry\Users\UserInterface;
use Config, Str;

class PagePreferenceRepository
{
	/**
	 * @param UserInterface $user
	 */
	static public function createDefaultsForUser(UserInterface $user)
	{
		$config = Config::get('admin::config');
		$pages = Page::all();

		$preferences = array();

		foreach(Config::get('admin::config.defaults') as $preset) {


			foreach($preset['filter'] as $type => $filter) {


				foreach($filter as $phrase) {

					foreach($pages as $page) {

						if(static::matchPage($page, $type, $phrase)) {

							if(isset($preset['color'])) {
								$preferences[$page->id]['color'] = $preset['color'];
							}
							if(isset($preset['icon_class'])) {
								$preferences[$page->id]['icon_class'] = $preset['icon_class'];
							}

						}
					}

				}
			}

			foreach($preferences as $pageId => $data) {
				PagePreference::create(array('page_id' => $pageId, 'user_id' => $user->id) + $data);
			}

		}

	}

	/**
	 * @param Page $page
	 * @param      $type
	 * @param      $phrase
	 * @return bool
	 */
	static protected function matchPage(Page $page, $type, $phrase)
	{
		if(Str::startsWith($phrase, '*')) {
			$match = substr($phrase, 1);
			return Str::endsWith($match, $page->$type);
		}
		elseif(Str::endsWith($phrase, '*')) {
			$match = substr($phrase, 0, -1);
			return Str::startsWith($match, $page->$type);
		}

		return $page->$type == $phrase;
	}

}

