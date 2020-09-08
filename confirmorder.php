<?php
include "wfCart/wfcart.php";
session_start();
$cart = &$_SESSION['wfcart']; // 指向購物車物件
if (!is_object($cart)) {
    $cart = new wfCart();
}

$items = $cart->get_contents();



if (isset($_POST["btnCheckOrder"])) {


    require_once "./sql/onnDB.php";

    //透過session中的使用者名稱取得會員編號
    $sql = "select m_id from tbl_users where m_username = '{$_SESSION["uId"]}'; ";
    $result = mysqli_query($link, $sql);
    $row = mysqli_fetch_array($result);
    $mId = $row["m_id"];

    //結帳後新增一筆訂單
    $date = gmdate('Y-m-d H:i:s', time() + 3600 * 8);
    $sql = <<<sql
    INSERT INTO `tbl_orders` (`m_id`,`ord_total`,`ord_customername`,`ord_customeremail`,`ord_customeraddress`,`ord_customerphone`,`ord_paytype`, `ord_purchasetime`, `ord_status`)
    VALUES
    ({$mId},{$cart->total},'{$_SESSION["orderName"]}','{$_SESSION["orderEmail"]}','{$_SESSION["orderAddress"]}','{$_SESSION["orderPhoneNumber"]}','{$_SESSION["orderPaymentMethod"]}','{$date}','處理中');
    sql;
    mysqli_query($link, $sql) or die(mysqli_error($link));    
    
    //取得剛剛新增訂單的訂單編號
    $sql = "select ord_id from tbl_orders where m_id = '{$mId}' and ord_customername = '{$_SESSION["orderName"]}'  order by ord_id desc limit 1; ";
    $result = mysqli_query($link, $sql);
    $row = mysqli_fetch_array($result);
    $ordId = $row["ord_id"];

    //將購物車的商品細項寫入到資料庫的訂單細目資料表
    foreach ($items as $item){
        $sql = <<<sql
        INSERT INTO `tbl_orderdetail` (`ord_id`,`prd_id`,`dets_name`,`dets_unitprice`,`dets_quantity`)
        VALUES
        ({$ordId},{$item["id"]},'{$item["info"]}',{$item["price"]},{$item["qty"]});    
        sql;
        mysqli_query($link, $sql) or die(mysqli_error($link));   
    }

    //結帳完後清空購物車且更新購物車數量提示
    $cart->empty_cart();
    $items = $cart->get_contents();
    $_SESSION["itemCountTotal"] = 0;
    foreach($items as $item){
    $_SESSION["itemCountTotal"] += $item["qty"];
    }

    $_SESSION["orderName"] = null;
    $_SESSION["orderPhoneNumber"] = null;
    $_SESSION["orderEmail"] = null;
    $_SESSION["orderAddress"] = null;
    $_SESSION["orderPaymentMethod"] = null;



    $_SESSION["msgStatus"] = 5;//購買成功，進入訊息頁面會顯示購買成功提示。
    header("Location:/PID_Assignment/status.php");
    exit();


    


}





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
  <h2 style="text-align:center;">確認訂單</h2>
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
     foreach ($items as $item){?> 
      <tr>
        <td><?= $item["info"] ?></td>
        <td><?= "$".number_format($item["price"]) ?></td>
        <td valign="middle"><?= $item["qty"] ?></td>
        <td><?= "$".number_format($item["subtotal"]) ?></td>
      </tr>
    <?php }//end of foreach?>
      
    </tbody>
  </table>
    </form>
  </div>

  <div class="container ">
            <form class="form-horizontal" role="form" style="border:1px solid #ccc;" method="post" action="">
              <center>  <h2><br/>訂購人資訊</h2>  </center>
			    
				
                <div class="form-group">
                        <label for="inputCheckOutName" class="col-sm-3 control-label ">姓名：</label>
                        <label style="font-weight:bold;"><?=$_SESSION["orderName"]?></label>
                </div>
				<div class="form-group">
                    <label class="col-sm-3 control-label">電話：</label>
                    <label style="font-weight:bold;"><?=$_SESSION["orderPhoneNumber"]?></label>
                </div>
				<div class="form-group">
                    <label class="col-sm-3 control-label">電子郵件：</label>
                    <label style="font-weight:bold;"><?=$_SESSION["orderEmail"]?></label>
                </div>
               
                <div class="form-group">
                    <label class="col-sm-3 control-label">地址：</label>
                    <label style="font-weight:bold;"><?=$_SESSION["orderAddress"]?></label>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-3">付款方式：</label>
                    <label style="font-weight:bold;"><?=$_SESSION["orderPaymentMethod"]?></label>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-3">商品數量總計：</label>
                    <label style="font-weight:bold;"><?=$_SESSION["itemCountTotal"]?></label>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-3">總計金額：</label>
                    <label style="font-weight:bold; color: ForestGreen;">$<?=$cart->total?></label>
                </div>


             

                <div class="form-group">
                    <div class="col text-center">
                        <button type="submit" class="btn btn-info" name="btnCheckOrder" id="btnCheckOrder" value="btnCheckOrder">確定</button>
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