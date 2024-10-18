<?php include("inc/connection.php");
if (isset($_POST['subName'])) {
  $catagory = $_POST['subName'];
  $_SESSION['sName'] = $_POST['subName'];

} elseif (!empty($_SESSION['sName'])) {
  $catagory = $_SESSION['sName'];

} else {
  $catagory = "all";
}

if ($catagory == "all") {
  $rqr = "SELECT * FROM `catagory` order by mother asc";

} else {
  $rqr = "SELECT * FROM `catagory` WHERE `mother`='$catagory'";

}
$rslt = mysqli_query($con, $rqr);
?>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>create catagory</title>

<body>
  <div class="slideBar">
    <?php include("inc/linkbar.php"); ?>
  </div>

  <div class="middle">
    <div class="labale_bar">create catagory</div>

    <div class="form_div">
      <?php if (isset($_GET['brows'])) {
        $rwj = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM `catagory` where `sl`='{$_GET['sl']}'"));
      } ?>

      <form method="post" autocomplete="off">
        <input name="srl" value="<?= $rwj['sl'] ?? ''; ?>" hidden />
        <input name="aa" value="<?= $rwj['sub'] ?? ''; ?>" placeholder="sub namme" required autofocus>
        <select name="bb">
          <option value="<?= $rwj['mother'] ?? '-mother-'; ?>">
            <?= $rwj['mother'] ?? '-mother-'; ?>
          </option>
          <option value="income">income</option>
          <option value="expense">expense</option>
          <option value="savings">savings</option>
          <option value="lend/get">lend/get</option>
        </select>
        <select name="cc">
          <option value="<?= $rwj['status'] ?? 'active'; ?>">
            <?= $rwj['status'] ?? 'active'; ?>
          </option>
          <option value="active">active</option>
          <option value="d-active">d-active</option>
        </select>
        <button type="submit" name="savebtn">save</button>
        <?php if (isset($_GET['brows'])) {
          echo "<button name='updatebtn'>update</button>
        <button name='cecuritybtn'>delete</button>";
        } ?>
      </form>
    </div>
  </div>

  <div class="rightdiv">
    <h1>catagory record</h1>
    <form method="post" style="text-align: center;">
      <select name="subName" onchange="this.form.submit()">
        <option value="<?= $catagory; ?>">
          <?= $catagory; ?>
        </option>
        <option value="all">all</option>
        <option value="income">income</option>
        <option value="expense">expense</option>
        <option value="savings">savings</option>
        <option value="lend/get">lend/get</option>
      </select>
    </form>

    <div class="tblHolder">
      <table>
        <tr>
          <th>sub</th>
          <th>mother</th>
          <th>status</th>
          <th>edit</th>
        </tr>
        <?php while ($rw = mysqli_fetch_assoc($rslt)): ?>
          <tr>
            <td>
              <?= $rw['sub']; ?>
            </td>
            <td>
              <?= $rw['mother']; ?>
            </td>
            <td>
              <?= $rw['status']; ?>
            </td>
            <td style="width: 30px;"><a href="catagory.php?sl=<?= $rw['sl']; ?>&brows">edit</a></td>
          </tr>
        <?php endwhile; ?>
      </table>
    </div>
  </div>

  <script src="java/jquery.js"></script>
  <script src="java/main.js"></script>
</body>

<?php
if ($_SERVER['REQUEST_METHOD'] === "POST") {
  $srl = $_POST['srl'] ?? '';
  $sub = $_POST['aa'] ?? '';
  $mother = $_POST['bb'] ?? '';
  $status = $_POST['cc'] ?? '';
  $dd = $_POST['dd'] ?? '';
  $name = $_POST['name'] ?? '';
  $password = $_POST['password'] ?? '';
}

if (isset($_POST['savebtn'])) {
  $qry = "SELECT * FROM `catagory` WHERE `sub`='$sub' and `mother`='$mother' and `status`='$status'";
  $qrs = "INSERT INTO `catagory`(`sub`, `mother`, `status`) VALUES ('$sub','$mother','$status')";
  checkData($con, $qry, $qrs);
  echo ("<script>window.location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>");
}

if (isset($_POST['updatebtn'])) {
  $qry = "SELECT * FROM `catagory` WHERE `sub`='$sub' && `mother`='$mother' && `status`='$status'";
  if (checkUpdate($con, $qry)) {
    echo ("<script>window.location.href='catagory.php'; </script>");
  } else {

    $rqr = "UPDATE `catagory` SET `sub`='$sub',`mother`='$mother',`status`='$status' WHERE `sl`='$srl'";
    updateData($con, $rqr);
    echo ("<script>window.location.href='" . $_SERVER['HTTP_REFERER'] . "'; </script>");
  }
}

if (isset($_POST['cecuritybtn'])) {
  echo "<div class='cecurity-form'>
  <form method='post'>
    <h2>userName and password</h2>
    <input name='srl' value='" . $srl . "' readonly>
    <input name='name' value='hannan'/>
    <input name='password' value='0000'/>
    <button name='deletebtn'>yes</button>
    <button name='blank'>no</button>
  </form>
  <div>";
}

if (isset($_POST['deletebtn'])) {
  if (mysqli_query($con, "DELETE FROM `catagory` WHERE `sl`='$srl'")) {
    echo ("<script>window.location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>");
  } else {
    echo "sarver down";
  }
}
?>
</div>

<style>
  #list7 {
    background-color: white;
    color: black;
  }
</style>