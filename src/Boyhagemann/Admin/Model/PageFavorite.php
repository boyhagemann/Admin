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

}

