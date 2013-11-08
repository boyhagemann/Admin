<?php

namespace Boyhagemann\Admin\Model;

use Boyhagemann\Pages\Model\PageRepository;
use Event;

class ResourceRepository
{
	/**
	 * @param Resource $resource
	 * @param null     $title
	 * @return mixed
	 */
	static public function createWithPages(Array $data, $title = null)
	{
		$resource = Resource::create($data);

		if(!$title) {
			$title = $resource->title;
		}

		$pages = PageRepository::createResourcePages($title, $resource->controller);

		Event::fire('admin.model.resourceRepository.createWithPages', array($resource, $pages));

		return $pages;
	}
}

