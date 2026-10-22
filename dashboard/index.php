<?php
    require __DIR__ . '/../vendor/autoload.php';
    require_once __DIR__ . '/../includes/posts.php';
    require_once __DIR__ . '/../includes/auth.php';

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


    // Echo HTML contents
    echo '<!doctype html><html lang="en"><head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="author" content="">
        <meta name="description" content="Vox Populi is a web client for Tumblr, allowing you to access you personal Tumblr dashboard without ads.">
        <link rel="apple-touch-icon" sizes="180x180" href="../assets/favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="../assets/favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="../assets/favicon/favicon-16x16.png">
        <link rel="manifest" href="../assets/favicon/site.webmanifest">
        <link rel="stylesheet" href="../assets/css/bootstrap-4.5.2.min.css">
        <link rel="stylesheet" href="../assets/css/app.css">
        <title>Vox Populi - A Tumblr Web Client</title>
        <style>
            @font-face {
                font-family: "IBM Plex Sans";
                src: url("../assets/fonts/IBMPlexSans-Regular.eot");
                src: url("../assets/fonts/IBMPlexSans-Regular.woff2") format("woff2"),
                    url("../assets/fonts/IBMPlexSans-Regular.woff") format("woff"),
                    url("../assets/fonts/IBMPlexSans-Regular.ttf") format("truetype");
            }

            @font-face {
                font-family: "IBM Plex Sans";
                src: url("../assets/fonts/IBMPlexSans-Bold.eot");
                src: url("../assets/fonts/IBMPlexSans-Bold.woff2") format("woff2"),
                    url("../assets/fonts/IBMPlexSans-Bold.woff") format("woff"),
                    url("../assets/fonts/IBMPlexSans-Bold.ttf") format("truetype");
                font-weight: bold;
            }

            @font-face {
                font-family: "IBM Plex Mono";
                src: url("../assets/fonts/IBMPlexMono-Regular.eot");
                src: url("../assets/fonts/IBMPlexMono-Regular.woff2") format("woff2"),
                    url("../assets/fonts/IBMPlexMono-Regular.woff") format("woff"),
                    url("../assets/fonts/IBMPlexMono-Regular.ttf") format("truetype");
            }

            @font-face {
                font-family: "IBM Plex Mono";
                src: url("../assets/fonts/IBMPlexMono-Bold.eot");
                src: url("../assets/fonts/IBMPlexMono-Bold.woff2") format("woff2"),
                    url("../assets/fonts/IBMPlexMono-Bold.woff") format("woff"),
                    url("../assets/fonts/IBMPlexMono-Bold.ttf") format("truetype");
                font-weight: bold;
            }
        </style>
    </head>

    <body>
        <div class="banner fixed-top" role="contentinfo">
            <p class="m-0 mr-2">Black Lives Matter.</p>
            <a href="https://developer.ibm.com/callforcode/racial-justice/" target="_blank" rel="noopener">Support the Call for Code®</a>
        </div>
        <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
            <li class="nav-item d-block d-md-none">
                <a id="sidebarButton" class="nav-link" href="javascript:void(0)"><svg id="icon-side-menu"
                        xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 32 32" aria-hidden="true">
                        <path
                            d="M14 4H18V8H14zM4 4H8V8H4zM24 4H28V8H24zM14 14H18V18H14zM4 14H8V18H4zM24 14H28V18H24zM14 24H18V28H14zM4 24H8V28H4zM24 24H28V28H24z">
                        </path>
                    </svg></a>
            </li>
            <a class="navbar-brand" href="./">Vox Populi</a>

            <ul class="navbar-nav mr-0 ml-auto flex-row">
                <li class="nav-item">
                    <a id="search-button" class="nav-link" href="javascript:void(0)"><svg id="icon-search"
                            xmlns="http://www.w3.org/2000/svg" description="Open search" width="20" height="20"
                            viewBox="0 0 32 32" aria-hidden="true">
                            <path d="M30,28.59,22.45,21A11,11,0,1,0,21,22.45L28.59,30ZM5,14a9,9,0,1,1,9,9A9,9,0,0,1,5,14Z">
                            </path>
                        </svg></a>
                </li>
            </ul>
            <form id="search-form" class="form-inline h-100 d-none flex-fill" method="get"
                action="../search.php">
                <input class="form-control h-100 ml-auto" type="search" placeholder="Search" aria-label="Search"
                    name="query">
                <a id="search-close-button" class="nav-link" href="javascript:void(0)">
                    <svg id="icon-search" xmlns="http://www.w3.org/2000/svg" description="Clear search" width="20"
                        height="20" viewBox="0 0 32 32" aria-hidden="true">
                        <path
                            d="M24 9.4L22.6 8 16 14.6 9.4 8 8 9.4 14.6 16 8 22.6 9.4 24 16 17.4 22.6 24 24 22.6 17.4 16 24 9.4z">
                        </path>
                    </svg>
                </a>
            </form>
        </nav>

        <div class="container-fluid">
            <div class="row">
                <div id="sidebar" class="sidebar d-none d-md-block col-md-3 col-lg-2 p-0">
                    <div class="sidebar-box p-0">
                        <div>
                            <ul class="actions">
                                <li><p>Account</p></li>
                                <hr class="sidebar-divider">
                                <li><a href="../dashboard/">Dashboard</a></li>
                                <li><a href="../profile/">Profile</a></li>
                                <li><a href="../logout.php">Logout</a></li>
                            </ul>
                            <ul class="categories">
                                <li><p>Filter</p></li>
                                <hr class="sidebar-divider">
                                <li><a class="'; echo ((isset($_GET['type']) && $_GET['type'] == '') ? 'active' : ""); echo '" href="./">All Posts</a></li>
                                <li><a class="'; echo ((isset($_GET['type']) && $_GET['type'] == 'answer') ? 'active' : ""); echo '" href="./?type=answer">Answer</a></li>
                                <li><a class="'; echo ((isset($_GET['type']) && $_GET['type'] == 'audio') ? 'active' : ""); echo '" href="./?type=audio">Audio</a></li>
                                <li><a class="'; echo ((isset($_GET['type']) && $_GET['type'] == 'chat') ? 'active' : ""); echo '" href="./?type=chat">Chat</a></li>
                                <li><a class="'; echo ((isset($_GET['type']) && $_GET['type'] == 'link') ? 'active' : ""); echo '" href="./?type=link">Link</a></li>
                                <li><a class="'; echo ((isset($_GET['type']) && $_GET['type'] == 'photo') ? 'active' : ""); echo '" href="./?type=photo">Photo</a></li>
                                <li><a class="'; echo ((isset($_GET['type']) && $_GET['type'] == 'quote') ? 'active' : ""); echo '" href="./?type=quote">Quote</a></li>
                                <li><a class="'; echo ((isset($_GET['type']) && $_GET['type'] == 'text') ? 'active' : ""); echo '" href="./?type=text">Text</a></li>
                                <li><a class="'; echo ((isset($_GET['type']) && $_GET['type'] == 'video') ? 'active' : ""); echo '" href="./?type=video">Video</a></li>
                            </ul>
                        </div>
                        <div>
                            <hr class="sidebar-divider">
                            <ul class="meta">
                                <li>
                                <a href="#" target="_blank">Source Code <svg
                                            id="icon-external-link" xmlns="http://www.w3.org/2000/svg" width="16"
                                            height="16" viewBox="0 0 16 16" aria-hidden="true">
                                            <path
                                                d="M13,14H3c-0.6,0-1-0.4-1-1V3c0-0.6,0.4-1,1-1h5v1H3v10h10V8h1v5C14,13.6,13.6,14,13,14z">
                                            </path>
                                            <path d="M10 1L10 2 13.3 2 9 6.3 9.7 7 14 2.7 14 6 15 6 15 1z"></path>
                                        </svg>
                                    </a>
                                </li>
                                <li>
                                    <a href="https://example.com" target="_blank">&copy; 0000</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div id="main" class="main col-xs-12 col-md-9 col-lg-10 ml-auto">
                    <span class="homepage--dots"></span>';

            // Get the user's blog name for welcome message
            if ($client->getUserInfo()) {
                $client->getUserInfo();
            } else {
                // Echo rate-limit error message
                $rate_error = '<div id="rate-limit-exceeded" class="alert alert-danger my-5"><b>[429] Error</b>: Tumblr rate limit exceeded. Please visit again tomorrow.</div>';
                echo $rate_error;
                die();
            }
            foreach ($client->getUserInfo()->user->blogs as $blog) {
                echo '<h1 class="text-center py-4">Welcome, <a href="https://' . $blog->name . '.tumblr.com">' . $blog->name . '</a>!</h1>';
            }

            // Create function to allow a client to get 20 posts per page
            function get_dashboard_posts($client, $post_start, $limit, $post_type) {
                if ($post_type != NULL) {
                    $dashboard_posts = $client->getDashboardPosts(array('limit' => $limit, 'offset' => $post_start, 'reblog_info' => true, 'type' => $post_type));
                } else {
                    $dashboard_posts = $client->getDashboardPosts(array('limit' => $limit, 'offset' => $post_start, 'reblog_info' => true));
                }
                $card_columns = '<div class="card-columns">';
                foreach ($dashboard_posts->posts as $post) {
                    $card_columns .= post_header($client, $post);

                    $card_columns .= post_media($post);
                    $card_columns .= '<div class="card-body">';
                    $card_columns .= post_content($post);
                    /* This seems to just post a duplicate of a QA answer
                    if (isset($post->reblog->comment) && $post->reblog->comment !== '') {
                        $card_columns .= '<p class="card-text reblog-comment">' . $post->reblog->comment . '</p>';
                    }
                    */
                    // $card_columns .= '<a href="' . $post->post_url . '" class="card-link">Visit Post &rarr;</a>';
                    $card_columns .= '<div class="card-footer d-flex justify-content-between align-items-center p-0"><div class="note-count">' . number_format($post->note_count, 0) . ' notes</div>';
                    $card_columns .= '<div class="post-icons"><a href="' . $post->post_url . '" target="_blank" title="View on Tumblr"><i data-feather="external-link"></i></a>';
                    $card_columns .= '<a disabled href="#" title="Share"><i data-feather="send"></i></a>';
                    $card_columns .= '<a disabled href="#" title="Comment"><i data-feather="message-square"></i></a>';

                    // Set up actions to reblog/unreblog post
                    if ($post->liked != true) {
                        // Reblog this post
                        $card_columns .= '<a href="javascript:void(0);" onclick="reblog(\'' . urlencode($post->blog_name) . '\', \'' . urlencode($post->id) . '\', \'' . urlencode($post->reblog_key) . '\');" title="Reblog" data-reblog-id="' . $post->id . '"><i data-feather="repeat"></i></a>';
                    } else {
                        // Unreblog this post
                        $card_columns .= '<a href="javascript:void(0);" onclick="unblog(\'' . urlencode($post->blog_name) . '\', \'' . urlencode($post->id) . '\', \'' . urlencode($post->reblog_key) . '\');" title="Unblog" data-unreblog-id="' . $post->id . '"><i data-feather="repeat"></i></a>';
                    }

                    // Set up actions to like/unlike post
                    if ($post->liked != true) {
                        // Like this post
                        $card_columns .= '<a href="javascript:void(0);" onclick="like(\'' . urlencode($post->id) . '\', \'' . urlencode($post->reblog_key) . '\');" title="Like" data-like-id="' . $post->id . '"><i data-feather="heart"></i></a></div></div>';
                    } else {
                        // Unlike this post
                        $card_columns .= '<a href="javascript:void(0);" onclick="unlike(\'' . urlencode($post->id) . '\', \'' . urlencode($post->reblog_key) . '\');" title="Unlike" data-unlike-id="' . $post->id . '"><i data-feather="heart"></i></a></div></div>';
                    }

                    $card_columns .= '</div></div>';
                }
                $card_columns .= '</div>';
                return $card_columns;
            }

            // Create a loop to call as many dashboard posts as you want (results are returned in sets of 20 per API rules)
            // Can specify post type: text, chat, link, photo, audio, video, NULL
            if (isset($_GET['type'])) {
                $post_type = $_GET['type'];
            } else {
                $post_type = NULL;
            }
            if (isset($_GET['page'])) {
                $page = $_GET['page'];
            } else {
                $page = 1;
            }
            $post_start = (($page - 1) * 20) + 1;
            $limit = 20;
            echo get_dashboard_posts($client, $post_start, $limit, $post_type);

            // Echo HTML page navigation
            // Embedded PHP tags here are calculating page numbers for URL parameters
            echo '<nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <li class="page-item '; echo ($page <= 1 ? "disabled" : ""); echo'">
                    <a class="page-link" href="./?page='; echo ($page-1); echo (isset($_GET['type']) ? "&type=" . $_GET['type'] : ""); echo '" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <li class="page-item '; echo ($page <= 1 ? "disabled" : "");  echo '"><a class="page-link"
                        href="./?page='; echo ($page-1); echo (isset($_GET['type']) ? "&type=" . $_GET['type'] : ""); echo '">'; echo ($page-1); echo '</a></li>
                <li class="page-item active"><a class="page-link" href="#">'; echo $page; echo '</a></li>
                <li class="page-item"><a class="page-link"
                        href="./?page='; echo ($page+1); echo (isset($_GET['type']) ? "&type=" . $_GET['type'] : ""); echo '">'; echo ($page+1); echo '</a></li>
                <li class="page-item">
                    <a class="page-link" href="./?page='; echo ($page+1); echo (isset($_GET['type']) ? "&type=" . $_GET['type'] : ""); echo'" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>

    </div>
    </div>
    <button class="btn-to-top" type="button" aria-label="Back to Top">
        <svg id="icon-to-top" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 32 32"
            aria-hidden="true">
            <path d="M16 14L6 24 7.4 25.4 16 16.8 24.6 25.4 26 24zM4 8H28V10H4z"></path>
        </svg>
    </button>

    <!-- JavaScript -->
    <script src="../assets/js/feather-icons.4.2.8.min.js"></script>
    <script src="../assets/js/jquery-3.5.1.min.js"></script>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/app.js"></script>
</body></html>';
?>
