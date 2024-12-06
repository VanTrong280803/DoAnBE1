<?php
require_once('./config/database.php');
// autoloading
spl_autoload_register(function ($className) {
    require_once("./app/models/$className.php");
});
session_start();
if(isset($_SESSION['admin'])) unset($_SESSION['admin']);
if(isset($_SESSION['search'])) unset($_SESSION['search']);
if(!isset($_SESSION['user']))
{
    header('Location: index.php');
}else
{
    $userModel = new UserModel();
    $info = $userModel->getInfoUserByUsername($_SESSION['user']);
}

if(isset($_COOKIE['cart']))
{
    if(isset($_GET['idDeleteProduct']))
    {
        $temp = json_decode($_COOKIE['cart'], true);
        $idDeleteProduct = $_GET['idDeleteProduct'];
        $temp = array_diff($temp, array("$idDeleteProduct"));
        if(sizeof($temp) > 0)  
        {
            setcookie('cart', json_encode($temp), time() + 3600 * 24);
        }
        else
        {
            setcookie('cart', "", time() - 3600 * 24 - 3);
        } 
        header("Location: profile.php");
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel='shortcut icon' href='./assets/img/icon/profile.png'/>
    <link rel="stylesheet" href="./assets/css/main.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
</head>
<style>
    .profile-info {
        padding: 100px 0 100px 0;
        background-color: #f5f5f5;
    }

    .profile-edit {
        padding: 25px 0;
    }

    .profile-list {
        display: flex;
        padding: 25px;
        justify-content: center;
        align-items: center;
    }

    .profile-title {
        color: #7c7c7c;
        font-weight: 400;
        font-size: 1rem;
        margin-right: 20px;
        width: 20%;
    }

    .profile-text {
        color: #7c7c7c;
        font-weight: 400;
        font-size: 1rem;
        margin-left: 12px;
        width: 80%;
    }

    .profile-name {
        align-items: center;
        display: flex;
        box-sizing: border-box;
        overflow: hidden;
        border: 1px solid rgba(0, 0, 0, 0.14);
        border-radius: 2px;
    }

    .profile-input {
        background: none;
        padding: 12px;
        outline: none;
        border: 0px;
        flex: 1 0 0%;
        filter: none;
    }

    .profile-text a {
        border: 0px;
        outline: none;
        background: none;
        color: rgb(0, 85, 170) !important;
        font-size: 1rem;
        text-transform: capitalize;
        text-decoration: underline !important;
    }

    .profile-edit-text {
        border-right: 1px solid;
    }

    .btn-solid-primary {
        color: rgb(255, 255, 255) !important;
        position: relative;
        overflow: visible;
        outline: 0px;
        background: rgb(238, 77, 45);
        padding: 10px 20px;
        margin-left: 13rem;
    }

    .btn-solid-primary:hover {
        opacity: .8;
    }

    .profile-edit-photo img {
        margin: 0 auto;
        display: block;
        border-radius: 50%;
        width: 50%;
        height: 50%;
    }

    .btn-light {
        outline: 0px;
        background: rgb(255, 255, 255);
        color: rgb(85, 85, 85);
        border: 1px solid rgba(0, 0, 0, 0.09);
        box-shadow: rgb(0 0 0 / 3%) 0px 1px 1px 0px;
        position: relative;
        overflow: visible;
        text-align: center;
        padding: 10px 20px;
        margin-bottom: 5px;
    }

    .profile-block {
        display: block;
        justify-content: center;
        text-align: center;
        padding: 15px 0;
    }

    @media (max-width: 1023px) {
        .profile-edit-text {
            border-right: 0px;
            padding-bottom: 40px;
        }
    }

    @media (max-width: 423px) {

        .profile-title,
        .profile-text {
            font-size: 15px;
        }
    }
    a.header__cart-view-cart.btn.btn--primary {
        color: #fff !important;
    }
    .header__cart-item-remove:hover {
        cursor: pointer;
        color: var(--primary-color) !important;
    }
</style>

<style>
    .header__cart-wrap::after {
        cursor: pointer;
        content: "";
        display: block;
        position: absolute;
        right: -3px;
        top: -9px;
        border-width: 35px 30px;
        border-style: solid;
        border-color: transparent transparent transparent transparent;
    }

    @media (max-width: 1023px) {
        .header__cart-list::after {
            border-color: transparent transparent transparent transparent;
        }
    }
</style>

<body>
    <!-- Header Begin -->
    <header>
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container">
                <div class="nav-bar-logo">
                    <a href="userindex.php">
                        <img src="./assets/img/files/LOGO.png" alt="Logo">
                    </a>
                </div>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav" style="margin: auto;">
                        <li class="nav-bar-item active">
                            <a href="userindex.php" class="nav-bar-link active">
                                <span class="nav-bar-title">
                                    Home
                                </span>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="nav-bar-list-item">
                    <ul class="nav-bar-list-item-list">
                        <li>
                            <a href="#"><i class="fas fa-search search-btn"></i></a>
                        </li>
                        <li class="header__navbar-item header__navbar-user">
                            <img src="./assets/img/avatar/<?php if(is_null($info['avatar'])) {echo "avatar_default.jpg";} else {echo $info['avatar'];}?>"
                                alt="" class="header__navbar-user-img">
                            <span class="header__navbar-user-name"><?php echo $info['fullname']?></span>

                            <ul class="header__navbar-user-menu">
                                <li class="header__navbar-user-item">
                                    <a href="">My Account</a>
                                </li>
                                <li class="header__navbar-user-item">
                                    <a href="purchasedproduct.php">Purchased</a>
                                </li>
                                <li class="header__navbar-user-item">
                                    <a href="favoriteproduct.php">Favorite</a>
                                </li>
                                <li class="header__navbar-user-item">
                                    <a href="changepassword.php">Change Password</a>
                                </li>
                                <li class="header__navbar-user-item header__navbar-user-item--separate">
                                    <a href="logout.php">
                                        Log Out
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <?php
                        if(isset($_COOKIE['cart']))
                        {                           
                            $productModel = new ProductModel();
                            $categoryModel = new CategoryModel();
                            $temp = json_decode($_COOKIE['cart'], true);
                            $listProductAdded = $productModel -> getProductByIds($temp);               
                    ?>
                            <div class="header__cart">
                                <div class="header__cart-wrap">
                                    <a href="cart.php"><i class="fas fa-shopping-cart"></i></a>
                                    <span class="header__cart-notice"><?php echo count($temp)?></span>

                                    <!-- no cart: header__cart-list--no-cart -->
                                    <div class="header__cart-list">
                                        <img src="./assets/img/cart/nothing_cart.png" alt=""
                                            class="header__cart-no-cart-img">
                                        <span class="header__cart-list-no-cart-msg">
                                            No Product
                                        </span>

                                        <!-- has cart -->
                                        <h4 class="header__cart-heading">Products Addeds</h4>
                                        <ul class="header__cart-list-item">
                                            <!-- cart item -->
                                            <?php
                                        foreach($listProductAdded as $element) :
                                            $productInCartImageActive = explode(',',$element['product_photo'])[0];
                                            $productAddedId = $categoryModel->getCategoryIdByProductId($element['id']);
                                            $productAddedTypeName = $categoryModel->getCategoryName($productAddedId['category_id']);
                                    ?>
                                            <li class="header__cart-item">
                                                <img src="./assets/img/products/<?php echo $productInCartImageActive?>"
                                                    alt="<?php echo $element['product_name']?>"
                                                    class="header__cart-img">
                                                <div class="header__cart-item-info">
                                                    <div class="header__cart-item-head">
                                                        <h5 class="header__cart-item-name">
                                                            <?php echo $element['product_name']?></h5>

                                                        <div class="header__cart-item-price-wrap">
                                                            <span class="header__cart-item-price">
                                                                $<?php printf('%.2f', $element['product_price'])?>
                                                            </span>
                                                            <span class="header__cart-item-multiply"></span>
                                                            <span class="header__cart-item-qnt"></span>
                                                        </div>
                                                    </div>

                                                    <div class="header__cart-item-body">
                                                        <span class="header__cart-item-description">
                                                            Type: <?php echo $productAddedTypeName['category_name']?>
                                                        </span>

                                                        <a href="profile.php?idDeleteProduct=<?php echo $element['id']?>"
                                                            class="header__cart-item-remove">Delete
                                                        </a>
                                                    </div>
                                                </div>
                                            </li>
                                            <?php
                                        endforeach
                                    ?>
                                        </ul>
                                        <a href="cart.php" class="header__cart-view-cart btn btn--primary">View Cart</a>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        else
                        {
                    ?>
                            <div class="header__cart">
                                <div class="header__cart-wrap">
                                    <a href="cart.php"><i class="header__cart-icon fas fa-shopping-cart"></i></a>
                                    <!-- no cart: header__cart-list--no-cart -->
                                    <div class="header__cart-list header__cart-list--no-cart">
                                        <img src="./assets/img/cart/nothing_cart.png" alt=""
                                            class="header__cart-no-cart-img">
                                        <span class="header__cart-list-no-cart-msg">
                                            No Product
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    ?>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Seach Begin -->
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="text-field">
                        <button class="icon-close">&times;</button>
                        <form action="usersearch.php" class="example" method="get">
                            <input autocomplete="off" type="text" id="search" placeholder="Search.."
                                name="findKeyWord" />
                            <button type="submit" class="submit-btn"><i class="fa fa-search"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Seach End -->
    </header>
    <!-- Header End -->
    <div class="profile-info">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h3>
                        Account Information
                    </h3>
                    <p>
                        Manage profile information for account security
                    </p>
                </div>
            </div>
        </div>

        <div class="profile-edit">
            <div class="container">
                <div class="row">
                    <div class="col-md-8">
                        <div class="profile-edit-text">
                            <div class="profile-list">
                                <span class="profile-title">
                                    Username
                                </span>
                                <span class="profile-text">
                                    <?php echo $info['username'] ?>
                                </span>
                            </div>
                            <div class="profile-list">
                                <span class="profile-title">
                                    Full name
                                </span>
                                <span class="profile-text">
                                    <?php echo $info['fullname'] ?>
                                </span>
                            </div>
                            <div class="profile-list">
                                <span class="profile-title">
                                    Email
                                </span>
                                <span class="profile-text">
                                    <?php echo $info['email'] ?>
                                </span>
                            </div>
                            <div class="profile-list">
                                <span class="profile-title">
                                    Phone
                                </span>
                                <span class="profile-text">
                                    <?php echo $info['phone'] ?>
                                </span>
                            </div>
                            <a href="changeprofile.php" type="button" class="btn btn-solid-primary">Change</a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="profile-edit-photo">
                            <img src="./assets/img/avatar/<?php if(is_null($info['avatar'])) {echo "avatar_default.jpg";} else {echo $info['avatar'];}?>"
                                alt="avatar">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <footer class="footer-mid">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-12 text-center text-lg-left">
                    <div class="footer__powered">
                        <p>
                            © Copyright 2022
                            <b>stark-demo</b>.
                            <span>
                                <a href="https://github.com/dkhak3">Powered by duykhadev</a>
                            </span>
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12 text-center">
                    <div class="footer-mid__linklist mt-2">
                        <ul>
                            <li class="d-inline-block px-3 py-2" style="position: relative ;">
                                <a href="#" title="Privacy Policy ">Privacy Policy </a>
                            </li>
                            <li class="d-inline-block px-3 py-2" style="position: relative ;">
                                <a href="#" title=" Help"> Help</a>
                            </li>
                            <li class="d-inline-block px-3 py-2" style="position: relative ;">
                                <a href="#" title="FAQs">FAQs</a>
                            </li>
                            <li class="d-inline-block px-3 py-2" style="position: relative ;">
                                <a href="#" title="Contact Us">Contact Us</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="footer-payment col-lg-4 col-md-12 text-lg-right text-center">
                    <img class="mb-4 lazyloaded" src="./assets/img/files/payma.png" alt="">
                </div>
            </div>
        </div>

        <a href="#">
            <div class="back-to-home">
                <i class="fa fa-chevron-up"></i>
            </div>
        </a>
    </footer>

    <script>
    const searchBtn = document.querySelector(".search-btn");
    const navListItem = document.querySelector(".nav-bar-list-item");
    const textField = document.querySelector(".text-field");
    const submitBtn = document.querySelector(".submit-btn");
    const iconClose = document.querySelector(".icon-close");

    searchBtn.addEventListener("click", () => {
        navListItem.style.display = "none";
        textField.style.display = "block";
        iconClose.style.display = "block";
    });

    submitBtn.addEventListener("click", () => {
        navListItem.style.display = "block";
        textField.style.display = "none";
        iconClose.style.display = "none";
    });

    iconClose.addEventListener("click", () => {
        navListItem.style.display = "block";
        textField.style.display = "none";
        iconClose.style.display = "none";
    });

    window.onscroll = function() {
        if (document.documentElement.scrollTop > 200) {
            document.querySelector(".back-to-home").style.display = "block";
        } else {
            document.querySelector(".back-to-home").style.display = "none";
        }
    };
    </script>
</body>

</html>