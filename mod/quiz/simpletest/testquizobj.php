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
 * Unit tests for the quiz class.
 *
 * @package    mod
 * @subpackage quiz
 * @copyright  2008 The Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/mod/quiz/locallib.php');


/**
 * Unit tests for the quiz class
 *
 * @copyright  2008 The Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class quiz_class_test extends UnitTestCase {
    public function test_cannot_review_message() {
        $quiz = new stdClass();
        $quiz->reviewattempt = 0x10010;
        $quiz->timeclose = 0;
        $quiz->attempts = 0;
        $quiz->questions = '1,2,0,3,4,0';

        $cm = new stdClass();
        $cm->id = 123;

        $quizobj = new quiz($quiz, $cm, new stdClass(), false);

        $this->assertEqual('',
                $quizobj->cannot_review_message(mod_quiz_display_options::DURING));
        $this->assertEqual('',
                $quizobj->cannot_review_message(mod_quiz_display_options::IMMEDIATELY_AFTER));
        $this->assertEqual(get_string('noreview', 'quiz'),
                $quizobj->cannot_review_message(mod_quiz_display_options::LATER_WHILE_OPEN));
        $this->assertEqual(get_string('noreview', 'quiz'),
                $quizobj->cannot_review_message(mod_quiz_display_options::AFTER_CLOSE));

        $closetime = time() + 10000;
        $quiz->timeclose = $closetime;
        $quizobj = new quiz($quiz, $cm, new stdClass(), false);

        $this->assertEqual(get_string('noreviewuntil', 'quiz', userdate($closetime)),
                $quizobj->cannot_review_message(mod_quiz_display_options::LATER_WHILE_OPEN));
    }

    public function test_empty_quiz() {
        $quiz = new stdClass();
        $quiz->reviewattempt = 0x10010;
        $quiz->timeclose = 0;
        $quiz->attempts = 0;
        $quiz->questions = '0';

        $cm = new stdClass();
        $cm->id = 123;

        $quizobj = new quiz($quiz, $cm, new stdClass(), false);

        $this->assertFalse($quizobj->has_questions());
    }
}
