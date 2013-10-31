<?php

namespace Boyhagemann\Admin\Controller;

use Boyhagemann\Crud\CrudController;
use Boyhagemann\Form\FormBuilder;
use Boyhagemann\Model\ModelBuilder;
use Boyhagemann\Overview\OverviewBuilder;

class DashboardController extends CrudController
{
    /**
     * @param FormBuilder $fb
     */
    public function buildForm(FormBuilder $fb)
    {
		$fb->text('title')->label('Title');
		$fb->modelSelect('page_id')->model('Boyhagemann\Pages\Model\Page')->label('Page');
		$fb->modelSelect('container_id')->model('Boyhagemann\Navigation\Model\Container')->label('Navigation container');
		$fb->text('icon_class')->label('Icon class');
    }

    /**
     * @param ModelBuilder $mb
     */
    public function buildModel(ModelBuilder $mb)
    {
        $mb->name('Boyhagemann\Admin\Model\Node')->table('navigation_nodes');
    }

    /**
     * @param OverviewBuilder $ob
     */
    public function buildOverview(OverviewBuilder $ob)
    {
    }

}

