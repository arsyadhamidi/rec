(function ($) {
    "use strict";
    $(function () {
        var body = $("body");
        var sidebar = $(".sidebar");

        // Awalnya sidebar berada dalam mode icon-only
        body.addClass("sidebar-icon-only");

        // Menangani klik untuk toggle sidebar
        $('[data-toggle="minimize"]').on("click", function () {
            if (body.hasClass("sidebar-icon-only")) {
                // Jika sidebar dalam mode icon-only, buka sidebar dengan menampilkan teks
                body.removeClass("sidebar-icon-only");
            } else {
                // Jika sidebar sudah dibuka, sembunyikan teks dan hanya tampilkan ikon
                body.addClass("sidebar-icon-only");
            }
        });

        // Menandai elemen yang aktif berdasarkan URL
        function addActiveClass(element) {
            var current = location.pathname; // Ambil seluruh path URL saat ini
            var href = element.attr("href");

            // Cek apakah URL saat ini cocok dengan href elemen (tanpa mempertimbangkan query params)
            if (current === href || current.indexOf(href) !== -1) {
                element.addClass("active");
                element.parents(".nav-item").last().addClass("active"); // Beri kelas active pada parent nav-item
                if (element.parents(".sub-menu").length) {
                    element.closest(".collapse").addClass("show"); // Jika ada sub-menu, buka
                }
            }
        }

        // Apply active class to sidebar links
        $(".nav li a", sidebar).each(function () {
            var $this = $(this);
            addActiveClass($this);
        });

        // Close other submenu on opening any
        sidebar.on("show.bs.collapse", ".collapse", function () {
            sidebar.find(".collapse.show").collapse("hide");
        });

        // Apply perfect scrollbar
        applyStyles();

        function applyStyles() {
            if (!body.hasClass("rtl")) {
                if ($(".settings-panel .tab-content .tab-pane.scroll-wrapper").length) {
                    const settingsPanelScroll = new PerfectScrollbar(".settings-panel .tab-content .tab-pane.scroll-wrapper");
                }
                if ($(".chats").length) {
                    const chatsScroll = new PerfectScrollbar(".chats");
                }
                if (body.hasClass("sidebar-fixed")) {
                    if ($("#sidebar").length) {
                        var fixedSidebarScroll = new PerfectScrollbar("#sidebar .nav");
                    }
                }
            }
        }
    });
})(jQuery);
