<?php
$usertype = new Usertype();
?>

<body onload="showMenu('menu_browse')">
<nav class="navbar sticky-top navbar-expand-md navbar-dark bg-dark">
  <a class="navbar-brand" href="#">SZE-Musify</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-bs-toggle="collapse" data-bs-target="#myNavLol" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse navbarNav" id="myNavLol">
    <ul class="navbar-nav">
      <li class="nav-item active">
        <a class="nav-link" href="javascript:void(0);" id="menu_browse" onclick="showMenu(this.id)"><i class="fa fa-home" aria-hidden="true"></i> Home</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="javascript:void(0);" id="menu_albums" onclick="showMenu(this.id)"><i class="fa fa-align-justify" aria-hidden="true"></i> Albums</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="javascript:void(0);" id="menu_search" onclick="showMenu(this.id)"><i class="fa fa-search" aria-hidden="true"></i> Search</a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-user" aria-hidden="true"></i> <b> <?php echo($usertype->getLastname()); ?> </b>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href="#"><i class="fa fa-star" aria-hidden="true"></i> My Profile</a>
          <a class="dropdown-item" href="#"><i class="fa fa-cog" aria-hidden="true"></i> Settings</a>
          <a class="dropdown-item bg-primary text-white" href="#"><i class="fa fa-check" aria-hidden="true"></i> Subscribe to Musify</a>
          <form action="logout.php" method="POST" enctype='application/x-www-form-urlencoded'>
            <button type="submit" class="dropdown-item" name="kijelentkezes" id="kijelentkezes"><i class="fa fa-sign-out-alt" aria-hidden="true"></i> Log out</button>
          </form>
        </div>
      </li>
    </ul>
    
  </div>
</nav>

<div class="framepage" id="framepage">

</div>

</body>
    
