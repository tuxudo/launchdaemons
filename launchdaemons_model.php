<?php

use CFPropertyList\CFPropertyList;

class Launchdaemons_model extends \Model
{
    public function __construct($serial = '')
    {
        parent::__construct('id', 'launchdaemons'); //primary key, tablename
        $this->rs['id'] = '';
        $this->rs['serial_number'] = $serial;
        $this->rs['label'] = '';
        $this->rs['path'] = '';
        $this->rs['disabled'] = 0;
        $this->rs['ondemand'] = 0;
        $this->rs['runatload'] = 0;
        $this->rs['program'] = '';
        $this->rs['startonmount'] = 0;
        $this->rs['startinterval'] = 0;
        $this->rs['keepalive'] = 0;
        $this->rs['daemon_json'] = '';
        
        // Retrieve record for serial number
        if ($serial) {
            $this->retrieve_record($serial);
        }

        $this->serial = $serial;
    }


    // ------------------------------------------------------------------------
    /**
     * Process data sent by postflight
     *
     * @param string data
     *
     **/
    public function process($data)
    {
        // If data is empty, echo out error
        if (! $data) {
            echo ("Error Processing launchdaemons module: No data found");
        } else { 
            
            // Delete previous entries
            $this->deleteWhere('serial_number=?', $this->serial_number);
            
            // List of labels to ignore
            $label_ignorelist = is_array(conf('launchdaemon_ignorelist')) ? conf('launchdaemon_ignorelist') : array();
            $regex = '/^'.implode('|', $label_ignorelist).'$/';

            // Process incoming launchdaemons.plist
            $parser = new CFPropertyList();
            $parser->parse($data, CFPropertyList::FORMAT_XML);
            $plist = $parser->toArray();

            // Process each daemon in the plist
            foreach ($plist as $daemon){

                // Check if is a user daemon and if processing userdaemons is allowed
                if ( substr( $daemon['path'], 0, 7 ) === "/Users/" && !conf('user_agents')) {
                    continue;
                }

                // Check if we should skip this daemon
                if (preg_match($regex, $daemon['label'])) {
                    continue;
                }
                
                // Make array of booleans for setting default
                $booleans = array('disabled','ondemand','runatload','startonmount','keepalive');

                // Process each field in the daemon
                foreach (array('label','path','disabled','ondemand','runatload','program','startonmount','startinterval','keepalive','daemon_json') as $item) {
                    
                    // If key does not exist and is boolean, set it to zero
                    if ( ! array_key_exists($item, $daemon) && in_array($item, $booleans)) {
                        $this->$item = 0;
                    
                    // Else if key does not exist in $daemon, null it
                    } else if ( ! array_key_exists($item, $daemon) || $daemon[$item] == '') {
                        $this->$item = null;

                    // Set the db fields to be the same as those in the daemon
                    } else {
                        $this->$item = $daemon[$item];
                    }
                }

                // Save the lunch for dinner later because reheated pizza is amazeballs
                $this->id = '';
                $this->save();
            }
        }
    }
}
