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
if (isset($_SESSION['qtyProductInRow_findProduct']))
    unset($_SESSION['qtyProductInRow_findProduct']);
if (isset($_SESSION['findSort']))
    unset($_SESSION['findSort']);
if (isset($_GET['id'])) {
    $productModel = new ProductModel();
    $id = $_GET['id'];
    $productModel->viewProduct($id);
    $item = $productModel->getProductById($id);
    $category = new CategoryModel();
    $categoryId = $category->getCategoryIdByProductId($id);
    $categoryName = $category->getCategoryName($categoryId['category_id']);

    $relatedProducts = $productModel->getRelatedProducts($categoryId['category_id'], $id);
}
if (isset($_POST['id'], $_POST['action'])) {
    $id = (int) $_POST['id'];
    $action = $_POST['action'];
    $result = [];

    if ($action === 'rate') {
        if (isset($_POST['rating']) && isset($_SESSION['user'])) {
            $rating = (float) $_POST['rating'];
            $username = $_SESSION['user'];

            $productModel = new ProductModel();
            $response = $productModel->addOrUpdateRating($id, $rating, $username);

            if ($response) {
                $newAverage = $productModel->getAverageRating($id);
                $userRating = $productModel->hasRated($id, $username); // Lấy lại rating của người dùng

                // Tạo HTML cho sao trung bình
                $averageRatingHtml = '';
                $fullStars = floor($newAverage);
                $halfStars = ($newAverage - $fullStars) >= 0.5 ? 1 : 0;
                $emptyStars = 5 - $fullStars - $halfStars;

                for ($i = 0; $i < $fullStars; $i++) {
                    $averageRatingHtml .= '<i class="fas fa-star star-yellow"></i>';
                }
                if ($halfStars) {
                    $averageRatingHtml .= '<i class="fas fa-star-half-alt star-yellow"></i>';
                }
                for ($i = 0; $i < $emptyStars; $i++) {
                    $averageRatingHtml .= '<i class="fas fa-star"></i>';
                }

                $result['success'] = true;
                $result['message'] = 'Your rating has been saved.';
                $result['average_rating'] = $newAverage;
                $result['average_rating_html'] = $averageRatingHtml;
                $result['user_rating'] = $userRating; // Trả về rating của người dùng
            } else {
                $result['success'] = false;
                $result['error'] = 'Error saving rating.';
            }
        } else {
            $result['success'] = false;
            $result['error'] = 'Missing rating or user not logged in.';
        }
    } else {
        $result['success'] = false;
        $result['error'] = 'Invalid action.';
    }

    header('Content-Type: application/json');
    echo json_encode($result);
    exit;
}


$productModel = new ProductModel();
$username = isset($_SESSION['user']) ? $_SESSION['user'] : null;
$userHasRated = $productModel->hasRated($item['id'], $username);
if ($userHasRated) {
    $userRating = $userHasRated;
} else {
    $userRating = null;
}
$productStar = $productModel->getAverageRating($item['id']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $item['product_name'] ?></title>
    <link rel='shortcut icon' href='./assets/img/icon/product.png' />
    <link rel="stylesheet" href="./assets/css/main.css">
    <!-- <link rel="stylesheet" href="./assets/css/product.css"> -->

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
    .star-yellow {
        color: yellow;
    }

    .fas.fa-star-half-alt {
        color: yellow;
    }

    span.carousel-control-next-icon,
    span.carousel-control-prev-icon {
        background-color: #333 !important;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    .product-photo {
        padding: 100px 0;
    }

    .product-content {
        padding: 100px 0rem 100px 3.5rem;
    }

    .product-photo img {
        width: 100%;
    }

    .product-title {
        font-size: 30px;
        margin-bottom: 15px;
        color: #222;
        font-weight: 600;
    }

    .review {
        margin-bottom: 10px;
        display: inline-block;
    }

    .review .spr-badge-starrating {
        margin-right: 8px;
    }

    .star-list {
        margin: 0 3px 0 0;
        font-size: calc(14px - 2px);
        padding: 0 1px;
        opacity: 1;
        color: #666;
    }

    .star-list i {
        cursor: pointer;
        font-size: 20px;
    }

    .review-badge {
        color: #666;
        font-size: calc(14px - 2px);
        top: 1px;
    }

    .product_price {
        font-size: 30px;
        color: var(--primary-color);
    }

    .product-info p {
        margin-bottom: 0;
        line-height: 26px;
    }

    .text-body {
        color: #212529 !important;
    }

    .product-info .text-body {
        min-width: 80px;
        display: inline-block;
        text-transform: capitalize;
        font-size: 14px;
        font-weight: 600;
    }

    .small,
    small {
        font-size: 80%;
        font-weight: 400;
    }

    .product-info p.product-availability {
        margin-top: 5px;
        color: #de3618;
        font-size: 12px;
    }

    .product-info p.product-availability.available {
        color: #4cbb6c;
    }

    .product-description {
        padding-bottom: 1.5rem !important;
    }

    .product-form-submit {
        display: flex;
        align-items: center;
        padding-top: 10px;
        padding-top: 1.5rem !important;
    }

    .product-form-item {
        flex: 0 0 130px;
    }

    .product-form-submit .product-form__cart-submit {
        background: #222;
        transition: all .3s ease-out 0s;
        padding: 20px 100px;
    }

    .product-form-submit .product-form__cart-submit span {
        color: #fff;
    }

    .product-form-submit .product-form__cart-submit:hover {
        background: #e97e3d;
    }

    .product-form__cart-submit {
        padding-left: 5px;
        padding-right: 5px;
        white-space: normal;
        flex: 1;
    }

    .product-buynow {
        flex-basis: 50%;
        display: flex;
        margin-left: 20px;
    }


    @media (max-width: 987px) {
        .product-form-submit {
            display: block !important;
            margin-bottom: 10rem;
        }

        button.btn.product-form__cart-submit {
            width: -webkit-fill-available;
            margin: 15px 0;
        }

        .product-buynow {
            margin-left: 0px !important;
        }

        .product-content {
            padding: 0 !important;
        }

        .product-form-submit .product-form__cart-submit {
            width: 100% !important;
        }

    }


    @media (max-width: 762px) {
        .product-photo img {
            width: 100%;
        }

        .product-photo {
            padding: 25px !important;
        }
    }

    span.carousel-control-next-icon,
    span.carousel-control-prev-icon {
        background-color: #333 !important;
    }

    .product-evaluate {
        display: flex;
        justify-content: space-around;
        margin-top: 25px;
    }

    .product-view {
        display: flex !important;
        align-items: center !important;
    }

    .product-social {
        display: flex;
        align-items: center;
    }

    span.badge.text-bg-warning {
        padding: 11px;
    }

    .product-social-text {
        font-size: 20px;
        color: #000;
        font-family: "Harmonia Sans", sans-serif;
        margin-right: 5px;
    }

    .icon-social {
        font-size: 30px;
    }

    i.fab.fa-facebook-messenger.icon-social {
        color: #0384FF;
    }

    i.fab.fa-facebook.icon-social {
        color: #3B5999;
    }

    i.fab.fa-pinterest.icon-social {
        color: #DE0217;
    }

    i.fab.fa-twitter.icon-social {
        color: #10C2FF;
    }

    @media (max-width: 987px) {
        .product-form-submit .product-form__cart-submit {
            width: 100% !important;
            margin-top: 5px;
        }

        .product-social-text {
            font-size: 15px;
        }

        .icon-social {
            font-size: 20px;
        }
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

    .badge {
        margin-left: 5px;
    }

    @media (max-width: 1023px) {
        .header__cart-list::after {
            border-color: transparent transparent transparent transparent;
        }
    }

    .heart-icon-unclick {
        font-size: 25px;
        color: #FF424F;
    }

    .heart-icon-click {
        font-size: 25px;
        color: #FF424F;
        display: none;
    }

    .btn:focus {
        box-shadow: none !important;
    }

    .btn-check:checked+.btn,
    .btn.active,
    .btn.show,
    .btn:first-child:active,
    :not(.btn-check)+.btn:active {
        background-color: #fff !important;
        border-color: #fff !important;
    }
</style>

<body>
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
                            <div class="header__cart">
                                <div class="header__cart-wrap">
                                    <i class="header__cart-icon fas fa-shopping-cart"></i>

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

    <div class="container">
        <div class="row">
            <div class="col-lg-5 col-md-6">
                <div class="product-photo">
                    <!-- <img src="./assets/img/products/<?php echo $item['product_photo'] ?>" alt="beoplay white" class="img-fluid"> -->
                    <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-indicators">
                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0"
                                class="active" aria-current="true" aria-label="Slide 1"></button>
                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1"
                                aria-label="Slide 2"></button>
                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2"
                                aria-label="Slide 3"></button>
                        </div>
                        <div class="carousel-inner">
                            <?php
                            $arrPhotoItem = explode(',', $item['product_photo']);
                            if (count($arrPhotoItem) === 1) {
                                ?>
                                <div class="carousel-item active">
                                    <img src="./assets/img/products/<?php echo $arrPhotoItem[0] ?>" class="d-block w-100"
                                        alt="beoplay-white">
                                </div>
                                <?php
                            } else {
                                for ($i = 0; $i < count($arrPhotoItem); ++$i) {
                                    if ($i === 0) {
                                        ?>
                                        <div class="carousel-item active">
                                            <img src="./assets/img/products/<?php echo $arrPhotoItem[$i] ?>" class="d-block w-100"
                                                alt="beoplay-white">
                                        </div>
                                        <?php
                                    } else {
                                        ?>
                                        <div class="carousel-item">
                                            <img src="./assets/img/products/<?php echo $arrPhotoItem[$i] ?>" class="d-block w-100"
                                                alt="beoplay-white">
                                        </div>
                                        <?php
                                    }
                                }
                            }
                            ?>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>

                    <div class="product-evaluate">
                        <div class="product-social">
                            <div class="product-social-text">
                                Chia sẻ:
                            </div>
                            <div class="product-social-icon">
                                <a href="#">
                                    <i class="fab fa-facebook-messenger icon-social"></i>
                                </a>
                                <a href="#">
                                    <i class="fab fa-facebook icon-social"></i>
                                </a>
                                <a href="#">
                                    <i class="fab fa-pinterest icon-social"></i>
                                </a>
                                <a href="#">
                                    <i class="fab fa-twitter icon-social"></i>
                                </a>
                            </div>
                        </div>
                        <div class="product-view">
                            <span class="badge text-bg-warning">
                                <?php
                                $view = (float) $item['product_view'];
                                if ($view >= 1000) {
                                    $view = (float) $item['product_view'] / 1000;
                                    ?>
                                    <?php printf('%.1fK', $view) ?> Viewer
                                    <?php
                                } else if ($view >= 1000000) {
                                    $view = (float) $item['product_view'] / 1000000;
                                    ?>
                                    <?php printf('%.1fM', $view) ?> Viewer
                                    <?php
                                } else {
                                    ?>
                                    <?php printf('%d', $view) ?> Viewer
                                    <?php
                                }
                                ?>
                            </span>
                            <form action="#navbar" method="post"
                                onsubmit="return confirm('Please login to be able to like the product!')">
                                <input type="hidden" id="likedId" name="likedId" value="">
                                <button type="submit" class="btn badge text-bg-danger">
                                    <i class="bi bi-heart-fill"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-7 col-12 col-md-6">
                <div class="product-content">
                    <div class="product-meta">
                        <h1 class="product-title"><?php echo $item['product_name'] ?></h1>
                        <div class="review" data-id="<?php echo $item['id']; ?>">
                           

                            <!-- Thanh sao cá nhân -->
                            <?php if (!$userHasRated): ?>
                                <div class="user-rating">
                                    <span class="star-list">
                                        <?php
                                        for ($i = 1; $i <= 5; $i++) {
                                            echo '<i class="fas fa-star" data-id="' . $item['id'] . '" data-rating="' . $i . '"></i>';
                                        }
                                        ?>
                                    </span>
                                    <span class="review-action">Click to rate</span>
                                </div>
                            <?php else: ?>
                                <div class="user-rating">
                                    <span>Your rating:
                                        <strong><?php echo $userRating; ?> stars</strong>
                                    </span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <p class="product_price">$<?php printf('%.2f', $item['product_price']) ?></p>
                        <div class="product-info">
                            <p class="product-vendor">
                                <smaill class="text-body">Vendor: </smaill>
                                <smaill>GPDOT</smaill>
                            </p>
                            <p class="product-type">
                                <smaill class="text-body">Type: </smaill>
                                <smaill><?php echo $categoryName['category_name'] ?></smaill>
                            </p>
                            <p class="product-availability available">
                                <i class="fas fa-check"></i>
                                IN STOCK
                            </p>
                        </div>

                        <div class="product-description">
                            <?php echo $item['product_description'] ?>
                        </div>
                    </div>
                    <div class="product-form-submit">
                        <div class="product-form-add">
                            <a href="#" class="btn product-form__cart-submit">
                                <span>ADD TO CART</span>
                            </a>
                        </div>
                        <div class="product-buynow">
                            <a href="#" class="btn product-form__cart-submit">
                                <span>BUY IT NOW</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="related-products container">
        <h2>Sản phẩm liên quan</h2>
        <div class="row">
            <?php foreach ($relatedProducts as $relatedProduct) { ?>
                <div class="col-md-3">
                    <div class="product-card">
                        <a href="userproduct.php?id=<?php echo $relatedProduct['id'] ?>">
                            <img src="./assets/img/products/<?php echo explode(',', $relatedProduct['product_photo'])[0] ?>"
                                alt="<?php echo $relatedProduct['product_name'] ?>" class="img-fluid">
                            <h3><?php echo $relatedProduct['product_name'] ?></h3>
                            <p class="price">$<?php printf('%.2f', $relatedProduct['product_price']) ?></p>
                        </a>
                    </div>
                </div>
            <?php } ?>
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
        const heartUnclick = document.querySelector(".heart-icon-unclick");
        const heartClick = document.querySelector(".heart-icon-click");

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

        heartUnclick.addEventListener("click", () => {
            heartUnclick.style.display = "none";
            heartClick.style.display = "block";
        });

        heartClick.addEventListener("click", () => {
            heartClick.style.display = "none";
            heartUnclick.style.display = "block";
        });

        window.onscroll = function () {
            if (document.documentElement.scrollTop > 200) {
                document.querySelector(".back-to-home").style.display = "block";
            } else {
                document.querySelector(".back-to-home").style.display = "none";
            }
        };
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const stars = document.querySelectorAll('.user-rating .fa-star');

            stars.forEach(function (star) {
                star.addEventListener('click', function () {
                    const rating = this.getAttribute('data-rating');
                    const productId = this.getAttribute('data-id');


                    updateStarsDisplay(productId, rating);

                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', 'userproduct.php', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.send('id=' + productId + '&action=rate&rating=' + rating);

                    xhr.onload = function () {
                        if (xhr.status == 200) {
                            try {
                                const response = JSON.parse(xhr.responseText);
                                if (response.success) {
                                    alert('Thank you for rating!');
                                    location.reload();
                                    const averageStars = document.querySelector(
                                        `.review[data-id="${productId}"] .average-rating .star-list`
                                    );
                                    averageStars.innerHTML = response.average_rating_html;
                                    const userRatingElement = document.querySelector(
                                        `.review[data-id="${productId}"] .user-rating`
                                    );
                                    userRatingElement.innerHTML =
                                        'Your rating: <strong>' + response.user_rating + ' stars</strong>';
                                } else {
                                    alert(response.error);
                                }
                            } catch (e) {
                                console.error('Error parsing response:', e);
                            }
                        }
                    };
                });
            });
            function updateStarsDisplay(productId, rating) {
                const starList = document.querySelector(`.review[data-id="${productId}"] .user-rating .star-list`);
                starList.innerHTML = '';
                const fullStars = Math.floor(rating);
                const halfStars = (rating - fullStars) >= 0.5 ? 1 : 0;
                const emptyStars = 5 - fullStars - halfStars;
                for (let i = 0; i < fullStars; i++) {
                    starList.innerHTML += '<i class="fas fa-star star-yellow"></i>';
                }
                if (halfStars) {
                    starList.innerHTML += '<i class="fas fa-star-half-alt star-yellow"></i>';
                }
                for (let i = 0; i < emptyStars; i++) {
                    starList.innerHTML += '<i class="fas fa-star"></i>';
                }
            }
        });

    </script>


</body>

</html>