<?php 
include ("../dbaccess.php");

if (isset($_POST['submit'])) {
	$request = $fm->createRecord('AddressChange');
	$request->setField('streetNumber', $_POST['streetNumber']);
	$request->setField('streetName', $_POST['streetName']);
	$request->setField('map', $_POST['map']);
	$request->setField('block', $_POST['block']);
	$request->setField('userName', $_POST['userName']);
	$request->setField('date', date('n/j/Y'));
	$request->setField('action', $_POST['action']);
	$result=$request->commit();

	$request = $request->getRecordID();
	$record = $fm->getRecordByID('AddressChange', $request);
	//echo $request;

	echo $record->getField("streetNumber") . ' ' . $record->getField("streetName") . ' (Map ' . $record->getField("map") . ' Block ' . $record->getField("block") . ') [' . $record->getField("userName") . ']<hr>';
}

echo '<form action="create-address.php" method="POST">
  Street number:<br>
  <input type="text" name="streetNumber" value="">
  <br>
  Street name:<br>
  <input type="text" name="streetName" value="">
  <br>
  Map:<br>
  <input type="text" name="map" value="">
  <br>
  Block:<br>
  <input type="number" name="block" value="">
  <br>
  Name:<br>
  <input type="text" name="userName" value="">
  <br>
  Action:<br>
  <input type="text" name="action" value="create">
  <br>
  <br>
  <input type="submit" name="submit" value="Submit">
</form>'; 

?>