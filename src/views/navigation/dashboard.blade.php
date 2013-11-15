
<ul class="dashboard clearfix">
	@foreach($nodes as $node)
	<li class="dashboard-item">
		<a href="{{ URL::route($node->page->alias, $node->params) }}"><span class="{{{ $node['icon_class'] }}}"></span><span>{{{ $node['title'] }}}</span></a>
	</li>
	@endforeach
</ul>