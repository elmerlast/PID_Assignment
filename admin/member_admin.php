<?php 
session_start();

if ($_SESSION["level"]!=999) {
	$_SESSION["msgStatus"] = 11;//權限非管理員，進入訊息頁面會顯示權限不足提示。
	header("Location:/PID_Assignment/status.php");
	exit();
  }

require_once("../sql/onnDB.php");


//取得所有會員資料
$sql = <<<sql
select * from tbl_users;
sql;
$result =mysqli_query($link, $sql);


if (isset($_POST["btnSearch"]) && $_POST["inputMemberUserName"]!="") {

  //透過管理員輸入的使用者名稱取得會員編號
  $sql = "select m_id from tbl_users where m_username = '{$_POST["inputMemberUserName"]}'; ";
  $result = mysqli_query($link, $sql);
  $row = mysqli_fetch_array($result);
  $mId = $row["m_id"];

  //查詢不到該會員
  if(mysqli_num_rows($result) == 0) {  
      $mIdNotFound = true;
  }else{
      //取得被搜尋會員的資料
      $sql = <<<sql
      select * from tbl_users where `m_id` = {$mId};
      sql;
      $result =mysqli_query($link, $sql);
  }
}

mysqli_close($link);

?>



<!DOCTYPE html>
<html>
<head>
  <title>CC購物 會員管理系統</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="/PID_Assignment/css/store_index.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>



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
  <h1>會員列表</h1>
  <table class="table table-hover" >
    <thead class="thead-light">
      <tr>
        <th colspan="10">
            <form action="" method="post">
              <div class="input-group ">
                <input id="inputMemberUserName" name="inputMemberUserName" type="text" class="form-control"
                  placeholder="請輸入使用者名稱" value="<?php if(isset($_POST["inputMemberUserName"])){echo"{$_POST["inputMemberUserName"]}";} ?>">
                <div class="input-group-append">
                  <button class="btn btn-primary" type="submit" name="btnSearch" id="btnSearch"
                   value="btnSearch">搜尋</button>
                </div>
            </form>
          </th>
      </tr>
      <tr>
        <th>編號</th>
        <th>姓名</th>
        <th>使用者名稱</th>
        <th>性別</th>
        <th>電子郵件</th>
        <th>電話</th>
        <th>生日</th>
        <th>加入時間</th>
        <th>權限等級</th>
        <th>
        <a href ="./member_adding.php" class="btn btn btn-outline-success btn-sm"><i class="fa fa-plus "></i></a>
        </th>
      </tr>
    </thead>
    <tbody>

    <?php if($mIdNotFound != true){    
    while ($row = mysqli_fetch_assoc($result)){?>
      <tr>
        <td><?= $row["m_id"] ?></td>
        <td><?= $row["m_name"] ?></td>
        <td><?= $row["m_username"] ?></td>
        <td style=""><?= $row["m_gender"] ?></td>
        <td class="td_overflow" title="<?= $row["m_email"] ?>"><?= $row["m_email"] ?></td>
        <td><?= $row["m_phone"] ?></td>
        <td class="td_overflow" title="<?= $row["m_birthday"] ?>"><?= $row["m_birthday"] ?></td>
        <td class="td_overflow" title="<?= $row["m_jointime"] ?>"><?= $row["m_jointime"] ?></td>
        <td><?= $row["m_level"] ?></td>
        <td>
        <a href ="./member_update.php?id=<?= $row["m_id"]?>" class="btn btn btn-success btn-sm"><i class="fa fa-pencil-square-o"></i></a>
        <a href ="./member_delete.php?id=<?= $row["m_id"]?>" class="btn btn btn-danger btn-sm"><i class="fa fa-times "></i></a>
        </td>
      </tr>
    <?php }
    }else{ ?>
        <td colspan="10">
            <h5 style="text-align:center;">找尋不到該會員。</h5>
        </td>
    <?php } ?>
    </tbody>
  </table>
  <div class="row">
      <div class="col-10"></div>
      <div class="col-2">
          <?php if(isset($_POST["inputMemberUserName"])){echo '<a href="member_admin.php" class="btn btn-info">&emsp;返回&emsp;</a>';} ?>
      </div>
  </div>


</body>
</html>