
<ul class="list-group">
	@foreach($apps as $app)
	<li class="list-group-item">
		<a href="{{ URL::route($app['route']) }}"><span class="{{{ $app['icon_class'] }}}"></span><span>{{{ $app['title'] }}}</span></a>
	</li>
	@endforeach
</ul>