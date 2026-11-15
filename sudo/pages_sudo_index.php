<?php
session_start();
require_once('assets/config/config.php');
require_once('../lib/auth.php');

$error = '';
if (isset($_POST['sudo_login'])) {
    $email = isset($_POST['email']) && is_string($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) && is_string($_POST['password']) ? $_POST['password'] : '';
    $statement = $mysqli->prepare("SELECT password, id FROM iL_sudo WHERE email = ? LIMIT 1");
    $statement->bind_param('s', $email);
    $statement->execute();
    $statement->bind_result($storedPassword, $id);
    $authenticated = $statement->fetch() && library_verify_password($password, $storedPassword);
    $statement->close();

    if ($authenticated) {
        if (library_needs_password_upgrade($storedPassword)) {
            $upgradedPassword = library_password_hash($password);
            $upgrade = $mysqli->prepare("UPDATE iL_sudo SET password = ? WHERE id = ?");
            $upgrade->bind_param('si', $upgradedPassword, $id);
            $upgrade->execute();
            $upgrade->close();
        }
        session_regenerate_id(true);
        $_SESSION['id'] = $id;
        header('Location: pages_sudo_dashboard.php');
        exit;
    }

    $error = 'Access denied. Please check your credentials.';
}
?>
<!doctype html>
<!--[if lte IE 9]> <html class="lte-ie9" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--> <html lang="en"> <!--<![endif]-->
<?php include("assets/inc/head.php"); ?>
<body class="login_page">
    <div class="login_page_wrapper">
        <div class="md-card" id="login_card">
            <div class="md-card-content large-padding" id="login_form">
                <div class="login_heading">
                    <h2>iLibrary Administrator Login</h2>
                </div>
                <?php if ($error !== '') { ?><div class="uk-alert uk-alert-danger" role="alert"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div><?php } ?>
                <form method="post">
                    <div class="uk-form-row">
                        <label for="login_username">Email</label>
                        <input class="md-input" required type="email" id="login_username" name="email" autocomplete="username">
                    </div>
                    <div class="uk-form-row">
                        <label for="login_password">Password</label>
                        <input class="md-input" required type="password" id="login_password" name="password" autocomplete="current-password">
                    </div>
                    <div class="uk-margin-medium-top">
                        <input type="submit" name="sudo_login" value="Sign In" class="md-btn md-btn-primary md-btn-block md-btn-large">
                    </div>
                </form>
            </div>
        </div>
        <div class="uk-margin-top uk-text-center">
            <a href="../register.php?role=Admin">Request an Administrator Account</a>
        </div>
        <div class="uk-margin-top uk-text-center">
            <a href="../">Home</a>
        </div>
    </div>
    <?php require_once('assets/inc/footer.php'); ?>
    <script src="assets/js/common.min.js"></script>
    <script src="assets/js/uikit_custom.min.js"></script>
    <script src="assets/js/altair_admin_common.min.js"></script>
    <script src="assets/js/pages/login.min.js"></script>
</body>
</html>
