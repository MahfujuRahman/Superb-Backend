{{-- @extends('errors::minimal')

@section('title', __('Unauthorized'))
@section('code', '401')
@section('message', __('Unauthorized')) --}}


<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>401 - Unauthorized</title>
	<link href="https://fonts.googleapis.com/css?family=Oswald:700" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Lato:400" rel="stylesheet">
	<link type="text/css" rel="stylesheet" href="{{url('error_assets')}}/css/font-awesome.min.css" />
	<link type="text/css" rel="stylesheet" href="{{url('error_assets')}}/css/style.css" />
</head>

<body>

	<div id="notfound">
		<div class="notfound-bg">
			<div></div>
			<div></div>
			<div></div>
			<div></div>
		</div>
		<div class="notfound">
			<div class="notfound-404">
				<h1>401</h1>
			</div>
			<h2>Unauthorized Access</h2>
			<p>You dont have permission to view the content of this page. Please Contact to Admisintrator for Access.</p>
			<a href="{{url('/home')}}" style="margin: 5px">Homepage</a>
            <a href="{{ route('logout') }}" style="margin: 5px" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                class="d-none">
                @csrf
            </form>
		</div>
	</div>

</body>
</html>
