<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>CSV Parser</title>

        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>
    <body>
        <div class="container">
            <h1>CSV Parser</h1>

            <!-- Form Errors Begin -->
            @if ($errors->any())
                <div id="errors">
                    @foreach ($errors->all() as $error)
                        <div class="alert alert-danger" role="alert">
                            {{ $error }}
                        </div>
                    @endforeach
                </div>
            @endif
            <!-- Form Errors End -->

            <!-- Upload Form Begin -->
            <div id="upload-form" data-token="{{ csrf_token() }}"></div>
            <!-- Upload Form End -->

            <!-- Results Table Begin -->
            <div class="table-responsive mt-3">
                <table class="table table-borderless">
                    <thead>
                        <tr>
                            <th nowrap='nowrap'>Trans Type</th>
                            <th nowrap='nowrap'>Trans Date</th>
                            <th nowrap='nowrap'>Trans Time</th>
                            <th nowrap='nowrap'>Cust #</th>
                            <th nowrap='nowrap'>Cust First Name</th>
                            <th nowrap='nowrap'>Cust Email</th>
                            <th nowrap='nowrap'>Cust Phone</th>
                            <th nowrap='nowrap'>Invite Sent</th>
                            <th nowrap='nowrap'>Invite Method</th>
                            <th nowrap='nowrap'>Invite Type</th>
                        </tr>
                    </thead>
                    <tbody id="reviewers">
                    </tbody>
                </table>
            </div>
            <!-- Results Table End -->

        </div>

        <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
    </body>
</html>
