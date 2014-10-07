<?php
error_reporting(0);
// error_reporting(E_ALL);
// ini_set('display_errors', '1');

require 'dbinfo.inc.php';
require 'ConfigFunctions.php';
require 'Constants.php';

$constants = new Constants();
$configFunctions = new ConfigFunctions($dbInfo, $constants);

$processResult = $configFunctions->processInput($_POST);
$mapfile = $processResult['mapfile'];
$errors = $processResult['errors'];

//BUILD VALUE MAP ARRAY
$val_map = $configFunctions->build_value_map($mapfile);

?>

<html>

<head>

	<title>Configuration for Account <?= $val_map['[VAR_ACCT_NUM]'] ?></title>

	<!--[if IE 9]>
		<link rel="stylesheet" type="text/css" href="../style/sardoss_style.css" />
	<![endif]-->

	<!--[if IE]>
	<link rel="stylesheet" type="text/css" href="../style/sardoss_style_ie.css" />
	<![endif]-->

	<!--[if !IE]><!-->
	<link rel="stylesheet" type="text/css" href="../style/sardoss_style.css" />
	<!--<![endif]-->

	<script language="JavaScript">

		function displayConfig(id, step){
			var displayDiv = document.getElementById('displayDiv');
			var elem = document.getElementById(id);

			var displayFrame = document.getElementById("displayFrame");
			var doc = displayFrame.contentDocument;
			
			if (doc == undefined || doc == null)
				doc = displayFrame.contentWindow.document;
				
			doc.open();
			doc.write(elem.innerHTML);
			doc.close();

			var elemCaption = document.getElementById("title");
			elemCaption.innerHTML= "<u><b>Current selection</b></u><br>" + step;
		} 

		function closeWin()
		{
			window.close();
		}

	</script>

	<style>

		a{
			font-size:120%;
			font-weight:bold;
		}

		a:link{
			color:darkblue;
		}
		a:visited{
			color:darkblue;
		}

		a:hover{
			color:red;
			text-decoration:none;
		}

		a:active{
			color:darkred;
			text-decoration:none;
		}

		#sidebar {
			position: absolute;
			z-index: 30;
			width: 120px;
			padding-left: 10px;
			padding-top: 100px;
			text-align:center;
			
		}


		#configframe {
			position:absolute;
			z-index: 30;
			margin-top: 0px;
			
		}

		#title {
			position: absolute;
			z-index: 30;
			width: 120px;
			padding-left: 10px;
			padding-top: 20px;
			text-align:center;
			font-size:90%;
				
		}

		#details {
			font-size:80%;
			z-index: 30;
			position:relative;
			padding-top: 50px;
		}

	</style>

</head>


<body style="font-style:arial;font-size:90%;min-width:780px;overflow-y:hidden;">

<div class="titlecolor">

	<div class="title">
	</div>

	<div class="pagetitle">
		<span class="location">
			Configuration for Account <?= $val_map['[VAR_ACCT_NUM]'] . " - " . $val_map['[VAR_ACCT_NAME]'] ?>
		</span>
	</div>
</div>

<hr class="topline" />
<div class="optionbar">
<a style="padding-left:10px;" href="#" onclick="closeWin()" >...</a>
<div class="current_user">User: <?php echo $_SERVER['PHP_AUTH_USER']; ?></div>
</div>

<?php include '_display_configs.php'; ?>

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
 
 




 