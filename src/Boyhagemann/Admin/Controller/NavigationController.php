<?php

namespace Boyhagemann\Admin\Controller;

use Boyhagemann\Admin\Model\NavigationNode as Node;
use Boyhagemann\Admin\Model\PagePreferenceRepository;
use View, Sentry;

class NavigationController extends \BaseController
{
	public function dashboard()
	{
		$nodes = Node::getChildrenByContainer('dashboard');

		return View::make('admin::navigation.dashboard', compact('nodes'));
	}

}

