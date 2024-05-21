let message = document.getElementById('message');
let closeBtn = document.getElementById('close-popup');

document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        message.style.display = 'none';
    }, 10000);
});

closeBtn.addEventListener('click', function() {
    message.style.display = 'none';
});