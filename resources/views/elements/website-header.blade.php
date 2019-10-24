<head>

    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>HYPEX</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>@section('title') :Hypex @show</title>

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.css">
    <link href="{{ asset('v1/website/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('v1/website/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('v1/website/css/font-awesome.css') }}" rel="stylesheet">
    <link href="{{ asset('v1/website/css/font-awesome.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('v1/website/css/parsley.css') }}">
	<link rel="stylesheet" href="{{ asset('v1/website/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('v1/website/css/new-design-custom.css') }}">
	<link rel="stylesheet" href="{{ asset('v1/website/css/vip.css') }}">
	<link rel="stylesheet" href="{{ asset('v1/website/css/lightslider.css') }}">
	<link rel="stylesheet" href="{{ asset('v1/website/css/simplelightbox.css') }}">
    <link rel="stylesheet" href="{{ asset('v1/website/css/account.css') }}">
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
	@yield('styles')

    <!-- Fav and touch icons -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="{{ asset('v1/website/img/apple-touch-icon-144-precomposed.png') }}">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="{{ asset('v1/website/img/apple-touch-icon-114-precomposed.png') }}">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{ asset('v1/website/img/apple-touch-icon-72-precomposed.png') }}">
    <link rel="apple-touch-icon-precomposed" href="{{ asset('v1/website/img/apple-touch-icon-57-precomposed.png') }}">
    <link rel="shortcut icon" href="{{ asset('v1/website/img/favicon.png') }}">
    <script type="text/javascript" src="{{ asset('v1/website/js/jquery.min.js') }}"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.min.js"></script>
    <script type="text/javascript" src="{{ asset('v1/website/js/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('v1/website/js/jquery.scrollTo.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('v1/website/js/all.js') }}"></script>
	<script type="text/javascript" src="{{ asset('v1/website/js/lightslider.js') }}"></script>
	<script type="text/javascript" src="{{ asset('v1/website/js/simple-lightbox.js') }}"></script>
	<script type="text/javascript" src="{{ asset('v1/website/js/canvasjs.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('v1/website/js/kinetic.js') }}"></script>
    <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script>
    <script src="{{ asset('v1/website/js/parsley.min.js') }}"></script>
    <script src="//cdn.rawgit.com/hilios/jQuery.countdown/2.2.0/dist/jquery.countdown.min.js"></script>
     <!-- Bootbox -->
    <script src="{{ asset('v1/website/js/bootbox.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('v1/website/js/jquery.final-countdown.js') }}"></script>

</head>