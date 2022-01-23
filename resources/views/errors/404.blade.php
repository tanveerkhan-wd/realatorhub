
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>404 Error - Realtor Hubs</title>
	<link rel="stylesheet" type="text/css" href="{{ url('public/front_end/assets/css/bootstrap.min.css')  }}">
	<link rel="stylesheet" type="text/css" href="{{ url('public/front_end/assets/css/fontawesome.css') }}">

	<!--<link rel="stylesheet" type="text/css" href="assets/css/animate.css">

	<link rel="stylesheet" type="text/css" href="assets/css/owl.carousel.min.css">
	<link rel="stylesheet" type="text/css" href="assets/css/owl.theme.default.min.css"> -->
		<style>
		/* feature */

			.error_header {
			    background-image: url({{ url('public/assets/images/404_bg.png') }});			    
			    background-position: right bottom;
			    background-repeat: no-repeat;
    			background-size: cover;
			}

			.icon_404 {
			    display: flex;
			    justify-content: center;
			    align-items: flex-end;
			    height: 500px;
			}
			.icon_404 img {
			    max-height: 350px;
			}
			.error_content h3 {
			    font-family: Sofiar, sans-serif;
			    font-size: 40px;
			    letter-spacing: 0.3px;
			}
			.error_content p {
			    font-size: 16px;
			}
			.error_content .yellow_btn {
			    margin-top: 40px;
			}
			.theme_btn {
			    background: #BB2424;
			    box-shadow: 0 4px 18px 0 rgba(0,0,0,0.16);
			    border-radius: 12px 0 12px 0;
			    height: 40px;
			    padding: 0px 40px;
			    min-width: 150px;
			    border: none;
			    font-family: latob, sans-serif;
			    font-size: 20px;
			    color: #FFFFFF;
			    text-align: center;
			    transition: 0.4s linear;
			    display: inline-block;
			    line-height: 45px;
			    margin-bottom: 10px;
			}
			.theme_btn:hover {
			    color: #fff;
			    text-decoration: none;
			    box-shadow: 0 3px 12px #bb2424;
			}
			.text-center {
			    text-align: center!important;
			}
	</style>
</head>
<body>
	<div class="inner_container sticky_footer sticky_nav">
		<section class="bg error_header">
			<div class="container">
				<div class="icon_404 text-center">
					<img src="{{ url('public/assets/images/404_icon.png') }}">
				</div>
			</div>
		</section>
		<div class="container error_content text-center">
			<h3>oops! page not found</h3>
			<p>Perhaps you can try to refresh the page, sometimes it </p>
			<a href="{{url('/agency/login')}}" class="theme_btn">Back</a>
		</div>
	</div>		
	<script type="text/javascript" src="{{ url('public/assets/js/jquery-3.4.1.min.js') }}"></script>	
	<script type="text/javascript" src="{{ url('public/assets/js/bootstrap.min.js') }}"></script>
	<script type="text/javascript" src="{{ url('public/assets/js/popper.min.js') }}"></script>
</body>
</html>