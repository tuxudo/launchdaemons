<?php
/**
 * launchdaemons module class
 *
 * @package munkireport
 * @author tuxudo
 **/
class Launchdaemons_controller extends Module_controller
{
    
    /*** Protect methods with auth! ****/
    public function __construct()
    {
        // Store module path
        $this->module_path = dirname(__FILE__);
        
        // Add local config
        configAppendFile(__DIR__ . '/config.php', 'launchdaemons');
    }
    
    /**
    * Default method
    *
    * @author AvB
    **/
    public function index()
    {
        echo "You've loaded the launchdaemons module!";
    }
    
    /**

    /**
    * Retrieve data in json format
    *
    * @return void
    * @author tuxudo
    **/
    public function get_tab_data($serial_number = '')
    {
        $obj = new View();

        if (! $this->authorized()) {
            $obj->view('json', array('msg' => 'Not authorized'));
            return;
        }
        
        $sql = "SELECT *
                        FROM launchdaemons 
                        WHERE serial_number = '$serial_number'";
        
        $queryobj = new Launchdaemons_model();
        $launchdaemons_tab = $queryobj->query($sql);
        $obj->view('json', array('msg' => current(array('msg' => $launchdaemons_tab)))); 
    }
} // END class Launchdaemon_controller
