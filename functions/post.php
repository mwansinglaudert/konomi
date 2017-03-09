<?php
$aPost = $_POST;
$postQuery = '';
$postVals = '';
$updateQuery = '';
$isDelete = false;
if ($isUpdate) {
    $updateIdent = $_POST['id'];
}
if (array_key_exists('delete', $aPost)) {
    if ($aPost['delete'] == 1) {
        $isDelete = true;
    }
    unset($aPost['delete']);
}

foreach ($aPost as $key => $value) {
    if (!$isUpdate) {
        $val = $value;
        if ($key == 'sum') {
            $val = price($value);
        }

        $postQuery .= $key;
        $postVals .= "'" . $val . "'";

        if ($value !== end($aPost)) {
            $postQuery .= ', ';
            $postVals .= ', ';
        }
    } else {
        if ($value !== reset($aPost)) {
            $updateQuery .= ", ";
        }
        $updateQuery .= $key . "='" . $value . "'";
    }
}

if (!$isUpdate) {
    $postSql = "INSERT INTO ko_logs ($postQuery) VALUES ($postVals)";
} else {
    if (!$isDelete) {
        $postSql = "UPDATE ko_logs SET $updateQuery WHERE id=$updateIdent";
    } else {
        $postSql = "DELETE FROM ko_logs WHERE id=$updateIdent";
    }
}

if (mysqli_query($db, $postSql)) {
    echo 'success';
} else {
    echo 'error';
}
?>