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

namespace mod_questionnaire\testing;

defined('MOODLE_INTERNAL') || die();

use stdClass;
use coding_exception;

/**
 * Class \mod_questionnaire\testing\generator, required for Totara
 *
 * @package   mod_questionnaire
 * @author    Sasha Anastasi <sasha.anastasi@catalyst.net.nz>
 * @copyright Catalyst IT
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

final class generator extends \core\testing\mod_generator {

    use tests_generator;

    /**
     * @var int keep track of how many questions have been created.
     */
    protected $questioncount = 0;

    /**
     * @var int
     */
    protected $responsecount = 0;

    /**
     * @var questionnaire[]
     */
    protected $questionnaires = [];

    /**
     * Number of entities created
     * @var int
     */
    protected $entitiescount = 0;

    /**
     * To be called from data reset code only,
     * do not use in tests.
     * @return void
     */

    public function reset() {
        $this->questioncount = 0;

        $this->responsecount = 0;

        $this->questionnaires = [];

        $this->entitiescount = 0;

        parent::reset();
    }

    /**
     * Accessor for questionnaires.
     *
     * @return array
     */
    public function questionnaires() {
        return $this->questionnaires;
    }

    /**
     * Create a questionnaire activity.
     * @param array $record Will be changed in this function.
     * @param array $options
     * @return questionnaire
     */
    public function create_instance($record = null, array $options = null) {
        $record = (object)(array)$record;

        $defaultquestionnairesettings = array(
            'qtype'                 => 0,
            'respondenttype'        => 'fullname',
            'resp_eligible'         => 'all',
            'resp_view'             => 0,
            'useopendate'           => true, // Used in form only to indicate opendate can be used.
            'opendate'              => 0,
            'useclosedate'          => true, // Used in form only to indicate closedate can be used.
            'closedate'             => 0,
            'resume'                => 0,
            'navigate'              => 0,
            'grade'                 => 0,
            'sid'                   => 0,
            'timemodified'          => time(),
            'completionsubmit'      => 0,
            'autonum'               => 3,
            'create'                => 'new-0', // Used in form only to indicate a new, empty instance.
        );

        foreach ($defaultquestionnairesettings as $name => $value) {
            if (!isset($record->{$name})) {
                $record->{$name} = $value;
            }
        }

        $instance = parent::create_instance($record, (array)$options);
        $cm = get_coursemodule_from_instance('questionnaire', $instance->id);
        $course = get_course($cm->course);
        $questionnaire = new \mod_questionnaire\questionnaire(0, $instance, $course, $cm, false);
        $this->questionnaires[$instance->id] = $questionnaire;

        return $questionnaire;
    }
}
