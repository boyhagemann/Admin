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
		foreach(Page::all() as $page) {
			$preferences = static::findDefaults($page, $user);
			foreach($preferences[$user->id] as $pageId => $data) {
				PagePreference::create(array('page_id' => $pageId, 'user_id' => $user->id) + $data);
			}
		}
	}

	/**
	 * @param Page $user
	 */
	static public function createDefaultsForPage(Page $page)
	{
		foreach(Sentry::findAllUsers() as $user) {
			$preferences = static::findDefaults($page, $user);
			foreach($preferences[$user->id] as $pageId => $data) {
				PagePreference::create(array('page_id' => $pageId, 'user_id' => $user->id) + $data);
			}
		}
	}


	/**
	 * @param Page          $page
	 * @param UserInterface $user
	 * @return array
	 */
	static public function findDefaults(Page $page, UserInterface $user)
	{
		$preferences = array(
			$user->id => array(),
		);

		foreach(Config::get('admin::config.defaults') as $preset) {

			foreach($preset['match'] as $type => $match) {

				foreach($match as $phrase) {

					if(static::matchPage($page, $type, $phrase)) {

						// Check if there is a color preset
						if(isset($preset['color'])) {
							$preferences[$user->id][$page->id]['color'] = $preset['color'];
						}

						// Check if there is an icon preset
						if(isset($preset['icon_class'])) {
							$preferences[$user->id][$page->id]['icon_class'] = $preset['icon_class'];
						}

					}

				}

			}

		}

		return $preferences;
	}

	/**
	 * Check if the page field mathches the phrase
	 *
	 * @param Page   $page
	 * @param string $field
	 * @param string $phrase
	 * @return bool
	 */
	static protected function matchPage(Page $page, $field, $phrase)
	{
		// Compare the page field with the phrase that has a wildcard
		// symbol at the beginning, e.g. "*.create"
		if(Str::startsWith($phrase, '*')) {
			$match = substr($phrase, 1);
			return Str::endsWith($page->$field, $match);
		}

		// Compare the page field with the phrase that has a wildcard
		// symbol at the end, e.g. "admin.*"
		if(Str::endsWith($phrase, '*')) {
			$match = substr($phrase, 0, -1);

			return Str::startsWith($page->$field, $match);
		}

		// If the phrase does not have a wildcard, then we can
		// check if the phrase exactly matches the field on
		// the page.
		return $page->$field == $phrase;
	}

}

