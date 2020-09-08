<?php
session_start();

if ($_SESSION["level"]!=999|| !isset($_SESSION["uId"])) {
	$_SESSION["msgStatus"] = 11;//權限非管理員，進入訊息頁面會顯示權限不足提示。
	header("Location:/PID_Assignment/status.php");
	exit();
  }

require_once("../sql/onnDB.php");

/*
 *使用者按下「取消」按鈕
 */
if(isset($_POST["btnCancel"])){
	header("location: member_admin.php");
	exit();
  }



/*
 *使用者按下「新增」按鈕
 */
if (isset($_POST["btnConfirm"])) {

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
		$addAuthority = $_POST["inputAuthority"];
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
		mysqli_close($link);
		$_SESSION["msgStatus"] = 9;//新增會員成功，進入訊息頁面會顯示成功提示。
		header("Location:/PID_Assignment/status.php");
		exit();
	}
}
mysqli_close($link);
?>


<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>新增會員</title>
	<!-- Bootstrap core CSS -->

	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.8/css/all.css">
	<link rel="stylesheet" href="\PID_Assignment\css\bootstrap.min.css">
  	<link rel="stylesheet" href="\PID_Assignment\css\store_index.css">
  	<script src="\PID_Assignment\js\jquery.min.js"></script>
  	<script src="\PID_Assignment\js\bootstrap.min.js"></script>
  	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
								<div class="col-6"><h4 class="card-title mt-2">新增會員</h4></div>
								<!-- <div class="col-6" style="position: absolute; bottom: 0; right: 0;" ><p>己經有帳號了嗎 ? <a href="page-register.php"> 現在就登入！</a></p></div> -->
							</div>
						</header>
						<article class="card-body">
							<form id="registerForm" name="registerForm" method="post" action="">
								<div class="form-row">
									<div class="col form-group">
										<label>姓名 </label>
										<input type="text" id="inputName" name="inputName" class="form-control" placeholder="">
									</div> <!-- form-group end.// -->
									<div class="form-row">
									<div class="col form-group">
										<label>帳號名稱</label>
										<?php if(isset($_POST["userError"])) { echo '&nbsp;<label style="color: red;">輸入的使用者名稱己被使用！</label>'; } ?>
										<input type="text" id="inputUserName" name="inputUserName" class="form-control" placeholder=" ">
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
									<input type="email" id="inputEmail" name="inputEmail" class="form-control" placeholder="">
									</div> <!-- form-group end.// -->
								</div> <!-- form-row end.// -->
								<div class="form-row">
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
								<div class="col form-group">
								  <div class="form-row">
									<div class="col-2"></div>
									<label for=" inputAuthority" class="col-2 col-form-label">權限</label>
									<div class="col-8">
										<select id=" inputAuthority" name=" inputAuthority" class="form-control"
											required="required">
											<option value="member" selected >一般會員</option>
											<option value="admin" >管理員</option>
											<option value="readonly">禁止購物</option>
											<option value="suspension">停權中</option>
										</select>
									</div>
							      </div>
								</div> <!-- form-group end.// -->
							</div> <!-- form-row end.// -->

								<div class="form-row">
								<div class="col form-group">
								        <label>電話</label>
										<input type="tel" class="form-control" id="inputPhoneNumber" name="inputPhoneNumber" required pattern="[0-9]{9,10}">
									</div> <!-- form-group end.// -->
									<div class="col form-group">
									<label>生日</label>
									<input class="form-control" type="date" id="inputBirthday" name="inputBirthday" >
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

</html>