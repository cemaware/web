<?php
$name = 'Register';
include("$_SERVER[DOCUMENT_ROOT]/cema/header.php");
$cema->requireGuest();
$enabled = $cema->query("SELECT registering_enabled FROM site_settings WHERE id = 1")->fetchColumn();
$numberOfAccountsOnCurrentIP = $cema->query("SELECT COUNT(1) FROM users WHERE user_ip = ?", [$_SERVER['REMOTE_ADDR']])->fetchColumn();


if (isset($_POST['submit']) && $enabled) {
   $username = $_POST["username"];
   $email = $_POST["email"];
   $password = $_POST["password"];
   $passwordRepeat = $_POST["password_repeat"];

   $username = htmlspecialchars($username);
   $username = $cema->filter($username);

   $conn = $cema->pdo;


   function redirect()
   {
      header("location: /register");
      exit();
   }
   if (empty($username) || empty($email) || empty($password) || empty($passwordRepeat)) {
      $_SESSION["error"] = "Please fill in all fields";
      redirect();
   }
   if (!preg_match("/^[a-zA-Z0-9_]*$/", $username)) {
      $_SESSION["error"] = "Username can only contain letters, numbers, and underscores.";
      redirect();
   }
   if (strlen($username) > 20 || strlen($username) < 3) {
      $_SESSION["error"] = "Username must be between 3 and 20 characters.";
      redirect();
   }

   if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $_SESSION["error"] = "Invalid email.";
      redirect();
   }
   if (strlen($password) < 8 || strlen($password) > 50) {
      $_SESSION["error"] = "Password must be between 8 and 50 characters.";
      redirect();
   }
   if ($password != $passwordRepeat) {
      $_SESSION["error"] = "Passwords do not match.";
      redirect();
   }

   unset($passwordRepeat);

   $statement = $conn->prepare("SELECT * FROM users WHERE name = :username");
   $statement->execute(array(':username' => $username));
   $result = $statement->fetch();



   if (!empty($result)) {
      $_SESSION["error"] = "Username already exists.";
      redirect();
   }

   $statement = $conn->prepare("INSERT INTO users (name, email, password, signup_ip, user_ip, created) VALUES (:username, :email, :password, :ip, :ip, UNIX_TIMESTAMP())");
   // hash password
   $password = password_hash($password, PASSWORD_DEFAULT);
   $statement->execute(array(':username' => $username, ':email' => $email, ':password' => $password, ':ip' => $_SERVER['REMOTE_ADDR']));
   // ANCHOR session variables
   session_start();
   $_SESSION['UserID'] = $conn->lastInsertId();
   $_SESSION['note'] = "Welcome to Cemaware, " . $_SESSION['Username'] . "!";
   header("location: /dashboard");
   exit();
}

?>
<?php

if ($enabled && !$numberOfAccountsOnCurrentIP >= 6) {
?>
   <div class="row">
      <div class="col-5 mx-auto">
         <?php
         $cema->alert();
         ?>
         <form action method="POST" id="regForm">
            <input type="hidden" name="submit">
            <h2 class="text-center">
               Welcome to Cemaware.
            </h2>
            <div class="reg-tab">
               <p class="small text-center text-secondary">
                  What would you like your account to be called?
               </p>
               <input type="text" name="username" id="username" placeholder="Username">
               <span id="usernameResult" class="small"></span>
            </div>
            <div class="reg-tab">
               <p class="small text-center text-secondary">
                  Provide an email address so we can contact you.
               </p>
               <input type="email" name="email" id="email" placeholder="email@example.com">
               <span id="emailResult" class="small"></span>
            </div>
            <div class="reg-tab">
               <p class="small text-center text-secondary">
                  Create a secure password. Don't share it with anyone.
               </p>
               <input type="password" name="password" id="password" placeholder="Password">
               <span id="passwordResult" class="small"></span>
            </div>
            <div class="reg-tab">
               <p class="small text-center text-secondary">
                  Repeat the password to make sure you typed it correctly.
               </p>
               <input type="password" name="password_repeat" id="password_repeat" placeholder="Repeat Password">
               <span id="passwordRepeatResult" class="small"></span>
               <div class="mt-2"></div>
               <button class="btn btn-theme btn-sm px-4" name="submit" type="submit">Register</button>
            </div>
            <div style="overflow:auto;" class="mt-2">
               <div style="float:right;">
                  <button type="button" id="prevBtn" onclick="nextPrev(-1)" class="btn btn-theme btn-sm px-4">Previous</button>
                  <button type="button" id="nextBtn" onclick="nextPrev(1)" class="btn btn-theme btn-sm px-4" disabled>Next</button>
               </div>
            </div>
         </form>
         <div style="text-align:center;margin-top:40px;">
            <span class="step"></span>
            <span class="step"></span>
            <span class="step"></span>
            <span class="step"></span>
         </div>
      </div>
   </div>
   <script>
      var currentTab = 0; // Current tab is set to be the first tab (0)
      showTab(currentTab); // Display the current tab

      function showTab(n) {
         // This function will display the specified tab of the form ...
         var x = document.getElementsByClassName("reg-tab");
         x[n].style.display = "block";
         // ... and fix the Previous/Next buttons:
         if (n == 0) {
            document.getElementById("prevBtn").style.display = "none";
         } else {
            document.getElementById("prevBtn").style.display = "inline";
         }
         if (n == (x.length - 1)) {
            document.getElementById("nextBtn").hidden = true;
         } else {
            document.getElementById("nextBtn").hidden = false;
            document.getElementById("nextBtn").innerHTML = "Next";
         }
         // ... and run a function that displays the correct step indicator:
         fixStepIndicator(n)
      }

      function nextPrev(n) {
         // This function will figure out which tab to display
         var x = document.getElementsByClassName("reg-tab");
         // Exit the function if any field in the current tab is invalid:
         if (n == 1 && !validateForm()) return false;
         // Hide the current tab:
         x[currentTab].style.display = "none";
         // Increase or decrease the current tab by 1:
         currentTab = currentTab + n;
         // if you have reached the end of the form... :
         if (currentTab >= x.length) {
            //...the form gets submitted:
            document.getElementById("regForm").submit();
            return false;
         }
         // Otherwise, display the correct tab:
         showTab(currentTab);
      }

      function validateForm() {
         // This function deals with validation of the form fields
         var x, y, i, valid = true;
         x = document.getElementsByClassName("reg-tab");
         y = x[currentTab].getElementsByTagName("input");
         // A loop that checks every input field in the current tab:
         for (i = 0; i < y.length; i++) {
            // If a field is empty...
            if (y[i].value == "") {
               // add an "invalid" class to the field:
               y[i].className += " invalid";
               // and set the current valid status to false:
               valid = false;
            }
         }
         // If the valid status is true, mark the step as finished and valid:
         if (valid) {
            document.getElementsByClassName("step")[currentTab].className += " finish";
         }
         return valid; // return the valid status
      }

      function fixStepIndicator(n) {
         // This function removes the "active" class of all steps...
         var i, x = document.getElementsByClassName("step");
         for (i = 0; i < x.length; i++) {
            x[i].className = x[i].className.replace(" active", "");
         }
         //... and adds the "active" class to the current step:
         x[n].className += " active";
      }
   </script>
   <script src="register.js"></script>
<?php
}
if (!$enabled) {
?>
   <div class="row">
      <div class="col-lg-6 col-md mx-auto">
         <h4 class="mb-2">
            Registering Disabled
         </h4>
         <p class="small">
            At the moment, Cemaware is only open to beta testers. Get the chance to become a beta tester by joining our <a href="https://discord.gg/2EdVuNF6Jr" style="color: dodgerblue;">Discord Server</a>.
         </p>
      </div>
   </div>
<?php
} else {
?>
   <div class="row">
      <div class="col-lg-6 col-md mx-auto">
         <h4 class="mb-2 fw-bold text-center">
            Account Limit
         </h4>
         <p>
            We have determined that from this IP address, there are <?= $numberOfAccountsOnCurrentIP ?> user accounts from the current network. This is <?= ($numberOfAccountsOnCurrentIP - 5) ?> accounts over the limit. To prevent spam and other forms of account abuse, we now limit Cemaware users to 5 accounts per IP address.
         </p>
         <p>
            If you've forgotten your passwrod, you can contact our support team at <a href="mailto:support@cemaware.ml">support@cemaware.ml</a>
         </p>
         <p>
            If you've been banned, please do note that ban evading is against our <a href="/site/terms">Terms of Service</a> and when caught you will be punished further.
         </p>
         <p>
            Have a blessed day, thank you for choosing Cemaware.
         </p>
      </div>
   </div>
<?php
}
?>

<?php

$cema->footer();

?>