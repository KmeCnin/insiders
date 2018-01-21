$(document).ready(function () {
    /*** Popover init ***/
    $('.toggle-popover').popover();

    /*** Nav toggle in sections ***/
    $('.vertical-breadcrumb > li').click(function () {
        // From clicked element, get its <section>
        let wrapper = $(this).closest('.increases-wrapper');
        // Un-activate all bullets
        $(wrapper).find('.vertical-breadcrumb > li').removeClass('active');
        // Active the selected bullet
        $(this).addClass('active');
        // Select each content and hide it
        $(wrapper).find('.increases-description > div').hide();
        // Get the value of the attribute data-target of the clicked element
        let target = $(this).attr('data-target');
        // Use this value in order to select the related content and show it
        $(target).show();
    });

    /*** Sticky Breadcrumbs (https://github.com/garand/sticky) ***/
    $('.breadcrumb').sticky({
        topSpacing: 66,
        bottomSpacing: 50
    });

    /*** Menu burger ***/
    $('.navbar-toggler').click(function() {
        $('.navbar-mobile').toggleClass('active');
    })
});
