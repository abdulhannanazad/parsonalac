<?php include("inc/connection.php"); ?>
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

  <div class="settingspanal">
    <div class="savediv" hidden>
      <p>paramiter and value save</p>
      <form method="get">
        <input name="prm" placeholder="Paramiter" required>
        <input name="val" placeholder="Value" required>
        <button type="submit" name="save">save</button>
      </form>
    </div>

    <div class="paramiter">
      <div class="tblHolder">
        <table>
          <tr>
            <th>css property</th>
            <th>Value</th>
            <th>update</th>
          </tr>
          <?php $rqr = "SELECT * FROM `settings` order by paramiter asc";
          $rslt = mysqli_query($con, $rqr);
          while ($rw = mysqli_fetch_assoc($rslt)) { ?>
            <tr>
              <td>
                <?= $rw['paramiter']; ?>
              </td>
              <td>
                <?= $rw['value']; ?>
              </td>
              <td style="width: 30px;"><a href="settings.php?sl=<?= $rw['sl']; ?>&brows">update</a>
              </td>
            </tr>
          <?php } ?>
        </table>
      </div>

      <div class="property">
        <div class="tblHolder">
          <table>
            <tr>
              <th>css property</th>
              <th>value</th>
            </tr>
            <tr>
              <td>color</td>
              <td><input type="color"></td>
            </tr>
            <tr>
              <td>gradiant</td>
              <td>linear-gradient(150deg,red,green,blue)</td>
            </tr>
            <tr>
              <td>box-shadow</td>
              <td>1px(vartical) 1px(horgintal) 5px(fade(animation)) 1px(width) black(color)</td>
            </tr>
            <tr>
              <td>font family</td>
              <td>segoe print, ink free regular, comic sans ms, sans-serif</td>
            </tr>
            <tr>
              <td>font style</td>
              <td>bold, italic, small, medium, large</td>
            </tr>
            <tr>
              <td>font size</td>
              <td>10px, 15px, 20px, 25px</td>
            </tr>
            <tr>
              <td>border</td>
              <td>1px(width) solid(dashed,dotted) cyan(color)</td>
            </tr>
            <tr>
              <td>text transform</td>
              <td>capitalize, uppercase, lowercase</td>
            </tr>
            <tr>
              <td>transition</td>
              <td>all(css property) linear(ease, ease-in-out, step-end) 0.5s(time)</td>
            </tr>
          </table>
        </div>
      </div>
    </div>
  </div>
</body>

</html>

<style>
  #list10 {
    background-color: white;
    color: black;
  }

  .updateform button {
    padding: 5px;
    border-radius: 5px;
  }

  .updateform input {
    display: block;
    margin-bottom: 5px;
    padding: 5px;
    border-radius: 5px;
  }

  .updateform {
    background-color: white;
    padding: 20px;
    border-radius: 5px;
    box-shadow: var(--box-shadow);
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
  }

  .property {
    width: 90%;
    margin: 0 auto;
    margin-top: 5px;
  }

  @media only screen and (min-width:371px) {
    .settingspanal {
      margin-left: 10%;
      padding: 5px;
    }
  }
</style>

<?php
if (isset($_GET['brows'])) {
  $sl = $_GET['sl'];
  $rs = mysqli_query($con, "SELECT * FROM `settings` WHERE `sl`='$sl'");
  $today = mysqli_fetch_assoc($rs);
  $sl = $today['sl'];
  $paramiter = $today['paramiter'];
  $value = $today['value'];
  echo ("<form method='get' class='updateform'>
      <p>css value settings</p>
      <input name='sl' Value='$sl' hidden readonly>
      <input name='pmt' Value='$paramiter' readonlyy>
      <input name='valu' Value='$value' required autofocus>
      <button type='submit' name='update'>update</button>
      <button type='submit' name='delete' hidden>delete</button>
</form>");
}

if (isset($_GET['save'])) {
  $prm = $_GET['prm'];
  $val = $_GET['val'];
  $qry = "INSERT INTO `settings`(`paramiter`, `value`) VALUES ('$prm','$val')";
  if (mysqli_query($con, $qry)) {
    echo ("<script>window.location.href='settings.php';</script>");
  } else {
    echo "sarver down";
  }
}

if (isset($_GET['update'])) {
  $sl = $_GET['sl'];
  $pmt = $_GET['pmt'];
  $valu = $_GET['valu'];
  $qru = "UPDATE `settings` SET `paramiter`='$pmt',`value`='$valu' WHERE `sl`='$sl'";
  if (mysqli_query($con, $qru)) {
    echo ("<script>window.location.href = 'settings.php';</script>");
  }
}

if (isset($_GET['delete'])) {
  $sl = $_GET['sl'];
  $qru = "DELETE FROM `settings` WHERE `sl`='$sl'";
  if (mysqli_query($con, $qru)) {
    echo ("<script>window.location.href = 'settings.php';</script>");
  }
}
?>