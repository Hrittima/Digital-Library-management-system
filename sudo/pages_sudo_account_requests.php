<?php
session_start();
require_once('assets/config/config.php');
require_once('assets/config/checklogin.php');
require_once('../lib/auth.php');
check_login();

if (empty($_SESSION['account_request_csrf'])) {
    $_SESSION['account_request_csrf'] = bin2hex(random_bytes(32));
}

$error = '';
$success = '';
$token = $_SESSION['account_request_csrf'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requestId = isset($_POST['request_id']) ? filter_var($_POST['request_id'], FILTER_VALIDATE_INT) : false;
    $action = isset($_POST['action']) && is_string($_POST['action']) ? $_POST['action'] : '';
    $submittedToken = isset($_POST['request_token']) && is_string($_POST['request_token']) ? $_POST['request_token'] : '';

    if (!hash_equals($token, $submittedToken)) {
        $error = 'Your form has expired. Please reload and try again.';
    } elseif (!$requestId || !in_array($action, array('approve', 'reject'), true)) {
        $error = 'Choose a valid account request.';
    } elseif ($action === 'reject') {
        $statement = $mysqli->prepare("UPDATE iL_AccountRequests SET ar_status = 'Rejected' WHERE ar_id = ? AND ar_status = 'Pending'");
        $statement->bind_param('i', $requestId);
        $statement->execute();
        $success = $statement->affected_rows === 1 ? 'The account request was rejected.' : 'That request is no longer pending.';
        $statement->close();
    } else {
        $mysqli->begin_transaction();
        $statement = $mysqli->prepare("SELECT * FROM iL_AccountRequests WHERE ar_id = ? AND ar_status = 'Pending' FOR UPDATE");
        $statement->bind_param('i', $requestId);
        $statement->execute();
        $request = $statement->get_result()->fetch_object();
        $statement->close();

        if (!$request || !in_array($request->ar_role, array('Librarian', 'Admin'), true)) {
            $mysqli->rollback();
            $error = 'That request is no longer pending or cannot be approved.';
        } else {
            $emailCheck = $mysqli->prepare(
                "SELECT s_email AS email FROM iL_Students WHERE s_email = ?
                 UNION SELECT l_email AS email FROM iL_Librarians WHERE l_email = ?
                 UNION SELECT email FROM iL_sudo WHERE email = ? LIMIT 1"
            );
            $emailCheck->bind_param('sss', $request->ar_email, $request->ar_email, $request->ar_email);
            $emailCheck->execute();
            $emailExists = $emailCheck->get_result()->num_rows > 0;
            $emailCheck->close();

            if ($emailExists) {
                $mysqli->rollback();
                $error = 'An account with this email already exists. The request remains pending.';
            } else {
                $role = $request->ar_role;
                $number = '';
                do {
                    $number = 'iLib-' . random_int(10000, 99999);
                    if ($role === 'Librarian') {
                        $numberCheck = $mysqli->prepare("SELECT l_id FROM iL_Librarians WHERE l_number = ? LIMIT 1");
                    } else {
                        $numberCheck = $mysqli->prepare("SELECT id FROM iL_sudo WHERE number = ? LIMIT 1");
                    }
                    $numberCheck->bind_param('s', $number);
                    $numberCheck->execute();
                    $numberExists = $numberCheck->get_result()->num_rows > 0;
                    $numberCheck->close();
                } while ($numberExists);

                if ($role === 'Librarian') {
                    $statement = $mysqli->prepare(
                        "INSERT INTO iL_Librarians
                            (l_number, l_name, l_phone, l_email, l_pwd, l_adr, l_bio, l_acc_status)
                         VALUES (?, ?, ?, ?, ?, ?, '', 'Active')"
                    );
                    $statement->bind_param('ssssss', $number, $request->ar_name, $request->ar_phone, $request->ar_email, $request->ar_password, $request->ar_address);
                } else {
                    $empty = '';
                    $statement = $mysqli->prepare(
                        "INSERT INTO iL_sudo (username, number, email, password, profile_pic, bio, phone)
                         VALUES (?, ?, ?, ?, ?, ?, ?)"
                    );
                    $statement->bind_param('sssssss', $request->ar_name, $number, $request->ar_email, $request->ar_password, $empty, $empty, $request->ar_phone);
                }

                if (!$statement->execute()) {
                    $mysqli->rollback();
                    $error = 'The account could not be created. The request remains pending.';
                } else {
                    $statement->close();
                    $statement = $mysqli->prepare("UPDATE iL_AccountRequests SET ar_status = 'Approved' WHERE ar_id = ? AND ar_status = 'Pending'");
                    $statement->bind_param('i', $requestId);
                    if ($statement->execute() && $statement->affected_rows === 1) {
                        $mysqli->commit();
                        $success = ucfirst(strtolower($role)) . ' account approved and created.';
                        $_SESSION['account_request_csrf'] = bin2hex(random_bytes(32));
                        $token = $_SESSION['account_request_csrf'];
                    } else {
                        $mysqli->rollback();
                        $error = 'The request could not be updated. No account was created.';
                    }
                }
                $statement->close();
            }
        }
    }
}

$requests = $mysqli->query("SELECT ar_id, ar_name, ar_email, ar_phone, ar_role, ar_gender, ar_address, created_at FROM iL_AccountRequests WHERE ar_status = 'Pending' ORDER BY created_at ASC");
?>
<!doctype html>
<html lang="en">
<?php include("assets/inc/head.php"); ?>
<body class="disable_transitions sidebar_main_open sidebar_main_swipe">
    <?php include("assets/inc/nav.php"); ?>
    <?php include("assets/inc/sidebar.php"); ?>
    <div id="page_content">
        <div id="top_bar">
            <ul id="breadcrumbs">
                <li><a href="pages_sudo_dashboard.php">Dashboard</a></li>
                <li><span>Account Requests</span></li>
            </ul>
        </div>
        <div id="page_content_inner">
            <div class="md-card">
                <div class="md-card-content">
                    <h3 class="heading_a">Pending account requests</h3>
                    <p>Approve requests only for people authorized to access librarian or administrator tools.</p>
                    <?php if ($error !== '') { ?><div class="uk-alert uk-alert-danger" role="alert"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div><?php } ?>
                    <?php if ($success !== '') { ?><div class="uk-alert uk-alert-success" role="status"><?php echo htmlspecialchars($success, ENT_QUOTES, 'UTF-8'); ?></div><?php } ?>
                    <div class="uk-overflow-container">
                        <table class="uk-table uk-table-striped">
                            <thead><tr><th>Name</th><th>Requested role</th><th>Email</th><th>Phone</th><th>Address</th><th>Requested</th><th>Actions</th></tr></thead>
                            <tbody>
                                <?php while ($request = $requests->fetch_object()) { ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($request->ar_name, ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?php echo htmlspecialchars($request->ar_role, ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?php echo htmlspecialchars($request->ar_email, ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?php echo htmlspecialchars($request->ar_phone, ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?php echo htmlspecialchars($request->ar_address, ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?php echo htmlspecialchars($request->created_at, ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td>
                                            <form method="post" style="display:inline">
                                                <input type="hidden" name="request_token" value="<?php echo htmlspecialchars($token, ENT_QUOTES, 'UTF-8'); ?>">
                                                <input type="hidden" name="request_id" value="<?php echo (int) $request->ar_id; ?>">
                                                <button class="md-btn md-btn-primary" type="submit" name="action" value="approve">Approve</button>
                                                <button class="md-btn" type="submit" name="action" value="reject" onclick="return confirm('Reject this account request?')">Reject</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php } ?>
                                <?php if ($requests->num_rows === 0) { ?><tr><td colspan="7">There are no pending account requests.</td></tr><?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include("assets/inc/footer.php"); ?>
</body>
</html>
