<?php
require_once('./config/database.php');
// autoloading
spl_autoload_register(function ($className) {
    require_once("./app/models/$className.php");
});
session_start();
if (isset($_SESSION['admin']))
    unset($_SESSION['admin']);
if (isset($_SESSION['search']))
    unset($_SESSION['search']);
if (isset($_SESSION['qtyProductInRow']))
    unset($_SESSION['qtyProductInRow']);
if (isset($_SESSION['sort']))
    unset($_SESSION['sort']);
if (isset($_SESSION['qtyProductInRow_category']))
    unset($_SESSION['qtyProductInRow_category']);
if (isset($_SESSION['categorySort']))
    unset($_SESSION['categorySort']);

if (isset($_GET['findKeyWord'])) {
    $_SESSION['findKeyWord'] = $_GET['findKeyWord'];
    $findKeyWord = $_GET['findKeyWord'];
    $productModel = new ProductModel();

    if (isset($_GET['page'])) {
        $page = $_GET['page'];
    } else {
        $page = 1;
    }

    $perpage = 8;

    $findProductList = $productModel->getProductsFindByPage($page, $perpage, $findKeyWord);
    $totalPage = ceil($productModel->getTotalProductFind($findKeyWord) / $perpage);
}

if (isset($_SESSION['findKeyWord'])) {
    $findKeyWord = $_SESSION['findKeyWord'];
    $productModel = new ProductModel();

    if (isset($_GET['page'])) {
        $page = $_GET['page'];
    } else {
        $page = 1;
    }

    $perpage = 8;

    if (isset($_GET['quantityProductInRow'])) {
        $_SESSION['qtyProductInRow_findProduct'] = $_GET['quantityProductInRow'];
    }

    if (isset($_SESSION['qtyProductInRow_findProduct'])) {
        if ($_SESSION['qtyProductInRow_findProduct'] == 2) {
            $perpage = 4;
        } else if ($_SESSION['qtyProductInRow_findProduct'] == 3) {
            $perpage = 6;
        }
    }

    $totalPage = ceil($productModel->getTotalProductFind($findKeyWord) / $perpage);

    if (isset($_GET['sort'])) {
        $_SESSION['findSort'] = $_GET['sort'];
        if ($_GET['sort'] == 1) {
            $findProductList = $productModel->getProductsFindSortUpFollowPrice($page, $perpage, $findKeyWord);
        } else if ($_GET['sort'] == 2) {
            $findProductList = $productModel->getProductsFindSortDownFollowPrice($page, $perpage, $findKeyWord);
        } else if ($_GET['sort'] == 3) {
            $findProductList = $productModel->getProductsFindSortUpFollowStar($page, $perpage, $findKeyWord);
        } else if ($_GET['sort'] == 4) {
            $findProductList = $productModel->getProductsFindSortDownFollowStar($page, $perpage, $findKeyWord);
        } else {
            $findProductList = $productModel->getProductsFindByPage($page, $perpage, $findKeyWord);
        }
    } else if (isset($_SESSION['findSort'])) {
        if ($_SESSION['findSort'] == 1) {
            $findProductList = $productModel->getProductsFindSortUpFollowPrice($page, $perpage, $findKeyWord);
        } else if ($_SESSION['findSort'] == 2) {
            $findProductList = $productModel->getProductsFindSortDownFollowPrice($page, $perpage, $findKeyWord);
        } else if ($_SESSION['findSort'] == 3) {
            $findProductList = $productModel->getProductsFindSortUpFollowStar($page, $perpage, $findKeyWord);
        } else if ($_SESSION['findSort'] == 4) {
            $findProductList = $productModel->getProductsFindSortDownFollowStar($page, $perpage, $findKeyWord);
        } else {
            $findProductList = $productModel->getProductsFindByPage($page, $perpage, $findKeyWord);
        }
    } else {
        $findProductList = $productModel->getProductsFindByPage($page, $perpage, $findKeyWord);
    }
}

$categoryModel = new CategoryModel();
$categoryList = $categoryModel->getAllCategory();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search</title>
    <link rel='shortcut icon' href='./assets/img/icon/findProduct.png' />
    <link rel="stylesheet" href="./assets/css/main.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
        </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
</head>

<style>
    .text-right {
        text-align: right;
    }

    .dropdown {
        text-align: right;
    }

    .product-menu {
        background-color: #F7F7F7;
        padding: 25px;
        margin-bottom: 25px;
    }

    .btn-view {
        border-bottom: 2px solid #dcdcdc;
        width: 36px;
        height: 35px;
        position: relative;
        cursor: pointer;
        padding: 0;
        display: inline-block;
        text-align: center;
        font-size: 0;
        vertical-align: middle;
        margin-right: 3px;
        z-index: 1;
        background: #fff;
    }

    .btn-view:before {
        box-shadow: 13px 0 #dcdcdc;
        background: #dcdcdc;
        content: "";
        top: 13px;
        position: absolute;
        left: 7px;
        width: 8px;
        height: 8px;
        border-radius: 50%;
    }

    .btn-view.active:before {
        background-color: #e97e3d;
        box-shadow: 13px 0 0 #e97e3d;
    }

    .btn-view-3 {
        width: 49px
    }

    .btn-view-3:before {
        box-shadow: 13px 0 #dcdcdc, 26px 0 #dcdcdc;
    }

    .btn-view-3.active:before {
        box-shadow: 13px 0 #e97e3d, 26px 0 #e97e3d;
    }

    .btn-view.active {
        border-color: #e97e3d;
        z-index: 2;
    }

    .btn-view-4 {
        width: 62px;
    }

    .btn-view-4:before {
        box-shadow: 13px 0 #dcdcdc, 26px 0 #dcdcdc, 39px 0 #dcdcdc;
    }

    .btn-view-4.active:before {
        box-shadow: 13px 0 #e97e3d, 26px 0 #e97e3d, 39px 0 #e97e3d;
    }

    @media (max-width: 991px) {
        .category-heading {
            display: block !important;
        }

        .product-menu {
            display: none;
        }
    }

    .btn-secondary,
    .btn-secondary:hover,
    .btn-check:active+.btn-secondary,
    .btn-check:checked+.btn-secondary,
    .btn-secondary.active,
    .btn-secondary:active,
    .show>.btn-secondary.dropdown-toggle,
    .btn-check:focus+.btn-secondary,
    .btn-secondary:focus {
        background-color: #fff;
        border-color: #fff;
    }

    .dropdown-item.active,
    .dropdown-item:active {
        background-color: #e97e3d;
        color: #fff !important;
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

    .category-show {
        cursor: pointer;
    }
</style>

<body>
    <!-- Header Begin -->
    <header>
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container">
                <div class="nav-bar-logo">
                    <a href="index.php">
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
                            <a href="index.php" class="nav-bar-link active">
                                <span class="nav-bar-title">
                                    Home
                                </span>
                            </a>
                        </li>
                        <li class="nav-bar-item">
                            <a href="#shop" class="nav-bar-link">
                                <span class="nav-bar-title">
                                    Shop
                                </span>
                            </a>
                        </li>
                        <li class="nav-bar-item">
                            <a href="#contact" class="nav-bar-link">
                                <span class="nav-bar-title">
                                    Contact
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
                        <li>
                            <a href="formIn.php"><i class="far fa-user"></i></a>
                        </li>
                        <li>
                            <div class="header__cart-wrap">
                                <a href="#"><i class="fas fa-shopping-cart"></i></a>

                                <div class="header__cart-list header__cart-list--no-cart">
                                    <img src="./assets/img/cart/nothing_cart.png" alt=""
                                        class="header__cart-no-cart-img">
                                    <span class="header__cart-list-no-cart-msg">
                                        No Product
                                    </span>
                                </div>
                            </div>
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
                        <form action="search.php" class="example" method="get">
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

    <!-- Category Heading Begin -->
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="category-heading">
                    <span class="category-show">
                        <i class="fas fa-sliders-h category-heading-icon"></i>
                        Filter
                    </span>

                    <div class="col">
                        <div class="dropdown">
                            <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="bi bi-funnel-fill" viewBox="0 0 16 16">
                                    <path
                                        d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5v-2z" />
                                </svg>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <li><a class="dropdown-item" href="index.php?sort=5">None <svg
                                            xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                                            <path
                                                d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z" />
                                        </svg></a></li>
                                <li><a class="dropdown-item" href="index.php?sort=1">Price: low to hight <svg
                                            xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-arrow-up" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd"
                                                d="M8 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L7.5 2.707V14.5a.5.5 0 0 0 .5.5z" />
                                        </svg></a></li>
                                <li><a class="dropdown-item" href="index.php?sort=2">Price: hight to low <svg
                                            xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-arrow-down" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd"
                                                d="M8 1a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L7.5 13.293V1.5A.5.5 0 0 1 8 1z" />
                                        </svg></a></li>
                                <li><a class="dropdown-item" href="index.php?sort=3">Star: low to hight <svg
                                            xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-arrow-up" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd"
                                                d="M8 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L7.5 2.707V14.5a.5.5 0 0 0 .5.5z" />
                                        </svg></a></li>
                                <li><a class="dropdown-item" href="index.php?sort=4">Star: hight to low <svg
                                            xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-arrow-down" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd"
                                                d="M8 1a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L7.5 13.293V1.5A.5.5 0 0 1 8 1z" />
                                        </svg></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Category Heading Begin -->

    <!-- Show Category Heading Begin -->
    <div class="modal js-modal">
        <div class="modal-container js-modal-container">
            <div class="modal-close js-modal-close">
                <i class="fas fa-times"></i>
            </div>

            <header class="modal-header">
                <i class="fas fa-sliders-h category-heading-icon"></i>
                Category
            </header>

            <div class="modal-body">
                <ul class="category-list">
                    <!-- Thêm category-item--active để active -->
                    <li class="category-item category-item--active">
                        <a href="#" class="category-item__link">
                            <!-- <i class="fas fa-arrow-right"></i> -->
                            <svg viewBox="0 0 4 7" class="category-svg-icon">
                                <polygon points="4 3.5 0 0 0 7"></polygon>
                            </svg>
                            Product
                        </a>
                    </li>
                    <?php
                    foreach ($categoryList as $item):
                        ?>
                        <li class="category-item">
                            <a href="category.php?id=<?php echo $item['id'] ?>"
                                class="category-item__link"><?php echo $item['category_name'] ?></a>
                        </li>
                        <?php
                    endforeach
                    ?>
                </ul>
            </div>
        </div>
    </div>
    <!-- Show Category Heading End -->


    <!-- Category Begin -->
    <div class="main-content" id="shop">
        <div class="container">
            <div class="row">
                <div class="col-md-2 col-12 js-sidebar">
                    <div class="category">
                        <h3 class="category__heading">
                            <i class="container__category-heading-icon fas fa-list-ul"></i>
                            Category
                        </h3>

                        <ul class="category-list">
                            <?php
                            foreach ($categoryList as $item):
                                ?>
                                <li
                                    class="category-item <?php echo (isset($_GET['id']) && $item['id'] == $_GET['id']) ? 'category-item--active' : '' ?>">
                                    <a href="category.php?id=<?php echo $item['id'] ?>"
                                        class="category-item__link"><?php echo $item['category_name'] ?></a>
                                </li>
                                <?php
                            endforeach
                            ?>
                        </ul>
                    </div>
                </div>

                <!-- Product Begin -->
                <div class="col">
                    <div class="product-style">
                        <div class="product-menu">
                            <div class="row">
                                <div class="col">
                                    <?php
                                    if (isset($_SESSION['qtyProductInRow_findProduct']) && $_SESSION['qtyProductInRow_findProduct'] == 2) {
                                        ?>
                                        <a href="search.php?quantityProductInRow=2" class="btn-view btn-view-2 active"></a>
                                        <a href="search.php?quantityProductInRow=3" class="btn-view btn-view-3"></a>
                                        <a href="search.php?quantityProductInRow=4" class="btn-view btn-view-4"></a>
                                        <?php
                                    } else if (isset($_SESSION['qtyProductInRow_findProduct']) && $_SESSION['qtyProductInRow_findProduct'] == 3) {
                                        ?>
                                            <a href="search.php?quantityProductInRow=2" class="btn-view btn-view-2"></a>
                                            <a href="search.php?quantityProductInRow=3" class="btn-view btn-view-3 active"></a>
                                            <a href="search.php?quantityProductInRow=4" class="btn-view btn-view-4"></a>
                                        <?php
                                    } else if (isset($_SESSION['qtyProductInRow_findProduct']) && $_SESSION['qtyProductInRow_findProduct'] == 4) {
                                        ?>
                                                <a href="search.php?quantityProductInRow=2" class="btn-view btn-view-2"></a>
                                                <a href="search.php?quantityProductInRow=3" class="btn-view btn-view-3"></a>
                                                <a href="search.php?quantityProductInRow=4" class="btn-view btn-view-4 active"></a>
                                        <?php
                                    } else {
                                        ?>
                                                <a href="search.php?quantityProductInRow=2" class="btn-view btn-view-2"></a>
                                                <a href="search.php?quantityProductInRow=3" class="btn-view btn-view-3"></a>
                                                <a href="search.php?quantityProductInRow=4" class="btn-view btn-view-4"></a>
                                        <?php
                                    }
                                    ?>
                                </div>

                                <div class="col">
                                    <div class="dropdown">
                                        <a class="btn btn-secondary dropdown-toggle" href="#" role="button"
                                            id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-funnel-fill" viewBox="0 0 16 16">
                                                <path
                                                    d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5v-2z" />
                                            </svg>
                                        </a>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                            <li><a class="dropdown-item" href="search.php?sort=5">None <svg
                                                        xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                                                        <path
                                                            d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z" />
                                                    </svg></a></li>
                                            <li><a class="dropdown-item" href="search.php?sort=1">Price: low to hight
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        fill="currentColor" class="bi bi-arrow-up" viewBox="0 0 16 16">
                                                        <path fill-rule="evenodd"
                                                            d="M8 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L7.5 2.707V14.5a.5.5 0 0 0 .5.5z" />
                                                    </svg></a></li>
                                            <li><a class="dropdown-item" href="search.php?sort=2">Price: hight to low
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        fill="currentColor" class="bi bi-arrow-down"
                                                        viewBox="0 0 16 16">
                                                        <path fill-rule="evenodd"
                                                            d="M8 1a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L7.5 13.293V1.5A.5.5 0 0 1 8 1z" />
                                                    </svg></a></li>
                                            <li><a class="dropdown-item" href="search.php?sort=3">Star: low to hight
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        fill="currentColor" class="bi bi-arrow-up" viewBox="0 0 16 16">
                                                        <path fill-rule="evenodd"
                                                            d="M8 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L7.5 2.707V14.5a.5.5 0 0 0 .5.5z" />
                                                    </svg></a></li>
                                            <li><a class="dropdown-item" href="search.php?sort=4">Star: hight to low
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        fill="currentColor" class="bi bi-arrow-down"
                                                        viewBox="0 0 16 16">
                                                        <path fill-rule="evenodd"
                                                            d="M8 1a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L7.5 13.293V1.5A.5.5 0 0 1 8 1z" />
                                                    </svg></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <?php
                            foreach ($findProductList as $item):
                                $productImageActive = explode(',', $item['product_photo'])[0];
                                if (isset($_SESSION['qtyProductInRow_findProduct']) && $_SESSION['qtyProductInRow_findProduct'] == 2) {
                                    ?>
                                    <div class="main-product col-sm-6 col-6 col-lg-6">
                                        <div class="product-card">
                                            <div class="product-card__image-wr">
                                                <a href="product.php?id=<?php echo $item['id'] ?>" style="max-width: 480px;">
                                                    <img src="./assets/img/products/<?php echo $productImageActive ?>"
                                                        alt="<?php echo $item['product_name'] ?>">
                                                </a>
                                            </div>

                                            <a href="product.php?id=<?php echo $item['id'] ?>">
                                                <div class="product-card-buy">
                                                    <i class="fas fa-shopping-cart"></i>
                                                </div>
                                            </a>

                                            <div class="product-card__info">
                                                <a href="product.php?id=<?php echo $item['id'] ?>" class="product-card__name">
                                                    <?php echo $item['product_name'] ?>
                                                </a>

                                                <div class="product-card__price">
                                                    <span class="money">$<?php printf("%.2f", $item['product_price']) ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                } else if (isset($_SESSION['qtyProductInRow_findProduct']) && $_SESSION['qtyProductInRow_findProduct'] == 3) {
                                    ?>
                                        <div class="main-product col-sm-6 col-6 col-lg-4">
                                            <div class="product-card">
                                                <div class="product-card__image-wr">
                                                    <a href="product.php?id=<?php echo $item['id'] ?>" style="max-width: 480px;">
                                                        <img src="./assets/img/products/<?php echo $productImageActive ?>"
                                                            alt="<?php echo $item['product_name'] ?>">
                                                    </a>
                                                </div>

                                                <a href="product.php?id=<?php echo $item['id'] ?>">
                                                    <div class="product-card-buy">
                                                        <i class="fas fa-shopping-cart"></i>
                                                    </div>
                                                </a>

                                                <div class="product-card__info">
                                                    <a href="product.php?id=<?php echo $item['id'] ?>" class="product-card__name">
                                                    <?php echo $item['product_name'] ?>
                                                    </a>

                                                    <div class="product-card__price">
                                                        <span class="money">$<?php printf("%.2f", $item['product_price']) ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                } else {
                                    ?>
                                        <div class="main-product col-sm-6 col-6 col-lg-3">
                                            <div class="product-card">
                                                <div class="product-card__image-wr">
                                                    <a href="product.php?id=<?php echo $item['id'] ?>" style="max-width: 480px;">
                                                        <img src="./assets/img/products/<?php echo $productImageActive ?>"
                                                            alt="<?php echo $item['product_name'] ?>">
                                                    </a>
                                                </div>

                                                <a href="product.php?id=<?php echo $item['id'] ?>">
                                                    <div class="product-card-buy">
                                                        <i class="fas fa-shopping-cart"></i>
                                                    </div>
                                                </a>

                                                <div class="product-card__info">
                                                    <a href="product.php?id=<?php echo $item['id'] ?>" class="product-card__name">
                                                    <?php echo $item['product_name'] ?>
                                                    </a>

                                                    <div class="product-card__price">
                                                        <span class="money">$<?php printf("%.2f", $item['product_price']) ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                }
                                ?>
                                <?php
                            endforeach
                            ?>
                        </div>
                        <?php
                        if ($totalPage > 1) {
                            ?>
                            <div class="pagination">
                                <?php
                                $previous = $page;
                                $next = $page;
                                if ($page > 1) {
                                    ?>
                                    <span class="next">
                                        <a href="search.php?page=<?php if ($previous > 1) {
                                            $previous--;
                                        }
                                        echo $previous ?>">←</a>
                                    </span>
                                    <?php
                                }
                                ?>
                                <?php
                                for ($i = 1; $i <= $totalPage; ++$i) {
                                    if ($i == $page) {
                                        ?>
                                        <span class="page current">
                                            <a href="search.php?page=<?php echo $i ?>"><?php echo $i ?></a>
                                        </span>
                                        <?php
                                    } else {
                                        ?>
                                        <span class="page">
                                            <a href="search.php?page=<?php echo $i ?>"><?php echo $i ?></a>
                                        </span>
                                        <?php
                                    }
                                }
                                ?>
                                <?php
                                if ($page < $totalPage) {
                                    ?>
                                    <span class="next">
                                        <a href="search.php?page=<?php if ($next < $totalPage) {
                                            $next++;
                                        }
                                        echo $next ?>">→</a>
                                    </span>
                                    <?php
                                }
                                ?>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
            <!-- Product End -->

        </div>
    </div>
    <!-- Category End -->

    <!-- Footer Begin -->
    <div id="contact" class="footer-container">
        <section class="footer-top text-center">
            <div class="container">
                <div class="footer__newsletter mx-auto">
                    <div class="footer__newsletter_title">
                        <h2 class="font-weight-bold">
                            Get in touch
                        </h2>
                        <p class="font-family-2">Subcrible for latest stories and promotions (35% save)</p>
                    </div>
                    <div class="form-vertical">
                        <form method="post" action="/contact#contact_form" id="contact_form" accept-charset="UTF-8"
                            class="contact-form">
                            <input type="hidden" name="form_type" value="customer" />
                            <input type="hidden" name="utf8" value="✓" />
                            <input type="hidden" name="contact[tags]" value="newsletter">
                            <div class="input-group form-group">
                                <input class="form-control py-4" type="email" value="" placeholder="Your email"
                                    name="contact[email]" id="NewsletterEmail-" autocorrect="off" autocapitalize="off">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-theme gradient-theme" name="commit">
                                        Subscribe
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
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
                    <img class="mb-4 lazyloaded" src="./assets/img/files/payma.png"></img>
                </div>
            </div>
        </div>

        <a href="#">
            <div class="back-to-home">
                <i class="fa fa-chevron-up"></i>
            </div>
        </a>
    </footer>
    <!-- Footer End -->
</body>

<script src="./assets/js/index.js"></script>

</html>