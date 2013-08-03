<?php

namespace Boyhagemann\Admin\Command;

use Illuminate\Console\Command;
use App;

class Install extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'admin:install';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Run admin installation.';
        
	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire()
	{
		echo 'Installing...'.PHP_EOL;

                $this->call('asset:publish', array(
                    '--bench' => 'boyhagemann/admin' 
                ));
                
                
		echo 'Creating resources...'.PHP_EOL;
                $controller = App::make('Boyhagemann\Admin\Controller\ResourceController');                
                $layout     = App::make('Boyhagemann\Pages\Controller\LayoutController');
                $section    = App::make('Boyhagemann\Pages\Controller\SectionController');
                $block      = App::make('Boyhagemann\Pages\Controller\BlockController');
                $page       = App::make('Boyhagemann\Pages\Controller\PageController');
                $content    = App::make('Boyhagemann\Pages\Controller\ContentController');
                                
		echo 'Registering resources...'.PHP_EOL;
                $controller->save('Layout', 'admin/layouts', get_class($layout));
                $controller->save('Section', 'admin/sections', get_class($section));
                $controller->save('Block', 'admin/blocks', get_class($block));
                $controller->save('Pages', 'admin/pages', get_class($page));
                $controller->save('Content', 'admin/content', get_class($content));

		echo 'Done.'.PHP_EOL;
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array();
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array();
	}

}
