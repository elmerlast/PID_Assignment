
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


if (isset($_POST["btnCheckout"])) {
    header("location: /PID_Assignment/checkout.php");
    exit();
}

if (isset($_POST["btnRemoveAll"])) {
  $cart->empty_cart();

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

     }elseif($_SESSION["itemCountTotal"] > 0){
     $items = $cart->get_contents();
     foreach ($items as $item){?> 
      <tr>
        <td><?= $item["info"] ?></td>
        <td><?= "$".number_format($item["price"]) ?></td>
        <td style="text-align: center;"><?= $item["qty"] ?></td>
        <td><?= "$".number_format($item["subtotal"]) ?></td>
        <td>
        <a href ="./cart.php?id=<?= $item["id"]?>" class="btn btn btn-danger btn-sm"><i class="fa fa-times "></i></a>
        </td>
      </tr>
    <?php }//end of foreach
     }else{ ?>
      <tr>
        <td colspan="4">
        <h5 style="text-align:center;"> 您的購物車目前是空的。</h2>
        </td>
      </tr>




     <?php }//end of if?>

      
    </tbody>
  </table>
  <div class="row"><div class="col-10"></div><div class="col-2 d-inline"><h6 style="display: inline;">&nbsp;總計&nbsp;&nbsp;&nbsp;&nbsp;</h6><h6 class="price" style="display: inline;">$<?=number_format($cart->total)?></h6></div></div>
  <div class="row"><div class="col">&nbsp;</div></div>
  <div class="row">
  <div class="col-9"></div>
    <div class="col-3  ">
      <button name="btnRemoveAll" id="btnRemoveAll" type="submit" class="btn btn-outline-success "
           value="btnRemoveAll">清空購物車</button>
      <button name="btnCheckout" id="btnCheckout" type="submit" class="btn btn-success float-right"
           value="btnCheckout" <?php if($_SESSION["itemCountTotal"] == 0) {echo "disabled";}?> >結帳</button>
    </div>


    
  </div>
    </form>
  </div>
</main>
  
      
  </div>

</body>






</html>