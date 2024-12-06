<?php
require_once('./config/database.php');
spl_autoload_register(function ($className) {
    require_once("./app/models/$className.php");
});
session_start();
if (isset($_SESSION['admin'])) unset($_SESSION['admin']);
if (isset($_SESSION['search'])) unset($_SESSION['search']);
if (isset($_POST['accountName']) && $_POST['password']) {
    $adminAccountName = $_POST['accountName'];
    $adminPassword = $_POST['password'];
    $adminModel = new AccountModel();
    if ($adminModel->loginAdmin($adminAccountName, $adminPassword)) {
        $_SESSION['admin'] = $adminAccountName;
        header('Location: admin.php');
    } else {
        header('Location: adminsignin.php');
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Admin Login</title>
    <link rel='shortcut icon' href='./assets/img/icon/login.png'/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="./assets/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="./assets/lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="./assets/css/bootstrap.min.css" rel="stylesheet">
    <!-- <link rel="stylesheet" href="./assets/css/formUp.css"> -->

    <!-- Template Stylesheet -->
    <link href="./assets/css/style.css" rel="stylesheet">
</head>
<style>
    .form-floating.invalid .form-message {
        color: #f33a58;
    }
    .form-floating.invalid .form-control {
        border-color: #f33a58;
    }
</style>
<body>
    <div class="container-xxl position-relative bg-white d-flex p-0">
        <!-- Spinner Start -->
        <!-- <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div> -->
        <!-- Spinner End -->


        <!-- Sign In Start -->
        <div class="container-fluid">
            <div class="row h-100 align-items-center justify-content-center" style="min-height: 100vh;">
                <div class="col-12 col-sm-8 col-md-6 col-lg-5 col-xl-4">
                    <div class="bg-light rounded p-4 p-sm-5 my-4 mx-3">
                        <form action="adminsignin.php" method="post" id="form-1">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <a href="index.php" class="">
                                    <h3 class="text-primary"><i class="fa fa-hashtag me-2"></i>ADMIN</h3>
                                </a>
                                <h3>Sign In</h3>
                            </div>
                            <?php
                            if (isset($_SESSION['adminAccountName'])) {
                            ?>
                                <div class="form-floating mb-3">
                                    <input type="accountName" class="form-control" id="accountName" name="accountName" placeholder="name@example.com" value="<?php echo $_SESSION['adminAccountName'] ?>">
                                    <label for="floatingInput">Account Name</label>
                                    <span class="form-message"></span>
                                </div>
                            <?php
                                unset($_SESSION['adminAccountName']);
                            } else {
                            ?>
                                <div class="form-floating mb-3">
                                    <input type="accountName" class="form-control" id="accountName" name="accountName" placeholder="name@example.com">
                                    <label for="floatingInput">Account Name</label>
                                    <span class="form-message"></span>
                                </div>
                            <?php
                            }
                            ?>
                            <div class="form-floating mb-4">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                                <label for="floatingPassword">Password</label>
                                <span class="form-message"></span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                    <label class="form-check-label" for="exampleCheck1">Check me out</label>
                                </div>
                                <a href="adminforgotpassword.php">Forgot Password</a>
                            </div>
                            <button type="submit" class="btn btn-primary py-3 w-100 mb-4">Sign In</button>
                            <p class="text-center mb-0">Don't have an Account? <a href="adminsignup.php">Sign Up</a></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Sign In End -->
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/chart/chart.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
    <script src="./assets/js/formUp.js"></script>

    <script>
    // Mong muốn của chúng ta
    Validator({
        form: '#form-1',
        formGroupSelector: '.form-floating',
        errorSelector: '.form-message',
        rules: [
            Validator.isRequired('#accountName'),
            Validator.minLength('#password', 6)
        ],
        /*onSubmit: function(data) {
        Call API
        console.log(data);
        }*/
    });

</script>
</body>

</html>