<!--
<script type="text/javascript" src="../style/jquery.js"></script>
<script type="text/javascript"> 
$(document).ready(function(){
  $("#menuItem").mouseenter(function(){
    $("#menuItem").animate({height:300},"slow");
  });
   $("#menuItem").mouseleave(function(){
    $("#menuItem").animate({height:10},"slow");
  });
});
</script> 
-->

<div class="optionbar">
<a style="padding-left:10px;" href="../config/config_archive.php">Config Archive</a>
<a style="padding-left:10px;" href="../config/config_modules.php">Config Modules</a>
<a style="padding-left:10px;" href="../base/base_configs.php">Base Configs</a>
<a style="padding-left:10px;" href="../vpn/10K_VPN_build.htm">VPN for 10K</a>
<a style="padding-left:10px;" href="/schedule">Online Schedule</a>
<div class="current_user">User: <?php echo $_SERVER['PHP_AUTH_USER']; ?></div>
</div>

