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
 * Resume User file for local_inactive_users.
 *
 * @package    local_inactive_users
 * @copyright  2024 Brain Station 23 Ltd.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

global $DB, $USER;

require_once(__DIR__ . '/../../config.php');
require_login();

$userid = required_param('userid', PARAM_INT);

if (!is_siteadmin($USER)) {
    throw new moodle_exception('Unauthorized access');
}

$DB->set_field('user', 'suspended', 0, ['id' => $userid]);
redirect(new moodle_url('/local/inactive_users/suspend_users.php'), get_string('userresumed', 'local_inactive_users'));
