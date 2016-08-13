(function ($) {

    /**
     * Show/hide menu when clicking on button.
     */
    $('.menu-toggle').click(function (e) {
        e.preventDefault();

        // Menu is currently hidden, let's reveal it.
        if ($(this).attr('aria-expanded') == 'false') {

            $(this).attr('aria-expanded', 'true');
            $(this).parent().parent().addClass('toggled');


        } else {

            // Menu is open, let's close it.
            $(this).attr('aria-expanded', 'false');
            $(this).parent().parent().removeClass('toggled');

        }
    });

    /**
     * Show/hide sidebar when clicking on button.
     */
    $('.sidebar-toggle').click(function (e) {
        e.preventDefault();

        // Menu is currently hidden, let's reveal it.
        if ($(this).attr('aria-expanded') == 'false') {

            $(this).attr('aria-expanded', 'true');
            $(this).next().addClass('toggled');


        } else {

            // Menu is open, let's close it.
            $(this).attr('aria-expanded', 'false');
            $(this).next().removeClass('toggled');

        }
    });

    /**
     * Open/close purchase button links
     */
    $('.purchase-links-list-dropdown > .button').click(function (e) {
        e.preventDefault();

        if ($(this).attr('aria-expanded') == 'false') {
            $(this).attr('aria-expanded', 'true');
            $(this).next().addClass('purchase-links-list-open');
        } else {
            $(this).attr('aria-expanded', 'false');
            $(this).next().removeClass('purchase-links-list-open');
        }
    });

})(jQuery);