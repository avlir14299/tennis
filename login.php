<?php
  session_start();

  if (isset($_SESSION['id'])){
    // セッションにユーザIDがある=ログインしている
    // ログイン済みならトップページに遷移する
    header('Location: index.php');
  } else if (isset($_POST['name']) && isset($_POST['password'])){
    // ログインしていないがユーザ名とパスワードが送信されたとき
    // DBに接続
    $dsn = 'mysql:host=localhost;dbname=tennis;charset=utf8';
    $user = 'tennisuser';
    $password = 'password';

    try {
      $db = new PDO($dsn, $user, $password);
      $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

      $stmt = $db->prepare("SELECT users.id, users.name AS login_name,profiles.name AS name
      FROM users,profiles
      WHERE users.id=profiles.id AND  users.name=:name AND users.password=:pass");


      $stmt->bindParam(':name', $_POST['name'], PDO::PARAM_STR);
      $stmt->bindParam(':pass', hash("sha256", $_POST['password']), PDO::PARAM_STR);

      $stmt->execute();

      if ($row = $stmt->fetch()){
        session_regenerate_id(true);
        $_SESSION['id'] = $row['id'];
        $_SESSION['name'] = $row['name'];
        header('Location: index.php');
        exit();
      } else {
        // 1レコードも取得できなかったとき
        // ユーザ名・パスワードが間違っている可能性あり
        // もう一度ログインフォームを表示
        header('Location: login.php');
        exit();
      }
    } catch (PDOException $e){
      exit('エラー：' . $e->getMessage());
    }
  }
  // ログインしていない場合は以降のログインフォームを表示する
?>

<?php
  $dsn = 'mysql:host=localhost;dbname=tennis;charset=utf8';
$user = 'tennisuser'; // データベースのユーザー名
$password = 'password'; // データベースのパスワード
try {
  $db = new PDO($dsn, $user, $password);
      $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
// 新しいユーザーの情報

if(isset($_POST['adduser'])){
  $insertQuery = "INSERT INTO users ( name, password) VALUES (:name, :password)";
  $statement = $db->prepare($insertQuery);

    // パラメータをバインド
    $newName = $_POST['newname'];
    $newPass = hash("sha256",$_POST['newpassword']);
    $statement->bindParam(':name', $newName,PDO::PARAM_STR);
    $statement->bindParam(':password', $newPass,PDO::PARAM_STR);
    $statement->execute();

    $id = $db->lastInsertId();

    $statement = $db->prepare("INSERT INTO profiles (id, name) VALUES (:id, :name);");
  $statement->bindParam(':id',$id, PDO::PARAM_INT);
  $statement->bindParam(':name', $newName, PDO::PARAM_STR);
  $statement->execute();


  echo "新しいユーザーが登録されました。";
}
} catch (PDOException $e) {
    die("エラー: " . $e->getMessage());
}

// データベース接続を閉じる
$db = null;

?>
<!doctype html>
<html lang="ja" >
  <head>
    <title>サークルサイト</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <style type="text/css">
      form {
        width: 100%;
        max-width: 330px;
        padding: 15px;
        margin: auto;
        text-align: center;
      }
      #name {
        margin-bottom: -1px;
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 0;
      }
      #password {
        margin-bottom: 10px;
        border-top-left-radius: 0;
        border-top-right-radius: 0;
      }
    </style>
  </head>
  <body>

    <main role="main" class="container" style="padding:60px 15px 0">
      <div>
        <!-- ここから「本文」-->

        <form action="login.php" method="post">
          <h1>サークルサイト</h1>
          <label class="sr-only">ユーザ名</label>
          <input type="text" id="name" name="name" class="form-control" placeholder="ユーザ名">
          <label class="sr-only">パスワード</label>
          <input type="password" id="password" name="password" class="form-control" placeholder="パスワード">
          <input type="submit" class="btn btn-primary btn-block" value="ログイン">
        </form>

        <form action="login.php" method="post">
          <h1>新規登録</h1>
          <label class="sr-only">ユーザ名</label>
          <input type="text" id="newname" name="newname" class="form-control" placeholder="ユーザ名">
          <label class="sr-only">パスワード</label>
          <input type="password" id="newpassword" name="newpassword" class="form-control" placeholder="パスワード">
          <input type="submit" name="adduser" class="btn btn-primary btn-block" value="登録">
        </form>

        <!-- 本文ここまで -->
      </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="/docs/4.5/assets/js/vendor/jquery-slim.min.js"><\/script>')</script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
