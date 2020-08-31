<?php
require_once "../sql/onnDB.php";

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
  header("location: order_list.php");


?>