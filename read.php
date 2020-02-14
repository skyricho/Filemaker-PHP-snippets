<?php 
include ("../dbaccess.php");

$request = $fm->newFindAllCommand('LayoutName');
$result = $request->execute();


$records = $result->getRecords();
foreach($records as $record) {
    echo $record->getField('recID') . '<br>';
}

?>