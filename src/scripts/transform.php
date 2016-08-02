<?php
	$_POST  = json_decode(file_get_contents('php://input'), true);

    $conn   = new MongoClient('mongodb://127.0.0.1:27017');
    $col    = $conn->wooyun->bugs;

    $cursor = $col->find(
        array(),
      	array('wooyun_id', 'html')
    );

    foreach ($cursor as $row)
    {
        $attention_num = 0;
        $credit        = NULL;

        if (preg_match ('/attention_num".(\d+)/', $row['html'], $matches))
        {
            $attention_num = intval($matches[1]);
        }
        if (preg_match ('~<img src="/images/(\w+).png"[^>]+class="credit">~', $row['html'], $matches))
        {
            $credit = $matches[1];
        }
    	
        $col->update (
            array ('wooyun_id'  => $row['wooyun_id']),
            array ('$set' => array(
                'attention_num' => $attention_num,
                'credit'        => $credit
            ))
        );
    }


?>

