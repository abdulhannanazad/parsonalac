<?php include("inc/connection.php");

$stable = $_SESSION['colamlist']['tablename'] ?? "";
$scolm = $_SESSION['colamlist']['colamname'] ?? "";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <div class="slideBar">
        <?php include("inc/linkbar.php"); ?>
    </div>

    <div class="updatepanal">
        <h2>update panale</h2>
        <div class="updatediv">
            <p>all coloumn</p>
            <form method="post" autocomplete="off" style="margin-bottom: 5px;">
                <input name="aa" placeholder="table name">
                <input name="bb" placeholder="coloumn name">
                <input name="cc" placeholder="updatable data">
                <button name="" id="upda">update</button>
            </form>

            <p>selected coloumn and data</p>
            <form method="post" autocomplete="off">
                <input name="dd" placeholder="table name" list="table">
                <input name="ee" placeholder="coloumn name">
                <input name="ff" placeholder="updating data" list="updating">
                <input name="gg" placeholder="where coloumn">
                <input name="hh" placeholder="where data" list="where">
                <button name="" id="updb">update</button>
            </form>
        </div>

        <div class="deletediv">
            <h2>delete panale</h2>
            <form method="post">
                <input name="ii" placeholder="table name">
                <input name="jj" placeholder="column name">
                <input name="kk" placeholder="deletable data">
                <button name="" id="delete">delete</button>
            </form>
        </div>

        <div class="listdiv">
            <h2>tabil and coloumn list</h2>
            <form method="post">
                <input name="aa" placeholder="table" value="<?= $stable; ?>">
                <input name="bb" placeholder="column" value="<?= $scolm; ?>">
                <button name="savedata">save</button>
            </form>
            <div class="tblHolder">
                <table>
                    <?php $tqr = mysqli_query($con, "SELECT * FROM `tablecolam` group by tablename desc");
                    while ($trw = mysqli_fetch_assoc($tqr)) {
                        $gtable = $trw['tablename']; ?>
                        <tr>
                            <td>
                                <?= $gtable; ?>
                            </td>
                            <td>
                                <?php $tqra = mysqli_query($con, "SELECT * FROM `tablecolam` where `tablename`='$gtable'");
                                while ($trwa = mysqli_fetch_assoc($tqra)) { ?>
                                    <div class="tblHolder">
                                        <table>
                                            <tr>
                                                <td>
                                                    <?= $trwa['colamname']; ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
        </div>
    </div>

    <script src="java/jquery.js"></script>
    <script src="java/main.js"></script>
</body>

</html>

<style>
    #list8 {
        background-color: white;
        color: black;
    }

    input,
    button,
    select {
        margin: 2px 2px 0 0;
    }

    input,
    button,
    select,
    p,
    h2 {
        padding: 5px;
    }

    p {
        background-color: rgb(4, 4, 131);
        color: white;
    }

    .updatediv,
    .deletediv,
    .listdiv {
        border: 1px solid blue;
        margin: 5px;
    }

    h2 {
        background-color: blue;
        color: white;
    }

    td,
    th {
        text-align: left;
        padding: 3px;
    }

    @media only screen and (max-width:370px) {
        margin-left: 0;
    }

    @media only screen and (min-width:371px) {
        .updatepanal {
            margin-left: 10%;
        }
    }
</style>

<script>
    document.getElementById("upda").addEventListener("click", function () {
        if (confirm("are you seur coloumn")) {
            document.getElementById("upda").setAttribute("name", "upda");
        }
    });
</script>

<?php
if (isset($_POST['savedata'])) {
    $aa = $_POST['aa'];
    $bb = $_POST['bb'];
    $_SESSION['colamlist'][0] = array('table' => $aa, 'colam' => $bb);

    $qry = "SELECT * FROM `tablecolam` WHERE `tablename`='$aa' && `colamname`='$bb'";
    $qrs = "INSERT INTO `tablecolam`(`tablename`, `colamname`) VALUES ('$aa','$bb')";
    checkData($con, $qry, $qrs);
    echo ("<script>window.location.href = 'update.php';</script>");
}

if (isset($_POST['upda'])) {
    $aa = $_POST['aa'];
    $bb = $_POST['bb'];
    $cc = $_POST['cc'];
    $qra = "UPDATE `$aa` SET `$bb`='$cc'";
    if (mysqli_query($con, $qra)) {
        echo ("<script>alert('record update success');</script>");
    }
} ?>

<script>
    document.getElementById("updb").addEventListener("click", function () {
        if (confirm("are you seur with selectiv data")) {
            document.getElementById("updb").setAttribute("name", "updb");
        }
    });
</script>

<?php
if (isset($_POST['updb'])) {
    $dd = $_POST['dd'];
    $ee = $_POST['ee'];
    $ff = $_POST['ff'];
    $gg = $_POST['gg'];
    $hh = $_POST['hh'];
    $qrb = "UPDATE `$dd` SET `$ee`='$ff' WHERE `$gg`='$hh'";
    if (mysqli_query($con, $qrb)) {
        echo ("<script>window.location.href='update.php';</script>");
    } else {
        echo ("<script>
alert('record update faild by data');
window.location.href = 'update.php';
</script>");
    }
}

if (isset($_POST['save'])) {
    $aa = $_POST['aa'];
    $bb = $_POST['bb'];
    $qrsave = "INSERT INTO `tablelist`(`table`, `column`) VALUES ('$aa','$bb')";
    if (mysqli_query($con, $qrsave)) {
        echo ("<script>alert('save success');
window.location.href = 'update.php';
</script>");
    } else {
        echo ("<script>alert('record faild');</script>");
    }
}
?>

<script>
    document.getElementById("delete").addEventListener("click", function () {
        if (confirm("are you seur to delete all data")) {
            document.getElementById("delete").setAttribute("name", "delete");
        }
    });
</script>

<?php
if (isset($_POST['delete'])) {
    $ii = $_POST['ii'];
    $jj = $_POST['jj'];
    $kk = $_POST['kk'];
    $qrc = "DELETE FROM `$ii` WHERE `$jj`='$kk'";
    if (mysqli_query($con, $qrc)) {
        echo ("<script>alert('all record delete success by data');
window.location.href='update.php';
</script>");
    } else {
        echo ("<script>
alert('record delete faild by data');
window.location.href = 'update.php';
</script>");
    }
}
?>