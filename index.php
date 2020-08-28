<!DOCTYPE html>
<?php 
require_once("./sql/onnDB.php");
$sqlStatement =<<<mulity
 select prd_name, prd_price, prd_images, prd_description
 from tbl_product;
mulity;
$result =mysqli_query($link, $sqlStatement);

$sqlStatement =<<<categorysql
 select ca_name
 from tbl_category;
categorysql;
$catResult =mysqli_query($link, $sqlStatement);


?>



<!DOCTYPE html>
<html>

<head>
  <title>CC購物</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <style>
    .tables {
      width: 100%;
      height: 100%;
      table-layout: fixed;
    }

    .td_overflow {
      max-width: 140px;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
    }
  </style>


</head>

<body>




  <div class="container">
    <h2>會員列表</h2>
    <table class="table table-hover tables">
      <thead class="thead-light">
        <tr>
          <th>商品名稱</th>
          <th>商品價格</th>
          <th>商品圖片</th>
          <th>商品敘述</th>
        </tr>
        <tr>
          <th colspan="4">
            <form>
              <div class="form-group row">
                <label for="text" class="col-2 col-form-label">搜尋商品：</label>
                <div class="col-4">
                  <input id="text" name="text" type="text" class="form-control">
                </div>
                <div class="col-1"><button name="submit" type="submit" class="btn btn-primary">Go</button></div>

            </form>

          </th>
        </tr>
        <tr>
          <th colspan="4">
          <form>
              <div class="form-group row">
                <label class="col-2" for="Categories" >商品分類：</label>
                <select id="Categories" name="Categories" class="col-4 form-control">
                  <?php while ($row = mysqli_fetch_assoc($catResult)){?>
                  <option value="<?= $row["ca_name"] ?>"><?= $row["ca_name"] ?></option>
                  <?php } ?>
                </select>
                <button name="btnCatFilter" id="btnCatFilter" type="submit" class="btn btn-primary" value="btnCatFilter">篩選</button>
              </div>
            </form>

          </th>
        </tr>
      </thead>
      <tbody>
        <?php if(isset($_POST["btnCatFilter"])&&($_POST["cid"!==""])) { //使用者設定分類進行篩選的話：

          $sqlStatement =<<<categorysql
          select prd_name, prd_price, prd_images, prd_description, ca_name
          from tbl_category
          left join tbl_product
          on tbl_category.ca_id = tbl_product.ca_id
          where ca_name = '{$_POST["Categories"]}';
          categorysql;
          $result =mysqli_query($link, $sqlStatement);

        ?>
        <?php while ($row = mysqli_fetch_assoc($result)){?>
        <tr>
          <td><?= $row["prd_name"] ?></td>
          <td><?= $row["prd_price"] ?></td>
          <td class="td_overflow" title="<?= $row["prd_images"] ?>"><?= $row["prd_images"] ?></td>
          <td class="td_overflow" title="<?= $row["prd_description"] ?>"><?= $row["prd_description"] ?></td>
        </tr>
        <?php } ?>
        <?php }else{ ?>

          <?php while ($row = mysqli_fetch_assoc($catResult)){?>
        <tr>
          <td><?= $row["prd_name"] ?></td>
          <td><?= $row["prd_price"] ?></td>
          <td class="td_overflow" title="<?= $row["prd_images"] ?>"><?= $row["prd_images"] ?></td>
          <td class="td_overflow" title="<?= $row["prd_description"] ?>"><?= $row["prd_description"] ?></td>
        </tr>
        <?php } ?>


        <?php } ?>

      </tbody>
    </table>
  </div>

</body>

</html>