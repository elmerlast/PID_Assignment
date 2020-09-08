<?php 
session_start();

//無登入會員自動跳轉到登入頁面
if (!isset($_SESSION["uId"])) {
	header("location: /PID_Assignment/member/login.php");
	exit();
}

require_once "../sql/onnDB.php";

//透過session中的使用者名稱取得會員編號
$sql = "select m_id from tbl_users where m_username = '{$_SESSION["uId"]}'; ";
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_array($result);
$mId = $row["m_id"];

//取得該會員的訂單
$sql = <<<sql
select ord_id, ord_paytype, ord_total, ord_purchasetime, ord_status
from tbl_orders
where m_id = {$mId};
sql;
$result =mysqli_query($link, $sql);







?>




<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>我的訂單</title>

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
    <h1>我的訂單</h1>
    <table class="table table-hover">
  <thead class="thead-light">
    <tr>
      <th scope="col">訂單編號</th>
      <th scope="col">付款方式</th>
      <th scope="col">總額</th>
      <th scope="col">下訂時間</th>
      <th scope="col">訂單狀態</th>
      <th scope="col"></th>
    </tr>
  </thead>
  <tbody>
    <?php if(mysqli_num_rows($result) > 0){    
    while ($row = mysqli_fetch_assoc($result)){?>
      <tr>
        <th scope="row"><?=$row["ord_id"] ?></th>
           <td><?=$row["ord_paytype"]?></td>
           <td><?=$row["ord_total"]?></td>  
           <td><?=$row["ord_purchasetime"]?></td>  
           <td><?=$row["ord_status"]?></td>
           <td>
             <a href ="./order_details.php?id=<?= $row["ord_id"]?>" class="btn btn btn-success btn-sm"><i class="fa fa-info-circle "></i></a>
             <a href ="./delete_order.php?id=<?= $row["ord_id"]?>" class="btn btn btn-danger btn-sm"><i class="fa fa-times "></i></a>
           </td>  
        <?php }//end of while
        }else{?>
        <td colspan="6">
            <h5 style="text-align:center;"> 您目前沒有任何訂單。</h2>
        </td>
        <?php } ?>        
      </tr>
  </tbody>
</table>

<div class="row">
    <div class="col-10"></div>
    <div class="col-2">
      <a href="/PID_Assignment/index.php" class="btn btn-info">&emsp;返回首頁&emsp;</a>
    </div>
</div>



  </div> 


</body>
</html>