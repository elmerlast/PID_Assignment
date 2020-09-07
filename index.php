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

if(isset($_GET["signout"])){
  unset($_SESSION["uId"]);
  $_SESSION["msgStatus"] = 3;//己登出，進入訊息頁面會顯示登出提示。
  header("Location:/PID_Assignment/status.php");
  exit();
  

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
    <a class="navbar-brand" href="/PID_Assignment/index.php">CC音饗</a>

    <!-- Links -->
    <ul class="navbar-nav ml-auto">
    <?php if(isset($_SESSION["uId"])){?>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <?php echo "{$_SESSION["uId"]}";?>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href="/PID_Assignment/member/order_list.php">我的訂單</a>
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
              <img class="card-img-top" src="<?=$row["prd_images"]?>" alt="無法顯示圖片">
              <div class="card-body">
                <h6 class="card-subtitle mb-2 text-muted"><?=$row["prd_name"]?></h6>
                <p class="card-text price"><?="$" . number_format($row["prd_price"])?></p>
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
                      <button type="button" id="btnDetail" class="btn btn-primary btndetail" value="<?=$row["prd_id"]?>" data-toggle="modal" data-target="#detailModal">
                        詳細資訊
                      </button>
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
                <p class="card-text price"><?="$" . number_format($row["prd_price"])?></p>
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
                      <button type="button" id="btnDetail" class="btn btn-primary btndetail" value="<?=$row["prd_id"]?>" data-toggle="modal" data-target="#detailModal">
                        詳細資訊
                      </button>
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
                <p class="card-text price"><?="$" . number_format($row["prd_price"])?></p>
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
                      <button type="button" id="btnDetail" class="btn btn-primary btndetail" value="<?=$row["prd_id"]?>" data-toggle="modal" data-target="#detailModal">
                        詳細資訊
                      </button>
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
                  <h5 class="modal-title" id="detailModalLabel">詳細資訊</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <p id="detext">modal</p>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">關閉</button>
                </div>
              </div>
            </div>
          </div>

 



</body>

<script type="text/javascript">

$(document).ready(init);


function init() {
	$(".btndetail").click(itemDetail);
}

function itemDetail() {
	var s = $(this).val();
	$.get('itemDetail.php?id=' + s, detailBack)
}

function detailBack(data) {
	$(".modal-body").html(data);
}

</script>

</html>