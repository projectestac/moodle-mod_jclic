<?php

require_once("../../config.php");
require_once("lib.php");

$id   = optional_param('id', 0, PARAM_INT);          // Course module ID
$a    = optional_param('a', 0, PARAM_INT);           // Assignment ID
$mode = optional_param('mode', 'all', PARAM_ALPHA);  // What mode are we in?

$url = new moodle_url('/mod/jclic/attempts.php');
if ($id) {
    if (! $cm = get_coursemodule_from_id('jclic', $id)) {
        print_error('invalidcoursemodule');
    }

    if (! $jclic = $DB->get_record("jclic", array("id"=>$cm->instance))) {
        print_error('invalidid', 'jclic');
    }

    if (! $course = $DB->get_record("course", array("id"=>$jclic->course))) {
        print_error('coursemisconf', 'jclic');
    }
    $url->param('id', $id);
} else {
    if (!$assignment = $DB->get_record("jclic", array("id"=>$a))) {
        print_error('invalidcoursemodule');
    }
    if (! $course = $DB->get_record("course", array("id"=>$jclic->course))) {
        print_error('coursemisconf', 'jclic');
    }
    if (! $cm = get_coursemodule_from_instance("jclic", $jclic->id, $course->id)) {
        print_error('invalidcoursemodule');
    }
    $url->param('a', $a);
}

if ($mode !== 'all') {
    $url->param('mode', $mode);
}
$PAGE->set_url($url);
require_login($course->id, false, $cm);

require_capability('mod/jclic:grade', get_context_instance(CONTEXT_MODULE, $cm->id));

//$PAGE->requires->js('/mod/assignment/assignment.js');

/// Load up the required jclic code
//require($CFG->dirroot.'/mod/assignment/type/'.$assignment->assignmenttype.'/assignment.class.php');


