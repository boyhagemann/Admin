<?php

namespace Boyhagemann\Admin\Controller;

use View, Config;

class IndexController extends \BaseController
{
	public function dashboard()
	{
		return View::make('admin::dashboard', array(
			'apps' => Config::get('admin::dashboard'),
		));
	}
}

