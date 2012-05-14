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
 * @author     Yannis Kaskamanidis (ttnfy17@yahoo.gr)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['activitydone'] = 'Οι δραστηριότητες ολοκληρώθηκαν';
$string['activitysolved'] = 'Οι δραστηριότητες ολοκληρώθηκαν με επιτυχία';
$string['attempts'] = 'Προσπάθειες';
$string['avaluation'] = 'Κριτήρια αξιολόγησης';
$string['avaluation_score'] = 'Επίτευξη συνολικής βαθμολογίας';
$string['avaluation_solved'] = 'Επίλυση αυτού του αριθμού διαφορετικών δραστηριοτήτων';
$string['description'] = 'Περιγραφή';
$string['height']='Ύψος';
$string['hideall']='Προβολή μόνο των περιλήψεων';
$string['lastaccess']='Τελευταία επίσκεψη';
$string['maxattempts'] = 'Μέγιστος αριθμός δοκιμών';
$string['maxgrade'] = 'Βαθμολογία/Δραστηριότητες που πρέπει να επιτευχθούν';
$string['modulename'] = 'JClic';
$string['modulenameplural'] = 'JClic';
$string['msg_noattempts']= 'Έχετε επιχειρήσει να ολοκληρώσετε αυτή τη δραστηριότητα με τον μέγιστο αριθμό προσπαθειών';
$string['score']='Βαθμολογία';
$string['sessions']='Συνεδρίες';
$string['showall']='Προβολή λεπτομερειών όλων των συνεδριών';
$string['size']='Μέγεθος';
$string['starttime']= 'Ημερομηνία έναρξης';
$string['skin'] = 'Θέμα';
$string['totals']= 'Σύνολα';
$string['totaltime']= 'Συνολικός χρόνος';
$string['unlimited'] = 'Απεριόριστο';
$string['url'] = 'URL';
$string['width']='Πλάτος';

/* Revision 20070305 */
$string['actions']='Ενέργειες';
$string['activity']='Δραστηριότητα';
$string['msg_nosessions']='Αυτή η δραστηριότητα του JClic δεν διαθέτει ακόμη καμία συνεδρία';
$string['solved']='Σωστό';
$string['time']='Χρόνος';

/* Revision 20071002 */
$string['header_jclic']='JClic Ρυθμίσεις';
$string['header_score']='Ρυθμίσεις Αξιολόγησης';

/* Revision 20091023 */
$string['deleteallsessions'] = 'Διαγραφή όλων των συνεδριών';

/* Revision 20110119  - version 0.1.0.11 */
$string['lang']='Γλώσσα';
$string['exiturl']='Έξοδος από το URL';


/* Revision Moodle 2.X */
$string['availabledate'] = 'Available from';
$string['closebeforeopen'] = 'Could not update the jclic. You have specified a due date before the available date.';
$string['duedate'] = 'Due date';
$string['exiturl_help'] = 'This is the URL that appears when students finish the last JClic activity.
    
To make work this redirection it\'s necessary to associate to the last activity in the Sequences tab the action "Exit JClic" in the Forward arrow section.';
$string['expired'] = 'Sorry, this activity closed on {$a} and is no longer available';
$string['filetype'] = 'Type';
$string['filetype_help'] = 'This setting determines how the JClic activity is included in the course. There are up to 2 options:

* Uploaded JClic - Enables a valid ".jclic.zip" package to be chosen by the file picker. 
* External URL - Enables a URL to be specified. Note: The URL must start with http(s) or www and contain a valid "jclic.zip" file.';
$string['filetypeexternal'] = 'External URL';
$string['filetypelocal'] = 'Uploaded JClic';
$string['invalidjclicfile'] = 'Invalid JClic specified. It must have the ".jclic.zip" extension.';
$string['invalidurl'] = 'Invalid URL specified. It must start with http(s) and has to be a valid ".jclic.zip" file.';
$string['jclic'] = 'JClic';
$string['jclicjarbase']='Jar base';
$string['jclicjarbase_help']='Web address where to locate all the JClic jar files';
$string['jclicurl'] = 'URL';
$string['jclicurl_help'] = 'This setting enables a URL for the JClic package to be specified, rather than choosing a file via the file picker.';
$string['jclicfile'] = 'JClic file';
$string['jclicfile_help'] = 'The .jclic.zip file containing the JClic files.';
$string['lap']='Lap';
$string['lap_help']='Χρόνος συναλλαγών μεταξύ client-server transactions (σε δευτερόλεπτα)';
$string['modulename_help'] = '<a href="http://clic.xtec.cat" target="_blank">JClic</a> is a project of the Catalan Ministry of Education. 
    It consists of a set of open source software applications that allow the creation of several types of multimedia educational activities: puzzles, association games, text activities, crosswords, wordsearch games and more. 
    Besides, the <a href="http://clic.xtec.cat/db/listact_ca.jsp" target="_blank">ClicZone</a> offers a repository where more than a thousand activities are displayed. 
    It has been created by teachers and other professionals who want to share their work with others.

This module allows teachers to add JClick activities to any course and to track pupils\' results (time spent on each of the activities, number of tries, score...).';
$string['notopenyet'] = 'Sorry, this activity is not available until {$a}';
$string['pluginadministration'] = 'JClic administration';
$string['pluginname'] = 'JClic';
$string['preview_jclic']='Εμφάνιση δραστηριότητας του JClic'; // Preview JClic activity
$string['return_results']='Return to results';
$string['show_my_results']='Προβολή αποτελεσμάτων';  // Show my results
$string['solveddone'] = 'Activities solved / done';
$string['urledit'] = 'JClic activity file';
$string['urledit_help'] = 'The "jclic.zip" file where you will find the JClic activity package.';
