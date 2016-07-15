<?php
  // ここにDBに登録する処理を記述する
	$dsn = 'mysql:dbname=oneline_bbs;host=localhost';
    $user ='root';
    $password = '';
    $dbh = new PDO($dsn, $user, $password);
    $dbh->query('SET NAMES utf8');
    //issetはその関数が存在するかどうか
    //emptyは空かどうか判断する
    if (isset($_POST) && !empty($_POST)) {
    	$sql = 'INSERT INTO `posts` (`nickname`, `comment`, `created`)VALUES (?,?,now())';

        $param[] = $_POST['nickname'];
        $param[] = $_POST['comment'];


        $stmt = $dbh->prepare($sql);
        $stmt->execute($param);
    	# code...
    }
    //↓がsql（命令）文
		$sql = 'SELECT*FROM `posts` ORDER BY `created` DESC';
		$stmt = $dbh->prepare($sql);
		$stmt->execute();

		$posts = array();

	while (1) {
	# code...
	$rec = $stmt->fetch(PDO::FETCH_ASSOC);
	if ($rec==false) {
		# code...
		break;
	}
			
	    $posts[] = $rec;

}


    $dbh = null;
?>


<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>セブ掲示版</title>
</head>
<body>
    <form method="post" action="">
      <p><input type="text" name="nickname" placeholder="nickname"></p>
      <p><textarea type="text" name="comment" placeholder="comment"></textarea></p>
      <p><button type="submit" >つぶやく</button></p>
    </form>
    <!-- ここにニックネーム、つぶやいた内容、日付を表示する -->
	<?php  ?>
	<ul>
		<?php 
			foreach ($posts as $post_each) {
				# code...
				echo "<li>";
				echo "nickname".$post_each['nickname'];
				echo "comment".$post_each['comment'];
				echo "created".$post_each['created'];
				echo "</li>";
			}
		 ?>
	</ul>

    <!-- <?php 
    //var_dump($posts); 
   //  echo $rec['nickname'];
			// echo $rec['comment'];
			// echo $rec['created'];
			// echo '<br>';

    ?> -->

</body>
</html>