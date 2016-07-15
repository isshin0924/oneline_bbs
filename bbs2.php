<?php


$dsn = 'mysql:dbname=oneline_bbs;host=localhost';
$user = 'root';
$password = '';
$dbh = new PDO($dsn,$user,$password);
$dbh->query('SET NAMES utf8');


// 編集ボタン
$hensyuname = '';
$hensyucomment = '';
$id = '';
if (!empty($_GET['action']) && $_GET['action'] == 'edit') {
  
  $sql = 'SELECT * FROM `posts` WHERE `id` = ?';
  $data[] = $_GET['id'];
  // SQL実行
  $stmt = $dbh->prepare($sql);
  $stmt->execute($data);
  // データを取得
  $rec = $stmt->fetch(PDO::FETCH_ASSOC);
  // 格納
  $hensyuname = $rec['nickname'];
  $hensyucomment = $rec['comment'];
  $id = $rec['id'];
}


// POST送信処理
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

$sql = 'SELECT * FROM `posts` ORDER BY `created` DESC';
// SQL実行
$stmt = $dbh->prepare($sql);
$stmt->execute();

$data = array();

while (1) {
  $rec = $stmt->fetch(PDO::FETCH_ASSOC);
  if ($rec == false) {
    break;
  }
  // 1レコードずつデータを格納
  $data[] = $rec;
}
// データベースを切断
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
  <link rel="stylesheet" href="assets/css/style.css">
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
        <form action="bbs2.php" method="post">
          <!-- nickname -->
          <div class="form-group">
            <div class="input-group">
            <!-- requiredタグはその中身を指定したものに入れ替える --> <!-- inputの時はvalueに指定するがtextareaなどの時はタグの外に書く -->
              <input type="text" name="nickname" class="form-control" id="validate-text" placeholder="nickname" required value="<?php echo $hensyuname; ?>">
              <span class="input-group-addon danger"><span class="glyphicon glyphicon-remove"></span></span>
            </div>
          </div>
          <!-- comment -->
          <div class="form-group">
            <div class="input-group" data-validate="length" data-length="4">
            <!-- inputの時はvalueに指定するがtextareaなどの時はタグの外に書く -->
              <textarea type="text" class="form-control" name="comment" id="validate-length" placeholder="comment" required><?php echo $hensyucomment; ?></textarea>
              <span class="input-group-addon danger"><span class="glyphicon glyphicon-remove"></span></span>
            </div>
          </div>




          <!-- つぶやくボタン -->
          <?php if ($hensyuname == '' ): ?>
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


        <?php foreach($data as $data2): ?>
          <article class="timeline-entry">
              <div class="timeline-entry-inner">


                <a href="bbs2.php?action=edit&id=<?php echo $data2['id']; ?>">


                  <div class="timeline-icon bg-success">
                      <i class="entypo-feather"></i>
                      <i class="fa fa-cogs"></i>
                  </div>
                </a>
                  <div class="timeline-label">
                    <?php
                      // 日時型に変換する
                      $created = strtotime($data2['created']);
                      // 書式の変換
                      $created = date('Y/m/d', $created);
                    ?>
                      <h2><a href="#"><?php echo $data2['nickname']; ?></a> <span><?php echo $created; ?></span></h2>
                      <p><?php echo $data2['comment']; ?></p>
                      
                  </div>
              </div>
          </article>
        <?php endforeach; ?>



          <article class="timeline-entry begin">
              <div class="timeline-entry-inner">
                  <div class="timeline-icon" style="-webkit-transform: rotate(-90deg); -moz-transform: rotate(-90deg);">
                      <i class="entypo-flight"></i> +
                  </div>
              </div>
          </article>
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