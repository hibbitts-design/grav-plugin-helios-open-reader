// Frontend JS customizations

(function () {
  var theme = localStorage.getItem("helios-theme") || "system";
  var isDark =
    theme === "dark" ||
    (theme === "system" &&
      window.matchMedia("(prefers-color-scheme: dark)").matches);
  var cardTheme = isDark ? "dark" : "light";

  var embeds = document.querySelectorAll(".embedly-card");
  for (var i = 0; i < embeds.length; i++) {
    embeds[i].setAttribute("data-card-theme", cardTheme);
  }

  // Load Embedly platform.js if embedly cards are present
  if (embeds.length > 0) {
    var script = document.createElement("script");
    script.src = "https://cdn.embedly.com/widgets/platform.js";
    script.async = true;
    document.body.appendChild(script);

    // Reload on theme class change so Embedly cards re-render with the correct theme
    window.addEventListener("load", function () {
      var observer = new MutationObserver(function () {
        observer.disconnect();
        window.location.reload();
      });
      observer.observe(document.documentElement, {
        attributes: true,
        attributeFilter: ["class"],
      });
    });
  }

  // --- BEGIN: HTMX Embedly fix --- (remove this block if it causes issues)
  window.addEventListener("helios:content-loaded", function (evt) {
    var container = evt.detail && evt.detail.container;
    if (!container) return;

    var newEmbeds = container.querySelectorAll(".embedly-card");
    if (newEmbeds.length === 0) return;

    for (var i = 0; i < newEmbeds.length; i++) {
      newEmbeds[i].setAttribute("data-card-theme", cardTheme);
    }

    // Remove existing Embedly script(s) then re-append to force re-execution in all browsers
    document.querySelectorAll('script[src*="cdn.embedly.com/widgets/platform.js"]').forEach(function(el) {
      el.parentNode.removeChild(el);
    });
    var s = document.createElement("script");
    s.src = "https://cdn.embedly.com/widgets/platform.js";
    s.async = true;
    document.body.appendChild(s);
  });
  // --- END: HTMX Embedly fix ---

  // -------------------------------------------------------------------------
  // Save My Place / Resume Reading
  // On section-page: saves current URL + title to localStorage.
  // On reader home: reads localStorage and shows a "Continue reading" strip.
  // Runs on initial load and re-runs on each HTMX content swap.
  // -------------------------------------------------------------------------

  // Per-publication key: namespaced by origin + publication path so each
  // publication's saved place is stored independently.
  function horPageKey(publicationPath) {
    return 'hor-last-page|' + window.location.origin + '|' + (publicationPath || '');
  }

  function horInitSavePlace(root) {
    // Save current page when on a section-page
    var savePlaceEl = root.querySelector('[data-hor-save-page]');
    if (savePlaceEl) {
      var pubPath = savePlaceEl.dataset.horPublicationPath || '';
      try {
        localStorage.setItem(horPageKey(pubPath), JSON.stringify({
          url: savePlaceEl.dataset.horUrl,
          title: savePlaceEl.dataset.horTitle
        }));
      } catch (e) {}

      // Update footer git link — footer is rendered from the section landing page and
      // cannot update itself on HTMX navigation; use the pre-built URL from section-page.html.twig
      var gitUrl = savePlaceEl.dataset.horGitUrl;
      if (gitUrl) {
        var gitLink = document.querySelector('.edit-page-link');
        if (gitLink) gitLink.href = gitUrl;
      }
    }

    // Populate and show the resume strip when on the reader home page
    var resumeStrip = root.querySelector('#hor-resume-reading');
    if (resumeStrip) {
      var stripPubPath = resumeStrip.dataset.horPublicationPath || '';
      try {
        var saved = localStorage.getItem(horPageKey(stripPubPath));
        if (saved) {
          var pageData = JSON.parse(saved);
          var resumeBtn   = root.querySelector('#hor-resume-btn');
          var resumeTitle = root.querySelector('#hor-resume-title');
          if (resumeBtn)   resumeBtn.href = pageData.url;
          if (resumeTitle) resumeTitle.textContent = pageData.title;
          resumeStrip.removeAttribute('hidden');

          var dismissBtn = resumeStrip.querySelector('.hor-resume-dismiss');
          if (dismissBtn) {
            dismissBtn.addEventListener('click', function () {
              try { localStorage.removeItem(horPageKey(stripPubPath)); } catch (e) {}
              resumeStrip.setAttribute('hidden', '');
            });
          }
        }
      } catch (e) {}
    }
  }

  horInitSavePlace(document);

  window.addEventListener('helios:content-loaded', function (evt) {
    var container = evt.detail && evt.detail.container;
    if (container) horInitSavePlace(container);
  });

  // -------------------------------------------------------------------------
  // Sticky prev/next nav
  // Shows a compact fixed bar when both main nav blocks are off-screen.
  // Observes .prev-next-nav--top (top nav) and #htmx-prev-next .prev-next-nav
  // (bottom nav); bar appears only when neither is visible in the viewport.
  // -------------------------------------------------------------------------
  function horInitStickyNav() {
    var stickyNav = document.getElementById('hor-sticky-nav');
    if (!stickyNav) return;

    var navBlocks = Array.prototype.slice.call(document.querySelectorAll(
      '.prev-next-nav--top, #htmx-prev-next .prev-next-nav'
    ));
    if (navBlocks.length === 0) return;

    var visible = new Set();

    function updateStickyNav() {
      var show = visible.size === 0;
      stickyNav.classList.toggle('hor-sticky-nav--visible', show);
      stickyNav.setAttribute('aria-hidden', show ? 'false' : 'true');
      var links = stickyNav.querySelectorAll('a');
      for (var i = 0; i < links.length; i++) {
        links[i].tabIndex = show ? 0 : -1;
      }
    }

    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          visible.add(entry.target);
        } else {
          visible.delete(entry.target);
        }
      });
      updateStickyNav();
    });

    navBlocks.forEach(function (block) {
      observer.observe(block);
    });

    // Disconnect before the next HTMX swap; horInitStickyNav re-runs after
    window.addEventListener('htmx:beforeSwap', function () {
      observer.disconnect();
    }, { once: true });
  }

  horInitStickyNav();

  window.addEventListener('helios:content-loaded', function () {
    horInitStickyNav();
  });

})();
