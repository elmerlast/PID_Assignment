<?php

if(!isset($_GET["id"])){
    die("id not found.");
}

$id = $_GET["id"];
if(!is_numeric($id)){
    die("id not a number.");
}

$sql=<<<sqls
delete from tbl_users where m_id = $id
sqls;

require_once("../sql/onnDB.php");
mysqli_query($link,$sql);
header("location: member_admin.php");
?>