<!doctype html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Konomi</title>
    <meta name="apple-mobile-web-app-title" content="konomi">

    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta content="black-translucent" id="status_bar" name="apple-mobile-web-app-status-bar-style" />
    <link rel="stylesheet" href="dist/assets/css/style.css">
    <link rel="apple-touch-icon" href="dist/assets/img/konomi-icon-192.png"/>
    <script>
        function setCookie(cname, cvalue, exdays) {
            var d = new Date();
            d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
            var expires = "expires=" + d.toUTCString();
            document.cookie = cname + "=" + cvalue + "; " + expires;
        }
        setCookie('ko_cookie_saved-webapp',window.navigator.standalone ? '_standalone':'',30);
    </script>
</head>
<body class="<?php echo $cookie->get('webapp');?>">
<div class="body">