<?php
require_once "./sql/onnDB.php";

$id = "";
if (!isset($_GET["id"])) {
	die("no id found.");
}
$id = $_GET["id"];

$sqlStatement = "SELECT * FROM `tbl_product` WHERE prd_id = {$id}";
$productResult = mysqli_query($link, $sqlStatement);

$productDetail = "";
$row = mysqli_fetch_assoc($productResult);
$productDetail = sprintf('

<div class="container">
    <div class="card align-items-center" style="width: 15rem; margin:0 auto;">
        <img class="card-img-top mx-auto d-block" style="width: 15vw; height: 15vw;" src="%s" alt="沒有圖片"> <br/>
    </div>
    <p>%s</p><br/>
    <p class="price">價格：$%s</p><br/>
    <p>商品介紹：%s</p><br/>
</div>
                         ', $row["prd_images"],$row["prd_name"],number_format($row["prd_price"]),$row["prd_description"]);

echo $productDetail;
?>