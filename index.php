<?php include("inc/connection.php"); //#create connection with database #this file import from inc folder and file name are connection.php

// Function to get sum of 'taka' based on the table name and optional conditions
function getSum($con, $table, $conditions = ''){
    $query = "SELECT SUM(taka) as sum_value FROM `$table` $conditions";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);
    return $row['sum_value'];
}

// Get today's date in Y-m-d format
$today = date('Y-m-d');

// Current month and year conditions
$currentMonthYear = "WHERE month(date) = month('$today') AND year(date) = year('$today')";

// Monthly sums
$insum = getSum($con, 'income', $currentMonthYear);
$exsum = getSum($con, 'expense', $currentMonthYear);
$svsum = getSum($con, 'savings', $currentMonthYear);
$reccalall = getSum($con, 'recomend', "$currentMonthYear AND `purpose`='need'");

// Total sums
$incalall = getSum($con, 'income');
$excalall = getSum($con, 'expense');
$svcalall = getSum($con, 'savings');
$lendcalall = getSum($con, 'lendget', "WHERE `drcr`='receive'");
$getcalall = getSum($con, 'lendget', "WHERE `drcr`='payment'");

if (isset($_POST['lnname'])) {
   $LenName = $_POST['lnname'];
   $_SESSION['LenName'] = $LenName;
   header("location: index.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>personal</title>
</head>

<body>
    <div class="slideBar">
        <?php include("inc/linkbar.php"); ?>
    </div>

    <div class="cardpanal">
        <div class="infobar">
            <p>parsonal account manegment system</p>
            <form method="post">
                <select name="lnname" onchange="this.form.submit(), changeLanguage()" id="langName">
                    <option value="<?= $LenName; ?>"><?= $LenName; ?></option>
                    <option value="bn">বাংলা</option>
                    <option value="en">english</option>
                </select>
            </form>
            <a href="about.php">about</a>
        </div>

        <div class="cardholder">
            <?php
            $cards = [
                'income' => $insum,
                'expense' => $exsum,
                'need' => $reccalall,
                'savings' => $svsum,
                'extra' => $insum - $exsum - $svsum,
                'total income' => $incalall,
                'total expense' => $excalall,
                'total savings' => $svcalall,
                'total extra' => $incalall - $excalall - $svcalall,
                'w.get' => $getcalall,
                'w.pay' => $lendcalall,
                'stayed' => $getcalall - $lendcalall
            ];

            foreach ($cards as $label => $value) {
                echo "<div class='card'>
                    <div class='cardTitle'>$label</div>
                    <div class='cardBody'>" . number_format($value) . "</div>
                </div>";
            }
            ?>
        </div>
    </div>

    <div class="connection">
        <p>database connection form</p>
        <form method="post" class="cform">
            <input name="number" type="number" placeholder="only 1 or 2 write down" value="<?php if (!empty($_SESSION['connection'])) {
                echo htmlspecialchars($_SESSION['connection']);
            } ?>" onchange="this.form.submit()" autofocus required>
            <button accesskey="c" title="alt + c" name="connection">connect</button>
        </form>
    </div>

    <script src="java/jquery.js"></script>
    <script src="java/main.js"></script>
</body>

</html>

<?php
if (isset($_POST['connection']) || isset($_POST['number'])) {
    $_SESSION['connection'] = $_POST['number'];
    echo "<script>window.location.href='index.php'</script>";
}
?>

<style>
    .connection {
        position: fixed;
        bottom: 0;
        right: 0;
    }

    .cardholder {
        padding: 0 10px;
        display: flex;
        flex-wrap: wrap;
    }

    .card {
        box-shadow: var(--box-shadow);
        ;
        text-align: center;
        margin: 10px 10px 0 0;
        background-color: var(--cardbgcolor);
        color: var(--cardfontcolor);
    }

    .cardTitle,
    .cardBody {
        padding: 5px 20px;
    }

    @media only screen and (max-width:370px) {
        .infobar {
            background-color: rgb(8, 8, 114);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .infobar p {
            color: white;
        }

        .infobar a {
            background-color: blue;
            color: white;
            padding: 5px;
        }
    }

    @media only screen and (min-width:371px) {
        .cardpanal {
            margin-left: 10%;
        }

        .infobar {
            background-color: blue;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .infobar p {
            color: white;
        }

        .infobar a {
            background-color: rgb(4, 4, 108);
            color: white;
            padding: 5px;
        }
    }
</style>
