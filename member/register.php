
<?php
session_start();
/*
 *使用者按下「註冊」按鈕
 */
if (isset($_POST["btnRegister"])) {

	//檢查使用者輸入的使用者名稱是否已被註冊。
	require_once "../sql/onnDB.php";
	$sql = "select m_username from tbl_users;";
	$result = mysqli_query($link, $sql);
	while($row = mysqli_fetch_row($result)){
		$rows[]=$row;
	}
	$rows[] =array("{$_POST["inputUserName"]}");
	$rows1d = array_column($rows,0);//將2維陣列降維成1維陣列。
	if (count($rows1d) != count(array_unique($rows1d))) {
		$_POST["userError"] = "error";
	}else{
		//新增使用者帳號
		$addName = $_POST["inputName"];
		$addUserName = $_POST["inputUserName"];
		$addPassword = hash('sha256', $_POST["inputPassword"]);
		$addEmail = $_POST["inputEmail"];
		$addGender = $_POST["radioGender"];
		$addPhoneNumber = $_POST["inputPhoneNumber"];
		$bdate = gmdate('Y-m-d H:i:s', time() + 3600 * 8);
		$addBirthday = $_POST["inputBirthday"];
		$sqlSTMT =<<<sqlSTMT
		INSERT INTO `tbl_users` (`m_name`, `m_username`, `m_password`, `m_gender`, `m_email`, `m_phone`, `m_jointime`,`m_birthday`)
		VALUES
		('{$addName}',
		 '{$addUserName}',
		 '{$addPassword}',
		 '{$addGender}',
		 '{$addEmail}',
		 '{$addPhoneNumber}',
		 '{$bdate}',
		 '{$addBirthday}');
		sqlSTMT;
		mysqli_query($link, $sqlSTMT) or die(mysqli_error($link));     
		$_SESSION["msgStatus"] = 4;//註冊成功，進入訊息頁面會顯示註冊成功提示。
		header("Location:/PID_Assignment/status.php");
		exit();
	  
 
	}
}


?>


<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>會員登入</title>
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
			<p class="text-center">歡迎註冊CC購物</a></p>
			<hr>


			<div class="row justify-content-center">
				<div class="col-md-6">
					<div class="card">
						<header class="card-header">
							<div class="row" style="position: relative;">
								<div class="col-6"><h4 class="card-title mt-2">註冊會員</h4></div>
								<!-- <div class="col-6" style="position: absolute; bottom: 0; right: 0;" ><p>己經有帳號了嗎 ? <a href="page-register.php"> 現在就登入！</a></p></div> -->
							</div>
						</header>
						<article class="card-body">
							<form id="registerForm" name="registerForm" method="post" action="">
								<div class="form-row">
									<div class="col form-group">
										<label>姓名 </label>
										<input type="text" id="inputName" name="inputName" class="form-control" placeholder="" value="<?php if(isset($_POST["inputName"])) { echo "{$_POST["inputName"]}"; } ?>">
									</div> <!-- form-group end.// -->
									<div class="form-row">
									<div class="col form-group">
										<label>帳號名稱</label>
										<?php if(isset($_POST["userError"])) { echo '&nbsp;<label style="color: red;">輸入的使用者名稱己被使用！</label>'; } ?>
										<input type="text" id="inputUserName" name="inputUserName" class="form-control" placeholder="" value="<?php if(isset($_POST["inputUserName"])) { echo "{$_POST["inputUserName"]}"; } ?>">
     								</div>
									</div> <!-- form-group end.// -->
								</div> <!-- form-row end.// -->
								<div class="form-row">
								<div class="col form-group">
										<label>密碼</label>
										<input type="password" id="inputPassword" name="inputPassword" class="form-control" placeholder="請輸入英文或數字">
									</div> <!-- form-group end.// -->
									<div class="col form-group">
									<label>電子郵件</label>
									<input type="email" id="inputEmail" name="inputEmail" class="form-control" placeholder="" value="<?php if(isset($_POST["inputEmail"])) { echo "{$_POST["inputEmail"]}"; } ?>">
									</div> <!-- form-group end.// -->
								</div> <!-- form-row end.// -->

								<div class="form-group">
                                <label>性別&nbsp;&nbsp;&nbsp;</label>

									<label class="form-check form-check-inline">
										<input class="form-check-input" type="radio" name="radioGender" value="男">
										<span class="form-check-label"> 男 </span>
									</label>
									<label class="form-check form-check-inline">
										<input class="form-check-input" type="radio" name="radioGender" value="女">
										<span class="form-check-label"> 女</span>
									</label>
								</div> <!-- form-group end.// -->
								<div class="form-row">
								<div class="col form-group">
								        <label>電話</label>
										<input type="tel" class="form-control" id="inputPhoneNumber" name="inputPhoneNumber" value="<?php if(isset($_POST["inputPhoneNumber"])) { echo "{$_POST["inputPhoneNumber"]}"; } ?>" required pattern="[0-9]{9,10}">
									</div> <!-- form-group end.// -->
									<div class="col form-group">
									<label>生日</label>
									<input class="form-control" type="date" id="inputBirthday" name="inputBirthday" value="<?php if(isset($_POST["inputBirthday"])) { echo "{$_POST["inputBirthday"]}"; } ?>">
									</div> <!-- form-group end.// -->
								</div> <!-- form-row end.// -->

								<div class="form-group text-right">
									<button type="submit" class="btn btn-outline-primary" name="btnRegister" id="btnRegister" value="btnRegister"> 註冊 </button>
								</div> <!-- form-group// -->

							</form>
						</article> <!-- card-body end .// -->
						<div class="border-top card-body text-center">己經有帳號了嗎 ? <a href="/PID_Assignment/member/login.php">現在就登入！</a></div>
						<a class="nav-link" href="/PID_Assignment/index.php">點此返回CC音饗<span class="fa fa-arrow-left"></span></a>
					</div> <!-- card.// -->
				</div> <!-- col.//-->

			</div> <!-- row.//-->


		</div>
		<!--container end.//-->
	
</body>

</html>