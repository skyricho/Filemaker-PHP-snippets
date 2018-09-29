# Filemaker PHP snippets

```
// Insert record
$form = $fm->createRecord('Layout');
$form->setField('field', 'value');
$result=$form->commit();

$form = $form->getRecordID();
//echo $form . '<br>';
$record = $fm->getRecordByID('Layout', $form);
```
