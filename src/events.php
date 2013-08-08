<?php

use Boyhagemann\Form\FormBuilder;

Event::listen('formBuilder.buildElement.post', function($name, $element, $factory, FormBuilder $formBuilder) {

	if($formBuilder->getName() == 'Boyhagemann\Pages\Controller\PageController' && $element->getName() == 'method') {
		$formBuilder->modelSelect('resource_id')
			->alias('resource')
			->label('Resource')
			->model('Boyhagemann\Admin\Model\Resource')
			->blank('-- No resource --');
	}

});