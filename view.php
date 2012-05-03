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
 * Prints a particular instance of jclic
 *
 * You can have a rather longer description of the file as well,
 * if you like, and it can span multiple lines.
 *
 * @package    mod
 * @subpackage jclic
 * @copyright  2011 Departament d'Ensenyament de la Generalitat de Catalunya
 * @author     Sara Arjona Téllez <sarjona@xtec.cat>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/locallib.php');

$id = optional_param('id', 0, PARAM_INT); // course_module ID, or
$n  = optional_param('n', 0, PARAM_INT);  // jclic instance ID - it should be named as the first character of the module

if ($id) {
    $cm         = get_coursemodule_from_id('jclic', $id, 0, false, MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $jclic  = $DB->get_record('jclic', array('id' => $cm->instance), '*', MUST_EXIST);
} elseif ($n) {
    $jclic  = $DB->get_record('jclic', array('id' => $n), '*', MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $jclic->course), '*', MUST_EXIST);
    $cm         = get_coursemodule_from_instance('jclic', $jclic->id, $course->id, false, MUST_EXIST);
} else {
    error('You must specify a course_module ID or an instance ID');
}

require_login($course, true, $cm);
$context = get_context_instance(CONTEXT_MODULE, $cm->id);
require_capability('mod/jclic:view', $context);

add_to_log($course->id, 'jclic', 'view', "view.php?id={$cm->id}", $jclic->name, $cm->id);

/// Print the page header

$PAGE->set_url('/mod/jclic/view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($jclic->name));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($context);

// other things you may want to set - remove if not needed
//$PAGE->set_cacheable(false);
//$PAGE->set_focuscontrol('some-html-id');
//$PAGE->add_body_class('jclic-'.$somevar);

jclic_view_header($jclic, $cm, $course);
jclic_view_intro($jclic, $cm);
jclic_view_dates($jclic, $cm);

if (has_capability('mod/jclic:grade', $context, $USER->id, false)){
    // Show students list with their results
    require_once($CFG->libdir.'/gradelib.php');
    $perpage = optional_param('perpage', 10, PARAM_INT);
    $perpage = ($perpage <= 0) ? 10 : $perpage ;
    $page    = optional_param('page', 0, PARAM_INT);
    
    
    /// find out current groups mode
    $groupmode = groups_get_activity_groupmode($cm);
    $currentgroup = groups_get_activity_group($cm, true);

    /// Get all ppl that are allowed to submit jclic
    list($esql, $params) = get_enrolled_sql($context, 'mod/jclic:submit', $currentgroup); 
    $sql = "SELECT u.id FROM {user} u ".
           "LEFT JOIN ($esql) eu ON eu.id=u.id ".
           "WHERE u.deleted = 0 AND eu.id=u.id ";

    $users = $DB->get_records_sql($sql, $params);
    if (!empty($users)) {
        $users = array_keys($users);
    }

    // if groupmembersonly used, remove users who are not in any group
    if ($users and !empty($CFG->enablegroupmembersonly) and $cm->groupmembersonly) {
        if ($groupingusers = groups_get_grouping_members($cm->groupingid, 'u.id', 'u.id')) {
            $users = array_intersect($users, array_keys($groupingusers));
        }
    }
  
    // Create table
    $extrafields = get_extra_user_fields($context);
    $tablecolumns = array_merge(array('picture', 'fullname'), $extrafields,
            array('grade', 'totaltime'));

    $extrafieldnames = array();
    foreach ($extrafields as $field) {
        $extrafieldnames[] = get_user_field_name($field);
    }

    $tableheaders = array_merge(
            array('', get_string('fullnameuser')),
            $extrafieldnames,
            array(
                get_string('grade'),
                get_string('totaltime', 'jclic'),
            ));

    require_once($CFG->libdir.'/tablelib.php');
    $table = new flexible_table('mod-jclic-results');

    $table->define_columns($tablecolumns);
    $table->define_headers($tableheaders);
    $table->define_baseurl($CFG->wwwroot.'/mod/jclic/view.php?id='.$cm->id.'&amp;currentgroup='.$currentgroup);

    $table->sortable(true, 'lastname'); //sorted by lastname by default
    $table->collapsible(true);
    $table->initialbars(true);

    $table->column_suppress('picture');
    $table->column_suppress('fullname');

    $table->column_class('picture', 'picture');
    $table->column_class('fullname', 'fullname');
    foreach ($extrafields as $field) {
        $table->column_class($field, $field);
    }
    $table->column_class('grade', 'grade');
    $table->column_class('totaltime', 'totaltime');

    $table->set_attribute('cellspacing', '0');
    $table->set_attribute('id', 'attempts');
    $table->set_attribute('class', 'results');
    $table->set_attribute('width', '100%');

    $table->no_sorting('totaltime'); //@TODO: Delete (only used for testing)

    // Start working -- this is necessary as soon as the niceties are over
    $table->setup();

    /// Construct the SQL
    list($where, $params) = $table->get_sql_where();
    if ($where) {
        $where .= ' AND ';
    }

    if ($sort = $table->get_sql_sort()) {
        $sort = ' ORDER BY '.$sort;
    }

    $ufields = user_picture::fields('u', $extrafields);
    if (!empty($users)) {
        $select = "SELECT $ufields,
                          j.id AS sessionid ";

        $sql = 'FROM {user} u '.
               'LEFT JOIN {jclic_sessions} j ON u.id = j.user_id
                AND j.jclicid = '.$jclic->id.' '.
               'WHERE '.$where.'u.id IN ('.implode(',',$users).') ';

        $ausers = $DB->get_records_sql($select.$sql.$sort, $params, $table->get_page_start(), $table->get_page_size());

        $table->pagesize($perpage, count($users));
        $offset = $page * $perpage; //offset used to calculate index of student in that particular query, needed for the pop up to know who's next

        if ($ausers !== false) {
            $grading_info = grade_get_grades($course->id, 'mod', 'jclic', $jclic->id, array_keys($ausers));
            $endposition = $offset + $perpage;
            $currentposition = 0;
            
            foreach ($ausers as $auser) {
                if ($currentposition == $offset && $offset < $endposition) {
                    $rowclass = null;
                    $picture = $OUTPUT->user_picture($auser);

/*                    
                    if (!empty($auser->submissionid)) {
                        $hassubmission = true;
                    ///Prints student answer and student modified date
                    ///attach file or print link to student answer, depending on the type of the assignment.
                    ///Refer to print_student_answer in inherited classes.
                        if ($auser->timemodified > 0) {
                            $studentmodifiedcontent = $this->print_student_answer($auser->id)
                                    . userdate($auser->timemodified);
                            if ($assignment->timedue && $auser->timemodified > $assignment->timedue) {
                                $studentmodifiedcontent .= assignment_display_lateness($auser->timemodified, $assignment->timedue);
                                $rowclass = 'late';
                            }
                        } else {
                            $studentmodifiedcontent = '&nbsp;';
                        }
                        $studentmodified = html_writer::tag('div', $studentmodifiedcontent, array('id' => 'ts' . $auser->id));
                    ///Print grade, dropdown or text
                        if ($auser->timemarked > 0) {
                            $teachermodified = '<div id="tt'.$auser->id.'">'.userdate($auser->timemarked).'</div>';

                            if ($final_grade->locked or $final_grade->overridden) {
                                $grade = '<div id="g'.$auser->id.'" class="'. $locked_overridden .'">'.$final_grade->formatted_grade.'</div>';
                            } else if ($quickgrade) {
                                $attributes = array();
                                $attributes['tabindex'] = $tabindex++;
                                $menu = html_writer::select(make_grades_menu($this->assignment->grade), 'menu['.$auser->id.']', $auser->grade, array(-1=>get_string('nograde')), $attributes);
                                $grade = '<div id="g'.$auser->id.'">'. $menu .'</div>';
                            } else {
                                $grade = '<div id="g'.$auser->id.'">'.$this->display_grade($auser->grade).'</div>';
                            }

                        } else {
                            $teachermodified = '<div id="tt'.$auser->id.'">&nbsp;</div>';
                            if ($final_grade->locked or $final_grade->overridden) {
                                $grade = '<div id="g'.$auser->id.'" class="'. $locked_overridden .'">'.$final_grade->formatted_grade.'</div>';
                            } else if ($quickgrade) {
                                $attributes = array();
                                $attributes['tabindex'] = $tabindex++;
                                $menu = html_writer::select(make_grades_menu($this->assignment->grade), 'menu['.$auser->id.']', $auser->grade, array(-1=>get_string('nograde')), $attributes);
                                $grade = '<div id="g'.$auser->id.'">'.$menu.'</div>';
                            } else {
                                $grade = '<div id="g'.$auser->id.'">'.$this->display_grade($auser->grade).'</div>';
                            }
                        }
                    ///Print Comment
                        if ($final_grade->locked or $final_grade->overridden) {
                            $comment = '<div id="com'.$auser->id.'">'.shorten_text(strip_tags($final_grade->str_feedback),15).'</div>';

                        } else if ($quickgrade) {
                            $comment = '<div id="com'.$auser->id.'">'
                                     . '<textarea tabindex="'.$tabindex++.'" name="submissioncomment['.$auser->id.']" id="submissioncomment'
                                     . $auser->id.'" rows="2" cols="20">'.($auser->submissioncomment).'</textarea></div>';
                        } else {
                            $comment = '<div id="com'.$auser->id.'">'.shorten_text(strip_tags($auser->submissioncomment),15).'</div>';
                        }
                    } else {
                        $studentmodified = '<div id="ts'.$auser->id.'">&nbsp;</div>';
                        $teachermodified = '<div id="tt'.$auser->id.'">&nbsp;</div>';
                        $status          = '<div id="st'.$auser->id.'">&nbsp;</div>';

                        if ($final_grade->locked or $final_grade->overridden) {
                            $grade = '<div id="g'.$auser->id.'">'.$final_grade->formatted_grade . '</div>';
                            $hassubmission = true;
                        } else if ($quickgrade) {   // allow editing
                            $attributes = array();
                            $attributes['tabindex'] = $tabindex++;
                            $menu = html_writer::select(make_grades_menu($this->assignment->grade), 'menu['.$auser->id.']', $auser->grade, array(-1=>get_string('nograde')), $attributes);
                            $grade = '<div id="g'.$auser->id.'">'.$menu.'</div>';
                            $hassubmission = true;
                        } else {
                            $grade = '<div id="g'.$auser->id.'">-</div>';
                        }

                        if ($final_grade->locked or $final_grade->overridden) {
                            $comment = '<div id="com'.$auser->id.'">'.$final_grade->str_feedback.'</div>';
                        } else if ($quickgrade) {
                            $comment = '<div id="com'.$auser->id.'">'
                                     . '<textarea tabindex="'.$tabindex++.'" name="submissioncomment['.$auser->id.']" id="submissioncomment'
                                     . $auser->id.'" rows="2" cols="20">'.($auser->submissioncomment).'</textarea></div>';
                        } else {
                            $comment = '<div id="com'.$auser->id.'">&nbsp;</div>';
                        }
                    } 
 */
                    $grade = '5'; //@TODO: Replace for the correct value (temporaly value for testing)
                    $totaltime = '20s'; //@TODO: Replace for the correct value  (temporaly value for testing)
                    $userlink = '<a href="' . $CFG->wwwroot . '/user/view.php?id=' . $auser->id . '&amp;course=' . $course->id . '">' . fullname($auser, has_capability('moodle/site:viewfullnames', $context)) . '</a>';
                    $extradata = array();
                    foreach ($extrafields as $field) {
                        $extradata[] = $auser->{$field};
                    }
                    $row = array_merge(array($picture, $userlink), $extradata,
                            array($grade, $totaltime));
                    $table->add_data($row, $rowclass);
                }
                $currentposition++;
                $offset++;
            }
            $table->print_html();  /// Print the whole table    
        }
    }
} else{
    jclic_view_applet($jclic, $context);    
}

jclic_view_footer();

