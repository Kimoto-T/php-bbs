<?php

// エスケープ処理
function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

date_default_timezone_set("Asia/Tokyo");

$comment_array = array();
$pdo = null;
$stmt = null;

// DB接続
try {
    $pdo = new PDO('mysql:host=localhost;dbname=bbs-yt', "root", "root");
} catch (PDOException $e) {
    echo $e->getMessage();
}

//フォームを打ち込んだ時

if (!empty($_POST["submitButton"])) {
    $postDate = date("Y-m-d H:i:s");

    try {

        $stmt = $pdo->prepare("INSERT INTO `bbs-table` (`username`, `comment`, `postDate`) VALUES (:username, :comment, :postDate);");
        $stmt->bindParam(':username', $_POST['username'], PDO::PARAM_STR);
        $stmt->bindParam(':comment', $_POST['comment'], PDO::PARAM_STR);
        $stmt->bindParam(':postDate', $postDate, PDO::PARAM_STR);

        $stmt->execute();
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}







// DBからコメントデータを取得する
$sql = "SELECT `id`, `username`,`comment`, `postDate` FROM `bbs-table`;";
$comment_array = $pdo->query($sql);

//DBの接続を閉じる
$pdo = null;


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="x-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device, initial-scale=1.0">
    <title>PHP掲示板</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <hi class="title">PHPで掲示板アプリ</hi>
    <hr>
    <div class="boardWrapper">
        <section>
            <?php foreach ($comment_array as $comment) : ?>
                <article>
                    <div class="wrapper">
                        <div class="nameArea">
                            <span>名前 : </span>
                            <p class="username"><?php echo h($comment["username"]); ?></p>
                            <time><?php echo $comment["postDate"]; ?></time>
                        </div>
                        <p class="comment"><?php echo h($comment["comment"]); ?></p>
                    </div>
                </article>
            <?php endforeach; ?>
        </section>

        <form class="formWrapper" method="POST">
            <div>
                <input type="submit" value="書き込む" name="submitButton">
                <label for="">名前：</label>
                <input type="text" name="username">
            </div>
            <div>
                <textarea class="commentTextArera" name="comment"></textarea>
            </div>
        </form>
    </div>

</body>

</html>