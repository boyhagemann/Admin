<?php

namespace Boyhagemann\Admin\Controller;

use View;

class IndexController extends \BaseController
{
	public function index()
	{
		return View::make('admin::index.index');
	}
}

