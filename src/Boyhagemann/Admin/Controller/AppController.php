<?php

namespace Boyhagemann\Admin\Controller;

use Boyhagemann\Crud\CrudController;
use Boyhagemann\Form\FormBuilder;
use Boyhagemann\Model\ModelBuilder;
use Boyhagemann\Overview\OverviewBuilder;

class AppController extends CrudController
{
    /**
     * @param FormBuilder $fb
     */
    public function buildForm(FormBuilder $fb)
    {
		$fb->text('title')->label('Title');
		$fb->text('route')->label('Route');
		$fb->text('icon_class')->label('Icon class');
    }

    /**
     * @param ModelBuilder $mb
     */
    public function buildModel(ModelBuilder $mb)
    {
        $mb->name('Boyhagemann\Admin\Model\App')->table('admin_apps');
    }

    /**
     * @param OverviewBuilder $ob
     */
    public function buildOverview(OverviewBuilder $ob)
    {
    }


}

