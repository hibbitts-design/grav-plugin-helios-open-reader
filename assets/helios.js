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

  // Namespace the key by origin so different sites don't share resume state
  var horLastPageKey = 'hor-last-page|' + window.location.origin;

  function horInitSavePlace(root) {
    // Save current page when on a section-page
    var savePlaceEl = root.querySelector('[data-hor-save-page]');
    if (savePlaceEl) {
      try {
        localStorage.setItem(horLastPageKey, JSON.stringify({
          url: savePlaceEl.dataset.horUrl,
          title: savePlaceEl.dataset.horTitle,
          publicationPath: savePlaceEl.dataset.horPublicationPath || '',
          publicationUrl: savePlaceEl.dataset.horPublicationUrl || '',
          publicationTitle: savePlaceEl.dataset.horPublicationTitle || ''
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
      try {
        var saved = localStorage.getItem(horLastPageKey);
        if (saved) {
          var pageData = JSON.parse(saved);
          var matchAny  = resumeStrip.hasAttribute('data-hor-match-any');
          var stripPath = resumeStrip.dataset.horPublicationPath || '';
          var savedPath = pageData.publicationPath || '';
          if (matchAny || stripPath === savedPath) {
            var resumeBtn   = root.querySelector('#hor-resume-btn');
            var resumeTitle = root.querySelector('#hor-resume-title');
            var destUrl   = matchAny ? (pageData.publicationUrl || pageData.url) : pageData.url;
            var destTitle = matchAny ? (pageData.publicationTitle || pageData.title) : pageData.title;
            if (resumeBtn)   resumeBtn.href = destUrl;
            if (resumeTitle) resumeTitle.textContent = destTitle;
            resumeStrip.removeAttribute('hidden');

            var dismissBtn = resumeStrip.querySelector('.hor-resume-dismiss');
            if (dismissBtn) {
              dismissBtn.addEventListener('click', function () {
                try { localStorage.removeItem(horLastPageKey); } catch (e) {}
                resumeStrip.setAttribute('hidden', '');
              });
            }
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

})();
