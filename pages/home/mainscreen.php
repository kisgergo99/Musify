<?php
$usertype = new Usertype();
?>

<body>
    <h1>Welcome back! <?php echo($usertype->getUsername()); ?> :)</h1>
    <form action="logout.php" method="POST" enctype='application/x-www-form-urlencoded'>
        <input type="submit" name="kijelentkezes" id="kijelentkezes" value="Sign Out" class="btn btn-primary"/>
    </form>
</body>