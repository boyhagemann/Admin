<?php

namespace Boyhagemann\Admin\Controller;

use Boyhagemann\Crud\CrudController;
use Boyhagemann\Form\FormBuilder;
use Boyhagemann\Model\ModelBuilder;
use Boyhagemann\Overview\OverviewBuilder;
use Boyhagemann\Navigation\Model\Container;

class DashboardController extends CrudController
{
    /**
     * @param FormBuilder $fb
     */
    public function buildForm(FormBuilder $fb)
    {
		$fb->text('title')->label('Title');
		$fb->modelSelect('page_id')->model('Boyhagemann\Pages\Model\Page')->label('Page');
		$fb->hidden('container_id')->value($this->getContainer()->id);
		$fb->text('icon_class')->label('Icon class');
    }

    /**
     * @param ModelBuilder $mb
     */
    public function buildModel(ModelBuilder $mb)
    {
        $mb->name('Boyhagemann\Navigation\Model\Node')->table('navigation_nodes');
    }

    /**
     * @param OverviewBuilder $ob
     */
    public function buildOverview(OverviewBuilder $ob)
    {
		$ob->getQueryBuilder()->whereContainerId($this->getContainer()->id);
    }

	/**
	 * @return Container
	 */
	public function getContainer()
	{
		return Container::whereName('dashboard')->first();
	}

}

