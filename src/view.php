<?php
    $conn   = new MongoClient('mongodb://127.0.0.1:27017');
    $col    = $conn->wooyun->bugs;

    $cursor = $col->findOne(array('wooyun_id' => $_GET['id']), array('html'));
    $result = array();
    foreach ($cursor as $row)
    {
    	$result[] = $row;
    }

    if (! $result)
    {
        die ('No such wooyun_id');
    }

    $php_url = preg_replace('/id=.*/', 'id=', "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");

    $html = $result[1];
    $html = str_replace ('/css/style.css', 'css/style.css', $html);
    $html = preg_replace ('/\/(images\/(m1|m2|m3|credit)\.png)/', '$1', $html);
    $html = str_replace ('http://www.wooyun.org/bugs/', $php_url, $html);
?>

<?= $html ?>