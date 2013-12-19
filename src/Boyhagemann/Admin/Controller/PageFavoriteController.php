<?php

namespace Boyhagemann\Admin\Controller;

use Boyhagemann\Admin\Model\NavigationNode as Node;
use Boyhagemann\Admin\Model\PagePreferenceRepository;
use View, Sentry;

class PageFavoriteController extends \BaseController
{
	public function menu()
	{

		return View::make('admin::pageFavorite.menu');
	}

}

