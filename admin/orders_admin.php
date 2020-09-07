<?php 
session_start();

if ($_SESSION["level"]!=999) {
	$_SESSION["msgStatus"] = 11;//權限非管理員，進入訊息頁面會顯示權限不足提示。
	header("Location:/PID_Assignment/status.php");
	exit();
  }

require_once("../sql/onnDB.php");




if (isset($_POST["btnSearch"]) && $_POST["inputMemberUserName"]!="") {

    //透過管理員輸入的使用者名稱取得會員編號
    $sql = "select m_id from tbl_users where m_username = '{$_POST["inputMemberUserName"]}'; ";
    $result = mysqli_query($link, $sql);
    $row = mysqli_fetch_array($result);
    $mId = $row["m_id"];

    //查詢不到該會員
    if(mysqli_num_rows($result) == 0) {$mIdNotFound = true;} 

    //取得被搜尋會員的所有訂單
    $sql = <<<sql

    select o.ord_id, o.m_id, u.m_username, o.ord_paytype, o.ord_total, o.ord_purchasetime, o.ord_status
    from tbl_users u
    inner join tbl_orders o
    on u.m_id = o.m_id
    where u.m_id = {$mId};
    
    sql;
    $result =mysqli_query($link, $sql);

}else{
    //取得所有的訂單
    $sql = <<<sql
    select o.ord_id, o.m_id, u.m_username, o.ord_paytype, o.ord_total, o.ord_purchasetime, o.ord_status
    from tbl_users u
    inner join tbl_orders o
    on u.m_id = o.m_id
    sql;
    $result =mysqli_query($link, $sql);
}


mysqli_close($link);


?>




<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>訂單管理</title>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
    integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
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
    <a class="navbar-brand" href="">CC音饗管理系統</a>

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
    <h1>訂單管理</h1>
    <table class="table table-hover">
  <thead class="thead-light">
    <tr>
        <th colspan="8">
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
      <th scope="col">訂單編號</th>
      <th scope="col">會員編號</th>
      <th scope="col">使用者名稱</th>
      <th scope="col">付款方式</th>
      <th scope="col">總額</th>
      <th scope="col">下訂時間</th>
      <th scope="col">訂單狀態</th>
      <th scope="col"></th>
    </tr>
  </thead>
  <tbody>
    <?php if(mysqli_num_rows($result) > 0){    
    while ($row = mysqli_fetch_assoc($result)){?>
      <tr>
        <th scope="row"><?=$row["ord_id"] ?></th>
           <td><?=$row["m_id"]?></td>
           <td><?=$row["m_username"]?></td>
           <td><?=$row["ord_paytype"]?></td>
           <td class="price"><?="$".number_format($row["ord_total"])?></td>  
           <td><?=$row["ord_purchasetime"]?></td>  
           <td><?=$row["ord_status"]?></td>
           <td>
             <a href ="./order_details.php?id=<?= $row["ord_id"]?>" class="btn btn btn-success btn-sm"><i class="fa fa-info-circle "></i></a>
             <a href ="./delete_order.php?id=<?= $row["ord_id"]?>" class="btn btn btn-danger btn-sm"><i class="fa fa-times "></i></a>
           </td>  
        <?php }//end of while
        }else{
              if(isset($_POST["inputMemberUserName"]) && $mIdNotFound != true ){    
        ?>
        <td colspan="8">
            <h5 style="text-align:center;"> 該會員目前沒有任何訂單。</h5>
        </td>
        <?php }elseif($mIdNotFound == true){ ?>
        <td colspan="8">
            <h5 style="text-align:center;">查詢不到該會員。</h5>
        </td>
        <?php }else{ ?>
        <td colspan="8">
            <h5 style="text-align:center;"> 商店目前沒有任何訂單。</h5>
        </td>
        <?php 
              }//end of isset btnsearch
        }//end of rows > 0       ?>        
      </tr>
  </tbody>
</table>

<div class="row">
    <div class="col-10"></div>
    <div class="col-2">
      <?php if(isset($_POST["inputMemberUserName"])){echo '<a href="orders_admin.php" class="btn btn-info">&emsp;返回&emsp;</a>';} ?>
</div>
</div>



  </div> 


</body>
</html>