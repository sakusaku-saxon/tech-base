<!--MySQL,PHP,HTMLを組み合わせて掲示板-->
<?php
//MySQLへの接続
$dsn = 'mysql:dbname=hoge;host=localhost';
	$user = 'hoge';
	$password = 'hoge';
	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

//bbsテーブルの作成
$sql = "CREATE TABLE IF NOT EXISTS bbs"
."("
."id INT AUTO_INCREMENT PRIMARY KEY,"
."name char(32),"
."comment TEXT,"
."pass char(32),"
."date  DATETIME"
.");";
$stmt = $pdo->query($sql);

//編集前データを入れる変数
$editNo = "";
$editName = "";
$editComment = "";

//名前もコメントも入力されているとき
if($_POST["name"] && $_POST["comment"] && $_POST["pass"]){
    //入力文字列を取得
    $name = $_POST["name"];
    $comment = $_POST["comment"];
    $date = date("Y-m-d H:i:s");
    $pass = $_POST["pass"];

    //さらにhiddenで編集番号も送られた場合
    if($_POST["editno"]){
        //tbtestテーブルのデータを更新
        $id = $_POST["editno"]; //変更する投稿番号
        $sql = 'UPDATE bbs SET name=:name,comment=:comment, date=:date, pass=:pass WHERE id=:id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
        $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
    //新規投稿の場合
    } else {
        //データを新規登録(プリペアドステートメントに)
        $sql = $pdo->prepare("INSERT INTO bbs(name, comment, date, pass) VALUES(:name, :comment, :date, :pass)");
        $sql->bindParam(':name', $name, PDO::PARAM_STR);
        $sql->bindParam(':comment', $comment, PDO::PARAM_STR);
        $sql->bindParam(':date', $date, PDO::PARAM_STR);
        $sql->bindParam(':pass', $pass, PDO::PARAM_STR);

        //プリペアドステートメントを実行
        $sql->execute();
    }
}

//削除番号が入力されたとき
if($_POST["delete"]){
	$delete = $_POST["delete"];
	$pass =$_POST["pass"];
	
	$sql = 'SELECT pass FROM bbs where id=:delete';
    $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
    $stmt->bindParam(':delete', $delete, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
    $stmt->execute();  // ←SQLを実行する。
    $results = $stmt->fetchAll(); 
    foreach($results as $row){
		if($row["pass"] == $pass){
    		$sql = 'delete from bbs where id=:delete';
        	$stmt = $pdo->prepare($sql);
        	$stmt->bindParam(':delete', $delete, PDO::PARAM_INT);
        	$stmt->execute();
		}
    }
}


//編集番号が入力されたとき
if($_POST["edit"]){
    $edit = $_POST["edit"];
    $pass = $_POST["pass"];
    
    $sql = 'SELECT * FROM bbs where id=:edit';
    $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
    $stmt->bindParam(':edit', $edit, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
    $stmt->execute();  // ←SQLを実行する。
    $results = $stmt->fetchAll(); 
    	foreach ($results as $row){
    		if($row['pass'] == $pass){
        		$editNo = $row['id'];
        		$editName = $row['name'];
        		$editComment =  $row['comment'];
    		}
	    }
        
    
    /*
    foreach($lines as $line){
        //投稿番号を取得
        $pieces = explode("<>", $line);
        //編集番号と一致したとき
        //パスも一致したとき
        if($pieces[0] == $edit && $pieces[4] == $pass){
            //編集番号のデータをフォームにセット
            $editNo = $pieces[0];
            $editName = $pieces[1];
            $editComment = $pieces[2];
            break;
        }
    }
    */
}
?>

<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8">
        <title>5-1</title>
    </head>
    <body>
            <!--名前とコメントを入力するフォーム-->
        <h1>投稿フォーム</h1>
        <form action="" method="post">
            <p><input type="hidden" name="editno" value="<?php echo $editNo;?>"></p>
            <p><input type="text" name="name" placeholder="名前" value="<?php echo $editName;?>"></p>
            <p><input type="text" name="comment" placeholder="コメント" value="<?php echo $editComment;?>"></p>
            <p><input type="text" name="pass" placeholder="パスワード"></p>
            <p><input type="submit" value="送信"></p>
        </form>
    
        <!--削除したい投稿の番号を入力するフォーム-->
        <h1>削除フォーム</h1>
        <form action="" method="post">
            <p><input type="number" name="delete" placeholder="削除対象番号"></p>
            <p><input type="text" name="pass" placeholder="パスワード"></p>
            <p><input type="submit" value="削除"></p>
        </form>
    
        <!--編集したい投稿の番号を入力するフォーム-->
        <h1>編集フォーム</h1>
        <form action="" method="post">
            <p><input type="number" name="edit" placeholder="編集対象番号"></p>
            <p><input type="text" name="pass" placeholder="パスワード"></p>
            <p><input type="submit" value="編集"></p>
        </form>
    </body>
</html>

<?php
//掲示板データを全て表示
//tbtestテーブルの全内容を表示
$sql = 'SELECT * FROM bbs';
$stmt = $pdo->query($sql);
$results = $stmt->fetchAll();
foreach ($results as $row){
	//$rowの中にはテーブルのカラム名が入る
	echo '投稿番号:'.$row['id'].' ';
	echo '名前:'.$row['name'].' ';
	echo 'コメント:'.$row['comment'].' ';
	echo '日付:'.$row['date'].'<br>';
}
echo "<hr>";

?>
