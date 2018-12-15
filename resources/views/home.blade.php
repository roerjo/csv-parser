<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>CSV Parser</title>

        <link href="{{ asset('css/app.css') }}" rel="stylesheet">

        <style>

            #entry {
                transition: height 1s;
            }

        </style>
    </head>
    <body>
        <div class="container">
            <h1>CSV Parser</h1>

            @if ($errors->any())
                <div id="errors">
                    @foreach ($errors->all() as $error)
                        <div class="alert alert-danger" role="alert">
                            {{ $error }}
                        </div>
                    @endforeach
                </div>
            @endif

            <div id="upload-form" data-token="{{ csrf_token() }}"></div>

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

        </div>

        <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
        <script type="text/javascript">
            Echo.channel('reviewer')
                .listen('ReviewerParsed', (e) => {
                    let mainErrors = document.getElementById('errors');
                    if (mainErrors) {
                        mainErrors.style.display = 'none';
                    }
                    let table = document.getElementById("reviewers");
                    let row = table.insertRow(-1)
                    row.id = 'entry';
                    if (e.reviewer.errors.length === 0) {
                        row.classList.add('table-success');
                        row.innerHTML = '<div class="mb-3>'
                        row.style.borderBottom = '1px solid black';
                    } else {
                        let errorRow = table.insertRow(-1)
                        let errorCell = errorRow.insertCell(0);
                        errorCell.colSpan = 10;
                        errorCell.innerHTML = e.reviewer.errors;

                        row.classList.add('table-danger');
                        errorRow.classList.add('table-danger');
                        errorRow.style.borderBottom = '1px solid black';
                    }
                    row.insertCell(0).innerHTML = e.reviewer.trans_type;
                    row.insertCell(1).innerHTML = e.reviewer.trans_date;
                    row.insertCell(2).innerHTML = e.reviewer.trans_time;
                    row.insertCell(3).innerHTML = e.reviewer.cust_num;
                    row.insertCell(4).innerHTML = e.reviewer.cust_fname;
                    row.insertCell(5).innerHTML = e.reviewer.cust_email;
                    row.insertCell(6).innerHTML = e.reviewer.cust_phone;
                    row.insertCell(7).innerHTML = e.reviewer.invite_sent;
                    row.insertCell(8).innerHTML = e.reviewer.invite_method;
                    row.insertCell(9).innerHTML = e.reviewer.invite_type;
                });
        </script>
    </body>
</html>
