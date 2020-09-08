<!DOCTYPE html>
<?php
include "../wfCart/wfcart.php";
session_start();

if ($_SESSION["level"]!=999 || !isset($_SESSION["uId"])) {
	$_SESSION["msgStatus"] = 11;//權限非管理員，進入訊息頁面會顯示權限不足提示。
	header("Location:/PID_Assignment/status.php");
	exit();
  }

require_once("../sql/onnDB.php");

$cart = &$_SESSION['wfcart']; // new一個購物車物件
if (!is_object($cart)) {
    $cart = new wfCart();
}



if (isset($_POST["btnDeleteCat"])) {
  //確認資料庫是否存在資料，且如果不是最新的資料刪除全部的資料表紀錄
  $sql = <<<STMT
  SELECT p.prd_name 
  FROM `tbl_category` as c 
  INNER JOIN `tbl_product` as p 
  ON p.ca_id = c.ca_id 
  where ca_name = '{$_POST["Categories"]}' 
  STMT;

  $result = mysqli_query($link, $sql);
  $nums = mysqli_num_rows($result);
  if($_POST["Categories"] =="全部產品"){
    $deleteError = 1;
  }elseif($nums == 0){
    $sql = <<<STMT
    DELETE FROM tbl_category
    WHERE ca_name = '{$_POST["Categories"]}';
    STMT;
    mysqli_query($link, $sql) or die("刪除分類失敗");
    $_POST["Categories"] = "全部產品";
  }else{
    $deleteError = 2;
  }
  
}

if (isset($_POST["btnSearch"])) {
  $_POST["Categories"] = "全部產品";
}




if(isset($_GET["signout"])){
  $_SESSION["uId"] = null;
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
  <title>CC音饗管理系統-商品管理</title>
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
    <a class="navbar-brand" >CC音饗管理系統</a>

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
    <h1>商品管理</h1>
    <table class="table table-hover">
  <thead class="thead-light">
    <tr>
        <th colspan="5">
            <form action="" method="post">
              <div class="input-group ">
                <input id="inputProductName" name="inputProductName" type="text" class="form-control"
                  placeholder="請輸入商品" value="<?php if(isset($_POST["inputProductName"])){echo"{$_POST["inputProductName"]}";} ?>">
                <div class="input-group-append">
                  <button class="btn btn-primary" type="submit" name="btnSearch" id="btnSearch"
                   value="btnSearch">搜尋</button>
                </div>
            </form>
          </th>
    </tr>
    <tr>
          <th colspan="5">
            <form method="post" action="" >
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
                <button name="btnDeleteCat" id="btnDeleteCat" type="submit" class="btn btn-danger" value="btnDeleteCat"
                  value="btnCatFilter">刪除分類標籤</button>
              </div>
              <?php
                switch ($deleteError)
                {
                  case 1:
                    echo('<label style="color: red;" >&emsp;※無效操作，您不可刪除此分類標籤。</label>');
                  break;  
                  case 2:
                    echo('<label style="color: red;" >&emsp;※尚有此分類商品存在，請先將該分類商品刪除再進行刪除動作。</label>');
                  break;
                }  
              ?>
            </form>


          </th>
        </tr>
    <tr>
      <th scope="col">編號</th>
      <th scope="col">分類</th>
      <th scope="col">名稱</th>
      <th scope="col">單價</th>
      <th>
        <a href ="./commodity_adding.php" class="btn btn btn-outline-success btn-sm"><i class="fa fa-plus "></i></a>
      </th>
    </tr>
    </thead>
    <tbody>
    <?php 
    if (!isset($_POST["inputProductName"]) && ($_POST["Categories"] != "全部產品") && (isset($_POST["Categories"])) ) { //使用者設定分類進行篩選的話：
    $sqlStatement = <<<categoryProductSql
    select prd_name, prd_price, prd_description, ca_name, prd_id
    from tbl_category
    inner join tbl_product
    on tbl_category.ca_id = tbl_product.ca_id
    where ca_name = '{$_POST["Categories"]}';
    categoryProductSql;
    $catProductResult = mysqli_query($link, $sqlStatement);
    mysqli_close($link);

    if(mysqli_num_rows($catProductResult) > 0){ 
      while ($row = mysqli_fetch_assoc($catProductResult)) { ?>
        <tr>
             <th scope="row"><?=$row["prd_id"] ?></th>
             <td><?=$row["ca_name"]?></td>
             <td><?=$row["prd_name"]?></td>
             <td><?="$".number_format($row["prd_price"])?></td>
             <td>
               <a href ="./commodity_update.php?id=<?= $row["prd_id"]?>" class="btn btn btn-success btn-sm"><i class="fa fa-pencil-square-o "></i></a>
               <a href ="./commodity_delete.php?id=<?= $row["prd_id"]?>" class="btn btn btn-danger btn-sm"><i class="fa fa-times "></i></a>
             </td>
        </tr>  
    <?php }//end of while?>
      <?php }else{ ?>
        <tr>
          <th colspan="5">
            <h5 style="text-align:center;"> 該分類目前沒有任何商品。</h5>
          </th>
        </tr>   
      <?php }//end of if  ?>
    <?php } else if(!isset($_POST["inputProductName"])) { //不進行篩選且沒有輸入搜尋資訊的話，列出所有商品 
    $sqlStatement = <<<mulity
    select prd_name, prd_price, prd_description, ca_name, prd_id
    from tbl_category
    inner join tbl_product
    on tbl_category.ca_id = tbl_product.ca_id
    mulity;
    $result = mysqli_query($link, $sqlStatement);
    mysqli_close($link);

      if(mysqli_num_rows($result) > 0){ 
        while ($row = mysqli_fetch_assoc($result)) { ?>
          <tr>
             <th scope="row"><?=$row["prd_id"] ?></th>
             <td><?=$row["ca_name"]?></td>
             <td><?=$row["prd_name"]?></td>
             <td><?="$".number_format($row["prd_price"])?></td>
             <td>
               <a href ="./commodity_update.php?id=<?= $row["prd_id"]?>" class="btn btn btn-success btn-sm"><i class="fa fa-pencil-square-o "></i></a>
               <a href ="./commodity_delete.php?id=<?= $row["prd_id"]?>" class="btn btn btn-danger btn-sm"><i class="fa fa-times "></i></a>
             </td>
        </tr>  
    <?php }//end of while?>
      <?php }else{ ?>
        <tr>
          <th colspan="5">
            <h5 style="text-align:center;"> 商店目前沒有任何商品。</h5>
          </th>
        </tr>  
      <?php }//end of if  ?>
    <?php }else{ // 商品的搜尋結果
          $sqlStatement = <<<searchsql
          select prd_name, prd_price, prd_description, ca_name, prd_id
          from tbl_category
          inner join tbl_product
          on tbl_category.ca_id = tbl_product.ca_id
          where tbl_product.prd_name like '%{$_POST["inputProductName"]}%';
          searchsql;
          $searchResult = mysqli_query($link, $sqlStatement);
          mysqli_close($link);

          if(mysqli_num_rows($searchResult) > 0){ 
            while ($row = mysqli_fetch_assoc($searchResult)) { ?>
                <tr>
                 <th scope="row"><?=$row["prd_id"] ?></th>
                 <td><?=$row["ca_name"]?></td>
                 <td><?=$row["prd_name"]?></td>
                 <td><?="$".number_format($row["prd_price"])?></td>
                 <td>
                   <a href ="./commodity_update.php?id=<?= $row["prd_id"]?>" class="btn btn btn-success btn-sm"><i class="fa fa-pencil-square-o "></i></a>
                   <a href ="./commodity_delete.php?id=<?= $row["prd_id"]?>" class="btn btn btn-danger btn-sm"><i class="fa fa-times "></i></a>
                 </td>
            </tr>  
            <?php }//end of while?>
      <?php }else{ ?>
        <tr>
          <th colspan="5">
            <h5 style="text-align:center;">查詢不到此商品</h5>
          </th>
        </tr>  
      <?php }//end of if  
         } //end of if?>






















  </tbody>
 </table>
</div>



</body>



</html>