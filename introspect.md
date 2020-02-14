
### List Layouts
```
$layouts = $fm->listLayouts();
// If an error is found, return a message and exit.
if (FileMaker::isError($layouts)) {
    printf("Error %s: %s\n", $layouts->getCode());
    "<br>";
    printf($layouts->getMessage());
    exit;
}
// Print out layout names
foreach ($layouts as $layout) {
    echo $layout . "<br>";
}
```