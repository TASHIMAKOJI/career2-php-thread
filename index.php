<html>
<head><title>掲示板</title></head>
<body>

<h1>掲示板App</h1>

<h2>投稿フォーム</h2>

<form method="POST" action="<?php print($_SERVER['PHP_SELF']) ?>">
    <input type="text" name="personal_name" placeholder="名前" required><br><br>
    <textarea name="contents" rows="8" cols="40" placeholder="内容" required>
</textarea><br><br>
    <input type="submit" name="btn" value="投稿する">
</form>

<form method="POST" action="<?php print($_SERVER['PHP_SELF']) ?>">
    <input type="hidden" name="method" value="DELETE">
    <button type="submit">投稿をすべて削除する</button>
</form>
<h2>スレッド</h2>

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

</body>
</html>