<?php

namespace Boyhagemann\Admin\Model;

class PageFavorite extends \Eloquent
{

    protected $table = 'page_favorite';

    public $timestamps = false;

    public $rules = array();

    protected $guarded = array('id');

    protected $fillable = array(
        'user_id',
        'page_id',
        'order',
	);

	/**
	 * @param $value
	 * @return string
	 */
	public function getColorAttribute($value)
	{
		$preference = $this->page->userPreference;
		return $preference && $preference->color ? $preference->color : '#31b0d5';
	}

	/**
	 * @param $value
	 * @return string
	 */
	public function getIconClassAttribute($value)
	{
		$preference = $this->page->userPreference;
		return $preference && $preference->icon_class ? $preference->icon_class : 'icon-file';
	}

	/**
	 * @param $value
	 * @return array
	 */
	public function getParamsAttribute($value)
	{
		if(!$value) {
			return array();
		}

		return unserialize($value);
	}

	public function setParamsAttribute(Array $value = array())
	{
		$this->attributes['params'] = serialize($value);
	}

	/**
	 * @return Page
	 */
	public function page()
	{
		return $this->belongsTo('Boyhagemann\Admin\Model\Page');
	}

}

