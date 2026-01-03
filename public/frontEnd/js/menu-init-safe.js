// Safe initialization of MmenuLight
(function() {
    var menuElement = document.querySelector("#menu");
    if (!menuElement) {
        console.warn('Menu element #menu not found - skipping MmenuLight initialization');
        return;
    }
    
    try {
        var menu = new MmenuLight(menuElement, "all");

        var navigator = menu.navigation({
            selectedClass: "Selected",
            slidingSubmenus: true,
            title: "ক্যাটাগরি",
        });

        var drawer = menu.offcanvas({});

        // Open the menu
        var menuLink = document.querySelector('a[href="#menu"]');
        if (menuLink) {
            menuLink.addEventListener("click", function(evnt) {
                evnt.preventDefault();
                drawer.open();
            });
        }
    } catch (error) {
        console.error('Error initializing MmenuLight:', error);
    }
})();
