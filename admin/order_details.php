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

  require_once("../sql/onnDB.php");  
  $sql=<<<sqls
  select dets_name, dets_unitprice, dets_quantity
  from tbl_orderdetail where ord_id = $id
  sqls;
  $result =mysqli_query($link, $sql);
  mysqli_close($link);


  $orderTotal = 0;

?>




<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>訂購項目</title>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="\PID_Assignment\css\bootstrap.min.css">
<link rel="stylesheet" href="\PID_Assignment\css\store_index.css">
<script src="\PID_Assignment\js\jquery.min.js"></script>
<script src="\PID_Assignment\js\bootstrap.min.js"></script>
<script src="\PID_Assignment\js\jquery.mycart.js"></script>




</head>
<body>

<nav class="navbar navbar-expand-sm bg-dark navbar-dark sticky-top">
    <!-- Brand/logo -->
    <a class="navbar-brand" href="/PID_Assignment/index.php">CC音饗管理系統</a>

    <!-- Links -->
    <ul class="navbar-nav ml-auto">
    <?php if(isset($_SESSION["uId"])){?>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <?php echo "{$_SESSION["uId"]}";?>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href="/PID_Assignment/admin/orders_admin.php">訂單管理</a>
          <a class="dropdown-item" href="/PID_Assignment/admin/member_admin.php">會員管理</a>
          <a class="dropdown-item" href="/PID_Assignment/admin/commodity_admin.php">商品管理</a>

        </div>
      </li>
      <?php } ?>
        <?php if(isset($_SESSION["uId"])){?>
          <a class="nav-link" href="/PID_Assignment/index.php?signout=1"><span class="fa fa-sign-out"></span> 登出</a>
        <?php }else{ ?>
          <a class="nav-link" href="/PID_Assignment/member/login.php"><span class="fa fa-user"></span> 登入</a>
        <?php } ?>
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
           <td><?=number_format($row["dets_unitprice"])?></td>
           <td><?=$row["dets_quantity"]?></td>  
           <td><?="$".number_format(($row["dets_unitprice"] * $row["dets_quantity"]))?></td>  
      </tr>
      <?php $orderTotal += ($row["dets_unitprice"] * $row["dets_quantity"]); //計算訂單總額 ?>
   <?php } ?>
  </tbody>
</table>
<div class="row"><div class="col-10"></div><div class="col-2 d-inline"><h6 style="display: inline;">&nbsp;總計&nbsp;&nbsp;&nbsp;&nbsp;</h6><h6 class="price" style="display: inline;">$<?=number_format($orderTotal)?></h6></div></div>
<div class="row">
    <div class="col-10"></div>
    <div class="col-2">
      <a href="/PID_Assignment/admin/orders_admin.php" class="btn btn-info">返回訂單管理</a>
    </div>
</div>



  </div> 


</body>
</html>