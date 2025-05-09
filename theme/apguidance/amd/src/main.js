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
 * AMD module
 *
 * @copyright 2025 santoshmagar.com.np
 * @author    santoshtmp7
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */

import $ from 'jquery';


export const main = () => {
    window.console.log('test apguidance');
};


export const theme_apguidance_setting_toggle = () => {
    if ($('body').hasClass('pagelayout-admin')) {
        let url = new URL(window.location.href);
        let params = new URLSearchParams(url.search);
        var query_title_id = params.get('title');
        $('#page-admin-setting-themesettingapguidance #adminsettings .tab-content .tab-pane').each(function () {
            var h3_title = $(this).find('h3.main');
            if (h3_title) {
                let query_title_id_true = false;
                h3_title.addClass('theme-apguidance-setting-toggle');
                //
                h3_title.on('click', function () {
                    $(this).toggleClass("open");
                    $(this).next().toggleClass("open");
                });
                h3_title.each(function () {
                    $(this).nextUntil('h3.main').wrapAll('<div class="admin-theme-apguidance-panel-content">');
                    let h3_title_id = 'theme-apguidance-' + ($(this).text().toLowerCase()).replace(" ", "-");
                    $(this).attr('id', h3_title_id);
                    if (query_title_id == h3_title_id) {
                        query_title_id_true = true;
                    }
                });
                // Add 'open' class to the first h3.main in each tab-pane
                if (!query_title_id_true) {
                    h3_title.first().addClass('open');
                    h3_title.first().next().addClass('open');
                } else {
                    $('#' + query_title_id).addClass('open');
                    $('#' + query_title_id).next().addClass('open');

                    // Change url to default moodle url
                    let currentUrl = window.location.href;
                    params.delete('title');
                    window.console.log(currentUrl);
                    currentUrl = currentUrl.replace('&title=' + query_title_id, '');
                    window.history.replaceState('', 'url', currentUrl);
                    //
                    setTimeout(function () {
                        // window.console.log(document.getElementById(query_title_id));
                        // document.getElementById(query_title_id).scrollIntoView({ behavior: "smooth" });
                        const offset = -100;
                        const element = document.getElementById(query_title_id);
                        if (element) {
                            const elementPosition = element.getBoundingClientRect().top + window.scrollY;
                            window.scrollTo({ top: elementPosition + offset, behavior: "smooth" });
                        }

                    }, 1000);
                }
            }
        });
        // Edit input fields with id to number field
        let number_fields_ids = [
            'id_s_theme_apguidance_banner_cta_count'
        ];
        $.each(number_fields_ids, function (index, id) {
            $('input#' + id).attr('type', 'number');
            $('input#' + id).attr('min', 0);
        });
    }
};