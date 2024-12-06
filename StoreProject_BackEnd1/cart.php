<?php
require_once('./config/database.php');
// autoloading
spl_autoload_register(function ($className) {
    require_once("./app/models/$className.php");
});
session_start();
if (isset($_SESSION['admin'])) unset($_SESSION['admin']);
if(isset($_SESSION['search'])) unset($_SESSION['search']);
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
} else {
    $userModel = new UserModel();
    $info = $userModel->getInfoUserByUsername($_SESSION['user']);
}

if(isset($_POST['deleteAllProduct']))
{
    setcookie('cart', '', time() - 3600 * 25);
    header("Location: cart.php");
}

if (isset($_POST['purchase'])) {
    $purchaseModel = new PurchasedProductModel();
    if (isset($_COOKIE['cart'])) {
        $listCart = json_decode($_COOKIE['cart'], true);
        for ($i = 0; $i < count($listCart); ++$i) {
            $purchaseModel->addProductPurchased($_SESSION['user'], (int)$listCart[$i]);
        }
        setcookie('cart', '', time() - 3600 * 25);
        header("Location: cart.php");
    }
}

if (isset($_GET['idBuyProduct'])) {
    $idBuyProduct = $_GET['idBuyProduct'];
    if (!isset($_COOKIE['cart'])) {
        $listProductAdd = [$idBuyProduct];
        setcookie('cart', json_encode($listProductAdd), time() + 3600 * 24);
        header("Location: cart.php");
    } else {
        $listProductAdded = json_decode($_COOKIE['cart'], true);
        if (!in_array($idBuyProduct, $listProductAdded)) {
            array_push($listProductAdded, $idBuyProduct);
            setcookie('cart', json_encode($listProductAdded), time() + 3600 * 24);
            header("Location: cart.php");
        }
    }
}

if (isset($_COOKIE['cart'])) {
    if (isset($_GET['idDeleteProduct'])) {
        $temp = json_decode($_COOKIE['cart'], true);
        $idDeleteProduct = $_GET['idDeleteProduct'];
        $temp = array_diff($temp, array("$idDeleteProduct"));
        if (sizeof($temp) > 0) {
            setcookie('cart', json_encode($temp), time() + 3600 * 24);
        } else {
            setcookie('cart', "", time() - 3600 * 24 - 3);
        }
        header("Location: cart.php");
    }
}

if (isset($_COOKIE['cart'])) {
    $temp = json_decode($_COOKIE['cart'], true);
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $temp = array_diff($temp, array("$id"));
        if (sizeof($temp) > 0) {
            setcookie('cart', json_encode($temp), time() + 3600 * 24);
        } else {
            setcookie('cart', "", time() - 3600 * 24 - 3);
        }
    }
    if (sizeof($temp) > 0) {
        $productModel = new ProductModel();
        $categoryModel = new CategoryModel();
        $listProductAdded = $productModel->getProductByIds($temp);
        $total = 0;
        foreach ($listProductAdded as $priceItem) {
            $total += $priceItem['product_price'];
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
    <title>Cart</title>
    <link rel='shortcut icon' href='./assets/img/icon/shopping-cart.png' />
    <link rel="stylesheet" href="./assets/css/main.css">
    <link rel="stylesheet" href="./assets/css/cart.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
</head>

<style>
    .product__main {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
    }

    .product__main--other-delete i {
        font-size: 20px;
    }
</style>

<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container">
                <div class="nav-bar-logo">
                    <a href="userindex.php">
                        <img src="./assets/img/files/LOGO.png" alt="Logo">
                    </a>
                </div>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
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
                            <img src="./assets/img/avatar/<?php if (is_null($info['avatar'])) {
                                                                echo "avatar_default.jpg";
                                                            } else {
                                                                echo $info['avatar'];
                                                            } ?>" alt="" class="header__navbar-user-img">
                            <span class="header__navbar-user-name"><?php echo $info['fullname'] ?></span>

                            <ul class="header__navbar-user-menu">
                                <li class="header__navbar-user-item">
                                    <a href="profile.php">My Account</a>
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
                                    <a href="index.php">
                                        Log Out
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <?php
                            if (isset($_COOKIE['cart']) && sizeof($temp) > 0) {
                            ?>
                                <div class="header__cart">
                                    <div class="header__cart-wrap">
                                        <a href="#"><i class="fas fa-shopping-cart"></i></a>
                                        <span class="header__cart-notice"><?php echo count($temp) ?></span>

                                        <!-- no cart: header__cart-list--no-cart -->
                                        <div class="header__cart-list">
                                            <img src="./assets/img/cart/nothing_cart.png" alt="" class="header__cart-no-cart-img">
                                            <span class="header__cart-list-no-cart-msg">
                                                No Product
                                            </span>

                                            <!-- has cart -->
                                            <h4 class="header__cart-heading">Products Addeds</h4>
                                            <ul class="header__cart-list-item">
                                                <!-- cart item -->
                                                <?php
                                                foreach ($listProductAdded as $element) :
                                                    $productInCartImageActive = explode(',', $element['product_photo'])[0];
                                                    $productAddedId = $categoryModel->getCategoryIdByProductId($element['id']);
                                                    $productAddedTypeName = $categoryModel->getCategoryName($productAddedId['category_id']);
                                                ?>
                                                    <li class="header__cart-item">
                                                        <img src="./assets/img/products/<?php echo $productInCartImageActive ?>" alt="<?php echo $element['product_name'] ?>" class="header__cart-img">
                                                        <div class="header__cart-item-info">
                                                            <div class="header__cart-item-head">
                                                                <h5 class="header__cart-item-name">
                                                                    <?php echo $element['product_name'] ?></h5>

                                                                <div class="header__cart-item-price-wrap">
                                                                    <span class="header__cart-item-price">
                                                                        $<?php printf('%.2f', $element['product_price']) ?>
                                                                    </span>
                                                                    <span class="header__cart-item-multiply"></span>
                                                                    <span class="header__cart-item-qnt"></span>
                                                                </div>
                                                            </div>

                                                            <div class="header__cart-item-body">
                                                                <span class="header__cart-item-description">
                                                                    Type: <?php echo $productAddedTypeName['category_name'] ?>
                                                                </span>

                                                                <a href="cart.php?idDeleteProduct=<?php echo $element['id'] ?>" class="header__cart-item-remove">Delete</a>
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
                            } else {
                            ?>
                                <div class="header__cart">
                                    <div class="header__cart-wrap">
                                        <a href="#"><i class="header__cart-icon fas fa-shopping-cart"></i></a>
                                        <!-- no cart: header__cart-list--no-cart -->
                                        <div class="header__cart-list header__cart-list--no-cart">
                                            <img src="./assets/img/cart/nothing_cart.png" alt="" class="header__cart-no-cart-img">
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
                            <input autocomplete="off" type="text" id="search" placeholder="Search.." name="findKeyWord" />
                            <button type="submit" class="submit-btn"><i class="fa fa-search"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Seach End -->

        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="header-context">
                        <div class="header-context-left">
                            <div class="header-main">
                                <h5>Cart</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </header>

    <!-- Checkout Begin -->
    <div class="modal js-modal">
        <div class="modal-container js-modal-container">
            <div class="modal-close js-modal-close">
                <i class="fas fa-times"></i>
            </div>

            <div class="modal-header">
                successfully purchase
            </div>
        </div>
    </div>
    <!-- Checkout End -->

    <div class="container" style="padding-bottom: 100px;">
        <div class="row">
            <div class="col">
                <div class="navbar-list">
                    <div class="nav-product">Product</div>
                    <div class="nav-price">Unit price</div>
                    <div class="nav-size">Quantity</div>
                    <div class="nav-total">Amount of money</div>
                    <div class="nav-other">Manipulation</div>
                </div>

                <div class="product-list">
                    <?php
                    if (isset($_COOKIE['cart']) && sizeof($temp) > 0) {
                        foreach ($listProductAdded as $item) {
                            $productInCartImageActive = explode(',', $item['product_photo'])[0];
                    ?>
                            <div class="product__main">
                                <div class="product__main--check">
                                    <!-- thêm class stardust-checkbox--checked để check -->
                                    <label for="" class="stardust-checkbox ">
                                        <input class="stardust-checkbox__input" type="checkbox" name="" id="">
                                        <div class="stardust-checkbox__box"></div>
                                    </label>
                                </div>
                                <div class="product__main--name">
                                    <div class="d-flex">
                                        <a title="<?php echo $item['product_name'] ?>" href="#">
                                            <div class="product__main--name-img" style="background-image: url(./assets/img/products/<?php echo $productInCartImageActive ?>);"></div>
                                        </a>
                                        <div class="product__main--name-title">
                                            <a title="<?php echo $item['product_name'] ?>" class="product__main--name-title-link"><?php echo $item['product_name'] ?></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="product__main--type">
                                    <div class="product__main--type-main">
                                        <div class="product__main--type-content">
                                            <div class="product__main--type-name">Type:</div>
                                            <div class="product__main--type-name2"><?php echo $productAddedTypeName['category_name'] ?></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="product__main--price">
                                    <div>
                                        <div class="product__main--price-content" data-price="<?php echo $item['product_price'] ?>">
                                            $<?php printf('%.2f', $item['product_price']) ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="product__main--size">
                                    <div class="product__main--size-main">
                                        <!-- <button class="product__main--size-minus" >
                                    <svg enable-background="new 0 0 10 10" viewBox="0 0 10 10" x="0" y="0" class="shopee-svg-icon">
                                        <polygon points="4.5 4.5 3.5 4.5 0 4.5 0 5.5 3.5 5.5 4.5 5.5 10 5.5 10 4.5"></polygon>
                                    </svg>
                                </button>
                                <input aria-label="quantity" class="input-qty" max="100" min="1" name="" type="number" value="1">
                                <div class="product__main--size-push" >
                                    <svg enable-background="new 0 0 10 10" viewBox="0 0 10 10" x="0" y="0" class="shopee-svg-icon">
                                        <polygon points="10 4.5 5.5 4.5 5.5 0 4.5 0 4.5 4.5 0 4.5 0 5.5 4.5 5.5 4.5 10 5.5 10 5.5 5.5 10 5.5"></polygon>
                                    </svg>
                                </div> -->
                                        <input type="number" class="form-control text-center qty" min="1" value="1" onchange="calculate()">
                                    </div>
                                </div>
                                <div class="product__main--total">
                                    <span class="product__main--total-price">$<?php printf('%.2f', $item['product_price']) ?></span>
                                </div>
                                <div class="product__main--other">
                                    <a href="cart.php?id=<?php echo $item['id'] ?>" class="product__main--other-delete"><i class="far fa-trash-alt"></i></a>
                                </div>
                            </div>
                    <?php
                        }
                    }
                    ?>
                </div>

            </div>
        </div>
    </div>

    <!-- Footer Begin -->
    <footer class="footer-mid" style="margin-bottom: 100px;">
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
    <!-- Footer End -->

    <!-- Pay Begin -->
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="footer-product" style="position: fixed; bottom: 0; right: 0; left: 0; background: #fff;">
                    <div class="footer-product__main">
                        <?php
                        if (isset($_COOKIE['cart']) && sizeof($temp) > 0) {
                        ?>
                            <div class="footer-product__main--checkall">
                                <!-- thêm class stardust-checkbox--checked để check -->
                                <label for="" class="stardust-checkbox ">
                                    <input class="stardust-checkbox__input" type="checkbox" name="" id="">
                                    <div class="stardust-checkbox__box stardust-checkbox__box-all" onclick="checkAll()"></div>
                                </label>
                            </div>
                            <button class="footer-product__main--btn-checkall" onclick="checkAll()">Select All </button>
                            <form action="cart.php" method="post">
                                <input type="text" hidden="true" name="deleteAllProduct" id="deleteAllProduct" value="1">
                                <button type="submit" class="footer-product__main--btn-delete">Delete</button>
                            </form>
                            <div class="fl-1"></div>

                            <div class="footer-product__main-total">
                                <div class="footer-product__main-total-flex">
                                    <div class="footer-product__main-total-content">
                                        <div class="footer-product__main-name">Total Payment:</div>
                                        <div class="footer-product__main-price">$<?php printf('%.2f', $total) ?></div>
                                        <div class="line"></div>
                                    </div>
                                </div>
                            </div>

                            <form action="cart.php" method="post">
                                <input type="number" value="1" hidden="true" id="purchase" name="purchase">
                                <button href="cart.php?purchase=2" class="footer-product__pay btn-color-primary">
                                    <div class="footer-product__pay-text">Purchase</div>
                                </button>
                            </form>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Pay End -->

    <script src="./assets/js/cart.js"></script>

</body>

</html>