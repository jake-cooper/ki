<?php

/**
 * webpay enrolment plugin tests.
 *
 * @package    enrol_webpay
 * @category   phpunit
 * @copyright  2014 Jake Cooper / Tim Lycet
 * @author     Jake Cooper / Tim Lycet - based on code by Petr Skoda, Eugene Venter and others
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();


class enrol_webpay_testcase extends advanced_testcase {

    protected function enable_plugin() {
        $enabled = enrol_get_plugins(true);
        $enabled['webpay'] = true;
        $enabled = array_keys($enabled);
        set_config('enrol_plugins_enabled', implode(',', $enabled));
    }

    protected function disable_plugin() {
        $enabled = enrol_get_plugins(true);
        unset($enabled['webpay']);
        $enabled = array_keys($enabled);
        set_config('enrol_plugins_enabled', implode(',', $enabled));
    }

    public function test_basics() {
        $this->assertFalse(enrol_is_enabled('webpay'));
        $plugin = enrol_get_plugin('webpay');
        $this->assertInstanceOf('enrol_webpay_plugin', $plugin);
        $this->assertEquals(ENROL_EXT_REMOVED_SUSPENDNOROLES, get_config('enrol_webpay', 'expiredaction'));
    }

    public function test_sync_nothing() {
        $this->resetAfterTest();

        $this->enable_plugin();
        $webpayplugin = enrol_get_plugin('webpay');

        // Just make sure the sync does not throw any errors when nothing to do.
        $webpayplugin->sync(new null_progress_trace());
    }

    public function test_expired() {
        global $DB;
        $this->resetAfterTest();

        /** @var enrol_webpay_plugin $webpayplugin  */
        $webpayplugin = enrol_get_plugin('webpay');
        /** @var enrol_manual_plugin $manualplugin  */
        $manualplugin = enrol_get_plugin('manual');
        $this->assertNotEmpty($manualplugin);

        $now = time();
        $trace = new null_progress_trace();
        $this->enable_plugin();


        // Prepare some data.

        $studentrole = $DB->get_record('role', array('shortname'=>'student'));
        $this->assertNotEmpty($studentrole);
        $teacherrole = $DB->get_record('role', array('shortname'=>'teacher'));
        $this->assertNotEmpty($teacherrole);
        $managerrole = $DB->get_record('role', array('shortname'=>'manager'));
        $this->assertNotEmpty($managerrole);

        $user1 = $this->getDataGenerator()->create_user();
        $user2 = $this->getDataGenerator()->create_user();
        $user3 = $this->getDataGenerator()->create_user();
        $user4 = $this->getDataGenerator()->create_user();

        $course1 = $this->getDataGenerator()->create_course();
        $course2 = $this->getDataGenerator()->create_course();
        $context1 = context_course::instance($course1->id);
        $context2 = context_course::instance($course2->id);

        $data = array('roleid'=>$studentrole->id, 'courseid'=>$course1->id);
        $id = $webpayplugin->add_instance($course1, $data);
        $instance1  = $DB->get_record('enrol', array('id'=>$id));
        $data = array('roleid'=>$studentrole->id, 'courseid'=>$course2->id);
        $id = $webpayplugin->add_instance($course2, $data);
        $instance2 = $DB->get_record('enrol', array('id'=>$id));
        $data = array('roleid'=>$teacherrole->id, 'courseid'=>$course2->id);
        $id = $webpayplugin->add_instance($course2, $data);
        $instance3 = $DB->get_record('enrol', array('id'=>$id));

        $maninstance1 = $DB->get_record('enrol', array('courseid'=>$course2->id, 'enrol'=>'manual'), '*', MUST_EXIST);

        $manualplugin->enrol_user($maninstance1, $user3->id, $studentrole->id);

        $this->assertEquals(1, $DB->count_records('user_enrolments'));
        $this->assertEquals(1, $DB->count_records('role_assignments'));
        $this->assertEquals(1, $DB->count_records('role_assignments', array('roleid'=>$studentrole->id)));

        $webpayplugin->enrol_user($instance1, $user1->id, $studentrole->id);
        $webpayplugin->enrol_user($instance1, $user2->id, $studentrole->id);
        $webpayplugin->enrol_user($instance1, $user3->id, $studentrole->id, 0, $now-60);

        $webpayplugin->enrol_user($instance2, $user1->id, $studentrole->id, 0, 0);
        $webpayplugin->enrol_user($instance2, $user2->id, $studentrole->id, 0, $now-60*60);
        $webpayplugin->enrol_user($instance2, $user3->id, $studentrole->id, 0, $now+60*60);

        $webpayplugin->enrol_user($instance3, $user1->id, $teacherrole->id, $now-60*60*24*7, $now-60);
        $webpayplugin->enrol_user($instance3, $user4->id, $teacherrole->id);

        role_assign($managerrole->id, $user3->id, $context1->id);

        $this->assertEquals(9, $DB->count_records('user_enrolments'));
        $this->assertEquals(9, $DB->count_records('role_assignments'));
        $this->assertEquals(6, $DB->count_records('role_assignments', array('roleid'=>$studentrole->id)));
        $this->assertEquals(2, $DB->count_records('role_assignments', array('roleid'=>$teacherrole->id)));
        $this->assertEquals(1, $DB->count_records('role_assignments', array('roleid'=>$managerrole->id)));

        // Execute tests.

        $webpayplugin->set_config('expiredaction', ENROL_EXT_REMOVED_KEEP);
        $code = $webpayplugin->sync($trace);
        $this->assertSame(0, $code);
        $this->assertEquals(9, $DB->count_records('user_enrolments'));
        $this->assertEquals(9, $DB->count_records('role_assignments'));


        $webpayplugin->set_config('expiredaction', ENROL_EXT_REMOVED_SUSPENDNOROLES);
        $webpayplugin->sync($trace);
        $this->assertEquals(9, $DB->count_records('user_enrolments'));
        $this->assertEquals(6, $DB->count_records('role_assignments'));
        $this->assertEquals(4, $DB->count_records('role_assignments', array('roleid'=>$studentrole->id)));
        $this->assertEquals(1, $DB->count_records('role_assignments', array('roleid'=>$teacherrole->id)));
        $this->assertFalse($DB->record_exists('role_assignments', array('contextid'=>$context1->id, 'userid'=>$user3->id, 'roleid'=>$studentrole->id)));
        $this->assertFalse($DB->record_exists('role_assignments', array('contextid'=>$context2->id, 'userid'=>$user2->id, 'roleid'=>$studentrole->id)));
        $this->assertFalse($DB->record_exists('role_assignments', array('contextid'=>$context2->id, 'userid'=>$user1->id, 'roleid'=>$teacherrole->id)));
        $this->assertTrue($DB->record_exists('role_assignments', array('contextid'=>$context2->id, 'userid'=>$user1->id, 'roleid'=>$studentrole->id)));


        $webpayplugin->set_config('expiredaction', ENROL_EXT_REMOVED_UNENROL);
        role_assign($studentrole->id, $user3->id, $context1->id);
        role_assign($studentrole->id, $user2->id, $context2->id);
        role_assign($teacherrole->id, $user1->id, $context2->id);
        $this->assertEquals(9, $DB->count_records('user_enrolments'));
        $this->assertEquals(9, $DB->count_records('role_assignments'));
        $this->assertEquals(6, $DB->count_records('role_assignments', array('roleid'=>$studentrole->id)));
        $this->assertEquals(2, $DB->count_records('role_assignments', array('roleid'=>$teacherrole->id)));
        $webpayplugin->sync($trace);
        $this->assertEquals(6, $DB->count_records('user_enrolments'));
        $this->assertFalse($DB->record_exists('user_enrolments', array('enrolid'=>$instance1->id, 'userid'=>$user3->id)));
        $this->assertFalse($DB->record_exists('user_enrolments', array('enrolid'=>$instance2->id, 'userid'=>$user2->id)));
        $this->assertFalse($DB->record_exists('user_enrolments', array('enrolid'=>$instance3->id, 'userid'=>$user1->id)));
        $this->assertEquals(5, $DB->count_records('role_assignments'));
        $this->assertEquals(4, $DB->count_records('role_assignments', array('roleid'=>$studentrole->id)));
        $this->assertEquals(1, $DB->count_records('role_assignments', array('roleid'=>$teacherrole->id)));
    }
}
