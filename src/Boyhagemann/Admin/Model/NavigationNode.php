<?php

namespace Boyhagemann\Admin\Model;

use Boyhagemann\Navigation\Model\Node;
use Sentry;

class NavigationNode extends Node
{

	public function getColorAttribute($value)
	{
		return $this->userPreference ? $this->userPreference->color : '#31b0d5';
	}

	public function getIconClassAttribute($value)
	{
		return $this->userPreference ? $this->userPreference->icon_class : 'icon-file';
	}

	public function userPreference()
	{
		$userId = Sentry::check() ? Sentry::getUser()->id : -1;

		return $this->page->hasOne('Boyhagemann\Admin\Model\PagePreference', 'page_id')->where('user_id', '=', $userId);
	}
}

