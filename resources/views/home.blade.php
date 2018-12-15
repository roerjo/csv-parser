<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>CSV Parser</title>

        <script type='text/javascript' src='js/app.js'></script>
    </head>
    <body>
        <form action="/parser" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="file" name="csv_data"/>
            <button type="submit">Submit File</button>
        </form>
        @if ($errors->any())
            {{ $errors }}
        @endif
        <script>
            Echo.channel('reviewer')
                .listen('ReviewerParsed', (e) => {
                    console.log(e);
                });
        </script>
    </body>
</html>
