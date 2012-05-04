<?php
 
define('CLI_SCRIPT', 1);
require_once('config.php');
require_once($CFG->dirroot . '/backup/util/includes/restore_includes.php');

$folder = 'c184c283a0e5788c99345c497177b4abb80cf134';
$userid = 2;
$fullname = 'JClic restore';
$shortname = 'jclic_restore';
$categoryid = 1;
$courseid = 100;


// Transaction
$transaction = $DB->start_delegated_transaction();

// Create new course
$courseid = restore_dbops::create_new_course($fullname, $shortname, $categoryid);

// Restore backup into course
$controller = new restore_controller($folder, $courseid, 
        backup::INTERACTIVE_NO, backup::MODE_SAMESITE, $userid,
        backup::TARGET_NEW_COURSE);
$controller->execute_precheck();
$controller->execute_plan();

// Commit
$transaction->allow_commit();
