<?php

require_once 'Matrix.class.php';
$matrix = new Matrix();

?>

<html>
<head>
<title>game index</title>
<META HTTP-EQUIV="Content-type" CONTENT="text/html; charset=EUC-JP">
</head>
<body>
<?= $matrix->display() ?>
</body>
</html>
