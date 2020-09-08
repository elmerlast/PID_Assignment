<?php
session_start();
?>

<!DOCTYPE html >
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>訊息</title>
</head>
<body>

<table width="300" border="7" align="center" cellpadding="5" cellspacing="0" bgcolor="#F2F2F2">
  <tr>
    <td align="center" bgcolor="#CCCCCC"><font color="#FFFFFF">訊息</font></td>
  </tr>
  <tr>
   <td align="center" valign="baseline">
    <?php
      switch ($_SESSION["msgStatus"]) {
        case 1:
          if(isset($_SESSION["checkout"])){
            $_SESSION["checkout"] = null;
            echo "登入成功!2秒後自動跳轉到結帳頁面";
            header("Refresh:2; url=/PID_Assignment/checkout.php");
            exit();
          }
          echo "登入成功!2秒後自動跳轉到首頁";
          header("Refresh:2; url=/PID_Assignment/index.php");
          exit();
        case 2:
          echo "管理員您好，2秒後自動跳轉到商品管理首頁";
          header("Refresh:2; url=/PID_Assignment/admin/commodity_admin.php");
          exit();
        case 3:
          echo "您已登出，2秒後自動跳轉到首頁";
          header("Refresh:2; url=/PID_Assignment/index.php");
          exit();
        case 4:
          echo "註冊成功，2秒後自動跳轉到登入頁面";
          header("Refresh:2; url=/PID_Assignment/member/login.php");
          exit();
        case 5:
          echo "購買成功，2秒後自動跳轉到首頁";
          header("Refresh:2; url=/PID_Assignment/index.php");
          exit();
        case 6:
          echo '<h5 style="color:red;">該會員已被停權。2秒後自動跳轉到首頁</h5>';
          header("Refresh:2; url=/PID_Assignment/index.php");
          exit();
        case 7:
          echo '新增成功，2秒後自動跳轉到商品管理首頁';
          header("Refresh:2; url=/PID_Assignment/admin/commodity_admin.php");
          exit();
        case 8:
          echo '修改成功，2秒後自動跳轉到商品管理首頁';
          header("Refresh:2; url=/PID_Assignment/admin/commodity_admin.php");
          exit();
        case 9:
          echo '新增成功，2秒後自動跳轉到會員管理首頁';
          header("Refresh:2; url=/PID_Assignment/admin/member_admin.php");
          exit();
        case 10:
          echo '修改成功，2秒後自動跳轉到會員管理首頁';
          header("Refresh:2; url=/PID_Assignment/admin/member_admin.php");
          exit();
        case 11:
          echo '<h5 style="color:red;">權限不足。2秒後自動跳轉到首頁</h5>';
          header("Refresh:2; url=/PID_Assignment/index.php");
          exit();
        case 12:
          echo '<h5 style="color:red;">您目前被禁止購物。2秒後自動跳轉到首頁</h5>';
          header("Refresh:2; url=/PID_Assignment/index.php");
          exit();
      }

    ?>
   </td>
  </tr>

</table>


</body>
<?php
// header("Refresh:2; url={$_SESSION["pageTarget"]}");
// header("Refresh:2; url=/PID_Assignment/index.php");
// exit();
?>
</html>