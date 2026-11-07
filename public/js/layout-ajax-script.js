$(document).ready(function() {
    'use strict';

    if (localStorage.getItem('sidebar_collapsed') === 'true' && $(window).width() >= 992) {
        $('body').addClass('sidebar-collapsed');
        $('#menuHamburguesaToggle').find('i').css('color', 'var(--dc-cyan)');
    }

    $('#menuHamburguesaToggle').on('click', function(e) {
        e.preventDefault();

        if ($(window).width() < 992) {
            $('body').toggleClass('sidebar-open');
            $('body').removeClass('sidebar-collapsed');
        }
        else {
            $('body').toggleClass('sidebar-collapsed');

            const isCollapsed = $('body').hasClass('sidebar-collapsed');
            localStorage.setItem('sidebar_collapsed', isCollapsed);

            if (isCollapsed) {
                $(this).find('i').css('color', 'var(--dc-cyan)');
            } else {
                $(this).find('i').css('color', '');
            }
        }
    });

    $(document).on('click', function(e) {
        if ($(window).width() < 992) {
            if (!$(e.target).closest('#appSidebar, #menuHamburguesaToggle').length) {
                $('body').removeClass('sidebar-open');
            }
        }
    });
});
