<?php include("inc/connection.php");
if (isset($_SESSION['savingsSaveForm'])) {
  foreach ($_SESSION['savingsSaveForm'] as $key => $value) {
    $date = $value['date'];
    $purpose = $value['purpose'];
  }
} else {
  $date = $today;
  $purpose = "-select-";
}


if (isset($_POST['opt'])) {
  $srcPurpose = $_POST['opt'];
  $_SESSION['savingsSrcPurpose'] = $srcPurpose;

} elseif (isset($_SESSION['savingsSrcPurpose'])) {
  $srcPurpose = $_SESSION['savingsSrcPurpose'];

} else {
  $srcPurpose = "all";
}

function getSum($con, $condition)
{
  $rslt = mysqli_query($con, "SELECT sum(taka) as total from `savings` $condition");
  $rw = mysqli_fetch_assoc($rslt);
  return $rw['total'];
}

if ($srcPurpose == "all") {
  $rqr = "SELECT * FROM `savings` order by date desc";
  $condition = "WHERE (`drcr`='deposit' or drcr`='widdrow')";
} else {
  $rqr = "SELECT * FROM `savings` WHERE `purpose`='$srcPurpose' order by date desc";
  $condition = "WHERE `purpose`='$srcPurpose' and (`drcr`='deposit' or drcr`='widdrow')";
}

// purpose list import
$catagory = 0;
$rslt = mysqli_query($con, "SELECT * FROM `catagory` WHERE `mother`='savings' && `status`='active'");
while ($rw = mysqli_fetch_assoc($rslt)) {
  $catagory .= "<option>" . $rw['sub'] . "</option>";
}
?>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>savings</title>

<body>
  <div class="slideBar">
    <?php include("inc/linkbar.php"); ?>
  </div>

  <div class="middle">
    <div class="labale_bar">Savings</div>
    <div class="form_div">
      <?php if (isset($_GET['brows'])) {
        $rwj = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM `savings` where `sl`='{$_GET['sl']}'"));
      } ?>
      <form method="post" autocomplete="off" class="mainform">
        <input name="srl" value="<?= $rwj['sl'] ?? ''; ?>" hidden />
        <input name="aa" type="date" value="<?= $rwj['date'] ?? $date; ?>" placeholder="Date" />
        <input name="bb" value="<?= $rwj['description'] ?? ''; ?>" placeholder="Description" autofocus />
        <select name="cc">
          <option value="<?= $purpose; ?>">
            <?= $purpose; ?>
          </option>
          <?= $catagory; ?>
        </select>
        <select name="dd">
          <option value="deposit" selected>deposit</option>
          <option value="widdrow">widdrow</option>
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
    <h1>savings Record</h1>
    <div class="formResult" style="display: flex;">
      <div style="display: flex;">
        <form action="savings.php" method="post">
          <select name="opt" onchange="this.form.submit()">
            <option value="<?= $srcPurpose; ?>">
              <?= $srcPurpose; ?>
            </option>
            <option value="all">all</option>
            <?= $catagory; ?>
          </select>
        </form>
      </div>
      <div
        style="flex-grow: 1;margin: 0 1px;background-image: linear-gradient(175deg,red,green,blue);color: aliceblue;text-align: center;">
        <?php
        $deposit = getSum($con, "where `drcr`='deposit'");
        $widdrow = getSum($con, "where `drcr`='widdrow'");
        $balance = $deposit - $widdrow;
        echo !empty($balance) ? "balance: " . number_format($balance) . " Taka" : 0; ?>
      </div>
    </div>

    <div class="tblHolder">
      <table>
        <tr>
          <th>date</th>
          <th>description</th>
          <th>purpose</th>
          <th>drcr</th>
          <th>taka</th>
          <th>edit</th>
        </tr>
        <?php $rslt = mysqli_query($con, $rqr);
        while ($rw = mysqli_fetch_assoc($rslt)) { ?>
          <tr>
            <td>
              <?= date_format(date_create($rw['date']), "d-M-y"); ?>
            </td>
            <td>
              <?= $rw['description']; ?>
            </td>
            <td>
              <?= $rw['purpose']; ?>
            </td>
            <td>
              <?= $rw['drcr']; ?>
            </td>
            <td>
              <?= number_format($rw['taka']); ?>
            </td>
            <td style="width: 30px;"><a href="savings.php?sl=<?= $rw['sl']; ?>&brows">edit</a></td>
          </tr>
        <?php } ?>
      </table>
    </div>
  </div>

  <div class="end">
    <div class="tblHolder">
      <table>
        <tr>
          <th>name</th>
          <th>deposit</th>
          <th>widdrow</th>
          <th>balance</th>
        </tr>
        <?php
        $rslt = mysqli_query($con, "SELECT * FROM `catagory` WHERE `mother`='savings'");
        while ($rw = mysqli_fetch_assoc($rslt)) {
          $gname = $rw['sub'];

          $gDeposit = getSum($con, "WHERE `purpose`='$gname' && `drcr`='deposit'");
          $gWiddrow = getSum($con, "WHERE `purpose`='$gname' && `drcr`='widdrow'");
          $gBalance = $gDeposit - $gWiddrow;
          ?>
          <tr>
            <td>
              <?= $gname; ?>
            </td>
            <td>
              <?= number_format($gDeposit); ?>
            </td>
            <td>
              <?= number_format($gWiddrow); ?>
            </td>
            <td>
              <?= number_format($gBalance); ?>
            </td>
          </tr>
        <?php } ?>
        <tr>
          <?php
          $depositTotal = getSum($con, "WHERE `drcr`='deposit'");
          $widdrowTotal = getSum($con, "WHERE `drcr`='widdrow'");
          $balanceTotal = $depositTotal - $widdrowTotal;
          ?>
          <td>total=</td>
          <td>
            <?= number_format($depositTotal); ?>
          </td>
          <td>
            <?= number_format($widdrowTotal); ?>
          </td>
          <td>
            <?= number_format($balanceTotal); ?>
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
  $purpose = $_POST['cc'] ?? '';
  $drcr = $_POST['dd'] ?? '';
  $taka = $_POST['ee'] ?? '';
  $name = $_POST['name'] ?? '';
  $password = $_POST['password'] ?? '';
}

if (isset($_POST['savebtn'])) {
  $_SESSION['savingsSaveForm'][0] = array('date' => $date, 'purpose' => $purpose);

  $qry = "SELECT * FROM `savings` WHERE `date`='$date' && `description`='$description' && `purpose`='$purpose' && `drcr`='$drcr' && `taka`='$taka' ";
  $qrs = "INSERT INTO `savings`(`date`, `description`, `purpose`, `drcr`, `taka`) VALUES ('$date','$description','$purpose','$drcr','$taka')";
  checkData($con, $qry, $qrs);
  echo ("<script>window.location.href = 'savings.php';</script>");
}

if (isset($_POST['updatebtn'])) {
  $qry = "SELECT * FROM `savings` WHERE `date`='$date' && `description`='$description' && `purpose`='$purpose' && `drcr`='$drcr' && `taka`='$taka'";
  if (checkUpdate($con, $qry)) {
    echo ("<script>window.location.href='savings.php';</script>");
  } else {

    $rqr = "UPDATE `savings` SET `date`='$date',`description`='$description',`purpose`='$purpose',`drcr`='$drcr',`taka`='$taka' WHERE `sl`='$srl'";
    updateData($con, $rqr);
    echo ("<script>window.location.href='savings.php';</script>");
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

    if (mysqli_query($con, "DELETE FROM `savings` WHERE `sl`='$srl'")) {
      echo ("<script> window.location.href='savings.php';</script>");
    }

  } else {
    echo ("<script>alert('Password Rong');
    window.location.href='savings.php';</script>");
  }
}

?>

<style>
  #list4 {
    background-color: white;
    color: black;
  }
</style>