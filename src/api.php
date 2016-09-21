<?php
include (dirname (__FILE__) . '/config.php');

$_POST = json_decode(file_get_contents('php://input'), true);
$limit = intval(@$_POST['limit']);
$skip  = intval(@$_POST['skip']);
if ($skip < 0) {
    $skip = 0;
}

$conn = new MongoClient($config['mongodb']);
$col  = $conn->wooyun->bugs;

$where = array(
    '$and' => array()
);
if (isset($_POST['credit']) && $_POST['credit'] != 'any') {
    $where['$and'][] = array('credit' => $_POST['credit']);
}
if (isset($_POST['keyword'])) {
    $where['$and'][] = array('$or' => array(
        array('title'     => new MongoRegex('/' . $_POST['keyword'] . '/i')),
        array('wooyun_id' => new MongoRegex('/' . $_POST['keyword'] . '/i'))
    ));
}
// var_dump ($where);

$cursor = $col->find(
    $where,
    array('_id', 'title', 'datetime', 'datetime_open', 'wooyun_id', 'attention_num', 'credit')
);
$cursor->limit($limit);
$cursor->skip($skip);
$cursor->sort(array('datetime' => -1));

$result = array();
foreach ($cursor as $row) {
    $result[] = $row;
}

echo json_encode(
    array(
        'data'  => $result,
        'count' => $cursor->count(),
    )
);
