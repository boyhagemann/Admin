<?php

namespace Boyhagemann\Admin\Controller;

use Boyhagemann\Admin\Model\App as AdminApp;
use View;

class IndexController extends \BaseController
{
    public function dashboard()
    {  
        return View::make('admin::index.dashboard', array(
                'apps' => AdminApp::all()
        ));
    }
}

