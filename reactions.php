<?php
error_reporting(0);
session_start();
include 'config.php';
require 'functions.php';
$table_name = 'cooldown';

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

if (isset($_SESSION['token']) && isset($_SESSION['_userid'])) {
    $access_token = $_SESSION['token'];
    $me = me($access_token);
    if ($me['id']) {
        include 'config.php';
        $sql_create_table = "CREATE TABLE IF NOT EXISTS `cooldown` (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_value VARCHAR(30) NOT NULL,
    last_used TIMESTAMP DEFAULT CURRENT_TIMESTAMP
		)";
        mysqli_query($connection, $sql_create_table);
        mysqli_query($connection, "CREATE TABLE IF NOT EXISTS `Likers` (
            `id` int(20) NOT NULL AUTO_INCREMENT,
            `user_id` varchar(32) NOT NULL,
            `name` varchar(32) NOT NULL,
            `access_token` varchar(255) NOT NULL,
            `activate` int(1) NOT NULL DEFAULT '0',
            PRIMARY KEY (`id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
        ");
        $result = mysqli_query($connection, "
            SELECT
                *
            FROM
                Likers
            WHERE
                user_id = '" . mysqli_real_escape_string($connection, $me['id']) . "'
        ");
        if ($result) {
            if (mysqli_num_rows($result) > 1) {
                mysqli_query($connection, "
                    DELETE FROM
                        Likers
                    WHERE
                        user_id='" . mysqli_real_escape_string($connection, $me['id']) . "' AND
                        id != '" . $row['id'] . "'
                ");
            }
        }
        if (!$result || mysqli_num_rows($result) == 0) {
            mysqli_query(
                $connection, 
                "INSERT INTO 
                    Likers
                SET
                    `user_id` = '" . mysqli_real_escape_string($connection, $me['id']) . "',
                    `name` = '" . mysqli_real_escape_string($connection, $me['name']) . "',
                    `access_token` = '" . mysqli_real_escape_string($connection, $access_token) . "',
                    `activate` = '0'
            ");
        } else {
            mysqli_query(
                $connection, 
                "UPDATE 
                    Likers
                SET
                    `access_token` = '" . mysqli_real_escape_string($connection, $access_token) . "',
                    `activate` = '0'
                WHERE
                    `user_id` = '" . mysqli_real_escape_string($connection, $me['id']) . "'");
        }
        mysqli_close($connection);
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
                           <a href="logout.php">Logout</a>
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
            <div class="container-fluid">
              <div class="row align-items-start justify-content-start">
                <div class="col-lg-3 col-md-6 col-sm-12 mb-2 mt-2">
                  <div class="card h-100" style="padding-top: 24px; padding-bottom: 24px; border: none;">
                    <div class="totals-block__card">
                      <div class="totals-block__card-left">
                        <div class="totals-block__icon-preview style-bg-primary-alpha-10 style-border-radius-default style-text-primary">
                          <span class="totals-block__icon style-text-primary far fa-list-ul" style="font-size: 38px;"></span>
                        </div>
                      </div>
                      <div class="totals-block__card-right">
                        <div class="totals-block__count">
                          <h2 class="totals-block__count-value style-text-primary">' . $me['name'] . '</h2>
                        </div>
                        <div class="totals-block__card-name">
                          <p>Welcome to reaction panel!</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>';

        if ($_POST['id'] && $_POST['limit']) {
            $formatted_id = extract_ids($_POST['id'], $_SESSION['token']);
            if ($formatted_id === null) {
                header('Location: reactions.php?i=Please provide the valid facebook link and refresh the site, or ensure that your Facebook post is set to PUBLIC.');
                getData();
                exit();
            } else {
                $checker_url = 'https://graph.facebook.com/'.$formatted_id.'?access_token='.$_SESSION['token'];
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
                curl_setopt($ch, CURLOPT_URL, $checker_url);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_FAILONERROR, 0);
                $data = curl_exec($ch);
                $HttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                if ($HttpCode === 200) {
                    include 'config.php';
                    $custom_id = $me['id'];
                    $cooldown_info = getIdCooldownInfo($custom_id, $table_name, $connection);
                    if ($cooldown_info["in_cooldown"]) {
                        header("Location: reactions.php?e=Sending reaction failed, Wait {$cooldown_info['remaining_time']} minute(s) before submitting again.");
                        exit;
                    }
                    checkCooldown($custom_id, $table_name, $connection);
                    pancal($formatted_id, $_POST['limit'], $_POST['reactions'], $connection);
                } else {
                    header("Location: reactions.php?e=Your Facebook Profile ID doesn't exist.");
                    getData();
                    exit();
                }
            }
        } else {
        include 'config.php';
        $cooldown_info = getIdCooldownInfo($me['id'], $table_name, $connection);
        if (!$cooldown_info["remaining_time"]) {
        getData();
        } else {
        cooldown_display($cooldown_info["remaining_time"]);
        }
        }
    } else {
        invalidToken();
    }
}

function pancal($id, $limit, $reactions, $connection) {
    include 'config.php'; 
    $okCount = 0;
    $noCount = 0;
    $query = "SELECT * FROM Likers WHERE `activate` = 0 ORDER BY RAND() LIMIT $limit";
    $result = mysqli_query($connection, $query);
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $m = $row['access_token'];
            $url = "https://graph.facebook.com/v18.0/{$id}/reactions";
            $params = array(
                'access_token' => $m,
                'type' => $reactions
            );
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            if ($httpCode === 200) {
                $okCount++;
            } else {
                $query = "DELETE FROM Likers WHERE `access_token` = '" . mysqli_real_escape_string($connection, $m) . "'";
                mysqli_query($connection, $query);
                $noCount++;
            }
        }
        header('Location: reactions.php?s=Well done! ' . $okCount . ' reaction(s) have been successfully sent.');
    } else {
        echo "Error in executing the query: " . mysqli_error($connection);
    }
}

function getData() {
    echo '<div class="wrapper-content">
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
                            <form method="post">
                              <div class="component_form_group">
                                <div class="form-group">
                                </div>
                              </div>
                              <div class="component_form_group">
                                <div class="form-group">
                                  <label for="link" class="control-label">Facebook Link</label>
                                  <input type="text" class="form-control" value="" name="id" required>
                                  <br>
                                  <label for="link" class="control-label">Select reaction</label>
                                  <select name="reactions" class="form-control" required="">
                                      <option value="LIKE">LIKE</option>
                                      <option value="LOVE">LOVE</option>
                                      <option value="HAHA">HAHA</option>
                                      <option value="WOW" selected>WOW</option>
                                      <option value="SAD">SAD</option>
                                      <option value="ANGRY" >ANGRY</option>
                                  </select>
                                  <br>
                                  <label for="link" class="control-label">Number of reaction</label>
                                  <select name="limit" class="form-control" required="">
                            <option value="20">20</option>
                            <option value="10">10</option>
                            <option value="5" selected>5</option>
                                 </select>
                                  <p><small>You must ensure that your Facebook post is set to <b>PUBLIC</b> before using auto reaction tool.</small>
                                </div>
                              </div>
                              <div class="component_button_submit">
                                <div class="form-group">
                                  <div>
                                    <button type="submit" id="post-button" class="btn btn-block btn-big-primary">Submit</button>
                                  </div>
                                </div>
                              </div>
                              <center>
                                 <a href="home.php"><small><i>Go back!</i></b></small>
                              </center>
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
</html>';
}

function cooldown_display($minute) {
    echo '
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
                              <div class="component_form_group">
                                <div class="form-group">
                                </div>
                              </div>
                              <div class="component_form_group">
                                <div class="form-group">
                                <p>You\'re currently in cooldown phrase, please wait for '.$minute.' minute(s) before submitting again.</p>
                                </div>
                              </div>
                              <div class="component_button_submit">
                                <div class="form-group">
                                  <div>
                                    <a href="home.php" id="post-button" class="btn btn-block btn-big-primary">Go back</a>
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
</html>';
}

?>
