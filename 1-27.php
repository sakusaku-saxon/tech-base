<!DOCTYPE HTML>
<!--総まとめ フォームで数字を送ってファイルに記録-->

<html>
    <head>
        <meta charset="utf-8">
        <title>1-27</title>
    </head>
    
    <body>
        <!--数字を入力、送信するフォーム-->
        <form action="" method="post">
            <input type="number" name="num" placeholder="30">
            <input type="submit" name="送信">
        </form>
        
        <?php
            $filename = "1-27.txt";
            $num = $_POST["num"];
            
            //フォームに数字が入力されたとき
            if($num != NULL){
                // 追記モードでファイルを開けてフォームの内容を書き込む
                $fp = fopen($filename, "a");
                
                fwrite($fp, $num.PHP_EOL);
                fclose($fp);
                echo "書き込み成功!<br>";
            }
            
            //$filenameが存在するとき
            if(file_exists($filename)){
                //ファイルを一旦閉じ、file関数で開けて中身を表示
                $lines = file($filename, FILE_IGNORE_NEW_LINES);
                //改行を飛ばして読み込んだので1行ずつ改行を挟んで表示
                foreach($lines as $line){
                    // 3の倍数はFizz,5の倍数はBuzz,3と5の倍数はFizzBuzz
                    if($line % 3 == 0 && $line % 5 == 0){
                        echo "Fizzbuzz<br>";
                    } else if($line % 3 == 0){
                        echo "Fizz<br>";
                    } else if($line % 5 == 0){
                        echo "Buzz<br>";
                    } else {
                        echo $line."<br>";
                    }
                }
            }
        ?>
    </body>
</html>