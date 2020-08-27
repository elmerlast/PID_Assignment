<?php 
// require_once("connDB.php");
// // $link = mysqli_connect("localhost", "root", "root", "lab0819db", 8889);
// // mysqli_query($link, "set names utf-8");
// $sqlStatement =<<<mulity
//  select e.employeeId, firstName, lastName, e.cityId, cityName
//      from city c join employee e on e.cityId = c.cityId;
// mulity;
// $result =mysqli_query($link, $sqlStatement);

?>



<!DOCTYPE html>
<html lang="en">
<head>
  <title>CC購物會員管理系統</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"> 
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

<style>

</style>


</head>
<body>
  
<div class="container">
  <h2>會員列表</h2>
  <table class="table table-hover">
    <thead class="thead-light">
      <tr>
        <th>Firstname</th>
        <th>Lastname</th>
        <th>City</th>
        <th>
         <!-- 維持版面 -->
         <span class = "float-right">
        <a href ="./addEmployee.php" class="btn btn btn-outline-success btn-sm"><b>+</b></a>
        </span>
        <!-- 維持版面 -->
        </th>
      </tr>
    </thead>
    <tbody>

    <?php while ($row = mysqli_fetch_assoc($result)){?>
      <tr>
        <td><?= $row["firstName"] ?></td>
        <td><?= $row["lastName"] ?></td>
        <td><?= $row["cityName"] ?></td>
        <td>
        <span class = "float-right">
        <a href ="./editform.php?id=<?= $row["employeeId"]?>" class="btn btn btn-outline-success btn-sm">edit</a>
        |
        <a href ="./deleteEmployee.php?id=<?= $row["employeeId"]?>" class="btn btn btn-outline-danger btn-sm">delete</a>
        </span>
        </td>
      </tr>
    <?php } ?>
      
    </tbody>
  </table>
</div>


</body>
</html>