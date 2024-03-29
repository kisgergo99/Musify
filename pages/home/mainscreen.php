<?php
$usertype = new Usertype();

?>
<body class="mainscreenframe">
<nav class="p-3 navbar sticky-top navbar-expand-md navbar-dark bg-dark justify-content-around">
  <a class="navbar-brand" href="#">SZE-Musify</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-bs-toggle="collapse" data-bs-target="#myNavLol" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse navbarNav" id="myNavLol">
    <ul class="navbar-nav ">
      <li class="nav-item active">
        <a class="nav-link clickableMenu" href="javascript:void(0);" id="menu_browse" ><i class="fa fa-home" aria-hidden="true"></i> Home</a>
      </li>
      <li class="nav-item">
        <a class="nav-link clickableMenu" href="javascript:void(0);" id="menu_albums"><i class="fa fa-align-justify" aria-hidden="true"></i> Albums</a>
      </li>
      <li class="nav-item">
        <a class="nav-link clickableMenu" href="javascript:void(0);" id="menu_search" ><i class="fa fa-search" aria-hidden="true"></i> Search</a>
      </li>
      <li class="nav-item dropdown justify-content-end">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-user" aria-hidden="true"></i> <b> <?php echo($usertype->getLastname()); ?> </b>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
          <?php if($_SESSION['user']['type'] == 'distributor'){echo '<a class="dropdown-item" href="/musify/pages/admin"><i class="fa fa-cog" aria-hidden="true"></i> Upload Music</a>';} ?>
          <?php if($_SESSION['user']['type'] == 'admin'){echo '<a class="dropdown-item" href="/musify/pages/admin"><i class="fa fa-cog" aria-hidden="true"></i> Admin Panel</a>';} ?>
          <?php if(!$usertype->isSubscribed($usertype->getUsername())){echo '<a class="dropdown-item bg-primary text-white" href="?givesub=1"><i class="fa fa-check" aria-hidden="true"></i> Subscribe to Musify</a>';} ?>
          <form action="logout.php" method="POST" enctype='application/x-www-form-urlencoded'>
            <button type="submit" class="dropdown-item" name="kijelentkezes" id="kijelentkezes"><i class="fa fa-sign-out-alt" aria-hidden="true"></i> Log out</button>
          </form>
        </div>
      </li>
    </ul>
    
  </div>
</nav>

<div class="framepage" id="framepage" style="padding-bottom: 140px; padding-left: 2%; padding-right: 2%"></div>
<link rel="stylesheet" href="/musify/pages/CSS/webplayer.css">

<footer>
<div class="webplayer-container" id="webplayer-container" style="color:black;">
    <audio preload="metadata">
        <source src="" preload="metadata" type="audio/mp3" id="sourceControl">
    </audio>
    <table class="webplayer-table">
        <tr>
          <td rowspan="2" class="webplayer-metadata-display">
            <img src="/musify/images/defaultart.png" class="music-artwork" />
            <div class="webplayer-metadata-text">
              <h3 class="music-artist-name">No music loaded</h3>
              <h6 class="music-track-name">No music loaded</h6>
            </div>
          </td>
          <td>
            <div style="margin-left: 23%; margin-top: 20px;">
              <button id="playpause" class="playerbutton"></button>
              <span id="current-time" class="time">0:00</span>
              <input type="range" id="music-slider" max="100" value="0">
              <span id="duration" class="time">0:00</span>
            </div>
          </td>
        </tr>
        <tr>
          <td>
            <div style="margin-left: 34%;">
              <button id="mute-icon" class="playerbutton"></button>
              <input type="range" id="volume-slider" max="100" value="70">
            </div>
          </td>
        </tr>
        
    </table>
    
    
    
</div>

<script src="/musify/pages/JS/webplayer.js" type="module"></script>


</body>

<?php

if(isset($_GET['givesub'])){
  $usertype->giveSub($usertype->getUsername(), 1);
  redirect("../");
}

?>
    
