<?php
class Lib
{
    public static function console_log($any): void
    {
        echo "<script> console.log(" . json_encode($any) . ")</script>";
    }

    public static function update_db_subject($subjects, $userid, $instanceid, $courseid)
    { //function to update the subjects in data base
        global $CFG, $DB;
        foreach ($subjects as $subject) {
            $create_user_dode = $DB->get_record_select('user', 'id=:id', ['id' => $userid], "id", MUST_EXIST);
            $data = new Stdclass();
            $data->userid = $create_user_dode->id;
            $data->subjects = $subject;
            $DB->insert_record('dode_subjects', $data);
        }

        // $CFG->wwwroot . "/blocks/dode/subjects.php?instanceid=" . $instanceid . "&courseid=" . $courseid,
        redirect(
            $CFG->wwwroot . "/blocks/dode/subjects.php?instanceid=" .$instanceid. "&courseid=" .$courseid,
            get_string('form_success', 'block_dode'),
            null,
            \core\output\notification::NOTIFY_SUCCESS
        );
    }
}
