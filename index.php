<?php
session_start();
if (isset($_SESSION['token']) && isset($_SESSION['_userid'])) {
    header('Location: home.php');
    exit();
}
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome to ffslikes!</title>
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
                                                <a target="_self"><span style="text-transform: uppercase"><span
                                                                style="font-size: 24px"><span
                                                                    style="letter-spacing: 1.0px"><span
                                                                        style="line-height: 48px"><strong
                                                                            style="font-weight: bold">ffslikes.site</strong></span></span></span></span></a>
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
            <div id="block_77">
                <div class="totals">
                    <h4 style="margin-left: 12px;" class="control-label">WELCOME TO FFSLIKES!</h4>
                    <p style="margin-left: 12px;">AutoLiker Tools is a social exchange platform that boosts likes
                        and comments on your posts, helping you gain followers and engagement for free.</p>
                    <hr style="margin: 12px;">
                    <div class="bg"></div>
                    <div class="divider-top"></div>
                    <div class="divider-bottom"></div>
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
                                                    <form action="" method="post">
                                                        <div class="component_form_group">
                                                            <div class="form-group">
                                                                <h4>Login</h4>
                                                                <p>Click the 'Login with Facebook' button below and
                                                                    login to start boosting your facebook post and
                                                                    followers.</p>
                                                            </div>
                                                        </div>
                                                        <div class="component_button_submit">
                                                            <div class="form-group">
                                                                <div>
                                                                    <a href="methods.php" id="post-button"
                                                                       class="btn btn-block btn-big-primary"><i
                                                                                class="fa fa-sign-out"
                                                                                aria-hidden="true"></i> Login with
                                                                        Facebook</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="wrapper-content__body">
                                                    <div id="block_77">
                                                        <div class="totals">
                                                            <div class="bg"></div>
                                                            <div class="divider-top"></div>
                                                            <div class="divider-bottom"></div>
                                                            <div class="container-fluid">
                                                                <div class="row sign-up-center-alignment">
                                                                    <div class="col-lg-8">
                                                                        <div class="component_card">
                                                                            <br>
                                                                            <div class="card" style="margin: -15px;">
                                                                            <h4>Demo tutorial</h4>
                                                                            <p>Watch this youtube video carefully, it might help you.</p>
                                                                            <br>
                                                                            <iframe height="260"
src="https://youtube.com/embed/u9LWTYaLciI?si=WSeZasa4QG9JQo9f"
title="YouTube video player"
frameborder="0"
allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
allowfullscreen></iframe>
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
</body>
</html>
