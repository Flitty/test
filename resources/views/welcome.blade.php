<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{--<link rel="icon" href="/favicon.ico">--}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ env('APP_NAME') }}</title>

    <!-- App CSS -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div id="app"></div>

<script src="{{ route('dataset') }}"></script>
<script src="{{ mix('js/app.js') }}"></script>
</body>
</html>