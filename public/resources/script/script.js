$(document).ready(function () {
    /*** Popover init ***/
    $('.toggle-popover').popover();

    /*** Nav toggle in sections ***/
    $('.vertical-breadcrumb > li').click(function () {
        // From clicked element, get its <section>
        var section = $(this).closest('section');
        // Un-activate all bullets
        $(section).find('.vertical-breadcrumb > li').removeClass('active');
        // Active the selected bullet
        $(this).addClass('active');
        // Select each content and hide it
        $(section).find('.block-content > .description > div').hide();
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
});
