(function (window, document) {
  function initLyricsArchive() {
    var archive = document.querySelector('.lyrics-archive');
    if (!archive) return;

    var tiles = Array.prototype.slice.call(
      archive.querySelectorAll('.lyrics-release-tile[href^="#"]')
    );
    var accordions = Array.prototype.slice.call(
      archive.querySelectorAll('.lyrics-accordion')
    );

    if (!tiles.length || !accordions.length) return;

    function setActive(target) {
      tiles.forEach(function (tile) {
        var active = tile.getAttribute('href') === '#' + target.id;
        tile.classList.toggle('is-active', active);
        if (active) {
          tile.setAttribute('aria-current', 'true');
        } else {
          tile.removeAttribute('aria-current');
        }
      });
    }

    function openTarget(target, shouldScroll) {
      if (!target) return;

      accordions.forEach(function (accordion) {
        accordion.open = accordion === target;
      });
      setActive(target);

      if (shouldScroll) {
        window.requestAnimationFrame(function () {
          var header = document.querySelector('.site-header');
          var offset = header ? header.getBoundingClientRect().height + 16 : 16;
          var top = target.getBoundingClientRect().top + window.scrollY - offset;
          window.scrollTo({ top: top, behavior: 'smooth' });
        });
      }
    }

    tiles.forEach(function (tile) {
      tile.addEventListener('click', function (event) {
        var selector = tile.getAttribute('href');
        var target = selector ? document.querySelector(selector) : null;
        if (!target) return;

        event.preventDefault();
        window.history.replaceState(null, '', selector);
        openTarget(target, true);
      });
    });

    accordions.forEach(function (accordion) {
      accordion.addEventListener('toggle', function () {
        if (accordion.open) setActive(accordion);
      });
    });

    var hashTarget = window.location.hash
      ? document.querySelector(window.location.hash)
      : null;
    var initial = hashTarget && hashTarget.matches('.lyrics-accordion')
      ? hashTarget
      : accordions.find(function (accordion) { return accordion.open; });

    openTarget(initial || accordions[0], false);
  }

  document.addEventListener('DOMContentLoaded', initLyricsArchive);
})(window, document);
