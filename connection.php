<?php ob_start();
session_start();

$a = 2; //$_SESSION['connection'];
$b = 2;
if ($a + $b == 4) {
  $con = mysqli_connect("localhost", "root", "", "parsonal");
} else {
  $con = mysqli_connect("localhost:3306", "root", "root", "parsonal");
}

if (mysqli_connect_error()) {
  echo "connection error";
}

$today = date("Y-m-d");
$serial = 0;

// save function------------------------------------------------------------
function checkData($con, $qry, $qrs)
{
  $rslt = mysqli_query($con, $qry);
  if (mysqli_num_rows($rslt) > 0) {
    echo "<script>alert('This Record Alrady In Hear');</script>";
  } else {
    saveData($con, $qrs);
  }
}

function saveData($con, $qrs)
{
  mysqli_query($con, $qrs);
}

// update function---------------------------------------------------------------------
function checkUpdate($con, $qry)
{
  $rslt = mysqli_query($con, $qry);

  if (mysqli_num_rows($rslt) > 0) {
    echo "<script>alert('This Record Alrady In Hear Not Updatable';</script>";
  }
}

function updateData($con, $rqr)
{
  mysqli_query($con, $rqr);
}

function cssValue($con, $whData)
{
  $rsa = mysqli_query($con, "SELECT * FROM `settings` WHERE `paramiter`='$whData'");
  $fsAccs = mysqli_fetch_assoc($rsa);
  return $fsAccs['value'];
}

$LenName = $_SESSION['LenName'] ?? "bn";
?>

<style>
  :root {
    --bodybgcolor:
      <?= cssValue($con, "body background-color") ?>
    ;
    --bodyfontcolor:
      <?= cssValue($con, "body font color") ?>
    ;

    --buttonbgcolor:
      <?= cssValue($con, "button background-color") ?>
    ;
    --btnfontcolor:
      <?= cssValue($con, "button font color") ?>
    ;
    --btnfontstyle:
      <?= cssValue($con, "button font-style") ?>
    ;

    --cardbgcolor:
      <?= cssValue($con, "card background-color") ?>
    ;
    --cardfontcolor:
      <?= cssValue($con, "card font color") ?>
    ;

    --childevenbgcolor:
      <?= cssValue($con, "child even color") ?>
    ;
    --childevenfontcolor:
      <?= cssValue($con, "child even font color") ?>
    ;
    --childoddbgcolor:
      <?= cssValue($con, "child odd color") ?>
    ;
    --childoddfontcolor:
      <?= cssValue($con, "child odd font color") ?>
    ;

    --fontfamily:
      <?= cssValue($con, "font-family") ?>
    ;
    --fontstyle:
      <?= cssValue($con, "font-style") ?>
    ;
    --fontsize:
      <?= cssValue($con, "font-size") ?>
    ;

    --inputselectbgcolor:
      <?= cssValue($con, "input, select background-color") ?>
    ;
    --inputselectfontcolor:
      <?= cssValue($con, "input, select font color") ?>
    ;

    --texttransform:
      <?= cssValue($con, "text-transform") ?>
    ;
    --leftdivbodybgcolor:
      <?= cssValue($con, "slidbar background-color") ?>
    ;

    --hoverBgColor:
      <?= cssValue($con, "hover background-color") ?>
    ;
    --hoverFontColor:
      <?= cssValue($con, "hover font color") ?>
    ;
    --box-shadow:
      <?= cssValue($con, "box-shadow") ?>
    ;
    --table-border:
      <?= cssValue($con, "table border") ?>
    ;
    --transition:
      <?= cssValue($con, "transition") ?>
    ;
    --gradient:
      <?= cssValue($con, "body gradient") ?>
    ;
  }

  * {
    transition: var(--transition);
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    text-decoration: none;
    outline: none;
    text-transform: var(--texttransform);
    font-family: var(--fontfamily);
    font-style: var(--fontstyle);
    font-size: var(--fontsize);
  }

  body {
    background-color: var(--bodybgcolor);
    color: var(--bodyfontcolor);
    background-image: var(--gradient);
  }

  .slideBar {
    background-color: var(--leftdivbodybgcolor);
  }

  input,
  select {
    background-color: var(--inputselectbgcolor);
    color: var(--inputselectfontcolor);
    border: 1px solid blue;
  }

  button {
    background-color: var(--buttonbgcolor);
    color: var(--btnfontcolor);
    font-style: var(--btnfontstyle);
    border: 1px solid blue;
  }

  table {
    border-collapse: collapse;
    width: 100%;
  }

  /* hover and focus */
  input:hover,
  select:hover,
  button:hover,
  a:hover {
    background-color: var(--hoverBgColor);
    color: var(--hoverFontColor);
    box-shadow: var(--box-shadow);
  }

  tr:hover {
    background-color: var(--hoverBgColor);
    color: var(--hoverFontColor);
    box-shadow: var(--box-shadow);
  }

  td:hover {
    background-color: var(--hoverBgColor);
    color: var(--hoverFontColor);
  }

  .form_div {
    padding: 5px;
  }

  input,
  select,
  button {
    padding: 5px;
    border-radius: 3px;
  }

  button {
    padding: 5px 8px;
  }

  .middle input,
  .middle select,
  .middle button {
    margin-top: 5px;
  }

  .labale_bar {
    background-color: rgb(75, 75, 248);
    padding: 5px;
    color: white;
  }

  .cecurity-form input {
    display: block;
    margin-bottom: 2px;
  }

  .cecurity-form {
    position: fixed;
    top: 50%;
    left: 50%;
    background-color: white;
    transform: translate(-50%, -50%);
    padding: 20px;
    box-shadow: 0 0 5px 2px black;
    border-radius: 5px;
  }

  @media only screen and (max-width:370px) {
    * {
      font-size: small;
    }

    h1 {
      padding: 5px;
      background-color: rgb(206, 160, 248);
    }

    input,
    select {
      width: 100px;
    }
  }

  @media only screen and (min-width:371px) {
    .slideBar {
      width: 10%;
      position: fixed;
      top: 0;
      left: 0;
      height: 100%;
      overflow: auto;
    }

    .middle {
      width: 20%;
      position: fixed;
      top: 0;
      left: 10%;
      overflow: auto;
      height: 100%;
    }

    .rightdiv {
      width: 70%;
      position: fixed;
      top: 0px;
      left: 30%;
      overflow: auto;
      padding-top: 35px;
      height: 100%;
    }

    h1 {
      position: fixed;
      top: 0;
      right: 0;
      width: 70%;
      padding: 5px;
      background-color: rgb(206, 160, 248);
    }

    .middletwo {
      width: 50%;
      position: fixed;
      top: 0;
      left: 30%;
      height: 100vh;
      overflow: auto;
      padding: 35px 5px 5px 5px;
    }

    .end {
      width: 20%;
      position: fixed;
      top: 0;
      left: 80%;
      padding-top: 35px;
      overflow: auto;
    }

    .middle_report {
      width: 42.5%;
      position: fixed;
      top: 30px;
      left: 15%;
      height: 100%;
      overflow: auto;
      padding: 5px;
    }

    .rightdiv_report {
      width: 42.5%;
      position: fixed;
      top: 30px;
      left: 57.5%;
      height: 100%;
      overflow: auto;
    }

    .h1_report {
      width: 90%;
    }

    .middle input,
    .middle select {
      width: 100%;
    }
  }

  .msg {
    position: fixed;
    top: 0;
    right: 0;
    padding: 5px;
    background-color: blue;
    color: white;
    border-radius: 5px;
    box-shadow: 0 0 5px black;
    display: none;
  }

  td {
    padding: 2px;
    white-space: nowrap;
  }

  th {
    text-align: left;
  }

  tr {
    border-bottom: 1px solid rgb(214, 196, 196);
  }

  .tblHolder {
    padding: 20px;
    border: 1px solid rgb(214, 196, 196);
    margin: 1px;
    border-radius: 5px;
  }

  td a {
    padding: 1px 3px;
    background-color: blue;
    color: white;
    border-radius: 2px;
  }

  .searchForm input[type="date"] {
    width: 120px;
  }

  body {
    counter-reset: tr;
  }

  tr {
    counter-increment: tr;
  }

  tr::before {
    content: counter(tr)".";
  }

  .linkdiv a::before {
    content: "✔ ";
  }
</style>

<link rel="shortcut icon" href="./image/favicon.jpg" type="image/x-icon" />

<script>
  var wordList = {
    en: {
      home: 'Home',
      expense: 'Expenses',
      income: 'Income',
      recomend: 'Recommend',
      savings: 'Savings',
      lendget: 'lendget',
      report: 'Reports',
      catagory: 'Categories',
      update: 'Update',
      search: 'Search',
      settings: 'Settings',
      userlog: 'Userlog',
    },

    bn: {
      home: 'বাড়ি',
      expense: 'ব্যয়',
      income: 'আয়',
      recomend: 'দরকার',
      savings: 'সঞ্চয়',
      lendget: 'পাব/দেব',
      report: 'রিপোর্ট',
      catagory: 'শ্রেণী',
      update: 'আপডেট',
      search: 'অনুসন্ধান',
      settings: 'সেটিংস',
      userlog: 'ব্যবহারকারীর',
    }
  }

  var cardHolder = {
    en: {
      income: 'Income',
      expense: 'Expenses',
      need: 'need',
      savings: 'Savings',
      extra: 'extra',
      totalincome: 'total income',
      totalexpense: 'total expense',
      totalsavings: 'total savings',
      totalextra: 'total extra',
      others: 'Others',
      wget: 'w.get',
      wpay: 'w.pay',
      stayed: 'stayed',
    },

    bn: {
      income: 'আয়',
      expense: 'ব্যয়',
      need: 'দরকার ',
      savings: 'সঞ্চয়',
      extra: 'অতিরিক্ত',
      totalincome: 'মোট আয়',
      totalexpense: 'মোট ব্যয়',
      totalsavings: 'মোট সঞ্চয়',
      totalextra: 'মোট অতিরিক্ত',
      others: 'অন্যান্য',
      wget: 'পাবো',
      wpay: 'দেবো',
      stayed: 'থাকলো',
    }
  }

  var forP = {
    en: {
      main: 'main form',
      others: 'others',
      title: 'parsonal account manegment system',
    },

    bn: {
      main: 'প্রধান ফর্ম',
      others: 'অন্যান্য',
      title: 'পার্সোনাল অ্যাকাউন্ট ম্যানেজমেন্ট সিস্টেম',
    }
  }

  function changeLanguage() {
    var languageName = "<?= $LenName; ?>"; //document.getElementById('langName').value;  

    var ancorList = document.querySelectorAll(".linkdiv a");
    var keyList = Object.keys(wordList[languageName]);
    ancorList.forEach(function (aRefarence, index) {
      aRefarence.textContent = wordList[languageName][keyList[index]];
    });



    var divList = document.querySelectorAll(".cardTitle");
    const divKeys = Object.keys(cardHolder[languageName]);
    divList.forEach((divRefarence, divIndex) => {
      divRefarence.textContent = cardHolder[languageName][divKeys[divIndex]];
    });


    var pList = document.querySelectorAll(".linkdiv p, .infobar p");
    const pKeys = Object.keys(forP[languageName]);
    pList.forEach((pRefarence, pIndex) => {
      pRefarence.textContent = forP[languageName][pKeys[pIndex]];
    });
  }

  document.addEventListener("DOMContentLoaded", function () {
    changeLanguage();
  });
</script>