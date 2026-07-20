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

    // Close footer/options panels on outside click — never touch the main navbar
    var footerPanelSelector = '#notes, #market_day_options, #product_options, #additionalNav';

    $(footerPanelSelector).on('shown.bs.collapse', function() {
      var $panel = $(this);

      setTimeout(function() {
        $(document).one('click.footerPanelClose', function(e) {
          var $target = $(e.target);

          if ($target.closest('[data-toggle="collapse"][href="#' + $panel.attr('id') + '"]').length) {
            return;
          }

          if ($target.closest('#' + $panel.attr('id')).length) {
            return;
          }

          $panel.collapse('hide');
        });
      }, 0);
    });

    $(footerPanelSelector).on('hidden.bs.collapse', function() {
      $(document).off('click.footerPanelClose');
    });
  });
}
