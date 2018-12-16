Echo.channel('reviewer')
    .listen('ReviewerParsed', (e) => {
        // determine if the upload form errors should be removed
        let mainErrors = document.getElementById('errors');
        if (mainErrors) {
            mainErrors.style.display = 'none';
        }

        // create a new row for the incoming broadcast
        let table = document.getElementById("reviewers");
        let row = table.insertRow(-1)
        row.id = 'entry';

        // set the row color, and error section if necessary
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

        // set the rest of data for the row
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
