<?php
    // The dashboard is the home page. Keep the query string so Tumblr's OAuth
    // callback (?oauth_verifier=...) reaches it.
    $query = $_SERVER['QUERY_STRING'] ?? '';
    header('Location: dashboard/' . ($query !== '' ? '?' . $query : ''));
    die();
?>
