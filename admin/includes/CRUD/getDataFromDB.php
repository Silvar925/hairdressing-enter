<?php
require '../include.php';
$id = $_POST['id'];
$tableName = $_POST['tableName'];

function getDataByIdAndTable($id, $tableName) {
    $record = R::load($tableName, $id);
    return $record;
}

if ($id == ''){
    $data = R::findAll($tableName);
} else {
    $data = getDataByIdAndTable($id, $tableName);
}

echo json_encode($data);