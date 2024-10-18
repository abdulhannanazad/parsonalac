<?php include("inc/connection.php"); ?>

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="css/login.css">
<title>Login</title>

<body>
  <nav>
    <div class="logo">
      <img src="image/logo.jpg">
    </div>
    <div style="margin-left: 60px;">
      <a href="index.php">home</a>
    </div>
    <div><a href="settings.php">settings</a></div>
  </nav>
  <style>
    .logo {
      position: fixed;
      top: 10px;
      left: 5px;
      border-radius: 0 0 50px 50px;
    }

    nav {
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 1px solid black;
      padding: 10px 5px;
      background-color: cyan;
    }

    img {
      width: 50px;
      height: 70px;
      border-radius: 0 0 50px 50px;
      box-shadow: 1px 1px 5px black;
    }
  </style>

  <div class="poster">
    <div class="title">
      <h2>personal</h2>
      <h2>account system</h2>
    </div>

    <div class="banar">
      <div class="card">
        <h3>no account ?</h3>
        <p>jodi cho tahole onumoti sapekkhe account khulte</p>
        <a href="#">ragister</a>
      </div>

      <div class="card">
        <h3>abedon korun</h3>
        <p>ai systeme kaj korar jonno abedon korun</p>
        <a href="#">applay</a>
      </div>
    </div>
  </div>
  <style>
    .card h3,
    .card p {
      color: white;
    }

    .banar card {
      width: 99%;
    }

    .banar {
      display: grid;
      grid-template-columns: 50% 50%;
      margin: 10px;
    }

    .poster h2 {
      font-size: 30px;
      color: white;
    }

    .poster a {
      border: 1px solid white;
      padding: 10px;
      margin: 10px 0 30px 0;
      display: inline-block;
      color: white;
    }

    .poster {
      padding-top: 60px;
      text-align: right;
      background-image: url('image/dashboard.jpg');
      background-position: center;
      background-size: cover;
    }
  </style>

  <div class="info">
    <div class="log">
      <h3>login</h3>
      <p>apnar jodi account thake tahole akhan theke login korun </p>
      <form method="post" action="login.php">
        <input name="name" value="hannan" required placeholder="user name">
        <input name="password" value="0000" required placeholder="password">
        <button type="submit" name="login">Login</button>
        <a href="#">forget password ?</a>
      </form>
      <div>
        <?php if (!empty($msg)) {
          echo $msg;
        } ?>
      </div>
    </div>

    <div class="nolage">
      <h3>genarel queschen</h3>
      <a href="#">how do posting exp --></a>
      <a href="#">how do posting income --></a>
      <a href="#">how do creat report --></a>
    </div>
  </div>
  <style>
    .nolage a {
      display: block;
      padding: 5px;
      border-radius: 5px;
      background: blue;
      color: white;
      margin-bottom: 5px;
    }

    button {
      border-radius: 5px;
      padding: 5px;
    }

    input {
      display: block;
      padding: 5px;
      border-radius: 5px;
      margin: 5px 0;
    }

    .info h3 {
      margin-bottom: 5px;
    }

    .info {
      display: grid;
      grid-template-columns: 50% 50%;
      justify-content: space-between;
      padding: 5px;
    }
  </style>
</body>

<?php if (isset($_POST['login'])) {
  $aa = $_POST['name'];
  $bb = $_POST['password'];
  $qry = "SELECT * FROM `userlog` WHERE `name`='$aa' AND `password`='$bb'";
  $rsl = mysqli_query($con, $qry);
  if (mysqli_num_rows($rsl) > 0) {
    echo ("<script>window.location.href = 'index.php';</script>");
  } else {
    $msg = "Information Rong";
  }
}
?>