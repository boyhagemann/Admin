<!DOCTYPE html>
<html lang="en">
<head>
	<!-- Basic Page Needs
	================================================== -->
	<meta charset="utf-8" />
	<title>Default</title>
	<meta name="keywords" content="your, awesome, keywords, here" />
	<meta name="author" content="Jon Doe" />
	<meta name="description" content="Lorem ipsum dolor sit amet, nihil fabulas et sea, nam posse menandri scripserit no, mei." />

	<!-- Mobile Specific Metas
	================================================== -->
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- CSS
	================================================== -->
	<link href="{{{ URL::asset('css/bootstrap.min.css') }}}" rel="stylesheet">
	<link href="{{{ URL::asset('css/screen.css') }}}" rel="stylesheet">


	<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
	<!--[if lt IE 9]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<!-- Favicons
	================================================== -->
</head>

<body>

<nav class="navbar navbar-inverse navbar-static-top" role="navigation">
	<div class="container">
		<ul class="nav navbar-nav">
			@if(isset($menuLeft))
			@foreach($menuLeft as $item)
			<li>
				@if($item['method'] == 'get')
				<a href="{{ URL::route($item['route'], $item['params']) }}">{{ $item['label'] }}</a>
				@else
				{{ Form::open($item['form']) }}
				{{ Form::submit($item['label']) }}
				{{ Form::close() }}
				@endif
			</li>
			@endforeach
			@endif
		</ul>
		<ul class="nav navbar-nav pull-right">
			@foreach($menuRight as $item)
			<li>
				@if($item['method'] == 'get')
				<a href="{{ URL::route($item['route'], $item['params']) }}">{{ $item['label'] }}</a>
				@else
				{{ Form::open($item['form']) }}
				{{ Form::submit($item['label']) }}
				{{ Form::close() }}
				@endif
			</li>
			@endforeach
		</ul>
	</div>
</nav>

<!-- Container -->
<div class="container">


	<!-- Notifications -->
	@include('notifications')
	<!-- ./ notifications -->

	<div class="row">

		<div class="col-lg-9">{{ $content }}</div>
	</div>


</div>
<!-- ./ container -->

<!-- Javascripts
================================================== -->
</body>
</html>

