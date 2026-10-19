/*
Tap or click an image in a page to see it larger, filling the screen - handy for diagrams and screenshots, especially
on phones (as in Grav Open Publishing Space and Grav Open Course Hub). Only images in the page's text that are shown
smaller than their full size, and aren't already links (such as [doc-image] with its own lightbox) or on cards, get
this. The larger view is a dialog: Escape, the Close button, or a tap anywhere closes it, and keyboard focus goes back
to the image - hibbittsdesign.org
*/
(function () {
    // (very old browsers without dialogs keep the images as they are)
    if (typeof HTMLDialogElement === 'undefined') {
        return;
    }
    // the labels, in the site's language (set by the plugin, next to where it adds this script)
    var labels = window.heliosImageZoomLabels || {};
    var largerLabel = labels.larger || 'Show image larger';
    var closeLabel = labels.close || 'Close';
    var dialog = null;

    // the dialog is made the first time it's needed, and used for every image
    function makeDialog() {
        dialog = document.createElement('dialog');
        dialog.className = 'image-zoom-dialog';
        var closeButton = document.createElement('button');
        closeButton.type = 'button';
        closeButton.className = 'image-zoom-close';
        closeButton.setAttribute('aria-label', closeLabel);
        closeButton.innerHTML = '<span aria-hidden="true">&times;</span>';
        var largeImage = document.createElement('img');
        dialog.appendChild(closeButton);
        dialog.appendChild(largeImage);
        // a tap or click anywhere (the image, the dark background or the Close button) closes it
        dialog.addEventListener('click', function () {
            dialog.close();
        });
        document.body.appendChild(dialog);
    }

    function showLarger(image) {
        if (!dialog) {
            makeDialog();
        }
        var largeImage = dialog.querySelector('img');
        largeImage.src = image.currentSrc || image.src;
        largeImage.alt = image.alt;
        dialog.setAttribute('aria-label', image.alt || largerLabel);
        dialog.showModal();
    }

    // An image shown at least a fifth smaller than its full size works as a button that shows it larger: it can be
    // reached with the Tab key and opened with Enter or Space, and screen readers hear it as a button named with its alt
    // text. (The image itself becomes the button, rather than going inside one, so the layout around it stays the same.)
    // Left out: images inside a link or button (including [doc-image] with its own lightbox), and images on cards or
    // covers ([class*="card"] and [class*="cover"] match any class name containing those words), which open a page.
    function setUp(image) {
        if (image.dataset.imageZoomChecked) {
            return;
        }
        image.dataset.imageZoomChecked = 'true';
        if (image.closest('a, button, [class*="card"], [class*="cover"]')
            || image.naturalWidth < image.clientWidth * 1.2) {
            return;
        }
        image.classList.add('image-zoom');
        image.setAttribute('role', 'button');
        image.setAttribute('tabindex', '0');
        image.setAttribute('aria-label', image.alt || largerLabel);
        image.addEventListener('click', function () {
            showLarger(image);
        });
        image.addEventListener('keydown', function (event) {
            if (event.key === 'Enter' || event.key === ' ') {
                // (Space would otherwise scroll the page)
                event.preventDefault();
                showLarger(image);
            }
        });
    }

    // the images in the page's text (the .prose article inside the main content)
    function setUpImages() {
        var images = document.querySelectorAll('#main-content .prose img');
        for (var i = 0; i < images.length; i++) {
            if (images[i].complete) {
                setUp(images[i]);
            } else {
                images[i].addEventListener('load', function (event) {
                    setUp(event.currentTarget);
                });
            }
        }
    }

    setUpImages();
    // Helios swaps in the next page's text without reloading (HTMX), then sends this event
    window.addEventListener('helios:content-loaded', setUpImages);
})();
