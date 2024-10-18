<?php include("inc/connection.php");
if (isset($_POST['search'])) {
  $_SESSION['srcdate'][0] = array("sdatea" => $_POST['datea'], "sdateb" => $_POST['dateb']);
}

if (isset($_SESSION['srcdate'])) {
  foreach ($_SESSION['srcdate'] as $key => $value) {
    $sdatea = $value['sdatea'];
    $sdateb = $value['sdateb'];
  }
}

if (isset($_POST['search'])) {
  $datea = $_POST['datea'];
  $dateb = $_POST['dateb'];

  $qrc = mysqli_query($con, "SELECT sum(taka) as totalc FROM `income` WHERE `date` between '$datea' and '$dateb' order by date desc");
  $qrd = mysqli_query($con, "SELECT sum(taka) as totald FROM `lendget` WHERE `drcr`='receive' AND `date` between '$datea' and '$dateb' order by date desc");
  $rwc = mysqli_fetch_assoc($qrc);
  $rwd = mysqli_fetch_assoc($qrd);


  $qre = mysqli_query($con, "SELECT sum(taka) as totale FROM `expense` WHERE `date` between '$datea' and '$dateb' order by date desc");
  $qrf = mysqli_query($con, "SELECT sum(taka) as totalf FROM `savings` WHERE `date` between '$datea' and '$dateb' order by date desc");
  $qrg = mysqli_query($con, "SELECT sum(taka) as totalg FROM `lendget` WHERE `drcr`='payment' AND `date` between '$datea' and '$dateb' order by date desc");
  $rwe = mysqli_fetch_assoc($qre);
  $rwf = mysqli_fetch_assoc($qrf);
  $rwg = mysqli_fetch_assoc($qrg);

  $totalc = $rwc['totalc'];
  $totald = $rwd['totald'];

  $totale = $rwe['totale'];
  $totalf = $rwf['totalf'];
  $totalg = $rwg['totalg'];

  $receive = $totalc + $totald;
  $paymen = $totale + $totalf + $totalg;
  $cash = $receive - $paymen;
}
?>

<meta charset="UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>search</title>

<body>
  <div class="slideBar">
    <?php include("inc/linkbar.php"); ?>
  </div>

  <div class="searchRight">
    <form method="post" style="text-align: center;">
      <input name="datea" type="date"        value="<?php if (!empty($sdatea)) {          echo $sdatea;        } else {          echo $today;        } ?>" />
      <input name="dateb" type="date"        value="<?php if (!empty($sdateb)) {          echo $sdateb;        } else {          echo $today;        } ?>" />
      <button name="search">search</button>
    </form>

    <div style="display:flex; justify-content: space-between; padding: 5px; background-color: aqua;">
      <?php if (!empty($cash)) {
        echo "<span>receive:" . number_format($receive) . "</span>
        <span>payment:" . number_format($paymen) . "</span>
        <span>cash:" . number_format($cash) . "</span>";
      } ?>
    </div>


    <div class="tblHolder">
      <table>
        <tr>
          <th>date</th>
          <th>description</th>
          <th>purpose</th>
          <th>taka</th>
        </tr>
        <?php if (isset($_POST['search'])) {
          $datea = $_POST['datea'];
          $dateb = $_POST['dateb'];
          echo '<caption>expense record, total: ' . number_format($totale) . ' /-</caption>';

          $qra = mysqli_query($con, "SELECT * FROM `expense` WHERE `date` between '$datea' and '$dateb' order by date desc");
          while ($rw = mysqli_fetch_assoc($qra)) { ?>
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
                <?= number_format($rw['taka']); ?>
              </td>
            </tr>
          <?php }
        } ?>
      </table>
    </div>
    <br>

    <div class="tblHolder">
      <table>
        <tr>
          <th>date</th>
          <th>description</th>
          <th>Source</th>
          <th>taka</th>
        </tr>
        <?php if (isset($_POST['search'])) {
          $datea = $_POST['datea'];
          $dateb = $_POST['dateb'];
          echo '<caption>income record, total: ' . number_format($totalc) . ' /-</caption>';

          $qra = mysqli_query($con, "SELECT * FROM `income` WHERE `date` between '$datea' and '$dateb' order by date desc");
          while ($rw = mysqli_fetch_assoc($qra)) { ?>
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
            </tr>
          <?php }
        } ?>
      </table>
    </div>
    <br>

    <div class="tblHolder">
      <table>
        <tr>
          <th>date</th>
          <th>description</th>
          <th>purpose</th>
          <th>taka</th>
          <th>status</th>
        </tr>
        <?php if (isset($_POST['search'])) {
          $datea = $_POST['datea'];
          $dateb = $_POST['dateb'];
          $qrb = mysqli_query($con, "SELECT sum(taka) as total FROM `recomend` WHERE `date` between '$datea' and '$dateb' order by date desc");
          $rwa = mysqli_fetch_assoc($qrb);
          echo '<caption>recomend record, total: ' . number_format($rwa['total']) . ' /-</caption>';

          $qra = mysqli_query($con, "SELECT * FROM `recomend` WHERE `date` between '$datea' and '$dateb' order by date desc");
          while ($rw = mysqli_fetch_assoc($qra)) { ?>
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
                <?= number_format($rw['taka']); ?>
              </td>
              <td>
                <?= $rw['status']; ?>
              </td>
            </tr>
          <?php }
        } ?>
      </table>
    </div>
    <br>

    <div class="tblHolder">
      <table>
        <tr>
          <th>date</th>
          <th>description</th>
          <th>purpose</th>
          <th>drcr</th>
          <th>taka</th>
        </tr>
        <?php if (isset($_POST['search'])) {
          $datea = $_POST['datea'];
          $dateb = $_POST['dateb'];
          echo '<caption>savings record, total: ' . number_format($totalf) . ' /-</caption>';

          $qra = mysqli_query($con, "SELECT * FROM `savings` WHERE `date` between '$datea' and '$dateb' order by date desc");
          while ($rw = mysqli_fetch_assoc($qra)) { ?>
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
            </tr>
          <?php }
        } ?>
      </table>
    </div>
    <br>

    <div class="tblHolder">
      <table>
        <tr>
          <th>date</th>
          <th>detail</th>
          <th>parson</th>
          <th>dr/cr</th>
          <th>taka</th>
        </tr>
        <?php if (isset($_POST['search'])) {
          $datea = $_POST['datea'];
          $dateb = $_POST['dateb'];
          echo '<caption>lendget record, total: ' . number_format($totald - $totalg) . ' /-</caption>';

          $qra = mysqli_query($con, "SELECT * FROM `lendget` WHERE `date` between '$datea' and '$dateb' order by date desc");
          while ($rw = mysqli_fetch_assoc($qra)) { ?>
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
            </tr>
          <?php }
        } ?>
      </table>
    </div>

  </div>
  <script src="java/jquery.js"></script>
  <script src="java/main.js"></script>
</body>

<style>
  #list9 {
    background-color: white;
    color: black;
  }

  .searchRight {
    margin-left: 10%;
    padding: 0 5px;
  }
</style>