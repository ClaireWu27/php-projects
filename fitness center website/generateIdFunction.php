<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
    // define a function to generate unique id for each table
    function generateUniqueId($prefix)
    {
        //generate 4 digits random number
        $randomNum = mt_rand(1000, 9999);
        //get last 3 digits of current timestamp 
        $timestamp = time() % 1000;
        // combine them as a unique id
        return $prefix . $randomNum . $timestamp;
    }
    ?>
</body>

</html>