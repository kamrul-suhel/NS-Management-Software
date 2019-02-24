<!DOCTYPE html>
<html>
<head>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="{{asset('fonts/ma-icon/material-icons.css')}}" rel="stylesheet">
    <link href="{{asset("fonts/roboto/roboto.css")}}" rel="stylesheet">
    <link href="{{ asset('css/vuetify.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/print.css') }}" rel="stylesheet" media="print">

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>NS Software</title>
</head>
<body>
<div id="budgetapp"></div>
<!-- built files will be auto injected -->
<script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
