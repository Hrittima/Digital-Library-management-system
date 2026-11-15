<?php
function library_verify_password($password, $stored_hash)
{
    if (password_verify($password, $stored_hash)) {
        return true;
    }

    return hash_equals(sha1(md5($password)), $stored_hash);
}

function library_needs_password_upgrade($stored_hash)
{
    return strlen($stored_hash) === 40;
}

function library_password_hash($password)
{
    $hash = password_hash($password, PASSWORD_DEFAULT);
    if ($hash === false) {
        throw new RuntimeException('Unable to secure the account password.');
    }

    return $hash;
}
