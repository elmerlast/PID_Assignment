<?php
session_start();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
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
          echo "登入成功!，2秒後自動跳轉到龬路銀行頁面";
          break;
        case 2:
          echo "變數是 1";
          break;
        case 3:
          echo "變數是 2";
          break;
      }
    ?>
   </td>
  </tr>

</table>


</body>
<?php
// header("Refresh:2; url={$_SESSION["pageTarget"]}");
header("Refresh:2; url=https://www.google.com/webhp?hl=zh-TW&sa=X&ved=0ahUKEwig4PLn5rrrAhUqyosBHRGcAw0QPAgI");
exit();
?>
</html>