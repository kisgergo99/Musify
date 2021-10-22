<?php
$usertype = new Usertype();
?>

<body>
    <h1>Welcome back! <?php echo($usertype->getUsername()); ?></h1>
</body>