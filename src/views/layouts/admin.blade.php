<!DOCTYPE html>
<html lang="en">
	<head>
		<!-- Basic Page Needs
		================================================== -->
		<meta charset="utf-8" />
		<title>Admin</title>
		<meta name="keywords" content="your, awesome, keywords, here" />
		<meta name="author" content="Jon Doe" />
		<meta name="description" content="Lorem ipsum dolor sit amet, nihil fabulas et sea, nam posse menandri scripserit no, mei." />

		<!-- Mobile Specific Metas
		================================================== -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<!-- CSS
		================================================== -->
		<link href="{{{ URL::asset('packages/boyhagemann/admin/css/bootstrap.min.css') }}}" rel="stylesheet">
		<link href="{{{ URL::asset('packages/boyhagemann/admin/css/screen.css') }}}" rel="stylesheet">

                
		<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
		<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->

		<!-- Favicons
		================================================== -->
	</head>

	<body>

			<div class="navbar navbar-static-top">
				<div class="container">
					<div class="row">
						{{ $menu }}
					</div>
				</div>
			</div>

		<!-- Container -->
		<div class="container">

                    <div class="row">
                        
                        <div class="col-lg-9">{{ $content }}</div>
                        <div class="col-lg-3">{{ $sidebar }}</div>
                    </div>                    
                    
                    
		</div>
		<!-- ./ container -->

		<!-- Javascripts
		================================================== -->
                {{ HTML::script('packages/boyhagemann/admin/js/jquery-2.0.3.min.js') }}
                {{ HTML::script('packages/boyhagemann/admin/js/bootstrap.min.js') }}
	</body>
</html>

