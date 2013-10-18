<?php

namespace Boyhagemann\Admin\Model;

class Resource extends \Eloquent
{

    protected $table = 'resources';

    public $timestamps = false;

    public $rules = array(
		'controller' => 'unique:resources,controller'
	);

    protected $guarded = array('id');

    protected $fillable = array(
        'title',
        'url',
        'controller',
        'path'
        );

}

