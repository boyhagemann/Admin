
<ul class="list-group">
	@foreach($nodes as $node)
	<li class="list-group-item">
		<a href="{{ URL::route($node['route']) }}">{{{ $node['label'] }}}</a>
	</li>
	@endforeach
</ul>