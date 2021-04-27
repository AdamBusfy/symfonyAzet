import '../bootstrap.js';
import $ from 'jquery';

setTimeout(function() {
    $('.alert').fadeOut('fast');
}, 4000);

window.onload=function() {
    const cities = document.getElementById("cities");

    if (cities) {
        cities.addEventListener('click', (event) => {
            if (event.target.className === 'btn btn-sm btn-danger delete-city') {
                if (confirm('Are you sure?')) {
                    const id = event.target.getAttribute('data-id');

                    fetch(`/city/delete/${id}`, {
                        method: 'DELETE'
                    }).then(res => window.location.reload());
                }
            }
        })
    }
}

