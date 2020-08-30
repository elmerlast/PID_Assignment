<!DOCTYPE html>
<?php
include "wfCart/wfcart.php";
session_start();
$cart = &$_SESSION['wfcart']; // 指向購物車物件
if (!is_object($cart)) {
    $cart = new wfCart();
}

if (isset($_POST["btnAddToCart"])) {
    $cart->add_item($_POST["prd_id"],1,$_POST["prd_price"],"{$_POST["prd_name"]}");

    //取得購物車裡商品數量總數。
    $items = $cart->get_contents();
    $_SESSION["itemCountTotal"] = 0;
    foreach($items as $item){
    $_SESSION["itemCountTotal"] += $item["qty"];
}
}


require_once "./sql/onnDB.php";

//抓取MySQL資料庫中的商品分類資料。
$sqlStatement = <<<categorysql
 select ca_name
 from tbl_category;
categorysql;
$catResult = mysqli_query($link, $sqlStatement);




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
    <a class="navbar-brand" href="#">CC音饗</a>

    <!-- Links -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link" href="#"><span class="fa fa-home"></span>首頁</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#"><span class="fa fa-user"></span> 登入</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">
          <span class="fa fa-shopping-cart fa-lg" style="color:Beige"></span> <span
            class="badge badge-pill badge-danger"><?=$_SESSION["itemCountTotal"] ?></span>
        </a>

      </li>
    </ul>
  </nav>





  <div class="container">
    <table class="table table-hover tables">
      <thead class="thead-light">
        <tr>
          <th colspan="4">
            <form>
              <!-- <div class="form-group row">
                <label for="text" class="col-2 col-form-label">搜尋商品：</label>
                <div class="col-4">
                  <input id="text" name="text" type="text" class="form-control">
                </div>
                <div class="col-1"><button name="submit" type="submit" class="btn btn-primary">Go</button></div> -->
              <div class="input-group">
                <input id="text" name="text" type="text" class="form-control" placeholder="請輸入商品名稱">
                <div class="input-group-append">
                  <button class="btn btn-primary" type="button">搜尋</button>
                </div>
            </form>
          </th>
        </tr>
        <tr>
          <th colspan="4">
            <form method="post" action="">
              <div class="form-group row">
                <label class="col-2" for="Categories">商品分類：</label>
                <select id="Categories" name="Categories" class="col-4 form-control">
                  <?php while ($row = mysqli_fetch_assoc($catResult)) {?>
                  <option value="<?=$row["ca_name"]?>"
                    <?php if ($row["ca_name"] == $_POST["Categories"]) {echo "selected";}?>><?=$row["ca_name"]?>
                  </option>
                  <?php }?>
                </select>
                <button name="btnCatFilter" id="btnCatFilter" type="submit" class="btn btn-primary"
                  value="btnCatFilter">篩選</button>
              </div>
            </form>

          </th>
        </tr>
      </thead>

    </table>

    <div class="container">
      <?php if (isset($_POST["btnCatFilter"]) && ($_POST["Categories"] != "全部產品")) { //使用者設定分類進行篩選的話：

    $sqlStatement = <<<categoryProductSql
      select prd_name, prd_price, prd_images, prd_description, ca_name
      from tbl_category
      left join tbl_product
      on tbl_category.ca_id = tbl_product.ca_id
      where ca_name = '{$_POST["Categories"]}';
      categoryProductSql;
    $catProductResult = mysqli_query($link, $sqlStatement);

    $itemPageCount = 0; //每列只顯示3個商品項目。
    ?>

      <div class="row">

        <?php while ($row = mysqli_fetch_assoc($catProductResult)) {?>


        <div class="col-sm-4">
          <div class="card" style="width: 18rem;">
            <img class="card-img-top" src="<?=$row["prd_images"]?>" alt="沒有圖片">
            <div class="card-body">
              <h6 class="card-subtitle mb-2 text-muted"><?=$row["prd_name"]?></h6>
              <p class="card-text price"><?="$" . $row["prd_price"]?></p>
              <div class="row">
                <div class="col">
                  <form action="" method="post">
                    <input type="hidden" name="prd_id" value="<?=$row["prd_id"]?>" />
                    <input type="hidden" name="prd_price" value="<?=$row["prd_price"]?>" />
                    <input type="hidden" name="prd_name" value="<?=$row["prd_name"]?>" />
                    <button class="btn btn-danger" name="btnAddToCart" value="addItem" type="submit">加入購物車</button>
                  </form>
                </div>
                <div class="col">
                  <a href="#" class="btn btn-primary">詳細資訊</a>
                </div>
              </div>
            </div>
          </div>
        </div>

        <?php $itemPageCount += 1;
        if ($itemPageCount >= 3) {?>

      </div>
      <!--end of row -->
      <div class="row">

        <?php
        $itemPageCount = 0;
        }?>



        <?php }?>
        <?php if ($itemPageCount < 3) {echo "</div><!--end of row -->";}?>



        <?php } else { //不進行篩選的商品清單
    $itemPageCount = 0;

    $sqlStatement = <<<mulity
    select prd_name, prd_price, prd_images, prd_description, prd_id
    from tbl_product;
    mulity;
    $result = mysqli_query($link, $sqlStatement);
    ?>
        <div class="row">
          <?php while ($row = mysqli_fetch_assoc($result)) {?>

          <div class="col-sm-4">
            <div class="card" style="width: 18rem;">
              <img class="card-img-top" src="<?=$row["prd_images"]?>" alt="無法顯示圖片">
              <div class="card-body">
                <h6 class="card-subtitle mb-2 text-muted"><?=$row["prd_name"]?></h6>
                <p class="card-text price"><?="$" . $row["prd_price"]?></p>
                <div class="row">
                  <div class="col">
                    <form action="" method="post">
                      <input type="hidden" name="prd_id" value="<?=$row["prd_id"]?>" />
                      <input type="hidden" name="prd_price" value="<?=$row["prd_price"]?>" />
                      <input type="hidden" name="prd_name" value="<?=$row["prd_name"]?>" />
                      <button class="btn btn-danger" name="btnAddToCart" value="addItem" type="submit">加入購物車</button>
                    </form>
                  </div>
                  <div class="col">
                    <a href="#" class="btn btn-primary">詳細資訊</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <?php $itemPageCount += 1;
        if ($itemPageCount >= 3) {?>
        </div>
        <!--end of row -->
        <div class="row">
          <?php
        $itemPageCount = 0;
        }?>

          <?php }?>
          <?php if ($itemPageCount < 3) {echo "</div><!--end of row -->";}?>


          <?php }?>
        </div>

</body>






</html>