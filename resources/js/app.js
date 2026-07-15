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

    // Close footer/options panels when clicking outside — never touch the main navbar
    $(document).on('click', function(e) {
      var $target = $(e.target);

      if ($target.closest('[data-toggle="collapse"]').length) {
        return;
      }

      // Defer so Bootstrap 3 finishes its toggle on the same click
      setTimeout(function() {
        if ($target.closest('[data-toggle="collapse"]').length) {
          return;
        }

        if ($target.closest('.collapse.in').length) {
          return;
        }

        $('#notes, #market_day_options, #product_options, #additionalNav')
          .filter('.in')
          .collapse('hide');
      }, 0);
    });
  });
}
