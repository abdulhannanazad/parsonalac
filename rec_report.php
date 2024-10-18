<?php include("inc/connection.php");

if (isset($_POST['month'])) {
  $month = $_POST['month'] . "-01";
  $_SESSION['reportDate'] = $month;
}

if (!empty($_SESSION['reportDate'])) {
  $sDate = $_SESSION['reportDate'];
}

if (isset($_POST['month'])) {
  $rse = mysqli_query($con, "SELECT sum(taka) as sumrec from `recomend` WHERE month(date) = month('$month') AND year(date) = year('$month') and `purpose`='need'");
  $rsb = mysqli_query($con, "SELECT sum(taka) as sumrexp from `expense` WHERE month(date) = month('$month') AND year(date) = year('$month')");
  $rsa = mysqli_query($con, "SELECT sum(taka) as sumrinc from `income` WHERE month(date) = month('$month') AND year(date) = year('$month')");

} elseif (!empty($_SESSION['reportDate'])) {

  $rse = mysqli_query($con, "SELECT sum(taka) as sumrec from `recomend` WHERE month(date) = month('$sDate') AND year(date) = year('$sDate') and `purpose`='need'");
  $rsb = mysqli_query($con, "SELECT sum(taka) as sumrexp from `expense` WHERE month(date) = month('$sDate') AND year(date) = year('$sDate')");
  $rsa = mysqli_query($con, "SELECT sum(taka) as sumrinc from `income` WHERE month(date) = month('$sDate') AND year(date) = year('$sDate')");

} else {

  $rse = mysqli_query($con, "SELECT sum(taka) as sumrec from `recomend` WHERE month(date) = month('$today') AND year(date) = year('$today') and `purpose`='need'");
  $rsb = mysqli_query($con, "SELECT sum(taka) as sumrexp from `expense` WHERE month(date) = month('$today') AND year(date) = year('$today')");
  $rsa = mysqli_query($con, "SELECT sum(taka) as sumrinc from `income` WHERE month(date) = month('$today') AND year(date) = year('$today')");
}

$rwe = mysqli_fetch_assoc($rse);
$recsum = $rwe['sumrec'];

$rwb = mysqli_fetch_assoc($rsb);
$exsum = $rwb['sumrexp'];

$rwa = mysqli_fetch_assoc($rsa);
$insum = $rwa['sumrinc'];
?>

<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Recoment Report</title>

<body>
  <div class="slideBar">
    <?php include("inc/linkbar.php"); ?>
  </div>

  <div class="reportPanal">
    <h1 class="h1_report">recomend Report</h1>

    <form method="post" style="text-align: center;">
      <input name="month" type="month" onchange="this.form.submit()"
        value="<?php if (!empty($sDate)) {
          echo date_format(date_create($sDate), "Y-m");
        } else {
          echo date_format(date_create(date("Y-m")), "Y-m");
        } ?>">
    </form>

      <div class="tblHolder">
        <table>
          <tr>
            <td>recoment:</td>
            <td>
              <?= number_format($recsum); ?>
            </td>
            <td>income:</td>
            <td>
              <?= number_format($insum); ?>
            </td>
          </tr>
          <tr>
            <td>expense:</td>
            <td>
              <?= number_format($exsum); ?>
            </td>
            <td>current:
              <?php if ($insum > $recsum) {
                echo " balance";
              } else {
                echo " short";
              }
              ; ?>
            </td>
            <td>
              <?php $cbalance = $insum - $exsum;
              echo number_format($cbalance); ?>
            </td>
          </tr>
          <tr>
            <td>expense
              <?php if ($recsum < $exsum) {
                echo " over";
              } else {
                echo " willbe";
              }
              ; ?>:
            </td>
            <td>
              <?php $wexp = $recsum - $exsum;
              echo number_format($wexp); ?>
            </td>
            <td>willbe:
              <?php if ($insum > $recsum) {
                echo " balance";
              } else {
                echo " short";
              }
              ; ?>
            </td>
            <td>
              <?php if ($recsum > $exsum) {
                echo number_format($cbalance - $wexp);
              } else {
                echo number_format($cbalance);
              }
              ; ?>
            </td>
          </tr>
        </table>
      </div>
  </div>

  <script src="java/jquery.js"></script>
  <script src="java/main.js"></script>
</body>

<style>
  .reportPanal {
    margin-left: 10%;
    margin-top: 40px;
  }

  #list3 {
    background-color: white;
    color: black;
  }

  .tblHolder{
    margin: 5px;
  }
</style>