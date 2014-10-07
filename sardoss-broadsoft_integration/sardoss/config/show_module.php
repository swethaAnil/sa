<?php
  require 'dbinfo.inc.php';
  require 'ConfigFunctions.php';
  require 'Constants.php';
  $constants = new Constants;
  $configFunctions = new ConfigFunctions($dbInfo, $constants);

  $moduleId = $_GET['id'];
  $module = $configFunctions->selectModule($moduleId);
  
?>

<!DOCTYPE HTML> 

<html>

<head>
<title> Service Activations - ReDCON</title>

<!--[if IE]>
<link rel="stylesheet" type="text/css" href="../style/sardoss_style_ie.css" />
<![endif]-->

<!--[if !IE]><!-->
<link rel="stylesheet" type="text/css" href="../style/sardoss_style.css" />
<!--<![endif]-->

</head>


<body>

<div class="full_header">

<div class="titlecolor">

<div class="title">
<img style="margin:10px;" src="../images/SA_title_words_small.png">
</div>

<div class="pagetitle"><a href="/sardoss">Home</a>
<span class="location">
 / <a href="../config/config_gen_order_form.php">ReDCON</a>
 / <u>Config Modules</u></span>
</span>
</div>

</div>

<hr class="topline" />
<?php include 'optionbar.php'; ?>

</div>
<div style="min-width:800px;position:relative;top:75px;margin-left:50px;width:100%;">
<br>

<h3>VIEWING CONFIG MODULE: <span style="color:blue"><?= $module['name'] ?></span></h3>

<a href="config_modules.php" class="bodylink" style="padding:8px;">Back to Config Modules</a>
<a href="admin/edit_module.php?id=<?= $module['id'] ?>" class="bodylink" style="margin-left:50px;padding:8px">Edit Module</a>

<br><br><br>

<!-- <textarea name="module" cols="110" rows="100" readonly style="margin-bottom:40px"> -->
<div style="border:1px solid black;padding:15px;width:80%;margin-bottom:175px">
  <pre>
  <?= $module['config'] ?>
  </pre>
</div>

<!-- </textarea> -->

</div>
</body>
 </html>
 <script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-39762922-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>

