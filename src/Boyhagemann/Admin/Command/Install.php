<?php

namespace Boyhagemann\Admin\Command;

use Illuminate\Console\Command;
use Boyhagemann\Pages\Model\Page;
use Boyhagemann\Pages\Model\Layout;
use Boyhagemann\Pages\Model\Section;
use Boyhagemann\Pages\Model\Block;
use Boyhagemann\Pages\Model\Content;
use Boyhagemann\Navigation\Model\Container;
use Boyhagemann\Navigation\Model\Node;
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

                foreach(array('resources', 'layouts', 'sections', 'blocks', 'pages', 'content', 'navigation_container', 'navigation_node') as $table) {
                    if(Schema::hasTable($table)) {
                        Schema::drop($table);
                    }
                }
                
		echo 'Creating resources...'.PHP_EOL;
                $controller = App::make('Boyhagemann\Admin\Controller\ResourceController');
				$controller->getModelBuilder()->export();

                $layout = App::make('Boyhagemann\Pages\Controller\LayoutController');
				$layout->getModelBuilder()->export();

                $section = App::make('Boyhagemann\Pages\Controller\SectionController');
				$section->getModelBuilder()->export();

                $block = App::make('Boyhagemann\Pages\Controller\BlockController');
				$block->getModelBuilder()->export();

                $page = App::make('Boyhagemann\Pages\Controller\PageController');
				$page->getModelBuilder()->export();

                $content = App::make('Boyhagemann\Pages\Controller\ContentController');
				$content->getModelBuilder()->export();

                $container = App::make('Boyhagemann\Navigation\Controller\ContainerController');
				$container->getModelBuilder()->export();

                $node = App::make('Boyhagemann\Navigation\Controller\NodeController');
				$node->getModelBuilder()->export();

		echo 'Seeding resources...'.PHP_EOL;
                Layout::create(array(
                    'title' => 'Admin Layout',
                    'name' => 'admin::layouts.admin',
                ));
				Layout::create(array(
					'title' => 'Default Layout',
					'name' => 'layouts.default',
				));
                Section::create(array(
                    'id' => 1,
                    'title' => 'Main content',
                    'name' => 'content',
                    'layout_id' => 1,
                ));
                Section::create(array(
                    'id' => 2,
                    'title' => 'Sidebar',
                    'name' => 'sidebar',
                    'layout_id' => 1,
                ));
                Section::create(array(
                    'id' => 3,
                    'title' => 'Main Menu',
                    'name' => 'menu',
                    'layout_id' => 1,
                ));
				Section::create(array(
					'id' => 4,
					'title' => 'Content',
					'name' => 'content',
					'layout_id' => 2,
				));
                Container::create(array(
					'id' => 1,
                    'title' => 'Admin menu',
                    'name' => 'admin',
                ));
				$contentNode = Node::create(array(
					'id' => 1,
					'title' => 'Content',
					'container_id' => 1
				));
                Block::create(array(
                    'id' => 1,
                    'title' => 'Admin menu',
                    'controller' => 'Boyhagemann\Navigation\Controller\MenuController@admin',
                ));
                Block::create(array(
                    'id' => 2,
                    'title' => 'Copy resource',
                    'controller' => 'Boyhagemann\Admin\Controller\ResourceController@copy',
                ));
				Block::create(array(
					'id' => 3,
					'title' => 'Text',
					'controller' => 'Boyhagemann\Text\Controller\TextController@text',
				));
                Content::create(array(
                    'global' => 1,
                    'page_id' => 1,
                    'section_id' => 3,
                    'block_id' => 1,
                ));

				Page::createWithContent('Admin home', '/admin', 'Boyhagemann\Admin\Controller\IndexController@index', 'get', 'admin::layouts.admin');
				Page::createWithContent('Page content', '/admin/pages/{page}/content', 'Boyhagemann\Pages\Controller\PageController@content', 'get', 'admin::layouts.admin');
				Page::createWithContent('Add content', '/admin/pages/{page}/content/create/{block}', 'Boyhagemann\Pages\Controller\PageController@addContent', 'get', 'admin::layouts.admin');
				$tree = Page::createWithContent('Tree', '/admin/nodes/tree', 'Boyhagemann\Navigation\Controller\NodeController@tree', 'get', 'admin::layouts.admin');

				Node::create(array(
					'id' => 2,
					'title' => 'Tree',
					'container_id' => 1,
					'page_id' => $tree->id
				));

		echo 'Registering resources...'.PHP_EOL;
                $controller->save('Layout', 'admin/layouts', get_class($layout));
                $controller->save('Section', 'admin/sections', get_class($section));
                $controller->save('Block', 'admin/blocks', get_class($block));
                $controller->save('Pages', 'admin/pages', get_class($page));
                $controller->save('Content', 'admin/content', get_class($content));
                $controller->save('Container', 'admin/containers', get_class($container));
                $controller->save('Node', 'admin/nodes', get_class($node));


		echo 'Creating pages and navigation...'.PHP_EOL;
                foreach(App::make('Boyhagemann\Admin\Model\Resource')->get() as $resource) {
                    $controller->savePages($resource);
                    $controller->saveNavigation($resource, $contentNode);
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
