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
 * English strings for jclic
 *
 * You can have a rather longer description of the file as well,
 * if you like, and it can span multiple lines.
 *
 * @package    mod
 * @copyright  2011 Departament d'Ensenyament de la Generalitat de Catalunya
 * @author     Sara Arjona Téllez
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['activitydone'] = 'Activities done';
$string['activitysolved'] = 'Activities completed successfully';
$string['attempts'] = 'Attempts';
$string['avaluation'] = 'Evaluation criteria';
$string['avaluation_score'] = 'Achieving this global score';
$string['avaluation_solved'] = 'Solving this number of different activities';
$string['contentheader'] = 'Content';
$string['description'] = 'Description';
$string['height'] = 'Height';
$string['lastaccess'] = 'Last visited';
$string['maxattempts'] = 'Maximum number of trials';
$string['maxgrade'] = 'Score/activities that must be achieved';
$string['modulename'] = 'JClic';
$string['modulenameplural'] = 'JClic';
$string['msg_noattempts']= 'You have tried this activity the maximum number of times';
$string['score'] = 'Score';
$string['sessions'] = 'Sessions';
$string['size'] = 'Size';
$string['starttime']= 'Date started';
$string['skin'] = 'Skin';
$string['totals']= 'Totals';
$string['totaltime']= 'Total time';
$string['unlimited'] = 'Unlimited';
$string['url'] = 'URL';
$string['width'] = 'Width';

/* Revision 20070305 */
$string['actions'] = 'Actions';
$string['activity'] = 'Activity';
$string['msg_nosessions'] = 'This JClic activity hasn\'t yet any session';
$string['solved'] = 'Correct';
$string['time'] = 'Time';

/* Revision 20091023 */
$string['deleteallsessions'] = 'Delete all sessions';

/* Revision 20110119  - version 0.1.0.11 */
$string['lang'] = 'Language';
$string['exiturl'] = 'Exit URL';


/* Revision Moodle 2.X */
$string['availabledate'] = 'Available from';
$string['closebeforeopen'] = 'Could not update the jclic. You have specified a due date before the available date.';
$string['duedate'] = 'Due date';
$string['exiturl_help'] = 'This is the URL that appears when students finish the last JClic activity.

To make work this redirection it\'s necessary to associate to the last activity in the Sequences tab the action "Exit JClic" in the Forward arrow section.';
$string['expired'] = 'Sorry, this activity closed on {$a} and is no longer available';
$string['filetype'] = 'Type';
$string['filetype_help'] = 'This setting determines how the JClic activity is included in the course. There are up to 2 options:

* Uploaded JClic - Enables a valid JClic package to be chosen by the file picker.
* External URL - Enables a URL to be specified. Note: The URL must start with http(s) or www and contain a valid JClic file.';
$string['filetypeexternal'] = 'External URL';
$string['filetypelocal'] = 'Uploaded JClic';
$string['invalidjclicfile'] = 'Invalid JClic specified. It must have the ".jclic" extension.';
$string['invalidurl'] = 'Invalid URL specified. It must start with http(s) and has to be a valid JClic file.';
$string['jclic'] = 'JClic';
$string['jclicjarbase'] = 'Jar base';
$string['jclicjarbase_help'] = 'Web address where to locate all the JClic jar files';
$string['jclicurl'] = 'URL';
$string['jclicurl_help'] = 'This setting enables a URL for the JClic package to be specified, rather than choosing a file via the file picker.';
$string['jclicfile'] = 'JClic file';
$string['jclicfile_help'] = 'The .jclic file containing the JClic files.';
$string['lap'] = 'Lap';
$string['lap_help'] = 'Time between client-server transactions (in seconds)';
$string['modulename_help'] = '<a href="https://clic.xtec.cat" target="_blank">JClic</a> is a project of the Catalan Ministry of Education.
    It consists of a set of open source software applications that allow the creation of several types of multimedia educational activities: puzzles, association games, text activities, crosswords, wordsearch games and more.
    Besides, the <a href="https://clic.xtec.cat/db/listact_ca.jsp" target="_blank">ClicZone</a> offers a repository where more than a thousand activities are displayed.
    It has been created by teachers and other professionals who want to share their work with others.

This module allows teachers to add JClick activities to any course and to track pupils\' results (time spent on each of the activities, number of tries, score...).';
$string['notopenyet'] = 'Sorry, this activity is not available until {$a}';
$string['pluginadministration'] = 'JClic administration';
$string['pluginname'] = 'JClic';
$string['preview_jclic'] = 'Preview JClic activity';
$string['return_results'] = 'Return to results';
$string['show_my_results'] = 'Show my results';
$string['solveddone'] = 'Activities solved / done';
$string['timing'] = 'Timing';
$string['urledit'] = 'JClic activity file';
$string['urledit_help'] = 'The "jclic.zip" file where you will find the JClic activity package.';

$string['jclic:view'] = 'View JClic';
$string['jclic:submit'] = 'Submit JClic';
$string['jclic:grade'] = 'Grade JClic';

/* Revision Moodle 2.3 */
$string['jclic:addinstance'] = 'Add JClic';
$string['pluginjs'] = 'JClic plugin js file url';
$string['pluginjs_help'] = 'URL where to find jclicplugin.js needed to work. If empty, it will use the file on the module.';


/* Revision Moodle 2.6 */
$string['event_course_module_viewed'] = 'Course module viewed';
$string['event_instances_list_viewed'] = 'Instances list viewed';
$string['results'] = 'Results';
$string['report_details'] = 'Detailed results with all the sessions';
$string['report_normal'] = 'Summary results';

/* Revision Moodle 3.1 */
$string['jclicjs'] = 'jclic.js file URL';
$string['jclicjs_help'] = 'URL where to find jclic.js needed for the non-Java player..';

/* Revision Moodle 4.5 */
$string['grade'] = 'Grade';
