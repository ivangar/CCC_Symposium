<?php
require_once("../lib/master.php");
/*  This file is for the "Event REP Guide " page */
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Home - CCC Sur-Demande</title>

    <!-- Bootstrap Core CSS -->
    <link href="bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/sb-admin-2.css" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">Accueil</a>
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">
                
                <!-- /.dropdown -->
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="#" id="english"><i class="fa fa-comments fa-fw"></i> Anglais</a>  
                        </li>
                        <li class="divider"></li>
                        <li><a href="#" id="logout"><i class="fa fa-power-off fa-fw"></i> Déconnexion</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
            <!-- /.navbar-top-links -->

            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">

                        <li>
                            <a href="forms/Rep-Guide-CCC-On-Demand.pdf" target="_blank"><i class="fa fa-file-pdf-o fa-fw"></i> Guide du représentant</a> 
                        </li>
                        <li>
                            <a href="forms/Program-Overview-CCC-On-Demand.pdf" target="_blank"><i class="fa fa-file-pdf-o fa-fw"></i> Aperçu du programme à l'intention de l'animateur</a> 
                        </li>
                        <li>
                            <a href="new_event.php"><i class="fa fa-plus-circle fa-fw"></i> Nouvelle activité</a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-list-alt fa-fw"></i> Liste des activités<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="my_events.php"><i class="fa fa-list-alt fa-fw"></i> Mes activités</a>
                                </li>
                                <li>
                                    <a href="all_events.php"><i class="fa fa-list-alt fa-fw"></i> Toutes les activités</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        <li>
                            <a href="documents.html"><i class="fa fa-folder-open fa-fw"></i> Formulaires et documents requis</a>
                        </li>    
                        <li>
                            <a href="videos.html"><i class="fa fa-video-camera fa-fw"></i> Présentations vidéo</a>
                        </li> 
                         <li>
                            <a href="contact_us.html"><i class="fa fa-envelope fa-fw"></i> Contactez-nous</a>
                        </li>                                               
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>

        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
               <div class="row" style="margin-top:20px;">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <img class="center-block img-responsive" src="../../images/ondemand_F_apercu.png" width="1000" height="271" align="center" alt="CCC Banner"/>
                    </div>
                </div>                
                <div class="row">
                    <div class="col-sm-12">
                        <p>Le programme <strong>« Innovation, optimisation et perfectionnement du traitement antithrombotique dans la fibrillation auriculaire » </strong> est une activité d’apprentissage collective agréée qui s'appuie sur des exposés préalablement enregistrés. Axé sur des données en situation réelle sur l'anticoagulothérapie orale, les lignes directrices relatives aux stratégies de prévention de l'AVC chez les patients atteints de fibrillation auriculaire (FA) qui présentent une IC ou un SCA ou qui ont subi une ICP, ainsi que sur des approches visant à réduire la récurrence des AVC chez les patients atteints de FA qui présentent une insuffisance rénale, le programme vous permettra de prendre connaissance des recommandations cliniques les plus récentes, de partager vos réflexions avec vos collègues et de participer à des exercices de remue-méninges sur des profils de patients particuliers et les défis du traitement de la FA.</p>
                    </div>
                    <div class="col-sm-12">
                        <div class="agenda">
                            <span style="font-size:16px;"><strong>Format de l’horaire</strong></span>
                            <ul>
                                <li><b>Introduction :</b> 5 minutes</li>
                                <li><b>Vidéo(s) :</b> 30 minutes</li>
                                <li><b>Discussion :</b> 20 minutes</li>
                                <li><b>Conclusion :</b> 5 minutes</li>
                            </ul>
                        </div>
                    </div>                 
                </div> 
                <div class="row">
                    <div class="col-sm-12">
                        <p style="padding-bottom:15px;">Un résumé de chacune des vidéos est fourni ci-dessous afin de permettre aux animateurs de choisir celle qu'ils aimeraient présenter.</p>
                    </div>  
                </div>
                <div class="panel green_panel"> 
                    <div class="panel-body">
                        <div class="row"> 
                            <div class="col-lg-2 col-md-4">
                                <img class="img-rounded" src="/fr/programs/CCC_Symposium/images/vid1.jpg" width="200" height="148" alt="Gregory Lip"/>
                            </div> 
                            <div class="col-lg-10 col-md-8"> 
                                <div class="row custom">
                                    <div class="col-sm-12">
                                        <span style="font-size:16px;" class="custom_blue"><strong>Données probantes en situation réelle sur l'anticoagulothérapie orale</strong></span><br>
                                    </div>
                                    <div class="col-sm-12">
                                        <p>Se servant des données en situation réelle, le professeur Lip discute de l'efficacité et de l’innocuité des anticoagulants oraux. Il passe en revue les plus récentes stratégies de stratification des risques ainsi que le processus décisionnel recommandé pour le traitement de la FANV nouvellement diagnostiquée. Il conclut par la comparaison en situation réelle du risque de saignement majeur chez les patients atteints de FANV qui débutent une anticoagulothérapie orale.</p>
                                    </div>         
                                </div> 
                            </div> 
                        </div>                        
                    </div>
                    <span class="divider"></span>
                    <div class="panel-body">
                        <div class="row"> 
                            <div class="col-lg-2 col-md-4">
                                <img class="img-rounded" src="/fr/programs/CCC_Symposium/images/vid2.jpg" width="200" height="148" alt="Gregory Lip"/>
                            </div> 
                            <div class="col-lg-10 col-md-8"> 
                                <div class="row custom">
                                    <div class="col-sm-12">
                                        <span style="font-size:16px;" class="custom_blue"><strong>Prévention de l'AVC chez les patients atteints de FA et d’IC ou d’un SCA concomitants, ou ayant subi une ICP</strong></span><br>
                                    </div>
                                    <div class="col-sm-12">
                                       <p> Le D<sup>r</sup> Mitchell parle des stratégies de prévention de l'AVC chez les patients atteints de FA non valvulaire (FANV) qui présentent une IC ou un SCA, ou qui ont subi une ICP, et présente les recommandations exactes de la mise à jour 2016 des <i>Lignes directrices pour la prise en charge de la fibrillation auriculaire de la Société canadienne de cardiologie</i> (SCC). Il présente les avantages et les désavantages de divers traitements antithrombotiques et passe en revue les algorithmes utilisés par la SCC pour déterminer le meilleur traitement chez les sous-groupes de patients atteints de FA décrits ci-dessus.</p>
                                    </div>                 
                                </div> 
                            </div> 
                        </div>                        
                    </div>
                    <span class="divider"></span>
                    <div class="panel-body">
                        <div class="row"> 
                            <div class="col-lg-2 col-md-4">
                                <img class="img-rounded " src="/fr/programs/CCC_Symposium/images/vid3.jpg" width="200" height="148"  alt="Gregory Lip"/>
                            </div> 
                            <div class="col-lg-10 col-md-8"> 
                                <div class="row custom">
                                    <div class="col-sm-12">
                                        <span style="font-size:16px;" class="custom_blue"><strong>Cas complexes pour la prévention des AVC dans la FA</strong></span><br>
                                    </div>
                                    <div class="col-sm-12">
                                        <p> Le D<sup>r</sup> Dorian explore les meilleures approches visant à réduire le risque de récurrence des AVC et à établir la dose de l'anticoagulothérapie chez les patients atteints de FA qui présentent une insuffisance rénale. En prenant l'exemple d'un cas complexe où le patient atteint de FA permanente et de néphropathie est hospitalisé en raison d'une insuffisance cardiaque congestive (ICC), il décrit le processus de réévaluation de l'anticoagulothérapie orale directe au moment de l'hospitalisation, passe en revue les méthodes les plus exactes pour mesurer l'effet de celle-ci au cas où une intervention chirurgicale serait nécessaire et parle des avantages de ce traitement dans la prévention de l'AVC.</p>
                                    </div>                 
                                </div> 
                            </div> 
                        </div>                        
                    </div>                    
                </div>
                <div class="row">
                    <div class="col-sm-12">&nbsp;</div>  
                </div>
            <!--
                <div class="panel green_panel"> 
                    <div class="panel-body">
                        <div class="row"> 
                            <div class="col-sm-12">
                                <img class="img-responsive" src="/programs/CCC_Symposium/images/presentation_3.jpg" width="300" height="44" align="left"/>
                            </div>
                        </div>     
                        <div class="row" style="padding-bottom:10px;"> 
                            <div class="col-sm-12">
                                <span style="font-size:16px;" class="custom_blue">Dr. Paul Dorian</span><br>
                                <span style="font-size:16px;" class="custom_blue"><strong>Complex Case for Stroke Prevention in AF</strong></span><br>
                            </div>
                        </div> 
                        <div class="row"> 
                            <div class="col-sm-12">
                                <p>Dr. Dorian explores the best approaches to mitigating the recurrence of stroke and measuring anticoagulation when treating AF patients with renal impairment. Using a complex case of a patient on dabigatran with permanent AF and kidney disease who is admitted to the hospital with congestive heart failure, he takes viewers through the process of re-evaluating the patient’s DOAC treatment at the point of hospitalization, reviews the most accurate ways of measuring DOAC effect in the event of surgery, and discusses the benefits of NOACs in stroke prevention.</p>
                            </div>
                        </div>                                                   
                    </div>                
                </div>  
            -->
                <!--              
                <div class="row">
                    <div class="col-sm-12">
                        <p style="padding-bottom:15px;"><a href="forms/Rep-Guide-CCC-On-Demand.pdf" class="btn btn-outline btn-success cases" target="_blank">Download Rep Guide</a></p>
                    </div>  
                </div>
            -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="bower_components/jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="bower_components/metisMenu/dist/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="js/sb-admin-2.js"></script>

    <script src="js/main.js"></script>

</body>

</html>
