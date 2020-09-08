<?php
session_start();

if ($_SESSION["level"]!=999 || !isset($_SESSION["uId"])) {
	$_SESSION["msgStatus"] = 11;//權限非管理員，進入訊息頁面會顯示權限不足提示。
	header("Location:/PID_Assignment/status.php");
	exit();
  }



if(!isset($_GET["id"])){
    die("id not found.");
}

$id = $_GET["id"];
if(!is_numeric($id)){
    die("id not a number.");
}


$sqlStatement = <<<categorysql
 select prd_images
 from tbl_product 
 where prd_id = {$id};
categorysql;

$result = mysqli_query($link, $sqlStatement);
$row = mysqli_fetch_assoc($result);

$picturePath = "../img".strrchr($row["prd_images"],"/");
unlink($picturePath);//刪除商品資料的同時一併刪除圖片


$sql=<<<sqls
delete from tbl_product where prd_id = $id
sqls;

require_once("../sql/onnDB.php");
mysqli_query($link,$sql);
mysqli_close($link);


header("location: commodity_admin.php");
?>