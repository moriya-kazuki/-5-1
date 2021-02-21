<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>mission_5-1</title>
        <style>
            .class{
                font-weight:bold;
            }
        </style>
    </head>
    <body>
    <form action="" method="post">
            <span class="class1" style="font-size: 40px">最近あった面白かったこと</span><br>
            <hr>
            <!--入力フォーム-->
            <label>名前：</label><input type="text" name="name">
            <label>編集：</label><input type="num" name="edit" placeholder="編集したい投稿番号"><br>
            <label>PASS:</label><input type="password" name="pass" placeholder="パスワードを設定">
            <span style="font-size: 5px">※パスワードを設定しない場合は0000になります</span><br>
            <label>コメ：<input type="text" size="60" name="com" placeholder="ここにコメントを記入してください"  ></label>
            <input type="submit" name="submit" value="書き込む" ><br><br>
            <!--消去フォーム-->
            <label>削除：</label><input type="num" name="delete" placeholder="消去したい投稿番号">
            <label>PASS:</label><input type="password" name="dpass">
            <input type="submit" value="削除"><br>
            <hr>
        </form>
    </body>
<?php
    // DB接続設定
	$dsn = 'データベース名';
	$user = 'ユーザー名';
	$password = 'パスワード';
	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

    //テーブル作成(なかった場合)
	$sql = "CREATE TABLE IF NOT EXISTS tbt5"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name char(32),"
    . "comment TEXT,"
    . "dt datetime,"
    . "pass char(32)"
    .");";
    $stmt = $pdo->query($sql);

    //変数定義
        //名前とコメント
        $name=$_POST["name"];
        $comment=$_POST["com"];
        $pass = $_POST["pass"];
        //日時
        date_default_timezone_set("Asia/Tokyo");
        $date=date("Y/m/d H:i:s");
        //消去・編集
        $edit = $_POST["edit"];
        $del = $_POST["delete"];
        $dpass = $_POST["dpass"];
    
    //投稿削除
    if(!empty($del)&&!empty($dpass)){
        $id = $del;
        $dpassword = $dpass;
	    $sql = 'delete from tbt5 where id=:id and pass=:pass';
	    $stmt = $pdo->prepare($sql);
	    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':pass', $dpassword, PDO::PARAM_INT);
	    $stmt->execute();
    }

    //名前のあるなし
    if(empty($name)){
        $name="名無し";
    }
    //パスワードのあるなし
    if(empty($pass)){
        $pass=0000;
    }

    //投稿編集
    if(!empty($edit)&&!empty($comment)){
        $id = $edit; //変更する投稿番号
	    $Ename = $name;
	    $Ecomment = $comment;
        $Epass = $pass;
	    $sql = 'UPDATE tbt5 SET name=:name,comment=:comment WHERE id=:id and pass=:pass';
	    $stmt = $pdo->prepare($sql);
	    $stmt->bindParam(':name', $Ename, PDO::PARAM_STR);
	    $stmt->bindParam(':comment', $Ecomment, PDO::PARAM_STR);
	    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':pass', $Epass, PDO::PARAM_INT);
	    $stmt->execute();
    }else{
        //サーバーへの書き込み
        if(!empty($comment)){
            $sql = $pdo -> prepare("INSERT INTO tbt5 (name, comment, dt, pass) VALUES (:name, :comment, :dt, :pass)");
	        $sql -> bindParam(':name', $name1, PDO::PARAM_STR);
	        $sql -> bindParam(':comment', $comment1, PDO::PARAM_STR);
            $sql -> bindParam(':dt', $dt1,PDO::PARAM_STR);
            $sql -> bindParam(':pass', $pass1,PDO::PARAM_STR);
	        $name1 = $name;
	        $comment1 = $comment;
            $dt1=$date;
            $pass1=$pass;
	        $sql -> execute();
        }
    }


    
    //データ出力
    $sql = 'SELECT * FROM tbt5';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
		//$rowの中にはテーブルのカラム名が入る
		echo $row['id'].',';
		echo $row['name'].',';
		echo $row['comment'].',';
		echo $row['dt'].'<br>';
	echo "<hr>";
	}
?>
</html>