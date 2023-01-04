var username = document.getElementById('username');
var password = document.getElementById('password');
var password_repeat = document.getElementById('password_repeat');
var email = document.getElementById('email');

var nameError = document.getElementById('usernameResult');
var passwordError = document.getElementById('passwordResult');
var passwordRepeatError = document.getElementById('passwordRepeatResult');
var emailError = document.getElementById('emailResult');
var button = document.getElementById("nextBtn")

password.addEventListener('keyup', function () {
   if (password.value.length < 8 || password.value.length >= 50) {
      passwordError.innerHTML = "Password must be between 8 and 50 characters!";
      passwordError.classList.add('text-danger', 'm-0');
      passwordError.classList.remove('my-3');
      password.classList.add('border-danger');
      button.disabled = true;
   } else {
      passwordError.innerHTML = "";
      passwordError.classList.remove('m-0', 'text-danger');
      passwordError.classList.add('my-3');
      password.classList.remove('border-danger');
      password.classList.add('border-success');
      button.disabled = false;
   }
});

email.addEventListener('keyup', function () {
   if (!isEmail(email.value)) {
      emailError.innerHTML = "Invalid email!";
      emailError.classList.add('text-danger', 'm-0');
      emailError.classList.remove('my-3');
      email.classList.add('border-danger');
      button.disabled = true;
   } else {
      emailError.innerHTML = "";
      emailError.classList.remove('m-0', 'text-danger');
      emailError.classList.add('my-3');
      email.classList.remove('border-danger');
      email.classList.add('border-success');
      button.disabled = false;
   }
});

password_repeat.addEventListener('keyup', function () {
   if (password_repeat.value != password.value) {
      passwordRepeatError.innerHTML = "Passwords must match!";
      passwordRepeatError.classList.add('text-danger', 'm-0');
      passwordRepeatError.classList.remove('my-3');
      password_repeat.classList.add('border-danger');
      button.disabled = true;
   } else {
      passwordRepeatError.innerHTML = "";
      passwordRepeatError.classList.remove('m-0', 'text-danger');
      passwordRepeatError.classList.add('my-3');
      password_repeat.classList.remove('border-danger');
      password_repeat.classList.add('border-success');
      button.disabled = false;
   }
});
// register api
username.addEventListener('keyup', function () {
   console.log("Keyup");
   var request = new XMLHttpRequest();
   var url = "/api/register/username.php";
   var params = "username=" + username.value;
   if (username.value.length <= 3 || username.value.length >= 20) {
      nameError.innerHTML = "Username must be between 3 and 20 characters!";
      nameError.classList.add('text-danger', 'm-0');
      nameError.classList.remove('my-3');
      username.classList.add('border-danger');
      button.disabled = true;
   } else if (!alphanumeric(username.value)) {
      nameError.innerHTML = "Please only use letters, numbers, or underscores!";
      nameError.classList.add('text-danger', 'm-0');
      nameError.classList.remove('my-3');
      username.classList.add('border-danger');
      button.disabled = true;
   } else {
      UsernameValidation(params, request, url);
   }
});

function UsernameValidation(params, request, url) {
   request.open('POST', url, true);
   request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
   request.onreadystatechange = function () {
      if (request.readyState === XMLHttpRequest.DONE) {
         if (request.status === 200) {
            var response = JSON.parse(request.response);
            if (response.result == true) {
               nameError.innerHTML = "The username you typed has been used!";
               nameError.classList.add('text-danger', 'm-0');
               nameError.classList.remove('my-3');
               username.classList.add('border-danger');
               button.disabled = true;
            } else if (response.approved == false) {
               nameError.innerHTML = "This username is not appropriate for Cemaware!";
               nameError.classList.add('text-danger', 'm-0');
               nameError.classList.remove('my-3');
               username.classList.add('border-danger');
               button.disabled = true;
            } else {
               nameError.innerHTML = "";
               nameError.classList.remove('text-danger');
               nameError.classList.add('text-success', 'm-0');
               nameError.classList.remove('my-3');
               username.classList.remove('border-danger');
               username.classList.add('border-success');
               button.disabled = false;
            }
         }
      }
   };
   request.send(params);
}