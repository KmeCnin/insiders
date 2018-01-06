$(document).ready(function () {
    /*** Popover init ***/
    $('.toggle-popover').popover();

    var loadedModals = [];

    /*** Nav toggle in sections ***/
    $('.vertical-breadcrumb > li').click(function () {
        // From clicked element, get its <section>
        var section = $(this).closest('section');
        // Unactivate all bullets
        $(section).find('.vertical-breadcrumb > li').removeClass('active');
        // Active the selected bullet
        $(this).addClass('active');
        // Select each content and hide it
        $(section).find('.block-content > div').hide();
        // Get the value of the attribute data-target of the clicked element
        var target = $(this).attr('data-target');
        // Use this value in order to select the related content and show it
        $('#' + target).show();
    });

    /*** Sticky Breadcrumbs (https://github.com/garand/sticky) ***/
    $('.breadcrumb').sticky({
        topSpacing: 66,
        bottomSpacing: 50
    });

    /*** Add modals at the end of the page (if not already existing) ***/
    $('.toggle-popup').each(function () {
        var modalId = $(this).attr('data-target').split('#')[1];
        var pieces = modalId.split('___');

        if (-1 !== loadedModals.indexOf(modalId)) {
            return;
        }

        $.get(
            Routing.generate(
                'popup',
                {
                    'modalId': modalId,
                    'code': pieces[1],
                    'id': pieces[2]
                },
                true
            ),
            function (data) {
                $('body').append(data);
                loadedModals.push(modalId);
                /*** Popover init ***/
                $('#'+modalId+' .toggle-popover').popover({
                    container: '#'+modalId
                });
            }
        );
    });
});
