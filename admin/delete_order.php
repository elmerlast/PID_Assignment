<?php
session_start();

if ($_SESSION["level"]!=999) {
	$_SESSION["msgStatus"] = 11;//權限非管理員，進入訊息頁面會顯示權限不足提示。
	header("Location:/PID_Assignment/status.php");
	exit();
  }

require_once("../sql/onnDB.php");

if(!isset($_GET["id"])){
    die("id not found.");
}
  
$id = $_GET["id"];
if(!is_numeric($id)){
  die("id not a number.");
}
  
$sql=<<<sqls
delete from tbl_orders where ord_id = $id
sqls;
mysqli_query($link,$sql);
header("location: orders_admin.php");


?>