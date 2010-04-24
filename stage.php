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
 * View staged strings and allow the user to commit them
 *
 * @package   local-amos
 * @copyright 2010 David Mudrak <david.mudrak@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/locallib.php');
require_once(dirname(__FILE__).'/mlanglib.php');

$message = optional_param('message', null, PARAM_RAW);

require_login(SITEID, false);
require_capability('moodle/site:config', $PAGE->context);

$PAGE->set_pagelayout('standard');
$PAGE->set_url('/local/amos/stage.php');
$PAGE->set_title('AMOS stage');
$PAGE->set_heading('AMOS stage');

if (isset($message)) {
    require_sesskey();
    $stage = mlang_persistent_stage::instance_for_user($USER);
    $stage->commit($message, array('source' => 'amos', 'userinfo' => fullname($USER) . '<' . $USER->email . '>'));
    $stage->store();
    redirect($PAGE->url);
    die();
}

$output = $PAGE->get_renderer('local_amos');

// create a renderable object that represents the stage
$stage = new local_amos_stage($USER);

/// Output starts here
echo $output->header();
$currenttab = 'stage';
include(dirname(__FILE__) . '/tabs.php');
echo $output->render($stage);
echo $output->footer();