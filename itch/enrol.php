<?php  // $Id: enrol.php,v 1.28.2.3 2008/01/07 20:56:20 poltawski Exp $
       // Implements all the main code for the Interswitch plugin

require_once("$CFG->dirroot/enrol/enrol.class.php");


class enrolment_plugin_iinterswitch {


/// Override the base print_entry() function
function print_entry($course) {
    global $CFG, $USER;

    $strloginto = get_string("loginto", "", $course->shortname);
    $strcourses = get_string("courses");

    $teacher = get_teacher($course->id);

    if ( (float) $course->cost < 0 ) {
        $cost = (float) $CFG->enrol_cost;
    } else {
        $cost = (float) $course->cost;
    }

    if (abs($cost) < 0.01) { // no cost, default to base class entry to course

        $manual = enrolment_factory::factory('manual');
        $manual->print_entry($course);

    } else {
        $navlinks = array();
        $navlinks[] = array('name' => $strcourses, 'link' => "$CFG->wwwroot/course", 'type' => 'misc');
        $navlinks[] = array('name' => $strloginto, 'link' => null, 'type' => 'misc');
        $navigation = build_navigation($navlinks);

        print_header($strloginto, $course->fullname, $navigation);
        print_course($course, "80%");

        if ($course->password) {  // Presenting two options
            //print_heading(get_string('costorkey', 'enrol_iinterswitch'), 'center');
        }

        print_simple_box_start("center");
        //print "<p> $USER->firstname $USER->lastname  </p>"; //This gets the first and last.

        if ($USER->username == 'guest') { // force login only for guest user, not real users with guest role
            if (empty($CFG->loginhttps)) {
                $wwwroot = $CFG->wwwroot;
            } else {
                // This actually is not so secure ;-), 'cause we're
                // in unencrypted connection...
                $wwwroot = str_replace("http://", "https://", $CFG->wwwroot);
            }
            echo '<div align="center"><p>'.get_string('paymentrequired').'</p>';
            //echo '<p><b>'.get_string('cost').": N$cost".'</b></p>';
            echo '<p><a href="'.$wwwroot.'/login/">'.get_string('loginsite').'</a></p>';
            echo '</div>';
        } else {
            //Sanitise some fields before building the Interswitch form
            $coursefullname  = $course->fullname;
            $courseshortname = $course->shortname;
            $userfullname    = fullname($USER);
            $userfirstname   = $USER->firstname;
            $userlastname    = $USER->lastname;
            $useraddress     = $USER->address;
            $usercity        = $USER->city;
            
            session_start();
            include($CFG->dirroot.'/enrol/iinterswitch/enrol.html');
            //include($CFG->dirroot.'/enrol/iinterswitch/enrol2.php');
        }

        print_simple_box_end();

        if ($course->password) {  // Second option
            $password = '';
            //include($CFG->dirroot.'/enrol/manual/enrol.html');
        }

        print_footer();

    }
} // end of function print_entry()




/// Override the get_access_icons() function
function get_access_icons($course) {
    global $CFG;

    $str = '';

    if ( (float) $course->cost < 0) {
        $cost = (float) $CFG->enrol_cost;
    } else {
        $cost = (float) $course->cost;
    }

    if (abs($cost) < 0.01) {
        $manual = enrolment_factory::factory('manual');
        $str = $manual->get_access_icons($course);

    } else {

        $strrequirespayment = get_string("requirespayment");
        $strcost = get_string("cost");

        //$str .= '<div class="cost" title="'.$strrequirespayment.'">'.$strcost.': ';
        //$str .= number_format($cost,2).'</div>';
    }

    return $str;
}

/// Override the base class config_form() function
function config_form($frm) {
    global $CFG;

    $vars = array('enrol_cost', 'enrol_product_id','enrol_mac', 'enrol_demo','enrol_iswitch','enrol_payment_params','enrol_pay_item_id','enrol_xml',
                  'enrol_mailstudents', 'enrol_mailteachers', 'enrol_mailadmins');
    foreach ($vars as $var) {
        if (!isset($frm->$var)) {
            $frm->$var = '';
        }
    }

    include("$CFG->dirroot/enrol/iinterswitch/config.html");
}

function process_config($config) {

    if (!isset($config->enrol_cost)) {
        $config->enrol_cost = 0;
    }
    set_config('enrol_cost', $config->enrol_cost);

  

    if (!isset($config->enrol_product_id)) {
        $config->enrol_product_id = '0001';
    }
    set_config('enrol_product_id', $config->enrol_product_id);

    if (!isset($config->enrol_mac)) {
        $config->enrol_mac = '';
    }
    set_config('enrol_mac', $config->enrol_mac);


  if (!isset($config->enrol_pay_item_id)) {
        $config->enrol_pay_item_id = '';    }
    set_config('enrol_pay_item_id', $config->enrol_pay_item_id);

 if (!isset($config->enrol_iswitch)) {
        $config->enrol_iswitch = "https://stageserv.interswitchng.com/test_paydirect/pay";
    }

    set_config('enrol_iswitch', $config->enrol_iswitch);


 if (!isset($config->enrol_payment_params)) {
        $config->enrol_payment_params = "";
    }
    set_config('enrol_payment_params', $config->enrol_payment_params);


 if (!isset($config->enrol_xml)) {
        $config->enrol_xml = "";
    }
    set_config('enrol_xml', $config->enrol_xml);

    
    if (!isset($config->enrol_mailstudents)) {
        $config->enrol_mailstudents = '';
    }
    set_config('enrol_mailstudents', $config->enrol_mailstudents);

    if (!isset($config->enrol_mailteachers)) {
        $config->enrol_mailteachers = '';
    }
    set_config('enrol_mailteachers', $config->enrol_mailteachers);

    if (!isset($config->enrol_mailadmins)) {
        $config->enrol_mailadmins = '';
    }
    set_config('enrol_mailadmins', $config->enrol_mailadmins);

    return true;

}

/**
* This function enables internal enrolment when iinterswitch is primary and course key is set at the same time.
*
* @param    form    the form data submitted, as an object
* @param    course  the current course, as an object
*/
function check_entry($form, $course) {
    $manual = enrolment_factory::factory('manual');
    $manual->check_entry($form, $course);
    if (isset($manual->errormsg)) {
        $this->errormsg = $manual->errormsg;
    }
}

/**
 * Provides method to print the enrolment key form code. This method is called
 * from /enrol/manual/enrol.html if it's included
 * @param  object a valid course object
 */
 
function print_enrolmentkeyfrom($course) {
    $manual = enrolment_factory::factory('manual');
    $manual->print_enrolmentkeyfrom($course);
}


} // end of class definition

?>