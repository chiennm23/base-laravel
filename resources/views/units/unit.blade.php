<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> {{ $title }}</title>
    <style>
        .thTable{
            border: 1px solid black;
        }
        /*table th {*/
        /*    border: 1px solid black;*/
        /*}*/
    </style>
</head>
<body>
<table>
    <thead>
    <tr></tr>
    <tr></tr>
    <tr>
        <th colspan="2" style="border: 1px solid black;">Stt</th>
{{--        <th></th>--}}
        <th>active start</th>
        <th>active end</th>
        <th>display</th>
    </tr>
    </thead>
    <tbody>
    @foreach($units as $key => $unit)
        <tr>
            <td></td>
            <td>{{ ++$key }}</td>
            <td>{{ $unit->active_start_date }}</td>
            <td>{{ $unit->active_end_date }}</td>
            <td>{{ $unit->display_order }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
