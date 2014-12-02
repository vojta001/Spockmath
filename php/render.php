<!DOCTYPE html>
<html lang="cs">
<head>
  <meta charset="utf-8" />
  <title><?php echo (isset($htmlTitle)?$htmlTitle:'$htmlTitle not set');?></title>
  <meta name="description" content="<?php echo (isset($htmlDesc)?$htmlDesc:'$htmlDesc not set');?>" />
  <meta name="keywords" content="<?php echo (is_array($htmlKwd)?implode($htmlKwd, ', '):'$htmlKwd not array');?>" /> 
  <meta name="robots" content="index,follow" />
  <meta name="author" content="<?php echo (isset($htmlAuthors)?(is_array($htmlAuthors)?implode($htmlAuthors, ', '):$htmlAuthors):'$htmlAuthor not set');?>" />
  <link rel="icon" type="image/png" href="<?php echo (isset($favIcon)?$favIcon:'favicon.png');?>" />
  <link type="text/css" rel="stylesheet" href="css/style.css" />
  <link type="text/css" rel="stylesheet" href="css/bubble.css" />
<?php if (is_array($jsScripts)) foreach($jsScripts as $js) {?>  <script src="<?php echo $js; ?>"></script>
<?php }
if (is_array($cssStyles)) foreach($cssStyles as $css) {?>  <link type="text/css" rel="stylesheet" href="<?php echo $css; ?>" />
<?php } ?></head>
<body>
<div id="container">
<div id="header">
  <h1>Spockova matika</h1>
  <?php echo renderMenu(); ?>
</div>
<div id="content">
  <?php
$renderFile = PAGES_PATH.$page.'/render.php';
if (file_exists($renderFile))
  include $renderFile;
?>
</div>
<div id="footer">
  <img id="kane" src="img/design/kane-foot.png" alt="" />připravil <a href="mailto:kane@terciani.cz">Vojtěch Káně</a>
  <img id="rgb" src="img/design/rgb-foot.png" alt="" />a <a href="mailto:rgb@trdlo.net">RgB</a>
</div>
</div>
<?php echo renderFlashMsg(); ?>
</body>
</html>