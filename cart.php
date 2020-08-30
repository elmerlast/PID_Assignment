
<?php
include "wfCart/wfcart.php";
session_start();
$cart = &$_SESSION['wfcart']; // 指向購物車物件
if (!is_object($cart)) {
    $cart = new wfCart();
}




if(isset($_GET["id"])){
    $id = $_GET["id"];
    $cart->del_item($id);

    //取得購物車裡商品數量總數。
    $items = $cart->get_contents();
    $_SESSION["itemCountTotal"] = 0;
    foreach($items as $item){
    $_SESSION["itemCountTotal"] += $item["qty"];
    }

    
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
    <a class="navbar-brand">CC音饗</a>

    <!-- Links -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link" href="/PID_Assignment/index.php"><span class="fa fa-home"></span>首頁</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/PID_Assignment/member/login.php"><span class="fa fa-user"></span> 登入</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">
          <span class="fa fa-shopping-cart fa-lg" style="color:Beige"></span> <span
            class="badge badge-pill badge-danger"><?php if(isset($_SESSION["itemCountTotal"])){ echo "{$_SESSION["itemCountTotal"]}";}else{echo "0";} ?></span>
        </a>
      </li>
    </ul>
  </nav>

<main>
  <div class="container">
  <h2 style="text-align:center;">購物車</h2>
  <form method="post" action="">
  <table class="table table-hover" >
    <thead >
      <tr>
        <th>商品名稱</th>
        <th>價格</th>
        <th>&nbsp;&nbsp;&nbsp;&nbsp;數量</th>
        <th colspan="2">項目總計</th>
      </tr>
    </thead>
    <tbody>
    <?php if(isset($_POST["btnUpdate"])){ 
    //暫時拿掉修改的功能

     }else{
     $items = $cart->get_contents();
     foreach ($items as $item){?> 
      <tr>
        <td><?= $item["info"] ?></td>
        <td><?= $item["price"] ?></td>
        <td><?= $item["qty"] ?></td>
        <td><?= $item["subtotal"] ?></td>
        <td>
        <a href ="./cart.php?id=<?= $item["id"]?>" class="btn btn btn-danger btn-sm"><i class="fa fa-times "></i></a>
        </td>
      </tr>
    <?php }//end of foreach
     }//end of if

    ?>
      
    </tbody>
  </table>
  <div class="row"><div class="col-10"></div><div class="col-2"><h6>&nbsp;總計&nbsp;&nbsp;&nbsp;&nbsp;$<?=$cart->total?></h6></div></div>
  <div class="row">
  <div class="col-10"></div>
    <div class="col-2">
      <!-- <button name="btnUpdate" id="btnUpdate" type="submit" class="btn btn-outline-success"
                  value="btnUpdate">更新</button> -->
      <button name="btnCheckout" id="btnCheckout" type="submit" class="btn btn-success float-right"
                  value="btnCheckout">結帳</button>
    </div>
    
  </div>
    </form>
  </div>
</main>
  
      
  </div>

</body>






</html>