
<?php
require_once "../sql/onnDB.php";
session_start();

function processFile($objFile) {
	if ($objFile ["error"] != 0) {
		echo "圖片上傳失敗! ";
		echo "<a href='javascript:window.history.back();'>Back</a>";
		return;
	}
	
	$imgresult = move_uploaded_file ( $objFile ["tmp_name"], "../img/" . $objFile ["name"] );
	if (! $imgresult) {
		die ( "圖片上傳失敗!" );
	}

}



//抓取MySQL資料庫中的商品分類資料。
$sqlStatement = <<<categorysql
 select ca_name 
 from tbl_category;
categorysql;
$catResult = mysqli_query($link, $sqlStatement);

/*
 *使用者按下「取消」按鈕
 */
if(isset($_POST["btnCancel"])){
	header("location: commodity_admin.php");
	exit();
  }



/*
 *使用者按下「新增」按鈕
 */
if (isset($_POST["btnConfirm"])) {

	processFile ( $_FILES ["inputImage"] );
	$addingProductName = $_POST["inputName"];
	$addingProductPrice = $_POST["inputPrice"];
	$addingProductCategory = $_POST["inputCategory"];
	$addPicturePath = "/PID_Assignment/img/".$_FILES["inputImage"]["name"];



	//根據所輸入的分類名稱從MySQL資料庫抓取分類的編號。如果資料庫回傳結果數量為0的話建立一個新商品類別再抓取編號。
	$sqlStatement = <<<sql
	select ca_id
	from tbl_category
	where ca_name = '{$addingProductCategory}';
	sql;
	$result = mysqli_query($link, $sqlStatement);

	if(mysqli_num_rows($result) == 0){
		$sqlStatement = <<<sql
		INSERT INTO `tbl_category` (`ca_name`)
		VALUES
		('{$addingProductCategory}')
		sql;
		mysqli_query($link, $sqlStatement) or die("新增分類失敗");

		$sqlStatement = <<<sql
		select ca_id
		from tbl_category
		where ca_name = '{$addingProductCategory}';
		sql;
		$result = mysqli_query($link, $sqlStatement);
	}
	$row = mysqli_fetch_assoc($result);
	$caId = $row["ca_id"];





	//新增商品至資料庫
	$sqlStatement =<<<sql
	INSERT INTO `tbl_product` (`ca_id`,`prd_name`,`prd_price`,`prd_images`)
	VALUES
	({$caId},'{$addingProductName}',{$addingProductPrice},'{$addPicturePath}');
	sql;
	mysqli_query($link, $sqlStatement) or die("新增失敗");

	$_SESSION["msgStatus"] = 7;//新增商品成功，進入訊息頁面會顯示成功提示。
	header("Location:/PID_Assignment/status.php");
	exit();

	
	
}



?>


<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>新增商品</title>
	<!-- Bootstrap core CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
		integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.8/css/all.css">


	<!-- Custom styles for this template -->
	<!-- <link rel="stylesheet" type="text/css" href="/RD5_Assignment/CSS/grid.css"> -->

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


</head>

<body>
	
		<div class="container">
			<br>



			<div class="row justify-content-center">
				<div class="col-md-6">
					<div class="card">
						<header class="card-header">
							<div class="row" style="position: relative;">
								<div class="col-6"><h4 class="card-title mt-2">新增商品</h4></div>
							</div>
						</header>
						<article class="card-body">
							<form id="addingProductForm" name="addingProductForm" method="post" action="" enctype="multipart/form-data" onSubmit="return InputCheck(this)">
								<div class="form-row">
									<div class="col-6 form-group">
										<label>商品名稱</label>
										<input type="text" id="inputName" name="inputName" class="form-control" placeholder="" required>
									</div> <!-- form-group end.// -->
									<div class="col-6 form-group">
									    <div class="c-zt-pic">
   									      <img class="card-img-top" id="preview" src="" alt="沒有圖片">
									    </div>	

									</div> <!-- form-group end.// -->

								</div> <!-- form-row end.// -->
								<div class="form-row">
								  <div class="col-6 form-group">
										<label for=" inputPrice" class="col-form-label">商品單價</label>
										<input type="number" class="form-control" id="inputPrice" name="inputPrice" min="1" placeholder="" required>
								  </div> <!-- form-group end.// -->
								  <div class="col-6 form-group">
									<label for="inputCategory" class="col-form-label">商品分類</label>
									<input type="text" class="form-control" id="inputCategory" name="inputCategory" placeholder="" required>
									<div class="col-8 float-right">
								      <select id="selectCategory" name="selectCategory" class="col form-control" required>
									    <?php $row = mysqli_fetch_assoc($catResult);//忽略「全部產品」這個分類
									    while ($row = mysqli_fetch_assoc($catResult)) { //將抓取出來的分類名稱資料轉換成下拉式選單標籤?>
                                        <option value="<?=$row["ca_name"]?>"> 
										  <?=$row["ca_name"]?>
                                        </option>
                                        <?php }?>
                                      </select>
								    </div>
								  </div> <!-- form-group end.// -->
								</div> <!-- form-row end.// -->

								
								
								<div class="form-row">
								<div class="col form-group">
								<!-- <form action="register.php" method="post" > -->
								<div style="margin-bottom: 20px;">
  									      <input type="file" name="inputImage" id="upload" class="btn btn-edc" value="新增圖片">
  								</div>
								<!-- </form> -->

								</div> <!-- form-group end.// -->
								</div> <!-- form-row end.// -->




							
								<div class="form-group text-right">
								<button type="submit" class="btn btn-outline-success" name="btnConfirm" id="btnConfirm"
									value="btnConfirm"> 確認 </button>
								<button type="submit" class="btn btn-outline-secondary cancel" name="btnCancel" id="btnCancel"
									value="btnCancel"  formnovalidate> 取消 </button>
							</div> <!-- form-group// -->
							

							</form>
						</article> <!-- card-body end .// -->
					</div> <!-- card.// -->
				</div> <!-- col.//-->

			</div> <!-- row.//-->


		</div>
		<!--container end.//-->

		
	
</body>

<script>
$(function() {

//預覽上傳圖片
	   $('#upload').change(function() {
		   var f = document.getElementById('upload').files[0];
		   var src = window.URL.createObjectURL(f);
		   document.getElementById('preview').src = src;
	   });
});


$('select').on('change', function() {
  let a = this.value ;
  $('input[name=inputCategory]').val(a);

});


</script>

</html>