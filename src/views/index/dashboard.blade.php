
<ul class="dasboard">
	@foreach($apps as $app)
	<li class="dasboard-item">
		<a href="{{ URL::route($app['route']) }}"><span class="{{{ $app['icon_class'] }}}"></span><span>{{{ $app['title'] }}}</span></a>
	</li>
	@endforeach
</ul>