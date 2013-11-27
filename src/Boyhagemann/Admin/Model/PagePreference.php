<?php

namespace Boyhagemann\Admin\Model;

class PagePreference extends \Eloquent
{

    protected $table = 'page_preference';

    public $timestamps = false;

    public $rules = array();

    protected $guarded = array('id');

    protected $fillable = array(
        'user_id',
        'page_id',
        'color',
        'icon_class'
        );

}

