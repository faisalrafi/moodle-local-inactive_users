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
 * View file for local_inactive_users.
 *
 * @package    local_inactive_users
 * @copyright  2024 Brain Station 23 Ltd.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../config.php');

global $USER, $PAGE;

if(!is_siteadmin($USER)) {
    return redirect(new moodle_url('/'), 'Unauthorized', null, \core\output\notification::NOTIFY_ERROR);
}

$PAGE->set_url('/local/inactive_users/view.php');
$PAGE->set_context(\context_system::instance());
$PAGE->set_title(get_string('pagetitle', 'local_inactive_users'));
$PAGE->set_heading(get_string('pagetitle', 'local_inactive_users'));

var_dump('hello'); die();
