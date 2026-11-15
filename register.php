<?php
session_start();
require_once('sudo/assets/config/config.php');
require_once('lib/auth.php');

if (empty($_SESSION['account_registration_token'])) {
    $_SESSION['account_registration_token'] = bin2hex(random_bytes(32));
}

$error = '';
$success = '';
$name = '';
$email = '';
$phone = '';
$role = isset($_GET['role']) && is_string($_GET['role']) && in_array($_GET['role'], array('Student', 'Librarian', 'Admin'), true)
    ? $_GET['role']
    : 'Student';
$gender = '';
$address = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) && is_string($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) && is_string($_POST['email']) ? trim($_POST['email']) : '';
    $phone = isset($_POST['phone']) && is_string($_POST['phone']) ? trim($_POST['phone']) : '';
    $role = isset($_POST['role']) && is_string($_POST['role']) ? $_POST['role'] : '';
    $gender = isset($_POST['gender']) && is_string($_POST['gender']) ? $_POST['gender'] : '';
    $address = isset($_POST['address']) && is_string($_POST['address']) ? trim($_POST['address']) : '';
    $password = isset($_POST['password']) && is_string($_POST['password']) ? $_POST['password'] : '';
    $confirmPassword = isset($_POST['confirm_password']) && is_string($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
    $token = isset($_POST['registration_token']) && is_string($_POST['registration_token']) ? $_POST['registration_token'] : '';

    if (!hash_equals($_SESSION['account_registration_token'], $token)) {
        $error = 'Your form has expired. Please try again.';
    } elseif (!in_array($role, array('Student', 'Librarian', 'Admin'), true)) {
        $error = 'Choose a valid account type.';
    } elseif ($name === '' || strlen($name) > 200) {
        $error = 'Enter your name (up to 200 characters).';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 200) {
        $error = 'Enter a valid email address.';
    } elseif ($phone === '' || strlen($phone) > 200) {
        $error = 'Enter your phone number.';
    } elseif (strlen($password) < 8) {
        $error = 'Choose a password with at least 8 characters.';
    } elseif ($password !== $confirmPassword) {
        $error = 'The passwords do not match.';
    } elseif ($role === 'Student' && !in_array($gender, array('Male', 'Female', 'Other'), true)) {
        $error = 'Choose an option for gender.';
    } else {
        $emailCheck = $mysqli->prepare(
            "SELECT s_email AS email FROM iL_Students WHERE s_email = ?
             UNION SELECT l_email AS email FROM iL_Librarians WHERE l_email = ?
             UNION SELECT email FROM iL_sudo WHERE email = ? LIMIT 1"
        );
        $emailCheck->bind_param('sss', $email, $email, $email);
        $emailCheck->execute();
        $emailExists = $emailCheck->get_result()->num_rows > 0;
        $emailCheck->close();

        $requestCheck = $mysqli->prepare(
            "SELECT ar_id FROM iL_AccountRequests
             WHERE ar_email = ? AND ar_status IN ('Pending', 'Approved') LIMIT 1"
        );
        $requestCheck->bind_param('s', $email);
        $requestCheck->execute();
        $requestExists = $requestCheck->get_result()->num_rows > 0;
        $requestCheck->close();

        if ($emailExists || $requestExists) {
            $error = 'An account or account request already exists for this email.';
        } else {
            $passwordHash = library_password_hash($password);

            if ($role === 'Student') {
                $studentNumber = '';
                do {
                    $studentNumber = 'iLib-' . random_int(10000, 99999);
                    $numberCheck = $mysqli->prepare("SELECT s_id FROM iL_Students WHERE s_number = ? LIMIT 1");
                    $numberCheck->bind_param('s', $studentNumber);
                    $numberCheck->execute();
                    $numberExists = $numberCheck->get_result()->num_rows > 0;
                    $numberCheck->close();
                } while ($numberExists);

                $statement = $mysqli->prepare(
                    "INSERT INTO iL_Students
                        (s_name, s_number, s_email, s_pwd, s_sex, s_phone, s_bio, s_adr, s_acc_status)
                     VALUES (?, ?, ?, ?, ?, ?, '', ?, 'Active')"
                );
                $statement->bind_param('sssssss', $name, $studentNumber, $email, $passwordHash, $gender, $phone, $address);
                $successMessage = 'Your student account is ready. You can now sign in.';
            } else {
                $statement = $mysqli->prepare(
                    "INSERT INTO iL_AccountRequests (ar_name, ar_email, ar_phone, ar_password, ar_role, ar_gender, ar_address)
                     VALUES (?, ?, ?, ?, ?, ?, ?)"
                );
                $statement->bind_param('sssssss', $name, $email, $phone, $passwordHash, $role, $gender, $address);
                $successMessage = 'Your ' . ($role === 'Admin' ? 'administrator' : 'librarian') . ' account request was sent for administrator approval.';
            }

            if ($statement->execute()) {
                $success = $successMessage;
                $_SESSION['account_registration_token'] = bin2hex(random_bytes(32));
                $name = $email = $phone = $address = '';
            } else {
                $error = 'We could not save your account. Please try again.';
            }
            $statement->close();
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#203d32">
    <title>Create an account - Open Shelf</title>
    <link rel="manifest" href="site.webmanifest">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/icofont.css">
    <link rel="stylesheet" href="css/theme.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="library.css">
    <style>
        .registration-page { background: #f7f5ef; min-height: 100vh; padding: 45px 15px; }
        .registration-card { background: #fff; border: 1px solid #e8e4da; border-radius: 12px; box-shadow: 0 16px 45px rgba(32,61,50,.1); margin: 0 auto; max-width: 580px; padding: 35px; }
        .registration-card h1 { font-family: Georgia, 'Times New Roman', serif; font-size: 34px; margin: 20px 0 8px; }
        .registration-card .form-group { margin-bottom: 18px; }
        .registration-card label { color: #536157; font-size: 12px; }
        .registration-card .form-control { border-color: #e8e4da; border-radius: 5px; box-shadow: none; height: 44px; }
        .registration-note { background: #f7f5ef; border-radius: 6px; color: #667167; font-size: 12px; line-height: 1.7; margin: 16px 0; padding: 12px 14px; }
        .registration-status { border-radius: 6px; margin: 15px 0; padding: 12px 14px; }
        .registration-status--success { background: #edf5ef; color: #315b49; }
        .registration-status--error { background: #fbefed; color: #9a3e34; }
        @media (max-width: 520px) { .registration-card { padding: 24px 19px; } }
    </style>
</head>
<body class="library-site">
    <main class="registration-page">
        <div class="registration-card">
            <a href="index.php" class="library-brand"><span class="brand-mark"><i class="icofont icofont-book"></i></span><span>Open Shelf<small>Digital Library</small></span></a>
            <h1>Create an account</h1>
            <p>Join the library to discover and borrow books.</p>
            <?php if ($error !== '') { ?><div class="registration-status registration-status--error" role="alert"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div><?php } ?>
            <?php if ($success !== '') { ?><div class="registration-status registration-status--success" role="status"><?php echo htmlspecialchars($success, ENT_QUOTES, 'UTF-8'); ?></div><?php } ?>
            <form method="post" action="register.php">
                <input type="hidden" name="registration_token" value="<?php echo htmlspecialchars($_SESSION['account_registration_token'], ENT_QUOTES, 'UTF-8'); ?>">
                <div class="form-group">
                    <label for="account-role">Account type</label>
                    <select class="form-control" id="account-role" name="role" required>
                        <option value="Student" <?php echo $role === 'Student' ? 'selected' : ''; ?>>Student / Library member</option>
                        <option value="Librarian" <?php echo $role === 'Librarian' ? 'selected' : ''; ?>>Librarian / Staff</option>
                        <option value="Admin" <?php echo $role === 'Admin' ? 'selected' : ''; ?>>Administrator</option>
                    </select>
                </div>
                <div class="form-group"><label for="account-name">Full name</label><input class="form-control" id="account-name" name="name" maxlength="200" value="<?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>" required></div>
                <div class="form-group"><label for="account-email">Email address</label><input class="form-control" id="account-email" name="email" type="email" maxlength="200" value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>" required></div>
                <div class="form-group"><label for="account-phone">Phone number</label><input class="form-control" id="account-phone" name="phone" type="tel" maxlength="200" value="<?php echo htmlspecialchars($phone, ENT_QUOTES, 'UTF-8'); ?>" required></div>
                <div class="form-group" id="student-gender-group"><label for="account-gender">Gender</label><select class="form-control" id="account-gender" name="gender"><option value="">Choose an option</option><option value="Female" <?php echo $gender === 'Female' ? 'selected' : ''; ?>>Female</option><option value="Male" <?php echo $gender === 'Male' ? 'selected' : ''; ?>>Male</option><option value="Other" <?php echo $gender === 'Other' ? 'selected' : ''; ?>>Other</option></select></div>
                <div class="form-group"><label for="account-address">Address (optional)</label><input class="form-control" id="account-address" name="address" maxlength="255" value="<?php echo htmlspecialchars($address, ENT_QUOTES, 'UTF-8'); ?>"></div>
                <div class="form-group"><label for="account-password">Password (at least 8 characters)</label><input class="form-control" id="account-password" name="password" type="password" minlength="8" autocomplete="new-password" required></div>
                <div class="form-group"><label for="account-confirm">Confirm password</label><input class="form-control" id="account-confirm" name="confirm_password" type="password" minlength="8" autocomplete="new-password" required></div>
                <div class="registration-note" id="registration-note">Student accounts are ready to use after sign-up. Librarian and administrator accounts need approval from an existing administrator.</div>
                <button class="btn btn-primary btn-lg btn-block" type="submit">Create account</button>
            </form>
            <p class="text-center" style="margin-top:20px"><a href="index.php">Back to home</a> &nbsp;·&nbsp; <a href="lib_user/pages_std_index.php">Sign in</a></p>
        </div>
    </main>
    <script>
        (function () {
            var role = document.getElementById('account-role');
            var genderGroup = document.getElementById('student-gender-group');
            var gender = document.getElementById('account-gender');
            var note = document.getElementById('registration-note');
            function updateRoleFields() {
                var student = role.value === 'Student';
                genderGroup.hidden = !student;
                gender.required = student;
                note.textContent = student
                    ? 'Student accounts are ready to use after sign-up. Librarian and administrator accounts need approval from an existing administrator.'
                    : 'This account type will remain pending until an existing administrator reviews and approves it.';
            }
            role.addEventListener('change', updateRoleFields);
            updateRoleFields();
        }());
    </script>
    <script src="js/pwa.js"></script>
</body>
</html>
