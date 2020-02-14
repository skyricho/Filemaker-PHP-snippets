# Filemaker PHP snippets


THis is intended as a quick referece to us the Filemaker PHP API

### Get Record by ID
```
# Get updated record to update the list item
$record = $fm->getRecordByID("AddressList", $_POST["id"]);
```

### Find all records
```
$request = $fm->newFindAllCommand('LayoutName');
$result = $request->execute();


$records = $result->getRecords();
foreach($records as $record) {
    echo $record->getField('recID') . '<br>';
}
```

### Find records
```
$request = $fm->newFindCommand('LayoutName');
$request->addFindCriterion('field', 'value'; 
$request->addSortRule('field', 'value');
$result = $request->execute();
```

## Find a single record
```
$record = $records[0];
```

## Trap for errors
Newer version
```
if (FileMaker::isError($result)) {
        if (! isset($result->code) || strlen(trim($result->code)) < 1) {
            echo 'A System Error Occured';
        } else {
            echo 'No Records Found (Error Code: '. $result->code. ')';
        }
    } else {
        // Delete claim
        $records = $result->getRecords();
        foreach($records as $record) {
            $rec = $fm->getRecordById('claimPHP', $record->getField('iD'));
            $rec->delete();
        }
    }
```


Older version
```
if (FileMaker::isError($result)) {
    echo "<p>Error: " . $result->getMessage() . "</p>"; exit;
}
```

### Create an array wtih found records
```
$records = $result->getRecords();
foreach($records as $record) {
    echo $record->getField('recID') . '<br>';
}

// or as an array
$var = array();
foreach($records as $record) {
    $var[] = array(
        'recID' => $record->getField('recID'),
        'foo' => $record->getField('foo'),
    );
}

// print array
echo '<pre>'; print_r($var); echo '</pre>';

// or as JSON
$var = array();
foreach($records as $record) {
    $var[] = $record->getField('Block');
}
echo json_encode($var);
```




### Insert record
```
$request = $fm->createRecord('web');
$request->setField('firstName', 'foo');
$result=$request->commit();

// see https://stackoverflow.com/questions/34757462/filemaker-php-api-get-id-of-newly-created-record
$request = $request->getRecordID();
//echo $request . '<br>';
$record = $fm->getRecordByID('web', $request);

echo 'Record ' . $record->getField('id') . ' has been inserted. First Name: ' . $record->getField('firstName');
```


### Iterate records object
```
$records = $result->getRecords();
foreach ($records as $record) {
echo $record->getField('FirstName') .' ' . $record->getField('LastName') . '<br>;
}
```

### Iterate records with Portal records
```
$records = $result->getRecords();
foreach($records as $record) {
    echo $record->getField('firstName') . ' ' . $record->getField('status') . '<br>';
    $related_records = $record->getRelatedSet('StandingRequest');
    foreach($related_records as $related_record) {
        echo $related_record->getField('StandingRequest::itemCode') . '<br>';
    }
}

or 

$records = $result->getRecords();
$var = array();
foreach($records as $record) {
    $related_records = $record->getRelatedSet('StandingRequest');
    $var1 = array();
    foreach($related_records as $related_record) {
        $var1[] = array(
        'itemCode' => $related_record->getField('StandingRequest::itemCode'),
        'quantity' => $related_record->getField('StandingRequest::quantity'),
        'languageCode' => $related_record->getField('StandingRequest::languageCode')
        );
    }

    $var[] = array(
        'firstName' => $record->getField('firstName'),
        'status' => $record->getField('status'),
        'StandingRequests' => $var1
    );

}

print_r($var);
```

## Sort
FileMaker API Syntax: addSortRule(‘FieldName’, Precedence, Order)
```
$request->addSortRule($_GET[‘sortby’], 1, FILEMAKER_SORT_ASCEND); Precedence is a numbered priority order 1, 2, 3, etc.
Order = FILEMAKER_SORT_ASCEND (default if not specified) or FILEMAKER_SORT_DESCEND
```

## Compund Find
```

// Create the Compound Find command object
$compoundFind = $fm->newCompoundFindCommand('Form View');
// Create first find request
$findreq1 = $fm->newFindRequest('Form View');
// Create second find request
$findreq2 = $fm->newFindRequest('Form View');
// Create third find request
$findreq3 = $fm->newFindRequest('Form View');
// Specify search criterion for first find request $findreq1->addFindCriterion('Quantity in Stock', '<100');
// Specify search criterion for second find request $findreq2->addFindCriterion('Quantity in Stock', '0'); $findreq2->setOmit(true);
// Specify search criterion for third find request $findreq3->addFindCriterion('Cover Photo Credit', 'The London Morning News'); $findreq3->setOmit(true);
// Add find requests to compound find command
$compoundFind->add(1,$findreq1);
$compoundFind->add(2,$findreq2);
$compoundFind->add(3,$findreq3);
// Set sort order
$compoundFind->addSortRule('Title', 1, FILEMAKER_SORT_DESCEND);
// Execute compound find command
$result = $compoundFind->execute();
// Get records from found set
$records = $result->getRecords();
```


### Display value list
```
$layout =& $fm->getLayout('AddressList');
$navBlocks = $layout->getValueListTwoFields('BlockWithCount', $navRecID);
foreach($navBlocks as $displayNavBlock => $navBlock) {
    echo '<a class="dropdown-item" href="ah.php?Map=' . $_GET['Map'] . '&Block=' . $navBlock . '&Filter=' . $_GET['Filter'] . '">Block ' . $displayNavBlock . '</a>'; 
}    
```

### Update Record
```
$request = $fm->newEditCommand('web', $_POST['id']);
	$request->setField('status', 'received']);
	$request->execute();

	echo $firstName . ' marked as received.';
```

### Delete record
```
$rec = $fm->getRecordById('Form View', $rec_ID);
$rec->delete()
```
or
```
$newDelete =& $fm->newDeleteCommand('Respondent', $rec_ID); 
$result = $newDelete->execute();
```

### Perform Script
```
//CREATE FILEMAKER OBJECT
$fm = new FileMaker($database, $hostname, $username, $password);
 
//CREATE PERFORM SCRIPT COMMAND
$command = $fm->newPerformScriptCommand($layoutName, $scriptName, $scriptParameter);
 
//EXECUTE THE COMMAND
$result = $command->execute();
```

### Display PHP error
```
ini_set('display_errors', 1);

```

### Measuring PHP Page Load Time
```
# Top of script
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$start = $time;

# Bottom of script
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$finish = $time;
$total_time = round(($finish - $start), 4);
echo 'Page generated in '.$total_time.' seconds.';
```

