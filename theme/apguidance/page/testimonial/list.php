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
 * @package   theme_apguidance
 * @copyright 2025 santoshmagar.com.np
 * @author    santoshtmp7
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use theme_apguidance\handler\testimonial_handler;
use theme_apguidance\handler\settings_handler;

// Require config.
require_once(dirname(__FILE__) . '/../../../../config.php');
defined('MOODLE_INTERNAL') || die();

// Get parameters.

// Get system context.
$context = \context_system::instance();

// Prepare the page information.
$url = new moodle_url('/theme/apguidance/page/testimonial/list.php');
$page_title = 'Testimonials';
$PAGE->set_context($context);
$PAGE->set_url($url);
$PAGE->set_pagelayout('admin'); // admin , standard , ...
$PAGE->set_pagetype('admin-testimonial-list');
$PAGE->set_title($page_title);
$PAGE->set_heading($page_title);
// $PAGE->navbar->add($page_title);
// $PAGE->requires->js_call_amd();
// $PAGE->requires->css(new moodle_url());

// Access checks.
// admin_externalpage_setup();
require_login(null, false);

/**
 * ========================================================
 *     Get the data and display
 * ========================================================
 */
$contents = "";
// $template_content = [
//     'admin_testimonial_url' => $url->out(),
//     'add_testimonial_url' => (new moodle_url('/theme/apguidance/page/testimonial/edit.php'))->out(),
//     'testimonial_data_table' => testimonial_handler::get_data_in_table($url, -1),
//     'apguidance_testimonial' => settings_handler::setting('apguidance_testimonial'),
//     'search_form' => testimonial_handler::get_search_form(),
// ];
// $contents .= $OUTPUT->render_from_template('theme_apguidance/pages/testimonial/admin', $template_content);
$contents .= '
<div class="admin-testimonial">
    <div class="header-container d-flex" style="justify-content: space-between;">
        <div class="title">
            <h3>
            ' . get_string('testimonial_list', 'theme_apguidance') . '
            </h3>
        </div>
        <div class="btn-wrapper">
            <a class="btn btn-primary" href="/theme/apguidance/page/testimonial/edit.php">
            ' . get_string('add_testimonial', 'theme_apguidance') . '
            </a>
        </div>
    </div>
    <div class="testimonial-content">
        ' . testimonial_handler::get_data_in_table($url, -1) . '
    </div>
</div>
';

/**
 * ========================================================
 * -------------------  Output Content  -------------------
 * ========================================================
 */
echo $OUTPUT->header();
echo $contents;
echo $OUTPUT->footer();
