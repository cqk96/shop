<?php
$recordWorkingTime = "243";
$startTime = date("Y-n-j", strtotime($recordWorkingTime . " 00:00:00") );
var_dump($startTime);
?>