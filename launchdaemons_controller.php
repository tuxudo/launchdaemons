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
        // Remove non-serial number characters
        $serial_number = preg_replace("/[^A-Za-z0-9_\-]]/", '', $serial_number);

        $sql = "SELECT *
                FROM launchdaemons 
                WHERE serial_number = '$serial_number'";
        
        $queryobj = new Launchdaemons_model;
        jsonView($queryobj->query($sql));
    }
} // END class Launchdaemon_controller
