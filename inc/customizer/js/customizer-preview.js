/**
 * Theme Customizer
 *
 * Theme Customizer enhancements for a better user experience. Contains
 * handlers to make Theme Customizer preview reload changes asynchronously.
 *
 * @package   wordy
 * @copyright Copyright (c) 2016, Nose Graze Ltd.
 * @license   GPL2+
 * @since     1.0
 */

(function ($) {

    // Site title and description.
    wp.customize('blogname', function (value) {
        value.bind(function (to) {
            $('.site-title a').text(to);
        });
    });
    wp.customize('blogdescription', function (value) {
        value.bind(function (to) {
            $('.site-description').text(to);
        });
    });

    // Header text color.
    wp.customize('header_textcolor', function (value) {
        value.bind(function (to) {
            if ('blank' === to) {
                $('.site-title a, .site-description').css({
                    'clip': 'rect(1px, 1px, 1px, 1px)',
                    'position': 'absolute'
                });
            } else {
                $('.site-title a, .site-description').css({
                    'clip': 'auto',
                    'position': 'relative'
                });
                $('.site-title a, .site-description').css({
                    'color': to
                });
            }
        });
    });

    // Primary colour.
    wp.customize('primary_colour', function (value) {
        value.bind(function (to) {
            $('#header, #footer, .button, button, input[type="submit"], .pagination .current').css({
                'background-color': to
            });
        });
    });

    // CTA box #1
    wp.customize('cta_text_1', function (value) {
        value.bind(function (to) {
            var box = $('#cta-box-1');

            box.find('a').text(to);

            if (to) {
                box.show();
            } else {
                box.hide();
            }
        });
    });
    wp.customize('cta_url_1', function (value) {
        value.bind(function (to) {
            $('#cta-box-1 a').attr('href', to);
        });
    });
    wp.customize('cta_colour_1', function (value) {
        value.bind(function (to) {
            $('#cta-box-1').css({
                'background-color': to
            })
        });
    });
    wp.customize('cta_image_1', function (value) {
        value.bind(function (to) {
            $('#cta-box-1').css({
                'background-image': 'url(' + to + ')'
            })
        });
    });

    // CTA box #2
    wp.customize('cta_text_2', function (value) {
        value.bind(function (to) {
            var box = $('#cta-box-2');

            box.find('a').text(to);

            if (to) {
                box.show();
            } else {
                box.hide();
            }
        });
    });
    wp.customize('cta_url_2', function (value) {
        value.bind(function (to) {
            $('#cta-box-2 a').attr('href', to);
        });
    });
    wp.customize('cta_colour_2', function (value) {
        value.bind(function (to) {
            $('#cta-box-2').css({
                'background-color': to
            })
        });
    });
    wp.customize('cta_image_2', function (value) {
        value.bind(function (to) {
            $('#cta-box-2').css({
                'background-image': 'url(' + to + ')'
            })
        });
    });

    // CTA box #3
    wp.customize('cta_text_3', function (value) {
        value.bind(function (to) {
            var box = $('#cta-box-3');

            box.find('a').text(to);

            if (to) {
                box.show();
            } else {
                box.hide();
            }
        });
    });
    wp.customize('cta_url_3', function (value) {
        value.bind(function (to) {
            $('#cta-box-3 a').attr('href', to);
        });
    });
    wp.customize('cta_colour_3', function (value) {
        value.bind(function (to) {
            $('#cta-box-3').css({
                'background-color': to
            })
        });
    });
    wp.customize('cta_image_3', function (value) {
        value.bind(function (to) {
            $('#cta-box-3').css({
                'background-image': 'url(' + to + ')'
            })
        });
    });

    // Affiliate ID
    wp.customize('affiliate_id', function (value) {
        value.bind(function (to) {
            if (to) {
                $('#wordy-credit-link').attr('href', 'https://novelistplugin.com/download/wordy-theme/?ref=' + to);
            } else {
                $('#wordy-credit-link').attr('href', 'https://novelistplugin.com/download/wordy-theme/');
            }
        });
    });

    // Text Before Credits
    wp.customize('before_credits', function (value) {
        value.bind(function (to) {
            $('#wordy-text-before-copyright').empty().append(nl2br(to));
        });
    });

    // Copyright
    wp.customize('copyright_message', function (value) {
        value.bind(function (to) {
            $('#wordy-copyright').empty().append(to);
        });
    });

    /**
     * Convert "Enter" Key to <br> tags
     *
     * @param str
     * @param is_xhtml
     *
     * @returns {string}
     */
    function nl2br(str, is_xhtml) {
        var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
        return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
    }

})(jQuery);