<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| routing array
	|--------------------------------------------------------------------------
	|
	| This is passed to the Route::group and allows us to group and filter the
	| routes for our package
	|
	*/
	'defaults' => array(
		array(
			'color' => '#334455',
			'icon_class' => 'icon-cog',
			'filter' => array(
				'alias' => array(
					'home',
					'admin*'
				),
				'method' => array(
					'post'
				),
			)
		)
	),

);