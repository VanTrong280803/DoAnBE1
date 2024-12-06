<?php
require_once('./config/database.php');
spl_autoload_register(function ($className) {
    require_once("./app/models/$className.php");
});
session_start();
if (isset($_SESSION['admin']))
    unset($_SESSION['admin']);
if (isset($_SESSION['search']))
    unset($_SESSION['search']);
if (isset($_SESSION['user'])) {
    $favoriteModel = new ProductModel();
    $userModel = new UserModel();
    $info = $userModel->getInfoUserByUsername($_SESSION['user']);
    $listFavoriteProducts = $favoriteModel->getFavoriteProducts($_SESSION['user']);
} else {
    header("Location: index.php");
}
// header('Content-Type: application/json'); 
if (isset($_POST['id']) && isset($_POST['action'])) {
    $id = $_POST['id'];
    $action = $_POST['action'];
    
    header('Content-Type: application/json');
    $result = array();
    
    if ($action == 'remove') {
        $favoriteModel = new ProductModel();
        $username = $_SESSION['user'];
        $result['success'] = $favoriteModel->removeFavoriteProducts($id, $username);
    }

    echo json_encode($result);
    exit;
}   


$category = new CategoryModel();
$productModel = new ProductModel();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Favorites Product</title>
    <link rel='shortcut icon' href='./assets/img/icon/add-to-cart.png' />
    <link rel="stylesheet" href="./assets/css/main.css">
    <link rel="stylesheet" href="./assets/css/purchaseorder.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
        </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
</head>

<style>
    .product__oder--main {
        margin-left: 0 !important;
    }

    .product__grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }

    .product__card {
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 8px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: box-shadow 0.3s ease, transform 0.3s ease;
    }

    .product__card:hover {
        transform: translateY(-5px);
        box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.1);
    }

    .product__card-img {
        height: 150px;
        background-size: cover;
        background-position: center;
        border-bottom: 1px solid #ddd;
    }

    .product__card-info {
        padding: 15px;
        text-align: center;
    }

    .product__card-title {
        font-size: 16px;
        font-weight: bold;
        color: #333;
        margin-bottom: 10px;
    }

    .product__card-price {
        font-size: 14px;
        color: #555;
    }

    .product__card-footer {
        text-align: center;
        padding: 10px;
        background: #f8f8f8;
    }

    .product__card-remove {
        display: inline-block;
        padding: 10px 15px;
        background-color: #ee4d2d;
        color: #fff;
        border-radius: 5px;
        text-decoration: none;
        transition: background-color 0.3s ease, transform 0.2s ease;
    }

    .product__card-remove:hover {
        background-color: #d84315;
        transform: scale(1.05);
    }

    .product__oder--main-content {
        background-color: #ffffff !important;
    }

    @media (max-width: 768px) {
        .product__grid {
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
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
                            if (isset($_COOKIE['cart'])) {
                                $temp = json_decode($_COOKIE['cart'], true);
                                $listProductInCart = $productModel->getProductByIds($temp);
                                ?>
                                <div class="header__cart">
                                    <div class="header__cart-wrap">
                                        <a href="cart.php"><i class="fas fa-shopping-cart"></i></a>
                                        <span class="header__cart-notice"><?php echo count($temp) ?></span>

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
                                                foreach ($listProductInCart as $element):
                                                    $productImageActive = explode(',', $element['product_photo'])[0];
                                                    $productAddedId = $category->getCategoryIdByProductId($element['id']);
                                                    $productAddedTypeName = $category->getCategoryName($productAddedId['category_id']);
                                                    ?>
                                                    <li class="header__cart-item">
                                                        <img src="./assets/img/products/<?php echo $productImageActive ?>"
                                                            alt="<?php echo $element['product_name'] ?>"
                                                            class="header__cart-img">
                                                        <div class="header__cart-item-info">
                                                            <div class="header__cart-item-head">
                                                                <h5 class="header__cart-item-name">
                                                                    <?php echo $element['product_name'] ?>
                                                                </h5>

                                                                <div class="header__cart-item-price-wrap">
                                                                    <span class="header__cart-item-price">
                                                                        $<?php printf('%.2f', $element['product_price']) ?>
                                                                    </span>
                                                                    <span class="header__cart-item-multiply">x</span>
                                                                    <span class="header__cart-item-qnt">1</span>
                                                                </div>
                                                            </div>

                                                            <div class="header__cart-item-body">
                                                                <span class="header__cart-item-description">
                                                                    Type:
                                                                    <?php echo $productAddedTypeName['category_name'] ?>
                                                                </span>
                                                                <a href="purchasedproduct.php?idDeleteProduct=<?php echo $element['id'] ?>"
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
                            } else {
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

        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="header-context">
                        <div class="header-context-left">
                            <div class="header-main">
                                <h5>Favorites Product</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- Header End -->

    <div class="container container-style">
        <div class="product__oder--main">
            <div class="product__oder--main-content">
                <div class="product__grid">
                    <?php foreach ($listFavoriteProducts as $item): ?>
                        <?php $productImage = explode(',', $item['product_photo'])[0]; ?>
                        <div class="product__card">
                            <a href="userproduct.php?id=<?php echo $item['id']; ?>" class="product__card-link">
                                <div class="product__card-img"
                                    style="background-image: url('./assets/img/products/<?php echo $productImage; ?>');">
                                </div>
                                <div class="product__card-info">
                                    <h3 class="product__card-title">
                                        <?php echo htmlspecialchars($item['product_name']); ?>
                                    </h3>
                                    <p class="product__card-price">
                                        Price: $<?php printf('%.2f', $item['product_price']); ?>
                                    </p>
                                </div>
                            </a>
                            <div class="product__card-footer">
                                <a href="javascript:void(0);" class="product__card-remove"
                                    data-id="<?php echo $item['id']; ?>">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const removeButtons = document.querySelectorAll('.product__card-remove');
            removeButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const productId = this.getAttribute('data-id');
                    if (confirm("Are you sure you want to remove this product from your favorites?")) {
                        fetch('favoriteproduct.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: 'id=' + productId + '&action=remove'
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert('Product removed successfully!');
                                const productCard = this.closest('.product__card');
                                productCard.remove();
                            } else {
                                alert('Error removing product.');
                            }
                        });
                    }
                });
            });
        });
    </script>
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

        <!-- <a href="#">
                <div class="back-to-home">
                    <i class="fa fa-chevron-up"></i>
                </div>
            </a> -->
    </footer>
    <!-- Footer End -->
</body>

</html>

