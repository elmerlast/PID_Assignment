<!DOCTYPE html>
<?php
include "wfCart/wfcart.php";
require_once "./sql/onnDB.php";
session_start();

$cart = &$_SESSION['wfcart']; // 指向購物車物件
if (!is_object($cart)) {
    $cart = new wfCart();
}



if (isset($_POST["btnSearch"])) {
  $_POST["Categories"] = "全部產品";
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

</head>

<body>

  <nav class="navbar navbar-expand-sm bg-dark navbar-dark sticky-top">
    <!-- Brand/logo -->
    <a class="navbar-brand" href="#">CC音饗</a>

    <!-- Links -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link" href="/PID_Assignment/index.php"><span class="fa fa-home"></span>首頁</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/PID_Assignment/member/login.php"><span class="fa fa-user"></span> 登入</a>
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
    <table class="table table-hover">
      <thead class="thead-light">
        <tr>
          <th colspan="4">
            <form action="" method="post">
              <div class="input-group ">
                <input id="inputProductName" name="inputProductName" type="text" class="form-control"
                  placeholder="請輸入商品名稱" value="<?php if(isset($_POST["inputProductName"])){echo"{$_POST["inputProductName"]}";} ?>">
                <div class="input-group-append">
                  <button class="btn btn-primary" type="submit" name="btnSearch" id="btnSearch"
                    value="btnSearch">搜尋</button>
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
                  <?php while ($row = mysqli_fetch_assoc($catResult)) { //將抓取出來的分類名稱資料轉換成下拉式選單標籤?>
                  <option value="<?=$row["ca_name"]?>"
                    <?php if ($row["ca_name"] == $_POST["Categories"]) {echo "selected";}?>> <?=$row["ca_name"]?>
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
      <?php if (!isset($_POST["inputProductName"]) && ($_POST["Categories"] != "全部產品") && (isset($_POST["Categories"])) ) { //使用者設定分類進行篩選的話：

    $sqlStatement = <<<categoryProductSql
      select prd_name, prd_price, prd_images, prd_description, ca_name, prd_id
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
                      <input type="hidden" name="Categories" value="<?=$_POST["Categories"]?>" />
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
      <div class="row">

        <?php
        $itemPageCount = 0;
        }?>



        <?php }?>
        <?php if ($itemPageCount < 3) {echo "</div><!--end of row -->";}?>




        <?php } else if(!isset($_POST["inputProductName"])) { //不進行篩選且沒有輸入搜尋資訊的話，列出所有商品
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
                  <form class="detail" action="index.php" method="post">
                      <input type="hidden" id="prd_id" value="<?=$row["prd_id"]?>" />
                      <input type="hidden" id="prd_price" value="<?=$row["prd_price"]?>" />
                      <input type="hidden" id="prd_name" value="<?=$row["prd_name"]?>" />
                      <input type="hidden" id="prd_description" value="<?=$row["prd_description"]?>" />
                      <input class="btn btn-primary" name="btnDetail" value="詳細資訊" type="submit"
                        data-toggle="modal" data-target="#detailModal"></button>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <?php $itemPageCount += 1;
        if ($itemPageCount >= 3) {?>
        </div>
        <div class="row">
          <?php
        $itemPageCount = 0;
        }?>

          <?php }?>
          <?php if ($itemPageCount < 3) {echo "</div><!--end of row -->";}  ?>
          

          <?php }else{ // 商品的搜尋結果

          $itemPageCount = 0;

          $sqlStatement = <<<searchsql
          select prd_name, prd_price, prd_images, prd_description, prd_id 
          from tbl_product 
          where prd_name like '%{$_POST["inputProductName"]}%';
          searchsql;
          $searchResult = mysqli_query($link, $sqlStatement);
          ?>

          <div class="row">
            <?php while ($row = mysqli_fetch_assoc($searchResult)) {?>

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
                        <input type="hidden" name="inputProductName" value="<?=$_POST["inputProductName"]?>" />
                        <input type="hidden" name="Categories" value="全部產品" />
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
          <div class="row">
            <?php
        $itemPageCount = 0;
        }?>

            <?php } //end of while
      } //end of else
          ?>
            <?php if ($itemPageCount < 3) {echo "</div><!--end of row -->";}?>

          </div>

          <div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="detailModalLabel">Modal title</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <p>ddds</p>
                  <?php var_dump($_POST); ?>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary">Save changes</button>
                </div>
              </div>
            </div>
          </div>



</body>
<script>


$( document ).ready(function() {
  $(".detail").submit(function(e) {

var form = $(this);
var url = form.attr('action');

$.ajax({
           type: "post",
           url: url,
           data: form.serialize(), // serializes the form's elements.
           dataType : 'json', // changing data type to json
           success: function(data)
           {
            alert("here");
            alert(data);
           }
         });
e.preventDefault(); // avoid to execute the actual submit of the form.
});
});




</script>





</html>