<?php
include "wfCart/wfcart.php";
session_start();
$cart = &$_SESSION['wfcart']; // 指向購物車物件
if (!is_object($cart)) {
    $cart = new wfCart();
}


if (!isset($_SESSION["uId"])) {
  $_SESSION["checkout"] = "true";
  header("location: /PID_Assignment/member/login.php");
	exit();
}

if ($_SESSION["level"] < 3) {
	$_SESSION["msgStatus"] = 12;//禁止購物會員，進入訊息頁面會顯示權限不足提示。
	header("Location:/PID_Assignment/status.php");
	exit();
  }




if (isset($_POST["btnPlaceOrder"])) {

  $_SESSION["orderName"] = $_POST["inputOrderName"];
  $_SESSION["orderPhoneNumber"] = $_POST["inputPhoneNumber"];
  $_SESSION["orderEmail"] = $_POST["inputEmail"];
  $_SESSION["orderAddress"] = $_POST["inputAddress"];
  $_SESSION["orderPaymentMethod"] = $_POST["radioPaymentMethod"];
  header("location: /PID_Assignment/confirmorder.php");
	exit();

}


require_once "./sql/onnDB.php";
$sql = "select * from tbl_users where m_username = '{$_SESSION["uId"]}'; ";
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_array($result);



?>



<!DOCTYPE html>
<html>

<head>
  <title>CC購物</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <link rel="stylesheet" href="\PID_Assignment\css\bootstrap.min.css">
  <link rel="stylesheet" href="\PID_Assignment\css\store_index.css">
  <script src="\PID_Assignment\js\jquery.min.js"></script>
  <script src="\PID_Assignment\js\bootstrap.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="\PID_Assignment\js\jquery.mycart.js"></script>





  <style>

  </style>


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
          <a class="dropdown-item" href="#">修改會員資料</a>
        </div>
      </li>
      <?php } ?>
      <li class="nav-item">
        <a class="nav-link" href="/PID_Assignment/index.php"><span class="fa fa-home"></span>首頁</a>
      </li>
      <li class="nav-item">
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


<main>
  <div class="container">
  <h2 style="text-align:center;">商品結帳</h2>
  <form method="post" action="">
  <table class="table" >
    <thead >
      <tr>
        <th>商品名稱</th>
        <th>價格</th>
        <th>&nbsp;&nbsp;&nbsp;&nbsp;數量</th>
        <th colspan="2">項目總計</th>
      </tr>
    </thead>
    <tbody>
    <?php 
     $items = $cart->get_contents();
     foreach ($items as $item){?> 
      <tr>
        <td><?= $item["info"] ?></td>
        <td><?="$".number_format($item["price"]) ?></td>
        <td><?= $item["qty"] ?></td>
        <td><?="$".number_format($item["subtotal"]) ?></td>
      </tr>
    <?php }//end of foreach?>
      
    </tbody>
  </table>
  <div class="row"><div class="col-10"></div><div class="col-2 d-inline"><h6 style="display: inline;">總計&nbsp;&nbsp;&nbsp;&nbsp;</h6><h6 class="price" style="display: inline;">$<?=number_format($cart->total)?></h6></div></div>
    </form>
  </div>

  <div class="container ">
            <form class="form-horizontal" role="form" style="border:1px solid #ccc;" method="post" action="" >
              <center>  <h2><br/>訂購人資訊</h2>  </center>
			    
				
                <div class="form-group">
                    <label for="inputOrderName" class="col-sm-3 control-label">姓名</label>
                    <div class="col-sm-4">
                        <input type="text" id="inputOrderName" name="inputOrderName"  class="form-control" value="<?=$row["m_name"] ?>" required autofocus>
                       
                    </div>
                </div>
				<div class="form-group">
                    <label for="inputPhoneNumber" class="col-sm-3 control-label">電話</label>
                    <div class="col-sm-4">
                        <input type="text" id="inputPhoneNumber" name="inputPhoneNumber"   class="form-control" value="<?=$row["m_phone"] ?>" required autofocus>
                       
                    </div>
                </div>
				<div class="form-group">
                    <label for="inputEmail" class="col-sm-3 control-label">電子郵件</label>
                    <div class="col-sm-4">
                        <input type="text" id="inputEmail"  name="inputEmail"  class="form-control" value="<?=$row["m_email"] ?>" required autofocus>
                       
                    </div>
                </div>
               
                <div class="form-group">
                    <label for="inputAddress" class="col-sm-3 control-label">地址</label>
                    <div class="col-sm-4">
                        <input type="text" id="inputAddress" name="inputAddress"  class="form-control" required autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-3">付款方式</label>
                    <div class="col-sm-6">
                        <div class="row">
                            <div class="col-sm-6">
                                <label class="radio-inline">
                                    <input type="radio" name="radioPaymentMethod" id="radioPaymentMethod" value="線上付款" required>線上付款
                                </label>
								<label class="radio-inline">
                                    <input type="radio" name="radioPaymentMethod" id="radioPaymentMethod" value="ATM轉帳">ATM轉帳
                                </label>
								<label class="radio-inline">
                                    <input type="radio" name="radioPaymentMethod" id="radioPaymentMethod" value="貨到付款">貨到付款
                                </label>
                            </div>
                           
                            </div>
                            
                                
                            
                        </div>
                    </div>
             

                <div class="form-group">
                    <div class="col text-center">
                        <button type="submit" class="btn btn-info" name="btnPlaceOrder" id="btnPlaceOrder" value="btnPlaceOrder">確定</button>
                    </div>
                </div>
            </form> <!-- /form -->
            </div> <!-- /div -->

</main>

<section>
    <footer>
      <div class="row">
        <div class="col-12"><h3>&nbsp;&nbsp;</h3></div>
      </div>
    </footer>
</section>
 

</body>






</html>