<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">

    {!! Html::style('https://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic&amp;subset=latin') !!}
    {!! Html::style('https://fonts.googleapis.com/css?family=Montserrat:400,700') !!}
    {!! Html::style('https://fonts.googleapis.com/css?family=Raleway:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i') !!}
    {!! Html::style('assets/bootstrap-material-design-font/css/material.css') !!}
    {!! Html::style('assets/et-line-font-plugin/style.css') !!}
    {!! Html::style('assets/tether/tether.min.css') !!}
    {!! Html::style('assets/bootstrap/css/bootstrap.min.css') !!}
    {!! Html::style('assets/animate.css/animate.min.css') !!}
    {!! Html::style('assets/theme/css/style.css') !!}
    {!! Html::style('assets/mobirise-gallery/style.css') !!}
    {!! Html::style('assets/mobirise/css/mbr-additional.css') !!}
    {!! Html::style('assets/select2-4.0.3/dist/css/select2.css', ['rel' => "stylesheet"]) !!}

    {!! Html::script('assets/web/assets/jquery/jquery.min.js') !!}
    {!! Html::script('assets/web/assets/jquery/jquery.cookie.js') !!}
    {!! Html::script('assets/select2-4.0.3/dist/js/select2.js') !!}



</head>

<body>

@include('layouts.partials._navigation')


@yield('content')

@include('layouts.partials._footer')

</body>
</html>