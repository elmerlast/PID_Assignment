<!DOCTYPE html>
<?php
include "../wfCart/wfcart.php";
require_once "../sql/onnDB.php";
session_start();

$cart = &$_SESSION['wfcart']; // new一個購物車物件
if (!is_object($cart)) {
    $cart = new wfCart();
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
    <a class="navbar-brand" href="/PID_Assignment/index.php">CC音饗管理系統</a>

    <!-- Links -->
    <ul class="navbar-nav ml-auto">
    <?php if(isset($_SESSION["uId"])){?>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <?php echo "{$_SESSION["uId"]}";?>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href="/PID_Assignment/member/order_list.php">訂單管理</a>
          <a class="dropdown-item" href="/PID_Assignment/member/editor.php">會員管理</a>
          <a class="dropdown-item" href="/PID_Assignment/member/editor.php">商品管理</a>

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
    <tr>
      <th scope="col">編號</th>
      <th scope="col">分類</th>
      <th scope="col">名稱</th>
      <th scope="col">單價</th>
      <th>
        <a href ="./member_adding.php" class="btn btn btn-outline-success btn-sm"><i class="fa fa-plus "></i></a>
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

    if(mysqli_num_rows($catProductResult) > 0){ 
      while ($row = mysqli_fetch_assoc($catProductResult)) { ?>
        <tr>
             <th scope="row"><?=$row["prd_id"] ?></th>
             <td><?=$row["ca_name"]?></td>
             <td><?=$row["prd_name"]?></td>
             <td><?=$row["prd_price"]?></td>
             <td>
               <a href ="./order_details.php?id=<?= $row["ord_id"]?>" class="btn btn btn-success btn-sm"><i class="fa fa-pencil-square-o "></i></a>
               <a href ="./delete_order.php?id=<?= $row["ord_id"]?>" class="btn btn btn-danger btn-sm"><i class="fa fa-times "></i></a>
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

      if(mysqli_num_rows($result) > 0){ 
        while ($row = mysqli_fetch_assoc($result)) { ?>
          <tr>
             <th scope="row"><?=$row["prd_id"] ?></th>
             <td><?=$row["ca_name"]?></td>
             <td><?=$row["prd_name"]?></td>
             <td><?=$row["prd_price"]?></td>
             <td>
               <a href ="./order_details.php?id=<?= $row["ord_id"]?>" class="btn btn btn-success btn-sm"><i class="fa fa-pencil-square-o "></i></a>
               <a href ="./delete_order.php?id=<?= $row["ord_id"]?>" class="btn btn btn-danger btn-sm"><i class="fa fa-times "></i></a>
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

          if(mysqli_num_rows($searchResult) > 0){ 
            while ($row = mysqli_fetch_assoc($searchResult)) { ?>
                <tr>
                 <th scope="row"><?=$row["prd_id"] ?></th>
                 <td><?=$row["ca_name"]?></td>
                 <td><?=$row["prd_name"]?></td>
                 <td><?=$row["prd_price"]?></td>
                 <td>
                   <a href ="./order_details.php?id=<?= $row["ord_id"]?>" class="btn btn btn-success btn-sm"><i class="fa fa-pencil-square-o "></i></a>
                   <a href ="./delete_order.php?id=<?= $row["ord_id"]?>" class="btn btn btn-danger btn-sm"><i class="fa fa-times "></i></a>
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