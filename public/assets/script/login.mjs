import request from './request.mjs';

let loginForm = document.getElementById("loginForm");
loginForm.addEventListener('submit', (e) => {
    e.preventDefault();
    let formData = new FormData(loginForm);
    let data = {};
    formData.forEach((value, key) => {
        data[key] = value;
    });
    request(data, (response) => {
        if (response.success) {
            location.reload();
        }
        else {
            console.log(response);
        }
    });
});


let registerForm = document.getElementById("registerForm");
registerForm.addEventListener('submit', (e) => {
    e.preventDefault();
    let formData = new FormData(registerForm);
    let data = {};
    formData.forEach((value, key) => {
        data[key] = value;
    });
    request(data, (response) => {
        if (response.success) {
            console.log(response.message);
        }
        else {
            console.log(response.error);
        }
    });
});