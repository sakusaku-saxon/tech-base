<!DOCTYPE HTML>
<!--テキストファイルを配列に読み込んでブラウザに表示-->

<html>
    <head>
        <meta charset="utf-8">
        <title>2-4</title>
    </head>
    
    <body>
        <!--テキストを送るフォーム-->
        <form action="" method="post">
            <input type="text" name="str" placeholder="コメント">
            <input type="submit" name="送信">
        </form>
        
        <?php
            //テキストファイルを指定
            $filename = "2-4.txt";
            
            //フォームが空でないとき
            if(isset($_POST["str"])){
                $str = $_POST["str"];
                
                // 追記モードでファイルを開けてフォームの内容を書き込む
                $fp = fopen($filename, "a");    
                fwrite($fp, $str.PHP_EOL);
                fclose($fp);
            }
            
            //$filenameが存在するとき
            if(file_exists($filename)){
                //file関数で1行ずつ配列に格納
                $array = file($filename);
                
                //配列を1行ずつ改行しながら表示
                for($i = 0; $array[$i] != NULL; $i++){
                    echo $array[$i]."<br>";
                }
            }
        ?>
    </body>
</html>