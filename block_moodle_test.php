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
 * Version info
 *
 * @package    block_moodle_test
 * @copyright  2023
 * @author     alkaries91@gmail.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Moodle Test Block
 * Displays course modules information
 */
class block_moodle_test extends block_list {

    public function init() {
        $this->title = get_string('pluginname', 'block_moodle_test');
    }

    public function applicable_formats() {
        return array('course-view' => true);
    }

    public function get_content() {
        global $CFG, $DB, $USER;

        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass;
        $course = $this->page->course;
        $modinfo = get_fast_modinfo($course);

        foreach ($modinfo->cms as $cm) {
            if (!$cm->uservisible or !$cm->has_view()) {
                continue;
            }
            $queryparams = array('coursemoduleid' => $cm->id, 'userid' => $USER->id);
            if ($compdata = $DB->get_record('course_modules_completion', $queryparams)) {
                if ($compdata->completionstate) {
                    $comletion = ' - Completed';
                }
            }
            $date = date('d-M-Y', $cm->added);
            $anchor = "<a href='$CFG->wwwroot/mod/$cm->modname/view.php?id=$cm->id'>$cm->id - $cm->name - $date $comletion</a>";
            $this->content->items[] = $anchor;
        }
        return $this->content;
    }
}
