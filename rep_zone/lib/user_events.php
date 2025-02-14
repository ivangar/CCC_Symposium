<?php 
require_once($_SERVER['DOCUMENT_ROOT'] . "/programs/CCC_Symposium/rep_zone/config/env_constants.php");
require_once($_SERVER['DOCUMENT_ROOT'] . '/FirePHPCore/FirePHP.class.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/FirePHPCore/fb.php');
ob_start(); 

/*
** This file is used to load all events linked to the currently logged in user (user ID).  It is called from my_events.php ("My Events")
*/

function createDatabaseConnection()
{
    try {
        $con = new PDO('mysql:host=' . HOST . ';dbname=' . DATABASE . ';charset='. ENCODING, USER, PWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));

    } catch (PDOException $e) {
        $con = "PDO database connection problem: " . $e->getMessage();
    } catch (Exception $e) {
        $con = "General problem: " . $e->getMessage();
    }

    $con->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    return $con;
}


class userEvents{

    /**
     * @var object Database connection
     */
    private $db_connection = null;

    /**
     * @var user currently connected
     */
    public $user = 0;

    /**
     * @var user ID
     */
    public $admin_logged = false;   

    /**
     * @var all table rows that need to be displayed
     */
    public $events;

    /**
     * @var containing the number of all the events per user
     */
 	public $events_empty = false;


    /**
     * @var one compact object containing the events
     */
	public $event_thread;
    

    /**
     * Does necessary checks for PHP version and PHP password compatibility library and runs the application
     */
    public function __construct()
    {   
        $this->runApplication();
    }

    /**
     * This is basically the controller that handles the entire flow of the application.
     */
    public function runApplication()
    {
        // start the session, always needed!
        $this->doStartSession();
        $this->db_connection = createDatabaseConnection(); // get database connection credentials
        $this->getUserId();
        $this->getRowCount();
    }

    /**
     * Simply starts the session.
     * It's cleaner to put this into a method than writing it directly into runApplication()
     */
    private function doStartSession()
    {
        session_start();
    }

    /**
     * Simply returns the current status of the user's login
     * @return bool User's login status
     */
    public function getUserId()
    {
        if (isset($_COOKIE['remember_repzone']) && !empty($_COOKIE['remember_repzone'])) { $this->user = $_COOKIE['remember_repzone']; $this->user_is_logged_in = true; }
        elseif (isset($_SESSION['rep_zone_user']) && !empty($_SESSION['rep_zone_user'])) { $this->user = $_SESSION['rep_zone_user']; $this->user_is_logged_in = true; }
    }

    //Get number of total rows
	public function getRowCount() {

        $page = end((explode('/', rtrim($_SERVER[REQUEST_URI], '/'))));
        $page = substr($page, 0, 14);

        if (strcmp($page, "all_events.php") === 0) {
            $sql = "SELECT COUNT(*) AS count FROM events";
            $query = $this->db_connection->prepare($sql);
        }

        else{
            $sql = "SELECT COUNT(*) AS count FROM events WHERE rep_id = :user";
            $query = $this->db_connection->prepare($sql);
            $query->bindParam(':user', $this->user);
        }
		
        $query->execute();

        while($result_row = $query->fetch(PDO::FETCH_ASSOC) ){
	        	$no_total_events = $result_row['count'];  
	        	if($no_total_events == 0){
	        		$this->events_empty = true;
	        	} 
	        	return true;
        }

        return false;

	}

	public function getRows() {

		$this->Get_Events();
		$this->Print_Events();
		$this->Close_DB_connection();

	}

	public function Get_Events(){

        $page = end((explode('/', rtrim($_SERVER[REQUEST_URI], '/'))));
        $page = substr($page, 0, 14);

        if (strcmp($page, "all_events.php") === 0) {
            $sql = "SELECT event_id, DATE_FORMAT(event_date,'%W, %M %e, %Y') AS event_date, DATE_FORMAT(event_time,'%l:%i %p') AS event_time, location, attendees, status, rep_id FROM events ORDER BY DATE(event_date) ASC, event_time ASC";
            $query = $this->db_connection->prepare($sql);
        }

        else{
            $sql = "SELECT event_id, DATE_FORMAT(event_date,'%W, %M %e, %Y') AS event_date, DATE_FORMAT(event_time,'%l:%i %p') AS event_time, location, attendees, status FROM events WHERE rep_id = :user ORDER BY DATE(event_date) ASC, event_time ASC";
            $query = $this->db_connection->prepare($sql);
            $query->bindParam(':user', $this->user);
        }

        $query->execute();

        while($result_row = $query->fetch(PDO::FETCH_ASSOC) ){
            $event_date = $this->Translate_date($result_row['event_date']);
    		$event_id = $result_row['event_id'];
        	$event_time = $result_row['event_time'];  
			$location = $result_row['location'];  
			$attendees = $result_row['attendees'];
            $status = $result_row['status'];
            (!empty($result_row['rep_id'])) ? $rep_id = $result_row['rep_id'] : $rep_id = null;
			$this->Generate_Events($event_id, $event_date, $event_time, $location, $attendees, $status, $rep_id);
        }

		return true;

	}

 	public function setTableContent(){
        $this->checkAdminlogin();
 		$this->getRows();
        
        if($this->admin_logged){    
    		$content = "<div id='content'>
                              <table class='table table-striped table-hover' id='sortable'>
                                <thead>
                                  <tr>
                                    <th class='col-sm-1'>Event #ID</th>
                                    <th class='col-sm-1'>Rep #ID</th>
                                    <th class='col-sm-3'>Date et heure de la séance</th>
                                    <th class='col-sm-3 col-md-4'>Lieu</th>
                                    <th class='col-sm-1'>Nombre de participants</th>
                                    <th class='col-sm-2 col-md-1'>Statut de l’activité</th>
                                    <th class='col-sm-1'></th>
                                  </tr>
                                </thead>
                                <tbody id='events'>
                                     {$this->event_thread}
                                </tbody>
                              </table> 
                            </div>  ";
        }

        else{
            $content = "<div id='content'>
                              <table class='table table-striped table-hover' id='sortable'>
                                <thead>
                                  <tr>
                                    <th class='col-sm-3'>Date et heure de la séance</th>
                                    <th class='col-sm-5 col-md-6'>Lieu</th>
                                    <th class='col-sm-1'>Nombre de participants</th>
                                    <th class='col-sm-2 col-md-1'>Statut de l’activité</th>
                                    <th class='col-sm-1'></th>
                                  </tr>
                                </thead>
                                <tbody id='events'>
                                     {$this->event_thread}
                                </tbody>
                              </table> 
                            </div>  ";            
        }

        echo $content;
 	}

	public function Generate_Events($event_id, $event_date, $event_time, $location, $attendees, $status, $rep_id){

        if(!empty($status)){
          $button_style = $this->Generate_Status($status);  
          $french_status = $this->Translate_Status($status);
          $status_button = "<td><button type='button' class='btn $button_style disabled'>$french_status</button> </td>";
        }
        else{ $status_button = "<td>&nbsp;</td>"; }

        if($this->admin_logged){
            $this->events .= "<tr >\n
                                <td>$event_id</td>\n
                                <td>$rep_id</td>\n
                                <td>$event_date à $event_time</td>\n
                                <td>$location</td>\n
                                <td>$attendees</td>\n
                                $status_button\n
                                <td><a href='myevent.php' class='btn btn-default' id='$event_id'>Voir</a></td>\n
                            </tr>\n
                                 ";        
        }

        else{
            $this->events .= "<tr >\n
                    <td>$event_date à $event_time</td>\n
                    <td>$location</td>\n
                    <td>$attendees</td>\n
                    $status_button\n
                    <td><a href='myevent.php' class='btn btn-default' id='$event_id'>Voir</a></td>\n
                </tr>\n
                     ";
        }

	}

    public function Generate_Status($status){

        $status_str = '';

        switch ($status) {
            case 'pending':
                $status_str = 'btn-warning';
                break;
            case 'approved':
                $status_str = 'btn-success';
                break;
            case 'cancelled':
                $status_str = 'btn-danger';
                break;
            case 'closed':
                $status_str = 'btn-primary';
                break;
        }

        return $status_str;
    }

    public function Translate_Status($status){

        $french_str = '';

        switch ($status) {
            case 'pending':
                $french_str = 'En Attente';
                break;
            case 'approved':
                $french_str = 'Approuvé';
                break;
            case 'cancelled':
                $french_str = 'Annulé';
                break;
            case 'closed':
                $french_str = 'Fermé';
                break;
        }

        return $french_str;
    }

    public function Translate_date($date){

        $timestamp = strtotime($date);
        $day = $this->TranslateDay(date("l", $timestamp));
        $day_no = date("j", $timestamp);
        $year = date("Y", $timestamp);
        $month = $this->TranslateMonth(date("F", $timestamp));
        $translated_date = $day . ' le ' . $day_no . ' ' . $month . ' ' . $year;

        return $translated_date;
    }

    public function TranslateDay($day){

        $french_day = '';

        switch ($day) {
            case 'Monday':
                $french_day = 'lundi';
                break;
            case 'Tuesday':
                $french_day = 'mardi';
                break;
            case 'Wednesday':
                $french_day = 'mercredi';
                break;
            case 'Thursday':
                $french_day = 'jeudi';
                break;      
            case 'Friday':
                $french_day = 'vendredi';
                break;  
            case 'Saturday':
                $french_day = 'samedi';
                break;  
            case 'Sunday':
                $french_day = 'dimanche';
                break;                                                              
        }

        return $french_day;
    }

    public function TranslateMonth($month){

        $french_month = '';

        switch ($month) {
            case 'January':
                $french_month = 'janvier';
                break;
            case 'February':
                $french_month = 'février';
                break;
            case 'March':
                $french_month = 'mars';
                break;
            case 'April':
                $french_month = 'avril';
                break;      
            case 'May':
                $french_month = 'mai';
                break;  
            case 'June':
                $french_month = 'juin';
                break;  
            case 'July':
                $french_month = 'juillet';
                break;  
            case 'August':
                $french_month = 'août';
                break;  
            case 'September':
                $french_month = 'septembre';
                break;  
            case 'October':
                $french_month = 'octobre';
                break;  
            case 'November':
                $french_month = 'novembre';
                break;  
            case 'December':
                $french_month = 'décembre';
                break;                                                                                                                                                  
        }

        return $french_month;
    }

	public function printAlert(){
        $page = end((explode('/', rtrim($_SERVER[REQUEST_URI], '/'))));
        $page = substr($page, 0, 14);

        if (strcmp($page, "all_events.php") === 0) {
            $alert = "<div class='alert alert-warning fade in' role='alert' ><h4>Aucun activité trouvé. <a href='new_event.php' style='color:#428bca;'> &nbsp; Cliquez ici pour ajouter un activité.</a></h4></div>";
        }

        else{
            $alert = "<div class='alert alert-warning fade in' role='alert' ><h4>Aucun activité trouvé. <a href='new_event.php' style='color:#428bca;'> &nbsp; Cliquez ici pour ajouter un activité.</a> </h4></div>";
        }

		echo $alert;	
	}

	public function Clear_Events(){
		$this->events = '';
	}

	public function Print_Events(){
		if(empty($this->events)) { return false; }
		$this->event_thread = $this->events;

		$this->Clear_Events(); //Clear all concatenated rows
	}

	private function Close_DB_connection(){
    	$this->db_connection = null;
	}

    public function checkAdminlogin()
    {   
        if (isset($_SESSION["repzone_admin"]) && $_SESSION["repzone_admin"]) {
            $this->admin_logged = true;
        }
    }

} //ends class

$my_events = new userEvents();


?>