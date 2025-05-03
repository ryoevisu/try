<?php
include 'config.php';
function extract_ids($url, $token) {
    $group_pattern = '/groups\/(\d+)\/permalink\/(\d+)\//';
    $post_pattern = '/(\d+)\/posts\/(\d+)\//';
    $photo_pattern = '/fbid=(\d+)/';

    if (preg_match($group_pattern, $url, $group_match)) {
        $group_id = $group_match[1];
        $post_id = $group_match[2];
        return "{$group_id}_{$post_id}";
    } elseif (preg_match($post_pattern, $url, $post_match)) {
        $group_id = $post_match[1];
        $post_id = $post_match[2];
        return "{$group_id}_{$post_id}";
    } elseif (preg_match($photo_pattern, $url, $photo_match)) {
        $photo_id = $photo_match[1];
        return $photo_id;
    } else {
        $pattern = '/\/posts\/([\w\d]+)\//';
        preg_match($pattern, $url, $matches);
        $postId = isset($matches[1]) ? $matches[1] : null;
        $userPattern = '/facebook\.com\/(\d+)\//';
        preg_match($userPattern, $url, $userMatches);
        $userId = isset($userMatches[1]) ? $userMatches[1] : null;
        if ($userId) {
            $extracted_id = "{$userId}_{$postId}";
        } else {
            $extracted_id = $postId;
        }
        $api_endpoint = "https://graph.facebook.com/{$extracted_id}?fields=id&access_token=".  $token;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
        $response = curl_exec($ch);
        curl_close($ch);
        $post_data = json_decode($response, true);
        if (isset($post_data['id'])) {
            return $post_data['id'];
        } else {
            return null;
        }
    }
}

function extract_ids_for_follow($url) {
    $pattern = '/facebook.com\/(?:profile\.php\?id=)?(\d+)|fbid=(\d+)/';
    
    if (preg_match($pattern, $url, $matches)) {
        if(isset($matches[1])) {
            return $matches[1];
        } elseif(isset($matches[2])) {
            return $matches[2];
        }
    } 
    
    return null;
}

function getRemainingCooldown($last_used_time) {
    $cooldown_duration = 30 * 60; // 20 minutes in seconds
    $current_time = time();
    $elapsed_time = $current_time - $last_used_time;
    $remaining_time = $cooldown_duration - $elapsed_time;
    return $remaining_time;
}

function getIdCooldownInfo($id, $table_name, $connection) {
    $sql = "SELECT UNIX_TIMESTAMP(last_used) AS last_used_timestamp FROM $table_name WHERE id_value = ?";
    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($stmt, "s", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $last_used_time = $row['last_used_timestamp'];
        $remaining_time = getRemainingCooldown($last_used_time);
        
        if ($remaining_time > 0) {
            $remaining_minutes = ceil($remaining_time / 60);
            return array("in_cooldown" => true, "remaining_time" => $remaining_minutes);
        } else {
            return array("in_cooldown" => false, "remaining_time" => 0);
        }
    } else {
        $last_week = date('Y-m-d H:i:s', strtotime('-1 week'));
        $sql_insert = "INSERT INTO $table_name (id_value, last_used) VALUES (?, ?)";
        $stmt_insert = mysqli_prepare($connection, $sql_insert);
        mysqli_stmt_bind_param($stmt_insert, "ss", $id, $last_week);
        mysqli_stmt_execute($stmt_insert);

        return array("in_cooldown" => false, "remaining_time" => 0);
    }
}

function checkCooldown($id, $table_name, $connection) {
    $sql = "SELECT UNIX_TIMESTAMP(last_used) AS last_used_timestamp FROM $table_name WHERE id_value = ?";
    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($stmt, "s", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $last_used_time = $row['last_used_timestamp'];
        $cooldown_duration = 20 * 60; // 20 minutes in seconds
        $current_time = time();
        $elapsed_time = $current_time - $last_used_time;
        $remaining_time = $cooldown_duration - $elapsed_time;
        
        if ($remaining_time <= 0) {
            $sql = "UPDATE $table_name SET last_used = NOW() WHERE id_value = ?";
            $stmt = mysqli_prepare($connection, $sql);
            mysqli_stmt_bind_param($stmt, "s", $id);
            mysqli_stmt_execute($stmt);
        }
    } else {
        $sql = "INSERT INTO $table_name (id_value, last_used) VALUES (?, NOW())";
        $stmt = mysqli_prepare($connection, $sql);
        mysqli_stmt_bind_param($stmt, "s", $id);
        mysqli_stmt_execute($stmt);
    }
}

function get_json($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_FAILONERROR, 0);
    $data = curl_exec($ch);
    curl_close($ch);
    return json_decode($data);
}

function get_html($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_FAILONERROR, 0);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

function me($access) {
    $response = _req('https://graph.facebook.com/me?access_token=' . $access);
    $valid = json_decode($response);

    if (isset($valid->error)) {
        if ($valid->error->type == "OAuthException") {
            include 'config.php';
            $query = "UPDATE Likers SET `activate` = 1 WHERE `access_token` = '" . mysqli_real_escape_string($connection, $access) . "'";
            mysqli_query($connection, $query) or die(mysqli_error($connection));
            mysqli_close($connection);
            session_destroy();
            header('Location: index.php?i=Your token has expired, please login again!');
            exit();
        } else {
            return null;
        }
    } else {
        return json_decode($response, true);
    }
}

function _req($url) {
    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_CONNECTTIMEOUT => 5,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_USERAGENT => $useragent,
        CURLOPT_URL => $url,
    ));
    
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
} 

function invalidToken() {
    print '
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Opps!</title>

        <style>
            /*! normalize.css v8.0.1 | MIT License | github.com/necolas/normalize.css */html{line-height:1.15;-webkit-text-size-adjust:100%}body{margin:0}a{background-color:transparent}code{font-family:monospace,monospace;font-size:1em}[hidden]{display:none}html{font-family:system-ui,-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica Neue,Arial,Noto Sans,sans-serif,Apple Color Emoji,Segoe UI Emoji,Segoe UI Symbol,Noto Color Emoji;line-height:1.5}*,:after,:before{box-sizing:border-box;border:0 solid #e2e8f0}a{color:inherit;text-decoration:inherit}code{font-family:Menlo,Monaco,Consolas,Liberation Mono,Courier New,monospace}svg,video{display:block;vertical-align:middle}video{max-width:100%;height:auto}.bg-white{--bg-opacity:1;background-color:#fff;background-color:rgba(255,255,255,var(--bg-opacity))}.bg-gray-100{--bg-opacity:1;background-color:#f7fafc;background-color:rgba(247,250,252,var(--bg-opacity))}.border-gray-200{--border-opacity:1;border-color:#edf2f7;border-color:rgba(237,242,247,var(--border-opacity))}.border-gray-400{--border-opacity:1;border-color:#cbd5e0;border-color:rgba(203,213,224,var(--border-opacity))}.border-t{border-top-width:1px}.border-r{border-right-width:1px}.flex{display:flex}.grid{display:grid}.hidden{display:none}.items-center{align-items:center}.justify-center{justify-content:center}.font-semibold{font-weight:600}.h-5{height:1.25rem}.h-8{height:2rem}.h-16{height:4rem}.text-sm{font-size:.875rem}.text-lg{font-size:1.125rem}.leading-7{line-height:1.75rem}.mx-auto{margin-left:auto;margin-right:auto}.ml-1{margin-left:.25rem}.mt-2{margin-top:.5rem}.mr-2{margin-right:.5rem}.ml-2{margin-left:.5rem}.mt-4{margin-top:1rem}.ml-4{margin-left:1rem}.mt-8{margin-top:2rem}.ml-12{margin-left:3rem}.-mt-px{margin-top:-1px}.max-w-xl{max-width:36rem}.max-w-6xl{max-width:72rem}.min-h-screen{min-height:100vh}.overflow-hidden{overflow:hidden}.p-6{padding:1.5rem}.py-4{padding-top:1rem;padding-bottom:1rem}.px-4{padding-left:1rem;padding-right:1rem}.px-6{padding-left:1.5rem;padding-right:1.5rem}.pt-8{padding-top:2rem}.fixed{position:fixed}.relative{position:relative}.top-0{top:0}.right-0{right:0}.shadow{box-shadow:0 1px 3px 0 rgba(0,0,0,.1),0 1px 2px 0 rgba(0,0,0,.06)}.text-center{text-align:center}.text-gray-200{--text-opacity:1;color:#edf2f7;color:rgba(237,242,247,var(--text-opacity))}.text-gray-300{--text-opacity:1;color:#e2e8f0;color:rgba(226,232,240,var(--text-opacity))}.text-gray-400{--text-opacity:1;color:#cbd5e0;color:rgba(203,213,224,var(--text-opacity))}.text-gray-500{--text-opacity:1;color:#a0aec0;color:rgba(160,174,192,var(--text-opacity))}.text-gray-600{--text-opacity:1;color:#718096;color:rgba(113,128,150,var(--text-opacity))}.text-gray-700{--text-opacity:1;color:#4a5568;color:rgba(74,85,104,var(--text-opacity))}.text-gray-900{--text-opacity:1;color:#1a202c;color:rgba(26,32,44,var(--text-opacity))}.uppercase{text-transform:uppercase}.underline{text-decoration:underline}.antialiased{-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}.tracking-wider{letter-spacing:.05em}.w-5{width:1.25rem}.w-8{width:2rem}.w-auto{width:auto}.grid-cols-1{grid-template-columns:repeat(1,minmax(0,1fr))}@-webkit-keyframes spin{0%{transform:rotate(0deg)}to{transform:rotate(1turn)}}@keyframes spin{0%{transform:rotate(0deg)}to{transform:rotate(1turn)}}@-webkit-keyframes ping{0%{transform:scale(1);opacity:1}75%,to{transform:scale(2);opacity:0}}@keyframes ping{0%{transform:scale(1);opacity:1}75%,to{transform:scale(2);opacity:0}}@-webkit-keyframes pulse{0%,to{opacity:1}50%{opacity:.5}}@keyframes pulse{0%,to{opacity:1}50%{opacity:.5}}@-webkit-keyframes bounce{0%,to{transform:translateY(-25%);-webkit-animation-timing-function:cubic-bezier(.8,0,1,1);animation-timing-function:cubic-bezier(.8,0,1,1)}50%{transform:translateY(0);-webkit-animation-timing-function:cubic-bezier(0,0,.2,1);animation-timing-function:cubic-bezier(0,0,.2,1)}}@keyframes bounce{0%,to{transform:translateY(-25%);-webkit-animation-timing-function:cubic-bezier(.8,0,1,1);animation-timing-function:cubic-bezier(.8,0,1,1)}50%{transform:translateY(0);-webkit-animation-timing-function:cubic-bezier(0,0,.2,1);animation-timing-function:cubic-bezier(0,0,.2,1)}}@media (min-width:640px){.sm\:rounded-lg{border-radius:.5rem}.sm\:block{display:block}.sm\:items-center{align-items:center}.sm\:justify-start{justify-content:flex-start}.sm\:justify-between{justify-content:space-between}.sm\:h-20{height:5rem}.sm\:ml-0{margin-left:0}.sm\:px-6{padding-left:1.5rem;padding-right:1.5rem}.sm\:pt-0{padding-top:0}.sm\:text-left{text-align:left}.sm\:text-right{text-align:right}}@media (min-width:768px){.md\:border-t-0{border-top-width:0}.md\:border-l{border-left-width:1px}.md\:grid-cols-2{grid-template-columns:repeat(2,minmax(0,1fr))}}@media (min-width:1024px){.lg\:px-8{padding-left:2rem;padding-right:2rem}}@media (prefers-color-scheme:dark){.dark\:bg-gray-800{--bg-opacity:1;background-color:#2d3748;background-color:rgba(45,55,72,var(--bg-opacity))}.dark\:bg-gray-900{--bg-opacity:1;background-color:#1a202c;background-color:rgba(26,32,44,var(--bg-opacity))}.dark\:border-gray-700{--border-opacity:1;border-color:#4a5568;border-color:rgba(74,85,104,var(--border-opacity))}.dark\:text-white{--text-opacity:1;color:#fff;color:rgba(255,255,255,var(--text-opacity))}.dark\:text-gray-400{--text-opacity:1;color:#cbd5e0;color:rgba(203,213,224,var(--text-opacity))}}
        </style>

        <style>
            body {
                font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
            }
        </style>
    </head>
    <body class="antialiased">
        <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center sm:pt-0">
            <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
                <div class="flex items-center pt-8 sm:justify-start sm:pt-0">
                    <div class="px-4 text-lg text-gray-500 border-r border-gray-400 tracking-wider">
                        OPPS!                    </div>

                    <div class="ml-4 text-lg text-gray-500 uppercase tracking-wider">
                        Something went wrong!                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
';
}

function alltoken($access) {
    // Your implementation
}
?>