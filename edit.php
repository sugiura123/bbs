<?php

require_once('config.php');
require_once('functions.php');

$id = $_GET['id'];

$dbh = connectDatabase();
$sql = "select * from posts where id = :id";
$stmt = $dbh->prepare($sql);
$stmt->bindParam(":id", $id);
$stmt->execute();

$row = $stmt->fetch();

// var_dump($row);

if (!$row)
{
  header('Location: index.php');
  exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
  $message = $_POST['message'];
  $errors = array();

  // バリデーション
  if ($message == '')
  {
    $errors['message'] = 'メッセージが未入力です';
  }

  // バリデーション突破後
  if (empty($errors))
  {
    $dbh = connectDatabase();
    $sql = "update posts set message = :message, updated_at = now()
            where id = :id";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(":id", $id);
    $stmt->bindParam(":message", $message);
    $stmt->execute();

    header('Location: index.php');
    exit;

  }


}




?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>編集画面</title>
  </head>
  <body>
    <h1>投稿内容を編集する</h1>
    <p><a href="index.php">戻る</a></p>
    <form action="" method="post">
      <textarea name="message" cols="30" rows="5"><?php echo h($row['message']) ?></textarea>
        <?php if ($errors['message']) : ?>
          <?php echo h($errors['message']) ?>
        <?php endif ?>
      <input type="submit" value="編集する">
    </form>
  </body>
</html>