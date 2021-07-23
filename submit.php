<?php
require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/../../user/profile/lib.php');
require_once('lib.php');


try {
    global $CFG, $PAGE, $OUTPUT;
    $instanceid = $_POST['instanceid'];
    $courseid   = $_POST['courseid'];
    $userid     = $_POST['userid'];

    $all_subjects =  ['FP117', 'FP118', 'FP119', 'FP120', 'FP121', 'FP122', 'FP123', 'FP124', 'FP125', 'FP126', 'FP127'];
    $new_subjects = [];
    for($i = 0; $i < count($all_subjects); $i++) {
        if ($_POST[$all_subjects[$i]] == $all_subjects[$i]){
            array_push($new_subjects, $all_subjects[$i]);
        }
    }
    lib::console_log($userid, $instanceid, $courseid);

    $user_exists = $DB->record_exists('dode_subjects', ['userid' => $userid]); //check if the user is already in that table
    if ($user_exists === false) {  //if it is not in the table
        Lib::update_db_subject($new_subjects, $userid, $instanceid, $courseid);
    } else {
        $DB->delete_records('dode_subjects', ['userid' => $userid]); //delete the old records
        Lib::update_db_subject($new_subjects, $userid, $instanceid, $courseid); //insert new subject records
    }

} catch (\moodle_exception $th) {
    echo "<script> console.log(" . json_encode($th) . ")</script>";
}


//$ids = [];
//$id_update = array_values($DB->get_records('dode_subjects', ['userid' => $userid], 'id'));
//foreach($id_update as $id){
//    array_push($ids,$id->id);
//}
//$dode_update = new stdClass();
//$dode_update->id = $ids;
//$dode_update->userid = $userid;
//$dode_update->subjects = $new_subjects;
//
//Lib::console_log($dode_update);
//$DB->update_record('dode_subjects', $dode_update, $bulk=true);