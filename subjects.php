<?php

require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/../../user/profile/lib.php');

require_once('lib.php');

$instanceid = required_param('instanceid', PARAM_INT); // ID instància
$courseid   = required_param('courseid', PARAM_INT); // ID curso
$userid     = optional_param('userid', '', PARAM_INT); // ID usuario

$context = context_course::instance($courseid);

//
require_course_login($DB->get_record('course', ['id' => $courseid], '*', MUST_EXIST));
require_capability('block/dode:managesubjects', $context);

$PAGE->set_context($context);
$PAGE->set_url('/blocks/dode/subjects.php', ['instanceid' => $instanceid, 'courseid' => $courseid, 'userid' => $userid]);
$PAGE->set_pagelayout('general');
$PAGE->navbar->add(get_string('action_managesubjects', 'block_dode'));
$PAGE->set_title(get_string('action_managesubjects', 'block_dode'));
$PAGE->set_heading(get_string('action_managesubjects', 'block_dode'));

echo $OUTPUT->header();

if ($userid !== '') {
    $user = $DB->get_record_select('user', 'id=:id', ['id' => $userid], "firstname, lastname, id", MUST_EXIST);
    $user_current_subjects = [];
    $new_subjects = [];
    $dode_subjects =  ['FP117', 'FP118', 'FP119', 'FP120', 'FP121', 'FP122', 'FP123', 'FP124', 'FP125', 'FP126', 'FP127'];
    $user_subjects = array_values($DB->get_records_sql(
        "SELECT d.userid, GROUP_CONCAT(
            DISTINCT d.subjects 
            ORDER BY d.subjects) 
            AS subjects
            FROM {dode_subjects} d
            WHERE d.userid = $userid"
    ));

    $current_subjects = explode(",", $user_subjects[0]->subjects);

    foreach ($current_subjects as $current) { //filter the current subjects
        if (in_array($current, $dode_subjects) == true) {
            $dode_subjects = array_diff($dode_subjects, [$current]);; // remove the current subject
            array_push($user_current_subjects, $current); //create a new array with the user subject

        }
    }
    $dode_subjects = array_values($dode_subjects); // Convert to array values

    $params->subjects = $dode_subjects;
    $params->user_subjects = $user_current_subjects;
    $params->root = $CFG->wwwroot;
    $params->userid = $userid;
    $params->instanceid = $instanceid;
    $params->courseid = $courseid;
    $params->user = $user;

    echo $OUTPUT->render_from_template("block_dode/form", $params);
} else {

    try {
        $list_user = array_map(function ($el) {
            global $DB;
            try {
                $subject_us = array_values($DB->get_records_sql(  
                    "SELECT ds.subjects, GROUP_CONCAT(DISTINCT ds.subjects ORDER BY ds.subjects) AS subjects
                    FROM {dode_subjects} ds
                    WHERE ds.userid = :userid",
                    ['userid' => $el->id]
                ));
            } catch (\Throwable $th) {
                Lib::console_log($th);
            }
            // Lib::console_log($subject_us);
            $el->subjects = $subject_us[0]->subjects;
            $el->login = (profile_user_record($el->id))->SGID;
            
            return $el;
        }, array_values(
            $DB->get_records_sql(
                "SELECT u.id, u.firstname, u.lastname 
                FROM {user} u
                WHERE u.id IN(
                    SELECT ra.userid
                    FROM {role_assignments} ra
                    WHERE ra.contextid = :contextid
                    AND ra.roleid = (SELECT id FROM {role} WHERE archetype = 'student'))",
                ['contextid' => context_course::instance($_GET['courseid'])->id]


            )
        ));
    } catch (\moodle_exception $th) {
        Lib::console_log($th);
    }
 
    $context_table = new stdClass();
    $context_table->students = $list_user;
    $context_table->root = $CFG->wwwroot;
    $context_table->instanceid = $instanceid;
    $context_table->courseid = $courseid;


    echo $OUTPUT->render_from_template("block_dode/table", $context_table);
}

echo $OUTPUT->footer();
