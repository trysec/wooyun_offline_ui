<?php
	$_POST  = json_decode(file_get_contents('php://input'), true);

    $conn   = new MongoClient('mongodb://127.0.0.1:27017');
    $col    = $conn->wooyun->bugs;

    $cursor = $col->find(
        array(),
      	array('html')
    );

    foreach ($cursor as $row)
    {
        // echo $row['html'], "\n";

        if (preg_match ('#<img src="(http://static.wooyun.org/wooyun/(upload/\d+/)[^"]+)#', $row['html'], $matches))
        {
            echo $matches[1], ' ', $matches[2], "\n";
        }
    }


?>

