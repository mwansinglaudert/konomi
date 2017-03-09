<?php
$login = true;

$dateStamp = date('Y').'-'.date('m');
$userIdent = uniqid('');
if ($_SERVER['HTTP_HOST'] == 'konomi.mwansing.de') {
    $dbn = 'mwa_konomi';
    include "../../mwadb.php";
}else {
    include "functions/db.php";
}

$dateStamp = isset($_GET['time']) ? $_GET['time']: $dateStamp;
$aDate = explode("-",$dateStamp);

include "templates/_lang.php";
include "functions/functions.php";

$isLoggedIn = $pass->loggedIn();
$template = isset($_GET['template']) ? $_GET['template']: 'dashboard';
$template = $isLoggedIn ? $template: 'login';
$isPost = isset($_GET['post']) ? true : false;
$isAccess = isset($_GET['access']) ? true : false;
$isUpdate = isset($_GET['update']) ? true : false;
$logType = isset($_GET['logtype']) ? $_GET['logtype']: '0';

if($isPost){
    include "functions/post.php";
}
elseif ($isAccess){
    include "functions/access.php";
}
else{
    include "templates/_header.php";
    include "templates/".$template.".php";
    include "templates/modal.php";
    include "templates/_footer.php";
}

?>