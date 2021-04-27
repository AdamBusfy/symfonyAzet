import '../bootstrap.js';
import $ from 'jquery';

setTimeout(function() {
    $('.alert').fadeOut('fast');
}, 4000);

window.onload=function() {
    const records = document.getElementById("records");

    if (records) {
        records.addEventListener('click', (event) => {
            if (event.target.className === 'btn btn-sm btn-danger delete-record') {
                if (confirm('Are you sure?')) {
                    const id = event.target.getAttribute('data-id');

                    fetch(`/record/delete/${id}`, {
                    }).then(res => window.location.reload());
                }
            }

            if (event.target.className === 'btn btn-sm btn-success edit-record') {
                if (confirm('Are you sure?')) {
                    const id = event.target.getAttribute('data-id');

                    fetch(`/record/edit/${id}`, {
                    }).then(res => window.location.reload());
                }
            }
        })
    }
}
