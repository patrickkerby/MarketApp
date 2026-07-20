// Chrome scrolls/changes unfocused number inputs on wheel instead of the page
document.addEventListener('wheel', function(e) {
  if (e.target.type === 'number' && document.activeElement !== e.target) {
    e.preventDefault();
    window.scrollBy(0, e.deltaY);
  }
}, { passive: false });

// jQuery helpers — jQuery + Bootstrap 3 are loaded from CDN in the layout
if (typeof jQuery !== 'undefined') {
  jQuery(function($) {
    $('.print-window').click(function() {
      window.print();
    });

    // Footer panels use a simple class toggle — not Bootstrap collapse,
    // which shares the .collapse class with the main navbar and causes flashing.
    $('.notes_trigger, .options_trigger').on('click', function(e) {
      e.preventDefault();
      e.stopPropagation();

      var $panel = $($(this).attr('href'));
      var willOpen = !$panel.hasClass('is-open');

      $('.footer-panel.is-open').removeClass('is-open');

      if (willOpen) {
        $panel.addClass('is-open');
      }
    });

    $(document).on('click', function(e) {
      if ($(e.target).closest('.notes_trigger, .options_trigger, .footer-panel, footer').length) {
        return;
      }

      $('.footer-panel.is-open').removeClass('is-open');
    });
  });
}
