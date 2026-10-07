<!-- Web Fonts  -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<!-- Vendor CSS -->
<link rel="stylesheet" href="<?= base_url('assets/') ?>vendor/bootstrap/css/bootstrap.css" />
<link rel="stylesheet" href="<?= base_url('assets/') ?>vendor/animate/animate.compat.css">

<link rel="stylesheet" href="<?= base_url('assets/') ?>vendor/font-awesome/css/all.min.css" />
<link rel="stylesheet" href="<?= base_url('assets/') ?>vendor/boxicons/css/boxicons.min.css" />
<link rel="stylesheet" href="<?= base_url('assets/') ?>vendor/magnific-popup/magnific-popup.css" />
<link rel="stylesheet" href="<?= base_url('assets/') ?>vendor/bootstrap-datepicker/css/bootstrap-datepicker3.css" />


<!-- Specific Page Vendor CSS -->
<link rel="stylesheet" href="<?= base_url('assets/') ?>vendor/jquery-ui/jquery-ui.css" />
<link rel="stylesheet" href="<?= base_url('assets/') ?>vendor/jquery-ui/jquery-ui.theme.css" />
<link rel="stylesheet" href="<?= base_url('assets/') ?>vendor/select2/css/select2.css" />
<link rel="stylesheet" href="<?= base_url('assets/') ?>vendor/select2-bootstrap-theme/select2-bootstrap.min.css" />
<link rel="stylesheet" href="<?= base_url('assets/') ?>vendor/dropzone/basic.css" />
<link rel="stylesheet" href="<?= base_url('assets/') ?>vendor/dropzone/dropzone.css" />
<link rel="stylesheet" href="<?= base_url('assets/') ?>vendor/bootstrap-markdown/css/bootstrap-markdown.min.css" />
<link rel="stylesheet" href="<?= base_url('assets/') ?>vendor/pnotify/pnotify.custom.css" />
<link rel="stylesheet" href="<?= base_url('assets/') ?>vendor/datatables/media/css/dataTables.bootstrap4.css" />
<link rel="stylesheet" href="<?= base_url('assets/') ?>vendor/simple-line-icons/css/simple-line-icons.css" />
<link rel="stylesheet" href="<?= base_url('assets/') ?>js/sweetalert2/sweetalert2.min.css" />
<link rel="stylesheet" href="<?= base_url('assets/') ?>js/croppie/croppie.css" />

<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.js" integrity="sha512-AQMSn1qO6KN85GOfvH6BWJk46LhlvepblftLHzAv1cdIyTWPBKHX+r+NOXVVw6+XQpeW4LJk/GTmoP48FLvblQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>


<!--(remove-empty-lines-end)-->

<!-- Theme CSS -->
<link rel="stylesheet" href="<?= base_url('assets/') ?>css/theme.css" />



<!-- Theme Layout -->
<link rel="stylesheet" href="<?= base_url('assets/') ?>css/layouts/modern.css" />
<!--(remove-empty-lines-end)-->



<!-- Theme Custom CSS -->
<link rel="stylesheet" href="<?= base_url('assets/') ?>css/custom.css?v=<?= time() ?>">
<link rel="shortcut icon" href="<?= base_url('assets/') ?>img/favicon.png" />
<!-- Head Libs -->

<style>
    html.modern html,
    html.modern body {
        background: none !important;
    }

    .header .logo-container {
        background-image: none;
        background-color: #1D2127;
        border-bottom-color: #161a1e;
        border-top-color: #1D2127;
    }

    .modal-header {
        padding: 5px 5px 5px 20px;
    }

    /* Modern Logo Brand Title Styling & Hover fix */
    html.modern .logo {
        text-decoration: none !important;
        display: block !important;
        line-height: 1.2 !important;
    }

    html.modern .logo:hover,
    html.modern .logo:focus,
    html.modern .logo:active {
        text-decoration: none !important;
        opacity: 0.95;
    }

    html.modern .logo b.brand-title {
        display: flex !important;
        flex-direction: column !important;
        font-family: 'Poppins', sans-serif !important;
        font-weight: 700 !important;
        text-align: left;
    }

    html.modern .logo .brand-system {
        font-size: 14.5px !important;
        color: #ffffff !important;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    html.modern .logo .brand-company {
        font-size: 10px !important;
        color: #0ea5e9 !important;
        /* Premium corporate cyan */
        font-weight: 600 !important;
        text-transform: uppercase;
        letter-spacing: 0.2px;
        margin-top: 1px;
    }

    @media only screen and (min-width: 768px) {
        html.modern .header:not(.header-nav-menu) .logo {
            padding: 10px 20px 0 15px !important;
        }
    }

    @media (max-width: 767px) {
        html.modern .header .logo-container .logo {
            margin-top: 0 !important;
            padding-left: 0 !important;
        }
    }

    /* Custom Sidebar & Layout Width Adjustments */
    @media only screen and (min-width: 768px) {
        .sidebar-left {
            width: 260px !important;
        }

        html.fixed:not(.sidebar-left-collapsed) .content-body {
            margin-left: 260px !important;
        }

        html.fixed:not(.sidebar-left-collapsed) .page-header {
            left: 260px !important;
        }

        html.modern .header:not(.header-nav-menu) .logo:after,
        html.modern .header.header-nav-menu .logo:after {
            width: 260px !important;
        }

        @media (min-width: 992px) {
            html.modern .header.header-nav-menu .header-nav {
                margin-left: 260px !important;
            }
        }

        @media (max-width: 991px) {
            html.modern:not(.sidebar-left-collapsed) .header.header-nav-menu .header-nav-main nav {
                margin-left: 260px !important;
                width: calc(100% - 260px) !important;
            }
        }
    }

    /* Modern & Clean Sidebar Menu Typography & Styling */
    .sidebar-left {
        background: #0f172a !important;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important;
        -webkit-font-smoothing: antialiased !important;
        -moz-osx-font-smoothing: grayscale !important;
        text-rendering: optimizeLegibility !important;
    }

    .sidebar-left .nano-content {
        background: #0f172a !important;
    }

    /* Nav Menu Styling & Clean Font */
    html.modern ul.nav-main li>a {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important;
        padding: 8px 14px !important;
        font-size: 12.5px !important;
        font-weight: 500 !important;
        letter-spacing: 0.01em !important;
        color: #94a3b8 !important;
        border-left: 3px solid transparent !important;
        display: flex !important;
        align-items: center !important;
        transition: all 0.18s ease-in-out !important;
        -webkit-font-smoothing: antialiased !important;
    }

    /* Modern & Clean Sidebar Icon Styling */
    html.modern ul.nav-main li>a i {
        color: #64748b !important;
        font-size: 13px !important;
        width: 26px !important;
        height: 26px !important;
        min-width: 26px !important;
        border-radius: 6px !important;
        background: rgba(255, 255, 255, 0.03) !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        margin-right: 10px !important;
        transition: all 0.18s ease-in-out !important;
        text-align: center;
    }

    /* Hover State */
    html.modern ul.nav-main li>a:hover {
        background-color: rgba(255, 255, 255, 0.035) !important;
        color: #f1f5f9 !important;
        border-left-color: rgba(56, 189, 248, 0.6) !important;
    }

    html.modern ul.nav-main li>a:hover i {
        color: #38bdf8 !important;
        background: rgba(56, 189, 248, 0.12) !important;
    }

    /* Active State */
    html.modern ul.nav-main li.nav-active>a {
        background-color: rgba(56, 189, 248, 0.07) !important;
        color: #38bdf8 !important;
        border-left-color: #38bdf8 !important;
        font-weight: 600 !important;
    }

    html.modern ul.nav-main li.nav-active>a i {
        color: #38bdf8 !important;
        background: rgba(56, 189, 248, 0.18) !important;
        box-shadow: 0 0 8px rgba(56, 189, 248, 0.2) !important;
    }

    /* Children menu style (collapsed/expanded) */
    html.modern ul.nav-main li.nav-parent>a:after {
        color: #64748b !important;
        font-size: 8.5px !important;
        transition: transform 0.2s ease, color 0.2s ease !important;
    }

    html.modern ul.nav-main li.nav-parent.nav-expanded>a:after {
        color: #38bdf8 !important;
    }

    html.modern ul.nav-main li .nav-children {
        background: #0b1120 !important;
        padding-left: 0 !important;
    }

    html.modern ul.nav-main li .nav-children li a {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important;
        padding: 7px 16px 7px 38px !important;
        font-size: 12px !important;
        font-weight: 400 !important;
        letter-spacing: 0.005em !important;
        color: #94a3b8 !important;
        position: relative !important;
        display: flex !important;
        align-items: center !important;
        transition: all 0.18s ease !important;
        -webkit-font-smoothing: antialiased !important;
    }

    /* Clean Sub-menu Bullet Indicator */
    html.modern ul.nav-main li .nav-children li a::before {
        content: "" !important;
        width: 4.5px !important;
        height: 4.5px !important;
        border-radius: 50% !important;
        background: #475569 !important;
        position: absolute !important;
        left: 22px !important;
        transition: all 0.18s ease !important;
    }

    html.modern ul.nav-main li .nav-children li a:hover {
        color: #38bdf8 !important;
        background: rgba(255, 255, 255, 0.02) !important;
    }

    html.modern ul.nav-main li .nav-children li a:hover::before {
        background: #38bdf8 !important;
        transform: scale(1.3) !important;
    }

    html.modern ul.nav-main li .nav-children li.nav-active a {
        color: #38bdf8 !important;
        font-weight: 600 !important;
    }

    html.modern ul.nav-main li .nav-children li.nav-active a::before {
        background: #38bdf8 !important;
        transform: scale(1.3) !important;
        box-shadow: 0 0 6px rgba(56, 189, 248, 0.6) !important;
    }

    /* Group labels (Lain Lain, etc.) */
    html.modern ul.nav-main li.nav-group-label {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important;
        color: #64748b !important;
        font-size: 10px !important;
        font-weight: 600 !important;
        letter-spacing: 0.08em !important;
        padding: 14px 16px 5px 16px !important;
        margin-top: 8px !important;
        text-transform: uppercase !important;
        -webkit-font-smoothing: antialiased !important;
    }

    /* Elegantly style the switch menu in sidebar */
    #switch_menu {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important;
        background-color: #1e293b !important;
        color: #f1f5f9 !important;
        border: 1px solid #334155 !important;
        border-radius: 8px !important;
        padding: 7px 12px !important;
        font-size: 12px !important;
        font-weight: 500 !important;
        height: auto !important;
        width: calc(100% - 24px) !important;
        margin: 12px !important;
        cursor: pointer;
        outline: none;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
        -webkit-font-smoothing: antialiased !important;
    }

    #switch_menu:focus {
        border-color: #38bdf8 !important;
        box-shadow: 0 0 0 2px rgba(56, 189, 248, 0.2) !important;
    }

    /* Mobile scrolling safe area and force-momentum scroll */
    @media (max-width: 767px) {
        .sidebar-left {
            overflow-y: auto !important;
            -webkit-overflow-scrolling: touch !important;
        }

        .sidebar-left .nano,
        .sidebar-left .nano-content {
            overflow: visible !important;
            position: static !important;
        }
    }

    /* Modern Header Styling Overrides */
    .header-right {
        display: flex !important;
        align-items: center;
        justify-content: flex-end;
        gap: 14px !important;
        height: 100% !important;
        padding-right: 20px !important;
    }

    @media only screen and (min-width: 768px) {
        .header-right {
            position: absolute !important;
            right: 0 !important;
            top: 0 !important;
            bottom: 0 !important;
            left: 260px !important;
            float: none !important;
            padding-left: 20px !important;
        }

        html.sidebar-left-collapsed .header-right {
            left: 73px !important;
        }
    }

    .header-clock-widget {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 5px 12px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
    }

    .header-clock-widget .clock-icon {
        font-size: 13px;
        color: #2563eb;
        background: #eff6ff;
        width: 26px;
        height: 26px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .header-clock-widget .clock-text-wrapper {
        display: flex;
        flex-direction: column;
        text-align: left;
    }

    .header-clock-widget .clock-time {
        font-size: 12.5px;
        font-weight: 700;
        color: #0f172a;
        line-height: 1.15;
        font-family: 'Poppins', sans-serif;
    }

    .header-clock-widget .clock-date {
        font-size: 10.5px;
        color: #64748b;
        font-weight: 500;
        line-height: 1.15;
    }

    /* Separator bar */
    .header .separator {
        width: 1px;
        height: 22px;
        background: #e2e8f0;
        margin: 0 2px;
        display: inline-block;
    }

    /* Modern User Profile Box */
    .userbox-modern {
        display: flex;
        align-items: center;
        gap: 9px;
        padding: 4px 8px;
        border-radius: 20px;
        transition: background 0.2s ease;
    }

    .userbox-modern:hover {
        background: #f8fafc;
    }

    .user-avatar-circle {
        width: 34px;
        height: 34px;
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: #ffffff;
        font-weight: 700;
        font-size: 13px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #ffffff;
        box-shadow: 0 2px 4px rgba(15, 23, 42, 0.08);
        text-transform: uppercase;
        flex-shrink: 0;
        overflow: hidden;
    }

    .user-info-wrapper {
        display: flex;
        flex-direction: column;
        text-align: left;
    }

    .user-greeting {
        font-size: 9.5px;
        color: #94a3b8;
        font-weight: 500;
        line-height: 1.1;
        letter-spacing: 0.02em;
    }

    .user-name {
        font-size: 12px;
        font-weight: 700;
        color: #0f172a;
        line-height: 1.2;
        letter-spacing: -0.01em;
    }

    /* Modern Notifications Dropdown */
    .notif-dropdown-wrapper {
        display: flex;
        align-items: center;
    }

    .notif-icon-box {
        width: 34px;
        height: 34px;
        background: #f8fafc;
        color: #64748b;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid #e2e8f0;
        font-size: 13px;
    }

    .notif-icon-box:hover {
        background: #eff6ff;
        color: #2563eb;
        border-color: #bfdbfe;
        transform: translateY(-1px);
        box-shadow: 0 3px 6px rgba(37, 99, 235, 0.1);
    }

    .notif-icon-box.has-notifications {
        background: #fff5f5;
        color: #ef4444;
        border-color: #fca5a5;
    }

    .notif-icon-box.has-notifications:hover {
        background: #fee2e2;
        color: #dc2626;
        border-color: #f87171;
    }

    /* Page Header Modern Styling (Title Halaman & Breadcrumb) */
    .page-header {
        background: #ffffff !important;
        border-bottom: 1px solid #e2e8f0 !important;
        border-left: 3px solid #2563eb !important;
        box-shadow: 0 1px 3px 0 rgba(15, 23, 42, 0.04) !important;
        height: 46px !important;
        min-height: 46px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        padding: 0 15px !important;
    }

    @media only screen and (min-width: 768px) {
        .page-header {
            padding: 0 20px !important;
        }

        /* Only apply negative margins if layout is NOT fixed to prevent overlapping fixed header */
        html:not(.fixed) .page-header {
            margin: -40px -40px 24px -40px !important;
        }

        html.fixed .page-header {
            margin: 0 !important;
        }
    }

    @media only screen and (max-width: 767px) {
        .page-header {
            margin: 0 -15px 15px -15px !important;
            height: auto !important;
            min-height: 46px !important;
            padding: 8px 15px !important;
            flex-wrap: wrap !important;
            gap: 6px !important;
        }
    }

    .page-header h2 {
        color: #0f172a !important;
        font-size: 13.5px !important;
        font-weight: 700 !important;
        padding: 0 !important;
        margin: 0 !important;
        display: flex !important;
        align-items: center !important;
        gap: 8px !important;
        line-height: normal !important;
        font-family: 'Poppins', sans-serif !important;
        letter-spacing: -0.01em !important;
    }

    .page-header h2 i {
        color: #2563eb !important;
        background: #eff6ff !important;
        width: 26px !important;
        height: 26px !important;
        border-radius: 7px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        font-size: 12px !important;
        box-shadow: 0 1px 2px rgba(37, 99, 235, 0.08) !important;
        flex-shrink: 0;
    }

    /* Page Header Breadcrumbs & Subtitle Styling */
    .page-header .breadcrumbs {
        margin: 0 !important;
        padding: 0 !important;
        list-style: none !important;
    }

    .page-header .breadcrumbs li {
        color: #64748b !important;
        font-size: 11.5px !important;
        font-weight: 500 !important;
        display: inline-flex !important;
        align-items: center !important;
    }

    .page-header .breadcrumbs li:after {
        color: #cbd5e1 !important;
        font-size: 10px !important;
        margin: 0 6px !important;
    }

    .page-header .breadcrumbs a {
        color: #2563eb !important;
        font-weight: 500 !important;
        font-size: 11.5px !important;
        transition: color 0.15s ease !important;
    }

    .page-header .breadcrumbs a:hover {
        color: #1d4ed8 !important;
        text-decoration: none !important;
    }

    .page-header .breadcrumbs span {
        color: #64748b !important;
        font-size: 11.5px !important;
        font-weight: 500 !important;
    }

    .notif-badge-num {
        position: absolute;
        top: -4px;
        right: -4px;
        background: #ef4444;
        color: #ffffff;
        font-size: 10px;
        font-weight: 700;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #ffffff;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    /* Dropdown Menu styling */
    .notif-dropdown-menu {
        width: 350px;
        max-width: calc(100vw - 32px);
        background: #ffffff !important;
        border: none !important;
        border-radius: 12px !important;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
        padding: 0 !important;
        margin-top: 8px !important;
        overflow: hidden;
        z-index: 1050;
    }

    .notif-header {
        background: #f8fafc;
        border-bottom: 1px solid #f1f5f9;
        padding: 12px 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .notif-header-title {
        font-weight: 700;
        font-size: 14px;
        color: #0f172a;
    }

    .notif-list-scroll {
        max-height: 280px;
        overflow-y: auto;
    }

    .notif-items-list {
        list-style: none !important;
        padding: 0 !important;
        margin: 0 !important;
    }

    .notif-single-item {
        display: flex;
        padding: 12px 16px;
        border-bottom: 1px solid #f1f5f9;
        transition: background 0.2s ease;
    }

    .notif-single-item:hover {
        background: #f8fafc;
    }

    .notif-single-item:last-child {
        border-bottom: none;
    }

    .notif-item-left {
        margin-right: 12px;
    }

    .notif-icon-circle {
        width: 32px;
        height: 32px;
        background: #e0f2fe;
        color: #0284c7;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .notif-item-body {
        flex: 1;
        text-align: left;
    }

    .notif-sender {
        font-size: 12px;
        color: #334155;
        margin-bottom: 2px;
    }

    .notif-recipient {
        font-size: 11px;
        color: #64748b;
        margin-bottom: 4px;
    }

    .notif-desc {
        font-size: 12px;
        line-height: 1.4;
        margin-bottom: 4px;
    }

    .notif-text-link {
        color: #0284c7 !important;
        font-weight: 500;
        text-decoration: none !important;
    }

    .notif-text-link:hover {
        color: #0369a1 !important;
        text-decoration: underline !important;
    }

    .notif-time {
        font-size: 10px;
        color: #94a3b8;
        display: flex;
        align-items: center;
    }

    .notif-empty-state {
        padding: 24px 16px;
        text-align: center;
        color: #94a3b8;
    }

    .notif-empty-state i {
        font-size: 32px;
        color: #cbd5e1;
        margin-bottom: 8px;
        display: block;
    }

    .notif-empty-state p {
        margin: 0;
        font-size: 12px;
    }

    .notif-footer {
        padding: 10px 16px;
        background: #f8fafc;
        border-top: 1px solid #f1f5f9;
    }

    .notif-footer-link {
        font-size: 11px;
        text-decoration: none !important;
        transition: opacity 0.2s ease;
    }

    .notif-footer-link:hover {
        opacity: 0.8;
    }

    .user-thumb {
        background: none repeat scroll 0 0 #ffffff;
        float: left;
        height: 40px;
        margin-right: 10px;
        margin-top: 5px;
        padding: 2px;
        width: 40px;
    }

    .recent-posts,
    .recent-comments,
    .recent-users {
        margin: 0;
        padding: 0;
    }

    .recent-posts li,
    .recent-comments li,
    .article-post li,
    .recent-users li {
        border-bottom: 1px dotted #aebdc8;
        list-style: none outside none;
        padding: 10px;
    }

    .recent-posts li.viewall,
    .recent-comments li.viewall,
    .recent-users li.viewall {
        padding: 0;
    }

    .recent-posts li.viewall a,
    .recent-comments li.viewall a,
    .recent-users li.viewall a {
        padding: 5px;
        text-align: center;
        display: block;
        color: #888888;
    }

    .recent-posts li.viewall a:hover,
    .recent-comments li.viewall a:hover,
    .recent-users li.viewall a:hover {
        background-color: #eeeeee;
    }

    .recent-posts li:last-child,
    .recent-comments li:last-child,
    .recent-users li:last-child {
        border-bottom: none !important;
    }

    /* Mobile header responsiveness overrides */
    @media (max-width: 767px) {
        .header-right .separator {
            display: none !important;
        }

        .header-right {
            display: none !important;
        }

        html.modern .header {
            height: 50px !important;
        }

        .header .logo-container {
            height: 50px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            padding: 0 15px !important;
        }

        .header .logo-container .logo {
            margin: 0 !important;
            line-height: normal !important;
        }

        .header .logo-container .toggle-sidebar-left {
            margin: 0 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            width: 32px !important;
            height: 32px !important;
            background: rgba(255, 255, 255, 0.1) !important;
            border: none !important;
            border-radius: 8px !important;
            color: #94a3b8 !important;
            font-size: 15px !important;
            cursor: pointer !important;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
            padding: 0 !important;
            flex-shrink: 0 !important;
        }

        .header .logo-container .toggle-sidebar-left:hover,
        .header .logo-container .toggle-sidebar-left:active {
            background: rgba(14, 165, 233, 0.2) !important;
            color: #38bdf8 !important;
            transform: scale(1.08) !important;
            box-shadow: 0 0 12px rgba(14, 165, 233, 0.15) !important;
        }

        .inner-wrapper {
            padding-top: 50px !important;
        }
    }

    /* Header Glassmorphism Effect */
    html.modern .header {
        background: rgba(255, 255, 255, 0.75) !important;
        backdrop-filter: blur(12px) !important;
        -webkit-backdrop-filter: blur(12px) !important;
        border-bottom: 1px solid rgba(226, 232, 240, 0.8) !important;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02) !important;
    }

    .header .logo-container {
        background-color: rgba(29, 33, 39, 0.85) !important;
        backdrop-filter: blur(12px) !important;
        -webkit-backdrop-filter: blur(12px) !important;
        border-bottom-color: rgba(22, 26, 30, 0.3) !important;
    }

    /* ========== Mobile Sidebar Header with Close Button ========== */
    .sidebar-header-mobile {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 16px;
        background: #0b1120;
        border-bottom: 1px solid rgba(56, 189, 248, 0.12);
    }

    .sidebar-mobile-title {
        font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important;
        font-size: 12.5px;
        font-weight: 700;
        color: #e2e8f0;
        letter-spacing: 0.3px;
        text-transform: uppercase;
    }

    .sidebar-mobile-close {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(239, 68, 68, 0.1);
        border: 1.5px solid rgba(239, 68, 68, 0.25);
        border-radius: 8px;
        color: #f87171;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        outline: none;
        padding: 0;
    }

    .sidebar-mobile-close:hover,
    .sidebar-mobile-close:active {
        background: rgba(239, 68, 68, 0.2);
        border-color: rgba(239, 68, 68, 0.5);
        color: #ef4444;
        transform: rotate(90deg) scale(1.1);
        box-shadow: 0 0 12px rgba(239, 68, 68, 0.2);
    }

    /* Hide sidebar-header-mobile on desktop (md+) */
    @media (min-width: 768px) {
        .sidebar-header-mobile {
            display: none !important;
        }
    }

    /* ========== Global Modern Table & DataTables Styling ========== */
    .table-card-container, 
    .card-modern-table {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
        overflow: hidden;
        margin-bottom: 25px;
    }

    .table-top-bar {
        padding: 16px 20px;
        background: #f8fafc;
        border-bottom: 1px solid #cbd5e1;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .table-top-title {
        font-size: 16px;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Clean Table with Clear Row Separation */
    #kt_table_1,
    table.table {
        width: 100% !important;
        margin-bottom: 0 !important;
        border-collapse: collapse !important;
    }

    #kt_table_1 thead th,
    table.table thead th {
        background-color: #f1f5f9 !important;
        color: #334155 !important;
        font-weight: 700 !important;
        font-size: 12.5px !important;
        text-transform: uppercase !important;
        letter-spacing: 0.5px !important;
        border: 1px solid #cbd5e1 !important;
        padding: 12px 14px !important;
        vertical-align: middle !important;
        text-align: center;
    }

    #kt_table_1 tbody td,
    table.table tbody td {
        padding: 11px 14px !important;
        vertical-align: middle !important;
        border: 1px solid #e2e8f0 !important;
        font-size: 13px !important;
        color: #1e293b;
    }

    /* Jelas Perbedaan Setiap Baris Item (Zebra Striping Kontras) */
    #kt_table_1 tbody tr:nth-of-type(odd),
    table.table-striped tbody tr:nth-of-type(odd) {
        background-color: #ffffff !important;
    }

    #kt_table_1 tbody tr:nth-of-type(even),
    table.table-striped tbody tr:nth-of-type(even) {
        background-color: #f8fafc !important;
    }

    /* Highlight Row Saat Diarahkan Kursor */
    #kt_table_1 tbody tr:hover,
    table.table tbody tr:hover {
        background-color: #e0f2fe !important;
    }

    #kt_table_1 tbody tr:hover td,
    table.table tbody tr:hover td {
        background-color: transparent !important;
        color: #0f172a !important;
    }

    .dataTables_wrapper {
        padding: 12px 0 !important;
        font-family: 'Poppins', sans-serif !important;
    }

    .dataTables_wrapper .dataTables_length {
        margin-bottom: 8px;
    }

    .dataTables_wrapper .dataTables_length label {
        font-size: 12px !important;
        font-weight: 500 !important;
        color: #64748b !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 6px !important;
    }

    .dataTables_wrapper .dataTables_length select {
        border-radius: 6px !important;
        border: 1px solid #cbd5e1 !important;
        padding: 3px 8px !important;
        height: 30px !important;
        font-size: 12px !important;
        font-weight: 500 !important;
        color: #334155 !important;
        background-color: #ffffff !important;
        outline: none !important;
        transition: border-color 0.15s ease !important;
    }

    .dataTables_wrapper .dataTables_length select:focus {
        border-color: #2563eb !important;
        box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.15) !important;
    }

    .dataTables_wrapper .dataTables_filter {
        margin-bottom: 8px;
    }

    .dataTables_wrapper .dataTables_filter label {
        font-size: 12px !important;
        font-weight: 500 !important;
        color: #64748b !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 6px !important;
    }

    .dataTables_wrapper .dataTables_filter input {
        border-radius: 6px !important;
        border: 1px solid #cbd5e1 !important;
        padding: 4px 10px !important;
        height: 30px !important;
        font-size: 12px !important;
        color: #0f172a !important;
        background-color: #ffffff !important;
        outline: none !important;
        min-width: 180px !important;
        transition: border-color 0.15s ease !important;
    }

    .dataTables_wrapper .dataTables_filter input:focus {
        border-color: #2563eb !important;
        box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.15) !important;
    }

    .dataTables_wrapper .dataTables_info {
        font-size: 12px !important;
        font-weight: 500 !important;
        color: #64748b !important;
        padding-top: 8px !important;
    }

    /* ==========================================================================
       SIMPLE & COMPACT SAAS PAGINATION (DATATABLES & BOOTSTRAP PAGINATION)
       ========================================================================== */
    .dataTables_wrapper .dataTables_paginate {
        padding-top: 6px !important;
        display: flex !important;
        justify-content: flex-end !important;
    }

    /* Base Pagination List */
    ul.pagination,
    .dataTables_wrapper .dataTables_paginate ul.pagination {
        display: flex !important;
        flex-wrap: wrap !important;
        align-items: center !important;
        gap: 4px !important;
        margin: 0 !important;
        padding: 0 !important;
        list-style: none !important;
    }

    /* Clear Bootstrap default merged border styles */
    ul.pagination .page-item,
    .dataTables_wrapper .dataTables_paginate .page-item,
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        margin: 0 !important;
        padding: 0 !important;
        list-style: none !important;
        display: inline-block !important;
    }

    ul.pagination .page-item:first-child .page-link,
    ul.pagination .page-item:last-child .page-link,
    .dataTables_wrapper .dataTables_paginate .page-item:first-child .page-link,
    .dataTables_wrapper .dataTables_paginate .page-item:last-child .page-link {
        border-radius: 6px !important;
    }

    /* Simple & Compact Page Tile / Link */
    ul.pagination .page-link,
    .dataTables_wrapper .dataTables_paginate .page-link,
    .dataTables_wrapper .dataTables_paginate a.paginate_button,
    .dataTables_wrapper .dataTables_paginate span.paginate_button {
        min-width: 30px !important;
        height: 30px !important;
        padding: 0 8px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        font-family: 'Poppins', sans-serif !important;
        font-size: 12px !important;
        font-weight: 500 !important;
        color: #475569 !important;
        background: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 6px !important;
        text-decoration: none !important;
        transition: all 0.15s ease !important;
        margin: 0 !important;
        user-select: none !important;
        cursor: pointer !important;
    }

    /* Hover State (Non-active, non-disabled) */
    ul.pagination .page-item:not(.active):not(.disabled) .page-link:hover,
    .dataTables_wrapper .dataTables_paginate .page-item:not(.active):not(.disabled) .page-link:hover,
    .dataTables_wrapper .dataTables_paginate a.paginate_button:not(.current):not(.disabled):hover {
        background: #f8fafc !important;
        border-color: #cbd5e1 !important;
        color: #2563eb !important;
        z-index: 2 !important;
    }

    /* Active / Current Page State */
    ul.pagination .page-item.active .page-link,
    .dataTables_wrapper .dataTables_paginate .page-item.active .page-link,
    .dataTables_wrapper .dataTables_paginate .paginate_button.current,
    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
        background: #2563eb !important;
        border-color: #2563eb !important;
        color: #ffffff !important;
        font-weight: 600 !important;
        box-shadow: 0 2px 4px rgba(37, 99, 235, 0.25) !important;
        z-index: 3 !important;
    }

    /* Disabled State */
    ul.pagination .page-item.disabled .page-link,
    .dataTables_wrapper .dataTables_paginate .page-item.disabled .page-link,
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled,
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover {
        background: #ffffff !important;
        border-color: #f1f5f9 !important;
        color: #cbd5e1 !important;
        opacity: 0.6 !important;
        cursor: not-allowed !important;
        pointer-events: none !important;
    }

    /* Previous & Next Special Spacing */
    ul.pagination .page-item.previous .page-link,
    ul.pagination .page-item.next .page-link,
    .dataTables_wrapper .dataTables_paginate .page-item.previous .page-link,
    .dataTables_wrapper .dataTables_paginate .page-item.next .page-link,
    .dataTables_wrapper .dataTables_paginate a.paginate_button.previous,
    .dataTables_wrapper .dataTables_paginate a.paginate_button.next {
        padding: 0 10px !important;
        font-weight: 500 !important;
        font-size: 12px !important;
    }

    /* Standalone Ellipsis (...) styling */
    ul.pagination .page-item.disabled span.page-link,
    .dataTables_wrapper .dataTables_paginate span.ellipsis {
        border: 1px solid transparent !important;
        background: transparent !important;
        color: #94a3b8 !important;
        min-width: 24px !important;
    }

    @media (max-width: 767px) {
        .dataTables_wrapper .dataTables_paginate {
            justify-content: center !important;
            margin-top: 8px !important;
        }

        .dataTables_wrapper .dataTables_info {
            text-align: center !important;
        }

        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            text-align: center !important;
        }
        
        .dataTables_wrapper .dataTables_filter input {
            width: 100% !important;
            min-width: 0 !important;
        }
    }

    /* ========== Global Surat Action Buttons & UI Enhancement ========== */
    /* Surat Document Card Container (Clean Subtle Paper) */
    .col-xl-10 > .card-body[style*="background-color:#FFFFFF"],
    .col-xl-10 > .card-body[style*="background-color: #FFFFFF"],
    .col-xl-10 > .card-body[style*="background:#FFFFFF"],
    .card-body[style*="padding:5%"],
    .card-body[style*="padding: 5%"] {
        background: #ffffff !important;
        border-radius: 8px !important;
        border: 1px solid #e2e8f0 !important;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.06), 0 1px 2px 0 rgba(0, 0, 0, 0.04) !important;
        margin-bottom: 25px !important;
    }

    /* Surat Top Status Badges (Clean Crisp Badges) */
    .card-body .text-right > .btn,
    .card-body .text-right > span.btn,
    .card-body .text-right > button.btn {
        border-radius: 4px !important;
        font-size: 12px !important;
        font-weight: 600 !important;
        padding: 5px 14px !important;
        letter-spacing: 0.3px !important;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05) !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        cursor: default !important;
        border: none !important;
        color: #ffffff !important;
    }

    .card-body .text-right > .btn-warning,
    .card-body .text-right > span.btn-warning {
        background-color: #f59e0b !important;
        color: #ffffff !important;
    }

    .card-body .text-right > .btn-success,
    .card-body .text-right > span.btn-success {
        background-color: #22c55e !important;
        color: #ffffff !important;
    }

    .card-body .text-right > .btn-danger,
    .card-body .text-right > span.btn-danger {
        background-color: #ef4444 !important;
        color: #ffffff !important;
    }

    .card-body .text-right > .btn-secondary,
    .card-body .text-right > span.btn-secondary {
        background-color: #64748b !important;
        color: #ffffff !important;
    }

    /* Surat Bottom Action Toolbar Container */
    div[width="100%"],
    div[role="document"] {
        width: 100% !important;
        padding-top: 16px !important;
        margin-top: 20px !important;
        border-top: 1px solid #e2e8f0 !important;
        display: block !important;
        clear: both !important;
    }

    div[width="100%"]::after,
    div[role="document"]::after {
        content: "" !important;
        display: table !important;
        clear: both !important;
    }

    /* Modern Clean Surat Buttons */
    div[width="100%"] .btn,
    div[width="100%"] a.btn,
    div[role="document"] .btn,
    div[role="document"] a.btn,
    .card-body div > .btn-approval,
    .card-body div > .btn-denial,
    .card-body div > .btn-clear-form,
    .card-body div > .btn-ajukan,
    .card-body div > a[href*="print_page"],
    .card-body div > a[href*="print"],
    .card-body div > button[onclick*="openAdminEditModal"] {
        border-radius: 6px !important;
        font-weight: 500 !important;
        font-size: 13px !important;
        padding: 7px 16px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        line-height: 1.4 !important;
        transition: all 0.15s ease-in-out !important;
        outline: none !important;
        text-decoration: none !important;
        vertical-align: middle !important;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
    }

    /* Alignment & Spacing */
    div[width="100%"] .float-left,
    div[role="document"] .float-left {
        float: left !important;
        margin-right: 10px !important;
        margin-left: 0 !important;
        margin-top: 6px !important;
        margin-bottom: 6px !important;
    }

    div[width="100%"] .float-right,
    div[role="document"] .float-right {
        float: right !important;
        margin-left: 10px !important;
        margin-right: 0 !important;
        margin-top: 6px !important;
        margin-bottom: 6px !important;
    }

    /* Icon Spacing */
    div[width="100%"] .btn i,
    div[width="100%"] a.btn i,
    div[role="document"] .btn i,
    div[role="document"] a.btn i {
        margin-right: 6px !important;
        font-size: 12px !important;
    }

    /* 1. Setujui / Ajukan Button (Fresh Green) */
    .btn-approval,
    .btn-ajukan,
    div[width="100%"] .btn-success,
    div[role="document"] .btn-success {
        background-color: #22c55e !important;
        color: #ffffff !important;
        border: 1px solid #16a34a !important;
    }

    .btn-approval:hover,
    .btn-ajukan:hover,
    div[width="100%"] .btn-success:hover,
    div[role="document"] .btn-success:hover {
        background-color: #16a34a !important;
        border-color: #15803d !important;
        color: #ffffff !important;
    }

    /* 2. Tolak Button (Fresh Red) */
    .btn-denial,
    div[width="100%"] .btn-denial,
    div[width="100%"] .btn-danger,
    div[role="document"] .btn-denial,
    div[role="document"] .btn-danger {
        background-color: #ef4444 !important;
        color: #ffffff !important;
        border: 1px solid #dc2626 !important;
    }

    .btn-denial:hover,
    div[width="100%"] .btn-denial:hover,
    div[width="100%"] .btn-danger:hover,
    div[role="document"] .btn-denial:hover,
    div[role="document"] .btn-danger:hover {
        background-color: #dc2626 !important;
        border-color: #b91c1c !important;
        color: #ffffff !important;
    }

    /* 3. Cetak Button (Fresh Amber Gold) */
    a[href*="print_page"],
    a[href*="print_custom"],
    div[width="100%"] a[href*="print"],
    div[width="100%"] .btn-warning:not([onclick*="openAdminEditModal"]),
    div[role="document"] a[href*="print"],
    div[role="document"] .btn-warning {
        background-color: #f59e0b !important;
        color: #ffffff !important;
        border: 1px solid #d97706 !important;
    }

    a[href*="print_page"]:hover,
    a[href*="print_custom"]:hover,
    div[width="100%"] a[href*="print"]:hover,
    div[width="100%"] .btn-warning:not([onclick*="openAdminEditModal"]):hover,
    div[role="document"] a[href*="print"]:hover,
    div[role="document"] .btn-warning:hover {
        background-color: #d97706 !important;
        border-color: #b45309 !important;
        color: #ffffff !important;
    }

    /* 4. Edit Data (Admin) Button (Warm Orange) */
    button[onclick*="openAdminEditModal"],
    .btn-admin-edit {
        background-color: #f97316 !important;
        color: #ffffff !important;
        border: 1px solid #ea580c !important;
    }

    button[onclick*="openAdminEditModal"]:hover,
    .btn-admin-edit:hover {
        background-color: #ea580c !important;
        border-color: #c2410c !important;
        color: #ffffff !important;
    }

    /* 5. Kembali Button (Clean Slate) */
    .btn-clear-form,
    button[onclick*="goBack"],
    div[width="100%"] .btn-secondary.btn-clear-form,
    div[role="document"] .btn-secondary.btn-clear-form {
        background-color: #64748b !important;
        color: #ffffff !important;
        border: 1px solid #475569 !important;
    }

    .btn-clear-form:hover,
    button[onclick*="goBack"]:hover,
    div[width="100%"] .btn-secondary.btn-clear-form:hover,
    div[role="document"] .btn-secondary.btn-clear-form:hover {
        background-color: #475569 !important;
        border-color: #334155 !important;
        color: #ffffff !important;
    }

    /* 6. Lampiran Button (Sky Blue) */
    div[width="100%"] a[href*="uploads"],
    div[width="100%"] a[href*="lampiran"],
    div[width="100%"] .btn-primary {
        background-color: #0284c7 !important;
        color: #ffffff !important;
        border: 1px solid #0369a1 !important;
    }

    div[width="100%"] a[href*="uploads"]:hover,
    div[width="100%"] a[href*="lampiran"]:hover,
    div[width="100%"] .btn-primary:hover {
        background-color: #0369a1 !important;
        border-color: #075985 !important;
        color: #ffffff !important;
    }
</style>