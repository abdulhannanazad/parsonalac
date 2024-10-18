<?php
include("inc/connection.php");

// Initialize variables
$monthlyIncomeTotal = $monthlyexpenseTotal = $monthlySavingsTotal = 0;
$sDate = $_SESSION['reportDate'] ?? date('Y-m-01');

// Handle form submission
if (isset($_POST['search']) || isset($_POST['month'])) {
  $month = $_POST['month'] . "-01";
  $_SESSION['reportDate'] = $month;
  $sDate = $month;
}

// Functions to fetch sum based on query
function fetchSum($con, $query)
{
  $result = mysqli_query($con, $query);
  $row = mysqli_fetch_assoc($result);
  return $row ? $row['sum'] : 0;
}

function overallSum($con, $ovqr)
{
  $rsk = mysqli_query($con, $ovqr);
  $rwk = mysqli_fetch_assoc($rsk);
  return $rwk ? $rwk['sum'] : 0;
}

// Fetch income sum
$qra = "SELECT SUM(taka) AS sum FROM `income` WHERE DATE_FORMAT(date, '%Y-%m') = DATE_FORMAT('$sDate', '%Y-%m')";
$monthlyIncomeTotal = fetchSum($con, $qra);

// Fetch expense sum
$qrb = "SELECT SUM(taka) AS sum FROM `expense` WHERE DATE_FORMAT(date, '%Y-%m') = DATE_FORMAT('$sDate', '%Y-%m')";
$monthlyexpenseTotal = fetchSum($con, $qrb);

// Fetch savings sum
$qrc = "SELECT SUM(taka) AS sum FROM `savings` WHERE DATE_FORMAT(date, '%Y-%m') = DATE_FORMAT('$sDate', '%Y-%m')";
$monthlySavingsTotal = fetchSum($con, $qrc);

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Report</title>
</head>

<body>
  <div class="slideBar">
    <?php include("inc/linkbar.php"); ?>
  </div>

  <div class="rightPanale">
    <div class="monthly">
      <h2>monthly report</h2>

      <form method="post" style="text-align: center;">
        <input style="width: fit-content;" name="month" type="month" onchange="this.form.submit()"
          value="<?= date('Y-m', strtotime($sDate)); ?>">
      </form>

      <div class="tblHolder">
        <table>
          <tr>
            <th>name</th>
            <th>income</th>
            <th>expense</th>
            <th>savings</th>
          </tr>

          <?php // Fetch income details
          $qrd = "SELECT `source`, SUM(taka) AS sumin FROM `income` WHERE DATE_FORMAT(date, '%Y-%m') = DATE_FORMAT('$sDate', '%Y-%m') GROUP BY `source` ASC";
          $rsd = mysqli_query($con, $qrd);
          while ($rwd = mysqli_fetch_assoc($rsd)) {
            echo "<tr>
                <td>" . $rwd['source'] . "</td>
                <td colspan='3'>" . $rwd['sumin'] . "</td>
                </tr>";
          }

          // Fetch expense details
          $qrf = "SELECT `purpose`, SUM(taka) AS sumex FROM `expense` WHERE DATE_FORMAT(date, '%Y-%m') = DATE_FORMAT('$sDate', '%Y-%m') GROUP BY `purpose` ASC";
          $rsf = mysqli_query($con, $qrf);
          while ($rwf = mysqli_fetch_assoc($rsf)) {
            $monthlyExpendSourceName = $rwf['purpose'];
            $monthlyExpendSourceTotal = $rwf['sumex'];
            ?>
            <tr>
              <td colspan='2'>
                <?= $monthlyExpendSourceName; ?>
              </td>
              <td colspan='2'>
                <?= number_format($monthlyExpendSourceTotal); ?>
              </td>
            </tr>
          <?php } ?>

          <?php
          // Fetch savings details
          $qrh = "SELECT `purpose`, SUM(taka) AS sumsv FROM `savings` WHERE DATE_FORMAT(date, '%Y-%m') = DATE_FORMAT('$sDate', '%Y-%m') GROUP BY `purpose` ASC";
          $rsh = mysqli_query($con, $qrh);
          while ($rwh = mysqli_fetch_assoc($rsh)) {
            $monthlySavingsSourceName = $rwh['purpose'];
            $monthlySavingsSourceTotal = $rwh['sumsv'];

            $monthlySavingsSourceNameTd = "<td colspan='3'>" . $monthlySavingsSourceName . "</td>";
            echo "<tr>"
              . $monthlySavingsSourceNameTd
              . "<td>" . number_format($monthlySavingsSourceTotal) . "</td>";
            ?>
            </tr>
          <?php } ?>
          <tr>
            <td colspan="4" style="height: 20px;background-color: aqua;"></td>
          </tr>
          <tr>
            <td>Total Income:</td>
            <td>
              <?= number_format($monthlyIncomeTotal); ?>
            </td>
            <td>
              <?= number_format($monthlyexpenseTotal); ?>
            </td>
            <td>
              <?= number_format($monthlySavingsTotal); ?>
            </td>
          </tr>
          <tr>
            <td>Cash In Hand:</td>
            <td colspan="3" style="text-align: center; font-weight: bold;">
              <?= number_format($monthlyIncomeTotal - $monthlyexpenseTotal - $monthlySavingsTotal); ?>
            </td>
          </tr>
        </table>
      </div>
    </div>





















    <div class="overall">
      <h2>overall Cash Report</h2>
      <div class="tblHolder">
        <table>
          <tr>
            <th>name</th>
            <th>income</th>
            <th>expense</th>
            <th>savings</th>
          </tr>

          <?php
          // Fetch details for overall income
          $rsg = mysqli_query($con, "SELECT `source`, SUM(taka) AS suminall FROM `income` GROUP BY `source` ASC");
          while ($rwg = mysqli_fetch_assoc($rsg)) {
            $nmall = $rwg['source'];
            $inall = $rwg['suminall'];

            echo "<tr>
                  <td>" . $nmall; ?>
            </td>
            <td colspan="3">
              <?= number_format($inall); ?>
            </td>
            </tr>

          <?php }
          $rsi = mysqli_query($con, "SELECT `purpose`, SUM(taka) AS sumexall FROM `expense` GROUP BY `purpose`");
          while ($rwi = mysqli_fetch_assoc($rsi)) {
            $nmex = $rwi['purpose'];
            $exall = $rwi['sumexall'];
            ?>
            <tr>
              <td colspan="2">
                <?= $nmex; ?>
              </td>
              <td colspan="2">
                <?= number_format($exall); ?>
              </td>
            </tr>
            <?php
          }
          ?>

          <?php
          // Fetch details for overall savings
          $qria = "SELECT `purpose`, SUM(taka) AS sumsvall FROM `savings` GROUP BY `purpose` ASC";

          function working($con, $qria)
          {
            $rsia = mysqli_query($con, $qria);
            $tary = array();
            while ($rwia = mysqli_fetch_assoc($rsia)) {
              $nmsvary[] = $rwia;
            }
            return $nmsvary;
          }

          $callFunc = working($con, $qria);
          foreach ($callFunc as $row) {
            $nmsv = $row['purpose'];
            $exalla = $row['sumsvall'];
            ?>
            <tr>
              <td colspan="3">
                <?= $nmsv; ?>
              </td>
              <td>
                <?= number_format($exalla); ?>
              </td>
            </tr>
          <?php } ?>
          <tr>
            <td colspan="4" style="height: 20px;"></td>
          </tr>

          <tr>
            <td>Total</td>
            <?php


            // Fetch overall income, expense, savings
            $ovqr = "SELECT SUM(taka) AS sum FROM `income`";
            $monthlyIncomeTotalall = overallSum($con, $ovqr);

            $ovqr = "SELECT SUM(taka) AS sum FROM `expense`";
            $monthlyexpenseTotalall = overallSum($con, $ovqr);

            $ovqr = "SELECT SUM(taka) AS sum FROM `savings`";
            $monthlySavingsTotalall = overallSum($con, $ovqr);

            $totalAry = [
              'income' => number_format($monthlyIncomeTotalall),
              'expense' => number_format($monthlyexpenseTotalall),
              'savings' => number_format($monthlySavingsTotalall)
            ];
            foreach ($totalAry as $key => $value) {
              echo "<td>" . $value . "</td>";
            }
            ?>
          </tr>
          <tr>
            <td>Cash In Hand:</td>
            <td colspan="3" style="text-align: center; font-weight: bold;">
              <?= (number_format($monthlyIncomeTotalall - $monthlyexpenseTotalall - $monthlySavingsTotalall)); ?>
            </td>
          </tr>
        </table>
      </div>
    </div>
  </div>

  <style>
    #list6 {
      background-color: white;
      color: black;
    }

    @media only screen and (max-width:370px) {
      h2 {
        background-color: rgb(0, 0, 113);
        color: white;
        padding: 5px;
        text-align: center;
        margin-bottom: 2px;
      }

      .overall h2 {
        margin: 0;
        margin-top: 2px;
      }
    }

    @media only screen and (min-width:371px) {
      .rightPanale {
        margin-left: 10%;
        display: grid;
        grid-template-columns: 50% 50%;
      }

      h2 {
        background-color: blue;
        color: white;
        padding: 5px;
        text-align: center;
        border-right: 1px solid white;
        margin-bottom: 2px;
      }
    }
  </style>