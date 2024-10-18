<?php include("inc/connection.php");

if (!empty($_SESSION['lgdateForm'])) {
  foreach ($_SESSION['lgdateForm'] as $key => $value) {
    $date = $value['date'];
    $fromto = $value['fromto'];
  }
} else {
  $date = $today;
  $fromto = "-parson-";
}

if (isset($_POST['opt'])) {
  $parson = $_POST['opt'];
  $_SESSION['searchDate'] = $parson;
}
$parson = $_SESSION['searchDate'] ?? 'all';

if ($parson == "all") {
  $qrt = "SELECT sum(taka) as rcv FROM `lendget` WHERE `drcr`='receive'";
  $qrta = "SELECT sum(taka) as pay FROM `lendget` WHERE `drcr`='payment'";

} else {
  $qrt = "SELECT sum(taka) as rcv FROM `lendget` WHERE `fromto`='$parson' && `drcr`='receive'";
  $qrta = "SELECT sum(taka) as pay FROM `lendget` WHERE `fromto`='$parson' && `drcr`='payment'";
}

$rslt = mysqli_query($con, $qrt);
$rw = mysqli_fetch_assoc($rslt);
$receive = $rw['rcv'];

$rslta = mysqli_query($con, $qrta);
$rwa = mysqli_fetch_assoc($rslta);
$payment = $rwa['pay'];


$catagoryList = "";
$rslt = mysqli_query($con, "SELECT * FROM `catagory` WHERE `mother`='lend/get' AND `status`='active'");
while ($rw = mysqli_fetch_assoc($rslt)) {
  $catagoryList .= "<option>" . $rw['sub'] . "</option>";
}
?>

<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>lend/get</title>

<body>
  <div class="slideBar">
    <?php include("inc/linkbar.php"); ?>
  </div>

  <div class="middle">
    <div class="labale_bar">lend/get</div>
    <div class="form_div">
      <?php if (isset($_GET['brows'])) {
        $rwj = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM `lendget` where `sl`='{$_GET['sl']}'"));
      } ?>
      <form method="post" autocomplete="off" class="mainform">
        <input name="srl" value="<?= $rwj['sl'] ?? ''; ?>" hidden />
        <input name="aa" type="date" value="<?= $rwj['date'] ?? $date; ?>" placeholder="Date" />
        <input name="bb" value="<?= $rwj['description'] ?? ''; ?>" placeholder="Description" autofocus />
        <select name="cc">
          <option value="<?= $rwj['fromto'] ?? $fromto; ?>">
            <?= $rwj['fromto'] ?? $fromto; ?>
          </option>
          <?= $catagoryList; ?>
        </select>
        <select name="dd">
          <option value="receive">receive</option>
          <option value="payment">payment</option>
        </select>
        <input name="ee" value="<?= $rwj['taka'] ?? ''; ?>" type="number" placeholder="Taka" />
        <button type="submit" name="savebtn">save</button>
        <?php if (isset($_GET['brows'])) {
          echo "<button name='updatebtn'>update</button>
        <button name='cecuritybtn'>delete</button>";
        } ?>
      </form>
    </div>
  </div>

  <div class="middletwo">
    <h1>lend/get Record</h1>
    <div class="tbldiv">
      <div class="formResult" style="display: flex;">
        <div style="display: flex;">
          <form method="post">
            <select name="opt" onchange="this.form.submit()">
              <option value="<?= $parson; ?>">
                <?= $parson; ?>
              </option>
              <option value="all">all</option>
              <?= $catagoryList; ?>
            </select>
          </form>
        </div>
        <div
          style="flex-grow: 1;margin: 0 2px;background-image: linear-gradient(175deg,red,green,blue);color: aliceblue;text-align: center;">
          <?php if (!empty($receive)) {
            echo "Total: " . number_format($payment - $receive) . " /- ";
          }
          if (!empty($receive) && $payment > $receive) {
            echo "Get";
          } else {
            echo "Lend";
          } ?>
        </div>
      </div>

      <div class="tblHolder">
        <table>
          <tr>
            <th>date</th>
            <th>detail</th>
            <th>parson</th>
            <th>dr/cr</th>
            <th>taka</th>
            <th>edit</th>
          </tr>
          <?php
          if ($parson == "all") {
            $rqr = "SELECT * FROM `lendget` order by date desc";
          } else {
            $rqr = "SELECT * FROM `lendget` WHERE `fromto`='$parson' order by date desc";
          }

          $rslt = mysqli_query($con, $rqr);
          while ($rw = mysqli_fetch_assoc($rslt)) { ?>
            <tr>
              <td>
                <?= date_format(date_create($rw['date']), "d-M-y"); ?>
              </td>
              <td>
                <?= $rw['description']; ?>
              </td>
              <td>
                <?= $rw['fromto']; ?>
              </td>
              <td>
                <?= $rw['drcr']; ?>
              </td>
              <td>
                <?= number_format($rw['taka']); ?>
              </td>
              <td style="width: 30px;"><a href="lendget.php?sl=<?= $rw['sl']; ?>&brows">edit</a></td>
            </tr>
          <?php } ?>
        </table>
      </div>
    </div>

  </div>

  <div class="end">
    <div class="tblHolder">
      <table>
        <tr>
          <th>name</th>
          <th>receive</th>
          <th>payment</th>
          <th>get</th>
        </tr>

        <?php
        $qrt = "SELECT * FROM `catagory` WHERE `mother`='lend/get'";
        $rslt = mysqli_query($con, $qrt);
        while ($rw = mysqli_fetch_assoc($rslt)) {
          $gname = $rw['sub'];

          $qrta = "SELECT sum(taka) as rcv FROM `lendget` WHERE `fromto`='$gname' && `drcr`='receive'";
          $rslta = mysqli_query($con, $qrta);
          while ($rwa = mysqli_fetch_assoc($rslta)) {

            $qrtb = "SELECT sum(taka) as pay FROM `lendget` WHERE `fromto`='$gname' && `drcr`='payment'";
            $rsltb = mysqli_query($con, $qrtb);
            while ($rwb = mysqli_fetch_assoc($rsltb)) {
              ?>
              <tr>
                <td>
                  <?= $rw['sub']; ?>
                </td>
                <td>
                  <?= $rwa['rcv']; ?>
                </td>
                <td>
                  <?= $rwb['pay']; ?>
                </td>
                <td>
                  <?= $rwb['pay'] - $rwa['rcv']; ?>
                </td>
              </tr>
            <?php }
          }
        } ?>

        <tr>
          <?php
          $qrt = "SELECT sum(taka) as rcv FROM `lendget` WHERE `drcr`='receive'";
          $qrta = "SELECT sum(taka) as pay FROM `lendget` WHERE `drcr`='payment'";

          $rslt = mysqli_query($con, $qrt);
          $rw = mysqli_fetch_assoc($rslt);
          $receive = $rw['rcv'];

          $rslta = mysqli_query($con, $qrta);
          $rwa = mysqli_fetch_assoc($rslta);
          $payment = $rwa['pay'];
          ?>
          <td>total=</td>
          <td>
            <?= number_format($receive); ?>
          </td>
          <td>
            <?= number_format($payment); ?>
          </td>
          <td>
            <?= number_format($payment - $receive); ?>
          </td>
        </tr>
      </table>
    </div>
  </div>

  <script src="java/jquery.js"></script>
  <script src="java/main.js"></script>
</body>

<?php
if ($_SERVER['REQUEST_METHOD'] === "POST") {
  $srl = $_POST['srl'] ?? '';
  $date = $_POST['aa'] ?? '';
  $description = $_POST['bb'] ?? '';
  $fromto = $_POST['cc'] ?? '';
  $drcr = $_POST['dd'] ?? '';
  $taka = $_POST['ee'] ?? '';
  $name = $_POST['name'] ?? '';
  $password = $_POST['password'] ?? '';
}

if (isset($_POST['savebtn'])) {
  $_SESSION['lgdateForm'][0] = array('date' => $date, 'fromto' => $fromto);

  $qry = "SELECT * FROM `lendget` WHERE `date`='$date' && `description`='$description' && `fromto`='$fromto' && `drcr`='$drcr' && `taka`='$taka'";
  $qrs = "INSERT INTO `lendget`(`date`, `description`, `fromto`, `drcr`, `taka`) VALUES ('$date','$description','$fromto','$drcr','$taka')";
  checkData($con, $qry, $qrs);
  echo ("<script>window.location.href='lendget.php'; </script>");
}

if (isset($_POST['updatebtn'])) {
  $qry = "SELECT * FROM `lendget` WHERE `date`='$date' && `description`='$description' && `fromto`='$fromto' && `drcr`='$drcr' && `taka`='$taka'";
  if (checkUpdate($con, $qry)) {
    echo ("<script>window.location.href='lendget.php'; </script>");
  } else {
    $rqr = "UPDATE `lendget` SET `date`='$date',`description`='$description',`fromto`='$fromto',`drcr`='$drcr',`taka`='$taka' WHERE `sl`='$srl'";
    updateData($con, $rqr);
    echo ("<script>window.location.href='lendget.php'; </script>");
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
  $rsl = mysqli_query($con, "SELECT * FROM `userlog` WHERE `name`='$name' AND `password`='$password'");
  if (mysqli_num_rows($rsl) > 0) {

    if (mysqli_query($con, "DELETE FROM `lendget` WHERE `sl`='$srl'")) {
      echo ("<script>window.location.href='lendget.php'; </script>");
    }

  } else {
    echo ("<script>alert('Password Rong');
      window.location.href='lendget.php';</script>");
  }
}
?>

<style>
  #list5 {
    background-color: white;
    color: black;
  }
</style>