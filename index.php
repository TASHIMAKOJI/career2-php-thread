<html>
<head><title>掲示板</title></head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous"> 

<body>

 
<h1 class="text-center">掲示板App</h1>
<br><br><br><br>
<h2 class="text-center">投稿フォーム</h2>

<form method="POST" action="<?php print($_SERVER['PHP_SELF']) ?>" class="text-center">
    <input type="text" name="personal_name" placeholder="名前" required><br><br>
    <textarea name="contents" rows="8" cols="40" placeholder="内容" required>
</textarea><br><br>
    <input type="submit" name="btn" value="投稿する" class="btn btn-primary">
</form>

<form method="POST" action="<?php print($_SERVER['PHP_SELF']) ?>" class="text-center">
    <input type="hidden" name="method" value="DELETE">
    <button type="submit" class="btn btn-danger">投稿をすべて削除する</button>
</form>
<br><br><br><br>
<h2 class="text-center">スレッド</h2>

<?php

const THREAD_FILE = 'thread.txt';

function deleteData()
{
    //ファイルを削除する
    file_put_contents(THREAD_FILE, "");
}
function readData() 
{
    // ファイルが存在しなければデフォルト空文字のファイルを作成する
    if (! file_exists(THREAD_FILE)) 
    {
        $fp = fopen(THREAD_FILE, 'w');
        fwrite($fp, '');
        fclose($fp);
    }

    $thread_text = file_get_contents(THREAD_FILE);
    echo $thread_text;
}

function writeData() 
{
    //date_default_timezone_set('Asia/Tokyo');
    //date("Y/m/d H:i:s") . "\n";
    $personal_name = $_POST['personal_name'];
    $contents = $_POST['contents'];
    $contents = nl2br($contents);

    $data = "<hr>\n";
    $data = $data."<p>投稿日時".date("Y/m/d H:i:s") ."</p>\n";
    $data = $data."<p>投稿者:".$personal_name."</p>\n";
    $data = $data."<p>内容:</p>\n";
    $data = $data."<p>".$contents."</p>\n";


    $fp = fopen(THREAD_FILE, 'a');

    if ($fp)
    {
        if (flock($fp, LOCK_EX))
        {
            if (fwrite($fp,  $data) === FALSE)
            {
                print('ファイル書き込みに失敗しました');
            }

            flock($fp, LOCK_UN);
        }
        else
        {
            print('ファイルロックに失敗しました');
        }
    }

    fclose($fp);

    // ブラウザのリロード対策
    $redirect_url = $_SERVER['HTTP_REFERER'];
    header("Location: $redirect_url");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") 
{
    if(isset($_POST["method"]) && $_POST["method"] === "DELETE")
    {
        deleteData();

    }
    else
        writeData();
}



readData();




?>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</body>
</html>