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
 * Code that deals with logging stuff during the question engine upgrade.
 *
 * @package    moodlecore
 * @subpackage questionengine
 * @copyright  2010 The Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->libdir . '/questionlib.php');


/**
 * This class serves to record all the assumptions that the code had to make
 * during the question engine database database upgrade, to facilitate reviewing
 * them.
 *
 * @copyright  2010 The Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class question_engine_assumption_logger {
    protected $handle;
    protected $attemptid;

    public function __construct() {
        global $CFG;
        make_upload_directory('upgradelogs');
        $this->handle = fopen($CFG->dataroot . '/upgradelogs/qe_' .
                date('Ymd-Hi') . '.html', 'a');
        fwrite($this->handle, '<html><head><title>Question engine upgrade assumptions ' .
                date('Ymd-Hi') . '</title></head><body><h2>Question engine upgrade assumptions ' .
                date('Ymd-Hi') . "</h2>\n\n");
    }

    public function set_current_attempt_id($id) {
        $this->attemptid = $id;
    }

    public function log_assumption($description, $quizattemptid = null) {
        global $CFG;
        $message = '<p>' . $description;
        if (!$quizattemptid) {
            $quizattemptid = $this->attemptid;
        }
        if ($quizattemptid) {
        $message .= ' (<a href="' . $CFG->wwwroot . '/mod/quiz/review.php?attempt=' .
                $quizattemptid . '">Review this attempt</a>)';
        }
        $message .= "</p>\n";
        fwrite($this->handle, $message);
    }

    public function __destruct() {
        fwrite($this->handle, '</body></html>');
        fclose($this->handle);
    }
}