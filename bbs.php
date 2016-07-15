<?php
  // ここにDBに登録する処理を記述する
  $dsn = 'mysql:dbname=LAA0763590-onelinebbs;host=mysql113.phy.lolipop.lan';
    $user ='LAA0763590';
    $password = 'Isshintakatsu092';
    $dbh = new PDO($dsn, $user, $password);
    $dbh->query('SET NAMES utf8');

    $editname = '';
$editcomment = '';
$id = '';
if (!empty($_GET['action']) && $_GET['action'] == 'edit') {
  // 該当のデータを取得する
  $sql = 'SELECT * FROM `posts` WHERE `id` = ?';
  $data[] = $_GET['id'];
  // SQL実行
  $stmt = $dbh->prepare($sql);
  $stmt->execute($data);
  // データを取得
  $rec = $stmt->fetch(PDO::FETCH_ASSOC);
  // 取得した値を格納
  $editname = $rec['nickname'];
  $editcomment = $rec['comment'];
  $id = $rec['id'];
}
// -----------------------------
// POST送信された時の処理
if (!empty($_POST)) {
  if (empty($_POST['id'])) {
    // データを登録する
    $sql = 'INSERT INTO `posts`(`nickname`, `comment`, `created`) VALUES (?, ?, now())';
    $data[] = $_POST['nickname'];
    $data[] = $_POST['comment'];
  } else {
    // データを更新する
    $sql = 'UPDATE `posts` SET `nickname`=?,`comment`=? WHERE `id` = ?';
    $data[] = $_POST['nickname'];
    $data[] = $_POST['comment'];
    $data[] = $_POST['id'];
  }
  // SQL実行
  $stmt = $dbh->prepare($sql);
  $stmt->execute($data);
}


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

  <!-- CSS -->
  <link rel="stylesheet" href="assets/css/bootstrap.css">
  <link rel="stylesheet" href="assets/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="assets/css/form.css">
  <link rel="stylesheet" href="assets/css/timeline.css">
  <link rel="stylesheet" href="assets/css/main.css">
</head>
<body>
  <!-- ナビゲーションバー -->
  <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
          <!-- Brand and toggle get grouped for better mobile display -->
          <div class="navbar-header page-scroll">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                  <span class="sr-only">Toggle navigation</span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" href="#page-top"><span class="strong-title"><i class="fa fa-linux"></i> Oneline bbs</span></a>
          </div>
          <!-- Collect the nav links, forms, and other content for toggling -->
          <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
              <ul class="nav navbar-nav navbar-right">
              </ul>
          </div>
          <!-- /.navbar-collapse -->
      </div>
      <!-- /.container-fluid -->
  </nav>

  <!-- Bootstrapのcontainer -->
  <div class="container">
    <!-- Bootstrapのrow -->
    <div class="row">

      <!-- 画面左側 -->
      <div class="col-md-4 content-margin-top">
        <!-- form部分 -->
        <form action="bbs.php" method="post">
          <!-- nickname -->
          <div class="form-group">
            <div class="input-group">
              <input type="text" name="nickname" class="form-control" id="validate-text" placeholder="nickname" required>
              <span class="input-group-addon danger"><span class="glyphicon glyphicon-remove"></span></span>
            </div>
          </div>
          <!-- comment -->
          <div class="form-group">
            <div class="input-group" data-validate="length" data-length="4">
              <textarea type="text" class="form-control" name="comment" id="validate-length" placeholder="comment" required></textarea>
              <span class="input-group-addon danger"><span class="glyphicon glyphicon-remove"></span></span>
            </div>
          </div>
          <!-- つぶやくボタン -->
          <?php if ($editname == ''): ?>
            <button type="submit" class="btn btn-primary col-xs-12" disabled>つぶやく</button>
          <?php else: ?>
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <button type="submit" class="btn btn-primary col-xs-12" disabled>更新する</button>
          <?php endif; ?>
        </form>
      </div>

      <!-- 画面右側 -->
      <div class="col-md-8 content-margin-top">
        <div class="timeline-centered">
        <?php 
          foreach ($posts as $post_each) {
            # code...
          
         ?>
          <article class="timeline-entry">
              <div class="timeline-entry-inner">
              <a href="bbs.php?action=edit id=<?php echo $post_each['id']; ?>">
                  <div class="timeline-icon bg-success">
                      <i class="entypo-feather"></i>
                      <i class="fa fa-cogs"></i>
                  </div>
                  <div class="timeline-label">
                      <h2><a href="#"><?php echo $post_each['nickname'];?></a> 
                      <span><?php 
                      //日付型に変換
                      $created = strtotime($post_each['created']);
                      $str_created = date('Y/m/d',$created);
                      echo $str_created;

                      ?>;</span></h2>
                      <p><?php echo $post_each['comment'];?></p>
                  </div>
              </div>
          </article>

          <article class="timeline-entry begin">
              <div class="timeline-entry-inner">
                  <div class="timeline-icon" style="-webkit-transform: rotate(-90deg); -moz-transform: rotate(-90deg);">
                      <i class="entypo-flight"></i> +
                  </div>
              </div>
          </article>
          <?php } ?>


        </div> 
      </div>

    </div>
  </div>

  <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <!-- Include all compiled plugins (below), or include individual files as needed -->
  <script src="assets/js/bootstrap.js"></script>
  <script src="assets/js/form.js"></script>
</body>
</html>



