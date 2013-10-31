<?php

namespace Boyhagemann\Admin\Controller;

use Boyhagemann\Navigation\Model\Node;
use View;

class NavigationController extends \BaseController
{
	public function dashboard()
	{
		$nodes = Node::getChildrenByContainer('dashboard');

		return View::make('admin::navigation.dashboard', compact('nodes'));
	}

}

