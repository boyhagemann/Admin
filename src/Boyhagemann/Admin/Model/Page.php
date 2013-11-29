<?php

namespace Boyhagemann\Admin\Model;

use Boyhagemann\Pages\Model\Page as BasePage;
use Sentry;

class Page extends BasePage
{
	/**
	 * @return Boyhagemann\Admin\Model\PagePreference
	 */
	public function userPreference()
	{
		$userId = Sentry::check() ? Sentry::getUser()->id : -1;

		return $this->hasOne('Boyhagemann\Admin\Model\PagePreference', 'page_id')->where('user_id', '=', $userId);
	}

}

