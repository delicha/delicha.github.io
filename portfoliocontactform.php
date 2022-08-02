<?php
session_start();
$mode = 'input';
$errmessage = array();
file_put_contents("./testlog.log",print_r($_POST,true));
if( isset($_POST['back']) && $_POST['back'] ){
	// 何もしない
} else if( isset($_POST['confirm']) && $_POST['confirm'] ){
	// 確認画面
	if( !$_POST['fullname'] ) {
		$errmessage[] = "名前を入力してください";
	} else if( mb_strlen($_POST['fullname']) > 100 ){
		$errmessage[] = "名前は100文字以内にしてください";
	}
	$_SESSION['fullname']	= htmlspecialchars($_POST['fullname'], ENT_QUOTES);

	if( !$_POST['email'] ) {
		$errmessage[] = "Eメールを入力してください";
	} else if( mb_strlen($_POST['email']) > 200 ){
		$errmessage[] = "Eメールは200文字以内にしてください";
	} else if( !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ){
		$errmessage[] = "メールアドレスが不正です";
	}
  $_SESSION['email']	= htmlspecialchars($_POST['email'], ENT_QUOTES);
  
  // if( $_POST['op'] === "---" ) {
	// 	$errmessage[] = "内容が選択されていません。";
	// }
	// $_SESSION['op']	= htmlspecialchars($_POST['op'], ENT_QUOTES);


	if( !$_POST['message'] ){
		$errmessage[] = "お問合せ内容を入力してください";
	} else if( mb_strlen($_POST['message']) > 500 ){
		$errmessage[] = "お問合せ内容は500文字以内にしてください";
	}
	$_SESSION['message'] = htmlspecialchars($_POST['message'], ENT_QUOTES);

	if( $errmessage ){
		$mode = 'input';
	} else {
	  $token = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM)); // php5のとき
	  //$token = bin2hex(random_bytes(32));                                   // php7以降
	  $_SESSION['token']  = $token;
		$mode = 'confirm';
	}
} else if( isset($_POST['send']) && $_POST['send'] ){
	// 送信ボタンを押したとき
  if( !$_POST['token'] || !$_SESSION['token'] || !$_SESSION['email'] ){
	  $errmessage[] = '不正な処理が行われました';
	  $_SESSION     = array();
	  $mode         = 'input';
  } else if( $_POST['token'] != $_SESSION['token'] ){
    $errmessage[] = '不正な処理が行われました';
    $_SESSION     = array();
    $mode         = 'input';
  } else {
	  $message = "お問合せを受け付けました \r\n"
	             . "名前: " . $_SESSION['fullname'] . "\r\n"
               . "email: " . $_SESSION['email'] . "\r\n"
              //  . "内容選択: " . $_SESSION['op'] . "\r\n"
	             . "お問合せ内容:\r\n"
	             . preg_replace( "/\r\n|\r|\n/", "\r\n", $_SESSION['message'] );
	  mail( $_SESSION['email'], 'お問合せありがとうございます', $message );
	  mail( 'info@provisioninc.biz', 'お問合せありがとうございます', $message );
	  $_SESSION = array();
	  $mode     = 'send';
  }
} else {
	$_SESSION['fullname'] = "";
  $_SESSION['email']    = "";
  // $_SESSION['op']       = "";
	$_SESSION['message']  = "";
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ポートフォリオサイト</title>
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css"
    integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
  <link rel="shortcut icon" href="images/favicon.ico" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
    integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css"
    integrity="sha512-1sCRPdkRXhBV2PBLUdRb4tMg1w2YPf37qatUFeS7zlBy7jJI8Lf4VHwWfZZfpXtYSLy85pkm9GaYVYMfw5BC1A=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="https://delicha.github.io/css/style.css">
</head>
<body>
<header class="ly_header">
    <nav class="navbar navbar-expand-lg navbar-light bg-light py-2 shadow-sm">
      <a class="navbar-brand hl_letterSpacing" href="https://provisioninc.biz/"><img src="images/provision-logo.png"
          alt="ポートフォリオサイト" width="25px" height="25px" class="mr-3 mt-n1">PORTFOLIO SITE</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown"
        aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" href="#me">About Me<span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item dropdown active">
            <a class="nav-link dropdown-toggle" href="service.html" id="navbarDropdownMenuLink" data-toggle="dropdown"
              aria-haspopup="true" aria-expanded="false">
              使用言語
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
              <a class="dropdown-item" href="#rails">Ruby/Rails</a>
              <a class="dropdown-item" href="#others">その他言語</a>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#app">開発アプリ</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#qiita">Qiita</a>
          </li>
          <li class="nav-item">
            <a class="nav-link btn btn-md btn-block btn-primary mx-auto d-block px-4 text-white"
              href="https://provisioninc.biz/portfoliocontactform.php">お問合せ</a>
          </li>
        </ul>
      </div>
    </nav>
    <div class="jumbotron jumbotron-fluid">
      <div class="container">
        <h1 class="display-4">SHOHEI YAMAMOTO</h1>
        <p class="lead">バックエンドエンジニアとして転職活動中！</p>
      </div>
    </div>
  </header>
    <main>
        <article>
          <div class="ly_cont hl_formSpacing">
              <div class="bl_header">
                  <svg width="3em" height="3em" viewBox="0 0 16 16" class="bi bi-envelope mb-3" fill="#4f8ce2" xmlns="http://www.w3.org/2000/svg">
                      <path fill-rule="evenodd" d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2zm13 2.383l-4.758 2.855L15 11.114v-5.73zm-.034 6.878L9.271 8.82 8 9.583 6.728 8.82l-5.694 3.44A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.739zM1 11.114l4.758-2.876L1 5.383v5.73z"/>
                  </svg>
                  <h2 class="bl_header_ttl mb-4">お問合せ</h2>
                  
                  <div class="bl_header_lead mb-5">「なにをどうしてよいかわからない」<br />というところからでもお気軽にご連絡ください。</div>
              </div>

       
<?php if( $mode == 'input' ){ ?>
  <!-- 入力画面 -->
    <form action="./contactform.php" method="post">

	<?php
	if( $errmessage ){
		echo '<div class="alert alert-danger" role="alert">';
		echo implode('<br>', $errmessage );
		echo '</div>';
	}
  ?>
      <div class="form-group">
        <input type="text"  class="form-control" placeholder="お名前"  name="fullname" value="<?php echo $_SESSION['fullname'] ?>">
      </div>
      <div class="form-group">
        <input type="text" class="form-control" placeholder="E-mail"  name="email" value="<?php echo $_SESSION['email'] ?>">
      </div>
      <!-- <div class="form-group">
        <select name="op" class="form-control">
          <option value="---">内容を選択してください</option>
          <option value="ホームページ制作のご相談">ホームページ制作のご相談</option>
          <option value="ホームページ制作のお見積もり">ホームページ制作のお見積もり</option>
          <option value="ホームページ制作スクール">ホームページ制作スクール</option>
          <option value="その他">その他</option>
        </select>
      </div> -->
      <div class="form-group">
        <textarea class="form-control" placeholder="お問合せ内容" name="message" rows="5"><?php echo $_SESSION['message'] ?></textarea>
      </div>
      <div>
        <button class="btn btn-md btn-block btn-primary mx-auto d-block w-50" type="submit" name="confirm" value="確認">確 認</button>
      </div>

    </form>

<?php } else if( $mode == 'confirm' ){ ?>
  <!-- 確認画面 -->

    <form action="./contactform.php" method="post">
      <table class="table">
        <tbody class="text-left">
          <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
          <tr>
            <th class="bg-light">名前</th>
            <td class="txt"><?php echo $_SESSION['fullname'] ?></td>
          </tr>
          <tr>
            <th class="bg-light">Eメール</th>
            <td class="txt"><?php echo $_SESSION['email'] ?></td>
          </tr>
          <!-- <tr>
            <th class="bg-light">内容選択</th>
            <td class="txt"><?php echo $_SESSION['op'] ?></td>
          </tr> -->
          <tr class="border-bottom">
            <th class="bg-light">お問合せ内容</th>
            <td class="txt"><?php echo $_SESSION['message'] ?></td>
          </tr>
        </tbody>
      </table>
      <div>
        <input class="btn btn-md btn-block btn-primary mx-auto d-block w-50" type="submit" name="back" value="戻る" />
        <input class="btn btn-md btn-block btn-primary mx-auto d-block w-50" type="submit" name="send" value="送信" />
      </div>
    </form>

<?php } else { ?>
  <!-- 完了画面 -->
  <p class="thankyou lead">送信致しました。<br />お問合せありがとうございました。</p>
<?php } ?>
</div>
        </article>
    </main>
    <footer class="py-md-3 text-white bg-dark fixed-bottom">
      山本昇平　&copy; 2022　All Rights Reserved
    </footer>
</body>
</html>


