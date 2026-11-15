<?php
 require_once('sudo/assets/config/config.php');
?>
<!doctype html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Home - Digital Library- Automated and digital way of mantaining libraries</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#203d32">
    <link rel="manifest" href="site.webmanifest">

    <!-- Place favicon.ico in the root directory -->
    <link rel="apple-touch-icon" href="images/apple-touch-icon.png">
    <link rel="shortcut icon" type="image/ico" href="images/favicon.png" />

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
    
    <header class="relative library-header library-header--home" id="sc1">
        <!-- Header-background-markup -->
        <div class="header-bg relative home-slide">
            <div class="item">
                <img src="images/slide/slide1.jpg" alt="library">
            </div>
            <div class="item">
                <img src="images/slide/slide2.jpg" alt="library">
            </div>
            <div class="item">
                <img src="images/slide/slide3.jpg" alt="library">
            </div>
            <div class="item">
                <img src="images/slide/slide4.jpg" alt="library">
            </div>
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
                            <li class="active">
                                <a href="#sc1">Home</a>
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
                    <div class="col-xs-12 col-sm-10 col-sm-offset-1 text-center">
                        <div class="jumbotron library-intro">
                            <p class="hero-eyebrow">A good book opens a new world</p>
                            <h1 class="text-white">Find your next <em>great read.</em></h1>
                            <p class="text-white">Explore a thoughtfully gathered collection, discover new ideas, and make the library yours.</p>
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
                <div class="row wow fadeInUp" data-wow-delay="0.5s">
                    <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 ">
                        <div class="panel library-search">
                            <div class="panel-heading">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a data-toggle="tab" href="#book">Book</a></li>
                                    <li><a data-toggle="tab" href="#author">Author</a></li>
                                    <li><a data-toggle="tab" href="#publisher">Publisher</a></li>
                                </ul>
                            </div>
                            <div class="panel-body">
                                <div class="tab-content">
                                    <div class="tab-pane fade in active" id="book">
                                        <form action="books.php" method="get">
                                            <input type="hidden" name="search_by" value="book">
                                            <div class="input-group">
                                                <input type="search" name="q" class="form-control" placeholder="Search books, authors, or publishers" aria-label="Search the collection">
                                                <div class="input-group-btn">
                                                    <button type="submit" class="btn btn-primary"><i class="icofont icofont-search-alt-2"></i><span>Explore</span></button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="tab-pane fade" id="author">
                                        <form action="books.php" method="get">
                                            <input type="hidden" name="search_by" value="author">
                                            <div class="input-group">
                                                <input type="search" name="q" class="form-control" placeholder="Search by author name" aria-label="Search by author">
                                                <div class="input-group-btn">
                                                    <button type="submit" class="btn btn-primary"><i class="icofont icofont-search-alt-2"></i><span>Explore</span></button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="tab-pane fade" id="publisher">
                                        <form action="books.php" method="get">
                                            <input type="hidden" name="search_by" value="publisher">
                                            <div class="input-group">
                                                <input type="search" name="q" class="form-control" placeholder="Search by publisher" aria-label="Search by publisher">
                                                <div class="input-group-btn">
                                                    <button type="submit" class="btn btn-primary"><i class="icofont icofont-search-alt-2"></i><span>Explore</span></button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 text-center">
                    <h2>Book<strong>Categories</strong></h2>
                    <div class="space-20"></div>
                    <div class="title-bar blue">
                        <ul class="list-inline list-unstyled">
                            <li><i class="icofont icofont-square"></i></li>
                            <li><i class="icofont icofont-square"></i></li>
                        </ul>
                    </div>
                    <div class="space-30"></div>
                    <p>Most popular book categories to get your learning started</p>
                </div>
            </div>
            <div class="space-60"></div>
            <div class="row text-center">
                <!--Show Book Categories-->
                <?php
                    //Fetch all book categories
                    $ret="SELECT * FROM  iL_BookCategories"; 
                    $stmt= $mysqli->prepare($ret) ;
                    $stmt->execute() ;//ok
                    $res=$stmt->get_result();
                    while($row=$res->fetch_object())
                    {
                        
                ?>
                    <div class="justify col-xs-12 col-sm-6 col-md-4 wow fadeInLeft" data-wow-delay="0.1s">
                        <div class="category-item well blue text-cetnr">
                            <div class="category_icon">
                                <i class="icofont icofont-book"></i>
                            </div>
                            <div class="space-20"></div>
                            <div class="title-bar">
                                <ul class="list-inline list-unstyled">
                                    <li><i class="icofont icofont-square"></i></li>
                                </ul>
                            </div>
                            <div class="space-20"></div>
                            <a href="books.php?category=<?php echo rawurlencode($row->bc_name); ?>"><?php echo htmlspecialchars($row->bc_name, ENT_QUOTES, 'UTF-8');?></a>
                        </div>
                    </div>
                <?php }?>
                <!--End book categories -->
            </div>
            <div class="space-60"></div>
            <div class="row">
                <div class="col-xs-12 text-center">
                    <a href="books.php" class="btn btn-primary">See More</a>
                </div>
            </div>
            <div class="space-80"></div>
        </div>
    </section>
    <!--Footer-->
    <?php require_once('partials/footer.php');?>

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