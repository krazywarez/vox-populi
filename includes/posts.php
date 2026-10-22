<?php
// Post rendering shared by the dashboard, blog, and search pages.

// Set up a function to check if variables are blank later (this function accepts 0, 0.0, and "0" as valid)
function not_blank($value) {
    return !empty($value) && isset($value) && $value !== '';
}

// The photo or video shown above a post's body.
function post_media($post) {
    if ($post->type == 'photo') {
        return '<a href="' . $post->photos[0]->original_size->url . '"><img src="' . $post->photos[0]->original_size->url . '" class="card-img" alt="..."></a>';
    }
    if ($post->type == 'video') {
        return '<video controls poster="'.$post->thumbnail_url.'" class="card-img"><source src="'.$post->video_url.'"></source></video>';
    }
    return '';
}

// A post's type-specific body.
function post_content($post) {
    switch ($post->type) {
        case 'photo':
            return not_blank($post->caption) ? '<p class="card-text post-caption">' . $post->caption . '</p>' : '';
        case 'text':
            $out = '';
            if (not_blank($post->title)) {
                $out .= '<h5 class="card-title post-title">' . $post->title . '</h5>';
            }
            if (not_blank($post->body)) {
                $out .= '<p class="card-text post-body">' . $post->body . '</p>';
            }
            return $out;
        case 'answer':
            return '<p class="card-text"><b>' . $post->asking_name . '</b>: ' . $post->question . '</p><hr>' . $post->answer;
        case 'quote':
            return '<blockquote class="card-text post-quote">' . $post->summary . '<br><br><cite>' . $post->reblog->comment . '</cite></blockquote>';
        case 'chat':
            $out = '';
            for ($i = 0; $i < count($post->dialogue); $i++) {
                $out .= '<blockquote class="card-text post-chat-' . $i . '">' . (not_blank($post->dialogue[$i]->name) ? ('<b>' . $post->dialogue[$i]->name . '</b>: ') : "") . $post->dialogue[$i]->phrase . '</blockquote>';
            }
            return $out;
    }
    return '';
}

// The card's opening and header rows: the posting blog, where it was reblogged
// from, and the root blog, with follow buttons for blogs not yet followed.
function post_header($client, $post) {
    $out = '<div class="card" data-type="' . $post->type . '" data-id="' . $post->id_string . '">';
    $out .= '<div class="card-header d-flex justify-content-between"><div class="card-header-blog"><a href="' . $post->blog->url . '" target="_blank"><img class="avatar" src="' . $client->getBlogAvatar($post->blog_name, 32) . '"></a>';
    $out .= '<a href="' . $post->blog->url . '">' . $post->blog_name . '</a>';
    if (not_blank($post->reblogged_from_name)) {
        $out .= '<i data-feather="repeat"></i><a href="' . $post->reblogged_from_url . '">' . $post->reblogged_from_name . '</a>';
        if (!$post->reblogged_from_following) {
            $out .= '<a href="javascript:void(0);" onclick="follow(\'' . $post->reblogged_from_name . '\', \'' . $post->reblogged_from_uuid . '\');" data-follow-id="' . $post->reblogged_from_uuid . '"><span class="badge badge-secondary ml-2">Follow</span></a>';
        }
    }
    $out .= '</div>';

    // Add 'follow user' button if not following blog
    if ($post->followed) {
        $out .= '</div>';
    } else {
        $out .= '<a href="javascript:void(0);" onclick="follow(\'' . $post->blog_name . '\', \'' . $post->blog->uuid . '\');" title="Follow" data-id="' . $post->blog->uuid . '"><i data-feather="user-plus"></i></a></div>';
    }

    // Add root blog (original poster)
    $out .= '<div class="card-header d-flex justify-content-between"><div class="card-header-blog">';
    $out .= '<a href="' . $post->reblogged_root_url . '" target="_blank"><img class="avatar" src="' . $client->getBlogAvatar($post->reblogged_root_name, 32) . '"></a>';
    $out .= '<a href="' . $post->reblogged_root_url . '">' . $post->reblogged_root_name . '</a></div>';
    if (!$post->reblogged_root_following && strpos($post->reblogged_root_name, 'deactivated') === false) {
        $out .= '<a href="javascript:void(0);" onclick="follow(\'' . $post->reblogged_root_name . '\');" title="Follow" data-id="' . $post->reblogged_root_uuid . '"><i data-feather="user-plus"></i></a>';
    }
    $out .= '</div>';
    return $out;
}
