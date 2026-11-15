<?php
    require_once('sudo/assets/config/config.php');
    $book_id = isset($_GET['book_id']) && is_string($_GET['book_id']) ? $_GET['book_id'] : '';
    $ret="SELECT * FROM  iL_Books WHERE b_id = ?"; 
    $stmt= $mysqli->prepare($ret) ;
    $stmt->bind_param('s', $book_id);
    $stmt->execute() ;//ok
    $res=$stmt->get_result();
    while($row=$res->fetch_object())
    {
    if (trim($row->b_coverimage) !== '') {
        $coverPath = 'sudo/assets/img/books/' . rawurlencode(basename($row->b_coverimage));
        $cover_image = '<img src="' . htmlspecialchars($coverPath, ENT_QUOTES, 'UTF-8') . '" alt="Cover of ' . htmlspecialchars($row->b_title, ENT_QUOTES, 'UTF-8') . '">';
    } else {
        $cover_image = '<div class="book-cover-placeholder"><i class="icofont icofont-book"></i></div>';
    }
?>
<!doctype html>
<html class="no-js" lang="zxx">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Books - Digital Library - Automated and digital ways of mantaining libraries</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#203d32">
    <link rel="manifest" href="site.webmanifest">

    <!-- Place favicon.ico in the root directory -->
    <link rel="apple-touch-icon" href="images/apple-touch-icon.png">
    <link rel="shortcut icon" type="image/ico" href="images/favicon.ico" />

    <!-- Plugin-CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/icofont.css">
    <link rel="stylesheet" href="css/animate.css">
    <link rel="stylesheet" href="css/cardslider.css">
    <link rel="stylesheet" href="css/responsiveslides.css">

    <!-- Main-Stylesheets -->
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/overright.css">
    <link rel="stylesheet" href="css/theme.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="css/responsive.css">
    <link rel="stylesheet" href="library.css">
    <script src="js/vendor/modernizr-2.8.3.min.js"></script>
</head>

<body class="library-site" data-spy="scroll" data-target="#mainmenu" data-offset="50">
   
    <header class="relative library-header library-header--compact" id="sc1">
        <!-- Header-background-markup -->
        <div class="overlay-bg relative">
            <img src="images/slide/slide1.jpg" alt="">
        </div>
        <!-- Mainmenu-markup-start -->
        <div class="mainmenu-area navbar-fixed-top" data-spy="affix" data-offset-top="10">
            <nav class="navbar">
                <div class="container">
                    <div class="navbar-header">
                        <div class="space-10"></div>
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#mainmenu">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <!--Logo-->
                        <a href="#sc1" class="navbar-left show library-brand"><span class="brand-mark"><i class="icofont icofont-book"></i></span><span>Open Shelf<small>Digital Library</small></span></a>
                        <div class="space-10"></div>
                    </div>
                    <!--Toggle-button-->

                    <!--Mainmenu list-->
                    <div class="navbar-right collapse navbar-collapse" id="mainmenu">
                        <ul class="nav navbar-nav nav-white text-uppercase">
                            <li>
                                <a href="index.php">Home</a>
                            </li>
                            <li>
                                <a href="books.php">Books</a>
                            </li>
                            <li>
                                <a href="register.php">Create Account</a>
                            </li>
                            <li>
                                <a href="lib_user/pages_std_index.php">Library User Login</a>
                            </li>
                            <li>
                                <a href="staff/pages_staff_index.php">Staff Login</a>
                            </li>
                            <li>
                                <a href="sudo/pages_sudo_index.php">Admin Login</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
        <div class="space-100"></div>
        <!-- Mainmenu-markup-end -->
        <!-- Header-jumbotron -->
        <div class="space-100"></div>
        <div class="header-text">
            <div class="container">
                <div class="row wow fadeInUp">
                    <div class="col-xs-12 col-sm-10 col-sm-offset-1 text-center library-page-intro">
                        <div class="jumbotron">
                            <p class="hero-eyebrow">The collection</p>
                            <h1 class="text-white">A closer look.</h1>
                            <p class="text-white">Get to know your next read.</p>
                        </div>
                        <div class="title-bar white">
                            <ul class="list-inline list-unstyled">
                                <li><i class="icofont icofont-square"></i></li>
                                <li><i class="icofont icofont-square"></i></li>
                            </ul>
                        </div>
                        <div class="space-40"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="space-100"></div>
        <!-- Header-jumbotron-end -->
    </header>
    <section>
        <div class="space-80"></div>
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-12">
                    <div class="space-30"></div>
                    <div class="row">
                            <div class="col-xs-12 col-md-12">
                                <article class="book-detail-card">
                                    <div class="book-detail-cover">
                                            <?php echo $cover_image;?>
                                    </div>
                                    <div class="book-detail-info">
                                            <span class="book-category"><?php echo htmlspecialchars($row->bc_name, ENT_QUOTES, 'UTF-8');?></span>
                                            <h2><?php echo htmlspecialchars($row->b_title, ENT_QUOTES, 'UTF-8');?></h2>
                                            <h6>By <?php echo htmlspecialchars($row->b_author, ENT_QUOTES, 'UTF-8');?></h6>
                                            <div class="space-10"></div>
                                            <ul class="list-inline list-unstyled rating-star">
                                                <li class="active"><i class="icofont icofont-star"></i></li>
                                                <li class="active"><i class="icofont icofont-star"></i></li>
                                                <li class="active"><i class="icofont icofont-star"></i></li>
                                                <li class="active"><i class="icofont icofont-star"></i></li>
                                                <li><i class="icofont icofont-star"></i></li>
                                            </ul>
                                            <p><?php echo nl2br(htmlspecialchars($row->b_summary, ENT_QUOTES, 'UTF-8'));?></p>
                                            <div class="space-10"></div>
                                            <a href="lib_user/pages_std_index.php" class="btn btn-outline-success text-primary">Borrow This Book</a>
                                    </div>
                                </article>
                            </div>
                        </div>
                        <!--Book-->
                    </div>
                    <div class="space-60"></div>
                </div>
            </div>
        </div>
        <div class="space-80"></div>
    </section>

    <!--Footer-->
    <?php require_once('partials/footer.php');?>
    <!-- Footer-Area-End -->

    <!-- Vandor-JS -->
    <script src="js/vendor/jquery-1.12.4.min.js"></script>
    <script src="js/vendor/bootstrap.min.js"></script>
    <!-- Plugin-JS -->
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/responsiveslides.min.js"></script>
    <script src="js/jquery.cardslider.min.js"></script>
    <script src="js/pagination.js"></script>
    <script src="js/scrollUp.min.js"></script>
    <script src="js/wow.min.js"></script>
    <script src="js/plugins.js"></script>
    <!-- Active-JS -->
    <script src="js/main.js"></script>
    <script src="js/pwa.js"></script>

</body>

</html>
<?php }?>