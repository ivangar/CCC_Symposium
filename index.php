<?php 
//REQUIRE FIREPHP AND INITIALIZE OBJECT
require_once($_SERVER['DOCUMENT_ROOT'] . '/FirePHPCore/FirePHP.class.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/FirePHPCore/fb.php');
session_start();
ob_start(); // Turn on output buffering. From this point output is stored in an internal buffer 
require_once($_SERVER['DOCUMENT_ROOT'] . '/fr/inc/php/membersite_config.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/fr/programs/lib/php/program.php'); 

if(isset($_COOKIE['rememberme']) && !empty($_COOKIE['rememberme']) ){
	$fgmembersite->CheckCookie();
}

//If the user has not logged in, restrict access
if(!$fgmembersite->CheckLogin())
{
	$fgmembersite->RedirectToURL(SCRIPT_ROOT . "/AccessDeny/deny_access.html");
	exit;
}

//if user wants to logout
if(isset($_POST['logout_submitted']))
{
	$fgmembersite->LogOut();
}

//Create a Program instance
//$program = new Program();
$FrenchProgram = new Program();

//----------------DEFINE PERMANENT VARIABLES FOR THIS PROGRAM-----------------------//

$program_id_fr = 'BMS_107_FR';   //Program ID
$pretestId = '';  //program_section_id that belongs to the specific program pretest from program_sections table
$posttestId = '';  //program_section_id that belongs to the specific program post test from program_sections table
$forum_id = 'BMS_107_Forum_01_FR';  //program_section_id that belongs to the specific program forum from program_sections table
$evaluation_id_fr = 'BMS_107_Eval_01_FR';  //program evaluation
$certificate_id_fr = 'void';	//Certificate id that belongs to a program
$topicIds = array("BMS_107_topic_04", "BMS_107_topic_05", "BMS_107_topic_06");  //Array of forum topic ids for this program
$sections = array("evaluation", "forum");  //These are required sections to obtain certificate
$no_sections = sizeof($sections);

//----------------DEFINE PERMANENT VARIABLES FOR THIS PROGRAM-----------------------//

$program_status = false;		//This will check the program_status field in the doctor profile
$program_completed = false;	  //This boolean value is used to allow to display the hidden certificate link if all 3 sections are complete
$sections_status = array();  //This array will hold the state of each program section whenever the page is loaded and reloaded
$no_sections_completed = 0;

$FrenchProgram->Set_Program($program_id_fr, $pretestId, $posttestId, $forum_id, $evaluation_id_fr, $certificate_id_fr);

//Check that a record having this program ID for this doctor ID exists in doctor_profiles table. 
//If not Insert a new record with the certificate id defined, program_status = 0, date of completion = NULL and time required Null
//NOTE: The doctor_profiles record will be updated once each program section is submitted

if(!$FrenchProgram->CheckProfileExists()){

	$FrenchProgram->CreateProfile(); //Create empty French profile
}

$program_status = $FrenchProgram->CheckProgramStatus();  //First check in the doctor profile to see if the program status is completed.
$FrenchProgram->GetSectionsStatus();	//Allways get the sections status regardles of program completion.
$sections_status = $FrenchProgram->sections_status;		//Allways set the section status array (will hold sections status every time user visits program)

if($program_status){
	$program_completed = true;
	$no_sections_completed = $no_sections;
}

//if program has not been completed in the profile check sections one by one everytime the program page is reloaded
if(!$program_status){

	//if all $no_sections sections are completed update profile with completed
	if($FrenchProgram->CheckSectionsCompletedCustomized($sections)){
		$FrenchProgram->UpdateProfile();  //call UpdateProfile to insert program_status = 1, date of completion = NOW()
		$program_completed = true;
		$no_sections_completed = $no_sections;
	}

}

//This will display program progress status
if($no_sections_completed !== $no_sections){
    	foreach($sections_status as $section => $status)
    	{
    		if( (strcmp($section,'pretest') == 0) || (strcmp($section,'postTest') == 0) ) continue;

    		elseif($status)
        	$no_sections_completed++;
    	}
}

$FrenchProgram->GetProgramEvaluationStatus();						//Allways get the sections status regardles of program completion.

// close connection 
mysqli_close($FrenchProgram->con);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<title>dxLink - CCC Symposium</title>
<script src="<?= SCRIPT_ROOT ?>/SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js" type="text/javascript"></script>
<link href="<?= SCRIPT_ROOT ?>/css/styles.css" rel="stylesheet" type="text/css" />
<link href="<?= SCRIPT_ROOT ?>/SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet" type="text/css" />
<link type="text/css" rel="stylesheet" href="<?= SCRIPT_ROOT ?>/css/form-submit-button.css"/>
<link type="text/css" rel="stylesheet" href="<?= SCRIPT_ROOT ?>/css/tabStyles.css"/>
<link type="text/css" rel="stylesheet" href="<?= SCRIPT_ROOT ?>/css/Pretest/form.css"/>
<link type="text/css" rel="stylesheet" href="<?= SCRIPT_ROOT ?>/css/Pretest/nova.css" />
<link type="text/css" rel="stylesheet" href="../css/program_styles.css" />
<link type="text/css" rel="stylesheet" href="../css/new_program_styles.css" />
<link type="text/css" rel="stylesheet" href="css/main.css" />
<script type="text/javascript" src="<?= SCRIPT_ROOT ?>/js/parsley.js"></script>
<script type="text/javascript" src="<?= SCRIPT_ROOT ?>/browser/bowser.min.js"></script>
<script type="text/javascript" src="<?= SCRIPT_ROOT ?>/js/hashchange.js"></script>
<script type="text/javascript" src="<?= SCRIPT_ROOT ?>/js/tabScript.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<script type="text/javascript" src="js/program.js"></script>
<script src="<?= SCRIPT_ROOT ?>/js/jquery.blockUI.js"></script>
<script type="text/javascript">
	var section_submitted = <?php if($_SESSION['section_submitted'] || $_SESSION['posted']) {echo "true"; $_SESSION['section_submitted'] = false; $_SESSION['posted'] = false; } else echo "false";?>;
	var sections = <?php echo $no_sections; ?>;
	var no_sections_completed = <?php if( isset($no_sections_completed) ) echo $no_sections_completed; ?>;
</script>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-49063752-3', 'auto');
  ga('send', 'pageview');

</script>
<style>
.ui-tooltip {
background: #f6f6f6;
border: 2px solid #969696;

}
.ui-tooltip {
border-radius: 10px;
box-shadow: 0 0 7px black;
font-family:Arial,Helvetica,sans-serif;
font-size:16px;
font-style: italic;
padding: 10px 20px;
}

.slides_qty{
font-family:Arial,Helvetica,sans-serif;
font-size:12px;
line-height: 10px;
}

#v-nav > ul > li{
	font-size: 1em;
}
</style>
</head>
<body class="gradient">
<table class="content" border="0" cellspacing="0">
  <tr valign="bottom">
    <td width="250" height="90" align="left" bgcolor="#FFFFFF" style="padding:0 0 10px 20px;" ><a href="<?= SCRIPT_ROOT ?>/fr/index.php"><img src="/images/dxLinkAP.jpg" width="147" height="42" align="left" alt="dxlink"/></a>
	</td>
	<td bgcolor="#FFFFFF" style="padding:0 0 5px 0;" align="right">
		<div style="display: inline-block;"><?php $fgmembersite->printLogout(); ?></div>
	</td>
  </tr>
</table>
<table class="content" border="0" cellspacing="0">
  <tr valign="bottom">
	  <td style="padding:0 0 5px 0;"><img src="/images/bannerPA.jpg" align="left" alt="Accredited Programs" width="1000px;"/>
	  </td>
  </tr>
</table>
<!-- NAV BAR TABLE -->
<table class="content" border="0" cellspacing="0">
  <tr align="center" valign="top">
    <td height="30" style="padding:0;background:#3B0CAF;">
		<ul id="MenuBar1" class="MenuBarHorizontal">
	      <li><a href="<?= SCRIPT_ROOT ?>/fr/index.php" class="accredited">Accueil</a></li>
	      <li><a href="" class="MenuBarItemSubmenu accredited">Programmes</a>
	        <ul>
          		<li><a href="<?= SCRIPT_ROOT ?>/fr/accredited_programs.php" class="accredited">Programmes agréés</a></li>
          		<li><a href="<?= SCRIPT_ROOT ?>/fr/virtual_clinic.php" class="accredited">Clinique virtuelle</a></li>
          		<li> <a href="<?= SCRIPT_ROOT ?>/fr/congress_reports.php" class="accredited">Rapports de congrès</a></li>
          		<li><a href="<?= SCRIPT_ROOT ?>/fr/clinical_update.php" class="accredited">Mise à jour clinique</a></li>
	        </ul>
	      </li>
	      <li><a href="<?= SCRIPT_ROOT ?>/fr/account.php" class="accredited">Mon profil</a></li>
	      <li><a href="<?= SCRIPT_ROOT ?>/fr/contactez-nous.php" class="accredited">Contactez-nous</a></li>
	  	  <li><a href="http://www.cjdiagnosis.com/?ac=diagnosis" target="_blank" class="accredited">CJ Diagnosis</a></li>
	  	  <li><a href="http://www.cjcme.com/?ac=cme" target="_blank" class="accredited">CJ CME</a></li>
	  	  <li class="last"><a href="http://www.stacommunications.com/" target="_blank" class="accredited">STA HealthCare Communications</a></li>
	    </ul>
	</td>
  </tr>
  </table>
  <!-- NAV BAR ENDS HERE -->
   <!-- INNER 3-COLUMN STYLE TABLE  -->
  <table class="three-columns" border="0" cellspacing="0">
  <tr valign="top">
	  <!-- LEFT VERTICAL TABBED SECTION -->

		  <td style="padding: 20px 0 0 0;">
	          <section id="wrapper" class="wrapper">
	              <div id="v-nav">
					  <ul>
	                      <li tab="tab1" class="first current" >Introduction</li>
	                      <li tab="tab2" title="player2" >Données probantes en situation réelle sur l’anticoagulothérapie orale<br> P<sup>r</sup> Gregory H.Y. Lip (30 minutes)</li>
	                      <li tab="tab3" title="player3" >Prévention de l’AVC chez les patients atteints de FA et d’IC ou d’un SCA concomitants, ou ayant subi une ICP<br> D<sup>r</sup> L. Brent Mitchell (19 minutes)</li>
	                      <li tab="tab4" title="player4" >Cas complexe pour la prévention <br>des AVC dans la FA<br> D<sup>r</sup> Paul Dorian (15 minutes)</li>
	                  	  <li tab="tab5" >Forum de discussion</li>
	                  	  <li tab="tab6" >Évaluation du programme</li>
	                      <li tab="tab7">Agrément</li>
	                      <!--  May need to put rep zone tab back in
	                      <li tab="tab8" class="last" id="custom"><span class="subTitle">Zone Rep</span></li>
	                  		-->
					  </ul>
	                  <div class="tab-content" >
	                  	<?php  require_once('introduction.html'); ?>  
	                  </div>
	                  <div class="tab-content" >
	                  		<?php  require_once('video2.html'); ?> 
	                  </div>
	                  <div class="tab-content" >
	                        <?php  require_once('video3.html'); ?> 
	                  </div>
	                  <div class="tab-content" >
	                        <?php  require_once('video4.html'); ?>    
	                  </div>   
	                  <div class="tab-content larger">
	                      <?php require_once('forum.php'); ?>           
	                  </div> 	                                
	                  <div class="tab-content larger" >
	                  	<?php require_once('evaluation.html'); ?> 
	                  </div>
	                  <div class="tab-content" >
	                        <?php  require_once('accreditation.html'); ?>    
	                  </div> 
	               	  <div class="tab-content" >
	                        <?php  require('introduction.html'); ?>
	                  </div> 	                  
	              </div>
	          </section>
  	</td>
  </tr>
</table>
  <!-- END INNER 3-COLUMN TABLE  -->
  <table class="content" border="0" cellspacing="0">
  <tr>
    <td  height="35" colspan="3" valign="top" bgcolor="#FFFFFF" align="center">
   </td>
  </tr>
 </table>
  <!-- FOOTER TABLE -->
  <table class="content" border="0" cellspacing="0">
  <tr>
    <td style="padding:20px 50px 0 20px;display:inline-block;" height="55" valign="bottom" bgcolor="#FFFFFF" align="center">
		<? $fgmembersite->printCopyright(); ?> 
    </td>
    <td style="padding:0 0 10px 400px;display:inline-block;" valign="bottom" bgcolor="#FFFFFF" align="center">  
    	<? $fgmembersite->printTermsConditions(); ?>
   </td>
  </tr>
</table>
<script type="text/javascript">
var MenuBar1 = new Spry.Widget.MenuBar("MenuBar1");
</script>
</body>
</html>
<?php ob_flush(); //This function will send the contents of the output buffer ?>