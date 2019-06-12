<?php
session_start();

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>


<?php

    include_once "config.php";
    if (isset($_POST) && !empty($_POST)){
        $errors = array();

        if (!isset($_POST["username"]) || empty($_POST["username"])){
            $errors[] = "Username không hợp lệ";
        }

        if (!isset($_POST["Password"]) || empty($_POST["Password"])){
            $errors[] = "Password không hợp lệ";
        }

        if (!isset($_POST["Confirm_Password"]) || empty($_POST["Confirm_Password"])){
            $errors[] = "Confirm_Password không hợp lệ";
        }

        if ($_POST["Confirm_Password"] !== $_POST["Password"]){
            $errors[] = "Confirm_Password khác passwword";
        }

        if (empty($errors)){
            /**
             * nếu không có lỗi thì thực thi câu lệnh insert vào CSDL
             */
            $username = $_POST["username"];
            $password = md5($_POST["Password"]);
            $created_at = date("Y-m-d H:i:s");

            $sqlInsert = "INSERT INTO users(username, password, created_at) VALUES (?,?,?)";

            //chuẩn bị cho phần SQL
            $stmt = $connection->prepare($sqlInsert);

            // Bind 3 biến vào trong câu SQL
            $stmt->bind_param("sss", $username, $password, $created_at);

            $stmt->execute();

            $stmt->close();

            echo "<div class='alert alert-success' >";
            echo  "Đăng ký người dùng mới thành công <a href='login.php'>Đăng nhập</a> ngay lập tức";
            echo "</div>";
        }else{
            $errors_string = implode("<br>" ,$errors);
            echo "<div class='alert alert-danger' >";
            echo  $errors_string;
            echo "</div>";
        }
    }
?>

<div class="container" style="margin-top: 150px">
    <div class="row">
        <div class="col-md-12">
            <h1>Đăng ký người dùng</h1>
            <form name="register" action="" method="post">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" placeholder="Tên đăng nhập" autocomplete="off">
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="Password" name="Password" class="form-control"placeholder="Nhập password">
                </div>

                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="Password" name="Confirm_Password" class="form-control"placeholder="Nhập Confirm password">
                </div>

                <button type="submit" class="btn btn-primary">Đăng ký</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>