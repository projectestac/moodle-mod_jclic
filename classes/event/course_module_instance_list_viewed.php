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
 * The mod_jclic instance list viewed event.
 *
 * @package    mod_jclic
 * @copyright  2014 Pau Ferrer <crazyserver@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_jclic\event;

defined('MOODLE_INTERNAL') || die();

/**
 * The mod_jclic instance list viewed event class.
 *
 * @package    mod_jclic
 * @since      Moodle 2.7
 * @copyright  2014 Pau Ferrer <crazyserver@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class course_module_instance_list_viewed extends \core\event\course_module_instance_list_viewed {

    /**
     * Create the event from course record.
     *
     * @param \stdClass $course
     * @throws \coding_exception
     * @return \core\event\base
     */
    public static function create_from_course(\stdClass $course): \core\event\base {

        $params = [
            'context' => \context_course::instance($course->id),
        ];
        $event = self::create($params);
        $event->add_record_snapshot('course', $course);

        return $event;

    }

}
