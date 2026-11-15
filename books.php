<?php
 require_once('sudo/assets/config/config.php');
 $search = isset($_GET['q']) && is_string($_GET['q']) ? trim($_GET['q']) : '';
 $searchBy = isset($_GET['search_by']) && is_string($_GET['search_by']) ? $_GET['search_by'] : 'book';
 $searchColumns = array(
     'book' => '(b_title LIKE ? OR b_author LIKE ? OR b_publisher LIKE ?)',
     'author' => 'b_author',
     'publisher' => 'b_publisher'
 );
 if (!isset($searchColumns[$searchBy])) {
     $searchBy = 'book';
 }
 $category = isset($_GET['category']) && is_string($_GET['category']) ? trim($_GET['category']) : '';
 $sort = isset($_GET['sort']) && is_string($_GET['sort']) ? $_GET['sort'] : 'latest';
 $sortOrders = array('latest' => 'created_at DESC', 'title' => 'b_title ASC', 'author' => 'b_author ASC');
 if (!isset($sortOrders[$sort])) {
     $sort = 'latest';
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
                            <h1 class="text-white">A shelf full of possibilities.</h1>
                            <p class="text-white">Find a favorite, or follow your curiosity somewhere new.</p>
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
                <div class="col-xs-12 col-md-10 pull-right catalog-results">
                    <div class="catalog-toolbar">
                    <h4>Browse the collection</h4>
                    <div class="space-5"></div>
                    <form action="books.php" method="get" class="catalog-search">
                        <input type="hidden" name="search_by" value="<?php echo htmlspecialchars($searchBy, ENT_QUOTES, 'UTF-8'); ?>">
                        <?php if ($category !== '') { ?><input type="hidden" name="category" value="<?php echo htmlspecialchars($category, ENT_QUOTES, 'UTF-8'); ?>"><?php } ?>
                        <div class="input-group">
                            <input type="search" name="q" class="form-control" value="<?php echo htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); ?>" placeholder="Search by title, author, or publisher" aria-label="Search the collection">
                            <div class="input-group-btn">
                                <button type="submit" class="btn btn-primary"><i class="icofont icofont-search-alt-2"></i><span>Search</span></button>
                            </div>
                        </div>
                    </form>
                    <div class="space-30"></div>
                    <div class="row">
                        <div class="pull-right col-xs-12 col-sm-7 col-md-6">
                            <form class="form-horizontal catalog-sort" action="books.php" method="get">
                                <?php if ($search !== '') { ?><input type="hidden" name="q" value="<?php echo htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); ?>"><input type="hidden" name="search_by" value="<?php echo htmlspecialchars($searchBy, ENT_QUOTES, 'UTF-8'); ?>"><?php } ?>
                                <?php if ($category !== '') { ?><input type="hidden" name="category" value="<?php echo htmlspecialchars($category, ENT_QUOTES, 'UTF-8'); ?>"><?php } ?>
                                <div class="form-group">
                                    <label class="control-label col-xs-4" for="sort">Sort by</label>
                                    <div class="col-xs-8">
                                        <select name="sort" id="sort" class="form-control" onchange="this.form.submit()">
                                            <option value="latest" <?php echo $sort === 'latest' ? 'selected' : ''; ?>>Recently added</option>
                                            <option value="title" <?php echo $sort === 'title' ? 'selected' : ''; ?>>Title A-Z</option>
                                            <option value="author" <?php echo $sort === 'author' ? 'selected' : ''; ?>>Author A-Z</option>
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    </div>
                    <hr>
                    <div class="space-20"></div>
                    <div class="row catalog-grid">
                        <!--Books-->
                        <?php
                            $conditions = array();
                            if ($search !== '') {
                                $conditions[] = $searchBy === 'book' ? $searchColumns[$searchBy] : $searchColumns[$searchBy] . " LIKE ?";
                            }
                            if ($category !== '') {
                                $conditions[] = "bc_name = ?";
                            }
                            $ret = "SELECT * FROM iL_Books";
                            if (count($conditions) > 0) {
                                $ret .= " WHERE " . implode(" AND ", $conditions);
                            }
                            $ret .= " ORDER BY " . $sortOrders[$sort];
                            $stmt= $mysqli->prepare($ret) ;
                            if ($search !== '' && $category !== '' && $searchBy === 'book') {
                                $searchTerm = '%' . $search . '%';
                                $stmt->bind_param('ssss', $searchTerm, $searchTerm, $searchTerm, $category);
                            } elseif ($search !== '' && $category !== '') {
                                $searchTerm = '%' . $search . '%';
                                $stmt->bind_param('ss', $searchTerm, $category);
                            } elseif ($search !== '' && $searchBy === 'book') {
                                $searchTerm = '%' . $search . '%';
                                $stmt->bind_param('sss', $searchTerm, $searchTerm, $searchTerm);
                            } elseif ($search !== '') {
                                $searchTerm = '%' . $search . '%';
                                $stmt->bind_param('s', $searchTerm);
                            } elseif ($category !== '') {
                                $stmt->bind_param('s', $category);
                            }
                            $stmt->execute() ;//ok
                            $res=$stmt->get_result();
                            $bookCount = 0;
                            while($row=$res->fetch_object())
                            {
                                $bookCount++;
                                $bookTitle = htmlspecialchars($row->b_title, ENT_QUOTES, 'UTF-8');
                                $bookAuthor = htmlspecialchars($row->b_author, ENT_QUOTES, 'UTF-8');
                                $bookCategory = htmlspecialchars($row->bc_name, ENT_QUOTES, 'UTF-8');
                                $bookId = (int) $row->b_id;
                                $coverPath = trim($row->b_coverimage) !== ''
                                    ? 'sudo/assets/img/books/' . rawurlencode(basename($row->b_coverimage))
                                    : '';
                        ?>
                            <div class="col-xs-12 col-sm-6">
                                <article class="book-card">
                                    <a class="book-cover" href="book.php?book_id=<?php echo $bookId; ?>" aria-label="View <?php echo $bookTitle; ?>">
                                        <?php if ($coverPath !== '') { ?>
                                            <img src="<?php echo htmlspecialchars($coverPath, ENT_QUOTES, 'UTF-8'); ?>" alt="Cover of <?php echo $bookTitle; ?>">
                                        <?php } else { ?>
                                            <span class="book-cover-placeholder"><i class="icofont icofont-book"></i></span>
                                        <?php } ?>
                                    </a>
                                    <div class="book-card-info">
                                        <span class="book-category"><?php echo $bookCategory; ?></span>
                                        <h5><a href="book.php?book_id=<?php echo $bookId; ?>"><?php echo $bookTitle; ?></a></h5>
                                        <p>by <?php echo $bookAuthor; ?></p>
                                        <a href="book.php?book_id=<?php echo $bookId; ?>" class="book-card-link">View book <span aria-hidden="true">&rarr;</span></a>
                                    </div>
                                </article>
                            </div>
                        <?php }?>
                        <!--Book-->
                        <?php if ($bookCount === 0) { ?>
                            <div class="col-xs-12">
                                <div class="empty-catalog">
                                    <i class="icofont icofont-search-alt-2"></i>
                                    <h5>No books found</h5>
                                    <p>Try a different search or browse the full collection.</p>
                                    <a href="books.php" class="btn btn-primary">Browse all books</a>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="space-60"></div>
                </div>
                <!-- Sidebar-Start -->
                <div class="col-xs-12 col-md-2 catalog-sidebar">
                    <aside>
                        <h3><i class="icofont icofont-filter"></i> Filter By</h3>
                        <div class="space-30"></div>
                        <div class="sigle-sidebar">
                            <h4>Book Category</h4>
                            <hr>
                            <ul class="list-unstyled menu-tip">
                            <?php
                                //Fetch all book categories
                                $ret="SELECT * FROM  iL_BookCategories"; 
                                $stmt= $mysqli->prepare($ret) ;
                                $stmt->execute() ;//ok
                                $res=$stmt->get_result();
                                while($row=$res->fetch_object())
                                {
                            ?>
                                <li><a href="books.php?category=<?php echo rawurlencode($row->bc_name); ?>"><?php echo htmlspecialchars($row->bc_name, ENT_QUOTES, 'UTF-8'); ?></a></li>
                            <?php }?>
                            </ul>
                            <a href="books.php" class="btn btn-primary btn-xs">See all books</a>
                        </div>
                        <div class="space-20"></div>
                    </aside>
                </div>
                <!-- Sidebar-End -->
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