<?php 
require_once("../sql/onnDB.php");
$sqlStatement =<<<mulity
 select * from tbl_users;
mulity;
$result =mysqli_query($link, $sqlStatement);

?>



<!DOCTYPE html>
<html>
<head>
  <title>CC購物會員管理系統</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"> 
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
.tables{
width: 125%;
height: 100%;
table-layout: fixed;
}

.td_overflow
{
 max-width: 140px;
 overflow: hidden;
 text-overflow: ellipsis;
 white-space: nowrap;
}
</style>


</head>
<body>


  
<div class="container">
  <h2>會員列表</h2>
  <table class="table table-hover tables" >
    <thead class="thead-light">
      <tr>
        <th>會員編號</th>
        <th>姓名</th>
        <th>使用者名稱</th>
        <th>密碼</th>
        <th>性別</th>
        <th>電子郵件</th>
        <th>電話</th>
        <th>生日</th>
        <th>加入時間</th>
        <th>權限等級</th>
        <th>
        <a href ="./member_adding.php" class="btn btn btn-outline-success btn-sm"><i class="fa fa-plus "></i></a>
        </th>
      </tr>
    </thead>
    <tbody>

    <?php while ($row = mysqli_fetch_assoc($result)){?>
      <tr>
        <td><?= $row["m_id"] ?></td>
        <td><?= $row["m_name"] ?></td>
        <td><?= $row["m_username"] ?></td>
        <td class="td_overflow" title="<?= $row["m_password"] ?>" ><?= $row["m_password"] ?></td>
        <td style=""><?= $row["m_gender"] ?></td>
        <td class="td_overflow" title="<?= $row["m_email"] ?>"><?= $row["m_email"] ?></td>
        <td><?= $row["m_phone"] ?></td>
        <td class="td_overflow" title="<?= $row["m_birthday"] ?>"><?= $row["m_birthday"] ?></td>
        <td class="td_overflow" title="<?= $row["m_jointime"] ?>"><?= $row["m_jointime"] ?></td>
        <td><?= $row["m_level"] ?></td>
        <td>
        <a href ="./member_update.php?id=<?= $row["m_id"]?>" class="btn btn btn-success btn-sm"><i class="fa fa-pencil-square-o "></i></a>
        <a href ="./member_delete.php?id=<?= $row["m_id"]?>" class="btn btn btn-danger btn-sm"><i class="fa fa-times "></i></a>
        </td>
      </tr>
    <?php } ?>
      
    </tbody>
  </table>
</div>

</body>
</html>