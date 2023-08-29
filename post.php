<?php
//教科書 p191  10-4
$name = $_POST['name']; // 名前

$gender = $_POST['gender'];
if ($gender == "man") {
    $gender = "男性";
} else if ($gender == "woman") {
    $gender = "女性";
} else {
    $gender = "不正な値です";
}

// forの中にifを入れる
$tmp_star = intval($_POST['star']);//数字 1-5
$star = ''; // 星を入れたい
// forの外側にif文を書く。$tmp_starの範囲のチェック
if ($tmp_star < 1 || $tmp_star > 5) {
    $star = "不正な値です";
} else {
    // 正常な値の時の処理
    for($i=0; $i < 5; $i++) {
        if ($i < $tmp_star) {
            $star .= '★';
        } else {
            $star .= '☆';
        }
        //  ↑ .= は付け足し
    }
}


$other = $_POST['other'];
?>
<html>
<head>
<meta charset="UTF-8">
<title>アンケート結果</title>
</head>
<body>
<h1>アンケート結果</h1>
<p>お名前: <?php echo $name;?></p>
<p>性別: <?php echo $gender;?></p>
<p>評価: <?php echo $star;?></p>
<p>ご意見: <?php echo nl2br($other);?></p>
</body>
</html>
