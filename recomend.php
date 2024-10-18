<?php include("inc/connection.php");

if (isset($_SESSION['recomendSaveForm'])) {
  foreach ($_SESSION['recomendSaveForm'] as $key => $value) {
    $date = $value['date'];
    $purpose = $value['purpose'];
  }
} else {
  $date = $today;
  $purpose = "-select-";
}

if (isset($_POST['month'])) {
  $srcMonth = $_POST['month'] . "-01";
  $srcInpMonth = $_POST['month'];
  $_SESSION['recomendSrc'] = $srcMonth;
  $_SESSION['recomendSrcInp'] = $srcInpMonth;

} elseif (isset($_SESSION['recomendSrc'])) {
  $srcMonth = $_SESSION['recomendSrc'];
  $srcInpMonth = $_SESSION['recomendSrcInp'];

} else {
  $srcMonth = date('Y-m-d');
  $srcInpMonth = date('Y-m');
}

try {
  function getSum($con, $condition)
  {
    $rslc = $con->query("SELECT sum(taka) as total from `recomend` $condition");
    $rwc = $rslc->fetch_assoc();
    return $rwc['total'];
  }

  $condition = "and date_format(date,'%Y-%m') = date_format('$srcMonth','%Y-%m')";
  $need = getSum($con, "WHERE `purpose`='need'" . $condition);
  $get = getSum($con, "WHERE `purpose`='get'" . $condition);

} catch (Exception $e) {
  echo "error: " . $e;
}
?>

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Requirment</title>

<body>
  <div class="slideBar">
    <?php include("inc/linkbar.php"); ?>
  </div>

  <div class="middle">
    <div class="labale_bar">Requirment</div>

    <div class="form_div">
      <?php if (isset($_GET['brows'])) {
        $rwj = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM `recomend` where `sl`='{$_GET['sl']}'"));
      } ?>
      <form method="post" autocomplete="off">
        <input name="srl" value="<?= $rwj['sl'] ?? ''; ?>" hidden />
        <input name="aa" type="date" value="<?= $rwj['date'] ?? $date; ?>" />
        <input list="datalist" name="bb" value="<?= $rwj['description'] ?? ''; ?>" placeholder="Description"
          autofocus />
        <datalist id="datalist">
          <?php $rslt = mysqli_query($con, "SELECT `description` FROM `recomend` GROUP BY `description` desc");
          while ($rw = mysqli_fetch_assoc($rslt)) {
            echo '<option>' . $rw['description'] . '</option>';
          } ?>
        </datalist>
        <select name="cc" id="">
          <option value="<?= $rwj['purpose'] ?? $purpose; ?>">
            <?= $rwj['purpose'] ?? $purpose; ?>
          </option>
          <option value="need">need</option>
          <option value="get">get</option>
        </select>
        <input name="dd" value="<?= $rwj['taka'] ?? ''; ?>" type="number" placeholder="Taka" />
        <button type="submit" name="savebtn">save</button>
        <?php if (isset($_GET['brows'])) {
          echo "<button name='updatebtn'>update</button>
        <button name='cecuritybtn'>delete</button>";
        } ?>
      </form>
    </div>
  </div>

  <div class="rightdiv">
    <h1>recomend record</h1>
    <div style="display: flex;">
      <form method="post" class="searchForm" style="margin-right: 3px;">
        <input name="month" type="month" value="<?= $srcInpMonth; ?>" onchange="this.form.submit()">
      </form>

      <input type="search" placeholder="type anythings" id="myInput" style="margin-right: 3px;">
      <a href="rec_report.php" target="_parent"><button style="padding: 6px;">report</button></a>

      <div
        style="display: flex; justify-content: space-between; padding: 5px; background-image: linear-gradient(175deg,red,green,blue); color: aliceblue; margin-left: 3px;">
        <span>get :
          <?= $get ? number_format($get) : 0; ?>
        </span>
        <span>need :
          <?= $need ? number_format($need) : 0; ?>
        </span>
        <span>
          <?= $need > $get ? "lagbe : " . number_format($get - $need) : "takbe : " . number_format($get - $need); ?>
        </span>
      </div>

    </div>

    <div class="tblHolder">
      <table>
        <tr>
          <th>date</th>
          <th>description</th>
          <th>purpose</th>
          <th>taka</th>
          <th>status</th>
          <th>edit</th>
        </tr>
        <?php $rslt = mysqli_query($con, "SELECT * FROM `recomend` where month(date) = month('$srcMonth') and year(date) = year('$srcMonth')");
        while ($rw = mysqli_fetch_assoc($rslt)) { ?>
          <tbody id="myTable">
            <tr>
              <td>
                <?= date_format(date_create($rw['date']), 'd-M-y'); ?>
              </td>
              <td>
                <?= $rw['description']; ?>
              </td>
              <td>
                <?= $rw['purpose']; ?>
              </td>
              <td>
                <?= number_format($rw['taka']); ?>
              </td>
              <td>
                <?= $rw['status']; ?>
              </td>
              <td style="width: 30px;"><a href="recomend.php?sl=<?= $rw['sl']; ?>&brows">edit</a></td>
            </tr>
          </tbody>
        <?php } ?>
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
  $source = $_POST['cc'] ?? '';
  $taka = $_POST['dd'] ?? '';
  $name = $_POST['name'] ?? '';
  $password = $_POST['password'] ?? '';
}

if (isset($_POST['savebtn'])) {
  $_SESSION['recomendSaveForm'][0] = array('date' => $date, 'purpose' => $purpose);

  $qry = "SELECT * FROM `recomend` WHERE `date`='$date' AND `description`='$description' AND `purpose`='$purpose' AND `taka`='$taka'";
  $qrs = "INSERT INTO `recomend`(`date`, `description`, `purpose`, `taka`) VALUES ('$date','$description','$purpose','$taka')";
  checkData($con, $qry, $qrs);
  echo ("<script>window.location.href = '" . $_SERVER['HTTP_PREFER'] . "'</script>");
}

if (isset($_POST['updatebtn'])) {
  $qry = "SELECT * FROM `recomend` WHERE `date`='$date' AND `description`='$description' AND `purpose`='$purpose' AND `taka`='$taka'";
  if (checkUpdate($con, $qry)) {
    echo ("<script>window.location.href='recomend.php';</script>");
  } else {

    $rqr = "UPDATE `recomend` SET `date`='$date',`description`='$description',`purpose`='$purpose',`taka`='$taka' WHERE `sl`='$srl'";
    updateData($con, $rqr);
    echo ("<script>window.location.href='recomend.php';</script>");
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
    if (mysqli_query($con, "DELETE FROM `recomend` WHERE `sl`='$srl'")) {
      header('Location:recomend.php');
    }
  } else {
    echo "<script>alert('Password Rong')";
    header('Location:recomend.php');
  }
}
?>

<style>
  #list3 {
    background-color: white;
    color: black;
  }
</style>

<script>
  $(document).ready(function () {
    $("#myInput").on("keyup", function () {
      var value = $(this).val().toLowerCase();
      $("#myTable tr").filter(function () {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
      });
    });
  });
</script>