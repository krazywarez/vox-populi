<?php
    require __DIR__ . '/vendor/autoload.php';
    require_once __DIR__ . '/includes/posts.php';
    require_once __DIR__ . '/includes/auth.php';

    session_start();

    $consumer_key = getenv('CONSUMER_KEY');
    $consumer_secret = getenv('CONSUMER_SECRET');
    $client = new Tumblr\API\Client($consumer_key, $consumer_secret);
    $requestHandler = $client->getRequestHandler();
    $requestHandler->setBaseUrl('https://www.tumblr.com/');

    list($token, $token_secret) = tumblr_tokens($client, $requestHandler);

    // Authenticate via OAuth
    $client = new Tumblr\API\Client(
        $consumer_key,
        $consumer_secret,
        $token,
        $token_secret
    );


    // Grab the callback URL
    $url = $_GET['callback'];
    if (!not_blank($url)) {
        $url = 'Location: https://example.com/vox-populi/';
    }

    // See what action we need to do
    $action = $_GET['action'];
    if (!not_blank($action)) {
        header('Location: ' . $url);
        die();
    }

    // Follow a new blog
    if ($action == "follow") {
        $blogName = $_GET['blog_name'];
        if(not_blank($blogName)) {
            $client->follow($blogName);
            print 'success';
        } else {
            print 'failure';
        }
    }

    // Unfollow a blog
    if ($action == "unfollow") {
        $blogName = $_GET['blog_name'];
        if(not_blank($blogName)) {
            $client->unfollow($blogName);
            print 'success';
        } else {
            print 'failure';
        }
    }

    // Like a post
    if ($action == "like") {
        $postId = $_GET['post_id'];
        $reblogKey = $_GET['reblog_key'];
        if(not_blank($postId) && not_blank($reblogKey)) {
            $client->like($postId, $reblogKey);
            print 'success';
        } else {
            print 'failure';
        }
    }

    // Unlike a post
    if ($action == "unlike") {
        $postId = $_GET['post_id'];
        $reblogKey = $_GET['reblog_key'];
        if(not_blank($postId) && not_blank($reblogKey)) {
            $client->unlike($postId, $reblogKey);
            print 'success';
        } else {
            print 'failure';
        }
    }

    // Reblog a post
    if ($action == "reblog") {
        $blogName = $_GET['blog_name'];
        $id = $_GET['id'];
        $reblogKey = $_GET['reblog_key'];
        if(not_blank($blogName) && not_blank($id) && not_blank($reblogKey)) {
            $client->reblogPost($blogName, $id, $reblogKey);
            print 'success';
        } else {
            print 'failure';
        }
    }

    // Unreblog a post
    if ($action == "reblog") {
        $blogName = $_GET['blog_name'];
        $id = $_GET['id'];
        $reblogKey = $_GET['reblog_key'];
        if(not_blank($blogName) && not_blank($id) && not_blank($reblogKey)) {
            $client->deletePost($blogName, $id, $reblogKey);
            print 'success';
        } else {
            print 'failure';
        }
    }
?>
