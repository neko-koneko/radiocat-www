<?php
// failsafe set of session timeout
$session_timeout = (intval($config['auth']['system_users_session_timeout'])<=0)?1800:intval($config['auth']['system_users_session_timeout']);

// force HTTPS usage
if ($config['auth']['force_https'])
{
	if (!empty( $_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off')
	{ }
	else
	{
		header('Location: https://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
	    exit;
	}
}
// check session timeout
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $session_timeout)) {
   // Unset all of the session variables.
	$_SESSION = array();

	// If it's desired to kill the session, also delete the session cookie.
	// Note: This will destroy the session, and not just the session data!
	if (ini_get("session.use_cookies")) {
	    $params = session_get_cookie_params();
	    $params["secure"] = true;
	    setcookie(session_name(), '', time() - 42000,
	        $params["path"], $params["domain"],
	        $params["secure"], $params["httponly"]
	    );
	}

	// Finally, destroy the session.
	session_destroy();
	header('Location: '.$base);
	exit;
}
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp

// additional time stamp to regenerate the session ID periodically to avoid attacks on sessions like session fixation:
if (!isset($_SESSION['CREATED'])) {
    $_SESSION['CREATED'] = time();
} else if (time() - $_SESSION['CREATED'] > $session_timeout) {
    // session started more than 30 minutes ago
    session_regenerate_id(true);    // change session ID for the current session an invalidate old session ID
    $_SESSION['CREATED'] = time();  // update creation time
}


// logout
if ($main_request_array[0]=='logout')
{
	// Unset all of the session variables.
	$_SESSION = array();

	// If it's desired to kill the session, also delete the session cookie.
	// Note: This will destroy the session, and not just the session data!
	if (ini_get("session.use_cookies")) {
	    $params = session_get_cookie_params();
	    $params["secure"] = true;
	    setcookie(session_name(), '', time() - 42000,
	        $params["path"], $params["domain"],
	        $params["secure"], $params["httponly"]
	    );
	}

	// Finally, destroy the session.
	session_destroy();
	header('Location: '.$base);
	exit;
}
?>