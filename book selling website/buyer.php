<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>

</head>

<body>
    <form action="buyer.php" method="post">
        <input type="submit" name="basic" value="onsale textbook basic info" />
    </form>
    </form>
    <br />
    <?php
  if (isset($_POST['basic'])) {
    include ('basic.php');
  }


  ?>

</body>

</html>