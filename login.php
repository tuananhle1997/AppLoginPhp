<?php
session_start();

if (isset($_SESSION["loggedin"]) && ($_SESSION["loggedin"]) ){
    header("Location: index.php");
    exit;
}

/*
 * nạp file kết nối CSDL
 */

include_once "config.php";

// biến lưu trữ lỗi trong quá trình đăng nhập
$errors = array();
/**
 * xử lý đăng nhập
 */

if (isset($_POST) && !empty($_POST)){
    if (!isset($_POST["username"]) || empty($_POST["username"])){
        $errors[] = "Chưa đăng nhập username";
    }
    if (!isset($_POST["password"]) || empty($_POST["password"])){
        $errors[] = "Chưa đăng nhập password";
    }

    /**
     * nếu mảng $errors bị rỗng tức là không có lỗi đăng nhập
     */
    if (is_array($errors) && empty($errors)){
        $username = $_POST["username"];
        $password = md5($_POST["password"]);

        $sqlLogin = "SELECT * FROM users WHERE username = ? AND password = ? ";

        // chuẩn bị cho phần SQL
        $stmt = $connection->prepare($sqlLogin);

        // 3 biến vào trong câu SQL
        $stmt->bind_param("ss", $username, $password );


        // thực thi câu lệnh sql
        $stmt->execute();

        // lấy ra bản ghi
        $res = $stmt->get_result();

        $row = $res->fetch_array(MYSQLI_ASSOC);

        if (isset($row['id']) && ($row['id'] > 0) ){

            /**
             * nếu tồn tại bản ghi thì sẽ tạo ra session đăng nhập
             */


            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $row['username'];

            header("Location: index.php");
            exit;
        }else{
            $errors[] = "Dữ liệu đăng nhập không đúng";
        }
    }
}

if (is_array($errors) && !empty($errors)){
    $errors_string = implode("<br>" ,$errors);
    echo "<div class='alert alert-danger' >";
    echo  $errors_string;
    echo "</div>";
}
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
<div class="container" style="margin-top: 150px">
    <div class="row">
        <div class="col-md-12">
            <h1>Đăng nhập</h1>
            <form name="login" action="" method="post">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" placeholder="Tên đăng nhập" autocomplete="off">
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control"placeholder="Nhập password">
                </div>
                <div class="form-group form-check">
                    <p><a href="register.php">Đăng ký</a></p>
                </div>

                <button type="submit" class="btn btn-primary">Đăng nhập</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>