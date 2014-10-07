<?php

  require 'dbinfo.inc.php';
  require 'ConfigFunctions.php';
  require 'Constants.php';
  $constants = new Constants;
  $configFunctions = new ConfigFunctions($dbInfo, $constants);
  
  $modules = $configFunctions->allModules();

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

  <div style="min-width:800px;position:relative;top:75px;margin-left:10px;margin-bottom:175px;width:100%;">

    <br>

    <?php foreach($modules as $id => $name): ?>
    	<a style="color:black" href="show_module.php?id=<?= $id ?>" name="filename"><?= $name ?></a><br>
    <?php endforeach ?>

  </div>

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

</body>

</html>



