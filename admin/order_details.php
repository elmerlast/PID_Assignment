<?php 
session_start();
require_once("../sql/onnDB.php");

if(!isset($_GET["id"])){
    die("id not found.");
  }
  
  $id = $_GET["id"];
  if(!is_numeric($id)){
    die("id not a number.");
  }
  
  $sql=<<<sqls
  select dets_name, dets_unitprice, dets_quantity
  from tbl_orderdetail where ord_id = $id
  sqls;
  $result =mysqli_query($link, $sql);

  $orderTotal = 0;

?>




<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>訂購項目</title>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
    integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="\PID_Assignment\css\bootstrap.min.css">
<link rel="stylesheet" href="\PID_Assignment\css\store_index.css">
<script src="\PID_Assignment\js\jquery.min.js"></script>
<script src="\PID_Assignment\js\bootstrap.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="\PID_Assignment\js\jquery.mycart.js"></script>




</head>
<body>

<nav class="navbar navbar-expand-sm bg-dark navbar-dark sticky-top">
    <!-- Brand/logo -->
    <a class="navbar-brand" href="/PID_Assignment/index.php">CC音饗</a>

    <!-- Links -->
    <ul class="navbar-nav ml-auto">
    <?php if(isset($_SESSION["uId"])){?>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <?php echo "{$_SESSION["uId"]}";?>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href="#">我的訂單</a>
          <a class="dropdown-item" href="/PID_Assignment/member/editor.php">編輯個人資料</a>
        </div>
      </li>
      <?php } ?>
        <?php if(isset($_SESSION["uId"])){?>
          <a class="nav-link" href="/PID_Assignment/index.php?signout=1"><span class="fa fa-sign-out"></span> 登出</a>
        <?php }else{ ?>
          <a class="nav-link" href="/PID_Assignment/member/login.php"><span class="fa fa-user"></span> 登入</a>
        <?php } ?>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/PID_Assignment/cart.php">
          <span class="fa fa-shopping-cart fa-lg" style="color:Beige"></span> <span
            class="badge badge-pill badge-danger"><?php if(isset($_SESSION["itemCountTotal"])){ echo "{$_SESSION["itemCountTotal"]}";}else{echo "0";} ?></span>
        </a>
      </li>
    </ul>
  </nav>

  <div class="container">
    <h1>訂購項目</h1>
    <table class="table table-hover">
  <thead class="thead-light">
    <tr>
      <th scope="col">項目名稱</th>
      <th scope="col">單價</th>
      <th scope="col">數量</th>
      <th scope="col">項目總計</th>

    </tr>
  </thead>
  <tbody>
   <?php while ($row = mysqli_fetch_assoc($result)){?>
      <tr>
        <th scope="row"><?=$row["dets_name"] ?></th>
           <td><?=$row["dets_unitprice"]?></td>
           <td><?=$row["dets_quantity"]?></td>  
           <td><?=($row["dets_unitprice"] * $row["dets_quantity"])?></td>  
      </tr>
      <?php $orderTotal += ($row["dets_unitprice"] * $row["dets_quantity"]); //計算訂單總額 ?>
   <?php } ?>
  </tbody>
</table>
<div class="row"><div class="col-10"></div><div class="col-2"><h6>&nbsp;總計&nbsp;&nbsp;&nbsp;&nbsp;$<?=$orderTotal?></h6></div></div>
<div class="row">
    <div class="col-10"></div>
    <div class="col-2">
      <a href="/PID_Assignment/admin/orders_admin.php" class="btn btn-info">返回訂單管理</a>
    </div>
</div>



  </div> 


</body>
</html>