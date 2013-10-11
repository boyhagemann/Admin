
<ul class="dashboard">
	@foreach($apps as $app)
	<li class="dashboard-item">
		<a href="{{ URL::route($app['route']) }}"><span class="{{{ $app['icon_class'] }}}"></span><span>{{{ $app['title'] }}}</span></a>
	</li>
	@endforeach
</ul>