<style>
    /* Modern Executive Employee Profile Styles */
    :root {
        --prof-primary: #2563eb;
        --prof-indigo: #4f46e5;
        --prof-navy: #0f172a;
        --prof-success: #059669;
        --prof-warning: #d97706;
        --prof-danger: #e11d48;
        --prof-info: #0284c7;
        --card-shadow: 0 6px 24px -4px rgba(15, 23, 42, 0.06), 0 2px 6px -1px rgba(15, 23, 42, 0.03);
        --card-hover-shadow: 0 12px 32px -6px rgba(15, 23, 42, 0.11);
    }

    .employee-profile-container {
        padding: 6px 0 40px 0;
        font-family: 'Poppins', -apple-system, BlinkMacSystemFont, sans-serif;
    }

    /* Profile Header Hero Card */
    .profile-header-card {
        background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 52%, #2563eb 100%);
        border-radius: 20px;
        padding: 28px 30px;
        color: #ffffff;
        box-shadow: 0 14px 36px -8px rgba(30, 58, 138, 0.35);
        margin-bottom: 22px;
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .profile-header-card::before {
        content: '';
        position: absolute;
        top: -45%;
        right: -12%;
        width: 420px;
        height: 420px;
        background: radial-gradient(circle, rgba(56, 189, 248, 0.22) 0%, transparent 68%);
        pointer-events: none;
    }

    .profile-header-card::after {
        content: '';
        position: absolute;
        bottom: -55%;
        left: 8%;
        width: 340px;
        height: 340px;
        background: radial-gradient(circle, rgba(99, 102, 241, 0.22) 0%, transparent 70%);
        pointer-events: none;
    }

    .profile-hero-topbar {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 20px;
        padding-bottom: 14px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.12);
    }

    .profile-hero-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 4px 12px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.2);
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.6px;
        text-transform: uppercase;
        color: #bfdbfe;
    }

    .profile-hero-back {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.22);
        color: #ffffff !important;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none !important;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .profile-hero-back:hover {
        background: rgba(255, 255, 255, 0.22);
        transform: translateY(-1px);
    }

    .profile-hero-body {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: flex-start;
        gap: 28px;
    }

    .profile-photo-col {
        flex-shrink: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .profile-photo-wrapper {
        position: relative;
        width: 144px;
        height: 144px;
        margin-bottom: 10px;
    }

    .profile-photo {
        width: 144px;
        height: 144px;
        border-radius: 22px;
        object-fit: cover;
        border: 3px solid rgba(255, 255, 255, 0.55);
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.32);
        transition: transform 0.25s ease;
    }

    .profile-photo:hover {
        transform: scale(1.03);
    }

    .profile-photo-placeholder {
        width: 144px;
        height: 144px;
        border-radius: 22px;
        background: rgba(255, 255, 255, 0.15);
        display: flex;
        align-items: center;
        justify-content: center;
        border: 3px solid rgba(255, 255, 255, 0.3);
        backdrop-filter: blur(10px);
    }

    .profile-photo-placeholder i {
        font-size: 62px;
        color: rgba(255, 255, 255, 0.72);
    }

    .employee-status-badge {
        position: absolute;
        bottom: -8px;
        left: 50%;
        transform: translateX(-50%);
        padding: 4px 12px;
        border-radius: 999px;
        font-size: 10.5px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border: 2px solid #ffffff;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.25);
        white-space: nowrap;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .status-active {
        background: #059669;
        color: #ffffff;
    }

    .status-inactive {
        background: #e11d48;
        color: #ffffff;
    }

    .profile-info-col {
        flex: 1 1 auto;
        min-width: 0;
    }

    .profile-name {
        font-size: 26px;
        font-weight: 800;
        margin: 0 0 4px 0;
        letter-spacing: -0.02em;
        color: #ffffff;
        line-height: 1.25;
    }

    .profile-position {
        font-size: 14.5px;
        color: #dbeafe;
        font-weight: 600;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
    }

    .profile-badges-row {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 16px;
    }

    .profile-npp {
        font-size: 12px;
        font-weight: 700;
        color: #ffffff;
        background: rgba(255, 255, 255, 0.14);
        padding: 5px 12px;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.22);
    }

    .profile-npp.badge-highlight {
        background: rgba(16, 185, 129, 0.22);
        border-color: rgba(52, 211, 153, 0.45);
        color: #d1fae5;
    }

    /* Uniform 4-Column Quick Meta Grid */
    .profile-meta-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 10px;
        margin-bottom: 10px;
    }

    .quick-info-item {
        text-align: left;
        padding: 11px 14px;
        background: rgba(15, 23, 42, 0.34);
        border-radius: 13px;
        backdrop-filter: blur(8px);
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.16);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-width: 0;
    }

    .quick-info-head {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 10.5px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #93c5fd;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .quick-info-value {
        font-size: 13px;
        font-weight: 700;
        color: #ffffff;
        line-height: 1.35;
        word-break: break-word;
        overflow-wrap: anywhere;
    }

    .quick-info-sub {
        font-size: 11px;
        color: #cbd5e1;
        margin-top: 2px;
        font-weight: 500;
    }

    /* Dedicated 2-Column Account & Supervision Row */
    .profile-account-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
    }

    .quick-account-card {
        padding: 12px 15px;
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.24);
        border-radius: 13px;
        backdrop-filter: blur(10px);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        min-width: 0;
    }

    .quick-account-main {
        min-width: 0;
        flex: 1 1 auto;
    }

    .quick-account-btn {
        flex-shrink: 0;
        background: #ffffff;
        color: #1e3a8a !important;
        border-radius: 9px;
        padding: 6px 11px;
        font-size: 11.5px;
        font-weight: 800;
        text-decoration: none !important;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.14);
        transition: all 0.18s ease;
        white-space: nowrap;
    }

    .quick-account-btn:hover {
        background: #eff6ff;
        transform: translateY(-1px);
    }

    /* Leaderboard Rank Spotlight in Hero */
    .profile-npp.badge-rank-gold {
        background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
        border-color: #fde68a;
        color: #451a03;
        text-decoration: none !important;
        box-shadow: 0 4px 14px rgba(245, 158, 11, 0.4);
    }

    .profile-npp.badge-rank-top {
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.28) 0%, rgba(234, 88, 12, 0.34) 100%);
        border-color: rgba(251, 191, 36, 0.65);
        color: #fef3c7;
        text-decoration: none !important;
    }

    .profile-npp.badge-rank-link:hover {
        transform: translateY(-1px);
        filter: brightness(1.06);
    }

    .profile-rank-spotlight {
        margin-top: 10px;
        padding: 13px 16px;
        border-radius: 14px;
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.52) 0%, rgba(30, 58, 138, 0.56) 100%);
        border: 1px solid rgba(251, 191, 36, 0.42);
        backdrop-filter: blur(10px);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }

    .rank-spotlight-left {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
    }

    .rank-spotlight-medal {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: linear-gradient(135deg, #fbbf24 0%, #ea580c 100%);
        color: #ffffff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 19px;
        flex-shrink: 0;
        box-shadow: 0 6px 16px rgba(245, 158, 11, 0.38);
    }

    .rank-spotlight-medal.is-muted {
        background: rgba(148, 163, 184, 0.25);
        color: #cbd5e1;
        box-shadow: none;
    }

    .rank-spotlight-title {
        font-size: 14.5px;
        font-weight: 800;
        color: #ffffff;
        line-height: 1.3;
    }

    .rank-spotlight-sub {
        font-size: 11.5px;
        color: #cbd5e1;
        margin-top: 2px;
    }

    .rank-spotlight-stats {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
    }

    .rank-stat-chip {
        padding: 5px 11px;
        border-radius: 9px;
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.18);
        font-size: 12px;
        font-weight: 700;
        color: #f8fafc;
        white-space: nowrap;
    }

    /* Segmented Tab Navigation */
    .profile-tabs {
        background: #f1f5f9;
        border: 1px solid #cbd5e1;
        border-radius: 15px;
        padding: 6px;
        box-shadow: inset 0 1px 2px rgba(15, 23, 42, 0.04);
        margin-bottom: 22px;
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
    }

    .profile-tabs .nav-item {
        margin-bottom: 0;
    }

    .profile-tabs .nav-link {
        border: 1px solid transparent;
        border-radius: 10px;
        padding: 10px 16px;
        font-weight: 700;
        font-size: 13px;
        color: #475569;
        transition: all 0.2s cubic-bezier(0.22, 1, 0.36, 1);
        display: inline-flex;
        align-items: center;
        gap: 7px;
        background: transparent;
    }

    .profile-tabs .nav-link:hover {
        background: #ffffff;
        color: #0f172a;
        border-color: #cbd5e1;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.05);
    }

    .profile-tabs .nav-link.active {
        background: linear-gradient(135deg, #2563eb 0%, #4f46e5 100%);
        color: #ffffff !important;
        box-shadow: 0 6px 16px -3px rgba(37, 99, 235, 0.42);
    }

    .profile-tabs .nav-link.active .tab-pill-count {
        background: rgba(255, 255, 255, 0.24);
        color: #ffffff;
    }

    .tab-pill-count {
        font-size: 11px;
        font-weight: 800;
        padding: 2px 8px;
        border-radius: 999px;
        background: #e2e8f0;
        color: #334155;
        line-height: 1.3;
    }

    /* Content Cards */
    .profile-content-card {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 18px;
        box-shadow: var(--card-shadow);
        overflow: hidden;
        margin-bottom: 22px;
    }

    .card-header-custom {
        background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
        padding: 16px 24px;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
    }

    .card-header-custom h5 {
        margin: 0;
        font-weight: 800;
        font-size: 1.05rem;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .card-header-icon {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        color: #2563eb;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
    }

    .card-body-custom {
        padding: 24px;
    }

    .profile-section-label {
        font-size: 11.5px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: #2563eb;
        margin: 6px 0 14px 0;
        padding-bottom: 6px;
        border-bottom: 1.5px solid #eff6ff;
        display: flex;
        align-items: center;
        gap: 7px;
    }

    /* Form Styles & Height Normalization */
    .form-group-modern {
        margin-bottom: 16px;
    }

    .form-group-modern label {
        font-weight: 700;
        color: #475569;
        font-size: 11.5px;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin-bottom: 6px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .form-group-modern label i {
        color: #64748b;
    }

    .form-group-modern .form-control {
        border: 1.5px solid #cbd5e1;
        border-radius: 10px;
        padding: 9px 13px;
        font-size: 13.5px;
        min-height: 43px;
        transition: border-color 0.18s ease, box-shadow 0.18s ease;
        background: #ffffff;
        color: #0f172a;
        font-weight: 500;
    }

    .form-group-modern select.form-control {
        height: 43px !important;
        padding: 6px 12px !important;
    }

    .form-group-modern textarea.form-control {
        min-height: 92px;
        line-height: 1.5;
    }

    .form-group-modern .form-control:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.13);
        background: #ffffff;
    }

    .form-group-modern .input-group-text {
        border: 1.5px solid #cbd5e1;
        background: #f8fafc;
        color: #475569;
        font-size: 12.5px;
        font-weight: 700;
        border-radius: 10px;
    }

    .form-group-modern .input-group-prepend .input-group-text {
        border-right: none;
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
    }

    .form-group-modern .input-group > .form-control:not(:first-child) {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }

    .form-group-modern .input-group > .form-control:not(:last-child) {
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
    }

    .form-group-modern .input-group-append .btn {
        border: 1.5px solid #cbd5e1;
        border-left: none;
        background: #f8fafc;
        color: #475569;
        padding: 0 13px;
        font-weight: 600;
    }

    .form-group-modern .input-group-append .btn:hover {
        background: #eff6ff;
        color: #2563eb;
    }

    .form-group-modern .input-group-append .btn:last-child {
        border-top-right-radius: 10px;
        border-bottom-right-radius: 10px;
    }

    /* Select2 alignment inside form-group-modern */
    .form-group-modern .select2-container--default .select2-selection--single {
        height: 43px !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 10px !important;
        padding: 6px 10px !important;
        display: flex;
        align-items: center;
    }

    .form-group-modern .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #0f172a !important;
        font-size: 13.5px;
        font-weight: 500;
        padding-left: 2px !important;
    }

    .form-group-modern .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 41px !important;
        right: 8px !important;
    }

    /* Modern Benefit Checkbox Cards */
    .benefit-summary-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
        padding: 12px 16px;
        border-radius: 12px;
        background: linear-gradient(135deg, #eff6ff 0%, #f8fafc 100%);
        border: 1px solid #bfdbfe;
        margin-bottom: 18px;
        font-size: 13px;
        color: #1e3a8a;
    }

    .benefit-item-card {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 15px;
        background: #ffffff;
        border-radius: 13px;
        border: 1.5px solid #e2e8f0;
        margin-bottom: 12px;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        position: relative;
        user-select: none;
    }

    .benefit-item-card:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        transform: translateY(-1px);
        box-shadow: 0 6px 14px rgba(15, 23, 42, 0.05);
    }

    .benefit-item-card.is-active {
        background: linear-gradient(135deg, #eff6ff 0%, #f8fafc 100%);
        border-color: #3b82f6;
        box-shadow: 0 6px 16px rgba(59, 130, 246, 0.12);
    }

    .benefit-item-card .benefit-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .benefit-item-card .benefit-icon {
        width: 38px;
        height: 38px;
        border-radius: 11px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        flex-shrink: 0;
        transition: all 0.2s ease;
    }

    .benefit-item-card.is-active .benefit-icon {
        transform: scale(1.06);
    }

    .benefit-icon-jabatan { background: #eff6ff; color: #2563eb; }
    .benefit-icon-tt { background: #ecfdf5; color: #059669; }
    .benefit-icon-konsumsi { background: #fffbeb; color: #d97706; }
    .benefit-icon-kinerja { background: #f5f3ff; color: #7c3aed; }
    .benefit-icon-komunikasi { background: #fff1f2; color: #e11d48; }
    .benefit-icon-transportasi { background: #eef2ff; color: #4f46e5; }
    .benefit-icon-bbm { background: #f0fdfa; color: #0d9488; }
    .benefit-icon-raya { background: #fff7ed; color: #ea580c; }
    .benefit-icon-bonus { background: #fdf2f8; color: #db2777; }

    .benefit-item-card .benefit-meta {
        display: flex;
        flex-direction: column;
    }

    .benefit-item-card .benefit-title {
        font-size: 13.5px;
        font-weight: 700;
        color: #0f172a;
        line-height: 1.25;
        letter-spacing: -0.01em;
    }

    .benefit-item-card .benefit-status-badge {
        font-size: 11px;
        font-weight: 600;
        margin-top: 2px;
        color: #94a3b8;
    }

    .benefit-item-card.is-active .benefit-status-badge {
        color: #2563eb;
    }

    /* Custom Toggle Switch */
    .custom-switch-clean {
        position: relative;
        display: inline-block;
        width: 42px;
        height: 24px;
        margin: 0;
        cursor: pointer;
    }

    .custom-switch-clean input {
        opacity: 0;
        width: 0;
        height: 0;
        position: absolute;
    }

    .custom-switch-clean .slider-clean {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #cbd5e1;
        transition: 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        border-radius: 24px;
    }

    .custom-switch-clean .slider-clean:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background-color: #ffffff;
        transition: 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        border-radius: 50%;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.22);
    }

    .custom-switch-clean input:checked + .slider-clean {
        background-color: #2563eb;
    }

    .custom-switch-clean input:focus + .slider-clean {
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.2);
    }

    .custom-switch-clean input:checked + .slider-clean:before {
        transform: translateX(18px);
    }

    /* Document Completeness Banner & Cards */
    .doc-completeness-banner {
        background: linear-gradient(135deg, #f8fafc 0%, #eff6ff 100%);
        border: 1px solid #bfdbfe;
        border-radius: 14px;
        padding: 15px 18px;
        margin-bottom: 20px;
    }

    .doc-completeness-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 10px;
    }

    .doc-completeness-track {
        height: 10px;
        border-radius: 999px;
        background: #dbeafe;
        overflow: hidden;
    }

    .doc-completeness-fill {
        height: 100%;
        border-radius: 999px;
        background: linear-gradient(90deg, #2563eb 0%, #10b981 100%);
        transition: width 0.45s ease;
    }

    .document-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(225px, 1fr));
        gap: 14px;
    }

    .document-card {
        background: #ffffff;
        border-radius: 15px;
        padding: 18px 16px;
        text-align: center;
        transition: all 0.2s ease;
        border: 1.5px solid #e2e8f0;
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: space-between;
    }

    .document-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.08);
        border-color: #3b82f6;
    }

    .document-card.has-file {
        background: linear-gradient(180deg, #f0fdf4 0%, #ffffff 65%);
        border-color: #86efac;
    }

    .document-card .doc-icon-wrap {
        width: 50px;
        height: 50px;
        border-radius: 14px;
        background: #f1f5f9;
        color: #64748b;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        margin-bottom: 10px;
    }

    .document-card.has-file .doc-icon-wrap {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: #ffffff;
        box-shadow: 0 6px 14px -3px rgba(16, 185, 129, 0.4);
    }

    .document-card .doc-title {
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 6px;
        font-size: 13.5px;
        line-height: 1.3;
    }

    .document-card .doc-status {
        font-size: 11px;
        padding: 3px 10px;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        margin-bottom: 14px;
        font-weight: 700;
    }

    .document-card.has-file .doc-status {
        background: #dcfce7;
        color: #15803d;
        border: 1px solid #86efac;
    }

    .document-card:not(.has-file) .doc-status {
        background: #fef3c7;
        color: #b45309;
        border: 1px solid #fde68a;
    }

    .document-card .doc-actions {
        display: flex;
        gap: 6px;
        justify-content: center;
        width: 100%;
    }

    .document-card .btn-doc {
        flex: 1 1 0;
        padding: 7px 10px;
        border-radius: 9px;
        font-size: 12px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
    }

    /* Salary Live Summary Grid */
    .salary-summary-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 12px;
        margin-bottom: 22px;
    }

    .salary-stat-card {
        border-radius: 14px;
        padding: 14px 16px;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
    }

    .salary-stat-card.stat-income {
        background: linear-gradient(135deg, #eff6ff 0%, #f8fafc 100%);
        border-color: #bfdbfe;
    }

    .salary-stat-card.stat-deduct {
        background: linear-gradient(135deg, #fff1f2 0%, #fff 100%);
        border-color: #fecdd3;
    }

    .salary-stat-card.stat-thp {
        background: linear-gradient(135deg, #059669 0%, #10b981 100%);
        border-color: #059669;
        color: #ffffff;
        box-shadow: 0 8px 20px -6px rgba(5, 150, 105, 0.4);
    }

    .salary-stat-card.stat-annual {
        background: linear-gradient(135deg, #fffbeb 0%, #fff 100%);
        border-color: #fde68a;
    }

    .salary-stat-label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        opacity: 0.82;
        margin-bottom: 4px;
    }

    .salary-stat-val {
        font-size: 17px;
        font-weight: 800;
        font-variant-numeric: tabular-nums;
        line-height: 1.2;
    }

    .salary-group-box {
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 18px 18px 6px 18px;
        background: #ffffff;
        margin-bottom: 18px;
    }

    /* Action Buttons */
    .btn-action-group {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        justify-content: flex-end;
        padding: 16px 24px;
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
    }

    .btn-modern {
        padding: 10px 20px;
        border-radius: 11px;
        font-weight: 700;
        font-size: 13px;
        transition: all 0.2s ease;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
    }

    .btn-modern:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(15, 23, 42, 0.14);
    }

    .btn-primary-modern {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
    }

    .btn-secondary-modern {
        background: #ffffff;
        border: 1.5px solid #cbd5e1;
        color: #475569 !important;
    }

    .btn-secondary-modern:hover {
        background: #f1f5f9;
        color: #0f172a !important;
    }

    .btn-warning-modern {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: #ffffff !important;
    }

    .btn-success-modern {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(5, 150, 105, 0.28);
    }

    /* Unified Modern Modals */
    .profile-modern-modal .modal-content,
    .modal-pdf-preview .modal-content {
        border-radius: 18px;
        overflow: hidden;
        border: none;
        box-shadow: 0 24px 70px rgba(15, 23, 42, 0.24);
    }

    .profile-modern-modal .modal-header,
    .modal-pdf-preview .modal-header {
        background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 60%, #2563eb 100%);
        color: #ffffff;
        border: none;
        padding: 16px 22px;
    }

    .profile-modern-modal.modal-amber .modal-header {
        background: linear-gradient(135deg, #d97706 0%, #ea580c 100%);
    }

    .profile-modern-modal .modal-header .close,
    .modal-pdf-preview .modal-header .close {
        color: #ffffff;
        opacity: 0.9;
        text-shadow: none;
    }

    .profile-modern-modal .modal-body {
        padding: 22px 24px;
    }

    .profile-modern-modal .modal-footer {
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
        padding: 14px 24px;
    }

    .modal-pdf-preview .modal-dialog {
        max-width: 900px;
    }

    .pdf-viewer-container {
        width: 100%;
        height: 600px;
        background: #1e293b;
    }

    .pdf-viewer-container iframe,
    .pdf-viewer-container embed {
        width: 100%;
        height: 100%;
        border: none;
    }

    .image-preview-container {
        width: 100%;
        max-height: 600px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #1e293b;
        padding: 20px;
    }

    .image-preview-container img {
        max-width: 100%;
        max-height: 560px;
        object-fit: contain;
        border-radius: 10px;
    }

    /* Loading Animation */
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.88);
        backdrop-filter: blur(4px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
    }

    .loading-overlay.hide {
        display: none;
    }

    .loading-spinner {
        width: 48px;
        height: 48px;
        border: 4px solid #e2e8f0;
        border-top-color: #2563eb;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* Signature Styles */
    .signature-container {
        padding: 12px 0;
    }

    .signature-preview-box {
        background-color: #f8fafc;
        background-image: linear-gradient(45deg, #f1f5f9 25%, transparent 25%),
                          linear-gradient(-45deg, #f1f5f9 25%, transparent 25%),
                          linear-gradient(45deg, transparent 75%, #f1f5f9 75%),
                          linear-gradient(-45deg, transparent 75%, #f1f5f9 75%);
        background-size: 20px 20px;
        background-position: 0 0, 0 10px, 10px -10px, -10px 0;
        border: 2px dashed #cbd5e1;
        border-radius: 16px;
        padding: 28px;
        min-height: 220px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }

    .signature-preview-box:hover {
        border-color: #2563eb;
    }

    .signature-image {
        max-width: 100%;
        max-height: 180px;
        object-fit: contain;
    }

    .no-signature {
        text-align: center;
        color: #94a3b8;
    }

    .no-signature i {
        font-size: 48px;
        margin-bottom: 12px;
        color: #cbd5e1;
    }

    .no-signature p {
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 0;
    }

    .signature-info {
        background: linear-gradient(135deg, #eff6ff 0%, #eef2ff 100%);
        border: 1px solid #bfdbfe;
        border-radius: 12px;
        padding: 14px 18px;
    }

    .signature-actions {
        display: flex;
        gap: 10px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .btn-danger-modern {
        background: linear-gradient(135deg, #e11d48 0%, #be123c 100%);
        color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(225, 29, 72, 0.25);
    }

    .btn-danger-modern:hover {
        box-shadow: 0 6px 16px rgba(225, 29, 72, 0.35);
    }

    /* Responsive Breakpoints */
    @media (max-width: 991px) {
        .profile-hero-body {
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: 20px;
        }

        .profile-position,
        .profile-badges-row {
            justify-content: center;
        }

        .profile-meta-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .profile-account-grid {
            grid-template-columns: 1fr;
        }

        .salary-summary-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 768px) {
        .profile-header-card {
            padding: 20px 16px;
            border-radius: 16px;
        }

        .profile-photo-wrapper,
        .profile-photo,
        .profile-photo-placeholder {
            width: 118px;
            height: 118px;
        }

        .profile-name {
            font-size: 21px;
        }

        .profile-tabs {
            flex-wrap: nowrap;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            padding: 6px;
            margin-bottom: 16px;
        }

        .profile-tabs .nav-item {
            flex: 0 0 auto;
        }

        .profile-tabs .nav-link {
            padding: 8px 13px;
            font-size: 12.5px;
            white-space: nowrap;
        }

        .card-header-custom,
        .card-body-custom,
        .btn-action-group {
            padding: 16px;
        }

        .document-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
        }

        .salary-summary-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 480px) {
        .profile-meta-grid,
        .document-grid {
            grid-template-columns: 1fr;
        }

        .quick-account-card {
            flex-direction: column;
            align-items: flex-start;
        }

        .quick-account-btn {
            width: 100%;
            justify-content: center;
        }

        .btn-action-group .btn-modern {
            width: 100%;
        }
    }
</style>

<header class="page-header">
    <h2><i class="fas fa-user-tie text-primary mr-2"></i><?= $page_title ?></h2>
    <div class="right-wrapper text-right">
        <ol class="breadcrumbs">
            <li><a href="<?= base_url('dashboard') ?>"><i class="fas fa-home"></i></a></li>
            <li><a href="<?= base_url('pengguna') ?>"><span>Data Karyawan</span></a></li>
            <li><span><?= htmlspecialchars($data_pengguna[0]->nama) ?></span></li>
        </ol>
        <a class="sidebar-right-toggle"><i class="fas fa-chevron-left"></i></a>
    </div>
</header>

<div class="employee-profile-container">
    <div class="container-fluid">
        <?php
        $emp = $data_pengguna[0];
        $pilihanAtasan = isset($list_atasan) && !empty($list_atasan) ? $list_atasan : (isset($pengguna) ? $pengguna : []);
        $curIdAtasan   = isset($emp->id_atasan) ? (int)$emp->id_atasan : 0;
        $namaAtasanProfil = '';
        $jabatanAtasanProfil = '';
        foreach ($pilihanAtasan as $rowAt) {
            if ((int)$rowAt->pengguna_id === $curIdAtasan) {
                $namaAtasanProfil = $rowAt->nama;
                $jabatanAtasanProfil = $rowAt->jabatan;
                break;
            }
        }
        $canEditAtasanProfil = ((int)sessPenggunaId() === (int)$emp->pengguna_id) || isAdmin() || isHrd() || isGa();
        $valEmailKantor      = isset($emp->email_kantor) ? trim((string)$emp->email_kantor) : '';
        $valPassEmailKantor  = isset($emp->password_email_kantor) ? (string)$emp->password_email_kantor : '';

        $masaKerjaText = '-';
        if (!empty($emp->tgl_masuk)) {
            try {
                $dtMasuk = new DateTime($emp->tgl_masuk);
                $dtNow   = new DateTime();
                if ($dtMasuk <= $dtNow) {
                    $diffMk = $dtMasuk->diff($dtNow);
                    $partsMk = [];
                    if ($diffMk->y > 0) $partsMk[] = $diffMk->y . ' Thn';
                    if ($diffMk->m > 0) $partsMk[] = $diffMk->m . ' Bln';
                    $masaKerjaText = !empty($partsMk) ? implode(' ', $partsMk) : ($diffMk->d . ' Hari');
                }
            } catch (Exception $e) {}
        }

        $benefitFields = [
            'terima_tunjangan_jabatan',
            'terima_tunjangan_tt',
            'terima_tunjangan_konsumsi',
            'terima_tunjangan_kinerja',
            'terima_tunjangan_komunikasi',
            'terima_tunjangan_transportasi',
            'terima_tunjangan_bbm',
            'terima_tunjangan_raya',
            'terima_bonus_tahunan'
        ];
        $activeBenefitsCount = 0;
        foreach ($benefitFields as $bf) {
            if (!empty($emp->$bf) && (int)$emp->$bf === 1) {
                $activeBenefitsCount++;
            }
        }

        $isSecurityStaff = in_array((int)$emp->pengguna_id, [94, 724], true)
            || stripos($emp->nama, 'boddy') !== false
            || stripos($emp->nama, 'muammar') !== false
            || stripos($emp->nama, 'muamar') !== false
            || (isset($emp->jabatan) && (stripos($emp->jabatan, 'security') !== false || stripos($emp->jabatan, 'satpam') !== false));

        $docsList = [
            ['type' => 'foto',                 'field' => 'file_foto',                 'title' => 'Pas Foto',                     'icon' => 'fas fa-camera'],
            ['type' => 'ktp',                  'field' => 'file_ktp',                  'title' => 'KTP',                          'icon' => 'fas fa-id-card'],
            ['type' => 'kk',                   'field' => 'file_kk',                   'title' => 'Kartu Keluarga',               'icon' => 'fas fa-users'],
            ['type' => 'ijazah',               'field' => 'file_ijazah',               'title' => 'Ijazah',                       'icon' => 'fas fa-graduation-cap'],
            ['type' => 'transkip',             'field' => 'file_transkip',             'title' => 'Transkip Nilai',               'icon' => 'fas fa-file-alt'],
            ['type' => 'sim',                  'field' => 'file_sim',                  'title' => 'SIM',                          'icon' => 'fas fa-car'],
            ['type' => 'bpjs',                 'field' => 'file_bpjs',                 'title' => 'Kartu BPJS Kesehatan',         'icon' => 'fas fa-heartbeat'],
            ['type' => 'bpjs_ketenagakerjaan', 'field' => 'file_bpjs_ketenagakerjaan', 'title' => 'Kartu BPJS Ketenagakerjaan',   'icon' => 'fas fa-user-shield'],
            ['type' => 'skck',                 'field' => 'file_skck',                 'title' => 'SKCK',                         'icon' => 'fas fa-shield-alt'],
            ['type' => 'no_rek',               'field' => 'file_no_rek',               'title' => 'Buku Tabungan',                'icon' => 'fas fa-piggy-bank'],
            ['type' => 'domisili',             'field' => 'file_domisili',             'title' => 'Surat Domisili',               'icon' => 'fas fa-home'],
            ['type' => 'sertifikat_vaksin',    'field' => 'file_sertifikat_vaksin',    'title' => 'Sertifikat Vaksin',            'icon' => 'fas fa-syringe'],
            ['type' => 'npwp',                 'field' => 'file_npwp',                 'title' => 'Kartu NPWP',                   'icon' => 'fas fa-file-invoice'],
            ['type' => 'passport',             'field' => 'file_passport',             'title' => 'Paspor',                       'icon' => 'fas fa-passport']
        ];

        if ($isSecurityStaff) {
            $docsList[] = [
                'type'  => 'kta',
                'field' => 'file_kta',
                'title' => 'KTA Satpam / Security',
                'icon'  => 'fas fa-id-badge'
            ];
        }
        $uploadedDocsCount = 0;
        foreach ($docsList as $dItem) {
            $fName = $dItem['field'];
            if (!empty($emp->$fName)) {
                $uploadedDocsCount++;
            }
        }
        $totalDocsCount = count($docsList);
        $docsPct = $totalDocsCount > 0 ? round(($uploadedDocsCount / $totalDocsCount) * 100) : 0;

        $kpiSummary     = isset($kpi_rank_summary) && is_array($kpi_rank_summary) ? $kpi_rank_summary : [];
        $kpiRank        = !empty($kpiSummary['rank']) ? (int)$kpiSummary['rank'] : 0;
        $kpiTotalRanked = !empty($kpiSummary['total_ranked']) ? (int)$kpiSummary['total_ranked'] : 0;
        $kpiRow         = isset($kpiSummary['row']) ? $kpiSummary['row'] : null;
        $kpiSemLabel    = !empty($kpiSummary['sem_label']) ? (string)$kpiSummary['sem_label'] : '';
        $kpiGapToAbove  = isset($kpiSummary['gap_to_above']) ? $kpiSummary['gap_to_above'] : null;
        $kpiAboveName   = !empty($kpiSummary['above_name']) ? (string)$kpiSummary['above_name'] : '';
        $lbUrl          = base_url('task_proyek?tab=leaderboard');
        ?>

        <!-- Profile Header Card -->
        <div class="profile-header-card">
            <div class="profile-hero-topbar">
                <span class="profile-hero-eyebrow">
                    <i class="fas fa-id-card-alt"></i> Profil Lengkap Karyawan
                </span>
                <button type="button" onclick="goBack()" class="profile-hero-back">
                    <i class="fas fa-arrow-left"></i> Kembali
                </button>
            </div>

            <div class="profile-hero-body">
                <div class="profile-photo-col">
                    <div class="profile-photo-wrapper">
                        <?php if ($emp->file_foto): ?>
                            <img src="<?= base_url('uploads/file_karyawan/foto/' . $emp->file_foto) ?>"
                                 alt="<?= htmlspecialchars($emp->nama) ?>"
                                 class="profile-photo btn-preview-file"
                                 data-type="foto"
                                 data-file="<?= htmlspecialchars($emp->file_foto) ?>"
                                 style="cursor: pointer;"
                                 title="Klik untuk memperbesar foto"
                                 onerror="this.onerror=null; this.parentNode.innerHTML='<div class=\'profile-photo-placeholder\'><i class=\'fas fa-user\'></i></div>';">
                        <?php else: ?>
                            <div class="profile-photo-placeholder">
                                <i class="fas fa-user"></i>
                            </div>
                        <?php endif; ?>
                        <span class="employee-status-badge <?= $emp->is_active == 1 ? 'status-active' : 'status-inactive' ?>">
                            <i class="fas <?= $emp->is_active == 1 ? 'fa-check-circle' : 'fa-times-circle' ?>"></i>
                            <?= $emp->is_active == 1 ? 'Aktif' : 'Nonaktif' ?>
                        </span>
                    </div>
                </div>

                <div class="profile-info-col">
                    <h1 class="profile-name"><?= htmlspecialchars($emp->nama) ?></h1>
                    <div class="profile-position">
                        <span><i class="fas fa-briefcase mr-1"></i> <?= htmlspecialchars($emp->jabatan ?: 'Belum ada jabatan') ?></span>
                        <?php if (!empty($emp->jabatan_visilab)): ?>
                            <span class="profile-npp" style="padding: 3px 10px; font-size: 11px;">
                                <i class="fas fa-flask"></i> VISILAB: <?= htmlspecialchars($emp->jabatan_visilab) ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <div class="profile-badges-row">
                        <span class="profile-npp">
                            <i class="fas fa-id-badge"></i> NPP: <?= htmlspecialchars($emp->no_pegawai ?: '-') ?>
                        </span>
                        <span class="profile-npp">
                            <i class="fas fa-hashtag"></i> ID: <?= (int)$emp->pengguna_id ?>
                        </span>
                        <?php if ($masaKerjaText !== '-'): ?>
                            <span class="profile-npp badge-highlight">
                                <i class="fas fa-clock"></i> Masa Kerja: <?= htmlspecialchars($masaKerjaText) ?>
                            </span>
                        <?php endif; ?>
                        <span class="profile-npp">
                            <i class="fas fa-folder-open"></i> Dokumen: <?= $uploadedDocsCount ?>/<?= $totalDocsCount ?> (<?= $docsPct ?>%)
                        </span>
                        <a href="<?= $lbUrl ?>" class="profile-npp badge-rank-link <?= $kpiRank === 1 ? 'badge-rank-gold' : ($kpiRank > 0 ? 'badge-rank-top' : '') ?>" title="Lihat posisi lengkap di Leaderboard KPI">
                            <?php if ($kpiRank === 1): ?>
                                <i class="fas fa-crown"></i> Rank #1 Leaderboard KPI
                            <?php elseif ($kpiRank > 0): ?>
                                <i class="fas fa-trophy"></i> Rank #<?= $kpiRank ?> / <?= $kpiTotalRanked ?> Leaderboard KPI
                            <?php else: ?>
                                <i class="fas fa-trophy"></i> Leaderboard KPI: Belum Terperingkat
                            <?php endif; ?>
                        </a>
                    </div>

                    <div class="profile-meta-grid">
                        <div class="quick-info-item">
                            <div class="quick-info-head"><i class="fas fa-user-tag"></i> Status Kerja</div>
                            <div class="quick-info-value"><?= htmlspecialchars(ucfirst($emp->status_karyawan ?: '-')) ?></div>
                            <div class="quick-info-sub"><?= !empty($emp->lama_training) ? 'Training: ' . htmlspecialchars($emp->lama_training) . ' bln' : 'Kepegawaian' ?></div>
                        </div>
                        <div class="quick-info-item">
                            <div class="quick-info-head"><i class="fas fa-calendar-alt"></i> Tanggal Masuk</div>
                            <div class="quick-info-value"><?= $emp->tgl_masuk ? date('d M Y', strtotime($emp->tgl_masuk)) : '-' ?></div>
                            <div class="quick-info-sub"><?= $emp->tgl_kontrak ? 'Kontrak: ' . date('d M Y', strtotime($emp->tgl_kontrak)) : 'Masa kerja ' . htmlspecialchars($masaKerjaText) ?></div>
                        </div>
                        <div class="quick-info-item">
                            <div class="quick-info-head"><i class="fas fa-envelope"></i> Email Pribadi</div>
                            <div class="quick-info-value"><?= htmlspecialchars($emp->email ?: '-') ?></div>
                            <div class="quick-info-sub">Kontak pribadi</div>
                        </div>
                        <div class="quick-info-item">
                            <div class="quick-info-head"><i class="fas fa-phone-alt"></i> No. HP</div>
                            <div class="quick-info-value"><?= htmlspecialchars($emp->no_hp ?: '-') ?></div>
                            <div class="quick-info-sub"><?= !empty($emp->no_hp_kantor) ? 'Kantor: ' . htmlspecialchars($emp->no_hp_kantor) : 'Pribadi' ?></div>
                        </div>
                    </div>

                    <div class="profile-account-grid">
                        <div class="quick-account-card">
                            <div class="quick-account-main">
                                <div class="quick-info-head" style="color: #fde68a;"><i class="fas fa-envelope-open-text"></i> Akun Email Kantor</div>
                                <div class="quick-info-value">
                                    <?= !empty($valEmailKantor) ? htmlspecialchars($valEmailKantor) : '<span style="opacity:0.75;font-weight:500;">Belum diatur</span>' ?>
                                </div>
                                <?php if ($canEditAtasanProfil && !empty($valPassEmailKantor)): ?>
                                    <div style="margin-top:4px; font-size:11.5px; display:flex; align-items:center; gap:6px; flex-wrap:wrap; color:#e2e8f0;">
                                        <span style="opacity:0.85;">Pass:</span>
                                        <span id="headerPassEmailMask" style="font-family:monospace; letter-spacing:1px;">••••••••</span>
                                        <span id="headerPassEmailReal" style="font-family:monospace; display:none; background:rgba(15,23,42,0.45); padding:1px 7px; border-radius:5px;"><?= htmlspecialchars($valPassEmailKantor) ?></span>
                                        <a href="javascript:;" onclick="toggleHeaderPassEmail()" class="badge badge-light" style="color:#1e3a8a; font-size:10px; padding:3px 6px;" title="Lihat / Sembunyikan Password">
                                            <i class="fas fa-eye" id="iconHeaderPassEmail"></i>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <?php if ($canEditAtasanProfil): ?>
                                <a href="javascript:;" data-toggle="modal" data-target="#modalEmailKantorProfil" class="quick-account-btn">
                                    <i class="fas fa-edit"></i> <?= !empty($valEmailKantor) ? 'Ubah' : 'Atur' ?>
                                </a>
                            <?php endif; ?>
                        </div>

                        <div class="quick-account-card">
                            <div class="quick-account-main">
                                <div class="quick-info-head" style="color: #a7f3d0;"><i class="fas fa-user-shield"></i> Atasan Langsung</div>
                                <div class="quick-info-value">
                                    <?= !empty($namaAtasanProfil) ? htmlspecialchars($namaAtasanProfil) : '<span style="opacity:0.75;font-weight:500;">Belum diatur</span>' ?>
                                </div>
                                <div class="quick-info-sub">
                                    <?= !empty($jabatanAtasanProfil) ? htmlspecialchars($jabatanAtasanProfil) : 'Alur penugasan & evaluasi' ?>
                                </div>
                            </div>
                            <?php if ($canEditAtasanProfil): ?>
                                <a href="javascript:;" data-toggle="modal" data-target="#modalPilihAtasanProfil" class="quick-account-btn">
                                    <i class="fas fa-user-edit"></i> <?= !empty($namaAtasanProfil) ? 'Ubah' : 'Pilih' ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Leaderboard KPI Rank Spotlight -->
                    <div class="profile-rank-spotlight">
                        <div class="rank-spotlight-left">
                            <div class="rank-spotlight-medal <?= $kpiRank <= 0 ? 'is-muted' : '' ?>">
                                <?php if ($kpiRank === 1): ?>
                                    <i class="fas fa-crown"></i>
                                <?php elseif ($kpiRank > 0): ?>
                                    <i class="fas fa-trophy"></i>
                                <?php else: ?>
                                    <i class="fas fa-chart-line"></i>
                                <?php endif; ?>
                            </div>
                            <div style="min-width: 0; text-align: left;">
                                <?php if ($kpiRank > 0 && $kpiRow !== null): ?>
                                    <div class="rank-spotlight-title">
                                        Peringkat #<?= $kpiRank ?> dari <?= $kpiTotalRanked ?> Karyawan &bull; <?= htmlspecialchars($kpiSemLabel) ?>
                                    </div>
                                    <div class="rank-spotlight-sub">
                                        <?php if ($kpiRank === 1): ?>
                                            Memimpin klasemen KPI perusahaan saat ini!
                                        <?php elseif ($kpiGapToAbove !== null): ?>
                                            Selisih <strong><?= number_format((float)$kpiGapToAbove, 1) ?></strong> poin ke peringkat #<?= $kpiRank - 1 ?> (<?= htmlspecialchars($kpiAboveName) ?>)
                                        <?php else: ?>
                                            Evaluasi gabungan 6 pilar kinerja karyawan
                                        <?php endif; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="rank-spotlight-title">
                                        Leaderboard KPI &bull; <?= htmlspecialchars($kpiSemLabel ?: 'Semester Berjalan') ?>
                                    </div>
                                    <div class="rank-spotlight-sub">
                                        Belum memiliki skor KPI atau belum termasuk dalam daftar peringkat periode ini.
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="rank-spotlight-stats">
                            <?php if ($kpiRank > 0 && $kpiRow !== null && $kpiRow->skor_akhir !== null): ?>
                                <span class="rank-stat-chip">
                                    Skor Akhir: <strong><?= number_format((float)$kpiRow->skor_akhir, 1) ?></strong>
                                </span>
                                <span class="rank-stat-chip">
                                    Grade <?= htmlspecialchars($kpiRow->grade) ?> (<?= htmlspecialchars($kpiRow->grade_label) ?>)
                                </span>
                                <span class="rank-stat-chip">
                                    <i class="fas fa-star text-warning"></i> <?= number_format((float)$kpiRow->rating_agregat, 2) ?>/5.0
                                </span>
                            <?php endif; ?>
                            <a href="<?= $lbUrl ?>" class="quick-account-btn">
                                <i class="fas fa-trophy text-warning"></i> Lihat Klasemen
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab Navigation -->
        <ul class="nav profile-tabs" id="profileTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="personal-tab" data-toggle="tab" href="#personal" role="tab">
                    <i class="fas fa-user-circle"></i> Data Pribadi
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="employment-tab" data-toggle="tab" href="#employment" role="tab">
                    <i class="fas fa-building"></i> Data Kepegawaian
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="benefits-tab" data-toggle="tab" href="#benefits" role="tab">
                    <i class="fas fa-gift"></i> Tunjangan
                    <span class="tab-pill-count" id="tabBenefitPillCount"><?= $activeBenefitsCount ?>/9</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="documents-tab" data-toggle="tab" href="#documents" role="tab">
                    <i class="fas fa-folder-open"></i> Dokumen
                    <span class="tab-pill-count"><?= $uploadedDocsCount ?>/<?= $totalDocsCount ?></span>
                </a>
            </li>
            <?php if (isAdmin() || isHrd() || isGa()) { ?>
            <li class="nav-item">
                <a class="nav-link" id="salary-tab" data-toggle="tab" href="#salary" role="tab">
                    <i class="fas fa-money-bill-wave"></i> Data Gaji
                </a>
            </li>
            <?php } ?>
            <li class="nav-item">
                <a class="nav-link" id="signature-tab" data-toggle="tab" href="#signature" role="tab">
                    <i class="fas fa-signature"></i> Tanda Tangan
                </a>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="profileTabsContent">
            <!-- Personal Data Tab -->
            <div class="tab-pane fade show active" id="personal" role="tabpanel">
                <?= form_open('pengguna/update/edit_on_detail', array('id' => 'personal-form', 'autocomplete' => 'off')); ?>
                <div class="profile-content-card">
                    <div class="card-header-custom">
                        <h5>
                            <span class="card-header-icon"><i class="fas fa-user-edit"></i></span>
                            Informasi Pribadi
                        </h5>
                    </div>
                    <div class="card-body-custom">
                        <div class="profile-section-label"><i class="fas fa-id-badge"></i> Identitas &amp; Pendidikan</div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label><i class="fas fa-user"></i> Nama Lengkap</label>
                                    <input class="form-control" type="text" name="nama" value="<?= htmlspecialchars($data_pengguna[0]->nama) ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label><i class="fas fa-id-card"></i> NIK</label>
                                    <input class="form-control" type="text" name="nik" value="<?= htmlspecialchars($data_pengguna[0]->nik) ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label><i class="fas fa-birthday-cake"></i> Tanggal Lahir</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                        </div>
                                        <input type="text" data-plugin-datepicker data-plugin-options='{"orientation": "bottom", "format": "dd-mm-yyyy"}' 
                                               class="form-control" name="tgl_lahir" 
                                               value="<?= $data_pengguna[0]->tgl_lahir ? date('d-m-Y', strtotime($data_pengguna[0]->tgl_lahir)) : '' ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label><i class="fas fa-graduation-cap"></i> Pendidikan Terakhir</label>
                                    <input class="form-control" type="text" name="pendidikan" value="<?= htmlspecialchars($data_pengguna[0]->pendidikan) ?>">
                                </div>
                            </div>
                        </div>

                        <div class="profile-section-label mt-2"><i class="fas fa-users"></i> Keluarga &amp; Domisili</div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label><i class="fas fa-ring"></i> Status Perkawinan</label>
                                    <input type="hidden" name="id_status_perkawinan" id="id_status_perkawinan" value="<?= $data_pengguna[0]->id_status_perkawinan ?>">
                                    <select class="form-control" name="status_perkawinan" id="status_perkawinan">
                                        <option value="">---Pilih Status Perkawinan---</option>
                                        <?php foreach (status_perkawinan() as $row) { ?>
                                            <option value="<?= $row['id'] ?>" <?= $data_pengguna[0]->id_status_perkawinan == $row['id'] ? 'selected' : '' ?>><?= $row['jenis'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label><i class="fas fa-users"></i> Pasangan Se-Kantor</label>
                                    <input type="hidden" name="id_pasangan_sekantor" id="id_pasangan_sekantor" value="<?= encrypt($data_pengguna[0]->id_pasangan_sekantor) ?>">
                                    <select data-plugin-selectTwo class="form-control" name="pasangan_sekantor" id="pasangan_sekantor">
                                        <option value="">---Pilih---</option>
                                        <?php foreach ($pengguna as $row) { ?>
                                            <option value="<?= encrypt($row->pengguna_id) ?>" <?= $data_pengguna[0]->id_pasangan_sekantor == $row->pengguna_id ? 'selected' : '' ?>><?= $row->nama ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label><i class="fas fa-map-marker-alt"></i> Alamat</label>
                                    <textarea class="form-control" name="alamat" rows="3"><?= htmlspecialchars($data_pengguna[0]->alamat ?? '') ?></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label><i class="fas fa-phone-alt"></i> Kontak Keluarga</label>
                                    <input class="form-control" type="text" name="kontak_keluarga" value="<?= htmlspecialchars($data_pengguna[0]->kontak_keluarga) ?>">
                                </div>
                                <div class="form-group-modern">
                                    <label><i class="fas fa-user-friends"></i> Hubungan Keluarga</label>
                                    <input class="form-control" type="text" name="hubungan_keluarga" value="<?= htmlspecialchars($data_pengguna[0]->hubungan_keluarga) ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="profile-content-card">
                    <div class="card-header-custom">
                        <h5>
                            <span class="card-header-icon"><i class="fas fa-address-book"></i></span>
                            Informasi Kontak &amp; Akun Email Kantor
                        </h5>
                    </div>
                    <div class="card-body-custom">
                        <div class="profile-section-label"><i class="fas fa-mobile-alt"></i> Kontak Aktif</div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group-modern">
                                    <label><i class="fas fa-envelope"></i> Email Pribadi</label>
                                    <input class="form-control" type="email" name="email" value="<?= htmlspecialchars($data_pengguna[0]->email) ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group-modern">
                                    <label><i class="fas fa-mobile-alt"></i> No. HP Pribadi</label>
                                    <input class="form-control" type="text" name="no_hp" value="<?= htmlspecialchars($data_pengguna[0]->no_hp) ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group-modern">
                                    <label><i class="fas fa-building"></i> No. HP Kantor (Notifikasi)</label>
                                    <input class="form-control" type="text" name="no_hp_kantor" value="<?= htmlspecialchars($data_pengguna[0]->no_hp_kantor) ?>" placeholder="Opsional jika menggunakan nomor kantor">
                                </div>
                            </div>
                        </div>

                        <div class="profile-section-label mt-2"><i class="fas fa-envelope-open-text"></i> Kredensial Email Kantor</div>
                        <div class="row align-items-end">
                            <div class="col-md-6">
                                <div class="form-group-modern mb-md-0">
                                    <label><i class="fas fa-envelope-open-text text-primary"></i> Email Kantor</label>
                                    <div class="input-group">
                                        <input class="form-control" type="email" id="input_email_kantor_form" name="email_kantor" value="<?= htmlspecialchars($valEmailKantor) ?>" placeholder="contoh: nama@visiyosindo.id">
                                        <div class="input-group-append">
                                            <button class="btn" type="button" onclick="copyTextValue('#input_email_kantor_form', 'Email Kantor')" title="Salin Email Kantor">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-modern mb-md-0">
                                    <label><i class="fas fa-key text-warning"></i> Password Email Kantor</label>
                                    <div class="input-group">
                                        <input class="form-control" type="password" id="input_pass_email_kantor_form" name="password_email_kantor" value="<?= $canEditAtasanProfil ? htmlspecialchars($valPassEmailKantor) : '' ?>" placeholder="Masukkan password email kantor">
                                        <div class="input-group-append">
                                            <button class="btn" type="button" onclick="togglePassInput('#input_pass_email_kantor_form', '#iconPassForm')" title="Lihat / Sembunyikan Password">
                                                <i class="fas fa-eye" id="iconPassForm"></i>
                                            </button>
                                            <button class="btn" type="button" onclick="copyTextValue('#input_pass_email_kantor_form', 'Password Email Kantor')" title="Salin Password">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="btn-action-group">
                        <input type="hidden" name="pengguna_id" value="<?= encrypt($data_pengguna[0]->pengguna_id) ?>">
                        <!-- Hidden fields for employment data -->
                        <input type="hidden" name="no_pegawai" value="<?= $data_pengguna[0]->no_pegawai ?>">
                        <input type="hidden" name="status_karyawan" value="<?= $data_pengguna[0]->status_karyawan ?>">
                        <input type="hidden" name="jabatan" value="<?= $data_pengguna[0]->jabatan ?>">
                        <input type="hidden" name="jabatan_visilab" value="<?= $data_pengguna[0]->jabatan_visilab ?>">
                        <input type="hidden" name="lama_training" value="<?= $data_pengguna[0]->lama_training ?>">
                        <input type="hidden" name="no_rek" value="<?= $data_pengguna[0]->no_rek ?>">
                        <input type="hidden" name="npwp" value="<?= $data_pengguna[0]->npwp ?>">
                        <input type="hidden" name="tgl_masuk" value="<?= $data_pengguna[0]->tgl_masuk ? date('d-m-Y', strtotime($data_pengguna[0]->tgl_masuk)) : '' ?>">
                        <input type="hidden" name="tgl_kontrak" value="<?= $data_pengguna[0]->tgl_kontrak ? date('d-m-Y', strtotime($data_pengguna[0]->tgl_kontrak)) : '' ?>">
                        <input type="hidden" name="tgl_keluar" value="<?= $data_pengguna[0]->tgl_keluar ? date('d-m-Y', strtotime($data_pengguna[0]->tgl_keluar)) : '' ?>">
                        <input type="hidden" name="alasan_keluar" value="<?= $data_pengguna[0]->alasan_keluar ?>">
                        <input type="hidden" name="keterangan_lain" value="<?= $data_pengguna[0]->keterangan_lain ?>">
                        <!-- Hidden fields for benefits -->
                        <input type="hidden" name="terima_tunjangan_jabatan" value="<?= $data_pengguna[0]->terima_tunjangan_jabatan == 1 ? 'on' : '' ?>">
                        <input type="hidden" name="terima_tunjangan_tt" value="<?= $data_pengguna[0]->terima_tunjangan_tt == 1 ? 'on' : '' ?>">
                        <input type="hidden" name="terima_tunjangan_konsumsi" value="<?= $data_pengguna[0]->terima_tunjangan_konsumsi == 1 ? 'on' : '' ?>">
                        <input type="hidden" name="terima_tunjangan_kinerja" value="<?= $data_pengguna[0]->terima_tunjangan_kinerja == 1 ? 'on' : '' ?>">
                        <input type="hidden" name="terima_tunjangan_komunikasi" value="<?= $data_pengguna[0]->terima_tunjangan_komunikasi == 1 ? 'on' : '' ?>">
                        <input type="hidden" name="terima_tunjangan_transportasi" value="<?= $data_pengguna[0]->terima_tunjangan_transportasi == 1 ? 'on' : '' ?>">
                        <input type="hidden" name="terima_tunjangan_bbm" value="<?= $data_pengguna[0]->terima_tunjangan_bbm == 1 ? 'on' : '' ?>">
                        <input type="hidden" name="terima_tunjangan_raya" value="<?= $data_pengguna[0]->terima_tunjangan_raya == 1 ? 'on' : '' ?>">
                        <input type="hidden" name="terima_bonus_tahunan" value="<?= $data_pengguna[0]->terima_bonus_tahunan == 1 ? 'on' : '' ?>">
                        
                        <button type="button" onclick="goBack()" class="btn btn-modern btn-secondary-modern">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </button>
                        <?php if (isAdmin() || sessPenggunaId() == $data_pengguna[0]->pengguna_id) { ?>
                            <button type="button" class="btn btn-modern btn-warning-modern btn-reset-password">
                                <i class="fas fa-key"></i> Reset Password
                            </button>
                        <?php } ?>
                        <?php if (isAdmin() || isHrd() || isGa()) { ?>
                            <button type="button" class="btn btn-modern btn-success-modern btn-save">
                                <i class="fas fa-save"></i> Simpan Data Pribadi
                            </button>
                        <?php } ?>
                    </div>
                </div>
                <?= form_close(); ?>
            </div>

            <!-- Employment Data Tab -->
            <div class="tab-pane fade" id="employment" role="tabpanel">
                <?= form_open('pengguna/update/edit_on_detail', array('id' => 'employment-form', 'autocomplete' => 'off')); ?>
                <div class="profile-content-card">
                    <div class="card-header-custom">
                        <h5>
                            <span class="card-header-icon"><i class="fas fa-briefcase"></i></span>
                            Informasi Kepegawaian
                        </h5>
                    </div>
                    <div class="card-body-custom">
                        <div class="profile-section-label"><i class="fas fa-sitemap"></i> Posisi, Status &amp; Supervisi</div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label><i class="fas fa-id-badge"></i> No. Pegawai (NPP)</label>
                                    <input class="form-control" type="text" name="no_pegawai" value="<?= htmlspecialchars($data_pengguna[0]->no_pegawai) ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label><i class="fas fa-user-tag"></i> Status Karyawan</label>
                                    <select class="form-control" name="status_karyawan">
                                        <option value="">Pilih Status</option>
                                        <option <?= $data_pengguna[0]->status_karyawan == 'training' ? 'selected' : '' ?> value="training">Training</option>
                                        <option <?= $data_pengguna[0]->status_karyawan == 'kontrak' ? 'selected' : '' ?> value="kontrak">Kontrak</option>
                                        <option <?= $data_pengguna[0]->status_karyawan == 'tetap' ? 'selected' : '' ?> value="tetap">Tetap</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label><i class="fas fa-user-tie"></i> Jabatan</label>
                                    <input class="form-control" type="text" name="jabatan" value="<?= htmlspecialchars($data_pengguna[0]->jabatan) ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label><i class="fas fa-flask"></i> Jabatan VISILAB</label>
                                    <input class="form-control" type="text" name="jabatan_visilab" value="<?= htmlspecialchars($data_pengguna[0]->jabatan_visilab) ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group-modern">
                                    <label><i class="fas fa-user-shield text-primary"></i> Atasan Langsung (Membawahi Karyawan Ini)</label>
                                    <select data-plugin-selectTwo class="form-control" name="id_atasan">
                                        <option value="">-- Belum Ditentukan / Tidak Ada Atasan --</option>
                                        <?php foreach ($pilihanAtasan as $atRow):
                                            if ((int)$atRow->pengguna_id === (int)$data_pengguna[0]->pengguna_id) continue;
                                        ?>
                                            <option value="<?= (int)$atRow->pengguna_id ?>" <?= ($curIdAtasan === (int)$atRow->pengguna_id) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($atRow->nama) ?><?= !empty($atRow->jabatan) ? ' — ' . htmlspecialchars($atRow->jabatan) : '' ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <small class="text-muted d-block mt-1">Pilih atasan/supervisor langsung karyawan ini untuk alur penugasan &amp; evaluasi kinerja.</small>
                                </div>
                            </div>
                        </div>

                        <div class="profile-section-label mt-2"><i class="fas fa-file-contract"></i> Administrasi &amp; Masa Kerja</div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label><i class="fas fa-hourglass-half"></i> Lama Training (Bulan)</label>
                                    <input class="form-control" type="text" name="lama_training" value="<?= htmlspecialchars($data_pengguna[0]->lama_training) ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label><i class="fas fa-university"></i> No. Rekening</label>
                                    <input class="form-control" type="text" name="no_rek" value="<?= htmlspecialchars($data_pengguna[0]->no_rek) ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label><i class="fas fa-file-invoice"></i> NPWP</label>
                                    <input class="form-control" type="text" name="npwp" value="<?= htmlspecialchars($data_pengguna[0]->npwp) ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label><i class="fas fa-calendar-check"></i> Tanggal Masuk</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                        </div>
                                        <input type="text" data-plugin-datepicker data-plugin-options='{"orientation": "bottom", "format": "dd-mm-yyyy"}' 
                                               class="form-control" name="tgl_masuk" 
                                               value="<?= $data_pengguna[0]->tgl_masuk ? date('d-m-Y', strtotime($data_pengguna[0]->tgl_masuk)) : '' ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label><i class="fas fa-file-signature"></i> Tanggal Kontrak</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                        </div>
                                        <input type="text" data-plugin-datepicker data-plugin-options='{"orientation": "bottom", "format": "dd-mm-yyyy"}' 
                                               class="form-control" name="tgl_kontrak" 
                                               value="<?= $data_pengguna[0]->tgl_kontrak ? date('d-m-Y', strtotime($data_pengguna[0]->tgl_kontrak)) : '' ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label><i class="fas fa-sign-out-alt"></i> Tanggal Keluar</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                        </div>
                                        <input type="text" data-plugin-datepicker data-plugin-options='{"orientation": "bottom", "format": "dd-mm-yyyy"}' 
                                               class="form-control" name="tgl_keluar" 
                                               value="<?= $data_pengguna[0]->tgl_keluar ? date('d-m-Y', strtotime($data_pengguna[0]->tgl_keluar)) : '' ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label><i class="fas fa-door-open"></i> Alasan Keluar</label>
                                    <textarea class="form-control" name="alasan_keluar" rows="3"><?= htmlspecialchars($data_pengguna[0]->alasan_keluar) ?></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label><i class="fas fa-sticky-note"></i> Keterangan Lainnya</label>
                                    <textarea class="form-control" name="keterangan_lain" rows="3"><?= htmlspecialchars($data_pengguna[0]->keterangan_lain) ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="btn-action-group">
                        <input type="hidden" name="pengguna_id" value="<?= encrypt($data_pengguna[0]->pengguna_id) ?>">
                        <!-- Hidden fields for personal data -->
                        <input type="hidden" name="nama" value="<?= $data_pengguna[0]->nama ?>">
                        <input type="hidden" name="nik" value="<?= $data_pengguna[0]->nik ?>">
                        <input type="hidden" name="email" value="<?= $data_pengguna[0]->email ?>">
                        <input type="hidden" name="no_hp" value="<?= $data_pengguna[0]->no_hp ?>">
                        <input type="hidden" name="no_hp_kantor" value="<?= $data_pengguna[0]->no_hp_kantor ?>">
                        <input type="hidden" name="tgl_lahir" value="<?= $data_pengguna[0]->tgl_lahir ? date('d-m-Y', strtotime($data_pengguna[0]->tgl_lahir)) : '' ?>">
                        <input type="hidden" name="pendidikan" value="<?= $data_pengguna[0]->pendidikan ?>">
                        <input type="hidden" name="status_perkawinan" value="<?= $data_pengguna[0]->id_status_perkawinan ?>">
                        <input type="hidden" name="pasangan_sekantor" value="<?= encrypt($data_pengguna[0]->id_pasangan_sekantor) ?>">
                        <input type="hidden" name="kontak_keluarga" value="<?= $data_pengguna[0]->kontak_keluarga ?>">
                        <input type="hidden" name="hubungan_keluarga" value="<?= $data_pengguna[0]->hubungan_keluarga ?>">
                        <!-- Hidden fields for benefits -->
                        <input type="hidden" name="terima_tunjangan_jabatan" value="<?= $data_pengguna[0]->terima_tunjangan_jabatan == 1 ? 'on' : '' ?>">
                        <input type="hidden" name="terima_tunjangan_tt" value="<?= $data_pengguna[0]->terima_tunjangan_tt == 1 ? 'on' : '' ?>">
                        <input type="hidden" name="terima_tunjangan_konsumsi" value="<?= $data_pengguna[0]->terima_tunjangan_konsumsi == 1 ? 'on' : '' ?>">
                        <input type="hidden" name="terima_tunjangan_kinerja" value="<?= $data_pengguna[0]->terima_tunjangan_kinerja == 1 ? 'on' : '' ?>">
                        <input type="hidden" name="terima_tunjangan_komunikasi" value="<?= $data_pengguna[0]->terima_tunjangan_komunikasi == 1 ? 'on' : '' ?>">
                        <input type="hidden" name="terima_tunjangan_transportasi" value="<?= $data_pengguna[0]->terima_tunjangan_transportasi == 1 ? 'on' : '' ?>">
                        <input type="hidden" name="terima_tunjangan_bbm" value="<?= $data_pengguna[0]->terima_tunjangan_bbm == 1 ? 'on' : '' ?>">
                        <input type="hidden" name="terima_tunjangan_raya" value="<?= $data_pengguna[0]->terima_tunjangan_raya == 1 ? 'on' : '' ?>">
                        <input type="hidden" name="terima_bonus_tahunan" value="<?= $data_pengguna[0]->terima_bonus_tahunan == 1 ? 'on' : '' ?>">
                        
                        <button type="button" onclick="goBack()" class="btn btn-modern btn-secondary-modern">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </button>
                        <?php if (isAdmin() || isHrd() || isGa()) { ?>
                            <button type="button" class="btn btn-modern btn-success-modern btn-save">
                                <i class="fas fa-save"></i> Simpan Data Kepegawaian
                            </button>
                        <?php } ?>
                    </div>
                </div>
                <?= form_close(); ?>
            </div>

            <!-- Benefits Tab -->
            <div class="tab-pane fade" id="benefits" role="tabpanel">
                <?= form_open('pengguna/update/edit_on_detail', array('id' => 'benefits-form', 'autocomplete' => 'off')); ?>
                <div class="profile-content-card">
                    <div class="card-header-custom">
                        <h5>
                            <span class="card-header-icon"><i class="fas fa-hand-holding-usd"></i></span>
                            Pengaturan Tunjangan
                        </h5>
                    </div>
                    <div class="card-body-custom">
                        <div class="benefit-summary-bar">
                            <div>
                                <i class="fas fa-sliders-h mr-1"></i>
                                Klik kartu atau geser sakelar untuk mengaktifkan hak tunjangan karyawan.
                            </div>
                            <div>
                                <span class="badge badge-primary px-3 py-1" style="border-radius: 999px; font-size: 12px;">
                                    <strong id="benefit-summary-active"><?= $activeBenefitsCount ?></strong> / 9 Tunjangan Aktif
                                </span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="benefit-item-card <?= $data_pengguna[0]->terima_tunjangan_jabatan == 1 ? 'is-active' : '' ?>">
                                    <div class="benefit-left">
                                        <div class="benefit-icon benefit-icon-jabatan">
                                            <i class="fas fa-user-tie"></i>
                                        </div>
                                        <div class="benefit-meta">
                                            <span class="benefit-title">Tunjangan Jabatan</span>
                                            <span class="benefit-status-badge"><?= $data_pengguna[0]->terima_tunjangan_jabatan == 1 ? 'Aktif' : 'Tidak Aktif' ?></span>
                                        </div>
                                    </div>
                                    <div class="benefit-right">
                                        <label class="custom-switch-clean">
                                            <input type="checkbox" id="terima_tunjangan_jabatan" name="terima_tunjangan_jabatan" <?= $data_pengguna[0]->terima_tunjangan_jabatan == 1 ? 'checked' : '' ?>>
                                            <span class="slider-clean"></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="benefit-item-card <?= $data_pengguna[0]->terima_tunjangan_tt == 1 ? 'is-active' : '' ?>">
                                    <div class="benefit-left">
                                        <div class="benefit-icon benefit-icon-tt">
                                            <i class="fas fa-random"></i>
                                        </div>
                                        <div class="benefit-meta">
                                            <span class="benefit-title">Tunjangan Tidak Tetap</span>
                                            <span class="benefit-status-badge"><?= $data_pengguna[0]->terima_tunjangan_tt == 1 ? 'Aktif' : 'Tidak Aktif' ?></span>
                                        </div>
                                    </div>
                                    <div class="benefit-right">
                                        <label class="custom-switch-clean">
                                            <input type="checkbox" id="terima_tunjangan_tt" name="terima_tunjangan_tt" <?= $data_pengguna[0]->terima_tunjangan_tt == 1 ? 'checked' : '' ?>>
                                            <span class="slider-clean"></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="benefit-item-card <?= $data_pengguna[0]->terima_tunjangan_konsumsi == 1 ? 'is-active' : '' ?>">
                                    <div class="benefit-left">
                                        <div class="benefit-icon benefit-icon-konsumsi">
                                            <i class="fas fa-utensils"></i>
                                        </div>
                                        <div class="benefit-meta">
                                            <span class="benefit-title">Tunjangan Konsumsi</span>
                                            <span class="benefit-status-badge"><?= $data_pengguna[0]->terima_tunjangan_konsumsi == 1 ? 'Aktif' : 'Tidak Aktif' ?></span>
                                        </div>
                                    </div>
                                    <div class="benefit-right">
                                        <label class="custom-switch-clean">
                                            <input type="checkbox" id="terima_tunjangan_konsumsi" name="terima_tunjangan_konsumsi" <?= $data_pengguna[0]->terima_tunjangan_konsumsi == 1 ? 'checked' : '' ?>>
                                            <span class="slider-clean"></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="benefit-item-card <?= $data_pengguna[0]->terima_tunjangan_kinerja == 1 ? 'is-active' : '' ?>">
                                    <div class="benefit-left">
                                        <div class="benefit-icon benefit-icon-kinerja">
                                            <i class="fas fa-chart-line"></i>
                                        </div>
                                        <div class="benefit-meta">
                                            <span class="benefit-title">Tunjangan Kinerja</span>
                                            <span class="benefit-status-badge"><?= $data_pengguna[0]->terima_tunjangan_kinerja == 1 ? 'Aktif' : 'Tidak Aktif' ?></span>
                                        </div>
                                    </div>
                                    <div class="benefit-right">
                                        <label class="custom-switch-clean">
                                            <input type="checkbox" id="terima_tunjangan_kinerja" name="terima_tunjangan_kinerja" <?= $data_pengguna[0]->terima_tunjangan_kinerja == 1 ? 'checked' : '' ?>>
                                            <span class="slider-clean"></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="benefit-item-card <?= $data_pengguna[0]->terima_tunjangan_komunikasi == 1 ? 'is-active' : '' ?>">
                                    <div class="benefit-left">
                                        <div class="benefit-icon benefit-icon-komunikasi">
                                            <i class="fas fa-phone-alt"></i>
                                        </div>
                                        <div class="benefit-meta">
                                            <span class="benefit-title">Tunjangan Komunikasi</span>
                                            <span class="benefit-status-badge"><?= $data_pengguna[0]->terima_tunjangan_komunikasi == 1 ? 'Aktif' : 'Tidak Aktif' ?></span>
                                        </div>
                                    </div>
                                    <div class="benefit-right">
                                        <label class="custom-switch-clean">
                                            <input type="checkbox" id="terima_tunjangan_komunikasi" name="terima_tunjangan_komunikasi" <?= $data_pengguna[0]->terima_tunjangan_komunikasi == 1 ? 'checked' : '' ?>>
                                            <span class="slider-clean"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="benefit-item-card <?= $data_pengguna[0]->terima_tunjangan_transportasi == 1 ? 'is-active' : '' ?>">
                                    <div class="benefit-left">
                                        <div class="benefit-icon benefit-icon-transportasi">
                                            <i class="fas fa-bus"></i>
                                        </div>
                                        <div class="benefit-meta">
                                            <span class="benefit-title">Tunjangan Transportasi</span>
                                            <span class="benefit-status-badge"><?= $data_pengguna[0]->terima_tunjangan_transportasi == 1 ? 'Aktif' : 'Tidak Aktif' ?></span>
                                        </div>
                                    </div>
                                    <div class="benefit-right">
                                        <label class="custom-switch-clean">
                                            <input type="checkbox" id="terima_tunjangan_transportasi" name="terima_tunjangan_transportasi" <?= $data_pengguna[0]->terima_tunjangan_transportasi == 1 ? 'checked' : '' ?>>
                                            <span class="slider-clean"></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="benefit-item-card <?= $data_pengguna[0]->terima_tunjangan_bbm == 1 ? 'is-active' : '' ?>">
                                    <div class="benefit-left">
                                        <div class="benefit-icon benefit-icon-bbm">
                                            <i class="fas fa-gas-pump"></i>
                                        </div>
                                        <div class="benefit-meta">
                                            <span class="benefit-title">Tunjangan BBM</span>
                                            <span class="benefit-status-badge"><?= $data_pengguna[0]->terima_tunjangan_bbm == 1 ? 'Aktif' : 'Tidak Aktif' ?></span>
                                        </div>
                                    </div>
                                    <div class="benefit-right">
                                        <label class="custom-switch-clean">
                                            <input type="checkbox" id="terima_tunjangan_bbm" name="terima_tunjangan_bbm" <?= $data_pengguna[0]->terima_tunjangan_bbm == 1 ? 'checked' : '' ?>>
                                            <span class="slider-clean"></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="benefit-item-card <?= $data_pengguna[0]->terima_tunjangan_raya == 1 ? 'is-active' : '' ?>">
                                    <div class="benefit-left">
                                        <div class="benefit-icon benefit-icon-raya">
                                            <i class="fas fa-star"></i>
                                        </div>
                                        <div class="benefit-meta">
                                            <span class="benefit-title">Tunjangan Hari Raya</span>
                                            <span class="benefit-status-badge"><?= $data_pengguna[0]->terima_tunjangan_raya == 1 ? 'Aktif' : 'Tidak Aktif' ?></span>
                                        </div>
                                    </div>
                                    <div class="benefit-right">
                                        <label class="custom-switch-clean">
                                            <input type="checkbox" id="terima_tunjangan_raya" name="terima_tunjangan_raya" <?= $data_pengguna[0]->terima_tunjangan_raya == 1 ? 'checked' : '' ?>>
                                            <span class="slider-clean"></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="benefit-item-card <?= $data_pengguna[0]->terima_bonus_tahunan == 1 ? 'is-active' : '' ?>">
                                    <div class="benefit-left">
                                        <div class="benefit-icon benefit-icon-bonus">
                                            <i class="fas fa-gift"></i>
                                        </div>
                                        <div class="benefit-meta">
                                            <span class="benefit-title">Bonus Tahunan</span>
                                            <span class="benefit-status-badge"><?= $data_pengguna[0]->terima_bonus_tahunan == 1 ? 'Aktif' : 'Tidak Aktif' ?></span>
                                        </div>
                                    </div>
                                    <div class="benefit-right">
                                        <label class="custom-switch-clean">
                                            <input type="checkbox" id="terima_bonus_tahunan" name="terima_bonus_tahunan" <?= $data_pengguna[0]->terima_bonus_tahunan == 1 ? 'checked' : '' ?>>
                                            <span class="slider-clean"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="btn-action-group">
                        <input type="hidden" name="pengguna_id" value="<?= encrypt($data_pengguna[0]->pengguna_id) ?>">
                        <!-- Hidden fields for personal data -->
                        <input type="hidden" name="nama" value="<?= $data_pengguna[0]->nama ?>">
                        <input type="hidden" name="nik" value="<?= $data_pengguna[0]->nik ?>">
                        <input type="hidden" name="email" value="<?= $data_pengguna[0]->email ?>">
                        <input type="hidden" name="no_hp" value="<?= $data_pengguna[0]->no_hp ?>">
                        <input type="hidden" name="no_hp_kantor" value="<?= $data_pengguna[0]->no_hp_kantor ?>">
                        <input type="hidden" name="tgl_lahir" value="<?= $data_pengguna[0]->tgl_lahir ? date('d-m-Y', strtotime($data_pengguna[0]->tgl_lahir)) : '' ?>">
                        <input type="hidden" name="pendidikan" value="<?= $data_pengguna[0]->pendidikan ?>">
                        <input type="hidden" name="status_perkawinan" value="<?= $data_pengguna[0]->id_status_perkawinan ?>">
                        <input type="hidden" name="pasangan_sekantor" value="<?= encrypt($data_pengguna[0]->id_pasangan_sekantor) ?>">
                        <input type="hidden" name="kontak_keluarga" value="<?= $data_pengguna[0]->kontak_keluarga ?>">
                        <input type="hidden" name="hubungan_keluarga" value="<?= $data_pengguna[0]->hubungan_keluarga ?>">
                        <!-- Hidden fields for employment data -->
                        <input type="hidden" name="no_pegawai" value="<?= $data_pengguna[0]->no_pegawai ?>">
                        <input type="hidden" name="status_karyawan" value="<?= $data_pengguna[0]->status_karyawan ?>">
                        <input type="hidden" name="jabatan" value="<?= $data_pengguna[0]->jabatan ?>">
                        <input type="hidden" name="jabatan_visilab" value="<?= $data_pengguna[0]->jabatan_visilab ?>">
                        <input type="hidden" name="lama_training" value="<?= $data_pengguna[0]->lama_training ?>">
                        <input type="hidden" name="no_rek" value="<?= $data_pengguna[0]->no_rek ?>">
                        <input type="hidden" name="npwp" value="<?= $data_pengguna[0]->npwp ?>">
                        <input type="hidden" name="tgl_masuk" value="<?= $data_pengguna[0]->tgl_masuk ? date('d-m-Y', strtotime($data_pengguna[0]->tgl_masuk)) : '' ?>">
                        <input type="hidden" name="tgl_kontrak" value="<?= $data_pengguna[0]->tgl_kontrak ? date('d-m-Y', strtotime($data_pengguna[0]->tgl_kontrak)) : '' ?>">
                        <input type="hidden" name="tgl_keluar" value="<?= $data_pengguna[0]->tgl_keluar ? date('d-m-Y', strtotime($data_pengguna[0]->tgl_keluar)) : '' ?>">
                        <input type="hidden" name="alasan_keluar" value="<?= $data_pengguna[0]->alasan_keluar ?>">
                        <input type="hidden" name="keterangan_lain" value="<?= $data_pengguna[0]->keterangan_lain ?>">
                        
                        <button type="button" onclick="goBack()" class="btn btn-modern btn-secondary-modern">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </button>
                        <?php if (isAdmin() || isHrd() || isGa()) { ?>
                            <button type="button" class="btn btn-modern btn-success-modern btn-save">
                                <i class="fas fa-save"></i> Simpan Pengaturan Tunjangan
                            </button>
                        <?php } ?>
                    </div>
                </div>
                <?= form_close(); ?>
            </div>

            <!-- Documents Tab -->
            <div class="tab-pane fade" id="documents" role="tabpanel">
                <div class="profile-content-card">
                    <div class="card-header-custom">
                        <h5>
                            <span class="card-header-icon"><i class="fas fa-folder-open"></i></span>
                            Dokumen Karyawan
                        </h5>
                        <span class="badge <?= $docsPct >= 100 ? 'badge-success' : 'badge-primary' ?> px-3 py-1" style="border-radius: 999px; font-size: 12px;">
                            <?= $uploadedDocsCount ?> / <?= $totalDocsCount ?> Berkas Terunggah
                        </span>
                    </div>
                    <div class="card-body-custom">
                        <div class="doc-completeness-banner">
                            <div class="doc-completeness-top">
                                <div class="font-weight-bold text-dark" style="font-size: 13.5px;">
                                    <i class="fas fa-clipboard-check text-primary mr-1"></i>
                                    Kelengkapan Berkas Administrasi Karyawan
                                </div>
                                <div class="font-weight-bold text-primary" style="font-size: 13.5px;">
                                    <?= $docsPct ?>% Lengkap
                                </div>
                            </div>
                            <div class="doc-completeness-track">
                                <div class="doc-completeness-fill" style="width: <?= $docsPct ?>%;"></div>
                            </div>
                        </div>

                        <div class="document-grid">
                            <?php foreach ($docsList as $docItem):
                                $docField = $docItem['field'];
                                $docVal   = $data_pengguna[0]->$docField ?? '';
                                $hasDoc   = !empty($docVal);
                            ?>
                                <div class="document-card <?= $hasDoc ? 'has-file' : '' ?>">
                                    <div class="w-100 d-flex flex-column align-items-center">
                                        <div class="doc-icon-wrap">
                                            <i class="<?= $docItem['icon'] ?>"></i>
                                        </div>
                                        <div class="doc-title"><?= htmlspecialchars($docItem['title']) ?></div>
                                        <span class="doc-status">
                                            <i class="fas <?= $hasDoc ? 'fa-check-circle' : 'fa-clock' ?>"></i>
                                            <?= $hasDoc ? 'Tersedia' : 'Belum Upload' ?>
                                        </span>
                                    </div>
                                    <div class="doc-actions">
                                        <?php if ($hasDoc): ?>
                                            <button type="button" class="btn btn-info btn-doc btn-preview-file" data-type="<?= htmlspecialchars($docItem['type']) ?>" data-file="<?= htmlspecialchars($docVal) ?>" title="Lihat Dokumen">
                                                <i class="fas fa-eye"></i> Lihat
                                            </button>
                                        <?php endif; ?>
                                        <?php if (isAdmin() || isHrd() || isGa() || (int)sessPenggunaId() === (int)$data_pengguna[0]->pengguna_id): ?>
                                            <button type="button" class="btn <?= $hasDoc ? 'btn-outline-primary' : 'btn-primary' ?> btn-doc btn-upload-file" data-type="<?= htmlspecialchars($docItem['type']) ?>" title="<?= $hasDoc ? 'Ganti File' : 'Upload File' ?>">
                                                <i class="fas fa-upload"></i> <?= $hasDoc ? 'Ganti' : 'Upload' ?>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Salary Tab -->
            <?php if (isAdmin() || isHrd() || isGa()) { ?>
            <div class="tab-pane fade" id="salary" role="tabpanel">
                <?= form_open('pengguna/addgaji/', array('id' => 'salary-form', 'autocomplete' => 'off')); ?>
                <div class="profile-content-card">
                    <div class="card-header-custom">
                        <h5>
                            <span class="card-header-icon"><i class="fas fa-money-bill-wave"></i></span>
                            Informasi Gaji &amp; Kompensasi
                        </h5>
                    </div>
                    <div class="card-body-custom">
                        <!-- Live Ringkasan Kompensasi -->
                        <div class="salary-summary-grid">
                            <div class="salary-stat-card stat-income">
                                <div class="salary-stat-label text-primary">Total Pendapatan Bulanan</div>
                                <div class="salary-stat-val text-dark" id="sal_sum_income">Rp 0</div>
                            </div>
                            <div class="salary-stat-card stat-deduct">
                                <div class="salary-stat-label text-danger">Total Potongan</div>
                                <div class="salary-stat-val text-danger" id="sal_sum_deduct">Rp 0</div>
                            </div>
                            <div class="salary-stat-card stat-thp">
                                <div class="salary-stat-label">Estimasi Take-Home Bulanan</div>
                                <div class="salary-stat-val" id="sal_sum_thp">Rp 0</div>
                            </div>
                            <div class="salary-stat-card stat-annual">
                                <div class="salary-stat-label text-warning">THR &amp; Bonus Tahunan</div>
                                <div class="salary-stat-val text-dark" id="sal_sum_annual">Rp 0</div>
                            </div>
                        </div>

                        <?php
                        $salRow = isset($data_salary[0]) ? $data_salary[0] : null;
                        $salMonthlyIncome = [
                            ['name' => 'gaji_pokok',             'label' => 'Gaji Pokok',             'icon' => 'fas fa-wallet'],
                            ['name' => 'tunjangan_jabatan',      'label' => 'Tunjangan Jabatan',      'icon' => 'fas fa-user-tie'],
                            ['name' => 'komisi',                 'label' => 'Komisi',                 'icon' => 'fas fa-percentage'],
                            ['name' => 'pendapatan_lain',        'label' => 'Pendapatan Lain',        'icon' => 'fas fa-plus-circle'],
                            ['name' => 'tunjangan_konsumsi',     'label' => 'Tunjangan Konsumsi',     'icon' => 'fas fa-utensils'],
                            ['name' => 'tunjangan_kinerja',      'label' => 'Tunjangan Kinerja',      'icon' => 'fas fa-chart-line'],
                            ['name' => 'tunjangan_komunikasi',   'label' => 'Tunjangan Komunikasi',   'icon' => 'fas fa-phone'],
                            ['name' => 'tunjangan_transportasi', 'label' => 'Tunjangan Transportasi', 'icon' => 'fas fa-bus'],
                            ['name' => 'tunjangan_bbm',          'label' => 'Tunjangan BBM',          'icon' => 'fas fa-gas-pump']
                        ];
                        $salDeductions = [
                            ['name' => 'potongan',          'label' => 'Potongan Tunjangan',           'icon' => 'fas fa-minus-circle'],
                            ['name' => 'dasar_bpjs_sehat',  'label' => 'Dasar Potong BPJS Kesehatan',  'icon' => 'fas fa-heartbeat'],
                            ['name' => 'dasar_bpjs_kerja',  'label' => 'Dasar Potong BPJS Ketenagakerjaan', 'icon' => 'fas fa-hard-hat']
                        ];
                        $salAnnual = [
                            ['name' => 'tunjangan_raya', 'label' => 'Tunjangan Hari Raya', 'icon' => 'fas fa-star'],
                            ['name' => 'bonus_tahunan',  'label' => 'Bonus Tahunan',       'icon' => 'fas fa-gift']
                        ];
                        ?>

                        <div class="salary-group-box">
                            <div class="profile-section-label"><i class="fas fa-coins"></i> Gaji Pokok &amp; Tunjangan Bulanan</div>
                            <div class="row">
                                <?php foreach ($salMonthlyIncome as $sf): $sName = $sf['name']; ?>
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label><i class="<?= $sf['icon'] ?>"></i> <?= $sf['label'] ?></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Rp</span>
                                                </div>
                                                <input class="form-control input_salary sal-field-income" type="text" name="<?= $sName ?>"
                                                       value="<?= ($salRow && isset($salRow->$sName)) ? $salRow->$sName : 0 ?>">
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="salary-group-box">
                            <div class="profile-section-label"><i class="fas fa-shield-alt"></i> Potongan &amp; Dasar BPJS</div>
                            <div class="row">
                                <?php foreach ($salDeductions as $sf): $sName = $sf['name']; ?>
                                    <div class="col-md-4">
                                        <div class="form-group-modern">
                                            <label><i class="<?= $sf['icon'] ?>"></i> <?= $sf['label'] ?></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Rp</span>
                                                </div>
                                                <input class="form-control input_salary <?= $sName === 'potongan' ? 'sal-field-deduct' : '' ?>" type="text" name="<?= $sName ?>"
                                                       value="<?= ($salRow && isset($salRow->$sName)) ? $salRow->$sName : 0 ?>">
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="salary-group-box mb-0">
                            <div class="profile-section-label"><i class="fas fa-award"></i> Tunjangan &amp; Bonus Tahunan</div>
                            <div class="row">
                                <?php foreach ($salAnnual as $sf): $sName = $sf['name']; ?>
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label><i class="<?= $sf['icon'] ?>"></i> <?= $sf['label'] ?></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Rp</span>
                                                </div>
                                                <input class="form-control input_salary sal-field-annual" type="text" name="<?= $sName ?>"
                                                       value="<?= ($salRow && isset($salRow->$sName)) ? $salRow->$sName : 0 ?>">
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <div class="btn-action-group">
                        <input type="hidden" name="pengguna_id" value="<?= encrypt($data_pengguna[0]->pengguna_id) ?>">
                        <button type="button" onclick="goBack()" class="btn btn-modern btn-secondary-modern">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </button>
                        <button type="button" class="btn btn-modern btn-success-modern btn-save">
                            <i class="fas fa-save"></i> Simpan Data Gaji
                        </button>
                    </div>
                </div>
                <?= form_close(); ?>
            </div>
            <?php } ?>
            
            <!-- Signature Tab -->
            <div class="tab-pane fade" id="signature" role="tabpanel">
                <div class="profile-content-card">
                    <div class="card-header-custom">
                        <h5>
                            <span class="card-header-icon"><i class="fas fa-signature"></i></span>
                            Tanda Tangan Digital
                        </h5>
                    </div>
                    <div class="card-body-custom">
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <div class="signature-container text-center">
                                    <?php 
                                    $ttd_file = 'uploads/file_karyawan/ttd/ttd_' . $data_pengguna[0]->pengguna_id . '.png';
                                    $ttd_exists = file_exists(FCPATH . $ttd_file);
                                    ?>
                                    
                                    <div class="signature-preview-box" id="signature-preview-box">
                                        <?php if($ttd_exists): ?>
                                        <img src="<?= base_url($ttd_file) ?>?t=<?= time() ?>" alt="Tanda Tangan" class="signature-image" id="current-signature">
                                        <?php else: ?>
                                        <div class="no-signature" id="no-signature">
                                            <i class="fas fa-file-signature"></i>
                                            <p>Belum ada tanda tangan</p>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="signature-info mt-4">
                                        <p class="text-dark font-weight-bold mb-2" style="font-size: 13px;">
                                            <i class="fas fa-info-circle text-primary mr-1"></i>
                                            Tanda tangan akan digunakan pada dokumen resmi perusahaan.
                                        </p>
                                        <p class="text-muted small mb-0">
                                            Format yang didukung: PNG (disarankan latar belakang transparan) &bull; Ukuran maksimal: 2MB
                                        </p>
                                    </div>
                                    
                                    <div class="signature-actions mt-4">
                                        <label for="signature-file-input" class="btn btn-modern btn-primary-modern mb-0" style="cursor: pointer;">
                                            <i class="fas fa-upload"></i> <?= $ttd_exists ? 'Ganti' : 'Upload' ?> Tanda Tangan
                                        </label>
                                        <input type="file" id="signature-file-input" accept=".png" style="display: none;">
                                        
                                        <?php if($ttd_exists): ?>
                                        <button type="button" class="btn btn-modern btn-danger-modern" id="btn-delete-signature">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <!-- Upload Progress -->
                                    <div class="upload-progress mt-4" id="signature-upload-progress" style="display: none;">
                                        <div class="progress" style="height: 10px; border-radius: 999px;">
                                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%;"></div>
                                        </div>
                                        <p class="text-muted mt-2 small">Mengupload...</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- File Upload Modal -->
<div id="file-upload-modal" class="modal fade profile-modern-modal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold" style="color:#fff;"><i class="fas fa-cloud-upload-alt mr-1"></i> <span id="upload-modal-title">Upload Dokumen</span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open('#', array('id' => 'file-upload-form', 'autocomplete' => 'off', 'enctype' => 'multipart/form-data')); ?>
            <div class="modal-body">
                <div class="form-group-modern mb-0">
                    <label><i class="fas fa-file"></i> Pilih File</label>
                    <input type="file" class="form-control" name="file_input" id="file_input" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx" style="padding: 8px 12px;">
                    <small class="text-muted d-block mt-2">
                        <i class="fas fa-info-circle mr-1"></i> Format yang didukung: PDF, JPG, JPEG, PNG, DOC, DOCX, XLS, XLSX (Max: 4MB)
                    </small>
                </div>
                <div id="file-preview-area" class="text-center mt-3" style="display: none;">
                    <img id="image-preview" src="" alt="Preview" style="max-width: 100%; max-height: 200px; border-radius: 10px; display: none;">
                    <div id="pdf-preview-icon" style="display: none;">
                        <i class="fas fa-file-pdf fa-5x text-danger"></i>
                        <p class="mt-2 font-weight-bold" id="file-name-preview"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="upload_pengguna_id" name="pengguna_id" value="<?= encrypt($data_pengguna[0]->pengguna_id) ?>">
                <input type="hidden" id="upload_file_type" name="file_type" value="">
                <button type="button" class="btn btn-modern btn-secondary-modern" data-dismiss="modal">
                    <i class="fas fa-times"></i> Batal
                </button>
                <button type="button" id="btn-submit-upload" class="btn btn-modern btn-success-modern">
                    <i class="fas fa-upload"></i> Upload
                </button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<!-- File Preview Modal -->
<div id="file-preview-modal" class="modal fade modal-pdf-preview" data-backdrop="static" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold" style="color:#fff;"><i class="fas fa-file mr-1"></i> <span id="preview-modal-title">Preview Dokumen</span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <div id="preview-content">
                    <!-- PDF Preview -->
                    <div id="pdf-preview-container" class="pdf-viewer-container" style="display: none;">
                        <iframe id="pdf-iframe" src=""></iframe>
                    </div>
                    <!-- Image Preview -->
                    <div id="image-preview-container" class="image-preview-container" style="display: none;">
                        <img id="preview-image" src="" alt="Preview">
                    </div>
                    <!-- Other File Type -->
                    <div id="other-preview-container" class="text-center p-5" style="display: none;">
                        <i class="fas fa-file fa-5x text-secondary mb-3"></i>
                        <p>File ini tidak dapat ditampilkan preview.</p>
                        <a id="download-link" href="#" class="btn btn-modern btn-primary-modern" target="_blank">
                            <i class="fas fa-download"></i> Download File
                        </a>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="border: none;">
                <a id="preview-download-link" href="#" class="btn btn-modern btn-primary-modern" target="_blank">
                    <i class="fas fa-download"></i> Download
                </a>
                <button type="button" class="btn btn-modern btn-secondary-modern" data-dismiss="modal">
                    <i class="fas fa-times"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Reset Password Modal -->
<div id="reset-password-modal" class="modal fade profile-modern-modal modal-amber" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold" style="color:#fff;"><i class="fas fa-key mr-1"></i> Reset Password</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open('pengguna/reset', array('id' => 'reset-password-form', 'autocomplete' => 'off')); ?>
            <div class="modal-body">
                <div class="form-group-modern">
                    <label><i class="fas fa-lock"></i> Password Baru</label>
                    <input class="form-control" type="password" name="password" id="new_password" placeholder="Masukkan password baru" required>
                    <small class="text-muted d-block mt-1">Minimal 8 karakter, kombinasi huruf dan angka</small>
                </div>
                <div class="form-group-modern mb-0">
                    <label><i class="fas fa-lock"></i> Konfirmasi Password</label>
                    <input class="form-control" type="password" name="rpassword" id="confirm_password" placeholder="Konfirmasi password baru" required>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" name="pengguna_id" value="<?= encrypt($data_pengguna[0]->pengguna_id) ?>">
                <button type="button" class="btn btn-modern btn-secondary-modern" data-dismiss="modal">
                    <i class="fas fa-times"></i> Batal
                </button>
                <button type="button" class="btn btn-modern btn-warning-modern btn-save">
                    <i class="fas fa-save"></i> Reset Password
                </button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div class="loading-overlay hide" id="loading-overlay">
    <div class="loading-spinner"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize salary input masks & live summary calculator
    $('.input_salary').mask('000.000.000.000', {
        reverse: true
    });

    function parseSalaryNum(v) {
        var digits = String(v || '').replace(/\D/g, '');
        return digits ? parseInt(digits, 10) : 0;
    }

    function formatRupiahDisplay(n) {
        var neg = n < 0;
        var absVal = Math.abs(Math.round(n));
        return (neg ? '-Rp ' : 'Rp ') + absVal.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function updateSalarySummary() {
        if (!$('#salary-form').length) return;
        var income = 0;
        $('#salary-form .sal-field-income').each(function() {
            income += parseSalaryNum($(this).val());
        });
        var deduct = 0;
        $('#salary-form .sal-field-deduct').each(function() {
            deduct += parseSalaryNum($(this).val());
        });
        var annual = 0;
        $('#salary-form .sal-field-annual').each(function() {
            annual += parseSalaryNum($(this).val());
        });
        var thp = income - deduct;

        $('#sal_sum_income').text(formatRupiahDisplay(income));
        $('#sal_sum_deduct').text(formatRupiahDisplay(deduct));
        $('#sal_sum_thp').text(formatRupiahDisplay(thp));
        $('#sal_sum_annual').text(formatRupiahDisplay(annual));
    }

    updateSalarySummary();
    $(document).on('input keyup change', '.input_salary', updateSalarySummary);

    // File type mappings for document titles
    const fileTypeTitles = {
        'foto': 'Pas Foto',
        'ktp': 'KTP',
        'kk': 'Kartu Keluarga',
        'ijazah': 'Ijazah',
        'transkip': 'Transkip Nilai',
        'sim': 'SIM',
        'bpjs': 'Kartu BPJS',
        'bpjs_ketenagakerjaan': 'Kartu BPJS Ketenagakerjaan',
        'skck': 'SKCK',
        'no_rek': 'Buku Tabungan',
        'domisili': 'Surat Domisili',
        'sertifikat_vaksin': 'Sertifikat Vaksin',
        'npwp': 'Kartu NPWP',
        'passport': 'Paspor',
        'kta': 'KTA Satpam / Security'
    };

    // Reset Password Modal
    $(document).on('click', '.btn-reset-password', function() {
        $('#reset-password-modal').modal('show');
    });

    // Upload File Button
    $(document).on('click', '.btn-upload-file', function() {
        var fileType = $(this).data('type');
        var title = fileTypeTitles[fileType] || 'Dokumen';
        
        $('#upload-modal-title').text('Upload ' + title);
        $('#upload_file_type').val(fileType);
        $('#file_input').val('');
        $('#file-preview-area').hide();
        $('#file-upload-form').attr('action', 'pengguna/update/file/' + fileType);
        $('#file-upload-modal').modal('show');
    });

    // File input preview
    $('#file_input').on('change', function() {
        var file = this.files[0];
        if (file) {
            var reader = new FileReader();
            var fileName = file.name;
            var fileExt = fileName.split('.').pop().toLowerCase();
            
            $('#file-preview-area').show();
            
            if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExt)) {
                reader.onload = function(e) {
                    $('#image-preview').attr('src', e.target.result).show();
                    $('#pdf-preview-icon').hide();
                };
                reader.readAsDataURL(file);
            } else {
                $('#image-preview').hide();
                $('#pdf-preview-icon').show();
                $('#file-name-preview').text(fileName);
            }
        }
    });

    // Submit Upload
    $('#btn-submit-upload').on('click', function() {
        var formData = new FormData($('#file-upload-form')[0]);
        var fileType = $('#upload_file_type').val();
        
        // Rename file input to match expected name
        var fileInput = $('#file_input')[0].files[0];
        if (!fileInput) {
            Swal.fire('Error', 'Silahkan pilih file terlebih dahulu', 'error');
            return;
        }
        
        formData.delete('file_input');
        formData.append(fileType, fileInput);
        formData.append('csrf_token', token);
        
        $('#loading-overlay').removeClass('hide');
        
        $.ajax({
            url: '<?= base_url() ?>pengguna/update/file/' + fileType,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#loading-overlay').addClass('hide');
                try {
                    var res = typeof response === 'string' ? JSON.parse(response) : response;
                    if (res.status === 'success') {
                        Swal.fire('Berhasil', res.msg, 'success').then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Error', res.msg, 'error');
                    }
                } catch(e) {
                    Swal.fire('Berhasil', 'File berhasil diupload', 'success').then(() => {
                        location.reload();
                    });
                }
            },
            error: function(xhr) {
                $('#loading-overlay').addClass('hide');
                Swal.fire('Error', 'Terjadi kesalahan saat mengupload file', 'error');
            }
        });
    });

    // Preview File Button
    $(document).on('click', '.btn-preview-file', function() {
        var fileType = $(this).data('type');
        var fileName = $(this).data('file');
        var title = fileTypeTitles[fileType] || 'Dokumen';
        var fileUrl = '<?= base_url() ?>uploads/file_karyawan/' + fileType + '/' + fileName;
        var fileExt = fileName.split('.').pop().toLowerCase();
        
        $('#preview-modal-title').text('Preview ' + title);
        $('#preview-download-link').attr('href', fileUrl);
        $('#download-link').attr('href', fileUrl);
        
        // Hide all preview containers
        $('#pdf-preview-container').hide();
        $('#image-preview-container').hide();
        $('#other-preview-container').hide();
        
        if (fileExt === 'pdf') {
            $('#pdf-iframe').attr('src', fileUrl);
            $('#pdf-preview-container').show();
        } else if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExt)) {
            $('#preview-image').attr('src', fileUrl);
            $('#image-preview-container').show();
        } else {
            $('#other-preview-container').show();
        }
        
        $('#file-preview-modal').modal('show');
    });

    // Clear iframe when modal closes
    $('#file-preview-modal').on('hidden.bs.modal', function() {
        $('#pdf-iframe').attr('src', '');
    });

    // Tab persistence
    $('#profileTabs a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
        localStorage.setItem('activeProfileTab', $(e.target).attr('href'));
    });

    var activeTab = localStorage.getItem('activeProfileTab');
    if (activeTab && $('#profileTabs a[href="' + activeTab + '"]').length) {
        $('#profileTabs a[href="' + activeTab + '"]').tab('show');
    }

    // ============ SIGNATURE UPLOAD ============
    $('#signature-file-input').on('change', function() {
        var file = this.files[0];
        if (!file) return;
        
        // Validate file type
        if (file.type !== 'image/png') {
            Swal.fire('Error', 'Hanya file PNG yang diperbolehkan!', 'error');
            $(this).val('');
            return;
        }
        
        // Validate file size (max 2MB)
        if (file.size > 2 * 1024 * 1024) {
            Swal.fire('Error', 'Ukuran file maksimal 2MB!', 'error');
            $(this).val('');
            return;
        }
        
        // Confirm upload
        Swal.fire({
            title: 'Upload Tanda Tangan?',
            text: 'File tanda tangan akan diupload.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#667eea',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Upload!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                uploadSignature(file);
            } else {
                $('#signature-file-input').val('');
            }
        });
    });

    function uploadSignature(file) {
        var formData = new FormData();
        formData.append('signature_file', file);
        formData.append('pengguna_id', '<?= encrypt($data_pengguna[0]->pengguna_id) ?>');
        formData.append('csrf_token', token);
        
        $('#signature-upload-progress').show();
        
        $.ajax({
            url: '<?= base_url("pengguna/upload_signature") ?>',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            xhr: function() {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener('progress', function(e) {
                    if (e.lengthComputable) {
                        var percent = Math.round((e.loaded / e.total) * 100);
                        $('#signature-upload-progress .progress-bar').css('width', percent + '%');
                    }
                });
                return xhr;
            },
            success: function(response) {
                $('#signature-upload-progress').hide();
                try {
                    var res = typeof response === 'string' ? JSON.parse(response) : response;
                    if (res.status === 'success') {
                        Swal.fire('Berhasil', res.msg || 'Tanda tangan berhasil diupload!', 'success').then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Error', res.msg || 'Gagal mengupload tanda tangan', 'error');
                    }
                } catch(e) {
                    Swal.fire('Berhasil', 'Tanda tangan berhasil diupload!', 'success').then(() => {
                        location.reload();
                    });
                }
            },
            error: function(xhr) {
                $('#signature-upload-progress').hide();
                Swal.fire('Error', 'Terjadi kesalahan saat mengupload file', 'error');
            }
        });
    }

    // ============ BENEFIT CHECKBOX CARDS INTERACTION ============
    $(document).on('click', '.benefit-item-card', function(e) {
        if ($(e.target).closest('.custom-switch-clean').length > 0) {
            return; // Let the switch handle its own click
        }
        var chk = $(this).find('input[type="checkbox"]');
        var newState = !chk.prop('checked');
        chk.prop('checked', newState).trigger('change');
    });

    $(document).on('change', '.custom-switch-clean input[type="checkbox"]', function() {
        var isChecked = $(this).prop('checked');
        var card = $(this).closest('.benefit-item-card');
        card.toggleClass('is-active', isChecked);
        card.find('.benefit-status-badge').text(isChecked ? 'Aktif' : 'Tidak Aktif');

        var activeCnt = $('.custom-switch-clean input[type="checkbox"]:checked').length;
        $('#benefit-summary-active').text(activeCnt);
        $('#tabBenefitPillCount').text(activeCnt + '/9');
    });

    // Delete Signature
    $('#btn-delete-signature').on('click', function() {
        Swal.fire({
            title: 'Hapus Tanda Tangan?',
            text: 'Tanda tangan akan dihapus secara permanen.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url("pengguna/delete_signature") ?>',
                    type: 'POST',
                    data: {
                        pengguna_id: '<?= encrypt($data_pengguna[0]->pengguna_id) ?>',
                        csrf_token: token
                    },
                    success: function(response) {
                        try {
                            var res = typeof response === 'string' ? JSON.parse(response) : response;
                            if (res.status === 'success') {
                                Swal.fire('Berhasil', res.msg || 'Tanda tangan berhasil dihapus!', 'success').then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire('Error', res.msg || 'Gagal menghapus tanda tangan', 'error');
                            }
                        } catch(e) {
                            Swal.fire('Berhasil', 'Tanda tangan berhasil dihapus!', 'success').then(() => {
                                location.reload();
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire('Error', 'Terjadi kesalahan', 'error');
                    }
                });
            }
        });
    });
});

function goBack() {
    window.history.back();
}

function toggleHeaderPassEmail() {
    var mask = document.getElementById('headerPassEmailMask');
    var real = document.getElementById('headerPassEmailReal');
    var icon = document.getElementById('iconHeaderPassEmail');
    if (!mask || !real) return;
    if (real.style.display === 'none') {
        real.style.display = 'inline-block';
        mask.style.display = 'none';
        if (icon) { icon.classList.remove('fa-eye'); icon.classList.add('fa-eye-slash'); }
    } else {
        real.style.display = 'none';
        mask.style.display = 'inline-block';
        if (icon) { icon.classList.remove('fa-eye-slash'); icon.classList.add('fa-eye'); }
    }
}

function togglePassInput(inputSelector, iconSelector) {
    var $inp = $(inputSelector);
    var $ico = $(iconSelector);
    if ($inp.attr('type') === 'password') {
        $inp.attr('type', 'text');
        $ico.removeClass('fa-eye').addClass('fa-eye-slash');
    } else {
        $inp.attr('type', 'password');
        $ico.removeClass('fa-eye-slash').addClass('fa-eye');
    }
}

function copyTextValue(inputSelector, labelName) {
    var val = $(inputSelector).val();
    if (!val) {
        if (typeof Swal !== 'undefined') {
            Swal.fire('Info', (labelName || 'Data') + ' masih kosong.', 'info');
        }
        return;
    }
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(val).then(function() {
            if (typeof Swal !== 'undefined') {
                Swal.fire({ icon: 'success', title: 'Disalin!', text: (labelName || 'Data') + ' berhasil disalin ke clipboard.', timer: 1400, showConfirmButton: false });
            }
        });
    } else {
        var $temp = $('<input>');
        $('body').append($temp);
        $temp.val(val).select();
        document.execCommand('copy');
        $temp.remove();
        if (typeof Swal !== 'undefined') {
            Swal.fire({ icon: 'success', title: 'Disalin!', text: (labelName || 'Data') + ' berhasil disalin ke clipboard.', timer: 1400, showConfirmButton: false });
        }
    }
}

$(document).on('submit', '#formEmailKantorProfil', function(e) {
    e.preventDefault();
    var $btn = $('#btnSimpanEmailKantorProfil');
    var orig = $btn.html();
    $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');
    $.ajax({
        url: '<?= base_url("pengguna/update/email_kantor") ?>',
        type: 'POST',
        dataType: 'json',
        data: {
            pengguna_id: '<?= encrypt($data_pengguna[0]->pengguna_id) ?>',
            email_kantor: $('#modal_input_email_kantor').val(),
            password_email_kantor: $('#modal_input_pass_email_kantor').val(),
            csrf_token: token
        },
        success: function(res) {
            $btn.prop('disabled', false).html(orig);
            if (res && res.status === 'success') {
                $('#modalEmailKantorProfil').modal('hide');
                if (typeof Swal !== 'undefined') {
                    Swal.fire('Berhasil!', res.msg || 'Akun Email Kantor berhasil disimpan!', 'success').then(function() {
                        location.reload();
                    });
                } else {
                    alert(res.msg || 'Akun Email Kantor berhasil disimpan!');
                    location.reload();
                }
            } else {
                alert((res && res.msg) ? res.msg : 'Gagal menyimpan akun email kantor.');
            }
        },
        error: function() {
            $btn.prop('disabled', false).html(orig);
            alert('Terjadi kesalahan jaringan.');
        }
    });
});

$(document).on('submit', '#formPilihAtasanProfil', function(e) {
    e.preventDefault();
    var $btn = $('#btnSimpanAtasanProfil');
    var orig = $btn.html();
    $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');
    $.ajax({
        url: '<?= base_url("pengguna/update/atasan") ?>',
        type: 'POST',
        dataType: 'json',
        data: {
            pengguna_id: '<?= encrypt($data_pengguna[0]->pengguna_id) ?>',
            id_atasan: $('#select_id_atasan_profil').val(),
            csrf_token: token
        },
        success: function(res) {
            $btn.prop('disabled', false).html(orig);
            if (res && res.status === 'success') {
                $('#modalPilihAtasanProfil').modal('hide');
                if (typeof Swal !== 'undefined') {
                    Swal.fire('Berhasil!', res.msg || 'Atasan Langsung berhasil diperbarui!', 'success').then(function() {
                        location.reload();
                    });
                } else {
                    alert(res.msg || 'Atasan Langsung berhasil diperbarui!');
                    location.reload();
                }
            } else {
                alert((res && res.msg) ? res.msg : 'Gagal menyimpan data atasan.');
            }
        },
        error: function() {
            $btn.prop('disabled', false).html(orig);
            alert('Terjadi kesalahan jaringan.');
        }
    });
});
</script>

<!-- Modal Pilih / Ubah Atasan Langsung di Profil -->
<div class="modal fade profile-modern-modal" id="modalPilihAtasanProfil" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="formPilihAtasanProfil">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold" style="color:#fff;">
                        <i class="fas fa-user-shield mr-1"></i> Atur Atasan Langsung (Supervisor)
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="small text-muted mb-3">
                        Pilih atasan langsung untuk karyawan <strong><?= htmlspecialchars($data_pengguna[0]->nama) ?></strong>:
                    </p>
                    <div class="form-group-modern mb-0">
                        <label><i class="fas fa-user-tie"></i> Nama Atasan Langsung</label>
                        <select id="select_id_atasan_profil" name="id_atasan" class="form-control">
                            <option value="">-- Belum Ditentukan / Tidak Ada Atasan --</option>
                            <?php foreach ($pilihanAtasan as $atRow):
                                if ((int)$atRow->pengguna_id === (int)$data_pengguna[0]->pengguna_id) continue;
                            ?>
                                <option value="<?= (int)$atRow->pengguna_id ?>" <?= ($curIdAtasan === (int)$atRow->pengguna_id) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($atRow->nama) ?><?= !empty($atRow->jabatan) ? ' (' . htmlspecialchars($atRow->jabatan) . ')' : '' ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-modern btn-secondary-modern" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-modern btn-primary-modern" id="btnSimpanAtasanProfil">
                        <i class="fas fa-save"></i> Simpan Atasan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Atur / Ubah Akun Email Kantor di Profil -->
<div class="modal fade profile-modern-modal" id="modalEmailKantorProfil" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="formEmailKantorProfil" autocomplete="off">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold" style="color:#fff;">
                        <i class="fas fa-envelope-open-text mr-1"></i> Akun Email Kantor &amp; Password
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="small text-muted mb-3">
                        Atur alamat email resmi kantor dan passwordnya untuk <strong><?= htmlspecialchars($data_pengguna[0]->nama) ?></strong>:
                    </p>
                    <div class="form-group-modern">
                        <label><i class="fas fa-envelope text-primary"></i> Alamat Email Kantor</label>
                        <input type="email" id="modal_input_email_kantor" name="email_kantor" class="form-control" value="<?= htmlspecialchars($valEmailKantor) ?>" placeholder="contoh: nama@visiyosindo.id">
                    </div>
                    <div class="form-group-modern mb-0">
                        <label><i class="fas fa-key text-warning"></i> Password Email Kantor</label>
                        <div class="input-group">
                            <input type="password" id="modal_input_pass_email_kantor" name="password_email_kantor" class="form-control" value="<?= $canEditAtasanProfil ? htmlspecialchars($valPassEmailKantor) : '' ?>" placeholder="Masukkan password email kantor">
                            <div class="input-group-append">
                                <button class="btn" type="button" onclick="togglePassInput('#modal_input_pass_email_kantor', '#iconPassModal')" title="Lihat / Sembunyikan Password">
                                    <i class="fas fa-eye" id="iconPassModal"></i>
                                </button>
                                <button class="btn" type="button" onclick="copyTextValue('#modal_input_pass_email_kantor', 'Password Email Kantor')" title="Salin Password">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-modern btn-secondary-modern" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-modern btn-primary-modern" id="btnSimpanEmailKantorProfil">
                        <i class="fas fa-save"></i> Simpan Email Kantor
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
