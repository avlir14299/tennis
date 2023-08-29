<?php
// 教科書　p182 リスト 10-1
//$page = $_GET['page'];
//$param = $_GET['param'];
//echo 'リクエストされたページは' . $page . 'です';
//echo 'パラメータは' . $param . 'です';
// http://localhost:8888/tennis/get.php?page=5&param=abc にアクセスする

//教科書 p184 10-2
foreach ($_GET as $key => $value) {
    echo 'キー:' . $key . '<br>';
    echo '値:' . $value . '<br><br>';
}

$_POST