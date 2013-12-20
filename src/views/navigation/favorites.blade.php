
<ul class="page_favorites media-list ">
	@foreach($nodes as $key => $node)
	<li class="page_favorites__item">
		<a href="{{ URL::route($node->page->alias, $node->params) }}" class="page_favorites__link">
			<span class="page_favorites__icon icon {{ $node->icon_class }} circle" style="background-color: {{ $node->color }}"></span>
			{{ $node->page->title }}
		</a>
	</li>
	@endforeach
</ul>
