<?php include("inc/connection.php");

if (!empty($_SESSION['incomeSave'])) {
  foreach ($_SESSION['incomeSave'] as $key => $value) {
    $date = $value['date'];
    $source = $value['source'];
  }
} else {
  $date = $today;
  $source = "-select-";
}

if (isset($_POST['search'])) {
  $srcDateA = $_POST['dta'];
  $srcDateB = $_POST['dtb'];
  $srcPurpose = $_POST['opt'];
  $_SESSION['incomeSearch'][0] = array('datea' => $srcDateA, 'dateb' => $srcDateB, 'purpose' => $srcPurpose);

} elseif (!empty($_SESSION['incomeSearch'])) {
  foreach ($_SESSION['incomeSearch'] as $key => $value) {
    $srcDateA = $value['datea'];
    $srcDateB = $value['dateb'];
    $srcPurpose = $value['purpose'];
  }
} else {
  $srcDateA = $today;
  $srcDateB = $today;
  $srcPurpose = "all";
}

if (isset($_POST['all'])) {
  $rqr = "SELECT * FROM `income` order by date desc";
  $qrt = "select sum(taka) as total from `income`";

} else {
  if ($srcPurpose == "all") {
    $rqr = "SELECT * FROM `income` WHERE `date` between '$srcDateA' and '$srcDateB' order by date desc";
    $qrt = "select sum(taka) as total from `income` WHERE `date` between '$srcDateA' and '$srcDateB'";
  } else {
    $rqr = "SELECT * FROM `income` WHERE `date` between '$srcDateA' and '$srcDateB' AND `source`='$srcPurpose' order by date desc";
    $qrt = "select sum(taka) as total from `income` WHERE `date` between '$srcDateA' and '$srcDateB' AND `source`='$srcPurpose'";
  }
}
$rslt = mysqli_query($con, $qrt);
$rw = mysqli_fetch_assoc($rslt);
$total = $rw['total'];

// source list import
$rslt = mysqli_query($con, "SELECT * FROM `catagory` WHERE `mother`='income' && `status`='active'");
$descriptionList = "";
while ($rw = mysqli_fetch_assoc($rslt)) {
  $descriptionList .= "<option>" . $rw['sub'] . "</option>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="create record of income">
  <meta name="keywords" content="income, record, parsonal, account">
  <title>income</title>
</head>

<body>

  <div class="slideBar">
    <?php include("inc/linkbar.php"); ?>
  </div>

  <div class="middle">
    <div class="labale_bar">income record creation form</div>
    <div class="form_div">
      <?php if (isset($_GET['brows'])) {
        $rwj = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM `income` where `sl`='{$_GET['sl']}'"));
      } ?>
      <form method="post" autocomplete="off">
        <input name="srl" value="<?= $rwj['sl'] ?? ''; ?>" hidden />
        <input name="aa" type="date" value="<?= $rwj['date'] ?? $date; ?>" />
        <input name="bb" list="datalist" value="<?= $rwj['description'] ?? ''; ?>" placeholder="description" autofocus
          required />
        <datalist id="datalist">
          <?php $rslt = mysqli_query($con, "SELECT `description` FROM `income` GROUP BY `description` desc order by `description` asc");
          while ($rw = mysqli_fetch_assoc($rslt)) {
            echo '<option>' . $rw['description'] . '</option>';
          } ?>
        </datalist>
        <select name="cc">
          <option value="<?= $rwj['source'] ?? $source; ?>">
            <?= $rwj['source'] ?? $source; ?>
          </option>
          <?= $descriptionList; ?>
        </select>
        <input name="dd" value="<?= $rwj['taka'] ?? ''; ?>" type="number" placeholder="Taka" required />
        <button title="alt + s" name="savebtn">save</button>
        <?php if (isset($_GET['brows'])) {
          echo "<button name='updatebtn'>update</button>
        <button name='cecuritybtn'>delete</button>";
        } ?>
      </form>
    </div>
  </div>

  <div class="rightdiv">
    <h1>income record table</h1>

    <div class="formResult" style="display: flex;">
      <div style="display: flex;">
        <form method="post" class="searchForm">
          <input name="dta" type="date" value="<?= $srcDateA; ?>" />
          <input name="dtb" type="date" value="<?= $srcDateB; ?>" />
          <select name="opt">
            <option value="<?= $srcPurpose; ?>">
              <?= $srcPurpose; ?>
            </option>
            <option value="all">all</option>
            <?= $descriptionList; ?>
          </select>
          <button title="alt + r" name="search">search</button>
          <button title="alt + q" name="all">all</button>
        </form>
      </div>
      <div
        style="flex-grow: 1;margin: 0 2px;background-image: linear-gradient(175deg,red,green,blue);color: aliceblue;text-align: center;">
        <?php if (!empty($total)) {
          echo "Total: " . number_format($total) . " Taka";
        } ?>
      </div>
    </div>

    <div class="tblHolder">
      <table>
        <tr>
          <th>date</th>
          <th>description</th>
          <th>Source</th>
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
              <?= $rw['source']; ?>
            </td>
            <td>
              <?= number_format($rw['taka']); ?>
            </td>
            <td style="width: 30px;"><a href="income.php?sl=<?= $rw['sl']; ?>&brows">edit</a></td>
          </tr>
        <?php } ?>
      </table>
    </div>
  </div>

  <script src="java/jquery.js"></script>
  <script src="java/main.js"></script>
</body>

</html>

<?php
if ($_SERVER['REQUEST_METHOD'] === "POST") {
  $srl = $_POST['srl'] ?? '';
  $date = $_POST['aa'] ?? '';
  $description = $_POST['bb'] ?? '';
  $source = $_POST['cc'] ?? '';
  $taka = $_POST['dd'] ?? '';
  $name = $_POST['name'] ?? '';
  $password = $_POST['password'] ?? '';
}

if (isset($_POST['savebtn'])) {
  $_SESSION['incomeSave'][0] = array('date' => $date, 'source' => $source);

  $qry = "SELECT * FROM `income` WHERE `date`='$date' AND `description`='$description' AND `source`='$source' AND `taka`='$taka'";
  $qrs = "INSERT INTO `income`(`date`, `description`, `source`, `taka`) VALUES ('$date','$description','$source','$taka')";
  checkData($con, $qry, $qrs);
  header('Location:income.php');
}

if (isset($_POST['updatebtn'])) {
  $qry = "SELECT * FROM `income` WHERE `date`='$date' AND `description`='$description' AND `source`='$source' AND `taka`='$taka'";
  if (checkUpdate($con, $qry)) {
    header('Location:income.php');
  } else {
    $rqr = "UPDATE `income` SET `date`='$date',`description`='$description',`source`='$source',`taka`='$taka' WHERE `sl`='$srl'";
    updateData($con, $rqr);
    header('Location:income.php');
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
    if (mysqli_query($con, "DELETE FROM `income` WHERE `sl`='$srl'")) {
      header('Location:income.php');
    }
  } else {
    echo "<script>alert('Password Rong')";
    header('Location:income.php');
  }
}
?>

<style>
  #list2 {
    background-color: white;
    color: black;
  }
</style>