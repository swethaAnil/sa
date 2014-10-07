<?php
  
  require '../dbinfo.inc.php';
  require '../ConfigFunctions.php';
  require '../Constants.php';
  $constants = new Constants;
  $configFunctions = new ConfigFunctions($dbInfo, $constants);

  $alert = '';

  $moduleId = $_GET['id'];
  
  if(isset($_POST['module'])){

    $result = $configFunctions->updateModule($moduleId, $_POST['module']);

    if($result){
      $alert = "<h3 style='color:green'>Module updated!</h3>";
    }else{
      $alert = "<h3 style='color:red'>Module update failed!</h3>";
    }
    
  }

  $module = $configFunctions->selectModule($moduleId);

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd"> 

<html>

<head>
<title> Service Activations - ReDCON</title>

<!--[if IE]>
<link rel="stylesheet" type="text/css" href="../style/sardoss_style_ie.css" />
<![endif]-->

<!--[if !IE]><!-->
<link rel="stylesheet" type="text/css" href="../../style/sardoss_style.css" />
<!--<![endif]-->

</head>


<body>

<div class="full_header">

<div class="titlecolor">

<div class="title">
<img style="margin:10px;" src="../../images/SA_title_words_small.png">
</div>

<div class="pagetitle"><a href="/sardoss">Home</a>
<span class="location">
 / <a href="../../config/config_gen_order_form.php">ReDCON</a>
 / <u>Config Modules</u></span>
</span>
</div>

</div>

<hr class="topline" />
<div class="optionbar">
<a style="padding-left:10px;" href="../../config/config_archive.php">Config Archive</a>
<a style="padding-left:10px;" href="../../config/config_modules.php">Config Modules</a>
<a style="padding-left:10px;" href="../../base/base_configs.php">Base Configs</a>
<a style="padding-left:10px;" href="../../vpn/10K_VPN_build.htm">VPN for 10K</a>
<a style="padding-left:10px;" href="/schedule">Online Schedule</a>
<div class="current_user">User: <?php echo $_SERVER['PHP_AUTH_USER']; ?></div>
</div>

</div>
<div style="min-width:800px;position:relative;top:75px;margin-left:50px;width:100%;">
<br>

<h3>EDITING CONFIG MODULE: <span style="color:blue"><?= $module['name'] ?></span> <a href="../modules/defaults/<?= $module['name'] ?>.dat" target="_blank" class="bodylink" style="padding:5px;font-size:12px">View original version</a></h3>
<!-- <h4><a href="../modules/defaults/<?= $module['name'] ?>.dat" target="_blank" class="bodylink" style="padding:5px">View original version</a></h4> -->
<?= $alert ?>
<form method="POST">
 
  <button type="submit" class="bodylink" style="padding:8px">Save Changes</button>
  <br><br>

  <textarea name="module" cols="110" rows="100" style="margin-bottom:40px">
    <?= $module['config'] ?>
  </textarea>  

</form>




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

