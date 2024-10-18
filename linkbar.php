<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="link bar/slide/hiper link" />
  <meta name="keywords" content="link, slide, navigation" />
</head>

<body>
  <div class="linkdiv">
    <div class="homebutton">
      <a href="index.php">
        <img src="image/home.png">
        home
      </a>
    </div>

    <div class="dropdawn">
      <p>main form</p>
      <div class="listitem">
        <li><a href="expense.php" id="list1">expense</a></li>
        <li><a href="income.php" id="list2">income </a></li>
        <li><a href="recomend.php" id="list3">recomend </a></li>
        <li><a href="savings.php" id="list4">savings</a></li>
        <li><a href="lendget.php" id="list5"> lend/get</a></li>
        <li><a href="report.php" id="list6"> report</a></li>
        <li><a href="catagory.php" id="list7">catagory</a></li>
      </div>
    </div>

    <div class="dropdawn">
      <p>others</p>
      <div class="listitem">
        <li><a href="update.php" id="list8">update</a></li>
        <li><a href="search.php" id="list9">search</a></li>
        <li><a href="settings.php" id="list10">settings</a></li>
        <li><a href="login.php" id="list11">userlog</a></li>
      </div>
    </div>
  </div>

</body>
</html>


<style>
  :root{
    --leftdivfontcolor:<?= cssValue($con, "slidbar font color") ?>;
  }

  .dropdawn p {
    background-color: blueviolet;
  }

  .dropdawn a,
  .dropdawn p {
    color: var(--leftdivfontcolor);
    padding: 5px;
    display: block;
  }

  .homebutton a {
    display: flex;
    align-items: center;
    color: white;
    padding: 5px;
  }

  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    list-style-type: none;
    text-decoration: none;
  }

  @media only screen and (max-width:370px) {
    .linkdiv {
      display: flex;
      align-items: center;
      background-color: blue;
    }

    .dropdawn {
      position: relative;
    }

    .listitem {
      transform: translateY(10px); 
      position: absolute;
      top: 26px;
      left: 0;
      background-color: green;
      opacity: 0;
      transition: transform linear 0.5s, opacity linear 0.5s;
      pointer-events: none;
    }

    .homebutton img {
      width: 18px;
      height: 18px;
    }
    
    .dropdawn:hover .listitem{
      transform: translateY(0px);
      opacity: 1;
      pointer-events: auto;
      z-index: 999;
    }
  }

  @media only screen and (min-width:371px) {
    .homebutton img {
      width: 25px;
      height: 25px;
    }
  }
</style>