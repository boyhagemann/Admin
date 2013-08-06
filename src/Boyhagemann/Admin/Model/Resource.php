<?php

namespace Boyhagemann\Admin\Model;

class Resource extends \Eloquent
{

    protected $table = 'resources';

    public $timestamps = false;

    public $rules = array();

    protected $guarded = array('id');

    protected $fillable = array(
        'title',
        'url',
        'controller'
        );

    /**
     * @return \Boyhagemann\Pages\Model\Page
     */
    public function pages()
    {
        return $this->hasMany('Boyhagemann\Pages\Model\Page');
    }


}

