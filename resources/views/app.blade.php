<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}</title>

    <link rel="stylesheet" href="css/app.css">
</head>
<body>
<div class="container">
    <div class="tasks">
       @include('include.addTask')

       @include('include.newTask')

       @include('include.taskGrid')
    </div>
</div>
<script src="js/app.js"></script>
</body>
</html>

