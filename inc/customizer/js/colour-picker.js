jQuery(document).ready(function ($) {
    if (typeof $.wp !== 'undefined' && typeof $.wp.wpColorPicker !== 'undefined') {
        $.wp.wpColorPicker.prototype.options = {
            hide: true,
            palettes: [
                '#556270',
                '#4ecdc4',
                '#c7f464',
                '#ff6b6b',
                '#c44d58',
                '#ecca2e',
                '#bada55'
            ]
        };
    }
});