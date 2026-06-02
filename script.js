// Basic form validation and alert handling
window.onload = function() {
    var loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.onsubmit = function(e) {
            var username = loginForm.username.value.trim();
            var password = loginForm.password.value.trim();
            if (!username || !password) {
                document.getElementById('alert').innerText = 'Please fill all fields.';
                e.preventDefault();
            }
        };
    }
};