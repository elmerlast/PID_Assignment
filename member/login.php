<?php
session_start();

if (isset($_POST["btnLogin"])) {
	$userId = $_POST["inputUid"];
	$userPwd = hash('sha256', "{$_POST["inputPwd"]}");
	if ($userId && $userPwd) {
		require_once "../sql/onnDB.php";
		$sql = "select * from tbl_users where m_username = '$userId' and m_password='$userPwd'";
		$result = mysqli_query($link, $sql);
		$rows = mysqli_fetch_array($result);
		if ($rows) {
			$_SESSION["uId"] = $rows["m_username"];
			$_SESSION["login"] = $rows["m_level"]; //將使用者的帳號等級記錄到session當中。
			switch ($_SESSION["login"]) {
				case "admin":
					$_SESSION["msgStatus"] = 2;//己登入，進入訊息頁面會顯示管理員登入提示。
					header("Location:/PID_Assignment/status.php");
					exit();
				case "member":
					$_SESSION["msgStatus"] = 1;//己登入，進入訊息頁面會顯示登入提示。
					header("Location:/PID_Assignment/status.php");
					exit();
				case "readonly":
					$_SESSION["msgStatus"] = 1;//己登入，進入訊息頁面會顯示登入提示。
					header("Location:/PID_Assignment/status.php");
					exit();
				case "suspension":
					$_SESSION["msgStatus"] = 6;//停權會員，進入訊息頁面會顯示停權提示。
					header("Location:/PID_Assignment/status.php");
					$_SESSION["uId"] = null;
					exit();
			}
		
		} else {
			$_POST["loginError"] = "error";
		}
	}
	mysqli_close();

}


?>



<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="icon" href="/docs/4.0/assets/img/favicons/favicon.ico">

	<title></title>

	<link rel="canonical" href="https://getbootstrap.com/docs/4.0/examples/sign-in/">

	<!-- Bootstrap core CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
		integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

	<!-- Custom styles for this template -->
	<link rel="stylesheet" type="text/css" href="/RD5_Assignment/CSS/signin.css">






</head>

<body>



	<div class="container">


		<div class="row justify-content-center">
			<div class="col-md-4">
				<div class="card">
					<header class="card-header">
						<div class="row" style="position: relative;">
							<div class="col-12">
								<h4 class="card-title mt-2">登入會員開始購物！</h4>
							</div>
							<!-- <div class="col-6" style="position: absolute; bottom: 0; right: 0;" ><p>己經有帳號了嗎 ? <a href="page-register.php"> 現在就登入！</a></p></div> -->
						</div>
					</header>
					<article class="card-body">
						<form id="registerForm" name="registerForm" method="post" action="">
							<div class="form-group row">
								<label for="" class="col-4 col-form-label">帳號</label>
								<div class="col-8">
									<input id="inputUid" name="inputUid" type="text" class="form-control" value="<?php if(isset($_POST["inputUid"])) { echo "{$_POST["inputUid"]}"; } ?>" required="required">
								</div>
							</div>
							<div class="form-group row">
								<label for="text" class="col-4 col-form-label">密碼</label>
								<div class="col-8">
									<input id="inputPwd" name="inputPwd" type="password" class="form-control" required="required">
								</div>
							</div>

							<div class="form-group">
								<button type="submit" class="btn btn-outline-primary btn-block" name="btnLogin"
									id="btnLogin" value="btnLogin"> 登入 </button>
							</div> <!-- form-group// -->

							<?php if(isset($_POST["loginError"])) { ?>
								<?php echo '<div class="form-group">  <label style="color: red;" >※帳號或密碼錯誤</label>   </div> <!-- form-group// -->';?>
							<?php } ?>							

						</form>
					</article> <!-- card-body end .// -->
					<div class="border-top card-body text-center">還沒有註冊嗎 ? <a
							href="/PID_Assignment/member/register.php">點此註冊</a></div>
							<a class="nav-link" href="/PID_Assignment/index.php">點此返回CC音饗<span class="fa fa-arrow-left"></span></a>

				</div> <!-- card.// -->
			</div> <!-- col.//-->

		</div> <!-- row.//-->


	</div>
	<!--container end.//-->

</body>

</html>