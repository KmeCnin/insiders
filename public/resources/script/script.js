$(document).ready(function() {
  /*** Popover init ***/
  $('[data-toggle="popover"]').popover();

  /*** Nav toggle in sections ***/
  $('.block-nav > li').click(function() {
    // From clicked element, get its <section>
    var section = $(this).closest('section');
    // Unactivate all bullets
    $(section).find('.block-nav > li').removeClass('active');
    // Active the selected bullet
    $(this).addClass('active');
    // Select each content and hide it
    $(section).find('.block-content > div').hide();
    // Get the value of the attribute data-target of the clicked element
    var target = $(this).attr('data-target');
    // Use this value in order to select the related content and show it
    $('#'+target).show();
  });
});
