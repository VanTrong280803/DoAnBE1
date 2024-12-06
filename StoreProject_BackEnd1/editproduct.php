<?php
require_once('./config/database.php');
spl_autoload_register(function ($className) {
    require_once("./app/models/$className.php");
});
session_start();
if(isset($_SESSION['search'])) unset($_SESSION['search']);
if (isset($_SESSION['admin'])) {
    $adminModel = new AdminModel();
    $adminInfo = $adminModel->getInfoAdminByUsername($_SESSION['admin']);
} else {
    header('Location: adminsignin.php');
}

if(isset($_SESSION['idProductEdit']))
{
    $productModel = new ProductModel();
    $id = $_SESSION['idProductEdit'];
    $productEdit = $productModel->getProductById($id);
}

if(isset($_GET['id']))
{
    $productModel = new ProductModel();
    $id = $_GET['id'];
    $_SESSION['idProductEdit'] = (int)$id;
    $productEdit = $productModel->getProductById($id);
}

if (!empty($_POST['product_name']) && !empty($_POST['product_description']) && !empty($_POST['product_price']) && !empty($_POST['product_type'])) {
    $productModel = new ProductModel();
    $product_name = $_POST['product_name'];
    $product_description = $_POST['product_description'];
    $product_price = (float)$_POST['product_price'];
    $product_photo = '';
    $product_type = (int)$_POST['product_type'];

    for ($i = 0; $i < count($_FILES['product_photo']['tmp_name']); $i++) {
        $path = 'assets/img/products/' . $_FILES['product_photo']['name'][$i];

        if (
            is_uploaded_file($_FILES['product_photo']['tmp_name'][$i]) &&
            move_uploaded_file($_FILES['product_photo']['tmp_name'][$i], $path)
        ) {
            if ($i == count($_FILES['product_photo']['tmp_name']) - 1) {
                $product_photo .= $_FILES['product_photo']['name'][$i];
            } else {
                $product_photo .= $_FILES['product_photo']['name'][$i] . ',';
            }
        }
    }
    if(isset($_SESSION['idProductEdit'])) {
        if ($productModel->editProduct($product_name, $product_description, $product_price, $product_photo, $_SESSION['idProductEdit'])) {
            $categoryModel = new CategoryModel();
            $categoryModel->updateCategory($product_type, $_SESSION['idProductEdit']);
            header('Location: admin.php');
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>ADMIN</title>
    <link rel='shortcut icon' href='./assets/img/icon/admin.png' />
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">


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

    <!-- Template Stylesheet -->
    <link href="./assets/css/style.css" rel="stylesheet">
</head>

<style>
    
    .manageproducts,
    .add {
        cursor: pointer;
    }
</style>

<body>
    <div class="container-xxl position-relative bg-white d-flex p-0">

        <!-- Sidebar Start -->
        <div class="sidebar pe-4 pb-3">
            <nav class="navbar bg-light navbar-light">
                <a href="admin.php" class="navbar-brand mx-4 mb-3">
                    <h3 class="text-primary"><i class="fa fa-hashtag me-2"></i>ADMIN</h3>
                </a>
                <div class="d-flex align-items-center ms-4 mb-4">
                    <div class="position-relative">
                        <img class="rounded-circle" src="https://kiemtientuweb.com/ckfinder/userfiles/images/avatar-fb/avatar-fb-1.jpg" alt="" style="width: 40px; height: 40px;">
                        <div class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1"></div>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-0"><?php echo $adminInfo['fullname']?></h6>
                        <span>Admin</span>
                    </div>
                </div>
                <div class="navbar-nav w-100">
                    <div class="nav-item nav-link active manageproducts"><i class="fa fa-keyboard me-2"></i>manageproducts</div>
                    <a href="admin.php" class="nav-item nav-link add"><i class="fa fa-keyboard me-2"></i>Add product</a>
                    <!-- <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="far fa-file-alt me-2"></i>Pages</a>
                        <div class="dropdown-menu bg-transparent border-0">
                            <a href="adminsignin.html" class="dropdown-item">Sign In</a>
                            <a href="adminsignup.html" class="dropdown-item">Sign Up</a>
                        </div>
                    </div> -->
                </div>
            </nav>
        </div>
        <!-- Sidebar End -->


        <!-- Content Start -->
        <div class="content">
            <!-- Navbar Start -->
            <nav class="navbar navbar-expand bg-light navbar-light sticky-top px-4 py-0">
                <a href="index.html" class="navbar-brand d-flex d-lg-none me-4">
                    <h2 class="text-primary mb-0"><i class="fa fa-hashtag"></i></h2>
                </a>
                <a href="#" class="sidebar-toggler flex-shrink-0">
                    <i class="fa fa-bars"></i>
                </a>
                <form action="admin.php" method="POST" class="d-none d-md-flex ms-4">
                    <input class="form-control border-0" type="search" id="search" name="search" placeholder="Search">
                    <Button class="btn btn-primary" style="margin-left: 10px;">Search</Button>
                </form>
                <div class="navbar-nav align-items-center ms-auto">
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fa fa-envelope me-lg-2"></i>
                            <span class="d-none d-lg-inline-flex">Message</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
                            <a href="#" class="dropdown-item">
                                <div class="d-flex align-items-center">
                                    <img class="rounded-circle" src="https://kiemtientuweb.com/ckfinder/userfiles/images/avatar-fb/avatar-fb-1.jpg" alt="" style="width: 40px; height: 40px;">
                                    <div class="ms-2">
                                        <h6 class="fw-normal mb-0">Tuan send you a message</h6>
                                        <small>15 minutes ago</small>
                                    </div>
                                </div>
                            </a>
                            <hr class="dropdown-divider">
                            <a href="#" class="dropdown-item">
                                <div class="d-flex align-items-center">
                                    <img class="rounded-circle" src="https://kiemtientuweb.com/ckfinder/userfiles/images/avatar-fb/avatar-fb-1.jpg" alt="" style="width: 40px; height: 40px;">
                                    <div class="ms-2">
                                        <h6 class="fw-normal mb-0">Tuan send you a message</h6>
                                        <small>15 minutes ago</small>
                                    </div>
                                </div>
                            </a>
                            <hr class="dropdown-divider">
                            <a href="#" class="dropdown-item">
                                <div class="d-flex align-items-center">
                                    <img class="rounded-circle" src="https://kiemtientuweb.com/ckfinder/userfiles/images/avatar-fb/avatar-fb-1.jpg" alt="" style="width: 40px; height: 40px;">
                                    <div class="ms-2">
                                        <h6 class="fw-normal mb-0">Phuoc send you a message</h6>
                                        <small>15 minutes ago</small>
                                    </div>
                                </div>
                            </a>
                            <hr class="dropdown-divider">
                            <a href="#" class="dropdown-item text-center">See all message</a>
                        </div>
                    </div>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fa fa-bell me-lg-2"></i>
                            <span class="d-none d-lg-inline-flex">Notificatin</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
                            <a href="#" class="dropdown-item">
                                <h6 class="fw-normal mb-0">Profile updated</h6>
                                <small>15 minutes ago</small>
                            </a>
                            <hr class="dropdown-divider">
                            <a href="#" class="dropdown-item">
                                <h6 class="fw-normal mb-0">New user added</h6>
                                <small>15 minutes ago</small>
                            </a>
                            <hr class="dropdown-divider">
                            <a href="#" class="dropdown-item">
                                <h6 class="fw-normal mb-0">Password changed</h6>
                                <small>15 minutes ago</small>
                            </a>
                            <hr class="dropdown-divider">
                            <a href="#" class="dropdown-item text-center">See all notifications</a>
                        </div>
                    </div>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <img class="rounded-circle me-lg-2" src="https://kiemtientuweb.com/ckfinder/userfiles/images/avatar-fb/avatar-fb-1.jpg" alt="" style="width: 40px; height: 40px;">
                            <span class="d-none d-lg-inline-flex"><?php echo $adminInfo['fullname']?></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
                            <a href="#" class="dropdown-item">My Profile</a>
                            <a href="#" class="dropdown-item">Settings</a>
                            <a href="#" class="dropdown-item">Log Out</a>
                        </div>
                    </div>
                </div>
            </nav>
            <!-- Navbar End -->


            <!-- Form Start -->
            <div class="container-fluid pt-4 px-4 add-product">
                <div class="row g-4">
                    <div class="col-12">
                        <div class="bg-light rounded h-100 p-4">
                            <h6 class="mb-4">EDIT PRODUCT</h6>
                            <form action="editproduct.php" method="post" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label for="product_name" class="form-label">Product Name</label>
                                    <input type="text" class="form-control" id="product_name" name="product_name" value="<?php echo $productEdit['product_name'] ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="product_price" class="form-label">Product Price</label>
                                    <input type="text" class="form-control" id="product_price" name="product_price" value="<?php echo $productEdit['product_price'] ?>">
                                </div>
                                <!-- <div class="mb-3">
                                    <label for="product_quantity" class="form-label">Product Quantity</label>
                                    <input type="text" class="form-control" id="product_quantity" name="product_quantity" value="product_quantity">
                                </div> -->
                                <div class="form-floating mb-3">
                                    <select class="form-select" id="product_type" name="product_type">
                                        <option selected value="1">Open this select menu</option>
                                        <option value="1">Electronic Device</option>
                                        <option value="2">Household Electrical Appliances</option>
                                        <option value="3">Household Appliances</option>
                                    </select>
                                    <label for="category_name">CATEGORY</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <textarea class="form-control" name="product_description" id="floatingTextarea product_description" style="height: 150px;"><?php echo $productEdit['product_description'] ?></textarea>
                                    <label for="floatingTextarea product_description">Description</label>
                                </div>
                                <div class="mb-3">
                                    <label for="formFileMultiple" class="form-label">Photos</label>
                                    <input class="form-control" type="file" id="formFileMultiple product_photo" name="product_photo[]" multiple>
                                </div>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Form End -->
            
        </div>
        <!-- Content End -->


        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
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
    <script>
        const manageproducts = document.querySelector('.manageproducts');
        const manageproductsProduct = document.querySelector('.manageproducts-product');
        const add = document.querySelector('.add');
        const addProduct = document.querySelector('.add-product');

        manageproducts.addEventListener('click', () => {
            manageproducts.classList.add('active');
            add.classList.remove('active');
            manageproductsProduct.style.display = 'block';
            addProduct.style.display = 'none';
        })

        add.addEventListener('click', () => {
            add.classList.add('active');
            manageproducts.classList.remove('active');
            addProduct.style.display = 'block';
            manageproductsProduct.style.display = 'none';
        })
    </script>
</body>

</html>