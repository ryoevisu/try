<?php
session_start();
include 'config.php';
require_once 'functions.php';

if (!isset($_SESSION['token']) && !isset($_SESSION['_userid'])) {
header('Location: index.php');
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

if (isset($_POST['activate'])) {
    $api_url = 'https://graph.facebook.com/graphql?variables={"0":{"is_shielded":true,"session_id":"9b78191c-84fd-4ab6-b0aa-19b39f04a6bc","client_mutation_id":"b0316dd6-3fd6-4beb-aed4-bb29c5dc64b0"}}&method=post&doc_id=1477043292367183&query_name=IsShieldedSetMutation&strip_defaults=false&strip_nulls=false&locale=en_US&client_country_code=US&fb_api_req_friendly_name=IsShieldedSetMutation&fb_api_caller_class=IsShieldedSetMutation&access_token='.urlencode($_SESSION['token']);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_FAILONERROR, 0);
    $data = curl_exec($ch);
    curl_close($ch);
    $response = json_decode($data, true);
    if ($response['extensions']['is_final'] === true) {
    header("Location: profile.php?s=Profile Guard Successfully activated!");
    } else {
    header("Location: profile.php?e=Sorry, an error encountered while processing your request, please try again later.");
    }
}

if (isset($_POST['deactivate'])) {
    $api_url = 'https://graph.facebook.com/graphql?variables={"0":{"is_shielded":false,"session_id":"9b78191c-84fd-4ab6-b0aa-19b39f04a6bc","client_mutation_id":"b0316dd6-3fd6-4beb-aed4-bb29c5dc64b0"}}&method=post&doc_id=1477043292367183&query_name=IsShieldedSetMutation&strip_defaults=false&strip_nulls=false&locale=en_US&client_country_code=US&fb_api_req_friendly_name=IsShieldedSetMutation&fb_api_caller_class=IsShieldedSetMutation&access_token='.urlencode($_SESSION['token']);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_FAILONERROR, 0);
    $data = curl_exec($ch);
    curl_close($ch);
    $response = json_decode($data, true);
    if ($response['extensions']['is_final'] === true) {
    header("Location: profile.php?s=Profile Guard Successfully deactivated!");
    } else {
    header("Location: profile.php?e=Sorry, an error encountered while processing your request, please try again later.");
    }
}

if ($_SESSION['token']) {
    $access_token = $_SESSION['token'];
    $me = me($access_token);
    if (!$me['name']) {
    invalidToken();
    } else {
    echo '<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard</title>
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
        <div id="block_77">
          <div class="totals">
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
                                    <center>
                                        <h4>Hello, ' . $me['name'] . '</h4>
                                        <img src="https://graph.facebook.com/' . $me['id'] . '/picture?width=1500&height=1500&access_token=1174099472704185|0722a7d5b5a4ac06b11450f7114eb2e9" alt="Profile" style="height:100px;width:100px;"/>
                                        </a><br/>Your Name: <b>' . $me['name'] . '</b></br>
            Profile ID: <b>' . $me['id'] . '</b></br>
            Status: <span style="color: #fff; background-color: #5cb85c; border-color: #5cb85c; border-radius: 5px; padding: 1px 4px; font-size: 12px;">ACTIVE USER</span><br>
                                         
                                         <br>
                                         <h5>Profile Guard Activator</h5>
                                         <p><small>If you\'re using your <b>main account</b> we highly recommend removing the logged location or changing the password immediately after the process so that your token will not be included for Facebook liker.</small></p>
                                    </center>
                                </div>
                              </div>
                              <div class="component_button_submit">
                                <div class="form-group">
                                  <div>
                                      <form method="post" action="">
                                          <button type="submit"  name="activate" id="post-button" class="btn btn-block btn-big-primary"><i class="fa fa-shield" aria-hidden="true"></i> ACTIVATE</button>
                                    <button type="submit"  name="deactivate" id="post-button" class="btn btn-block btn-big-primary"><i class="fa fa-shield" aria-hidden="true"></i> DEACTIVATE</button>

                                    <a href="home.php" id="post-button" class="btn btn-block btn-big-primary"><i class="fa fa-sign-out" aria-hidden="true"></i> GO BACK</a>                             
                                      </form>       
                                  </div>
                                </div>
                              </div>
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
      </div>
    </div>
  </div>
</body>
</html>
';
}
}
?>
