<?php

/**
 * Webpay enrolment plugin version specification.
 *
 * @package    enrol_webpay
 * @copyright  2014 Jake Cooper / Tim Lycet
 * @author     Jake Cooper / Tim Lycet - based on code by Petr Skoda, Eugene Venter and others
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$plugin->version   = 2014071200;        // The current plugin version (Date: YYYYMMDDXX)
$plugin->requires  = 2014050800;        // Requires this Moodle version
$plugin->component = 'enrol_webpay';    // Full name of the plugin (used for diagnostics)
$plugin->cron      = 60;
