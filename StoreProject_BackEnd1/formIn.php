<?php
require_once('./config/database.php');
// autoloading
spl_autoload_register(function ($className) {
  require_once("./app/models/$className.php");
});

session_start();
if(isset($_SESSION['admin'])) unset($_SESSION['admin']);
unset($_SESSION['user']);
if(isset($_SESSION['user_register']) && isset($_POST['password']))
{
  $accountModel = new AccountModel();
  $username = $_SESSION['user_register'];
  $password = $_POST['password'];
  if($accountModel->loginUser($username, $password))
  {
    $_SESSION['user'] = $username;
    unset($_SESSION['user_register']);
    header('Location: userindex.php');
  }
}
else
{
  if(isset($_POST['username']))
  {
    $accountModel = new AccountModel();
    $username = $_POST['username'];
    $password = $_POST['password'];
    if($accountModel->loginUser($username, $password))
    {
      $_SESSION['user'] = $_POST['username'];
      unset($_SESSION['user_register']);
      header('Location: userindex.php');
    }
    else
    {
      header('Location: formIn.php');
    }
  } 
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel='shortcut icon' href='./assets/img/icon/login.png'/>
    <link rel="stylesheet" href="./assets/css/formIn.css">
</head>

<style>
  .form-group {
    position: relative;
  }
  .eye {
    position: absolute;
    right: 15px;
    top: 63%;
    transform: translateY(-50%);
    cursor: pointer;
    max-width: 20px;
  }
  .hidden {
    display: none;
  }
  .forgot-password {
    text-decoration: none;
    color: #e97e3d;
    font-size: 14px;
    display: flex;
    justify-content: end;
    margin-bottom: 10px;
  }
  .forgot-password:hover {
    color: #F59E68;
  }
  .eye-style {
    top: 55%;
  }
</style>

<div class="main">

    <form action="formIn.php" method="POST" class="form" id="form-1">
      <h3 class="heading">LOGIN</h3>
  
      <div class="spacer"></div>
  
      <div class="form-group">
        <label for="username" class="form-label">Username</label>
        <?php
        if(isset($_SESSION['user_register']))
        {
        ?>
        <input id="username" name="username" rules="required" type="text" placeholder="Username" class="form-control" value="<?php echo $_SESSION['user_register'] ?>">
        <span class="form-message"></span>
        <?php
        }else
        {
        ?>
        <input id="username" name="username" rules="required" type="text" placeholder="Username" class="form-control">
        <span class="form-message"></span>
        <?php
        }
        ?>
      </div>
  
      <div class="form-group">
        <label for="password" class="form-label">Password</label>
        <input id="password" name="password" rules="required|min:6" type="password" placeholder="Password" class="form-control">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="eye eye-open hidden">
          <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
        </svg>

        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="eye eye-close">
          <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
        </svg>

        <span class="form-message"></span>
      </div>
  
      <a href="forgotpassword.php" class="forgot-password">Forgot Password?</a>
      <div class="form-center">
        <button href="formIn.php" class="form-submit">LOGIN</button>
        <a href="formUp.php">SIGN UP</a>
      </div>

    </form>
  
    <script src="./assets/js/formIn.js"></script>
    <script>

      // Mong muốn của chúng ta
      let form = new Validator('#form-1');

      // form.onSubmit = function (value) {
      //   console.log(value);
      // }

      const input = document.querySelector("#password");
      const eyeOpen = document.querySelector(".eye-open");
      const eyeClose = document.querySelector(".eye-close");

      eyeOpen.addEventListener("click", () => {
        eyeOpen.classList.add("hidden");
        eyeClose.classList.remove("hidden");
        input.setAttribute("type", "password");
      });

      eyeClose.addEventListener("click", () => {
        eyeOpen.classList.remove("hidden");
        eyeClose.classList.add("hidden");
        input.setAttribute("type", "text");
      })

      const formSubmit = document.querySelector(".form-submit");
      const eye = document.querySelector(".eye");

      const password = document.querySelector('input[name="password"]');
      password.addEventListener("blur", () => {
        eye.style.top = "55%";
        eyeOpen.style.top = "55%";
        eyeClose.style.top = "55%";
      })

      password.addEventListener("input", () => {
        eye.style.top = "63%";
        eyeOpen.style.top = "63%";
        eyeClose.style.top = "63%";

        password.addEventListener("blur", () => {
          eye.style.top = "63%";
          eyeOpen.style.top = "63%";
          eyeClose.style.top = "63%";
        })

        formSubmit.addEventListener('click', () => {
        eye.style.top = "63%";
        eyeOpen.style.top = "63%";
        eyeClose.style.top = "63%";
        eye2.style.top = "63%";
        eyeOpen2.style.top = "63%";
        eyeClose2.style.top = "63%";
      })
      })

      formSubmit.addEventListener('click', () => {
        eye.style.top = "55%";
        eyeOpen.style.top = "55%";
        eyeClose.style.top = "55%";
        eye2.style.top = "55%";
        eyeOpen2.style.top = "55%";
        eyeClose2.style.top = "55%";
      })

    </script>
  </div>
</html>