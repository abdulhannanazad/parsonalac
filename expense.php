<?php include("inc/connection.php");
if (isset($_SESSION['expSaveForm'])) {
  foreach ($_SESSION['expSaveForm'] as $key => $value) {
    $date = $value['date'];
    $purpose = $value['purpose'];
  }
} else {
  $date = $today;
  $purpose = "-select-";
}

function getSum($con, $table, $whr = "")
{
  return mysqli_fetch_assoc(mysqli_query($con, "SELECT sum(taka) as total FROM `$table` $whr"))['total'];
}

// searching condition
if (isset($_POST['allDataButton'])) {
  $qry = "SELECT * FROM `expense` order by date desc";
  $querySum = "SELECT sum(taka) as total from `expense`";

} elseif (isset($_POST['searchButton'])) {
  $srcDatea = $_POST['datea'];
  $srcDateb = $_POST['dateb'];
  $_SESSION['exprecorddate'][0] = array('sdatea' => $srcDatea, 'sdateb' => $srcDateb);
  $qry = "SELECT * FROM `expense` where `date` between '$srcDatea' and '$srcDateb' order by `date` desc";
  $querySum = "SELECT sum(taka) as total from `expense` WHERE `date` between '$srcDatea' and '$srcDateb'";

} elseif (!empty($_SESSION['exprecorddate'])) {
  foreach ($_SESSION['exprecorddate'] as $key => $value) {
    $srcDatea = $value['sdatea'];
    $srcDateb = $value['sdateb'];
  }
  $qry = "SELECT * FROM `expense` where `date` between '$srcDatea' and '$srcDateb' order by `date` desc";
  $querySum = "SELECT sum(taka) as total from `expense` WHERE `date` between '$srcDatea' and '$srcDateb'";

} else {
  $srcDatea = $today;
  $srcDateb = $today;
  $qry = "SELECT * FROM `expense` WHERE `date`='$today' order by date desc";
  $querySum = "SELECT sum(taka) as total from `expense` WHERE `date`='$today'";
}

$rsltSum = mysqli_query($con, $querySum);
$rowSum = mysqli_fetch_assoc($rsltSum);
$total = $rowSum['total'];

// purpose list import
$qryCatagory = mysqli_query($con, "SELECT `sub` FROM `catagory` WHERE `mother`='expense' && `status`='active' order by `sub` asc");
$purposeList = "";
while ($ctgRow = $qryCatagory->fetch_assoc()) {
  $purposeList .= "<option>" . $ctgRow['sub'] . "</option>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>expense</title>
</head>

<body>
  <div class="slideBar">
    <?php include("inc/linkbar.php"); ?>
  </div>

  <div class="middle">
    <div class="labale"
      style="display: flex;align-items: center; justify-content: space-between;padding: 0 5px 3.4px 5px;background-color: steelblue;">
      <span>create expense</span>
      <button accesskey="v" title="alt + v" onclick="viewbox()" style="padding:0 5px;">view</button>
    </div>

    <div class="compare" hidden>
      <p style="background-color: rgb(129, 140, 140); padding: 5px; border-radius: 5px 5px 0 0; text-align: center;">
        compare box <a href="">X</a></p>
      <input onkeyup="compareCal()" id="cash" type="number" autofocus placeholder="insert your current cash"
        style="width: fit-content; display: block;">
      <span id="reult" style="border: 1px solid blue;">result hare</span>
    </div>

    <div class="form_div">
      <?php if (isset($_GET['brows'])) {
        $rwj = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM `expense` where `sl`='{$_GET['sl']}'"));
      } ?>

      <form method="post" autocomplete="off" class="saveForm">
        <input name="srl" value="<?= $rwj['sl'] ?? ''; ?>" hidden />
        <input name="aa" type="date" value="<?= $rwj['date'] ?? $date; ?>" />

        <input name="bb" list="Description" value="<?= $rwj['description'] ?? ''; ?>" placeholder="description"
          autofocus required />
        <datalist id="Description">
          <?php $rslt = mysqli_query($con, "SELECT `description` FROM `expense` GROUP BY `description` ORDER BY `description` ASC");
          while ($rw = mysqli_fetch_assoc($rslt)) {
            echo '<option>' . $rw['description'] . '</option>';
          } ?>
        </datalist>

        <select name="cc">
          <option value="<?= $rwj['purpose'] ?? $purpose; ?>">
            <?= $rwj['purpose'] ?? $purpose; ?>
          </option>
          <?= $purposeList; ?>
        </select>

        <input name="dd" value="<?= $rwj['taka'] ?? $purpose; ?>" type="number" placeholder="taka" required />
        <button name="savebtn">save</button>
        <?php if (isset($_GET['brows'])) {
          echo "<button name='updatebtn'>update</button>
        <button name='cecuritybtn'>delete</button>";
        } ?>
      </form>
    </div>
  </div>

  <div class="rightdiv">
    <h1>expense record</h1>
    <form method="post" class="searchForm" style="display: inline-block;">
      <input name="datea" type="date" value="<?= $srcDatea ?? $today; ?>" />
      <input name="dateb" type="date" value="<?= $srcDateb ?? $today; ?>" />
      <button name="searchButton">search</button>
      <button name="allDataButton" id="allSearch">all</button>
      <input id="myInput" placeholder="type anythings" type="search">
    </form>

    <div
      style="display: inline-block; background-image: linear-gradient(175deg,red,green,blue); color: aliceblue; text-align: center;">
      total:<input style="background-color: transparent; border: none;" id="expval"
        value="<?= number_format($total) ?? 0; ?>" readonly>taka
    </div>

    <div class="tblHolder">
      <table>
        <tr>
          <th>date</th>
          <th>details</th>
          <th>purpose</th>
          <th>taka</th>
          <th>edit</th>
        </tr>
        <?php $table = mysqli_query($con, $qry);
        while ($row = mysqli_fetch_assoc($table)) { ?>
          <tbody id="myTable">
            <tr>
              <td>
                <?= date_format(date_create($row['date']), "d-M-y"); ?>
              </td>
              <td>
                <?= $row['description']; ?>
              </td>
              <td>
                <?= $row['purpose']; ?>
              </td>
              <td>
                <?= number_format($row['taka']); ?>
              </td>
              <td><a href="expense.php?sl=<?= $row['sl']; ?>&brows">edit</a></td>
            </tr>
          </tbody>
        <?php } ?>
      </table>
    </div>
    <div class="msg">message div</div>

    <script src="java/jquery.js"></script>
    <script src="java/main.js"></script>
</body>

<?php
if ($_SERVER['REQUEST_METHOD'] === "POST") {
  $srl = $_POST['srl'] ?? '';
  $date = $_POST['aa'] ?? '';
  $description = $_POST['bb'] ?? '';
  $purpose = $_POST['cc'] ?? '';
  $taka = $_POST['dd'] ?? '';
  $name = $_POST['name'] ?? '';
  $password = $_POST['password'] ?? '';

  if (isset($_POST['savebtn'])) {
    $_SESSION['expSaveForm'][0] = array('date' => $date, 'purpose' => $purpose);
    $qry = "SELECT * FROM `expense` WHERE `date`='$date' and `description`='$description' and `purpose`='$purpose' and `taka`='$taka'";
    $qrs = "INSERT INTO `expense`(`date`, `description`, `purpose`, `taka`) VALUES ('$date','$description','$purpose','$taka')";
    checkData($con, $qry, $qrs);
    header('Location:expense.php');
  }

  if (isset($_POST['updatebtn'])) {
    if (checkUpdate($con, "SELECT * FROM `expense` WHERE `date`='$date' && `description`='$description' && `purpose`='$purpose' && `taka`='$taka'")) {
      header('Location:expense.php');
    } else {
      updateData($con, "UPDATE `expense` SET `date`='$date',`description`='$description',`purpose`='$purpose',`taka`='$taka' WHERE `sl`='$srl'");
      header('Location:expense.php');
    }
  }
}

if (isset($_POST['cecuritybtn'])) {
  echo "<div class='cecurity-form'>
  <form method='post'>
    <h2>userName and password</h2>
    <input name='srl' value='" . $srl . "' readonly>
    <input name='name' value='hannan' placeholder='username'/>
    <input name='password' value='0000' placeholder='password'/>
    <button name='deletebtn'>yes</button>
    <button name='blank'>no</button>
  </form>
  <div>";
}

if (isset($_POST['deletebtn'])) {
  $rsl = mysqli_query($con, "SELECT * FROM `userlog` WHERE `name`='$name' AND `password`='$password'");
  if (mysqli_num_rows($rsl) > 0) {
    if (mysqli_query($con, "DELETE FROM `expense` WHERE `sl`='$srl'")) {
      header('Location:expense.php');
    }
  } else {
    echo ("<script>alert('Password Rong');
        window.location.href = 'expense.php'; </script>");
  }
}
?>

<script>
  function viewbox() {
    $(".compare").show();
  }

  function compareCal() {
    var cash = $("#cash").val();
    var comval = $("#expval").val();
    cash = parseFloat(cash);
    comval = parseFloat(comval);
    var calval = cash - comval;
    if (cash > comval) {
      $("#reult").text(calval + " thakbe");
    } else {
      $("#reult").text(calval + " lagbe");
    }
  }
</script>

<style>
  #list1 {
    background-color: white;
    color: black;
  }
</style>