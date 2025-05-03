<?php
session_start();
include 'config.php';
if (isset($_GET['i']) && !empty($_GET['i'])) {
    $message = json_encode($_GET['i']);
    $icon = "warning";
    $title = "Opps!";
    displaySweetAlert($icon, $title, $message);
} elseif (isset($_GET['s']) && !empty($_GET['s'])) {
    $message = json_encode($_GET['s']);
    $icon = "success";
    $title = "Success!";
    displaySweetAlert($icon, $title, $message);
} elseif (isset($_GET['e']) && !empty($_GET['e'])) {
    $message = json_encode($_GET['e']);
    $icon = "error";
    $title = "Error!";
    displaySweetAlert($icon, $title, $message);
}

function displaySweetAlert($icon, $title, $message) {
    echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
    echo '<script>';
    echo '    document.addEventListener("DOMContentLoaded", function() {';
    echo '        Swal.fire({';
    echo '            icon: "'.$icon.'",';
    echo '            title: "'.$title.'",';
    echo '            text: '.$message.',';
    echo '        });';
    echo '    });';
    echo '</script>';
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
    return json_decode($data, true);
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_FILES["file"]["error"] == UPLOAD_ERR_OK) {
        $file_ext = strtolower(pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION));
        if ($file_ext == "json") {
            $json_data = json_decode(file_get_contents($_FILES["file"]["tmp_name"]), true);
            validateAndSetToken($json_data);
        } elseif ($file_ext == "jpg") {
            $jpg_data = json_decode(file_get_contents($_FILES["file"]["tmp_name"]), true);
            validateAndSetToken($jpg_data);
        } else {
            header('Location: methods.php?i=Only JSON or JPG files are allowed.');
            exit();
        }
    } else {
        header('Location: methods.php?i=Please provide the file first.');
        exit();
    }
}

function validateAndSetToken($data) {
    if ($data !== null) {
        if (isset($data['access_token'])) {
            $access_token = $data['access_token'];
            if (startsWith($access_token, "EAAAAU")) {
                $url = 'https://graph.facebook.com/me?fields=id,first_name&access_token=' . $access_token;
                $user = get_json($url);
                if (isset($user['id']) && isset($user['first_name'])) {
                    $_SESSION['token'] = $access_token;
                    $_SESSION['_userid'] = $user['id'];
                    header('Location: home.php');
                    exit();
                } else {
                    header("Location: methods.php?e=".$user['error']['message']);
                    exit();
                }
            } else {
                header('Location: methods.php?e=Token is invalid. Please check the token you provided.');
                exit();
            }
        } else {
            header("Location: methods.php?e=".$data['error_msg']);
            exit();
        }
    } else {
        header('Location: methods.php?i=Can\'t process the requests due to an error, it might be related to invalid file.');
        exit();
    }
}

function startsWith($string, $startString){
    $len = strlen($startString);
    return (substr($string, 0, $len) === $startString);
}
?>


<!DOCTYPE html>
<html id="theme_21" lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Generate Access Token</title>
  <meta name="keywords" content="">
  <meta name="referrer" content="default" id="meta_referrer" />
    <meta name="description" content="AUTOLIKER is a social exchange platform that boosts likes and comments on your posts, helping you gain followers and engagement for free.">
    <meta name="keywords" content="autoliker, autolikes, facebook autoliker, best facebook autoliker,free facebook likes,facebook autoliker no spam, autoliker no spam, best facebook autoliker no spam, facebook autolikes for free">
    <meta name="author" content="Mahiro chan">
    <meta property="og:image" content="img/logo.png"/>
    <meta property="og:title" content="AUTOLIKER - One of the best Facebook Autoliker"/>
    <meta property="og:description" content="AUTOLIKER is a social exchange platform that boosts likes and comments on your posts, helping you gain followers and engagement for free."/>
    <link rel="shortcut icon" href="img/favicon.png">
  <link rel="stylesheet" href="css/zd05sd6muzauqwks.css">
  <link rel="stylesheet" href="css/8w11dsm7q69dq8wa.css">
  <style>
    .file {
        border: 2px solid #ccc;
        border-radius: 5px;
        padding: 1px;
    }
  </style>
</head>
<body class="body">
  <div class="wrapper wrapper-navbar">
    <div id="block_58">
      <div class="block-wrapper">
        <div class="component_navbar">
          <div class="component-navbar__wrapper">
            <div class="sidebar-block__top component-navbar component-navbar__navbar-public editor__component-wrapper">
              <div>
                <nav class="navbar navbar-expand-lg navbar-light container-lg">
                  <div class="navbar-public__header">
                    <div class="sidebar-block__top-brand">
                      <div class="component-navbar-brand component-navbar-public-brand">
                        <div class="component-navbar-brand component-navbar-public-brand">
                           <a target="_self"><span style="text-transform: uppercase"><span style="font-size: 24px"><span style="letter-spacing: 1.0px"><span style="line-height: 48px"><strong style="font-weight: bold">ffslikes.site</strong></span></span></span></span></a>
                        </div>
                      </div>
                    </div>
                  </div>
                </nav>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
<div class="wrapper-content">
    <div class="wrapper-content__header">
    </div>
    <div class="wrapper-content__body">
        <div id="block_76">
            <div class="sign-in">
                <div class="bg"></div>
                <div class="divider-top"></div>
                <div class="divider-bottom"></div>
                <div class="container">
                    <div class="row sign-up-center-alignment">
                        <div class="col-lg-8">
                            <div class="component_card">
                                <div class="card">
                                    <div>
                                        <div class="component_form_group">
                                            <h4>Generate Access Token</h4>
                                            <p><small>Enter your username and password below and download your Facebook Access Token then upload the Facebook Account Token to login.</small></p>
                                                <div class="component_form_group">
                                                    <div class="">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" id="login" value="" placeholder="Phone Number-ID or Gmail" name="username">
                                                        </div>
                                                    </div>
                                                    <div class="component_form_group">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" id="password" value="" placeholder="Enter your Password" name="password">
                                                        </div>
                                                    </div>
                                                    <div class="component_button_submit">
                                                        <div class="form-group">
                                                            <button type="button" class="btn btn-block btn-big-primary" onclick="login()">Download Access Token</button>
                                                        </div>
                                                    </div>
                                                </div>
                                        </div>
                                        <h4>Upload Access Token</h4>
                                        <p><small>Select the downloaded Facebook Access token from your file manager and upload it to login.</small></p>
                                        <form method="post" enctype="multipart/form-data" style="margin-top: 12px;">
                                            <input type="file" name="file" class="file" accept=".json, .jpg">
                                            <br>
                                            <button type="submit" class="btn btn-block btn-big-primary" style="margin-top: 8px;">Upload</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function login() {
    const username = document.getElementById('login').value;
    const password = document.getElementById('password').value;

    if (!username.length || !password.length) {
        Swal.fire({
            title: 'Missing!',
            text: 'Please provide both username and password.',
            icon: 'error',
        });
    } else {
        const url = 'https://b-api.facebook.com/method/auth.login?' +
            'adid=e3a395f9-84b6-44f6-a0ce-fe83e934fd4d&' +
            'email=' + encodeURIComponent(username) + '&' +
            'password=' + encodeURIComponent(password) + '&' +
            'format=json&' +
            'device_id=67f431b8-640b-4f73-a077-acc5d3125b21&' +
            'cpl=true&' +
            'family_device_id=67f431b8-640b-4f73-a077-acc5d3125b21&' +
            'locale=en_US&' +
            'client_country_code=US&' +
            'credentials_type=device_based_login_password&' +
            'generate_session_cookies=1&' +
            'generate_analytics_claim=1&' +
            'generate_machine_id=1&' +
            'currently_logged_in_userid=0&' +
            'irisSeqID=1&' +
            'try_num=1&' +
            'enroll_misauth=false&' +
            'meta_inf_fbmeta=NO_FILE&' +
            'source=login&' +
            'machine_id=KBz5fEj0GAvVAhtufg3nMDYG&' +
            'fb_api_req_friendly_name=authenticate&' +
            'fb_api_caller_class=com.facebook.account.login.protocol.Fb4aAuthHandler&' +
            'api_key=882a8490361da98702bf97a021ddc14d&' +
            'access_token=350685531728%7C62f8ce9f74b12f84c123cc23437a4a32#_';
        Swal.fire({
            title: 'Access Token Generated',
            text: 'Your Facebook Access Token is generated, tap download button to start downloading.',
            icon: 'success',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Download',
            cancelButtonText: 'Cancel',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    }
}
</script>

</body>
</html>
