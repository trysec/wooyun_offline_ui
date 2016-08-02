<?php
$_POST = json_decode(file_get_contents('php://input'), true);
$limit = intval(@$_POST['limit']);
$skip  = intval(@$_POST['skip']);
if ($skip < 0) {
    $skip = 0;
}

$conn = new MongoClient('mongodb://127.0.0.1:27017');
$col  = $conn->wooyun->bugs;

$where = array();
if (isset($_POST['credit']) && $_POST['credit'] != 'any') {
    $where['credit'] = $_POST['credit'];
}
if (isset($_POST['keyword'])) {
    $where['title'] = new MongoRegex('/' . $_POST['keyword'] . '/');
}

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
