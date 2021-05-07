<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>

        table {
            border-collapse: collapse;
            width: 100%;
        }

        td, th {
            border: solid 1px #ccc;
        }

        form {
            width: 450px;
        }
    </style>
</head>
<body>
<form action="" method="post">
    <input type="text" name="userName" placeholder="UserName" value="">
    <input type="email" name="email" placeholder="Email">
    <input type="number" name="phoneNumber" placeholder="Phone Number">
    <input type="submit">
</form>
<?php
$contact = [];
$condition1 = false;
$condition2 = false;
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];
    $phoneNumber = $_POST['phoneNumber'];
    $userName = $_POST['userName'];

    if (empty($email) || empty($phoneNumber) || empty($userName)) {
        echo "điền thiếu thông tin";
        $condition1 = false;
    } else {
        $condition1 = true;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($email)) {
        echo "nhập sai cấu trúc mail";
        $condition2 = false;
    }
    if ($condition1) {

        saveDataJSON($userName, $email, $phoneNumber, 'users.json');
        $userName = null;
        $email = null;
        $phoneNumber = null;
    }
}
function loadRegistrations($filename)
{
    $jsondata = file_get_contents($filename);
    $arr_data = json_decode($jsondata, true);
    return $arr_data;
}

function saveDataJSON($userName, $email, $phoneNumber, $filename)
{
    try {
        $contact = [
            'name' => $userName,
            'email' => $email,
            'phone' => $phoneNumber
        ];

        // converts json data into array
        $arr_data = loadRegistrations($filename);
        //var_dump($arr_data); die();


        // Push user data to array
        array_push($arr_data, $contact);

        //Convert updated array to JSON
        $jsondata = json_encode($arr_data, JSON_PRETTY_PRINT);
        var_dump($jsondata);

        //write json data into users.json file
        file_put_contents($filename, $jsondata);

        echo "Lưu dữ liệu thành công!";
    } catch (Exception $e) {
        echo 'Lỗi: ', $e->getMessage(), "\n";
    }
}

?>
<?php
$registrations = loadRegistrations('users.json');
?>
<h2>Đăng ký người dùng</h2>
<table>
    <tr>
        <td>Name</td>
        <td>Email</td>
        <td>Số điện thoại</td>
    </tr>
    <?php foreach ($registrations as $registration): ?>
        <tr>
            <td><?= $registration['name'] ?></td>
            <td><?= $registration['email'] ?></td>
            <td><?= $registration['phone'] ?></td>
        </tr>
    <?php endforeach; ?>
</table>
</body>
</html>
