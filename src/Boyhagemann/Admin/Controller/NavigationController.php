<?php

namespace Boyhagemann\Admin\Controller;

use Boyhagemann\Admin\Model\NavigationNode as Node;
use Boyhagemann\Admin\Model\PageFavorite;
use View, Sentry;

class NavigationController extends \BaseController
{
	public function dashboard()
	{
		$q = Node::getChildrenByContainerQuery('dashboard');
        
        // Optimize the query, eager load the user preferences
        $nodes = $q->with('page', 'page.userPreference')->get();

		return View::make('admin::navigation.dashboard', compact('nodes'));
	}

	public function favorites()
	{
		if(!Sentry::check()) {
			return;
		}

		$nodes = PageFavorite::whereUserId(Sentry::getUser()->id)->with('page', 'page.userPreference')->get();

		return View::make('admin::navigation.favorites', compact('nodes'));
	}

}

