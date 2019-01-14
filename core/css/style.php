<?php
require_once(dirname(__FILE__) . '/../abre_functions.php');
header("Content-type: text/css");
$siteColor = getSiteColor();
?>

a{
  color: <?php echo $siteColor;?>;
}

.switch label input[type=checkbox]:checked+.lever:after{
  background-color: <?php echo $siteColor;?> !important;
}

.site-background-color {
  background-color: <?php echo $siteColor; ?>;
}

.site-color {
  color: <?php echo $siteColor; ?>;
}
