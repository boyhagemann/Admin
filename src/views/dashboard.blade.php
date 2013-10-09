
<ul class="list-group">
	@foreach($apps as $app)
	<li class="list-group-item">
		<a href="{{ URL::route($app['route']) }}">{{{ $app['title'] }}}</a>
	</li>
	@endforeach
</ul>