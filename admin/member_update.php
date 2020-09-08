<?php
session_start();

if ($_SESSION["level"]!=999 || !isset($_SESSION["uId"])) {
	$_SESSION["msgStatus"] = 11;//權限非管理員，進入訊息頁面會顯示權限不足提示。
	header("Location:/PID_Assignment/status.php");
	exit();
  }



if(!isset($_GET["id"])){
	die("id not found.");
}

$id = $_GET["id"];
if(!is_numeric($id)){
	die("id not a number.");
}

require_once("../sql/onnDB.php");


/*
 *使用者按下「取消」按鈕
 */
if(isset($_POST["btnCancel"])){
	header("location: member_admin.php");
	exit();
  }
  




require_once "../sql/onnDB.php";
$sql = "select * from tbl_users where m_id = '{$id}' ;";
$result = mysqli_query($link, $sql);
$rows = mysqli_fetch_assoc($result);  






/*
 *使用者按下「確認」按鈕
 */
if (isset($_POST["btnConfirm"])) {

	//接收使用者輸入的POST表單。
	$modName = $_POST["inputName"];
	$modPassword = hash('sha256', $_POST["inputPassword"]);
	$modEmail = $_POST["inputEmail"];
	$modGender = $_POST["radioGender"];
	$modPhoneNumber = $_POST["inputPhoneNumber"];
	$modBirthday = $_POST["inputBirthday"];
	$modAuthority = $_POST["inputAuthority"];

	if($_POST["inputPassword"]==""){//使用者沒有設置新密碼。
		$sqlSTMT =<<<sqlSTMT
		UPDATE tbl_users
		SET m_name = '{$modName}',
			m_email = '{$modEmail}',
			m_gender = '{$modGender}',
			m_phone = '{$modPhoneNumber}',
			m_birthday = '{$modBirthday}',
			m_level = '{$modAuthority}'
		WHERE m_id = '{$id}'; 
		sqlSTMT;
	}else{                         //如果使用者有設置新密碼的話，更新使用者所輸入包刮新密碼的資料到資料庫。
		$sqlSTMT =<<<sqlSTMT
		UPDATE tbl_users
		SET m_name = '{$modName}',
			m_password = '{$modPassword}',
			m_email = '{$modEmail}',
			m_gender = '{$modGender}',
			m_phone = '{$modPhoneNumber}',
			m_birthday = '{$modBirthday}',
			m_level = '{$modAuthority}'
		WHERE m_id = '{$id}'; 
		sqlSTMT;
	}
	
	mysqli_query($link, $sqlSTMT) or die(mysqli_error($link));
	mysqli_close($link);
	$_SESSION["msgStatus"] = 10;//修改會員成功，進入訊息頁面會顯示成功提示。
	header("Location:/PID_Assignment/status.php");
	exit();


}
mysqli_close($link);


?>


<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>編輯個人資料</title>

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.8/css/all.css">
	<link rel="stylesheet" href="\PID_Assignment\css\bootstrap.min.css">
  	<link rel="stylesheet" href="\PID_Assignment\css\store_index.css">
  	<script src="\PID_Assignment\js\jquery.min.js"></script>
  	<script src="\PID_Assignment\js\bootstrap.min.js"></script>
  	<script src="\PID_Assignment\js\jquery.mycart.js"></script>
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.8/css/all.css">



</head>

<body>

	<div class="container">
		<br>



		<div class="row justify-content-center">
			<div class="col-md-6">
				<div class="card">
					<header class="card-header">
						<div class="row" style="position: relative;">
							<div class="col-6">
								<h4 class="card-title mt-2">編輯會員資料</h4>
							</div>
						</div>
					</header>
					<article class="card-body">
						<form id="registerForm" name="registerForm" method="post" action="">
							<div class="form-row">
								<div class="col form-group">
									<label>姓名 </label>
									<input type="text" id="inputName" name="inputName" class="form-control"
										value="<?=$rows["m_name"]?>">
								</div> <!-- form-group end.// -->
							</div> <!-- form-row end.// -->
							<div class="form-row">
								<div class="col form-group">
									<label>設置新密碼</label>
									<input type="password" id="inputPassword" name="inputPassword" class="form-control"
										placeholder="請輸入英文或數字">
								</div> <!-- form-group end.// -->
								<div class="col form-group">
									<label>電子郵件</label>
									<input type="email" id="inputEmail" name="inputEmail" class="form-control"
										placeholder="" value="<?=$rows["m_email"]?>">
								</div> <!-- form-group end.// -->
							</div> <!-- form-row end.// -->

							<div class="form-row">
								<div class="col-5 form-group">
									<label>性別&nbsp;&nbsp;&nbsp;</label>

									<label class="form-check form-check-inline">
										<input class="form-check-input" type="radio" name="radioGender" value="男"
											<?php if($rows["m_gender"] == "男"){ echo "checked";}?>>
										<span class="form-check-label"> 男 </span>
									</label>
									<label class="form-check form-check-inline">
										<input class="form-check-input" type="radio" name="radioGender" value="女"
											<?php if($rows["m_gender"] == "女"){ echo "checked";}?>>
										<span class="form-check-label"> 女</span>
									</label>
								</div> <!-- form-group end.// -->
								<div class="col form-group">
								  <div class="form-row">
									<div class="col-2"></div>
									<label for=" inputAuthority" class="col-2 col-form-label">權限</label>
									<div class="col-8">
										<select id=" inputAuthority" name=" inputAuthority" class="form-control"
											required="required">
											<option value="member"
											  <?php if($rows["m_level"] == "member"){ echo "selected";}?>>一般會員</option>
											<option value="admin"
											  <?php if($rows["m_level"] == "admin"){ echo "selected";}?>>管理員</option>
											<option value="readonly"
											  <?php if($rows["m_level"] == "readonly"){ echo "selected";}?>>禁止購物</option>
											<option value="suspension"
											  <?php if($rows["m_level"] == "suspension"){ echo "selected";}?>>停權中</option>
										</select>
									</div>
							      </div>
								</div> <!-- form-group end.// -->
							</div> <!-- form-row end.// -->


							<div class="form-row">
								<div class="col form-group">
									<label>電話</label>
									<input type="tel" class="form-control" id="inputPhoneNumber" name="inputPhoneNumber"
										required pattern="[0-9]{9,10}" value="<?=$rows["m_phone"]?>">
								</div> <!-- form-group end.// -->
								<div class="col form-group">
									<label>生日</label>
									<input class="form-control" type="date" id="inputBirthday" name="inputBirthday"
										value="<?=$rows["m_birthday"]?>">
								</div> <!-- form-group end.// -->
							</div> <!-- form-row end.// -->

							<div class="form-group text-right">
								<button type="submit" class="btn btn-outline-success" name="btnConfirm" id="btnConfirm"
									value="btnConfirm"> 確認 </button>
								<button type="submit" class="btn btn-outline-secondary" name="btnCancel" id="btnCancel"
									value="btnCancel"> 取消 </button>
							</div> <!-- form-group// -->

						</form>
					</article> <!-- card-body end .// -->
				</div> <!-- card.// -->
			</div> <!-- col.//-->

		</div> <!-- row.//-->


	</div>
	<!--container end.//-->

</body>

</html>