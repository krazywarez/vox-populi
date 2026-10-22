<?php
// The Tumblr OAuth handshake every page starts with.

function has_tokens($store) {
    return isset($store['perm_token']) && !empty($store['perm_token']) && isset($store['perm_secret']) && !empty($store['perm_secret']);
}

// Return [token, secret] for the signed-in user. A first visit is sent to
// Tumblr to authorize, and the return from Tumblr stores the permanent tokens
// and redirects home; neither of those returns.
function tumblr_tokens($client, $requestHandler) {
    // Check if the user has already authenticated
    if (has_tokens($_SESSION)) {
        return [$_SESSION['perm_token'], $_SESSION['perm_secret']];
    }
    // Check if the user was here earlier by checking cookies
    if (has_tokens($_COOKIE)) {
        return [$_COOKIE['perm_token'], $_COOKIE['perm_secret']];
    }
    // Check if this is the user's first visit
    if (!isset($_GET['oauth_verifier'])) {

        // Grab the oauth token
        $resp = $requestHandler->request('POST', 'oauth/request_token', array());
        $data = array();
        parse_str($resp->body, $data);

        // Save temporary tokens to session
        $_SESSION['tmp_token'] = $data['oauth_token'];
        $_SESSION['tmp_secret'] = $data['oauth_token_secret'];

        // Redirect user to Tumblr auth page
        session_regenerate_id(true);
        $header_url = 'https://www.tumblr.com/oauth/authorize?oauth_token=' . $data['oauth_token'];
        header('Location: ' . $header_url);
        die();
    }

    // The user was just sent back from the Tumblr authentication site
    $verifier = $_GET['oauth_verifier'];

    // Use the stored temporary tokens
    $client->setToken($_SESSION['tmp_token'], $_SESSION['tmp_secret']);

    // Access the permanent tokens
    $resp = $requestHandler->request('POST', 'oauth/access_token', array('oauth_verifier' => $verifier));
    $data = array();
    parse_str($resp->body, $data);

    // Set permanent tokens
    $_SESSION['perm_token'] = $data['oauth_token'];
    $_SESSION['perm_secret'] = $data['oauth_token_secret'];

    // Set cookies in case the user comes back later
    setcookie("perm_token", $_SESSION['perm_token']);
    setcookie("perm_secret", $_SESSION['perm_secret']);

    // Redirect user to homepage for a clean URL
    session_regenerate_id(true);
    $header_url = 'https://example.com/vox-populi/';
    header('Location: ' . $header_url);
    die();
}
