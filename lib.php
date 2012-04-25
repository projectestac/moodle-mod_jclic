<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Library of interface functions and constants for module jclic
 *
 * All the core Moodle functions, neeeded to allow the module to work
 * integrated in Moodle should be placed here.
 * All the jclic specific functions, needed to implement all the module
 * logic, should go to locallib.php. This will help to save some memory when
 * Moodle is performing actions across all modules.
 *
 * @package    mod
 * @subpackage jclic
 * @copyright  2011 Departament d'Ensenyament de la Generalitat de Catalunya
 * @author     Sara Arjona Téllez <sarjona@xtec.cat>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

define('JCLIC_DEFAULT_PLUGINJS', 'http://clic.xtec.cat/dist/jclic/jclicplugin.js');
define('JCLIC_DEFAULT_LAP', 5);

if (!isset($CFG->jclic_jclicpluginjs)) {
    set_config('jclic_jclicpluginjs', JCLIC_DEFAULT_PLUGINJS);
}
if (!isset($CFG->jclic_lap)) {
    set_config("jclic_lap", "5");
}


////////////////////////////////////////////////////////////////////////////////
// Moodle core API                                                            //
////////////////////////////////////////////////////////////////////////////////

/**
 * Returns the information on whether the module supports a feature
 * 
 * @todo: review features before publishing the module
 *
 * @see plugin_supports() in lib/moodlelib.php
 * @param string $feature FEATURE_xx constant for requested feature
 * @return mixed true if the feature is supported, null if unknown
 */
function jclic_supports($feature) {
    switch($feature) {
        case FEATURE_GROUPS:                  return true;
//        case FEATURE_GROUPINGS:               return true;
//        case FEATURE_GROUPMEMBERSONLY:        return true;*/
        case FEATURE_MOD_INTRO:               return true;
//        case FEATURE_COMPLETION_TRACKS_VIEWS: return true;
        case FEATURE_GRADE_HAS_GRADE:         return true;
//        case FEATURE_GRADE_OUTCOMES:          return true;
//        case FEATURE_BACKUP_MOODLE2:          return true;
//        case FEATURE_SHOW_DESCRIPTION:        return true;*/
//        case FEATURE_ADVANCED_GRADING:        return true;
        default:                        return null;
    }
}

/**
 * Saves a new instance of the jclic into the database
 *
 * Given an object containing all the necessary data,
 * (defined by the form in mod_form.php) this function
 * will create a new instance and return the id number
 * of the new instance.
 * 
 * @todo: create event (when timedue added)
 *
 * @param object $jclic An object from the form in mod_form.php
 * @param mod_jclic_mod_form $mform
 * @return int The id of the newly inserted jclic record
 */
function jclic_add_instance(stdClass $jclic, mod_jclic_mod_form $mform = null) {
    global $DB;
    $cmid = $jclic->coursemodule;
    $jclic->timecreated = time();
    $jclic->url = trim($jclic->url);
    if ($jclic->skin=='') $jclic->skin = "default";

    $jclic->id = $DB->insert_record('jclic', $jclic);
    // we need to use context now, so we need to make sure all needed info is already in db
    $DB->set_field('course_modules', 'instance', $jclic->id, array('id'=>$cmid));
    
    jclic_save_file($jclic);
    
    /*
    if ($jclic->timedue) {
        $event = new stdClass();
        $event->name        = $jclic->name;
        $event->description = format_module_intro('jclic', $jclic, $jclic->coursemodule);
        $event->courseid    = $jclic->course;
        $event->groupid     = 0;
        $event->userid      = 0;
        $event->modulename  = 'jclic';
        $event->instance    = $returnid;
        $event->eventtype   = 'due';
        $event->timestart   = $jclic->timedue;
        $event->timeduration = 0;

        calendar_event::create($event);
    } */
    jclic_grade_item_update($jclic);
    
    return $jclic->id;    
}

/**
 * Updates an instance of the jclic in the database
 *
 * Given an object containing all the necessary data,
 * (defined by the form in mod_form.php) this function
 * will update an existing instance with new data.
 *
 * @param object $jclic An object from the form in mod_form.php
 * @param mod_jclic_mod_form $mform
 * @return boolean Success/Fail
 */
function jclic_update_instance(stdClass $jclic, mod_jclic_mod_form $mform = null) {
    global $DB;

    $jclic->timemodified = time();
    $jclic->id = $jclic->instance;
    $jclic->url = trim($jclic->url);

    $result = $DB->update_record('jclic', $jclic);
    if ($result){
        jclic_save_file($jclic);    
    }
    if ($result){
        // get existing grade item
        $result = jclic_grade_item_update($jclic);
    }
    
    return $result;
}

/**
 * Removes an instance of the jclic from the database
 *
 * Given an ID of an instance of this module,
 * this function will permanently delete the instance
 * and any data that depends on it.
 * 
 * @todo: delete event records (after adding this feature to the module)
 *
 * @param int $id Id of the module instance
 * @return boolean Success/Failure
 */
function jclic_delete_instance($id) {
    global $DB;

    if (!$jclic = $DB->get_record('jclic', array('id'=>$id))) {
        return false;
    }
    
    // Delete any dependent records
    $result = true;
    $rs =  $DB->get_record('jclic_sessions', array('jclicid' => $id));
    foreach($rs as $session){
        if (!$DB->delete_records('jclic_activities', array('session_id' => $rs->session_id))){
            echo 'jclic_activities';die();
            $result = false;
            exit;
        }
    }

    if ($result && !$DB->delete_records('jclic_sessions', array('jclicid' => $id))){
        echo 'jclic_sessions';die();
        $result = false;
    }

    if ($result && !$DB->delete_records('jclic', array('id' => $id))) {
        echo 'jclic';die();
        $result = false;
    }
    
    /*if ($result && !$DB->delete_records('event', array('modulename'=>'jclic', 'instance'=>$jclic->id))) {
        $result = false;
    }*/

    if ($result && !jclic_grade_item_delete($jclic)){
        $result = false;
    }

    return $result;
}

/**
 * Returns a small object with summary information about what a
 * user has done with a given particular instance of this module
 * Used for user activity reports.
 * $return->time = the time they did it
 * $return->info = a short text description
 *
 * @return stdClass|null
 */
function jclic_user_outline($course, $user, $mod, $jclic) {
    $jclic_summary = jclic_get_sessions_summary($jclic->id, $user->id);
    $return = new stdClass();
    $return->time = $jclic_summary->totaltime;
    $maxgrade = -1;
    if (property_exists($jclic, 'maxgrade')) $maxgrade = $jclic->maxgrade;
    $return->info = get_string('grade').': '.$jclic_summary->score.($maxgrade>0?'/'.$maxgrade:'');
    return $return;
}

/**
 * Prints a detailed representation of what a user has done with
 * a given particular instance of this module, for user activity reports.
 * 
 * @todo: implement
 *
 * @return string HTML
 */
function jclic_user_complete($course, $user, $mod, $jclic) {
    return '';
}

/**
 * Given a course and a time, this module should find recent activity
 * that has occurred in jclic activities and print it out.
 * Return true if there was output, or false is there was none.
 * 
 * @todo: implement
 *
 * @return boolean
 */
function jclic_print_recent_activity($course, $viewfullnames, $timestart) {
    return false;  //  True if anything was printed, otherwise false
}

/**
 * Returns all activity in jclics since a given time
 * 
 * @todo: implement
 *
 * @param array $activities sequentially indexed array of objects
 * @param int $index
 * @param int $timestart
 * @param int $courseid
 * @param int $cmid
 * @param int $userid defaults to 0
 * @param int $groupid defaults to 0
 * @return void adds items into $activities and increases $index
 */
function jclic_get_recent_mod_activity(&$activities, &$index, $timestart, $courseid, $cmid, $userid=0, $groupid=0) {
}

/**
 * Prints single activity item prepared by {@see jclic_get_recent_mod_activity()}

 * @return void
 */
function jclic_print_recent_mod_activity($activity, $courseid, $detail, $modnames, $viewfullnames) {
    //TODO: implement
}

/**
 * Function to be run periodically according to the moodle cron
 * This function searches for things that need to be done, such
 * as sending out mail, toggling flags etc ...
 *
 * @return boolean
 * @todo Finish documenting this function
 **/
function jclic_cron () {
    return true;
}

/**
 * Returns an array of users who are participanting in this jclic
 *
 * Must return an array of users who are participants for a given instance
 * of jclic. Must include every user involved in the instance,
 * independient of his role (student, teacher, admin...). The returned
 * objects must contain at least id property.
 * See other modules as example.
 * 
 * @todo: deprecated - to be deleted in 2.2
 * 
 * @param int $jclicid ID of an instance of this module
 * @return boolean|array false if no participants, array of objects otherwise
 */
function jclic_get_participants($jclicid) {
    global $CFG, $DB;

    //Get students
    $students = $DB->get_records_sql("SELECT DISTINCT u.id, u.id as userid
                                 FROM {user} u,
                                      {jclic_sessions} js
                                 WHERE js.jclicid = ? and
                                       u.id = js.user_id", array($jclicid));
    //Get teachers
    $teachers = $DB->get_records_sql("SELECT DISTINCT u.id, u.id as userid
                                 FROM {user} u,
                                      {jclic_sessions} js
                                 WHERE js.jclicid = ? and
                                       u.id = js.user_id", array($jclicid));

    //Add teachers to students
    if ($teachers) {
        foreach ($teachers as $teacher) {
            $students[$teacher->id] = $teacher;
        }
    }
    //Return students array (it contains an array of unique users)
    return ($students);
}

/**
 * Returns all other caps used in the module
 *
 * @example return array('moodle/site:accessallgroups');
 * @return array
 */
function jclic_get_extra_capabilities() {
    return array('moodle/site:accessallgroups', 'moodle/site:viewfullnames', 'moodle/grade:managegradingforms');
}

////////////////////////////////////////////////////////////////////////////////
// Gradebook API                                                              //
////////////////////////////////////////////////////////////////////////////////

/**
 * Is a given scale used by the instance of jclic?
 *
 * This function returns if a scale is being used by one jclic
 * if it has support for grading and scales. Commented code should be
 * modified if necessary. See forum, glossary or journal modules
 * as reference.
 *
 * @param int $jclicid ID of an instance of this module
 * @return bool true if the scale is used by the given jclic instance
 */
function jclic_scale_used($jclicid, $scaleid) {
    global $DB;
    
    $return = false;
    $rec = $DB->get_record('jclic', array('id'=>$jclicid,'grade'=>-$scaleid));
    if (!empty($rec) && !empty($scaleid)) {
        $return = true;
    }
    return $return;
}

/**
 * Checks if scale is being used by any instance of jclic.
 *
 * This is used to find out if scale used anywhere.
 *
 * @param $scaleid int
 * @return boolean true if the scale is used by any jclic instance
 */
function jclic_scale_used_anywhere($scaleid) {
    global $DB;

    if ($scaleid and $DB->record_exists('jclic', array('grade'=>-$scaleid))) {
        return true;
    } else {
        return false;
    }
}

/**
 * Creates or updates grade item for the give jclic instance
 *
 * Needed by grade_update_mod_grades() in lib/gradelib.php
 *
 * @uses GRADE_TYPE_NONE
 * @uses GRADE_TYPE_VALUE
 * @uses GRADE_TYPE_SCALE
 * @param stdClass $jclic instance object with extra cmidnumber and modname property
 * @param mixed $grades optional array/object of grade(s); 'reset' means reset grades in gradebook
 * @return int 0 if ok
 */
function jclic_grade_item_update(stdClass $jclic, $grades=NULL) {
    global $CFG;
    require_once($CFG->libdir.'/gradelib.php');

    if (!isset($jclic->courseid)) {
        $jclic->courseid = $jclic->course;
    }

    $params = array();
    $params['itemname'] = clean_param($jclic->name, PARAM_NOTAGS);
    $params['idnumber'] = $jclic->cmidnumber;
    if ($jclic->grade > 0) {
        $params['gradetype'] = GRADE_TYPE_VALUE;
        $params['grademax']  = $jclic->grade;
        $params['grademin']  = 0;

    } else if ($jclic->grade < 0) {
        $params['gradetype'] = GRADE_TYPE_SCALE;
        $params['scaleid']   = -$jclic->grade;

    } else {
        $params['gradetype'] = GRADE_TYPE_NONE; // allow text comments only
    }

    if ($grades  === 'reset') {
        $params['reset'] = true;
        $grades = NULL;
    }
    
    grade_update('mod/jclic', $jclic->courseid, 'mod', 'jclic', $jclic->id, 0, $grades, $params);

    return true;
}

/**
 * Delete grade item for given jclic
 *
 * @global object
 * @param object $jclic object
 * @return object grade_item
 */
function jclic_grade_item_delete($jclic) {
    global $CFG;
    require_once($CFG->libdir.'/gradelib.php');

    return grade_update('mod/jclic', $jclic->course, 'mod', 'jclic', $jclic->id, 0, NULL, array('deleted'=>1));
}

/**
 * Return grade for given user or all users.
 *
 * @todo: implement userid=0 (all users)
 * @todo: optimize this function (to avoid call jclic_get_sessions_summary or update only mandatory info)
 * 
 * @param object $jclic object
 * @param int $userid optional user id, 0 means all users
 * @return array array of grades, false if none
 */
function jclic_get_user_grades($jclic, $userid=0) {
    global $CFG, $DB;
    require_once($CFG->dirroot.'/mod/jclic/locallib.php');

    // sanity check on $jclic->id
    if (! isset($jclic->id)) {
        return;
    }
    
    $sessions_summary = jclic_get_sessions_summary($jclic->id, $userid);
    if ($jclic->avaluation=='score'){
        $grades[$userid]=$sessions_summary->score;				
    }else{
        $grades[$userid]=$sessions_summary->solved;
    }
    return $grades;
}

/**
 * Update jclic grades in the gradebook
 *
 * Needed by grade_update_mod_grades() in lib/gradelib.php
 * 
 * @todo: Fix some problems (this function is not working when is called from beans.php)
 *
 * @param stdClass $jclic instance object with extra cmidnumber and modname property
 * @param int $userid update grade of specific user only, 0 means all participants
 * @param boolean $nullifnone return null if grade does not exist
 * @return void
 */
function jclic_update_grades(stdClass $jclic, $userid = 0, $nullifnone=true) {
    global $CFG, $DB;
    require_once($CFG->libdir.'/gradelib.php');

    if ($jclic->grade == 0) {
        jclic_grade_item_update($jclic);

    } else if ($grades = jclic_get_user_grades($jclic, $userid)) {
        foreach($grades as $k=>$v) {
            if ($v->rawgrade == -1) {
                $grades[$k]->rawgrade = null;
            }
        }
        jclic_grade_item_update($jclic, $grades);

    } else if ($userid and $nullifnone) {
        $grade = new stdClass();
        $grade->userid   = $userid;
        $grade->rawgrade = NULL;
        jclic_grade_item_update($jclic, $grade);
        
    } else {
        jclic_grade_item_update($jclic);
    }
}

////////////////////////////////////////////////////////////////////////////////
// File API                                                                   //
////////////////////////////////////////////////////////////////////////////////

/**
 * Returns the lists of all browsable file areas within the given module context
 *
 * The file area 'intro' for the activity introduction field is added automatically
 * by {@link file_browser::get_file_info_context_module()}
 *
 * @param stdClass $course
 * @param stdClass $cm
 * @param stdClass $context
 * @return array of [(string)filearea] => (string)description
 */
function jclic_get_file_areas($course, $cm, $context) {
    return array(
        'content'      => get_string('urledit',  'jclic')
    );
}

/**
 * Serves the files from the jclic file areas
 *
 * @param stdClass $course
 * @param stdClass $cm
 * @param stdClass $context
 * @param string $filearea
 * @param array $args
 * @param bool $forcedownload
 * @return void this should never return to the caller
 */
function jclic_pluginfile($course, $cm, $context, $filearea, array $args, $forcedownload) {
    global $DB, $CFG;

    if ($context->contextlevel != CONTEXT_MODULE) {
        send_file_not_found();
    }

    require_login($course, true, $cm);
    
    if (!has_capability('mod/jclic:view', $context)) {
        return false;
    }

    if ($filearea !== 'content') {
        // intro is handled automatically in pluginfile.php
        return false;
    }

    array_shift($args); // ignore revision - designed to prevent caching problems only

    $fs = get_file_storage();
    $relativepath = implode('/', $args);
    $fullpath = rtrim("/$context->id/mod_jclic/$filearea/0/$relativepath", '/');
    do {
        if ($file = $fs->get_file_by_hash(sha1($fullpath))) {
            break;
        }
/*            
            $jclic = $DB->get_record('jclic', array('id'=>$cm->instance), 'id, legacyfiles', MUST_EXIST);
            if (!$file = jcliclib_try_file_migration('/'.$relativepath, $cm->id, $cm->course, 'mod_jclic', 'content', 0)) {
                return false;
            }
            // file migrate - update flag
            $resource->legacyfileslast = time();
            $DB->update_record('resource', $resource); 
 */
    } while (false);

    // finally send the file
    send_stored_file($file, 86400, 0, $forcedownload);    
}

////////////////////////////////////////////////////////////////////////////////
// Navigation API                                                             //
////////////////////////////////////////////////////////////////////////////////

/**
 * Extends the global navigation tree by adding jclic nodes if there is a relevant content
 *
 * This can be called by an AJAX request so do not rely on $PAGE as it might not be set up properly.
 *
 * @param navigation_node $navref An object representing the navigation tree node of the jclic module instance
 * @param stdClass $course
 * @param stdClass $module
 * @param cm_info $cm
 */
function jclic_extend_navigation(navigation_node $navref, stdclass $course, stdclass $module, cm_info $cm) {
}

/**
 * Extends the settings navigation with the jclic settings
 *
 * This function is called when the context for the page is a jclic module. This is not called by AJAX
 * so it is safe to rely on the $PAGE.
 *
 * @param settings_navigation $settingsnav {@link settings_navigation}
 * @param navigation_node $jclicnode {@link navigation_node}
 */
function jclic_extend_settings_navigation(settings_navigation $settingsnav, navigation_node $jclicnode=null) {
}
