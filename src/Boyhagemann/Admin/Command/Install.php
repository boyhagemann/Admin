<?php

namespace Boyhagemann\Admin\Command;

use Illuminate\Console\Command;
use App, Schema;

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
                
                foreach(array('resources', 'layouts', 'sections', 'blocks', 'pages', 'content', 'navigation_container', 'navigation_node') as $table) {                    
                    if(Schema::hasTable($table)) {
                        Schema::drop($table);
                    }
                }
                
		echo 'Creating resources...'.PHP_EOL;
                $controller = App::make('Boyhagemann\Admin\Controller\ResourceController');                
                $layout     = App::make('Boyhagemann\Pages\Controller\LayoutController');
                $section    = App::make('Boyhagemann\Pages\Controller\SectionController');
                $block      = App::make('Boyhagemann\Pages\Controller\BlockController');
                $page       = App::make('Boyhagemann\Pages\Controller\PageController');
                $content    = App::make('Boyhagemann\Pages\Controller\ContentController');
                $container  = App::make('Boyhagemann\Navigation\Controller\ContainerController');
                $node       = App::make('Boyhagemann\Navigation\Controller\NodeController');
                                        
                
		echo 'Seeding resources...'.PHP_EOL;
                \Pages\Layout::create(array(
                    'title' => 'Admin Layout',
                    'name' => 'admin::layouts.admin',
                ));
                \Pages\Section::create(array(
                    'title' => 'Main content',
                    'name' => 'content',
                    'layout_id' => 1,
                ));
                \Pages\Section::create(array(
                    'title' => 'Sidebar',
                    'name' => 'sidebar',
                    'layout_id' => 1,
                ));
                $mainMenu = \Pages\Section::create(array(
                    'title' => 'Main Menu',
                    'name' => 'menu',
                    'layout_id' => 1,
                ));
                \Navigation\Container::create(array(
                    'title' => 'Admin menu',
                    'name' => 'admin',
                ));
                \Pages\Block::create(array(
                    'title' => 'Admin menu',
                    'controller' => 'Boyhagemann\Navigation\Controller\MenuController@admin',
                ));
                \Pages\Content::create(array(
                    'page_id' => 1,
                    'section_id' => $mainMenu->id,
                    'block_id' => 1,
                    'global' => 1,
                ));
                
		echo 'Registering resources...'.PHP_EOL;
                $controller->save('Layout', 'admin/layouts', get_class($layout));
                $controller->save('Section', 'admin/sections', get_class($section));
                $controller->save('Block', 'admin/blocks', get_class($block));
                $controller->save('Pages', 'admin/pages', get_class($page));
                $controller->save('Content', 'admin/content', get_class($content));
                
                 
		echo 'Creating pages and navigation...'.PHP_EOL;
                foreach(App::make('Admin\Resource')->get() as $resource) {
                    $controller->savePages($resource);
                    $controller->saveNavigation($resource);
                }

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
