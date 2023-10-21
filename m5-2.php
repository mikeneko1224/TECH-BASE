<?php
ini_set('error_reporting', E_ALL);
$dsn = "mysql:dbname=データベース名;host=localhost";
$user = 'ユーザー名';
$password = 'パスワード';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, PDO::ATTR_EMULATE_PREPARES => false,));
// if($pdo){
//     echo '成功！';
// }else{
//     echo '失敗！';
// }
// $spl = "DROP TABLE IF EXISTS tbtest";
// $stmt = $pdo->query($sql);

global $name_value;
$name_value = "";
global $comment_value;
$comment_value = "";
$ID3 = "";
$password = "";

$sql = "CREATE TABLE IF NOT EXISTS tbtest"
."("
."id INT AUTO_INCREMENT PRIMARY KEY,"
."`name` CHAR(32),"
."`comment` TEXT,"
."`pass` CHAR(32),"
."`date` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"
.");";
$stmt = $pdo->query($sql);

// $sql = "SHOW TABLES";
// $result = $pdo->query($sql);
// foreach ($result as $row) {
//     echo $row[0];
//     echo "<br>";
// }
// echo "<hr>";

// $sql ='SHOW CREATE TABLE tbtest';
//     $result = $pdo -> query($sql);
//     foreach ($result as $row){
//         echo $row[1];
//     }
//     echo "<hr>";


if(!empty($_POST['name'])) {
    if(!empty($_POST['str'])) {
        if(!empty($_POST['password'])) {
            if(!empty($_POST["num_value"])) { //変更を追加
                $id = $_POST['num_value']; //変更する投稿番号
                $pass = $_POST["password"];
                $name = $_POST['name'];
                $comment = $_POST['str']; //変更したい名前、変更したいコメントは自分で決めること
                $date = date('Y-m-d H:i:s');
                
                $sql = 'UPDATE tbtest SET name=:name,comment=:comment,date=:date, pass=:password WHERE id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(':id', $id, PDO::PARAM_INT);
                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                $stmt->bindValue(':password', $pass);
                $stmt->bindParam(':date', $date);
                $stmt->execute();
            } else {
                //追加機能
                $name = $_POST['name'];
                $comment = $_POST['str'];
                $password = $_POST['password'];
                $date = date('Y-m-d H:i:s');
            
                $sql = "INSERT INTO tbtest (name, comment, pass, date) VALUES (:name, :comment, :password, :date)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(':name', $name);
                $stmt->bindValue(':comment', $comment);
                $stmt->bindValue(':password', $password);
                $stmt->bindValue(':date', $date);
                $stmt->execute();
            }
        }
    }
} else if(!empty($_POST["num"])) { //削除
    $id = $_POST["num"];
    $pass = $_POST["password3"];
    $sql = 'delete from tbtest where id=:id and pass=:password';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':password', $pass);
    $stmt->execute();
} else if(!empty($_POST["chnum"])) {
    $id = $_POST["chnum"];
    $pass = $_POST["password2"];
    // $sql = 'SELECT name, comment, pass, date FROM tbtest where id=:id and pass=:password';
    // $stmt = $pdo->prepare($sql);
    // $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    // $stmt->bindParam(':password', $pass);
    // $stmt->execute();
    // echo $stmt[0];
    
    // $name_value = $row['name'];
    // $comment_value = $row['comment'];
    // $ID3 = $row['id'];
    // $password = $row['pass'];
    
    $sql = 'SELECT * FROM tbtest';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
        if($row['id'] == $id) {
            if($row['pass'] == $pass) {
                //$name_value = "name";
                $name_value = $row['name'];
                $comment_value = $row['comment'];
                $ID3 = $row['id'];
                $password = $row['pass'];
                //echo $name_value;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_1-24</title>
</head>
<body>
<div style="font-weight:bold; text-align:center; font-size:70px; background-color:#ccffff;">掲示板</div>
<form style="margin:30px 50px;" action="" method="post">
    <input type="text" name="name" placeholder="名前" value="<?= $name_value; ?>">
    <input type="text" name="str" placeholder="コメント"  value="<?= $comment_value; ?>">
    <input type="hidden" name="num_value" value="<?= $ID3; ?>">
    <input type="text" name="password" placeholder="パスワードを入力" value="<?= $password; ?>">
    <input type="submit" name="submit" value="送信"><br>
    <input type="text" name="chnum" placeholder="変更したい番号">
    <input type="text" name="password2" placeholder="パスワードを入力">
    <input type="submit" name="change" value="変更"><br>
    <input type="text" name="num" placeholder="削除したい番号">
    <input type="text" name="password3" placeholder="パスワードを入力">
    <input type="submit" name="delete" value="削除">
</form>

<div style="margin:30px 50px;">
<?php
$dsn = "mysql:dbname=tb250412db;host=localhost";
$user = 'tb-250412';
$password = 'SbdhTwGdC4';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));


$sql = 'SELECT * FROM tbtest';
$stmt = $pdo->query($sql);
$results = $stmt->fetchAll();
//echo $sql;
foreach ($results as $row){
    //$rowの中にはテーブルのカラム名が入る
    //print_r($row);
    echo $row['id'].'. ';
    echo $row['name'].' ';
    echo $row['comment'].' ';
    echo $row['date'].'<br>';
    echo "<hr>";
}
?>
</div>

</body>