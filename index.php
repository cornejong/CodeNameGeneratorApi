<?php

require_once 'class.CodeNameGenerator.php';

/* Get a new CodeName Generator */
$generate = new Generator\codeName;
/* Check if there are any specifications given */
if(isset($_GET['atribute']) && !empty($_GET['atribute']))   $generate->givenAtribute = $_GET['atribute'];
if(isset($_GET['object']) && !empty($_GET['object']))       $generate->givenObject = $_GET['object'];

/* Get the codename */
$generate->newCodeName();

$generate->responde("JSON", $response);

print($response);

?>
