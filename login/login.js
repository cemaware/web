
$(document).ready(function () {
   $("#main-form").submit(function (e) {
      e.preventDefault();
      $.post("submit.php", {
         username: $("#username").val(),
         password: $("#password").val(),
      }, function (reply) {
         switch (reply) {
            case "real":
               document.location = '/dashboard';
               break;
            case "err1":
               errorAppend("All fields are required.");
               $("#submit").prop("disabled", false);
               break;
            case "err2":
               errorAppend("Incorrect username or password!");
               $("#submit").prop("disabled", false);
               break;
            case "err3":
               errorAppend("Incorrect username or password!");
               $("#submit").prop("disabled", false);
               break;
            case "err4":
               errorAppend("Too many failed attempts from this IP address. Try again later.");
               break;
         }
      });
   });
});
var username = document.getElementById('username');
var img = document.getElementById('realImg');

username.addEventListener('keyup', function () {
   var request = new XMLHttpRequest();
   var url = "/api/login/avatar.php";
   var params = "username=" + username.value;
   check(params, request, url);
});

function check(params, request, url) {
   request.open('POST', url, true);
   request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
   request.onreadystatechange = function () {
      if (request.readyState === XMLHttpRequest.DONE) {
         if (request.status === 200) {
            var response = JSON.parse(request.response);
            if (response.avatar) {
               img.src = "/cdn/img/avatar/thumbnail/" + response.avatar + ".png";
            } else {
               img.src = "/cdn/img/icon.png";
            }
         }
      }
   };
   request.send(params);
}

function errorAppend(text) {
   var time = new Date();
   var time = time.getTime();
   $("#errors").append('<div id="' + time + '" class="toast border-0 bg-dark my-1" role="alert" aria-live="assertive" aria-atomic="true"><div class="toast-body bg-danger rounded border-0">' + text + '</div></div>');
   setTimeout(() => {
      $("#" + time).toast('show')
   }, 0)
}

check();

