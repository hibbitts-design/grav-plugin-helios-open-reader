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
  // On section-page: saves current URL + title to localStorage, and the section being read on it (the last heading
  // scrolled up to near the top of the window), so "Continue reading" goes back to that section.
  // On reader home: reads localStorage and shows a "Continue reading" strip.
  // Runs on initial load and re-runs on each HTMX content swap.
  // -------------------------------------------------------------------------

  // Per-publication key: namespaced by origin + publication path so each
  // publication's saved place is stored independently.
  function horPageKey(publicationPath) {
    return 'hor-last-page|' + window.location.origin + '|' + (publicationPath || '');
  }

  // The page being read (its storage key and details), while on a section-page with Keep My Place on, and its
  // headings (with an id to link to, as in the table of contents); null elsewhere
  var horPlace = null;
  var horPlaceHeadings = [];

  function horSavePlace() {
    try {
      localStorage.setItem(horPlace.key, JSON.stringify(horPlace.details));
    } catch (e) {}
  }

  // The section being read: the last heading that has scrolled up to near the top of the window (below the header,
  // the same point as the table of contents uses), or the last one at the very end of the page. Before the first
  // heading, no section is saved (the top of the page). Saved only when it changes.
  function horRememberSection() {
    if (!horPlace || horPlaceHeadings.length === 0) return;
    var section = '';
    for (var i = 0; i < horPlaceHeadings.length; i++) {
      if (horPlaceHeadings[i].getBoundingClientRect().top <= 100) {
        section = horPlaceHeadings[i].id;
      }
    }
    if (window.innerHeight + window.scrollY >= document.documentElement.scrollHeight - 2) {
      section = horPlaceHeadings[horPlaceHeadings.length - 1].id;
    }
    if (section !== (horPlace.details.section || '')) {
      horPlace.details.section = section;
      horSavePlace();
    }
  }

  // checked at most twice a second while scrolling ("passive" tells the browser this never stops scrolling)
  var horSectionCheckPending = false;
  window.addEventListener('scroll', function () {
    if (!horPlace || horSectionCheckPending) return;
    horSectionCheckPending = true;
    setTimeout(function () {
      horSectionCheckPending = false;
      horRememberSection();
    }, 500);
  }, { passive: true });

  function horInitSavePlace(root) {
    // Save current page when on a section-page
    var savePlaceEl = root.querySelector('[data-hor-save-page]');
    // (a page without the marker, such as the reader home, isn't a place to remember)
    horPlace = null;
    horPlaceHeadings = [];
    if (savePlaceEl) {
      var pubPath = savePlaceEl.dataset.horPublicationPath || '';
      // Only save the page when the Keep My Place setting is on (data-hor-keep-place is "false" when it's off)
      if (savePlaceEl.dataset.horKeepPlace !== 'false') {
        horPlace = {
          key: horPageKey(pubPath),
          details: { url: savePlaceEl.dataset.horUrl, title: savePlaceEl.dataset.horTitle }
        };
        horPlaceHeadings = Array.prototype.slice.call(document.querySelectorAll('article h2[id], article h3[id]'));
        // A page opened at one of its headings (from "Continue reading" or a link ending in # and the heading's id)
        // starts with that section, as the page loads before the browser has scrolled to it
        var linkedHeading = null;
        try {
          linkedHeading = document.getElementById(decodeURIComponent(window.location.hash.slice(1)));
        } catch (e) {
          // a # part that isn't valid (such as a stray %) is left alone
        }
        if (linkedHeading && horPlaceHeadings.indexOf(linkedHeading) !== -1) {
          horPlace.details.section = linkedHeading.id;
        }
        horSavePlace();
      }

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
          // the saved page, at the section being read when there is one (# and the heading's id)
          if (resumeBtn) {
            resumeBtn.href = pageData.url + (pageData.section ? '#' + encodeURIComponent(pageData.section) : '');
            if (pageData.section) {
              resumeBtn.setAttribute('data-hor-full-load', '');
            } else {
              resumeBtn.removeAttribute('data-hor-full-load');
            }
          }
          if (resumeTitle) resumeTitle.textContent = pageData.title;
          resumeStrip.removeAttribute('hidden');

          var dismissBtn = resumeStrip.querySelector('.hor-resume-dismiss');
          if (dismissBtn) {
            dismissBtn.addEventListener('click', function () {
              try { localStorage.removeItem(horPageKey(stripPubPath)); } catch (e) {}
              resumeStrip.setAttribute('hidden', '');
              // keyboard focus moves to the page's heading, as the button it was on is now hidden
              var heading = document.querySelector('#main-content h1');
              if (heading) {
                heading.setAttribute('tabindex', '-1');
                heading.focus();
              }
            });
          }
        }
      } catch (e) {}
    }
  }

  horInitSavePlace(document);

  // "Continue reading" to a section loads the page in full rather than swapping it in with HTMX, which scrolls a
  // swapped-in page to its top - a full load lets the browser go straight to the section. (Caught in the capture
  // phase, before HTMX's own click handler on the link.)
  document.addEventListener('click', function (evt) {
    if (evt.defaultPrevented || evt.button !== 0 || evt.metaKey || evt.ctrlKey || evt.shiftKey || evt.altKey) return;
    var link = evt.target.closest('a[data-hor-full-load]');
    if (!link) return;
    evt.preventDefault();
    evt.stopPropagation();
    window.location.href = link.href;
  }, true);

  // "This page as Markdown" link in the footer: the footer isn't re-rendered on HTMX navigation, so update the
  // link from the new page's data-hor-markdown-url marker, and hide it where the page has none
  function horUpdateMarkdownLink(root) {
    var marker = root.querySelector('[data-hor-markdown-url]');
    var wrap = document.querySelector('.hor-markdown-link-wrap');
    if (!marker || !wrap) return;
    // dataset.horMarkdownUrl reads the marker's data-hor-markdown-url attribute (empty where there's no link)
    var url = marker.dataset.horMarkdownUrl;
    var link = wrap.querySelector('a');
    if (url) {
      link.href = url;
      wrap.hidden = false;
    } else {
      wrap.hidden = true;
    }
  }

  window.addEventListener('helios:content-loaded', function (evt) {
    var container = evt.detail && evt.detail.container;
    if (container) {
      horInitSavePlace(container);
      horUpdateMarkdownLink(container);
    }
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
      if (show) {
        stickyNav.removeAttribute('inert');
      } else {
        stickyNav.setAttribute('inert', '');
      }
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

// On reader pages, the left and right arrow keys go to the previous and next page - as when presenting slides,
// for example by screen sharing. On a publication's home page, the right arrow key follows its Start Reading button,
// and on its first page the left arrow key goes back to its home page.
// The links are clicked rather than opened, so pages load just as they would for a reader clicking them (the home page,
// which has no link on the bar, is opened by its address).
(function () {

  // true when an element is a box that scrolls sideways (such as a wide table or code block) and is wider than its space,
  // where the arrow keys should scroll it instead
  function scrollsSideways(element) {
    var overflowX = window.getComputedStyle(element).overflowX;
    var canScroll = overflowX === 'auto' || overflowX === 'scroll';
    return canScroll && element.scrollWidth > element.clientWidth;
  }

  document.addEventListener('keydown', function (event) {
    // leave the keys alone with a modifier key held, so browser shortcuts still work
    if (event.altKey || event.ctrlKey || event.metaKey || event.shiftKey) {
      return;
    }
    // and while typing (including in the search dialog), or in a box that scrolls sideways
    var target = event.target;
    var typingTags = ['INPUT', 'TEXTAREA', 'SELECT'];
    if (target.isContentEditable || typingTags.indexOf(target.tagName) !== -1) {
      return;
    }
    if (target !== document.body && scrollsSideways(target)) {
      return;
    }
    // and while an image is shown larger (tap to see it larger), where the reader is looking at the image
    if (document.querySelector('dialog[open]')) {
      return;
    }

    // the page's own Previous/Next buttons (at the top or bottom of the page)
    var link = null;
    if (event.key === 'ArrowLeft') {
      link = document.querySelector('.prev-next-nav .nav-button.prev');
      // on a publication's first page there's no Previous page, so the left arrow key goes back to the publication's
      // home page instead (its address is on the Previous/Next bar, as data-hor-home-url), so presenting with the arrow
      // keys works both ways
      var homeNav = document.querySelector('.prev-next-nav[data-hor-home-url]');
      if (!link && homeNav) {
        window.location.href = homeNav.dataset.horHomeUrl;
        return;
      }
    }
    if (event.key === 'ArrowRight') {
      link = document.querySelector('.prev-next-nav .nav-button.next, .hor-start-reading');
    }
    if (link) {
      link.click();
    }
  });

})();

// Collapse/expand the sidebar: on wider screens, a button below the site icon collapses the sidebar to a slim strip, so
// readers can focus on the page, or expands it again. The choice is remembered in the reader's browser (localStorage) for
// the whole site, and applied before the page is drawn (see partials/base.html.twig). Phones use the menu button as
// before - hibbittsdesign.org
(function () {
  var button = document.querySelector(".hor-sidebar-toggle");
  var sidebar = document.getElementById("hor-sidebar");
  if (!button || !sidebar) {
    return;
  }
  var root = document.documentElement;
  var expandLabel = button.querySelector(".hor-sidebar-toggle-expand");
  var homeLink = sidebar.querySelector(".hor-sidebar-head a");
  var siteName = homeLink ? homeLink.textContent.trim() : "";

  // collapse or expand, and tell screen readers whether the sidebar is expanded (aria-expanded); collapsed, the button
  // shows only its arrows, so its name also shows on hover (title)
  function setCollapsed(collapsed) {
    root.classList.toggle("hor-sidebar-collapsed", collapsed);
    button.setAttribute("aria-expanded", collapsed ? "false" : "true");
    if (collapsed) {
      button.setAttribute("title", expandLabel.textContent.trim());
    } else {
      button.removeAttribute("title");
    }
    // collapsed, only the site icon shows, so the site name also shows on hover
    if (homeLink && siteName) {
      if (collapsed) {
        homeLink.setAttribute("title", siteName);
      } else {
        homeLink.removeAttribute("title");
      }
    }
  }

  setCollapsed(root.classList.contains("hor-sidebar-collapsed"));
  button.classList.add("is-ready");

  button.addEventListener("click", function () {
    var collapsed = !root.classList.contains("hor-sidebar-collapsed");
    setCollapsed(collapsed);
    try {
      localStorage.setItem("hor-sidebar-collapsed", collapsed ? "1" : "0");
    } catch (error) {
      // without storage the choice still works on this page, it just isn't remembered
    }
  });
})();
