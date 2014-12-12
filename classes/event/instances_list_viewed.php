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
 * mod_jclic instances list viewed event.
 *
 * @package    mod_jclic
 * @copyright  2014 Pau Ferrer <crazyserver@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_jclic\event;
defined('MOODLE_INTERNAL') || die();

/**
 * mod_jclic instances list viewed event class.
 */
class instances_list_viewed extends \core\event\course_module_instances_list_viewed {

    // TODO: Delete after 2.8 upgrade
    /**
     * Returns description of what happened.
     *
     * @return string
     */
    public function get_description() {
        return "User $this->userid viewed the list of jclic activities in the course $this->courseid.";
    }

    /**
     * Return the legacy event log data.
     *
     * @return array|null
     */
    protected function get_legacy_logdata() {
        return array($this->courseid, 'jclic', 'view all', 'index.php?id=' . $this->courseid, '');
    }

    /**
     * Return localised event name.
     *
     * @return string
     */
    public static function get_name() {
        return get_string('event_instances_list_viewed', 'mod_jclic');
    }

    /**
     * Get URL related to the action
     *
     * @return \moodle_url
     */
    public function get_url() {
        return new \moodle_url('/mod/jclic/index.php', array('id' => $this->courseid));
    }

}
