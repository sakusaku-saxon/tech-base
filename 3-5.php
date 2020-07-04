<!--掲示板のパスワード機能-->
<?php
    $filename = "3-1.txt";

    //編集前データを入れる変数
    $editNo = "";
    $editName = "";
    $editComment = "";

    //名前もコメントも入力されているとき
    if($_POST["name"] && $_POST["comment"] && $_POST["pass"]){
        //入力文字列を取得
        $name = $_POST["name"];
        $comment = $_POST["comment"];
        $date = date("Y/m/d H:i:s");
        $pass = $_POST["pass"];

        //さらにhiddenで編集番号も送られた場合
        if($_POST["editno"]){
            $num = $_POST["editno"];
            $lines = file($filename);
            //全部上書きするためwモード
            $fp = fopen($filename, "w");

            foreach($lines as $line){
                //投稿番号を取得
                $pieces = explode("<>", $line);
                //編集対象番号でないとき
                if($pieces[0] != $num){
                    //ファイルに書き込む
                    fwrite($fp, $line);
                } else {
                    fwrite($fp, $num."<>".$name."<>".$comment."<>".$date."<>".$pass."<>".PHP_EOL);
                }
            }
            fclose(fp);
            $editNo = "";
        //新規投稿の場合
        } else {
            //ファイルを開く(追記モード)
            $fp = fopen($filename, "a");

            //ファイルの配列数を取得することで投稿番号を決める
            $num = count(file($filename)) + 1;

            fwrite($fp, $num."<>".$name."<>".$comment."<>".$date."<>".$pass."<>".PHP_EOL);
            fclose($fp);
        }
    }

    //削除番号が入力されたとき
    if($_POST["delete"]) {
        $delete = $_POST["delete"];
        $pass = $_POST["pass"];
        $lines = file($filename);

        //全部上書きするためwモード
        $fp = fopen($filename, "w");

        foreach($lines as $line){
            //投稿番号を取得
            $pieces = explode("<>", $line);
            //削除対象番号でないとき
            //または、削除対象だがパスが違うとき
            if($pieces[0] != $delete || $pieces[4] != $pass){
                //ファイルの配列数を取得することで投稿番号を決める
                $num = count(file($filename)) + 1;
    
                fwrite($fp, $num."<>".$pieces[1]."<>".$pieces[2]."<>".$pieces[3]."<>".$pieces[4]."<>".PHP_EOL);
                //削除対象番号は書き込まないので消える
            }
        }
        fclose($fp);
    }

    //編集番号が入力されたとき
    if($_POST["edit"]){
        $edit = $_POST["edit"];
        $lines = file($filename, FILE_IGNORE_NEW_LINES);
        $pass = $_POST["pass"];
        
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
    }
    ?>

<!DOCTYPE HTML>
<html>
<head>
        <meta charset="utf-8">
        <title>3-5</title>
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

    <?php
    //$filenameが存在するとき
    if(file_exists($filename)){
        $lines = file($filename, FILE_IGNORE_NEW_LINES);
        //配列を1行ずつ改行しながら表示
        foreach($lines as $line){
            $pieces = explode("<>", $line);
            echo "投稿番号:".$pieces[0]." 名前:".$pieces[1]." コメント:".$pieces[2]." 日付:".$pieces[3]."<br>";
        }
    }
    ?>
</body>
</html>