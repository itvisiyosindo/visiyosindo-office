<style>
    /* Modern Energetic & Competitive Surface Architecture */
    @keyframes tpHeroLiveDot {
        0%, 100% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.45); opacity: 0.55; }
    }
    @keyframes tpBadgePulse {
        0%, 100% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.55); transform: scale(1); }
        50% { box-shadow: 0 0 0 6px rgba(245, 158, 11, 0); transform: scale(1.03); }
    }
    @keyframes tpUrgentGlow {
        0%, 100% { box-shadow: 0 2px 8px rgba(239, 68, 68, 0.35); }
        50% { box-shadow: 0 2px 14px rgba(239, 68, 68, 0.65); }
    }
    @keyframes tpProgressStripes {
        0% { background-position: 0 0; }
        100% { background-position: 28px 0; }
    }
    @keyframes tpArenaShine {
        0% { transform: translateX(-140%) skewX(-20deg); }
        100% { transform: translateX(240%) skewX(-20deg); }
    }

    /* Arena Hero Command Banner */
    .tp-arena-hero {
        position: relative;
        overflow: hidden;
        border-radius: 16px 16px 0 0;
        background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 52%, #1d4ed8 100%);
        color: #f8fafc;
        padding: 20px 24px;
        border: 1px solid #1e293b;
        border-bottom: none;
        box-shadow: 0 12px 28px -8px rgba(15, 23, 42, 0.28);
    }
    .tp-arena-hero::before {
        content: "";
        position: absolute;
        top: -55%;
        right: -8%;
        width: 340px;
        height: 340px;
        background: radial-gradient(circle, rgba(251, 191, 36, 0.22) 0%, transparent 68%);
        pointer-events: none;
    }
    .tp-arena-hero::after {
        content: "";
        position: absolute;
        bottom: -65%;
        left: 10%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(56, 189, 248, 0.18) 0%, transparent 70%);
        pointer-events: none;
    }
    .tp-arena-hero-inner {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
    }
    .tp-arena-live-pill {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 4px 12px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: #fde68a;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 0.7px;
        text-transform: uppercase;
        margin-bottom: 8px;
        backdrop-filter: blur(6px);
    }
    .tp-arena-live-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #22c55e;
        box-shadow: 0 0 8px #22c55e;
        animation: tpHeroLiveDot 1.8s ease-in-out infinite;
    }
    .tp-arena-title {
        font-size: 20px;
        font-weight: 800;
        color: #ffffff;
        margin: 0 0 6px 0;
        line-height: 1.25;
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
    }
    .tp-arena-rank-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: 999px;
        font-size: 12.5px;
        font-weight: 800;
        background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
        color: #451a03;
        box-shadow: 0 4px 14px rgba(245, 158, 11, 0.4);
    }
    .tp-arena-gap-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 11px;
        border-radius: 999px;
        font-size: 11.5px;
        font-weight: 700;
        background: rgba(249, 115, 22, 0.22);
        border: 1px solid rgba(251, 146, 60, 0.45);
        color: #ffedd5;
    }
    .tp-arena-sub {
        font-size: 13px;
        color: #cbd5e1;
        margin-bottom: 10px;
    }
    .tp-arena-chips {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }
    .tp-arena-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 11px;
        border-radius: 10px;
        background: rgba(15, 23, 42, 0.5);
        border: 1px solid rgba(148, 163, 184, 0.28);
        color: #e2e8f0;
        font-size: 12px;
        font-weight: 600;
    }
    .tp-arena-chip.chip-urgent {
        background: rgba(245, 158, 11, 0.2);
        border-color: rgba(251, 191, 36, 0.55);
        color: #fef3c7;
        animation: tpBadgePulse 2.4s ease-in-out infinite;
    }
    .tp-arena-actions {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 10px;
    }
    .tp-btn-cta-arena {
        position: relative;
        overflow: hidden;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
        border: 1px solid rgba(255, 255, 255, 0.22) !important;
        color: #ffffff !important;
        border-radius: 11px !important;
        padding: 10px 18px !important;
        font-size: 13.5px !important;
        font-weight: 800 !important;
        letter-spacing: 0.2px;
        box-shadow: 0 8px 20px -4px rgba(16, 185, 129, 0.55);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .tp-btn-cta-arena::after {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 45%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.28), transparent);
        transform: translateX(-140%) skewX(-20deg);
        transition: transform 0.6s ease;
    }
    .tp-btn-cta-arena:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 24px -4px rgba(16, 185, 129, 0.68);
        color: #ffffff !important;
    }
    .tp-btn-cta-arena:hover::after {
        animation: tpArenaShine 0.85s ease forwards;
    }
    .tp-btn-lb-jump {
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.22) 0%, rgba(234, 88, 12, 0.28) 100%) !important;
        border: 1px solid rgba(251, 191, 36, 0.6) !important;
        color: #fef3c7 !important;
        border-radius: 11px !important;
        padding: 10px 16px !important;
        font-size: 13px !important;
        font-weight: 800 !important;
        backdrop-filter: blur(6px);
        transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
    }
    .tp-btn-lb-jump:hover {
        transform: translateY(-2px);
        background: linear-gradient(135deg, #f59e0b 0%, #ea580c 100%) !important;
        color: #ffffff !important;
        box-shadow: 0 8px 20px -4px rgba(245, 158, 11, 0.55);
    }

    .tp-control-card {
        border-radius: 0 0 16px 16px;
        border: 1px solid #cbd5e1;
        border-top: 2px solid #2563eb;
        background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
        padding: 14px 20px;
        box-shadow: 0 6px 20px -4px rgba(15, 23, 42, 0.06);
        margin-bottom: 18px;
    }
    .tp-kpi-card {
        position: relative;
        overflow: hidden;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
        padding: 18px 20px;
        box-shadow: 0 4px 16px -2px rgba(15, 23, 42, 0.05), 0 2px 6px -1px rgba(15, 23, 42, 0.02);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 16px;
        height: 100%;
        transition: transform 0.22s cubic-bezier(0.22, 1, 0.36, 1), box-shadow 0.22s ease, border-color 0.22s ease;
    }
    .tp-kpi-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        opacity: 0.9;
    }
    .tp-kpi-card::after {
        content: "";
        position: absolute;
        right: -24px;
        bottom: -24px;
        width: 96px;
        height: 96px;
        border-radius: 50%;
        pointer-events: none;
        opacity: 0.14;
        transition: transform 0.25s ease, opacity 0.25s ease;
    }
    .tp-kpi-card:hover {
        transform: translateY(-4px);
    }
    .tp-kpi-card:hover::after {
        transform: scale(1.18);
        opacity: 0.22;
    }
    .tp-kpi-card > div:first-child {
        position: relative;
        z-index: 2;
    }
    .tp-kpi-card > div:first-child > div:nth-child(2) {
        font-variant-numeric: tabular-nums;
        letter-spacing: -0.5px;
        line-height: 1.15;
        margin: 4px 0;
    }
    .tp-kpi-card.kpi-blue { border-left: 5px solid #2563eb !important; }
    .tp-kpi-card.kpi-blue::before { background: linear-gradient(90deg, #2563eb, #38bdf8); }
    .tp-kpi-card.kpi-blue::after { background: radial-gradient(circle, #3b82f6, transparent 70%); }
    .tp-kpi-card.kpi-blue:hover { box-shadow: 0 14px 28px -8px rgba(37, 99, 235, 0.22); border-color: #93c5fd; }

    .tp-kpi-card.kpi-amber { border-left: 5px solid #f59e0b !important; }
    .tp-kpi-card.kpi-amber::before { background: linear-gradient(90deg, #f59e0b, #ef4444); }
    .tp-kpi-card.kpi-amber::after { background: radial-gradient(circle, #f59e0b, transparent 70%); }
    .tp-kpi-card.kpi-amber:hover { box-shadow: 0 14px 28px -8px rgba(245, 158, 11, 0.25); border-color: #fcd34d; }

    .tp-kpi-card.kpi-green { border-left: 5px solid #10b981 !important; }
    .tp-kpi-card.kpi-green::before { background: linear-gradient(90deg, #10b981, #22c55e); }
    .tp-kpi-card.kpi-green::after { background: radial-gradient(circle, #10b981, transparent 70%); }
    .tp-kpi-card.kpi-green:hover { box-shadow: 0 14px 28px -8px rgba(16, 185, 129, 0.22); border-color: #6ee7b7; }

    .tp-kpi-card.kpi-purple { border-left: 5px solid #7c3aed !important; }
    .tp-kpi-card.kpi-purple::before { background: linear-gradient(90deg, #8b5cf6, #ec4899); }
    .tp-kpi-card.kpi-purple::after { background: radial-gradient(circle, #8b5cf6, transparent 70%); }
    .tp-kpi-card.kpi-purple:hover { box-shadow: 0 14px 28px -8px rgba(124, 58, 237, 0.22); border-color: #c4b5fd; }

    .tp-kpi-icon {
        position: relative;
        z-index: 2;
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 21px;
        flex-shrink: 0;
        color: #ffffff !important;
        transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.25s ease;
    }
    .tp-kpi-card.kpi-blue .tp-kpi-icon {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%) !important;
        box-shadow: 0 8px 18px -4px rgba(37, 99, 235, 0.45);
    }
    .tp-kpi-card.kpi-amber .tp-kpi-icon {
        background: linear-gradient(135deg, #f59e0b 0%, #ea580c 100%) !important;
        box-shadow: 0 8px 18px -4px rgba(245, 158, 11, 0.45);
    }
    .tp-kpi-card.kpi-green .tp-kpi-icon {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
        box-shadow: 0 8px 18px -4px rgba(16, 185, 129, 0.45);
    }
    .tp-kpi-card.kpi-purple .tp-kpi-icon {
        background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%) !important;
        box-shadow: 0 8px 18px -4px rgba(124, 58, 237, 0.45);
    }
    .tp-kpi-card:hover .tp-kpi-icon {
        transform: scale(1.09) rotate(-4deg);
    }
    .tp-star-display {
        color: #f59e0b;
        font-size: 14px;
        letter-spacing: 1px;
        text-shadow: 0 1px 4px rgba(245, 158, 11, 0.25);
    }
    .tp-star-empty {
        color: #cbd5e1;
    }
    .tp-priority-badge {
        padding: 4px 11px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        letter-spacing: 0.4px;
    }
    .tp-prio-Low { background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; }
    .tp-prio-Medium { background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%); color: #0369a1; border: 1px solid #7dd3fc; }
    .tp-prio-High {
        background: linear-gradient(135deg, #ffedd5 0%, #fed7aa 100%);
        color: #c2410c;
        border: 1px solid #fdba74;
        box-shadow: 0 2px 6px rgba(234, 88, 12, 0.2);
    }
    .tp-prio-Urgent {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: #ffffff;
        border: 1px solid #b91c1c;
        animation: tpUrgentGlow 2s ease-in-out infinite;
    }

    .tp-status-badge {
        padding: 5px 11px;
        border-radius: 999px;
        font-size: 11.5px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        letter-spacing: 0.2px;
    }
    .tp-st-0 { background: #f8fafc; color: #475569; border: 1px solid #cbd5e1; }
    .tp-st-1 { background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); color: #1d4ed8; border: 1px solid #93c5fd; }
    .tp-st-2 { background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); color: #92400e; border: 1px solid #fcd34d; }
    .tp-st-3 { background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); color: #b91c1c; border: 1px solid #fca5a5; }
    .tp-st-4 { background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%); color: #15803d; border: 1px solid #86efac; }
    .tp-st-5 { background: #fffbeb; color: #92400e; border: 1px solid #fde68a; }
    .tp-st-6 { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }

    /* Interactive 5-Star Rating Picker */
    .star-rating-picker {
        display: inline-flex;
        flex-direction: row;
        gap: 8px;
        cursor: pointer;
        user-select: none;
    }
    .star-rating-picker .star-btn {
        font-size: 32px;
        color: #cbd5e1;
        transition: transform 0.15s ease, color 0.15s ease;
    }
    .star-rating-picker .star-btn.active,
    .star-rating-picker .star-btn.hover {
        color: #f59e0b;
        transform: scale(1.12);
    }
    .tp-checklist-row {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 9px 13px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        margin-bottom: 6px;
        background: #f8fafc;
        transition: background-color 0.15s ease;
    }
    .tp-checklist-row:hover {
        background: #f1f5f9;
    }
    .tp-checklist-row.done {
        background: #f0fdf4;
        border-color: #bbf7d0;
        text-decoration: line-through;
        color: #64748b;
    }
    .tp-log-item {
        border-left: 3px solid #3b82f6;
        padding: 9px 13px;
        margin-bottom: 10px;
        background: #f8fafc;
        border-radius: 0 8px 8px 0;
        border-top: 1px solid #f1f5f9;
        border-bottom: 1px solid #f1f5f9;
        border-right: 1px solid #f1f5f9;
    }

    /* Main Workspace Featured Panel */
    .tp-workspace-card {
        border-radius: 16px !important;
        overflow: hidden;
        border: 1px solid #cbd5e1 !important;
        box-shadow: 0 12px 32px -10px rgba(15, 23, 42, 0.1) !important;
    }
    .tp-workspace-card > .card-header {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 52%, #1e3a8a 100%) !important;
        color: #ffffff !important;
        padding: 15px 20px !important;
        border-bottom: 2px solid #2563eb !important;
    }
    .tp-workspace-card > .card-header .card-title {
        color: #ffffff !important;
        font-weight: 800 !important;
        font-size: 15.5px !important;
        letter-spacing: 0.2px;
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
    }
    .tp-workspace-card > .card-header .card-actions a {
        color: #e2e8f0 !important;
    }
    .tp-workspace-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 3px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 800;
        background: rgba(251, 191, 36, 0.18);
        border: 1px solid rgba(251, 191, 36, 0.45);
        color: #fde68a;
        letter-spacing: 0.4px;
    }

    /* Segmented Competitive Workspace Tabs */
    .nav-tabs-tp {
        border: 1px solid #e2e8f0 !important;
        border-radius: 14px !important;
        padding: 6px !important;
        margin-bottom: 20px;
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        background: #f1f5f9;
        box-shadow: inset 0 1px 3px rgba(15, 23, 42, 0.05);
    }
    .nav-tabs-tp > li {
        margin-bottom: 0;
    }
    .nav-tabs-tp > li > a,
    .nav-tabs-tp .nav-link {
        font-weight: 700 !important;
        font-size: 13.5px !important;
        color: #475569 !important;
        background: transparent !important;
        border: 1px solid transparent !important;
        border-radius: 10px !important;
        padding: 10px 16px !important;
        display: inline-flex !important;
        align-items: center !important;
        text-decoration: none !important;
        transition: all 0.22s cubic-bezier(0.22, 1, 0.36, 1);
    }
    .nav-tabs-tp > li > a:hover,
    .nav-tabs-tp .nav-link:hover {
        background: #ffffff !important;
        color: #0f172a !important;
        border-color: #cbd5e1 !important;
        box-shadow: 0 3px 8px rgba(15, 23, 42, 0.06);
        transform: translateY(-1px);
    }
    .nav-tabs-tp > li > a[href="#tab_leaderboard"] {
        background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%) !important;
        color: #92400e !important;
        border-color: #fde68a !important;
    }
    .nav-tabs-tp > li > a[href="#tab_leaderboard"]:hover {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%) !important;
        color: #78350f !important;
        border-color: #fbbf24 !important;
    }
    .nav-tabs-tp > li.active > a,
    .nav-tabs-tp > li.active > a:hover,
    .nav-tabs-tp > li.active > a:focus,
    .nav-tabs-tp .nav-link.active,
    .nav-tabs-tp .nav-link.active:hover,
    .nav-tabs-tp .nav-link.active:focus {
        background: linear-gradient(135deg, #2563eb 0%, #4f46e5 100%) !important;
        color: #ffffff !important;
        font-weight: 800 !important;
        border: 1px solid transparent !important;
        margin-bottom: 0 !important;
        box-shadow: 0 6px 16px -3px rgba(37, 99, 235, 0.42) !important;
        transform: translateY(0);
    }
    .nav-tabs-tp > li.active > a[href="#tab_tugas_atasan"],
    .nav-tabs-tp .nav-link.active[href="#tab_tugas_atasan"] {
        background: linear-gradient(135deg, #059669 0%, #0d9488 100%) !important;
        box-shadow: 0 6px 16px -3px rgba(5, 150, 105, 0.42) !important;
    }
    .nav-tabs-tp > li.active > a[href="#tab_semua_task"],
    .nav-tabs-tp .nav-link.active[href="#tab_semua_task"] {
        background: linear-gradient(135deg, #0284c7 0%, #2563eb 100%) !important;
        box-shadow: 0 6px 16px -3px rgba(2, 132, 199, 0.42) !important;
    }
    .nav-tabs-tp > li.active > a[href="#tab_leaderboard"],
    .nav-tabs-tp .nav-link.active[href="#tab_leaderboard"] {
        background: linear-gradient(135deg, #f59e0b 0%, #ea580c 100%) !important;
        color: #ffffff !important;
        box-shadow: 0 8px 18px -3px rgba(234, 88, 12, 0.48) !important;
    }
    .nav-tabs-tp > li.active > a > i,
    .nav-tabs-tp .nav-link.active > i {
        color: #ffffff !important;
    }
    .nav-tabs-tp .badge {
        font-size: 11px !important;
        font-weight: 800 !important;
        padding: 4px 10px !important;
        border-radius: 999px !important;
        line-height: 1.2 !important;
        letter-spacing: 0.2px;
        vertical-align: middle;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        margin-left: 8px !important;
    }
    .nav-tabs-tp .badge-primary {
        background-color: #0284c7 !important;
        color: #ffffff !important;
    }
    .nav-tabs-tp .badge-warning {
        background-color: #f59e0b !important;
        color: #ffffff !important;
        box-shadow: 0 2px 8px rgba(245, 158, 11, 0.45);
        animation: tpBadgePulse 2.3s ease-in-out infinite;
    }
    .nav-tabs-tp .badge-warning i {
        color: #fef08a !important;
    }
    .nav-tabs-tp .badge-secondary {
        background-color: #64748b !important;
        color: #ffffff !important;
    }
    .nav-tabs-tp > li.active > a .badge-primary,
    .nav-tabs-tp .nav-link.active .badge-primary,
    .nav-tabs-tp > li.active > a .badge-info,
    .nav-tabs-tp .nav-link.active .badge-info,
    .nav-tabs-tp > li.active > a .badge-secondary,
    .nav-tabs-tp .nav-link.active .badge-secondary {
        background-color: rgba(255, 255, 255, 0.22) !important;
        color: #ffffff !important;
        border: 1px solid rgba(255, 255, 255, 0.35);
    }

    /* Tab Intro Mission Banners */
    .tp-tab-banner {
        border-radius: 12px;
        padding: 12px 16px;
        margin-bottom: 16px;
        font-size: 13px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
    }
    .tp-tab-banner.tp-banner-blue {
        background: linear-gradient(135deg, #eff6ff 0%, #eef2ff 100%);
        border: 1px solid #bfdbfe;
        border-left: 4px solid #2563eb;
        color: #1e3a8a;
    }
    .tp-tab-banner.tp-banner-emerald {
        background: linear-gradient(135deg, #ecfdf5 0%, #f0fdfa 100%);
        border: 1px solid #a7f3d0;
        border-left: 4px solid #059669;
        color: #064e3b;
    }
    .tp-tab-banner.tp-banner-cyan {
        background: linear-gradient(135deg, #f0f9ff 0%, #ecfeff 100%);
        border: 1px solid #bae6fd;
        border-left: 4px solid #0284c7;
        color: #0c4a6e;
    }
    .tp-mode-switch .btn {
        border-radius: 8px !important;
        font-weight: 700;
        padding: 6px 12px;
    }

    /* Energetic & Clean Task Table Architecture */
    .tp-workspace-card .table-responsive {
        border-radius: 14px;
        border: 1px solid #cbd5e1;
        background: #ffffff;
        padding: 14px 16px;
        box-shadow: 0 4px 16px -4px rgba(15, 23, 42, 0.05);
    }
    .tp-workspace-card .table-responsive.tp-has-dt-scroll,
    .tp-lb-table-card .table-responsive.tp-has-dt-scroll {
        overflow: visible !important;
    }
    .tp-workspace-card .dataTables_wrapper .row,
    .tp-lb-table-card .dataTables_wrapper .row {
        margin-left: 0;
        margin-right: 0;
        align-items: center;
    }
    .tp-workspace-card .dataTables_wrapper .dataTables_filter,
    .tp-lb-table-card .dataTables_wrapper .dataTables_filter {
        text-align: right !important;
    }
    .tp-dt-sync-wrap {
        display: none;
        align-items: center;
        gap: 10px;
        margin: 4px 0 8px 0;
        padding: 6px 10px;
        border-radius: 10px;
        background: linear-gradient(135deg, #f8fafc 0%, #eff6ff 100%);
        border: 1px solid #dbeafe;
    }
    .tp-dt-sync-wrap.is-scrollable {
        display: flex;
    }
    .tp-dt-sync-hint {
        font-size: 11px;
        font-weight: 800;
        color: #1e40af;
        white-space: nowrap;
        flex-shrink: 0;
    }
    .tp-dt-sync-top {
        flex: 1 1 auto;
        overflow-x: auto;
        overflow-y: hidden;
        height: 16px;
        padding: 2px 0;
        scrollbar-width: thin;
        scrollbar-color: #2563eb #dbeafe;
    }
    .tp-dt-sync-top::-webkit-scrollbar {
        height: 10px;
    }
    .tp-dt-sync-top::-webkit-scrollbar-track {
        background: #dbeafe;
        border-radius: 999px;
    }
    .tp-dt-sync-top::-webkit-scrollbar-thumb {
        background: linear-gradient(90deg, #2563eb, #0284c7);
        border-radius: 999px;
    }
    .tp-dt-sync-spacer {
        height: 1px;
    }
    .tp-dt-sync-btns {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        flex-shrink: 0;
    }
    .tp-scroll-step {
        width: 26px;
        height: 24px;
        border-radius: 7px;
        border: 1px solid #bfdbfe;
        background: #ffffff;
        color: #1d4ed8;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        cursor: pointer;
        transition: all 0.15s ease;
        padding: 0;
    }
    .tp-scroll-step:hover {
        background: #2563eb;
        color: #ffffff;
        border-color: #2563eb;
    }
    .tp-dt-scroll {
        width: 100%;
        max-height: 64vh;
        overflow-x: auto;
        overflow-y: auto;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        background: #ffffff;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: thin;
        scrollbar-color: #94a3b8 #f1f5f9;
    }
    .tp-dt-scroll::-webkit-scrollbar {
        height: 10px;
        width: 10px;
    }
    .tp-dt-scroll::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 999px;
    }
    .tp-dt-scroll::-webkit-scrollbar-thumb {
        background: #94a3b8;
        border-radius: 999px;
        border: 2px solid #f1f5f9;
    }
    .tp-dt-scroll::-webkit-scrollbar-thumb:hover {
        background: #475569;
    }
    .tp-dt-scroll thead th {
        position: sticky;
        top: 0;
        z-index: 15;
    }
    .tp-workspace-card .dataTables_wrapper .dataTables_length label,
    .tp-workspace-card .dataTables_wrapper .dataTables_filter label {
        font-size: 12.5px;
        font-weight: 600;
        color: #475569;
        margin-bottom: 10px;
    }
    .tp-workspace-card .dataTables_wrapper .dataTables_length select {
        border-radius: 8px !important;
        border: 1.5px solid #cbd5e1 !important;
        padding: 3px 8px !important;
        font-weight: 700;
        color: #1e293b;
    }
    .tp-workspace-card .dataTables_wrapper .dataTables_filter input {
        border-radius: 999px !important;
        border: 1.5px solid #cbd5e1 !important;
        padding: 4px 14px !important;
        font-size: 12.5px;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }
    .tp-workspace-card .dataTables_wrapper .dataTables_filter input:focus {
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15) !important;
        outline: none;
    }
    .tp-workspace-card .dataTables_wrapper .dataTables_info {
        font-size: 12px;
        color: #64748b;
        padding-top: 10px;
    }
    .tp-workspace-card .dataTables_wrapper .dataTables_paginate {
        padding-top: 8px;
    }
    .tp-workspace-card .dataTables_wrapper .dataTables_paginate .paginate_button,
    .tp-workspace-card .dataTables_wrapper .pagination .page-link {
        border-radius: 8px !important;
        font-size: 12px !important;
        font-weight: 700 !important;
    }
    #datatable_tugas_saya,
    #datatable_tugas_atasan,
    #datatable_semua_task {
        border-collapse: separate !important;
        border-spacing: 0 !important;
        width: 100% !important;
        border: none !important;
        margin-top: 0 !important;
        margin-bottom: 0 !important;
    }
    #datatable_tugas_saya thead th,
    #datatable_tugas_atasan thead th,
    #datatable_semua_task thead th {
        background: linear-gradient(180deg, #eff6ff 0%, #e0e7ff 100%) !important;
        color: #1e3a8a !important;
        font-size: 11px !important;
        font-weight: 800 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.45px !important;
        border-top: none !important;
        border-left: none !important;
        border-right: 1px solid rgba(191, 219, 254, 0.75) !important;
        border-bottom: 2px solid #3b82f6 !important;
        padding: 12px 11px !important;
        vertical-align: middle !important;
        line-height: 1.3;
    }
    #datatable_tugas_saya thead th:last-child,
    #datatable_tugas_atasan thead th:last-child,
    #datatable_semua_task thead th:last-child {
        border-right: none !important;
    }
    #datatable_tugas_saya tbody tr:nth-child(even) td,
    #datatable_tugas_atasan tbody tr:nth-child(even) td,
    #datatable_semua_task tbody tr:nth-child(even) td {
        background-color: #fafcff;
    }
    #datatable_tugas_saya tbody tr,
    #datatable_tugas_atasan tbody tr,
    #datatable_semua_task tbody tr {
        transition: background-color 0.16s ease, box-shadow 0.16s ease;
    }
    #datatable_tugas_saya tbody tr:hover td,
    #datatable_tugas_atasan tbody tr:hover td,
    #datatable_semua_task tbody tr:hover td {
        background-color: #f1f5f9 !important;
    }
    #datatable_tugas_saya tbody tr:hover td:first-child,
    #datatable_tugas_atasan tbody tr:hover td:first-child,
    #datatable_semua_task tbody tr:hover td:first-child {
        box-shadow: inset 4px 0 0 #2563eb;
    }
    #datatable_tugas_saya tbody td,
    #datatable_tugas_atasan tbody td,
    #datatable_semua_task tbody td {
        padding: 13px 11px !important;
        vertical-align: top !important;
        font-size: 12.5px !important;
        line-height: 1.45;
        border-top: none !important;
        border-left: none !important;
        border-right: 1px solid #f1f5f9 !important;
        border-bottom: 1px solid #e2e8f0 !important;
    }
    #datatable_semua_task tbody td {
        padding: 11px 9px !important;
    }
    #datatable_tugas_saya tbody td:last-child,
    #datatable_tugas_atasan tbody td:last-child,
    #datatable_semua_task tbody td:last-child {
        border-right: none !important;
    }

    /* Table Column Sizing & Clean Long-Text Wrapping */
    .tp-col-no { width: 44px; text-align: center; font-weight: 700; color: #64748b; }
    .tp-col-code { min-width: 140px; max-width: 185px; }
    .tp-col-main { min-width: 230px; max-width: 315px; }
    .tp-col-person { min-width: 132px; max-width: 180px; }
    .tp-col-prio { min-width: 142px; max-width: 175px; }
    .tp-col-prog { width: 112px; min-width: 108px; }
    .tp-col-status { min-width: 158px; white-space: nowrap; }
    .tp-col-rating { min-width: 148px; max-width: 215px; }
    .tp-col-aksi { width: 138px; min-width: 132px; }
    #datatable_semua_task .tp-col-code { min-width: 112px; max-width: 150px; }
    #datatable_semua_task .tp-col-main { min-width: 190px; max-width: 265px; }
    #datatable_semua_task .tp-col-person { min-width: 104px; max-width: 142px; }
    #datatable_semua_task .tp-col-prio { min-width: 116px; max-width: 145px; }
    #datatable_semua_task .tp-col-prog { width: 84px; min-width: 82px; }
    #datatable_semua_task .tp-col-status { min-width: 132px; }
    #datatable_semua_task .tp-col-rating { min-width: 96px; max-width: 145px; }
    #datatable_semua_task .tp-col-aksi { width: 106px; min-width: 102px; }

    .tp-status-badge,
    .tp-priority-badge {
        white-space: nowrap;
    }
    .tp-task-code {
        display: inline-block;
        font-weight: 800;
        color: #2563eb;
        font-size: 12px;
        letter-spacing: 0.25px;
        margin-bottom: 4px;
    }
    .tp-proyek-pill {
        display: inline-block;
        max-width: 100%;
        white-space: normal;
        word-break: break-word;
        overflow-wrap: anywhere;
        text-align: left;
        line-height: 1.35;
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        background: #f8fafc;
        color: #334155;
        border: 1px solid #cbd5e1;
    }
    .tp-jenis-meta {
        font-size: 11px;
        color: #64748b;
        margin-top: 4px;
        word-break: break-word;
    }
    .tp-task-title {
        font-weight: 800;
        color: #0f172a;
        font-size: 13px;
        line-height: 1.4;
        word-break: break-word;
        overflow-wrap: anywhere;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .tp-task-desc {
        font-size: 11.8px;
        color: #475569;
        line-height: 1.45;
        margin-top: 4px;
        word-break: break-word;
        overflow-wrap: anywhere;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .tp-jobdesk-pill {
        display: inline-block;
        max-width: 100%;
        white-space: normal;
        word-break: break-word;
        overflow-wrap: anywhere;
        text-align: left;
        line-height: 1.35;
        padding: 4px 9px;
        border-radius: 7px;
        font-size: 11px;
        font-weight: 700;
        margin-top: 5px;
    }
    .tp-note-box {
        margin-top: 6px;
        padding: 6px 9px;
        border-radius: 8px;
        font-size: 11.5px;
        line-height: 1.4;
        word-break: break-word;
        overflow-wrap: anywhere;
    }
    .tp-note-clamp {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .tp-note-revisi {
        background: #fef2f2;
        border: 1px solid #fecaca;
        border-left: 3px solid #ef4444;
        color: #991b1b;
    }
    .tp-note-laporan {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-left: 3px solid #10b981;
        color: #14532d;
    }
    .tp-note-ulasan {
        background: #fffbeb;
        border: 1px solid #fde68a;
        border-left: 3px solid #f59e0b;
        color: #78350f;
        font-style: italic;
    }
    .tp-note-hrd {
        background: #fef2f2;
        border: 1px solid #fecaca;
        border-left: 3px solid #dc2626;
        color: #991b1b;
        font-size: 11px;
    }
    .tp-person-name {
        display: block;
        font-weight: 700;
        color: #0f172a;
        font-size: 12.5px;
        line-height: 1.35;
        word-break: break-word;
        overflow-wrap: anywhere;
    }
    .tp-person-meta {
        display: block;
        font-size: 11px;
        color: #64748b;
        line-height: 1.35;
        margin-top: 2px;
        word-break: break-word;
        overflow-wrap: anywhere;
    }
    .tp-badge-wrap {
        display: inline-block;
        max-width: 100%;
        white-space: normal !important;
        word-break: break-word;
        overflow-wrap: anywhere;
        text-align: left;
        line-height: 1.3;
    }
    .tp-action-stack {
        display: flex;
        flex-direction: column;
        align-items: stretch;
        gap: 5px;
    }
    .tp-action-stack .btn-xs {
        width: 100%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        margin: 0 !important;
        white-space: nowrap;
    }

    /* Vibrant Progress Bars & Tactile Action Pills in Task Tables */
    #datatable_tugas_saya .progress,
    #datatable_tugas_atasan .progress,
    #datatable_semua_task .progress {
        height: 11px !important;
        border-radius: 999px !important;
        background: #e2e8f0;
        box-shadow: inset 0 1px 2px rgba(15, 23, 42, 0.1);
        overflow: hidden;
    }
    #datatable_tugas_saya .progress-bar,
    #datatable_tugas_atasan .progress-bar,
    #datatable_semua_task .progress-bar {
        border-radius: 999px;
        transition: width 0.55s cubic-bezier(0.22, 1, 0.36, 1);
    }
    #datatable_tugas_saya .progress-bar.bg-primary,
    #datatable_tugas_saya .progress-bar.bg-info,
    #datatable_tugas_atasan .progress-bar.bg-primary,
    #datatable_tugas_atasan .progress-bar.bg-info,
    #datatable_semua_task .progress-bar.bg-primary,
    #datatable_semua_task .progress-bar.bg-info {
        background-color: #2563eb !important;
        background-image: linear-gradient(135deg, rgba(255, 255, 255, 0.22) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.22) 50%, rgba(255, 255, 255, 0.22) 75%, transparent 75%, transparent),
                          linear-gradient(90deg, #2563eb 0%, #06b6d4 100%) !important;
        background-size: 28px 28px, 100% 100%;
        animation: tpProgressStripes 1.6s linear infinite;
    }
    #datatable_tugas_saya .progress-bar.bg-success,
    #datatable_tugas_atasan .progress-bar.bg-success,
    #datatable_semua_task .progress-bar.bg-success {
        background: linear-gradient(90deg, #10b981 0%, #22c55e 100%) !important;
        box-shadow: 0 0 8px rgba(16, 185, 129, 0.35);
    }
    #datatable_tugas_saya .btn-xs,
    #datatable_tugas_atasan .btn-xs,
    #datatable_semua_task .btn-xs {
        border-radius: 8px !important;
        font-weight: 700 !important;
        padding: 5px 10px !important;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    #datatable_tugas_saya .btn-xs:hover,
    #datatable_tugas_atasan .btn-xs:hover,
    #datatable_semua_task .btn-xs:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 10px -2px rgba(15, 23, 42, 0.2);
    }
    #datatable_tugas_saya .btn-xs.btn-warning,
    #datatable_tugas_atasan .btn-xs.btn-warning,
    #datatable_semua_task .btn-xs.btn-warning {
        background: linear-gradient(135deg, #f59e0b 0%, #ea580c 100%) !important;
        border-color: #d97706 !important;
        color: #ffffff !important;
    }

    /* Responsive Mobile Overrides */
    @media (max-width: 768px) {
        .tp-dt-sync-hint {
            display: none;
        }
        .tp-dt-scroll {
            max-height: 70vh;
        }
        .tp-workspace-card .dataTables_wrapper .dataTables_filter,
        .tp-lb-table-card .dataTables_wrapper .dataTables_filter {
            text-align: left !important;
        }
        .tp-arena-hero {
            padding: 15px 14px;
            border-radius: 14px 14px 0 0;
        }
        .tp-arena-title {
            font-size: 16.5px;
        }
        .tp-arena-actions {
            width: 100%;
        }
        .tp-arena-actions .btn {
            width: 100%;
            text-align: center;
        }
        .tp-control-card {
            padding: 12px 14px;
            border-radius: 0 0 14px 14px;
        }
        .tp-control-card form {
            width: 100%;
        }
        .tp-control-card form .form-control {
            width: 100% !important;
            min-width: 0 !important;
            margin-bottom: 6px;
        }
        .tp-control-card form .btn {
            width: 100%;
            margin-top: 4px;
        }
        .tp-kpi-card {
            padding: 12px 14px;
            flex-direction: column-reverse;
            align-items: flex-start;
            gap: 6px;
        }
        .tp-kpi-icon {
            width: 38px;
            height: 38px;
            font-size: 16px;
            border-radius: 10px;
        }
        .tp-kpi-card div:first-child > div:nth-child(2) {
            font-size: 20px !important;
        }
        .nav-tabs-tp {
            display: flex !important;
            flex-wrap: nowrap !important;
            overflow-x: auto !important;
            -webkit-overflow-scrolling: touch;
            padding-bottom: 8px !important;
            gap: 4px;
            margin-bottom: 14px;
        }
        .nav-tabs-tp > li {
            flex: 0 0 auto;
        }
        .nav-tabs-tp > li > a,
        .nav-tabs-tp .nav-link {
            padding: 8px 12px !important;
            font-size: 12px !important;
            white-space: nowrap;
        }
    }

    /* =========================================================================
       CUSTOM JOBDESK PICKER (MULTI-LINE WRAP, CLEAN SEARCHABLE, ANTI-TERPOTONG)
       ========================================================================= */
    #dropdown_jobdesk_container {
        position: relative;
        width: 100%;
    }
    #btn_toggle_jobdesk {
        white-space: normal;
        min-height: 44px;
        padding: 9px 14px;
        border: 1.5px solid #86efac;
        border-radius: 6px;
        background: #ffffff;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        width: 100%;
        text-align: left;
        transition: all 0.2s ease;
    }
    #btn_toggle_jobdesk:hover,
    #btn_toggle_jobdesk:focus {
        border-color: #10b981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
        outline: none;
        background: #fff;
    }
    #menu_jobdesk_list {
        position: absolute;
        top: 100%;
        left: 0;
        width: 100%;
        min-width: 100%;
        max-height: 380px;
        border: 1.5px solid #10b981;
        border-radius: 8px;
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.18);
        overflow: hidden;
        z-index: 100050;
        margin-top: 4px;
        background: #ffffff;
        display: none;
    }
    .jobdesk-choice-item {
        padding: 10px 14px;
        border-bottom: 1px solid #f1f5f9;
        cursor: pointer;
        line-height: 1.5;
        font-size: 13px;
        color: #1e293b;
        text-align: left;
        word-break: break-word;
        white-space: normal;
        transition: background-color 0.15s ease, color 0.15s ease;
    }
    .jobdesk-choice-item:hover {
        background-color: #ecfdf5 !important;
        color: #065f46 !important;
    }
    .jobdesk-group-header {
        background: #f0fdf4;
        color: #166534;
        font-weight: 700;
        font-size: 11.5px;
        padding: 7px 14px;
        border-top: 1px solid #bbf7d0;
        border-bottom: 1px solid #bbf7d0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        text-align: left;
    }

    /* =========================================================================
       MODERN ENTERPRISE MODAL FORM STYLING (#modalBuatTask & #modalDetailTask)
       ========================================================================= */
    #modalBuatTask .modal-content,
    #modalDetailTask .modal-content,
    #modalAturAtasanSaya .modal-content,
    #modalJadwalHrdSp3 .modal-content {
        border-radius: 16px !important;
        border: 1px solid #e2e8f0 !important;
        box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.25) !important;
        overflow: hidden;
        background: #ffffff;
    }
    #modalBuatTask .modal-header,
    #modalDetailTask .modal-header,
    #modalAturAtasanSaya .modal-header,
    #modalJadwalHrdSp3 .modal-header {
        background: #ffffff !important;
        border-bottom: 1px solid #f1f5f9 !important;
        padding: 18px 24px !important;
    }
    #modalBuatTask .modal-body,
    #modalDetailTask .modal-body,
    #modalAturAtasanSaya .modal-body,
    #modalJadwalHrdSp3 .modal-body {
        padding: 22px 24px !important;
        background: #ffffff;
    }
    #modalBuatTask .modal-footer,
    #modalDetailTask .modal-footer,
    #modalAturAtasanSaya .modal-footer,
    #modalJadwalHrdSp3 .modal-footer {
        background: #f8fafc !important;
        border-top: 1px solid #e2e8f0 !important;
        padding: 14px 24px !important;
    }
    #modalBuatTask .form-label-custom {
        font-size: 11.5px !important;
        font-weight: 700 !important;
        color: #475569 !important;
        letter-spacing: 0.35px !important;
        text-transform: uppercase !important;
        margin-bottom: 6px !important;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    #modalBuatTask .input-group-text {
        background: #f8fafc !important;
        border: 1.5px solid #cbd5e1 !important;
        border-right: none !important;
        color: #64748b !important;
        border-top-left-radius: 8px !important;
        border-bottom-left-radius: 8px !important;
        padding: 8px 12px !important;
        font-size: 13.5px !important;
        transition: all 0.2s ease;
    }
    #modalBuatTask .form-control {
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 8px !important;
        font-size: 13px !important;
        color: #1e293b !important;
        padding: 9px 13px !important;
        transition: all 0.2s ease;
        background-color: #ffffff;
    }
    #modalBuatTask .input-group .form-control {
        border-top-left-radius: 0 !important;
        border-bottom-left-radius: 0 !important;
        border-left: none !important;
    }
    #modalBuatTask .form-control:focus {
        border-color: #10b981 !important;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.15) !important;
        background-color: #ffffff !important;
    }
    #modalBuatTask .input-group:focus-within .input-group-text {
        border-color: #10b981 !important;
        color: #10b981 !important;
        background-color: #ecfdf5 !important;
    }
    #modalBuatTask .select2-container--default .select2-selection--multiple {
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 8px !important;
        min-height: 42px !important;
        padding: 4px 6px !important;
        transition: all 0.2s ease;
    }
    #modalBuatTask .select2-container--default.select2-container--focus .select2-selection--multiple {
        border-color: #10b981 !important;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.15) !important;
    }
    #modalBuatTask .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #ecfdf5 !important;
        border: 1px solid #a7f3d0 !important;
        color: #065f46 !important;
        border-radius: 6px !important;
        padding: 3px 8px !important;
        font-size: 12px !important;
        font-weight: 600 !important;
    }
    #modalBuatTask .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: #059669 !important;
        margin-right: 5px !important;
    }
    #modalBuatTask select.form-control {
        height: 42px !important;
        padding: 8px 12px !important;
        cursor: pointer;
        background-color: #ffffff !important;
    }

    /* =========================================================================
       ENERGIC & MODERN LEADERBOARD KPI UI
       ========================================================================= */
    .tp-lb-hero {
        position: relative;
        overflow: hidden;
        border-radius: 16px;
        padding: 20px 22px;
        margin-bottom: 18px;
        color: #ffffff;
        background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 48%, #0284c7 100%);
        box-shadow: 0 10px 28px rgba(2, 132, 199, 0.22);
    }
    .tp-lb-hero::before {
        content: '';
        position: absolute;
        top: -45px;
        right: -35px;
        width: 190px;
        height: 190px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(250, 204, 21, 0.28) 0%, rgba(250, 204, 21, 0) 70%);
        pointer-events: none;
    }
    .tp-lb-hero::after {
        content: '';
        position: absolute;
        bottom: -60px;
        left: 20%;
        width: 220px;
        height: 140px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(56, 189, 248, 0.22) 0%, rgba(56, 189, 248, 0) 70%);
        pointer-events: none;
    }
    .tp-lb-chip {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
        background: rgba(255, 255, 255, 0.14);
        border: 1px solid rgba(255, 255, 255, 0.22);
        color: #f8fafc;
        backdrop-filter: blur(4px);
    }
    .tp-lb-podium-card {
        position: relative;
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        padding: 16px 18px;
        height: 100%;
        box-shadow: 0 6px 20px rgba(15, 23, 42, 0.06);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        overflow: hidden;
    }
    .tp-lb-podium-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 14px 28px rgba(15, 23, 42, 0.11);
    }
    .tp-lb-podium-1 {
        border: 2px solid #facc15;
        background: linear-gradient(180deg, #fefce8 0%, #ffffff 42%);
        box-shadow: 0 10px 26px rgba(234, 179, 8, 0.18);
    }
    .tp-lb-podium-2 {
        border: 1.5px solid #cbd5e1;
        background: linear-gradient(180deg, #f8fafc 0%, #ffffff 42%);
    }
    .tp-lb-podium-3 {
        border: 1.5px solid #fdba74;
        background: linear-gradient(180deg, #fff7ed 0%, #ffffff 42%);
    }
    .tp-lb-mini-card {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        padding: 11px 14px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        box-shadow: 0 3px 10px rgba(15, 23, 42, 0.04);
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .tp-lb-mini-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 18px rgba(15, 23, 42, 0.08);
    }
    .tp-lb-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 12.5px;
        color: #ffffff;
        flex-shrink: 0;
        box-shadow: 0 2px 6px rgba(15, 23, 42, 0.14);
        letter-spacing: 0.4px;
    }
    .tp-lb-avatar-lg {
        width: 44px;
        height: 44px;
        font-size: 15px;
    }
    .tp-lb-table-card {
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        padding: 14px;
        box-shadow: 0 6px 22px rgba(15, 23, 42, 0.05);
    }
    #datatable_leaderboard {
        border-collapse: separate !important;
        border-spacing: 0 !important;
        border: none !important;
    }
    #datatable_leaderboard thead th {
        background: linear-gradient(180deg, #f8fafc 0%, #eef2f6 100%) !important;
        color: #1e293b;
        font-size: 11.5px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        border-top: none !important;
        border-left: none !important;
        border-right: 1px solid #e2e8f0 !important;
        border-bottom: 2px solid #cbd5e1 !important;
        vertical-align: middle;
        padding-top: 11px;
        padding-bottom: 11px;
    }
    #datatable_leaderboard thead th:last-child {
        border-right: none !important;
    }
    #datatable_leaderboard tbody tr {
        transition: background-color 0.15s ease;
    }
    #datatable_leaderboard tbody td {
        border-top: none !important;
        border-left: none !important;
        border-right: 1px solid #f1f5f9 !important;
        border-bottom: 1px solid #e2e8f0 !important;
    }
    #datatable_leaderboard tbody td:last-child {
        border-right: none !important;
    }
    #datatable_leaderboard tbody td:nth-child(2) {
        min-width: 195px;
        max-width: 245px;
        white-space: normal;
        word-break: break-word;
        overflow-wrap: anywhere;
    }
    #datatable_leaderboard tbody td:nth-child(3) {
        min-width: 145px;
        max-width: 195px;
        white-space: normal;
        word-break: break-word;
        overflow-wrap: anywhere;
    }
    #datatable_leaderboard tbody tr:hover td {
        background-color: #f8fafc !important;
    }
    #datatable_leaderboard tbody tr.tp-lb-rank-1 td:first-child { box-shadow: inset 4px 0 0 #eab308; }
    #datatable_leaderboard tbody tr.tp-lb-rank-2 td:first-child { box-shadow: inset 4px 0 0 #64748b; }
    #datatable_leaderboard tbody tr.tp-lb-rank-3 td:first-child { box-shadow: inset 4px 0 0 #ea580c; }
    #datatable_leaderboard tbody tr.tp-lb-rank-4 td:first-child { box-shadow: inset 4px 0 0 #2563eb; }
    #datatable_leaderboard tbody tr.tp-lb-rank-5 td:first-child { box-shadow: inset 4px 0 0 #7c3aed; }
    #datatable_leaderboard tbody tr.tp-lb-is-me td {
        background-color: #eff6ff !important;
    }
    .tp-lb-bar-track {
        width: 56px;
        height: 4px;
        background: #e2e8f0;
        border-radius: 999px;
        margin: 4px auto 3px;
        overflow: hidden;
    }
    .tp-lb-bar-fill {
        height: 100%;
        border-radius: 999px;
    }
    .tp-lb-score-pill {
        display: inline-block;
        padding: 3px 12px;
        border-radius: 10px;
        font-size: 17px;
        font-weight: 800;
        letter-spacing: -0.3px;
        transition: transform 0.18s ease;
    }

    /* =========================================================================
       ANIMASI ENERGETIK LEADERBOARD KPI
       ========================================================================= */
    @keyframes tpLbBgFlow {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
    @keyframes tpLbTrophyFloat {
        0%, 100% { transform: translateY(0) rotate(-3deg); }
        50% { transform: translateY(-4px) rotate(4deg); }
    }
    @keyframes tpLbCardPop {
        0% { opacity: 0; transform: translateY(18px) scale(0.97); }
        70% { opacity: 1; transform: translateY(-3px) scale(1.01); }
        100% { opacity: 1; transform: translateY(0) scale(1); }
    }
    @keyframes tpLbBarSweep {
        0% { transform: scaleX(0); }
        100% { transform: scaleX(1); }
    }
    @keyframes tpLbGoldPulse {
        0%, 100% { box-shadow: 0 0 0 0 rgba(234, 179, 8, 0.45); }
        50% { box-shadow: 0 0 0 7px rgba(234, 179, 8, 0); }
    }
    @keyframes tpLbSheen {
        0% { transform: translateX(-140%) skewX(-22deg); }
        45%, 100% { transform: translateX(260%) skewX(-22deg); }
    }
    @keyframes tpLbRowSlide {
        0% { opacity: 0; transform: translateX(-8px); }
        100% { opacity: 1; transform: translateX(0); }
    }
    .tp-lb-hero {
        background-size: 200% 200%;
        animation: tpLbBgFlow 10s ease infinite;
    }
    .tp-lb-trophy-anim {
        display: inline-block;
        animation: tpLbTrophyFloat 2.8s ease-in-out infinite;
    }
    .tp-lb-podium-1::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 45%;
        height: 100%;
        background: linear-gradient(90deg, rgba(255,255,255,0) 0%, rgba(255,255,255,0.55) 50%, rgba(255,255,255,0) 100%);
        transform: translateX(-140%) skewX(-22deg);
        animation: tpLbSheen 5.2s ease-in-out infinite;
        pointer-events: none;
    }
    .tp-lb-pin-1 {
        animation: tpLbGoldPulse 2.4s ease-in-out infinite;
    }
    #tab_leaderboard.tp-lb-play .tp-lb-anim-card {
        opacity: 0;
        animation: tpLbCardPop 0.55s cubic-bezier(0.22, 1, 0.36, 1) forwards;
    }
    #tab_leaderboard.tp-lb-play .tp-lb-delay-1 { animation-delay: 0.05s; }
    #tab_leaderboard.tp-lb-play .tp-lb-delay-2 { animation-delay: 0.14s; }
    #tab_leaderboard.tp-lb-play .tp-lb-delay-3 { animation-delay: 0.22s; }
    #tab_leaderboard.tp-lb-play .tp-lb-delay-4 { animation-delay: 0.30s; }
    #tab_leaderboard.tp-lb-play .tp-lb-delay-5 { animation-delay: 0.36s; }
    #tab_leaderboard.tp-lb-play .tp-lb-delay-table { animation-delay: 0.42s; }
    .tp-lb-bar-fill,
    .tp-lb-podium-bar-fill {
        transform-origin: left center;
    }
    #tab_leaderboard.tp-lb-play .tp-lb-bar-fill,
    #tab_leaderboard.tp-lb-play .tp-lb-podium-bar-fill {
        animation: tpLbBarSweep 0.75s cubic-bezier(0.22, 1, 0.36, 1) backwards;
        animation-delay: 0.25s;
    }
    #tab_leaderboard.tp-lb-play #datatable_leaderboard tbody tr {
        animation: tpLbRowSlide 0.38s ease backwards;
    }
    #datatable_leaderboard tbody tr:hover .tp-lb-score-pill {
        transform: scale(1.06);
    }
    #datatable_leaderboard tbody tr:hover .tp-lb-avatar {
        transform: scale(1.08);
        transition: transform 0.18s ease;
    }

    @keyframes tpLbLivePing {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(74, 222, 128, 0.85); }
        70% { transform: scale(1.1); box-shadow: 0 0 0 8px rgba(74, 222, 128, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(74, 222, 128, 0); }
    }
    @keyframes tpLbBoltZap {
        0%, 100% { transform: scale(1) rotate(0deg); }
        20% { transform: scale(1.22) rotate(-10deg); }
        40% { transform: scale(1.12) rotate(8deg); }
        60% { transform: scale(1) rotate(0deg); }
    }
    @keyframes tpLbSlowSpin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    @keyframes tpLbSparkRise {
        0% { transform: translateY(28px) scale(0.4); opacity: 0; }
        25% { opacity: 0.75; }
        80% { opacity: 0.55; }
        100% { transform: translateY(-95px) scale(1.15); opacity: 0; }
    }
    @keyframes tpLbChampionFloat {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-5px); }
    }
    @keyframes tpLbMedalBob {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        30% { transform: translateY(-2px) rotate(-6deg); }
        60% { transform: translateY(-1px) rotate(5deg); }
    }
    @keyframes tpLbProgressGlint {
        0% { left: -40%; }
        100% { left: 130%; }
    }
    @keyframes tpLbStarTwinkle {
        0%, 100% { transform: scale(1); filter: brightness(1); }
        50% { transform: scale(1.18); filter: brightness(1.25); }
    }
    #tab_leaderboard {
        position: relative;
    }
    .tp-lb-live-dot {
        display: inline-block;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #4ade80;
        animation: tpLbLivePing 1.8s infinite;
    }
    .tp-lb-bolt-anim {
        display: inline-block;
        animation: tpLbBoltZap 2.6s ease-in-out infinite;
    }
    .tp-lb-spin-slow {
        display: inline-block;
        animation: tpLbSlowSpin 8s linear infinite;
    }
    .tp-lb-spark {
        position: absolute;
        bottom: -10px;
        border-radius: 50%;
        pointer-events: none;
        background: radial-gradient(circle, rgba(255,255,255,0.9) 0%, rgba(250,204,21,0.5) 55%, rgba(250,204,21,0) 100%);
        animation: tpLbSparkRise linear infinite;
    }
    .tp-lb-spark.s1 { left: 8%; width: 7px; height: 7px; animation-duration: 4.5s; animation-delay: 0s; }
    .tp-lb-spark.s2 { left: 26%; width: 5px; height: 5px; animation-duration: 5.4s; animation-delay: 1.1s; }
    .tp-lb-spark.s3 { left: 52%; width: 8px; height: 8px; animation-duration: 4.8s; animation-delay: 0.6s; }
    .tp-lb-spark.s4 { left: 74%; width: 6px; height: 6px; animation-duration: 5.8s; animation-delay: 1.9s; }
    .tp-lb-spark.s5 { left: 90%; width: 7px; height: 7px; animation-duration: 4.2s; animation-delay: 0.3s; }
    .tp-lb-podium-1 {
        animation: tpLbChampionFloat 4.2s ease-in-out infinite;
    }
    .tp-lb-podium-1:hover {
        animation-play-state: paused;
        transform: translateY(-6px);
    }
    .tp-lb-medal-icon {
        display: inline-block;
        animation: tpLbMedalBob 2.6s ease-in-out infinite;
    }
    .tp-lb-podium-bar-fill {
        position: relative;
        overflow: hidden;
    }
    .tp-lb-podium-bar-fill::after {
        content: '';
        position: absolute;
        top: 0;
        left: -40%;
        width: 35%;
        height: 100%;
        background: linear-gradient(90deg, rgba(255,255,255,0), rgba(255,255,255,0.7), rgba(255,255,255,0));
        animation: tpLbProgressGlint 2.5s ease-in-out infinite;
    }
    #tab_leaderboard .tp-star-display i.fas {
        display: inline-block;
        animation: tpLbStarTwinkle 2.8s ease-in-out infinite;
    }
    #tab_leaderboard .tp-star-display i.fas:nth-child(2) { animation-delay: 0.2s; }
    #tab_leaderboard .tp-star-display i.fas:nth-child(3) { animation-delay: 0.4s; }
    #tab_leaderboard .tp-star-display i.fas:nth-child(4) { animation-delay: 0.6s; }
    #tab_leaderboard .tp-star-display i.fas:nth-child(5) { animation-delay: 0.8s; }
    @media (prefers-reduced-motion: reduce) {
        .tp-arena-live-dot, .tp-arena-chip.chip-urgent, .tp-btn-cta-arena::after,
        .tp-prio-Urgent, .nav-tabs-tp .badge-warning,
        #datatable_tugas_saya .progress-bar, #datatable_tugas_atasan .progress-bar, #datatable_semua_task .progress-bar,
        .tp-lb-hero, .tp-lb-trophy-anim, .tp-lb-podium-1::after, .tp-lb-pin-1,
        .tp-lb-live-dot, .tp-lb-bolt-anim, .tp-lb-spin-slow, .tp-lb-spark,
        .tp-lb-podium-1, .tp-lb-medal-icon, .tp-lb-podium-bar-fill::after,
        #tab_leaderboard .tp-star-display i.fas,
        #tab_leaderboard.tp-lb-play .tp-lb-anim-card,
        #tab_leaderboard.tp-lb-play .tp-lb-bar-fill,
        #tab_leaderboard.tp-lb-play .tp-lb-podium-bar-fill,
        #tab_leaderboard.tp-lb-play #datatable_leaderboard tbody tr {
            animation: none !important;
            opacity: 1 !important;
            transform: none !important;
        }
    }
</style>

<?php
function renderStarsHtml($rating)
{
    $r = (int)round((float)$rating);
    $html = '<span class="tp-star-display" title="Rating ' . $rating . ' / 5">';
    for ($i = 1; $i <= 5; $i++) {
        if ($i <= $r) {
            $html .= '<i class="fas fa-star"></i>';
        } else {
            $html .= '<i class="far fa-star tp-star-empty"></i>';
        }
    }
    $html .= '</span>';
    return $html;
}

function renderStatusTaskBadge($st)
{
    $st = (int)$st;
    switch ($st) {
        case 0:
            return '<span class="tp-status-badge tp-st-0"><i class="far fa-clock"></i> Baru / Belum Mulai</span>';
        case 1:
            return '<span class="tp-status-badge tp-st-1"><i class="fas fa-spinner fa-spin"></i> Sedang Dikerjakan</span>';
        case 2:
            return '<span class="tp-status-badge tp-st-2"><i class="fas fa-hourglass-half"></i> Menunggu Penilaian</span>';
        case 3:
            return '<span class="tp-status-badge tp-st-3"><i class="fas fa-redo"></i> Perlu Revisi</span>';
        case 4:
            return '<span class="tp-status-badge tp-st-4"><i class="fas fa-check-circle"></i> Selesai & Dinilai</span>';
        case 5:
            return '<span class="tp-status-badge tp-st-5" style="background:#fef3c7; color:#92400e; border:1px solid #fde68a;"><i class="fas fa-hourglass-start"></i> Menunggu ACC HRD</span>';
        case 6:
            return '<span class="tp-status-badge tp-st-6" style="background:#fee2e2; color:#991b1b; border:1px solid #fecaca;"><i class="fas fa-times-circle"></i> Ditolak HRD</span>';
        default:
            return '<span class="tp-status-badge tp-st-0">Draft</span>';
    }
}

function tpTablePreviewText($text, $limit = 110)
{
    $clean = preg_replace('/\s+/u', ' ', trim((string)$text));
    if ($clean === '') {
        return '';
    }
    return mb_strimwidth($clean, 0, $limit, '...');
}

$namaBulanList = [
    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
];
?>

<header class="page-header">
    <h2><i class="fas fa-tasks text-primary mr-2"></i><?= htmlspecialchars($page_title) ?></h2>
    <div class="right-wrapper text-right">
        <ol class="breadcrumbs">
            <li><a href="<?= base_url('dashboard') ?>"><i class="fas fa-home"></i></a></li>
            <li><span>Task & Proyek Kerja</span></li>
        </ol>
        <a class="sidebar-right-toggle"><i class="fas fa-chevron-left"></i></a>
    </div>
</header>

<div class="row">
    <div class="col">

    <?php
    $canAssignToAll   = true;
    $isAdminUser      = (!empty($isHrdAdmin) || !empty($isAdmin) || isAdmin() || (int)$myId === 1);
    $jmlSupervisiSaya = count($list_bawahan);
    $canGiveTask      = true; // Sekarang semua karyawan bisa membuat / mengajukan task
    $penerimaList     = !empty($list_semua_karyawan_all) ? $list_semua_karyawan_all : $list_semua_karyawan;

    // Hitung posisi kompetitif user di Leaderboard KPI saat ini
    $scoredLb = [];
    if (!empty($leaderboard)) {
        foreach ($leaderboard as $lbItem) {
            if (isset($lbItem->skor_akhir) && $lbItem->skor_akhir !== null) {
                $scoredLb[] = $lbItem;
            }
        }
    }
    $totalScoredLb = count($scoredLb);
    $myRankPos     = 0;
    $myLbRow       = null;
    $gapToAbove    = null;
    $aboveName     = '';
    foreach ($scoredLb as $idxLb => $lbItem) {
        if (in_array((int)$lbItem->pengguna_id, $myReceiverIds, true)) {
            $myRankPos = $idxLb + 1;
            $myLbRow   = $lbItem;
            if ($idxLb > 0) {
                $prevItem   = $scoredLb[$idxLb - 1];
                $gapToAbove = max(0.0, (float)$prevItem->skor_akhir - (float)$lbItem->skor_akhir);
                $aboveName  = (string)($prevItem->nama ?? '');
            }
            break;
        }
    }
    $lbActiveLabel = !empty($lbLabel) ? (string)$lbLabel : (string)$semLabel;
    $lbMonthlyMode = !empty($lbIsMonthly);
    ?>

    <!-- ARENA HERO BANNER & ACTION COMMAND -->
    <div class="tp-arena-hero">
        <div class="tp-arena-hero-inner">
            <div>
                <div class="tp-arena-live-pill">
                    <span class="tp-arena-live-dot"></span>
                    Performance &amp; Task Arena &bull; <?= htmlspecialchars($lbActiveLabel) ?>
                </div>

                <?php if ($myRankPos > 0 && $myLbRow !== null): ?>
                    <div class="tp-arena-title">
                        <span>Klasemen KPI <?= $lbMonthlyMode ? '(Bulanan)' : '' ?> Anda:</span>
                        <span class="tp-arena-rank-pill">
                            <?php if ($myRankPos === 1): ?>
                                <i class="fas fa-crown"></i> #1 PEMIMPIN KLASEMEN
                            <?php elseif ($myRankPos <= 3): ?>
                                <i class="fas fa-trophy"></i> #<?= $myRankPos ?> TOP PODIUM / <?= $totalScoredLb ?>
                            <?php else: ?>
                                <i class="fas fa-bolt"></i> Rank #<?= $myRankPos ?> / <?= $totalScoredLb ?> Karyawan
                            <?php endif; ?>
                        </span>
                        <?php if ($myRankPos > 1 && $gapToAbove !== null): ?>
                            <span class="tp-arena-gap-pill">
                                <i class="fas fa-fire-alt"></i> Selisih <?= number_format($gapToAbove, 1) ?> poin ke #<?= $myRankPos - 1 ?> (<?= htmlspecialchars($aboveName) ?>)
                            </span>
                        <?php elseif ($myRankPos === 1): ?>
                            <span class="tp-arena-gap-pill" style="background: rgba(16, 185, 129, 0.22); border-color: rgba(52, 211, 153, 0.5); color: #d1fae5;">
                                <i class="fas fa-shield-alt"></i> Pertahankan Tahta #1!
                            </span>
                        <?php endif; ?>
                    </div>
                    <div class="tp-arena-sub">
                        Selesaikan task sebelum deadline dan kejar <strong>Rating Bintang 5 ⭐</strong> untuk melesat di klasemen tim.
                    </div>
                <?php else: ?>
                    <div class="tp-arena-title">
                        <span>Arena Eksekusi Proyek &amp; Evaluasi Bintang 5</span>
                        <span class="tp-arena-rank-pill">
                            <i class="fas fa-rocket"></i> LIVE PERFORMANCE HUB
                        </span>
                    </div>
                    <div class="tp-arena-sub">
                        Kelola penugasan, ACC pengajuan mandiri, dan dorong kompetisi positif lewat <strong>Rating Bintang 1–5 ⭐</strong>.
                    </div>
                <?php endif; ?>

                <div class="tp-arena-chips">
                    <?php if ($myLbRow !== null && $myLbRow->skor_akhir !== null): ?>
                        <span class="tp-arena-chip">
                            <i class="fas fa-chart-line text-info"></i>
                            Skor Akhir: <strong><?= number_format((float)$myLbRow->skor_akhir, 1) ?></strong> (Grade <?= htmlspecialchars($myLbRow->grade) ?>)
                        </span>
                        <span class="tp-arena-chip">
                            <i class="fas fa-star text-warning"></i>
                            Rating Agregat: <strong><?= number_format((float)$myLbRow->rating_agregat, 2) ?> / 5.0</strong>
                        </span>
                    <?php endif; ?>
                    <span class="tp-arena-chip">
                        <i class="fas fa-tasks text-primary"></i>
                        Task Aktif Saya: <strong><?= (int)$kpi['my_active'] ?></strong>
                    </span>
                    <?php if ((int)$kpi['need_review'] > 0): ?>
                        <span class="tp-arena-chip chip-urgent">
                            <i class="fas fa-bolt text-warning"></i>
                            <strong><?= (int)$kpi['need_review'] ?> Task</strong> Menunggu Rating Anda
                        </span>
                    <?php endif; ?>
                    <?php if (!empty($kpi['waiting_acc_hrd']) && (int)$kpi['waiting_acc_hrd'] > 0): ?>
                        <span class="tp-arena-chip chip-urgent">
                            <i class="fas fa-hourglass-start text-warning"></i>
                            <strong><?= (int)$kpi['waiting_acc_hrd'] ?> Pengajuan</strong> Butuh ACC HRD
                        </span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="tp-arena-actions">
                <button type="button" class="btn btn-sm tp-btn-lb-jump" id="btnJumpLeaderboard">
                    <i class="fas fa-trophy mr-1"></i> Klasemen Leaderboard
                </button>
                <button type="button" class="btn btn-sm tp-btn-cta-arena" id="btnOpenModalBuatTask">
                    <i class="fas fa-plus-circle mr-1"></i> + Buat / Ajukan Task Proyek
                </button>
            </div>
        </div>
    </div>

    <!-- KARTU KONTROL FILTER & STATUS SUPERVISI -->
    <div class="tp-control-card">
        <div class="d-flex justify-content-between align-items-center flex-wrap" style="gap: 12px;">
            <!-- Filter Periode -->
            <form method="GET" action="<?= base_url('task_proyek') ?>" class="form-inline" style="gap: 8px; display: flex; flex-wrap: wrap; align-items: center;">
                <?php if (!empty($switch) && $switch === 'kepegawaian'): ?>
                    <input type="hidden" name="nav" value="kepegawaian">
                <?php endif; ?>
                <?php if (!empty($viewMode)): ?>
                    <input type="hidden" name="mode" value="<?= htmlspecialchars($viewMode) ?>">
                <?php endif; ?>
                <input type="hidden" name="tab" id="tpTopFilterTab" value="<?= $openTabLeaderboard ? 'leaderboard' : '' ?>">

                <label class="font-weight-bold text-dark mr-1 mb-0" style="font-size: 13px;">
                    <i class="far fa-calendar-alt text-primary mr-1"></i> Periode:
                </label>
                <select name="semester" id="tpTopFilterSemester" class="form-control form-control-sm" style="min-width: 185px; border-radius: 8px;">
                    <option value="1" <?= ((string)($filterSemester ?? '') === '1') ? 'selected' : '' ?>>Semester 1 (Januari – Juni)</option>
                    <option value="2" <?= ((string)($filterSemester ?? '') === '2') ? 'selected' : '' ?>>Semester 2 (Juli – Desember)</option>
                </select>

                <select name="bulan" id="tpTopFilterBulan" class="form-control form-control-sm" style="min-width: 165px; border-radius: 8px;" title="Filter Leaderboard KPI per Bulan">
                    <option value="">-- Semua Bulan (Leaderboard) --</option>
                    <?php foreach ($namaBulanList as $bNum => $bNama): ?>
                        <option value="<?= $bNum ?>" <?= ((string)($filterBulan ?? '') === (string)$bNum) ? 'selected' : '' ?>>Bulan <?= $bNama ?></option>
                    <?php endforeach; ?>
                </select>

                <select name="tahun" class="form-control form-control-sm" style="min-width: 90px; border-radius: 8px;">
                    <?php for ($y = date('Y') + 1; $y >= 2024; $y--): ?>
                        <option value="<?= $y ?>" <?= ((string)$filterTahun === (string)$y) ? 'selected' : '' ?>><?= $y ?></option>
                    <?php endfor; ?>
                </select>

                <button type="submit" class="btn btn-sm btn-primary" style="border-radius: 8px; font-weight: 700; padding: 5px 14px;">
                    <i class="fas fa-filter mr-1"></i> Terapkan
                </button>
                <a href="<?= base_url('task_proyek' . ($switch === 'kepegawaian' ? '?nav=kepegawaian' : '')) ?>" class="btn btn-sm btn-default" style="border-radius: 8px; padding: 5px 12px; border: 1px solid #cbd5e1; font-weight: 600;">
                    Reset
                </a>
            </form>

            <div class="d-none d-md-flex align-items-center" style="gap: 8px;">
                <span class="badge badge-light border px-2 py-1" style="font-size: 11.5px; border-radius: 8px; color: #334155;">
                    <i class="fas fa-globe text-info mr-1"></i> Total Task Tim: <strong><?= !empty($semua_task) ? count($semua_task) : 0 ?></strong>
                </span>
            </div>
        </div>

        <hr style="margin: 12px 0 10px 0; border: 0; border-top: 1px solid #f1f5f9;">

        <!-- Status Hirarki (Atasan Langsung & Karyawan Dibawahi) -->
        <div class="d-flex align-items-center justify-content-between flex-wrap" style="gap: 12px;">
            <div class="d-flex align-items-center flex-wrap" style="gap: 14px 20px;">
                <!-- Atasan Langsung -->
                <div class="d-flex align-items-center" style="font-size: 13px;">
                    <span class="text-muted mr-1">
                        <i class="fas fa-user-shield text-primary mr-1"></i> <strong>Atasan Langsung:</strong>
                    </span>
                    <?php if (!empty($me->nama_atasan)): ?>
                        <span class="badge badge-light border font-weight-bold px-2 py-1 text-primary" style="font-size: 12px; background: #eff6ff; border-color: #bfdbfe !important; border-radius: 6px;">
                            <?= htmlspecialchars($me->nama_atasan) ?><?= !empty($me->jabatan_atasan) ? ' (' . htmlspecialchars($me->jabatan_atasan) . ')' : '' ?>
                        </span>
                        <button type="button" class="btn btn-xs btn-outline-primary ml-2 font-weight-bold" data-toggle="modal" data-target="#modalAturAtasanSaya" style="border-radius: 6px; font-size: 11px; padding: 2px 8px;">
                            <i class="fas fa-user-edit mr-1"></i> Ubah
                        </button>
                    <?php elseif ($isAdminUser || (int)$myId === 1 || $jmlSupervisiSaya > 0): ?>
                        <span class="badge badge-success px-2 py-1" style="font-size: 11px; border-radius: 6px;">
                            <i class="fas fa-crown text-warning mr-1"></i> Pimpinan / Struktur Tertinggi
                        </span>
                        <button type="button" class="btn btn-xs btn-outline-secondary ml-2 font-weight-bold" data-toggle="modal" data-target="#modalAturAtasanSaya" style="border-radius: 6px; font-size: 11px; padding: 2px 8px;" title="Atur jika memiliki atasan langsung tertentu">
                            <i class="fas fa-cog mr-1"></i> Atur
                        </button>
                    <?php else: ?>
                        <span class="badge badge-warning px-2 py-1" style="font-size: 11px; border-radius: 6px;">Belum Diatur</span>
                        <button type="button" class="btn btn-xs btn-outline-primary ml-2 font-weight-bold" data-toggle="modal" data-target="#modalAturAtasanSaya" style="border-radius: 6px; font-size: 11px; padding: 2px 8px;">
                            <i class="fas fa-user-edit mr-1"></i> Pilih Atasan
                        </button>
                    <?php endif; ?>
                </div>

                <div class="d-none d-md-block" style="width: 1px; height: 16px; background: #cbd5e1;"></div>

                <!-- Karyawan Dibawahi -->
                <div class="d-flex align-items-center" style="font-size: 13px;">
                    <span class="text-muted mr-1">
                        <i class="fas fa-users text-success mr-1"></i> <strong>Karyawan Dibawahi:</strong>
                    </span>
                    <span class="badge <?= $jmlSupervisiSaya > 0 ? 'badge-success' : 'badge-secondary' ?> px-2 py-1" style="font-size: 12px; font-weight: 600; border-radius: 6px;">
                        <?= $jmlSupervisiSaya ?> Karyawan
                    </span>
                    <?php if ($canAssignToAll): ?>
                        <span class="badge badge-info px-2 py-1 ml-2" title="Anda memiliki akses untuk menugaskan task ke seluruh pegawai" style="font-size: 11px; border-radius: 6px;">
                            <i class="fas fa-shield-alt mr-1"></i> Akses Penugasan (Bisa Beri Task ke Semua Pegawai)
                        </span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="text-muted small d-none d-lg-block" style="font-size: 12px;">
                <i class="fas fa-star text-warning mr-1"></i> Evaluasi Kinerja dengan <strong>Rating Bintang 1–5 ⭐</strong>
            </div>
        </div>
    </div>

    <?php
    $isTopLeaderOnly = ($isAdminUser || (empty($me->nama_atasan) && $jmlSupervisiSaya > 0)) && (count($tugas_saya) === 0);
    $isStaffOnly     = !$canGiveTask && (count($tugas_atasan) === 0);
    ?>

    <!-- 4 KARTU KPI RINGKASAN -->
    <div class="row">
        <?php if ($isTopLeaderOnly): ?>
            <!-- Mode Pimpinan Tertinggi / Direksi (Fokus Supervisi Tim) -->
            <div class="col-6 col-md-3 mb-3">
                <div class="tp-kpi-card kpi-blue">
                    <div>
                        <div class="text-muted small font-weight-bold text-uppercase">Penugasan Berjalan Tim</div>
                        <div style="font-size: 26px; font-weight: 800; color: #1e293b;"><?= (int)($kpi['given_active'] ?? 0) ?> <small style="font-size: 13px; font-weight: 600; color:#64748b;">Task</small></div>
                        <div class="small text-primary">Sedang Dikerjakan Karyawan</div>
                    </div>
                    <div class="tp-kpi-icon" style="background: #eff6ff; color: #2563eb;">
                        <i class="fas fa-tasks"></i>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-3 mb-3">
                <div class="tp-kpi-card kpi-amber">
                    <div>
                        <div class="text-muted small font-weight-bold text-uppercase">Menunggu Review Saya</div>
                        <div style="font-size: 26px; font-weight: 800; color: #d97706;" id="kpi_need_review"><?= (int)$kpi['need_review'] ?> <small style="font-size: 13px; font-weight: 600; color:#64748b;">Task</small></div>
                        <div class="small text-warning">Siap Diberi Rating Bintang ⭐</div>
                    </div>
                    <div class="tp-kpi-icon" style="background: #fef3c7; color: #d97706;">
                        <i class="fas fa-user-check"></i>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-3 mb-3">
                <div class="tp-kpi-card kpi-green">
                    <div>
                        <div class="text-muted small font-weight-bold text-uppercase">Penugasan Selesai Tim</div>
                        <div style="font-size: 26px; font-weight: 800; color: #15803d;"><?= (int)($kpi['given_done'] ?? 0) ?> <small style="font-size: 13px; font-weight: 600; color:#64748b;">Selesai</small></div>
                        <div class="small text-success">Sudah Tuntas & Dinilai</div>
                    </div>
                    <div class="tp-kpi-icon" style="background: #dcfce7; color: #16a34a;">
                        <i class="fas fa-check-double"></i>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-3 mb-3">
                <div class="tp-kpi-card kpi-purple">
                    <div>
                        <div class="text-muted small font-weight-bold text-uppercase">Karyawan Dibawahi</div>
                        <div style="font-size: 26px; font-weight: 800; color: #7c3aed;"><?= $jmlSupervisiSaya ?> <small style="font-size: 13px; font-weight: 600; color:#64748b;">Pegawai</small></div>
                        <div class="small" style="color: #7c3aed;">Tim Supervisi Langsung</div>
                    </div>
                    <div class="tp-kpi-icon" style="background: #f3e8ff; color: #7c3aed;">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>

        <?php elseif ($isStaffOnly): ?>
            <!-- Mode Karyawan / Staf Pelaksana Murni -->
            <div class="col-6 col-md-3 mb-3">
                <div class="tp-kpi-card kpi-blue">
                    <div>
                        <div class="text-muted small font-weight-bold text-uppercase">Tugas Aktif Saya</div>
                        <div style="font-size: 26px; font-weight: 800; color: #1e293b;"><?= (int)$kpi['my_active'] ?> <small style="font-size: 13px; font-weight: 600; color:#64748b;">Task</small></div>
                        <div class="small text-primary">Sebagai Pelaksana</div>
                    </div>
                    <div class="tp-kpi-icon" style="background: #eff6ff; color: #2563eb;">
                        <i class="fas fa-tasks"></i>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-3 mb-3">
                <div class="tp-kpi-card kpi-amber">
                    <div>
                        <div class="text-muted small font-weight-bold text-uppercase">Menunggu Rating Atasan</div>
                        <div style="font-size: 26px; font-weight: 800; color: #d97706;"><?= (int)($kpi['my_waiting_review'] ?? 0) ?> <small style="font-size: 13px; font-weight: 600; color:#64748b;">Task</small></div>
                        <div class="small text-warning">Sudah Disubmit Selesai</div>
                    </div>
                    <div class="tp-kpi-icon" style="background: #fef3c7; color: #d97706;">
                        <i class="fas fa-hourglass-half"></i>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-3 mb-3">
                <div class="tp-kpi-card kpi-green">
                    <div>
                        <div class="text-muted small font-weight-bold text-uppercase">Tugas Selesai Saya</div>
                        <div style="font-size: 26px; font-weight: 800; color: #15803d;"><?= (int)$kpi['my_done'] ?> <small style="font-size: 13px; font-weight: 600; color:#64748b;">Selesai</small></div>
                        <div class="small text-success">Sudah Disetujui Atasan</div>
                    </div>
                    <div class="tp-kpi-icon" style="background: #dcfce7; color: #16a34a;">
                        <i class="fas fa-check-double"></i>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-3 mb-3">
                <div class="tp-kpi-card kpi-purple">
                    <div>
                        <div class="text-muted small font-weight-bold text-uppercase">KPI Gabungan Saya</div>
                        <?php if (!empty($kpi['my_combined']) && $kpi['my_combined']->skor_akhir !== null): ?>
                            <div style="font-size: 24px; font-weight: 800; color: <?= $kpi['my_combined']->grade_color ?>;">
                                <?= number_format($kpi['my_combined']->skor_akhir, 1) ?> <small style="font-size: 13px; color:#64748b;">(Grade <?= $kpi['my_combined']->grade ?>)</small>
                            </div>
                            <div class="small">
                                <?= renderStarsHtml($kpi['my_combined']->rating_agregat) ?>
                                <span class="badge <?= $kpi['my_combined']->grade_badge ?> ml-1"><?= $kpi['my_combined']->grade_label ?></span>
                                <?php if (!empty($kpi['my_combined']->sp_level)): ?>
                                    <span class="badge badge-danger ml-1" title="Plafon maks <?= (int)$kpi['my_combined']->sp_cap ?>"><?= htmlspecialchars($kpi['my_combined']->sp_label) ?> (Maks <?= (int)$kpi['my_combined']->sp_cap ?>)</span>
                                <?php endif; ?>
                            </div>
                            <div class="text-muted" style="font-size: 10.5px;">Bobot: <?= htmlspecialchars($kpi['my_combined']->komposisi) ?></div>
                            <?php if (!empty($kpi['my_combined']->is_capped_sp)): ?>
                                <div class="text-danger font-weight-bold" style="font-size: 10.5px;">Hitungan <?= number_format($kpi['my_combined']->skor_akhir_raw, 1) ?> dibatasi plafon <?= htmlspecialchars($kpi['my_combined']->sp_label) ?> (<?= (int)$kpi['my_combined']->sp_cap ?>)</div>
                            <?php endif; ?>
                        <?php else: ?>
                            <div style="font-size: 24px; font-weight: 800; color: #f59e0b;">
                                <?= number_format((float)$kpi['my_avg_star'], 2) ?> <small style="font-size: 13px; color:#64748b;">/ 5.0</small>
                            </div>
                            <div><?= renderStarsHtml($kpi['my_avg_star']) ?> <small class="text-muted">(<?= (int)$kpi['my_star_cnt'] ?> dinilai)</small></div>
                        <?php endif; ?>
                    </div>
                    <div class="tp-kpi-icon" style="background: #fffbeb; color: #f59e0b;">
                        <i class="fas fa-medal"></i>
                    </div>
                </div>
            </div>

        <?php else: ?>
            <!-- Mode Manajer / Supervisor (Memiliki Atasan & Memiliki Bawahan) -->
            <div class="col-6 col-md-3 mb-3">
                <div class="tp-kpi-card kpi-blue">
                    <div>
                        <div class="text-muted small font-weight-bold text-uppercase">Tugas Aktif Saya</div>
                        <div style="font-size: 26px; font-weight: 800; color: #1e293b;"><?= (int)$kpi['my_active'] ?> <small style="font-size: 13px; font-weight: 600; color:#64748b;">Task</small></div>
                        <div class="small text-primary">Sebagai Pelaksana</div>
                    </div>
                    <div class="tp-kpi-icon" style="background: #eff6ff; color: #2563eb;">
                        <i class="fas fa-tasks"></i>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-3 mb-3">
                <div class="tp-kpi-card kpi-amber">
                    <div>
                        <div class="text-muted small font-weight-bold text-uppercase">Menunggu Review Saya</div>
                        <div style="font-size: 26px; font-weight: 800; color: #d97706;" id="kpi_need_review"><?= (int)$kpi['need_review'] ?> <small style="font-size: 13px; font-weight: 600; color:#64748b;">Task</small></div>
                        <div class="small text-warning">Siap Diberi Rating Bintang ⭐</div>
                    </div>
                    <div class="tp-kpi-icon" style="background: #fef3c7; color: #d97706;">
                        <i class="fas fa-user-check"></i>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-3 mb-3">
                <div class="tp-kpi-card kpi-green">
                    <div>
                        <div class="text-muted small font-weight-bold text-uppercase">Tugas Selesai Saya</div>
                        <div style="font-size: 26px; font-weight: 800; color: #15803d;"><?= (int)$kpi['my_done'] ?> <small style="font-size: 13px; font-weight: 600; color:#64748b;">Selesai</small></div>
                        <div class="small text-success">Sudah Disetujui Atasan</div>
                    </div>
                    <div class="tp-kpi-icon" style="background: #dcfce7; color: #16a34a;">
                        <i class="fas fa-check-double"></i>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-3 mb-3">
                <div class="tp-kpi-card kpi-purple">
                    <div>
                        <div class="text-muted small font-weight-bold text-uppercase">KPI Gabungan Saya</div>
                        <?php if (!empty($kpi['my_combined']) && $kpi['my_combined']->skor_akhir !== null): ?>
                            <div style="font-size: 24px; font-weight: 800; color: <?= $kpi['my_combined']->grade_color ?>;">
                                <?= number_format($kpi['my_combined']->skor_akhir, 1) ?> <small style="font-size: 13px; color:#64748b;">(Grade <?= $kpi['my_combined']->grade ?>)</small>
                            </div>
                            <div class="small">
                                <?= renderStarsHtml($kpi['my_combined']->rating_agregat) ?>
                                <span class="badge <?= $kpi['my_combined']->grade_badge ?> ml-1"><?= $kpi['my_combined']->grade_label ?></span>
                                <?php if (!empty($kpi['my_combined']->sp_level)): ?>
                                    <span class="badge badge-danger ml-1" title="Plafon maks <?= (int)$kpi['my_combined']->sp_cap ?>"><?= htmlspecialchars($kpi['my_combined']->sp_label) ?> (Maks <?= (int)$kpi['my_combined']->sp_cap ?>)</span>
                                <?php endif; ?>
                            </div>
                            <div class="text-muted" style="font-size: 10.5px;">Bobot: <?= htmlspecialchars($kpi['my_combined']->komposisi) ?></div>
                            <?php if (!empty($kpi['my_combined']->is_capped_sp)): ?>
                                <div class="text-danger font-weight-bold" style="font-size: 10.5px;">Hitungan <?= number_format($kpi['my_combined']->skor_akhir_raw, 1) ?> dibatasi plafon <?= htmlspecialchars($kpi['my_combined']->sp_label) ?> (<?= (int)$kpi['my_combined']->sp_cap ?>)</div>
                            <?php endif; ?>
                        <?php else: ?>
                            <div style="font-size: 24px; font-weight: 800; color: #f59e0b;">
                                <?= number_format((float)$kpi['my_avg_star'], 2) ?> <small style="font-size: 13px; color:#64748b;">/ 5.0</small>
                            </div>
                            <div><?= renderStarsHtml($kpi['my_avg_star']) ?> <small class="text-muted">(<?= (int)$kpi['my_star_cnt'] ?> dinilai)</small></div>
                        <?php endif; ?>
                    </div>
                    <div class="tp-kpi-icon" style="background: #fffbeb; color: #f59e0b;">
                        <i class="fas fa-medal"></i>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- PANEL UTAMA BERBASIS TAB -->
    <section class="card card-featured card-featured-primary tp-workspace-card">
        <header class="card-header">
            <div class="card-actions">
                <a href="#" class="card-action card-action-toggle" data-card-toggle></a>
            </div>
            <h2 class="card-title">
                <i class="fas fa-project-diagram text-warning mr-1"></i>
                Workspace Penugasan Proyek &amp; Evaluasi Bintang 5
                <span class="tp-workspace-pill"><i class="fas fa-bolt"></i> KOMPETITIF &bull; REALTIME</span>
            </h2>
        </header>
        <div class="card-body">
            <?php
            $tabParam = $this->input->get('tab', TRUE);
            $openTabLeaderboard = ($tabParam === 'leaderboard');
            $openTabSemua       = !$openTabLeaderboard && ($tabParam === 'semua' || $tabParam === 'monitoring');
            $openTabAtasan      = !$openTabLeaderboard && (($tabParam === 'atasan') || (empty($tugas_saya) && !empty($tugas_atasan) && !$openTabSemua));
            $openTabSaya        = !$openTabLeaderboard && !$openTabAtasan && !$openTabSemua;
            ?>
            <ul class="nav nav-tabs nav-tabs-tp" role="tablist">
                <li class="nav-item <?= $openTabSaya ? 'active' : '' ?>">
                    <a class="nav-link <?= $openTabSaya ? 'active' : '' ?>" href="#tab_tugas_saya" data-toggle="tab" role="tab">
                        <i class="fas fa-inbox text-primary mr-2"></i> Tugas Saya (Pelaksana)
                        <?php if ($kpi['my_active'] > 0): ?>
                            <span class="badge badge-primary ml-2"><?= (int)$kpi['my_active'] ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <li class="nav-item <?= $openTabAtasan ? 'active' : '' ?>">
                    <a class="nav-link <?= $openTabAtasan ? 'active' : '' ?>" href="#tab_tugas_atasan" data-toggle="tab" role="tab">
                        <i class="fas fa-paper-plane text-success mr-2"></i> Tugas yang Saya Berikan / Ajukan
                        <?php if ($kpi['need_review'] > 0): ?>
                            <span class="badge badge-warning ml-2" id="badge_need_review"><?= (int)$kpi['need_review'] ?> Perlu Rating <i class="fas fa-star text-warning"></i></span>
                        <?php else: ?>
                            <span class="badge badge-secondary ml-2" id="badge_need_review"><?= count($tugas_atasan) ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <li class="nav-item <?= $openTabSemua ? 'active' : '' ?>">
                    <a class="nav-link <?= $openTabSemua ? 'active' : '' ?>" href="#tab_semua_task" data-toggle="tab" role="tab">
                        <i class="fas fa-users-cog text-info mr-2"></i> Semua Task Perusahaan (Monitoring Tim)
                        <?php if (!empty($kpi['waiting_acc_hrd']) && $kpi['waiting_acc_hrd'] > 0): ?>
                            <span class="badge badge-warning ml-2 font-weight-bold" title="<?= $kpi['waiting_acc_hrd'] ?> Pengajuan Mandiri Butuh ACC HRD"><i class="fas fa-hourglass-start"></i> <?= (int)$kpi['waiting_acc_hrd'] ?> Butuh ACC</span>
                        <?php else: ?>
                            <span class="badge badge-info ml-2"><?= !empty($semua_task) ? count($semua_task) : 0 ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <li class="nav-item <?= $openTabLeaderboard ? 'active' : '' ?>">
                    <a class="nav-link <?= $openTabLeaderboard ? 'active' : '' ?>" href="#tab_leaderboard" data-toggle="tab" role="tab">
                        <i class="fas fa-trophy text-warning mr-2"></i> Leaderboard KPI & Evaluasi Karyawan
                    </a>
                </li>
            </ul>

            <div class="tab-content pt-3">
                <!-- ================= TAB 1: TUGAS SAYA (SEBAGAI KARYAWAN) ================= -->
                <div id="tab_tugas_saya" class="tab-pane <?= $openTabSaya ? 'active' : '' ?>" role="tabpanel">
                    <div class="tp-tab-banner tp-banner-blue">
                        <div>
                            <i class="fas fa-bullseye mr-1"></i>
                            <strong>Misi Eksekusi Anda:</strong> Klik <strong>Detail / Progres</strong> untuk mencentang checklist pekerjaan, lalu klik <strong>Submit Selesai</strong> saat tuntas 100% sebelum deadline untuk mengunci <strong>Rating Bintang 5 ⭐</strong>!
                        </div>
                        <span class="badge badge-primary px-2 py-1" style="border-radius: 999px; font-weight: 700;">
                            <i class="fas fa-bolt mr-1"></i> <?= (int)$kpi['my_active'] ?> Aktif &bull; <?= (int)$kpi['my_done'] ?> Selesai
                        </span>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="datatable_tugas_saya">
                            <thead>
                                <tr>
                                    <th class="tp-col-no">No</th>
                                    <th class="tp-col-code">Kode &amp; Proyek</th>
                                    <th class="tp-col-main">Judul Task / Instruksi</th>
                                    <th class="tp-col-person">Pemberi Tugas</th>
                                    <th class="tp-col-prio">Prioritas &amp; Deadline</th>
                                    <th class="tp-col-prog">Progres</th>
                                    <th class="tp-col-status">Status</th>
                                    <th class="tp-col-rating">Rating &amp; Ulasan</th>
                                    <th class="tp-col-aksi text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; foreach ($tugas_saya as $row):
                                    $isOverdue = (!empty($row->tgl_deadline) && (int)$row->status_task < 4 && date('Y-m-d') > $row->tgl_deadline);
                                ?>
                                    <tr id="row_task_saya_<?= (int)$row->id_task ?>" data-id="<?= (int)$row->id_task ?>">
                                        <td class="tp-col-no"><?= $no++ ?></td>
                                        <td class="tp-col-code">
                                            <span class="tp-task-code"><?= htmlspecialchars($row->kode_task) ?></span><br>
                                            <span class="tp-proyek-pill" title="<?= htmlspecialchars($row->nama_proyek ?: 'Umum') ?>"><?= htmlspecialchars($row->nama_proyek ?: 'Umum') ?></span>
                                            <div class="tp-jenis-meta"><?= htmlspecialchars($row->jenis) ?></div>
                                        </td>
                                        <td class="tp-col-main">
                                            <div class="tp-task-title" title="<?= htmlspecialchars($row->judul) ?>"><?= htmlspecialchars($row->judul) ?></div>
                                            <?php if (!empty($row->deskripsi)): ?>
                                                <div class="tp-task-desc" title="<?= htmlspecialchars($row->deskripsi) ?>">
                                                    <?= htmlspecialchars(tpTablePreviewText($row->deskripsi, 140)) ?>
                                                </div>
                                            <?php endif; ?>
                                            <?php if ((int)$row->status_task === 3 && !empty($row->catatan_revisi)): ?>
                                                <div class="tp-note-box tp-note-revisi" title="<?= htmlspecialchars($row->catatan_revisi) ?>">
                                                    <strong><i class="fas fa-exclamation-triangle mr-1"></i> Revisi Atasan:</strong>
                                                    <div class="tp-note-clamp"><?= htmlspecialchars(tpTablePreviewText($row->catatan_revisi, 130)) ?></div>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="tp-col-person">
                                            <span class="tp-person-name"><?= htmlspecialchars($row->nama_pemberi ?: '-') ?></span>
                                            <?php if (!empty($row->jabatan_pemberi)): ?>
                                                <span class="tp-person-meta"><?= htmlspecialchars($row->jabatan_pemberi) ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="tp-col-prio">
                                            <span class="tp-priority-badge tp-prio-<?= htmlspecialchars($row->prioritas) ?>"><?= htmlspecialchars($row->prioritas) ?></span>
                                            <div class="small mt-1 text-dark">
                                                <i class="far fa-calendar-check text-muted mr-1"></i><?= !empty($row->tgl_deadline) ? date('d-m-Y', strtotime($row->tgl_deadline)) : '-' ?>
                                            </div>
                                            <?php if (!empty($row->is_late)): ?>
                                                <div class="mt-1"><span class="badge badge-danger tp-badge-wrap" title="Melewati batas deadline (Maksimal Rating Bintang 1)"><i class="fas fa-exclamation-circle"></i> Telat H+<?= (int)$row->hari_telat ?> (Maks ⭐1)</span></div>
                                            <?php elseif ($isOverdue): ?>
                                                <div class="mt-1"><span class="badge badge-danger tp-badge-wrap">Terlambat / Overdue</span></div>
                                            <?php endif; ?>
                                            <?php if ((int)($row->sp_level ?? 0) >= 1): ?>
                                                <div class="mt-1">
                                                    <span class="badge <?= htmlspecialchars($row->sp_badge_cls ?? 'badge-danger') ?> tp-badge-wrap px-2 py-1" style="font-size: 10.5px;">
                                                        <i class="fas fa-gavel mr-1"></i> <?= htmlspecialchars($row->sp_label ?? ('SP ' . (int)$row->sp_level)) ?>
                                                    </span>
                                                </div>
                                            <?php endif; ?>
                                            <?php if (!empty($row->hrd_jadwal_tgl)): ?>
                                                <div class="tp-note-box tp-note-hrd">
                                                    <strong><i class="fas fa-handshake mr-1"></i> Jadwal HRD:</strong><br>
                                                    <?= date('d-m-Y', strtotime($row->hrd_jadwal_tgl)) ?> <?= !empty($row->hrd_jadwal_jam) ? htmlspecialchars($row->hrd_jadwal_jam) . ' WIB' : '' ?>
                                                    <?php if (!empty($row->hrd_jadwal_lokasi)): ?>
                                                        <div class="text-muted mt-1"><?= htmlspecialchars($row->hrd_jadwal_metode ?: 'Tatap Muka') ?> - <?= htmlspecialchars($row->hrd_jadwal_lokasi) ?></div>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="tp-col-prog">
                                            <div class="progress progress-sm mb-1" style="height: 10px; border-radius: 8px;">
                                                <div class="progress-bar <?= (int)$row->progres >= 100 ? 'bg-success' : 'bg-primary' ?>" role="progressbar" style="width: <?= (int)$row->progres ?>%;"></div>
                                            </div>
                                            <div class="text-center small font-weight-bold"><?= (int)$row->progres ?>%</div>
                                        </td>
                                        <td class="tp-col-status"><?= renderStatusTaskBadge(((int)$row->status_task === 1 && ((int)$row->progres >= 100 || !empty($row->tgl_submit))) ? 2 : $row->status_task) ?></td>
                                        <td class="tp-col-rating">
                                            <?php if ((int)$row->status_task === 4 && !empty($row->rating_bintang)): ?>
                                                <div style="white-space: nowrap;"><?= renderStarsHtml($row->rating_bintang) ?> <strong>(<?= (int)$row->rating_bintang ?>/5)</strong></div>
                                                <?php if (!empty($row->is_late)): ?>
                                                    <div class="badge badge-light-danger text-danger tp-badge-wrap mt-1" style="font-size:10px;">Maks ⭐1 (Lewat Deadline)</div>
                                                <?php endif; ?>
                                                <?php if (!empty($row->ulasan_atasan)): ?>
                                                    <div class="tp-note-box tp-note-ulasan" title="<?= htmlspecialchars($row->ulasan_atasan) ?>">
                                                        <div class="tp-note-clamp">"<?= htmlspecialchars(tpTablePreviewText($row->ulasan_atasan, 120)) ?>"</div>
                                                    </div>
                                                <?php endif; ?>
                                            <?php elseif (!empty($row->is_late)): ?>
                                                <span class="badge badge-danger" style="font-size:10.5px;"><i class="fas fa-lock mr-1"></i> Maks ⭐ 1/5</span>
                                                <div class="text-muted small mt-1"><em>Lewat deadline H+<?= (int)$row->hari_telat ?></em></div>
                                            <?php else: ?>
                                                <span class="text-muted small"><em>Belum dinilai</em></span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="tp-col-aksi text-center">
                                            <div class="tp-action-stack">
                                                <button type="button" class="btn btn-xs btn-primary btn-detail-task" data-id="<?= (int)$row->id_task ?>" data-focus="progres">
                                                    <i class="fas fa-edit"></i> Detail / Progres
                                                </button>
                                                <?php if ((int)$row->status_task < 4 && (int)$row->status_task !== 2): ?>
                                                    <button type="button" class="btn btn-xs btn-success btn-detail-task" data-id="<?= (int)$row->id_task ?>" data-focus="submit">
                                                        <i class="fas fa-check"></i> Submit Selesai
                                                    </button>
                                                <?php endif; ?>
                                                <?php if ((int)($row->sp_level ?? 0) >= 3): ?>
                                                    <button type="button" class="btn btn-xs btn-danger font-weight-bold btn-jadwal-hrd-sp3"
                                                        data-id="<?= (int)$row->id_task ?>"
                                                        data-kode="<?= htmlspecialchars($row->kode_task) ?>"
                                                        data-judul="<?= htmlspecialchars($row->judul) ?>"
                                                        data-karyawan="<?= htmlspecialchars($row->nama_penerima ?: ($me->nama ?? '-')) ?>"
                                                        data-telat="<?= (int)($row->hari_telat ?? 21) ?>"
                                                        data-tgl="<?= htmlspecialchars($row->hrd_jadwal_tgl ?? '') ?>"
                                                        data-jam="<?= htmlspecialchars($row->hrd_jadwal_jam ?? '10:00') ?>"
                                                        data-metode="<?= htmlspecialchars($row->hrd_jadwal_metode ?? 'Tatap Muka (Offline)') ?>"
                                                        data-lokasi="<?= htmlspecialchars($row->hrd_jadwal_lokasi ?? 'Ruang HRD') ?>"
                                                        data-catatan="<?= htmlspecialchars($row->hrd_jadwal_catatan ?? '') ?>">
                                                        <i class="fas fa-calendar-check"></i> <?= !empty($row->hrd_jadwal_tgl) ? 'Ubah Jadwal HRD' : 'Jadwalkan HRD (SP 3)' ?>
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ================= TAB 2: PENUGASAN KE KARYAWAN (SEBAGAI ATASAN) ================= -->
                <?php if ($canGiveTask || count($tugas_atasan) > 0): ?>
                <div id="tab_tugas_atasan" class="tab-pane <?= $openTabAtasan ? 'active' : '' ?>" role="tabpanel">
                    <div class="tp-tab-banner tp-banner-emerald">
                        <div>
                            <i class="fas fa-medal mr-1"></i>
                            <strong>Panel Penugasan &amp; Evaluasi:</strong> Pantau progres eksekusi tim secara real-time dan berikan penilaian <strong>Rating Bintang 5 ⭐</strong> yang objektif.
                        </div>
                        <?php if ($canViewAll): ?>
                            <div class="btn-group btn-group-sm tp-mode-switch">
                                <a href="<?= base_url('task_proyek?mode=all' . ($switch === 'kepegawaian' ? '&nav=kepegawaian' : '')) ?>" class="btn <?= ($viewMode !== 'mine') ? 'btn-primary' : 'btn-default' ?>">
                                    <i class="fas fa-globe mr-1"></i> Semua Penugasan Perusahaan
                                </a>
                                <a href="<?= base_url('task_proyek?mode=mine' . ($switch === 'kepegawaian' ? '&nav=kepegawaian' : '')) ?>" class="btn <?= ($viewMode === 'mine') ? 'btn-primary' : 'btn-default' ?>">
                                    <i class="fas fa-user-tie mr-1"></i> Hanya Penugasan Dari Saya
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="datatable_tugas_atasan">
                            <thead>
                                <tr>
                                    <th class="tp-col-no">No</th>
                                    <th class="tp-col-code">Kode &amp; Proyek</th>
                                    <th class="tp-col-person">Penerima Tugas</th>
                                    <th class="tp-col-main">Judul Task &amp; Hasil Karyawan</th>
                                    <th class="tp-col-person">Pemberi Tugas</th>
                                    <th class="tp-col-prio">Prioritas &amp; Deadline</th>
                                    <th class="tp-col-prog">Progres</th>
                                    <th class="tp-col-status">Status</th>
                                    <th class="tp-col-rating">Rating Bintang ⭐</th>
                                    <th class="tp-col-aksi text-center">Aksi Atasan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; foreach ($tugas_atasan as $row):
                                    $isOverdue = (!empty($row->tgl_deadline) && (int)$row->status_task < 4 && date('Y-m-d') > $row->tgl_deadline);
                                ?>
                                    <tr id="row_task_atasan_<?= (int)$row->id_task ?>" data-id="<?= (int)$row->id_task ?>">
                                        <td class="tp-col-no"><?= $no++ ?></td>
                                        <td class="tp-col-code">
                                            <span class="tp-task-code"><?= htmlspecialchars($row->kode_task) ?></span><br>
                                            <span class="tp-proyek-pill" title="<?= htmlspecialchars($row->nama_proyek ?: 'Umum') ?>"><?= htmlspecialchars($row->nama_proyek ?: 'Umum') ?></span>
                                        </td>
                                        <td class="tp-col-person">
                                            <span class="tp-person-name"><?= htmlspecialchars($row->nama_penerima ?: '-') ?></span>
                                            <?php if (!empty($row->jabatan_penerima)): ?>
                                                <span class="tp-person-meta"><?= htmlspecialchars($row->jabatan_penerima) ?></span>
                                            <?php endif; ?>
                                            <?php if (!empty($row->divisi_penerima)): ?>
                                                <div class="mt-1"><span class="badge badge-info tp-badge-wrap" style="font-size:10px;"><?= htmlspecialchars($row->divisi_penerima) ?></span></div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="tp-col-main">
                                            <div class="tp-task-title" title="<?= htmlspecialchars($row->judul) ?>"><?= htmlspecialchars($row->judul) ?></div>
                                            <?php if (!empty($row->catatan_bawahan)): ?>
                                                <div class="tp-note-box tp-note-laporan" title="<?= htmlspecialchars($row->catatan_bawahan) ?>">
                                                    <strong>Laporan Karyawan:</strong>
                                                    <div class="tp-note-clamp"><?= htmlspecialchars(tpTablePreviewText($row->catatan_bawahan, 130)) ?></div>
                                                    <?php if (!empty($row->bukti_selesai)): ?>
                                                        <div class="mt-1">
                                                            <a href="<?= htmlspecialchars($row->bukti_selesai) ?>" target="_blank" class="text-primary font-weight-bold d-inline-block text-truncate" style="max-width: 100%;"><i class="fas fa-external-link-alt mr-1"></i> Buka Bukti / Lampiran</a>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="tp-col-person">
                                            <span class="tp-person-name"><?= htmlspecialchars($row->nama_pemberi ?: '-') ?></span>
                                            <?php if (!empty($row->nama_mengetahui)): ?>
                                                <span class="tp-person-meta"><i class="fas fa-eye text-primary mr-1"></i> Mengetahui: <strong><?= htmlspecialchars($row->nama_mengetahui) ?></strong></span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="tp-col-prio">
                                            <span class="tp-priority-badge tp-prio-<?= htmlspecialchars($row->prioritas) ?>"><?= htmlspecialchars($row->prioritas) ?></span>
                                            <div class="small mt-1 text-dark">
                                                <i class="far fa-calendar-check text-muted mr-1"></i><?= !empty($row->tgl_deadline) ? date('d-m-Y', strtotime($row->tgl_deadline)) : '-' ?>
                                            </div>
                                            <?php if (!empty($row->is_late)): ?>
                                                <div class="mt-1"><span class="badge badge-danger tp-badge-wrap" title="Melewati batas deadline (Maksimal Rating Bintang 1)"><i class="fas fa-exclamation-circle"></i> H+<?= (int)$row->hari_telat ?> (Maks ⭐1)</span></div>
                                            <?php elseif ($isOverdue): ?>
                                                <div class="mt-1"><span class="badge badge-danger tp-badge-wrap">Overdue</span></div>
                                            <?php endif; ?>
                                            <?php if ((int)($row->sp_level ?? 0) >= 1): ?>
                                                <div class="mt-1">
                                                    <span class="badge <?= htmlspecialchars($row->sp_badge_cls ?? 'badge-danger') ?> tp-badge-wrap px-2 py-1" style="font-size: 10.5px;">
                                                        <i class="fas fa-gavel mr-1"></i> <?= htmlspecialchars($row->sp_label ?? ('SP ' . (int)$row->sp_level)) ?>
                                                    </span>
                                                </div>
                                            <?php endif; ?>
                                            <?php if (!empty($row->hrd_jadwal_tgl)): ?>
                                                <div class="tp-note-box tp-note-hrd">
                                                    <strong><i class="fas fa-handshake mr-1"></i> Jadwal HRD:</strong><br>
                                                    <?= date('d-m-Y', strtotime($row->hrd_jadwal_tgl)) ?> <?= !empty($row->hrd_jadwal_jam) ? htmlspecialchars($row->hrd_jadwal_jam) . ' WIB' : '' ?>
                                                    <?php if (!empty($row->hrd_jadwal_lokasi)): ?>
                                                        <div class="text-muted mt-1"><?= htmlspecialchars($row->hrd_jadwal_metode ?: 'Tatap Muka') ?> - <?= htmlspecialchars($row->hrd_jadwal_lokasi) ?></div>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="tp-col-prog">
                                            <div class="progress progress-sm mb-1" style="height: 10px; border-radius: 8px;">
                                                <div class="progress-bar <?= (int)$row->progres >= 100 ? 'bg-success' : 'bg-info' ?>" role="progressbar" style="width: <?= (int)$row->progres ?>%;"></div>
                                            </div>
                                            <div class="text-center small font-weight-bold"><?= (int)$row->progres ?>%</div>
                                        </td>
                                        <td class="tp-col-status"><?= renderStatusTaskBadge(((int)$row->status_task === 1 && ((int)$row->progres >= 100 || !empty($row->tgl_submit))) ? 2 : $row->status_task) ?></td>
                                        <td class="tp-col-rating">
                                            <?php if ((int)$row->status_task === 4 && !empty($row->rating_bintang)): ?>
                                                <div style="white-space: nowrap;"><?= renderStarsHtml($row->rating_bintang) ?> <strong>(<?= (int)$row->rating_bintang ?>/5)</strong></div>
                                                <?php if (!empty($row->is_late)): ?>
                                                    <div class="badge badge-light-danger text-danger tp-badge-wrap mt-1" style="font-size:10px;">Maks ⭐1 (Lewat Deadline)</div>
                                                <?php endif; ?>
                                                <?php if (!empty($row->ulasan_atasan)): ?>
                                                    <div class="tp-note-box tp-note-ulasan" title="<?= htmlspecialchars($row->ulasan_atasan) ?>">
                                                        <div class="tp-note-clamp">"<?= htmlspecialchars(tpTablePreviewText($row->ulasan_atasan, 120)) ?>"</div>
                                                    </div>
                                                <?php endif; ?>
                                            <?php elseif ((int)$row->status_task === 2): ?>
                                                <span class="badge badge-warning"><i class="fas fa-star"></i> Siap Dinilai</span>
                                                <?php if (!empty($row->is_late)): ?>
                                                    <div class="mt-1"><span class="badge badge-danger" style="font-size:10px;"><i class="fas fa-lock"></i> Maks ⭐1</span></div>
                                                <?php endif; ?>
                                            <?php elseif (!empty($row->is_late)): ?>
                                                <span class="badge badge-danger" style="font-size:10px;"><i class="fas fa-lock"></i> Maks ⭐1</span>
                                            <?php else: ?>
                                                <span class="text-muted small">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="tp-col-aksi text-center">
                                            <div class="tp-action-stack">
                                            <?php
                                            $effStatusAtasan = ((int)$row->status_task === 1 && ((int)$row->progres >= 100 || !empty($row->tgl_submit))) ? 2 : (int)$row->status_task;
                                            $alreadyRated = ($effStatusAtasan === 4 && !empty($row->rating_bintang));
                                            ?>
                                            <?php if ((int)$row->status_task === 5 && !empty($isHrdAdmin)): ?>
                                                <button type="button" class="btn btn-xs btn-success font-weight-bold btn-acc-hrd"
                                                    data-id="<?= (int)$row->id_task ?>"
                                                    data-kode="<?= htmlspecialchars($row->kode_task) ?>"
                                                    data-judul="<?= htmlspecialchars($row->judul) ?>">
                                                    <i class="fas fa-check-circle"></i> ACC Task
                                                </button>
                                                <button type="button" class="btn btn-xs btn-outline-danger font-weight-bold btn-tolak-hrd"
                                                    data-id="<?= (int)$row->id_task ?>"
                                                    data-kode="<?= htmlspecialchars($row->kode_task) ?>"
                                                    data-judul="<?= htmlspecialchars($row->judul) ?>">
                                                    <i class="fas fa-times"></i> Tolak
                                                </button>
                                            <?php endif; ?>
                                            <?php 
                                            $isSelfRow = ((int)$row->id_penerima === (int)$myId);
                                            if ($effStatusAtasan === 2 && !$alreadyRated && !$isSelfRow): 
                                            ?>
                                                <button type="button" class="btn btn-xs btn-warning font-weight-bold btn-detail-task" data-id="<?= (int)$row->id_task ?>" data-focus="review">
                                                    <i class="fas fa-star"></i> Beri Rating ⭐
                                                </button>
                                            <?php elseif ($effStatusAtasan === 2 && !$alreadyRated && $isSelfRow): ?>
                                                <span class="badge badge-warning tp-badge-wrap text-center" style="font-size: 10px;"><i class="fas fa-hourglass-half"></i> Menunggu Rating HRD</span>
                                                <button type="button" class="btn btn-xs btn-info btn-detail-task" data-id="<?= (int)$row->id_task ?>" data-focus="detail">
                                                    <i class="fas fa-eye"></i> Detail Task
                                                </button>
                                            <?php else: ?>
                                                <button type="button" class="btn btn-xs btn-info btn-detail-task" data-id="<?= (int)$row->id_task ?>" data-focus="detail">
                                                    <i class="fas fa-eye"></i> Detail Task
                                                </button>
                                            <?php endif; ?>

                                            <?php if ((int)($row->sp_level ?? 0) >= 3): ?>
                                                <button type="button" class="btn btn-xs btn-danger font-weight-bold btn-jadwal-hrd-sp3"
                                                    data-id="<?= (int)$row->id_task ?>"
                                                    data-kode="<?= htmlspecialchars($row->kode_task) ?>"
                                                    data-judul="<?= htmlspecialchars($row->judul) ?>"
                                                    data-karyawan="<?= htmlspecialchars($row->nama_penerima ?: '-') ?>"
                                                    data-telat="<?= (int)($row->hari_telat ?? 21) ?>"
                                                    data-tgl="<?= htmlspecialchars($row->hrd_jadwal_tgl ?? '') ?>"
                                                    data-jam="<?= htmlspecialchars($row->hrd_jadwal_jam ?? '10:00') ?>"
                                                    data-metode="<?= htmlspecialchars($row->hrd_jadwal_metode ?? 'Tatap Muka (Offline)') ?>"
                                                    data-lokasi="<?= htmlspecialchars($row->hrd_jadwal_lokasi ?? 'Ruang HRD') ?>"
                                                    data-catatan="<?= htmlspecialchars($row->hrd_jadwal_catatan ?? '') ?>">
                                                    <i class="fas fa-calendar-check"></i> <?= !empty($row->hrd_jadwal_tgl) ? 'Jadwal HRD' : 'Jadwalkan HRD (SP 3)' ?>
                                                </button>
                                            <?php endif; ?>

                                            <?php if ((int)$row->id_pemberi === (int)$myId || $canViewAll): ?>
                                                <button type="button" class="btn btn-xs btn-danger btn-hapus-task" data-id="<?= (int)$row->id_task ?>" data-kode="<?= htmlspecialchars($row->kode_task) ?>">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php endif; ?>

                <!-- ================= TAB 3: SEMUA TASK PERUSAHAAN (MONITORING TIM) ================= -->
                <div id="tab_semua_task" class="tab-pane <?= $openTabSemua ? 'active' : '' ?>" role="tabpanel">
                    <div class="tp-tab-banner tp-banner-cyan">
                        <div>
                            <i class="fas fa-globe mr-1"></i>
                            <strong>Radar Task Perusahaan:</strong> Transparansi seluruh proyek lintas divisi PT Visi Yosindo Medikal untuk mendorong akuntabilitas dan kolaborasi tim.
                        </div>
                        <div class="d-flex align-items-center flex-wrap" style="gap: 8px;">
                            <label class="font-weight-bold text-dark small mb-0"><i class="fas fa-filter text-primary mr-1"></i> Saring:</label>
                            <select id="filter_semua_karyawan" class="form-control form-control-sm" style="min-width: 170px; border-radius: 8px;">
                                <option value="">-- Semua Karyawan --</option>
                                <?php foreach ($list_semua_karyawan_all as $kAll): ?>
                                    <option value="<?= htmlspecialchars(strtolower($kAll->nama)) ?>"><?= htmlspecialchars($kAll->nama) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <select id="filter_semua_status" class="form-control form-control-sm" style="min-width: 150px; border-radius: 8px;">
                                <option value="">-- Semua Status --</option>
                                <option value="Menunggu ACC HRD">Menunggu ACC HRD</option>
                                <option value="Baru">Baru / Belum Mulai</option>
                                <option value="Sedang Dikerjakan">Sedang Dikerjakan</option>
                                <option value="Menunggu Penilaian">Menunggu Penilaian</option>
                                <option value="Perlu Revisi">Perlu Revisi</option>
                                <option value="Selesai">Selesai & Dinilai</option>
                                <option value="Ditolak">Ditolak HRD</option>
                            </select>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="datatable_semua_task" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th class="tp-col-no">No</th>
                                    <th class="tp-col-code">Kode &amp; Proyek</th>
                                    <th class="tp-col-main">Judul Task &amp; Jobdesk</th>
                                    <th class="tp-col-person">Pelaksana</th>
                                    <th class="tp-col-person">Pemberi / Pengaju</th>
                                    <th class="tp-col-person">Mengetahui</th>
                                    <th class="tp-col-prio">Prioritas &amp; Deadline</th>
                                    <th class="tp-col-prog">Progres</th>
                                    <th class="tp-col-status">Status</th>
                                    <th class="tp-col-rating">Rating</th>
                                    <th class="tp-col-aksi text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $noAll = 1; foreach ($semua_task as $row):
                                    $isOverdue = (!empty($row->tgl_deadline) && (int)$row->status_task < 4 && date('Y-m-d') > $row->tgl_deadline);
                                ?>
                                    <tr id="row_semua_task_<?= (int)$row->id_task ?>" data-id="<?= (int)$row->id_task ?>">
                                        <td class="tp-col-no"><?= $noAll++ ?></td>
                                        <td class="tp-col-code">
                                            <span class="tp-task-code"><?= htmlspecialchars($row->kode_task) ?></span><br>
                                            <span class="tp-proyek-pill" title="<?= htmlspecialchars($row->nama_proyek ?: 'Umum') ?>"><?= htmlspecialchars($row->nama_proyek ?: 'Umum') ?></span>
                                            <div class="tp-jenis-meta"><?= htmlspecialchars($row->jenis) ?></div>
                                        </td>
                                        <td class="tp-col-main">
                                            <div class="tp-task-title" title="<?= htmlspecialchars($row->judul) ?>"><?= htmlspecialchars($row->judul) ?></div>
                                            <?php if (!empty($row->nama_jobdesk)): ?>
                                                <div>
                                                    <span class="badge badge-success tp-jobdesk-pill" title="<?= htmlspecialchars($row->nama_jobdesk) ?>">
                                                        <i class="fas fa-bullseye mr-1"></i> <?= htmlspecialchars($row->nama_jobdesk) ?>
                                                    </span>
                                                </div>
                                            <?php endif; ?>
                                            <?php if (!empty($row->deskripsi)): ?>
                                                <div class="tp-task-desc" title="<?= htmlspecialchars($row->deskripsi) ?>">
                                                    <?= htmlspecialchars(tpTablePreviewText($row->deskripsi, 120)) ?>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="tp-col-person">
                                            <span class="tp-person-name"><?= htmlspecialchars($row->nama_penerima ?: '-') ?></span>
                                            <?php if (!empty($row->jabatan_penerima)): ?>
                                                <span class="tp-person-meta"><?= htmlspecialchars($row->jabatan_penerima) ?></span>
                                            <?php endif; ?>
                                            <?php if (!empty($row->divisi_penerima)): ?>
                                                <div class="mt-1"><span class="badge badge-light border text-info tp-badge-wrap" style="font-size:10.5px;"><?= htmlspecialchars($row->divisi_penerima) ?></span></div>
                                            <?php endif; ?>
                                            <?php if (!empty($row->is_mandiri)): ?>
                                                <div class="mt-1"><span class="badge badge-warning text-dark" style="font-size: 10px;"><i class="fas fa-user-edit mr-1"></i> Mandiri</span></div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="tp-col-person">
                                            <span class="tp-person-name"><?= htmlspecialchars($row->nama_pemberi ?: '-') ?></span>
                                            <?php if (!empty($row->jabatan_pemberi)): ?>
                                                <span class="tp-person-meta"><?= htmlspecialchars($row->jabatan_pemberi) ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="tp-col-person">
                                            <?php if (!empty($row->nama_mengetahui)): ?>
                                                <span class="tp-person-name"><i class="fas fa-eye text-primary mr-1"></i> <?= htmlspecialchars($row->nama_mengetahui) ?></span>
                                            <?php else: ?>
                                                <span class="text-muted small">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="tp-col-prio">
                                            <span class="tp-priority-badge tp-prio-<?= htmlspecialchars($row->prioritas) ?>"><?= htmlspecialchars($row->prioritas) ?></span>
                                            <div class="small mt-1 text-dark">
                                                <i class="far fa-calendar-check text-muted mr-1"></i><?= !empty($row->tgl_deadline) ? date('d-m-Y', strtotime($row->tgl_deadline)) : '-' ?>
                                            </div>
                                            <?php if (!empty($row->is_late)): ?>
                                                <div class="mt-1"><span class="badge badge-danger tp-badge-wrap">Terlambat H+<?= (int)$row->hari_telat ?></span></div>
                                            <?php elseif ($isOverdue): ?>
                                                <div class="mt-1"><span class="badge badge-danger tp-badge-wrap">Overdue</span></div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="tp-col-prog">
                                            <div class="progress progress-sm mb-1" style="height: 10px; border-radius: 8px;">
                                                <div class="progress-bar <?= (int)$row->progres >= 100 ? 'bg-success' : 'bg-primary' ?>" role="progressbar" style="width: <?= (int)$row->progres ?>%;"></div>
                                            </div>
                                            <div class="text-center small font-weight-bold"><?= (int)$row->progres ?>%</div>
                                        </td>
                                        <td class="tp-col-status">
                                            <?= renderStatusTaskBadge(((int)$row->status_task === 1 && ((int)$row->progres >= 100 || !empty($row->tgl_submit))) ? 2 : $row->status_task) ?>
                                            <?php if (!empty($row->nama_acc_hrd) && (int)$row->status_task !== 5): ?>
                                                <div class="tp-person-meta mt-1"><i class="fas fa-check-double text-success mr-1"></i> ACC HRD: <?= htmlspecialchars($row->nama_acc_hrd) ?></div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="tp-col-rating">
                                            <?php if ((int)$row->status_task === 4 && !empty($row->rating_bintang)): ?>
                                                <div style="white-space: nowrap;"><?= renderStarsHtml($row->rating_bintang) ?> <strong>(<?= (int)$row->rating_bintang ?>/5)</strong></div>
                                            <?php else: ?>
                                                <span class="text-muted small"><em>-</em></span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="tp-col-aksi text-center">
                                            <div class="tp-action-stack">
                                                <button type="button" class="btn btn-xs btn-primary btn-detail-task" data-id="<?= (int)$row->id_task ?>" data-focus="detail">
                                                    <i class="fas fa-eye"></i> Detail
                                                </button>
                                                <?php if ((int)$row->status_task === 2 && empty($row->rating_bintang) && (int)$row->id_penerima !== (int)$myId && !empty($isHrdAdmin)): ?>
                                                    <button type="button" class="btn btn-xs btn-warning font-weight-bold btn-detail-task" data-id="<?= (int)$row->id_task ?>" data-focus="review">
                                                        <i class="fas fa-star"></i> Nilai (HRD)
                                                    </button>
                                                <?php endif; ?>
                                                <?php if ((int)$row->status_task === 5 && !empty($isHrdAdmin)): ?>
                                                    <button type="button" class="btn btn-xs btn-success font-weight-bold btn-acc-hrd"
                                                        data-id="<?= (int)$row->id_task ?>"
                                                        data-kode="<?= htmlspecialchars($row->kode_task) ?>"
                                                        data-judul="<?= htmlspecialchars($row->judul) ?>">
                                                        <i class="fas fa-check-circle"></i> ACC
                                                    </button>
                                                    <button type="button" class="btn btn-xs btn-outline-danger font-weight-bold btn-tolak-hrd"
                                                        data-id="<?= (int)$row->id_task ?>"
                                                        data-kode="<?= htmlspecialchars($row->kode_task) ?>"
                                                        data-judul="<?= htmlspecialchars($row->judul) ?>">
                                                        <i class="fas fa-times"></i> Tolak
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ================= TAB 3: LEADERBOARD KPI & EVALUASI KARYAWAN ================= -->
                <div id="tab_leaderboard" class="tab-pane <?= $openTabLeaderboard ? 'active' : '' ?>" role="tabpanel">
                    <canvas id="tpLbConfettiCanvas" style="position:absolute; top:0; left:0; width:100%; height:440px; pointer-events:none; z-index:25;"></canvas>
                    <?php
                    $scoredLeaders = [];
                    foreach ($leaderboard as $cand) {
                        if ($cand->skor_akhir !== null && count($scoredLeaders) < 5) {
                            $scoredLeaders[] = $cand;
                        }
                    }
                    $getInitials = function($fullName) {
                        $parts = preg_split('/\s+/', trim((string)$fullName));
                        $ini = '';
                        foreach ($parts as $pt) {
                            if ($pt !== '') {
                                $ini .= strtoupper(substr($pt, 0, 1));
                                if (strlen($ini) >= 2) break;
                            }
                        }
                        return $ini !== '' ? $ini : '?';
                    };
                    $avatarGradients = [
                        'linear-gradient(135deg, #2563eb, #0ea5e9)',
                        'linear-gradient(135deg, #7c3aed, #ec4899)',
                        'linear-gradient(135deg, #059669, #10b981)',
                        'linear-gradient(135deg, #ea580c, #f59e0b)',
                        'linear-gradient(135deg, #4f46e5, #06b6d4)',
                        'linear-gradient(135deg, #db2777, #f43f5e)',
                    ];
                    $topPins = [
                        1 => ['icon' => '🥇', 'bg' => 'linear-gradient(135deg,#fef08a,#fde047)', 'fg' => '#713f12', 'bd' => '#eab308', 'title' => 'Top 1 Leaderboard'],
                        2 => ['icon' => '🥈', 'bg' => 'linear-gradient(135deg,#f1f5f9,#cbd5e1)', 'fg' => '#1e293b', 'bd' => '#94a3b8', 'title' => 'Top 2 Leaderboard'],
                        3 => ['icon' => '🥉', 'bg' => 'linear-gradient(135deg,#ffedd5,#fdba74)', 'fg' => '#7c2d12', 'bd' => '#f97316', 'title' => 'Top 3 Leaderboard'],
                        4 => ['icon' => '🏅', 'bg' => 'linear-gradient(135deg,#dbeafe,#bfdbfe)', 'fg' => '#1e40af', 'bd' => '#60a5fa', 'title' => 'Top 4 Leaderboard'],
                        5 => ['icon' => '🏅', 'bg' => 'linear-gradient(135deg,#ede9fe,#ddd6fe)', 'fg' => '#5b21b6', 'bd' => '#a78bfa', 'title' => 'Top 5 Leaderboard'],
                    ];
                    ?>

                    <!-- Hero Header Energetic -->
                    <div class="tp-lb-hero">
                        <span class="tp-lb-spark s1"></span>
                        <span class="tp-lb-spark s2"></span>
                        <span class="tp-lb-spark s3"></span>
                        <span class="tp-lb-spark s4"></span>
                        <span class="tp-lb-spark s5"></span>
                        <div class="d-flex justify-content-between align-items-start flex-wrap" style="gap: 14px; position: relative; z-index: 1;">
                            <div>
                                <div class="d-flex align-items-center flex-wrap mb-2" style="gap: 8px;">
                                    <span class="tp-lb-chip" style="background: rgba(34, 197, 94, 0.22); border-color: rgba(134, 239, 172, 0.45);">
                                        <span class="tp-lb-live-dot"></span> LIVE KLASEMEN
                                    </span>
                                    <span class="badge px-2 py-1" style="background: #facc15; color: #713f12; font-weight: 800; font-size: 11px; border-radius: 999px;">
                                        <i class="fas fa-bolt mr-1 tp-lb-bolt-anim"></i> ARENA KPI <?= strtoupper(htmlspecialchars($lbActiveLabel)) ?>
                                    </span>
                                    <?php if ($lbMonthlyMode): ?>
                                        <span class="tp-lb-chip"><i class="fas fa-calendar-day"></i> Mode Bulanan (<?= htmlspecialchars($lbStart ?? '') ?> s/d <?= htmlspecialchars($lbEnd ?? '') ?>)</span>
                                    <?php else: ?>
                                        <span class="tp-lb-chip"><i class="fas fa-sync-alt tp-lb-spin-slow"></i> Reset Per Semester</span>
                                    <?php endif; ?>
                                    <span class="tp-lb-chip"><i class="fas fa-balance-scale"></i> 80% Mutu + 20% Volume</span>
                                </div>
                                <h4 class="font-weight-bold mb-1" style="color: #ffffff; letter-spacing: -0.3px;">
                                    <i class="fas fa-trophy text-warning mr-2 tp-lb-trophy-anim"></i>Klasemen Performa Karyawan Terbaik
                                </h4>
                                <div class="d-flex align-items-center flex-wrap mt-2" style="gap: 6px;">
                                    <span class="tp-lb-chip">Task 25%</span>
                                    <span class="tp-lb-chip">Laporan 20%</span>
                                    <span class="tp-lb-chip">Pencapaian 15%</span>
                                    <span class="tp-lb-chip">Product Knowledge 10%</span>
                                    <span class="tp-lb-chip">Absensi 15% <small style="opacity:.85;">(Telat -poin)</small></span>
                                    <span class="tp-lb-chip">Evaluasi Smt 15%</span>
                                    <span class="tp-lb-chip" style="background: rgba(239, 68, 68, 0.28); border-color: rgba(252, 165, 165, 0.45);">
                                        <i class="fas fa-shield-alt"></i> Plafon SP: SP0 100% &bull; SP1 80% &bull; SP2 60% &bull; SP3 40%
                                    </span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center flex-wrap" style="gap: 6px; font-size: 11px;">
                                <span class="badge badge-success px-2 py-1" style="border-radius: 8px;">Grade A &ge; 85</span>
                                <span class="badge badge-primary px-2 py-1" style="border-radius: 8px;">Grade B 75–84</span>
                                <span class="badge badge-warning px-2 py-1" style="border-radius: 8px;">Grade C 65–74</span>
                                <span class="badge badge-danger px-2 py-1" style="border-radius: 8px;">Grade D &lt; 65</span>
                            </div>
                        </div>
                    </div>

                    <!-- Filter Khusus Periode Leaderboard (Semester / Bulan) -->
                    <?php
                    $curNowMonth = (int)date('n');
                    $curNowYear  = (int)date('Y');
                    $navQueryPrefix  = (!empty($switch) && $switch === 'kepegawaian') ? 'nav=kepegawaian&' : '';
                    $modeQueryPrefix = (!empty($viewMode)) ? ('mode=' . urlencode($viewMode) . '&') : '';
                    $urlLbSemester   = base_url('task_proyek?' . $navQueryPrefix . $modeQueryPrefix . 'tab=leaderboard&semester=' . (int)$filterSemester . '&tahun=' . (int)$filterTahun);
                    $urlLbThisMonth  = base_url('task_proyek?' . $navQueryPrefix . $modeQueryPrefix . 'tab=leaderboard&bulan=' . $curNowMonth . '&tahun=' . $curNowYear);
                    $isCurMonthSelected = ($lbMonthlyMode && (int)$filterBulan === $curNowMonth && (int)$filterTahun === $curNowYear);
                    ?>
                    <div class="d-flex justify-content-between align-items-center flex-wrap mb-3 p-3" style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 14px; gap: 10px; box-shadow: 0 3px 10px rgba(15, 23, 42, 0.04);">
                        <div class="d-flex align-items-center flex-wrap" style="gap: 8px;">
                            <span class="font-weight-bold text-dark" style="font-size: 12.5px;">
                                <i class="fas fa-calendar-check text-primary mr-1"></i> Filter Klasemen:
                            </span>
                            <a href="<?= $urlLbSemester ?>" class="btn btn-sm <?= !$lbMonthlyMode ? 'btn-primary font-weight-bold' : 'btn-outline-secondary' ?>" style="border-radius: 999px; font-size: 12px; padding: 4px 12px;">
                                <i class="fas fa-layer-group mr-1"></i> 1 Semester Penuh
                            </a>
                            <a href="<?= $urlLbThisMonth ?>" class="btn btn-sm <?= $isCurMonthSelected ? 'btn-warning font-weight-bold text-dark' : 'btn-outline-secondary' ?>" style="border-radius: 999px; font-size: 12px; padding: 4px 12px;">
                                <i class="fas fa-bolt mr-1"></i> Bulan Ini (<?= $namaBulanList[$curNowMonth] ?>)
                            </a>
                        </div>

                        <form method="GET" action="<?= base_url('task_proyek') ?>" class="form-inline" style="gap: 7px; display: flex; flex-wrap: wrap; align-items: center;">
                            <?php if (!empty($switch) && $switch === 'kepegawaian'): ?>
                                <input type="hidden" name="nav" value="kepegawaian">
                            <?php endif; ?>
                            <?php if (!empty($viewMode)): ?>
                                <input type="hidden" name="mode" value="<?= htmlspecialchars($viewMode) ?>">
                            <?php endif; ?>
                            <input type="hidden" name="tab" value="leaderboard">
                            <input type="hidden" name="semester" value="<?= htmlspecialchars($filterSemester) ?>">

                            <select name="bulan" class="form-control form-control-sm" onchange="this.form.submit()" style="min-width: 175px; border-radius: 8px; font-weight: 600;">
                                <option value="">-- Semua Bulan (1 Semester) --</option>
                                <?php foreach ($namaBulanList as $bNum => $bNama): ?>
                                    <option value="<?= $bNum ?>" <?= ((string)($filterBulan ?? '') === (string)$bNum) ? 'selected' : '' ?>>Bulan <?= $bNama ?></option>
                                <?php endforeach; ?>
                            </select>

                            <select name="tahun" class="form-control form-control-sm" onchange="this.form.submit()" style="min-width: 85px; border-radius: 8px; font-weight: 600;">
                                <?php for ($y = date('Y') + 1; $y >= 2024; $y--): ?>
                                    <option value="<?= $y ?>" <?= ((string)$filterTahun === (string)$y) ? 'selected' : '' ?>><?= $y ?></option>
                                <?php endfor; ?>
                            </select>

                            <button type="submit" class="btn btn-sm btn-primary" style="border-radius: 8px; font-weight: 700; padding: 4px 12px;">
                                <i class="fas fa-search mr-1"></i> Tampilkan
                            </button>
                        </form>
                    </div>

                    <!-- Top 3 Champions Podium & Rank 4-5 Spotlight -->
                    <?php if (!empty($scoredLeaders)): ?>
                        <div class="row mb-3" style="row-gap: 14px;">
                            <?php
                            $podiumOrderClasses = [1 => 'order-1 order-md-2', 2 => 'order-2 order-md-1', 3 => 'order-3 order-md-3'];
                            $podiumCardClasses  = [1 => 'tp-lb-podium-1', 2 => 'tp-lb-podium-2', 3 => 'tp-lb-podium-3'];
                            foreach ($scoredLeaders as $idx => $p):
                                $pos = $idx + 1;
                                if ($pos > 3) break;
                                $pin = $topPins[$pos];
                                $avBg = $avatarGradients[((int)$p->pengguna_id) % count($avatarGradients)];
                                $isPodiumMe = (!empty($myReceiverIds) && is_array($myReceiverIds)) ? in_array((int)$p->pengguna_id, $myReceiverIds, true) : ((int)$p->pengguna_id === (int)$myId);
                            ?>
                                <div class="col-md-4 <?= $podiumOrderClasses[$pos] ?>">
                                    <div class="tp-lb-podium-card <?= $podiumCardClasses[$pos] ?> tp-lb-anim-card tp-lb-delay-<?= $pos ?>">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="badge px-2 py-1 <?= $pos === 1 ? 'tp-lb-pin-1' : '' ?>" style="font-size:12.5px; background:<?= $pin['bg'] ?>; color:<?= $pin['fg'] ?>; border:1px solid <?= $pin['bd'] ?>; border-radius:20px; font-weight:800;">
                                                <span class="tp-lb-medal-icon"><?= $pin['icon'] ?></span> <?= $pos === 1 ? 'JUARA #1' : ('RANK #' . $pos) ?>
                                            </span>
                                            <div class="d-flex align-items-center" style="gap: 5px;">
                                                <?php if ((int)($p->sp_level ?? 0) > 0): ?>
                                                    <span class="badge badge-danger px-2 py-1" style="font-size: 10px; border-radius: 999px;"><?= htmlspecialchars($p->sp_label) ?> Cap <?= (int)$p->sp_cap ?>%</span>
                                                <?php endif; ?>
                                                <span class="badge <?= $p->grade_badge ?> px-2 py-1" style="font-size: 10.5px; border-radius: 999px;">Grade <?= $p->grade ?></span>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between" style="gap: 10px;">
                                            <div class="d-flex align-items-center" style="gap: 11px; min-width: 0;">
                                                <div class="tp-lb-avatar tp-lb-avatar-lg" style="background: <?= $avBg ?>;">
                                                    <?= $getInitials($p->nama) ?>
                                                </div>
                                                <div style="min-width: 0;">
                                                    <div class="font-weight-bold text-dark text-truncate" style="font-size: 14.5px;" title="<?= htmlspecialchars($p->nama) ?>">
                                                        <?= htmlspecialchars($p->nama) ?>
                                                        <?php if ($isPodiumMe): ?><span class="badge badge-primary ml-1" style="font-size: 9.5px;">Saya</span><?php endif; ?>
                                                    </div>
                                                    <div class="text-muted small text-truncate" style="font-size: 11.5px;"><?= htmlspecialchars($p->jabatan ?: '-') ?></div>
                                                    <div class="mt-1" style="font-size: 11px;">
                                                        <?= renderStarsHtml($p->rating_agregat) ?>
                                                        <span class="font-weight-bold ml-1" style="color: #d97706;"><?= number_format($p->rating_agregat, 2) ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-right flex-shrink-0">
                                                <div class="font-weight-bold tp-lb-countup" data-score="<?= htmlspecialchars(number_format((float)$p->skor_akhir, 1, '.', '')) ?>" style="font-size: 26px; line-height: 1; color: <?= $p->grade_color ?>;"><?= number_format($p->skor_akhir, 1) ?></div>
                                                <small class="text-muted" style="font-size: 10px;">Skor Akhir</small>
                                            </div>
                                        </div>
                                        <div class="mt-3 pt-2" style="border-top: 1px dashed rgba(148, 163, 184, 0.35);">
                                            <div class="d-flex justify-content-between small text-muted mb-1" style="font-size: 10.5px;">
                                                <span>Mutu: <strong class="text-dark"><?= number_format($p->skor_mutu, 1) ?></strong></span>
                                                <span>Volume: <strong class="text-dark"><?= number_format($p->skor_prod, 0) ?>%</strong></span>
                                            </div>
                                            <div style="height: 6px; border-radius: 999px; background: #e2e8f0; overflow: hidden;">
                                                <div class="tp-lb-podium-bar-fill" style="height: 100%; width: <?= min(100, max(0, (float)$p->skor_akhir)) ?>%; background: linear-gradient(90deg, #0ea5e9, <?= $p->grade_color ?>); border-radius: 999px;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <?php if (count($scoredLeaders) > 3): ?>
                            <div class="row mb-3" style="row-gap: 10px;">
                                <?php for ($i = 3; $i < count($scoredLeaders) && $i < 5; $i++):
                                    $p = $scoredLeaders[$i];
                                    $pos = $i + 1;
                                    $pin = $topPins[$pos];
                                    $avBg = $avatarGradients[((int)$p->pengguna_id) % count($avatarGradients)];
                                ?>
                                    <div class="col-md-6">
                                        <div class="tp-lb-mini-card tp-lb-anim-card tp-lb-delay-<?= $pos ?>" style="border-left: 4px solid <?= $pin['bd'] ?>;">
                                            <div class="d-flex align-items-center" style="gap: 10px; min-width: 0;">
                                                <span class="badge px-2 py-1" style="font-size:12px; white-space:nowrap; background:<?= $pin['bg'] ?>; color:<?= $pin['fg'] ?>; border:1px solid <?= $pin['bd'] ?>; border-radius:20px; font-weight:800;">
                                                    <?= $pin['icon'] ?> #<?= $pos ?>
                                                </span>
                                                <div class="tp-lb-avatar" style="background: <?= $avBg ?>;"><?= $getInitials($p->nama) ?></div>
                                                <div style="min-width: 0;">
                                                    <div class="font-weight-bold text-dark text-truncate" style="font-size: 13.5px;"><?= htmlspecialchars($p->nama) ?></div>
                                                    <div class="text-muted small text-truncate" style="font-size: 11px;"><?= htmlspecialchars($p->jabatan ?: '-') ?></div>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center flex-shrink-0" style="gap: 8px;">
                                                <span class="badge <?= $p->grade_badge ?> px-2 py-1" style="font-size: 10.5px; border-radius: 999px;">Grade <?= $p->grade ?></span>
                                                <div class="font-weight-bold tp-lb-countup" data-score="<?= htmlspecialchars(number_format((float)$p->skor_akhir, 1, '.', '')) ?>" style="font-size: 19px; color: <?= $p->grade_color ?>;"><?= number_format($p->skor_akhir, 1) ?></div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endfor; ?>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <div class="tp-lb-table-card tp-lb-anim-card tp-lb-delay-table">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover mb-0" id="datatable_leaderboard">
                                <thead>
                                    <tr>
                                        <th class="text-center align-middle" style="min-width: 78px;">Rank</th>
                                        <th class="align-middle" style="min-width: 190px;">Nama Karyawan</th>
                                        <th class="align-middle" style="min-width: 145px;">Jabatan</th>
                                        <th class="text-center align-middle" style="min-width: 110px;">
                                            <div>Task Proyek</div>
                                            <small class="badge badge-primary" style="font-size: 9.5px;">Mutu 25%</small>
                                        </th>
                                        <th class="text-center align-middle" style="min-width: 110px;">
                                            <div>Laporan</div>
                                            <small class="badge badge-info" style="font-size: 9.5px;">Mutu 20%</small>
                                        </th>
                                        <th class="text-center align-middle" style="min-width: 110px;">
                                            <div>Pencapaian</div>
                                            <small class="badge text-white" style="font-size: 9.5px; background-color: #8b5cf6;">Mutu 15%</small>
                                        </th>
                                        <th class="text-center align-middle" style="min-width: 135px;">
                                            <div>Product Knowledge</div>
                                            <small class="badge text-white" style="font-size: 9.5px; background-color: #0d9488;">Mutu 10%</small>
                                        </th>
                                        <th class="text-center align-middle" style="min-width: 115px;">
                                            <div>Absensi</div>
                                            <small class="badge text-white" style="font-size: 9.5px; background-color: #0284c7;">Mutu 15%</small>
                                        </th>
                                        <th class="text-center align-middle" style="min-width: 120px;">
                                            <div>Evaluasi Smt</div>
                                            <small class="badge text-white" style="font-size: 9.5px; background-color: #6366f1;">Mutu 15%</small>
                                        </th>
                                        <th class="text-center align-middle" style="min-width: 100px;">
                                            <div>Status SP</div>
                                            <small class="badge badge-dark" style="font-size: 9.5px;">Plafon Maks</small>
                                        </th>
                                        <th class="text-center align-middle" style="min-width: 135px; background: #ecfdf5 !important;">
                                            <div class="text-success font-weight-bold">Skor Akhir</div>
                                            <small class="text-muted" style="font-size: 9.5px;">80% Mutu + 20% Vol</small>
                                        </th>
                                        <th class="text-center align-middle" style="min-width: 130px;">Rating Agregat</th>
                                        <th class="text-center align-middle" style="min-width: 120px;">Grade Kinerja</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $rank = 1;
                                    foreach ($leaderboard as $lb):
                                        $selesai   = (int)$lb->task_selesai;
                                        $isMe      = (!empty($myReceiverIds) && is_array($myReceiverIds)) ? in_array((int)$lb->pengguna_id, $myReceiverIds, true) : ((int)$lb->pengguna_id === (int)$myId);
                                        $hasPin    = ($lb->skor_akhir !== null && isset($topPins[$rank]));
                                        $rowCls    = $hasPin ? ('tp-lb-rank-' . $rank) : '';
                                        if ($isMe) $rowCls .= ' tp-lb-is-me';
                                        $avBg      = $avatarGradients[((int)$lb->pengguna_id) % count($avatarGradients)];
                                    ?>
                                        <tr class="<?= trim($rowCls) ?>">
                                            <td class="text-center font-weight-bold align-middle" data-order="<?= $rank ?>">
                                                <?php if ($hasPin): $pin = $topPins[$rank]; ?>
                                                    <span class="badge px-2 py-1 <?= $rank === 1 ? 'tp-lb-pin-1' : '' ?>" style="font-size:12.5px; white-space:nowrap; background:<?= $pin['bg'] ?>; color:<?= $pin['fg'] ?>; border:1px solid <?= $pin['bd'] ?>; border-radius:20px; font-weight:800; box-shadow:0 2px 5px rgba(15,23,42,.08);" title="<?= $pin['title'] ?>"><span class="tp-lb-medal-icon"><?= $pin['icon'] ?></span> #<?= $rank ?></span>
                                                <?php else: ?>
                                                    <span class="text-muted font-weight-semibold">#<?= $rank ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="align-middle">
                                                <div class="d-flex align-items-center" style="gap: 10px;">
                                                    <div class="tp-lb-avatar" style="background: <?= $avBg ?>;"><?= $getInitials($lb->nama) ?></div>
                                                    <div>
                                                        <strong style="color: #0f172a;"><?= htmlspecialchars($lb->nama) ?></strong>
                                                        <?php if ($isMe): ?>
                                                            <span class="badge badge-primary ml-1" style="font-size: 9.5px; border-radius: 999px;">Saya</span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="align-middle">
                                                <span class="text-secondary" style="font-size: 12.5px;"><?= htmlspecialchars($lb->jabatan ?: '-') ?></span>
                                            </td>
                                            <td class="text-center align-middle">
                                                <?php if ($lb->skor_task !== null): ?>
                                                    <div class="font-weight-bold" style="font-size: 14px; color: #1e293b;"><?= number_format($lb->skor_task, 1) ?></div>
                                                    <div class="tp-lb-bar-track"><div class="tp-lb-bar-fill" style="width:<?= min(100, (float)$lb->skor_task) ?>%; background:#2563eb;"></div></div>
                                                    <div class="small text-muted" style="font-size: 10.5px;">
                                                        <i class="fas fa-star text-warning"></i> <?= number_format($lb->avg_bintang, 2) ?> (<?= $selesai ?>)
                                                    </div>
                                                <?php else: ?>
                                                    <span class="text-muted small">Belum ada task</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center align-middle">
                                                <?php if ($lb->skor_lap !== null): ?>
                                                    <div class="font-weight-bold" style="font-size: 14px; color: #0284c7;"><?= number_format($lb->skor_lap, 1) ?></div>
                                                    <div class="tp-lb-bar-track"><div class="tp-lb-bar-fill" style="width:<?= min(100, (float)$lb->skor_lap) ?>%; background:#0ea5e9;"></div></div>
                                                    <div class="small text-muted" style="font-size: 10.5px;">
                                                        Poin: <?= number_format($lb->total_poin_laporan, 0) ?>
                                                    </div>
                                                <?php else: ?>
                                                    <span class="text-muted small">Belum dinilai</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center align-middle">
                                                <?php if ($lb->skor_penc !== null): ?>
                                                    <div class="font-weight-bold" style="font-size: 14px; color: #7c3aed;"><?= number_format($lb->skor_penc, 1) ?></div>
                                                    <div class="tp-lb-bar-track"><div class="tp-lb-bar-fill" style="width:<?= min(100, (float)$lb->skor_penc) ?>%; background:#8b5cf6;"></div></div>
                                                    <div class="small text-muted" style="font-size: 10.5px;">
                                                        <i class="fas fa-award mr-1" style="color: #8b5cf6;"></i><?= (int)$lb->total_pencapaian ?> item
                                                    </div>
                                                <?php else: ?>
                                                    <span class="text-muted small">Belum dinilai</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center align-middle">
                                                <?php if (!empty($lb->is_exempt_pk)): ?>
                                                    <span class="badge badge-light text-muted px-2 py-1" style="border: 1px dashed #cbd5e1; font-size: 11px; border-radius: 999px;" title="Dikecualikan dari Product Knowledge (HR/GA/Security/Manajemen)">
                                                        <i class="fas fa-minus-circle mr-1 text-secondary"></i>Bebas PK
                                                    </span>
                                                <?php elseif ($lb->skor_pk !== null): ?>
                                                    <div class="font-weight-bold" style="font-size: 14px; color: #0d9488;"><?= number_format($lb->skor_pk, 1) ?></div>
                                                    <div class="tp-lb-bar-track"><div class="tp-lb-bar-fill" style="width:<?= min(100, (float)$lb->skor_pk) ?>%; background:#0d9488;"></div></div>
                                                    <div class="small text-muted" style="font-size: 10.5px;">
                                                        <i class="fas fa-book-reader mr-1" style="color: #0d9488;"></i><?= (int)$lb->total_pk ?> kuis
                                                    </div>
                                                <?php else: ?>
                                                    <span class="text-muted small">Belum kuis</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center align-middle">
                                                <?php if ($lb->skor_abs !== null): ?>
                                                    <div class="font-weight-bold" style="font-size: 14px; color: #0369a1;"><?= number_format($lb->skor_abs, 1) ?></div>
                                                    <div class="tp-lb-bar-track"><div class="tp-lb-bar-fill" style="width:<?= min(100, (float)$lb->skor_abs) ?>%; background:#0284c7;"></div></div>
                                                    <div class="small text-muted" style="font-size: 10.5px;">
                                                        <?= (int)$lb->abs_hari_hadir ?>/<?= (int)$lb->abs_hari_kerja ?> hr
                                                        <?php if ((int)$lb->abs_hari_terlambat > 0): ?>
                                                            <span class="badge badge-danger px-1 py-0 ml-1" style="font-size: 9.5px; border-radius: 999px;"><?= (int)$lb->abs_hari_terlambat ?> telat</span>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php else: ?>
                                                    <span class="text-muted small">Belum ada data</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center align-middle">
                                                <?php if ($lb->skor_eval !== null): ?>
                                                    <?php if (!empty($isAdminUser)): ?>
                                                        <span class="font-weight-bold" style="font-size: 15px; letter-spacing: 2px; color: #6366f1; cursor: help;" title="Nilai Asli: <?= number_format($lb->skor_eval, 1) ?> (Khusus HRD/Admin)">***</span>
                                                        <div class="small text-muted" style="font-size: 9.5px;" title="Nilai Asli: <?= number_format($lb->skor_eval, 1) ?> (Khusus HRD/Admin)">Rahasia</div>
                                                    <?php else: ?>
                                                        <span class="font-weight-bold" style="font-size: 15px; letter-spacing: 2px; color: #64748b;" title="Nilai evaluasi semester bersifat rahasia">***</span>
                                                        <div class="small text-muted" style="font-size: 9.5px;">Rahasia</div>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <span class="text-muted small">Belum dinilai</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center align-middle">
                                                <?php
                                                    $spLv = (int)($lb->sp_level ?? 0);
                                                    $spCp = (int)($lb->sp_cap ?? 100);
                                                    $spTooltip = ($spLv > 0 && !empty($lb->sp_kode))
                                                        ? htmlspecialchars($lb->sp_kode . ' (berlaku s/d ' . $lb->sp_akhir . ')')
                                                        : 'Tidak ada SP aktif';
                                                ?>
                                                <?php if ($spLv === 0): ?>
                                                    <span class="badge badge-light text-success px-2 py-1" style="border: 1px solid #bbf7d0; font-size: 11px; border-radius: 999px;" title="<?= $spTooltip ?>">SP 0</span>
                                                    <div class="small text-muted" style="font-size: 10px;">Maks 100%</div>
                                                <?php elseif ($spLv === 1): ?>
                                                    <span class="badge badge-warning text-dark px-2 py-1" style="font-size: 11px; font-weight: 700; border-radius: 999px;" title="<?= $spTooltip ?>">SP 1</span>
                                                    <div class="small font-weight-bold text-warning" style="font-size: 10px;">Maks <?= $spCp ?>%</div>
                                                <?php elseif ($spLv === 2): ?>
                                                    <span class="badge text-white px-2 py-1" style="background: #ea580c; font-size: 11px; font-weight: 700; border-radius: 999px;" title="<?= $spTooltip ?>">SP 2</span>
                                                    <div class="small font-weight-bold" style="color: #ea580c; font-size: 10px;">Maks <?= $spCp ?>%</div>
                                                <?php else: ?>
                                                    <span class="badge badge-danger px-2 py-1" style="font-size: 11px; font-weight: 700; border-radius: 999px;" title="<?= $spTooltip ?>">SP 3</span>
                                                    <div class="small font-weight-bold text-danger" style="font-size: 10px;">Maks <?= $spCp ?>%</div>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center align-middle" style="background: #f0fdf4;">
                                                <?php if ($lb->skor_akhir !== null): ?>
                                                    <div class="tp-lb-score-pill" style="color: <?= $lb->grade_color ?>; background: rgba(255,255,255,0.85); border: 1px solid rgba(16, 185, 129, 0.22);">
                                                        <?= number_format($lb->skor_akhir, 1) ?>
                                                    </div>
                                                    <?php if ($lb->status_nilai === 'lengkap'): ?>
                                                        <div class="small font-weight-bold text-success mt-1" style="font-size: 10px;">
                                                            <i class="fas fa-check-circle text-success mr-1"></i>Mutu <?= number_format($lb->skor_mutu, 1) ?> &bull; Vol <?= number_format($lb->skor_prod, 0) ?>%
                                                        </div>
                                                    <?php elseif ($lb->status_nilai === 'parsial_no_eval'): ?>
                                                        <div class="small text-info font-weight-bold mt-1" style="font-size: 10px;">
                                                            <span class="badge badge-info py-0 px-1 mr-1" style="font-size: 9px; font-weight: 600;">Non-Eval</span>Mutu <?= number_format($lb->skor_mutu, 1) ?> &bull; Vol <?= number_format($lb->skor_prod, 0) ?>%
                                                        </div>
                                                    <?php else: ?>
                                                        <div class="small text-muted mt-1" style="font-size: 10px;">
                                                            <span class="badge badge-warning text-dark py-0 px-1 mr-1" style="font-size: 9px; font-weight: 600;">Parsial</span>Mutu <?= number_format($lb->skor_mutu, 1) ?> &bull; Vol <?= number_format($lb->skor_prod, 0) ?>%
                                                        </div>
                                                    <?php endif; ?>
                                                    <?php if (!empty($lb->is_capped_sp)): ?>
                                                        <div class="small text-danger font-weight-bold" style="font-size: 9.5px;">
                                                            Asli <?= number_format($lb->skor_akhir_raw, 1) ?> &rarr; Cap <?= htmlspecialchars($lb->sp_label) ?>
                                                        </div>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <span class="text-muted small">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center align-middle">
                                                <?php if ($lb->skor_akhir !== null): ?>
                                                    <div><?= renderStarsHtml($lb->rating_agregat) ?></div>
                                                    <span class="font-weight-bold ml-1" style="font-size:12px; color:#d97706;"><?= number_format($lb->rating_agregat, 2) ?> / 5.0</span>
                                                <?php else: ?>
                                                    <span class="text-muted small">Belum dinilai</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center align-middle">
                                                <?php if ($lb->grade !== '-'): ?>
                                                    <span class="badge <?= $lb->grade_badge ?> px-2 py-1" style="font-size: 11.5px; font-weight: 700; border-radius: 999px;">
                                                        Grade <?= $lb->grade ?>
                                                    </span>
                                                    <div class="small font-weight-bold mt-1" style="font-size: 11px; color: <?= $lb->grade_color ?>;">
                                                        <?= htmlspecialchars($lb->grade_label) ?>
                                                    </div>
                                                <?php else: ?>
                                                    <span class="badge badge-secondary px-2 py-1" style="font-size: 11px; border-radius: 999px;">Belum Dinilai</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php $rank++; endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    </div>
</div>

<!-- =========================================================================
     MODAL 1: BUAT TASK / PROYEK BARU (ATASAN -> KARYAWAN)
     ========================================================================= -->
<div class="modal fade" id="modalBuatTask" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="formBuatTask">
                <div class="modal-header d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div style="width: 42px; height: 42px; border-radius: 10px; background: #ecfdf5; border: 1.5px solid #a7f3d0; display: flex; align-items: center; justify-content: center; font-size: 19px; color: #059669; flex-shrink: 0; margin-right: 14px;">
                            <i class="fas fa-tasks"></i>
                        </div>
                        <div>
                            <h5 class="modal-title font-weight-bold mb-0" style="color: #0f172a; font-size: 17px; letter-spacing: -0.2px;">
                                Buat Penugasan Task / Proyek ke Karyawan
                            </h5>
                            <small class="text-muted d-block" style="font-size: 12px; margin-top: 2px;">
                                Delegasikan instruksi kerja baru atau ajukan task mandiri ke HRD
                            </small>
                        </div>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #64748b; font-size: 26px; font-weight: 300; opacity: 0.7; transition: opacity 0.2s; outline: none; margin-top: -6px;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <!-- SECTION 1: PILIH KARYAWAN PENERIMA TUGAS -->
                    <div class="p-3 mb-3 rounded" style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 10px;">
                        <div class="d-flex justify-content-between align-items-center flex-wrap mb-2">
                            <label class="form-label-custom mb-0 font-weight-bold" style="color: #0f172a; font-size: 12px;">
                                <span><i class="fas fa-user-friends text-success mr-1"></i> PILIH KARYAWAN PENERIMA TUGAS <span class="text-danger">*</span></span>
                            </label>
                            <?php if (!empty($penerimaList)): ?>
                                <button type="button" class="btn btn-xs btn-outline-success font-weight-bold shadow-none" id="btnSelectSupervisedEmployees" style="border-radius: 20px; padding: 3px 12px; font-size: 11px;">
                                    <i class="fas fa-check-double mr-1"></i> <?= $canAssignToAll ? 'Pilih Semua Pegawai' : 'Pilih Semua Bawahan' ?> (<?= count($penerimaList) ?>)
                                </button>
                            <?php endif; ?>
                        </div>
                        <?php if ($canAssignToAll): ?>
                            <small class="text-primary d-block mb-2 font-weight-semibold" style="font-size: 11.5px;">
                                <i class="fas fa-shield-alt mr-1"></i> Anda memiliki akses untuk memberikan task / proyek kepada seluruh pegawai perusahaan.
                            </small>
                        <?php else: ?>
                            <small class="text-muted d-block mb-2" style="font-size: 11.5px;">
                                <i class="fas fa-info-circle mr-1"></i> Hanya menampilkan karyawan yang berada di bawah supervisi / atasan langsung Anda.
                            </small>
                        <?php endif; ?>
                        <select name="id_penerima[]" id="select_penerima_task" class="form-control" multiple="multiple" required style="width: 100%;">
                            <?php foreach ($penerimaList as $bw):
                                $divLabel = !empty($bw->nama_divisi) ? $bw->nama_divisi : 'Umum';
                                $isSelf = ((int)$bw->pengguna_id === (int)$myId);
                            ?>
                                <option value="<?= (int)$bw->pengguna_id ?>" <?= $isSelf ? 'data-self="1"' : '' ?>>
                                    <?= $isSelf ? "[Diri Sendiri] " : "" ?><?= htmlspecialchars($bw->nama) ?> <?= !empty($bw->jabatan) ? "(" . htmlspecialchars($bw->jabatan) . ")" : "" ?> - [<?= htmlspecialchars($divLabel) ?>]<?= $isSelf ? " (Pengajuan Mandiri)" : "" ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div id="alert_task_mandiri" class="alert alert-warning py-2 px-3 mt-2 mb-0" style="display: none; font-size: 12px; border-left: 4px solid #f59e0b; background: #fffbeb; color: #92400e; border-radius: 6px;">
                            <i class="fas fa-shield-alt text-warning mr-1"></i>
                            <strong>Pengajuan Task Mandiri:</strong> Tugas ini diajukan untuk diri Anda sendiri. Sesuai ketentuan, tugas akan dikirimkan ke <strong>HRD</strong> untuk di-<strong>ACC</strong> terlebih dahulu sebelum Anda dapat mulai mengerjakan.
                        </div>
                        <?php if (empty($penerimaList)): ?>
                            <div class="alert alert-warning py-2 px-3 mt-2 mb-0 small" style="border-radius: 6px;">
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                <strong>Belum ada karyawan yang dibawahi:</strong> Anda hanya dapat memberikan tugas kepada karyawan yang dibawahi langsung oleh Anda. Belum ada karyawan yang memilih Anda sebagai <em>Atasan Langsung</em>. Karyawan dapat memilih Anda di menu <strong>Profil Karyawan</strong> mereka, atau Admin/HRD dapat mengaturnya di menu <strong>Data Karyawan</strong>.
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Container Dropdown Jobdesk Sasaran Mutu (Hanya muncul jika 1 orang karyawan dipilih) -->
                    <div id="container_jobdesk_karyawan" style="display: none; background: #f0fdf4; border: 1.5px solid #86efac; border-radius: 10px; padding: 14px 16px; margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.03);">
                        <div class="d-flex align-items-center justify-content-between flex-wrap mb-1" style="gap: 8px;">
                            <label class="font-weight-bold text-success mb-0" style="font-size: 12.5px;">
                                <i class="fas fa-bullseye text-success mr-1"></i> Hubungkan ke Jobdesk (Sama dengan Laporan Mingguan) <span class="text-danger">* (Wajib)</span>
                            </label>
                            <span class="badge badge-success px-2 py-1" id="badge_info_karyawan" style="font-size: 11px; border-radius: 12px;">
                                <i class="fas fa-user mr-1"></i> <span id="nama_karyawan_terpilih">-</span>
                            </span>
                        </div>
                        <small class="text-muted d-block mb-2" style="font-size: 11.5px;">
                            <i class="fas fa-sync-alt text-success mr-1"></i> Penugasan ini akan otomatis terhubung ke jobdesk karyawan terkait dan disinkronkan ke <strong>Sasaran Mutu</strong> serta <strong>Audit Kinerja</strong>.
                        </small>

                        <!-- Input Hidden untuk form submission -->
                        <input type="hidden" name="id_sasaran_mutu" id="select_jobdesk_karyawan" value="">
                        <input type="hidden" name="nama_jobdesk" id="input_nama_jobdesk" value="">

                        <!-- Custom Searchable Dropdown Picker -->
                        <div class="dropdown" id="dropdown_jobdesk_container">
                            <button type="button" class="btn btn-default" id="btn_toggle_jobdesk">
                                <span id="label_selected_jobdesk" style="font-size: 13px; color: #64748b; line-height: 1.45; word-break: break-word; text-align: left;">-- Pilih Jobdesk Sasaran Mutu Terkait (Wajib) --</span>
                                <i class="fas fa-chevron-down text-muted" style="font-size: 12px; flex-shrink: 0;"></i>
                            </button>

                            <div id="menu_jobdesk_list">
                                <!-- Search Box -->
                                <div class="p-2 border-bottom" style="background: #f8fafc;">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-white border-right-0"><i class="fas fa-search text-muted"></i></span>
                                        </div>
                                        <input type="text" id="filter_jobdesk_search" class="form-control border-left-0" placeholder="Ketik kata kunci jobdesk...">
                                    </div>
                                </div>

                                <!-- Loading Indicator -->
                                <div id="jobdesk_loading" class="p-3 text-center text-muted small" style="display: none;">
                                    <i class="fas fa-spinner fa-spin text-success mr-1"></i> Memuat daftar jobdesk resmi karyawan...
                                </div>

                                <!-- Items List -->
                                <div id="container_jobdesk_items" style="max-height: 290px; overflow-y: auto;">
                                    <!-- Dynamic items -->
                                </div>
                            </div>
                        </div>

                        <div id="jobdesk_detail_preview" class="mt-2 small text-dark p-3 bg-white rounded border border-success" style="display: none; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border-radius: 8px;">
                            <div class="font-weight-bold text-success mb-1" style="font-size: 13px;">
                                <i class="fas fa-check-circle mr-1"></i> <span id="text_jobdesk_title">-</span>
                            </div>
                            <div id="text_jobdesk_detail" class="mt-1" style="white-space: pre-line; line-height: 1.45; color: #334155; font-size: 12px;">-</div>
                        </div>
                    </div>

                    <!-- ROW: NAMA PROYEK, JENIS PENUGASAN, PRIORITAS -->
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group mb-3">
                                <label class="form-label-custom">
                                    <span><i class="fas fa-folder text-muted mr-1"></i> Nama Proyek / Kategori</span>
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-briefcase"></i></span>
                                    </div>
                                    <input type="text" name="nama_proyek" class="form-control" placeholder="Contoh: Proyek CCTV / Operasional IT">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label-custom">
                                    <span><i class="fas fa-tags text-muted mr-1"></i> Jenis Penugasan</span>
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-clipboard-list"></i></span>
                                    </div>
                                    <select name="jenis" class="form-control">
                                        <option value="Task Harian">Task Harian</option>
                                        <option value="Proyek Mingguan">Proyek Mingguan</option>
                                        <option value="Proyek Bulanan / Khusus">Proyek Bulanan / Khusus</option>
                                        <option value="Urgent / Insidental">Urgent / Insidental</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-3">
                                <label class="form-label-custom">
                                    <span><i class="fas fa-flag text-muted mr-1"></i> Prioritas</span>
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-signal"></i></span>
                                    </div>
                                    <select name="prioritas" class="form-control">
                                        <option value="Low">Low (Rendah)</option>
                                        <option value="Medium" selected>Medium (Normal)</option>
                                        <option value="High">High (Tinggi)</option>
                                        <option value="Urgent">Urgent (Mendesak)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ROW: JUDUL TASK -->
                    <div class="form-group mb-3">
                        <label class="form-label-custom">
                            <span><i class="fas fa-pencil-alt text-success mr-1"></i> Judul Task / Instruksi Utama <span class="text-danger">*</span></span>
                        </label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-heading"></i></span>
                            </div>
                            <input type="text" name="judul" class="form-control" required placeholder="Contoh: Penarikan Kabel Jaringan & Konfigurasi Router Lantai 2" style="font-weight: 500;">
                        </div>
                    </div>

                    <!-- ROW: TANGGAL MULAI & TARGET SELESAI -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label-custom">
                                    <span><i class="far fa-calendar-alt text-muted mr-1"></i> Tanggal Mulai</span>
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                    </div>
                                    <input type="date" name="tgl_mulai" class="form-control" value="<?= date('Y-m-d') ?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label-custom">
                                    <span class="text-danger"><i class="far fa-calendar-check mr-1"></i> Target Selesai (Deadline) *</span>
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="far fa-calendar-check text-danger"></i></span>
                                    </div>
                                    <input type="date" name="tgl_deadline" class="form-control" value="<?= date('Y-m-d', strtotime('+3 days')) ?>" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ROW: DESKRIPSI -->
                    <div class="form-group mb-3">
                        <label class="form-label-custom">
                            <span><i class="fas fa-align-left text-muted mr-1"></i> Deskripsi / Rincian Instruksi Kerja</span>
                        </label>
                        <textarea name="deskripsi" rows="3" class="form-control" placeholder="Tuliskan rincian target, spesifikasi hasil yang diharapkan, atau langkah kerja..."></textarea>
                    </div>

                    <!-- ROW: MENGETAHUI (PIHAK TERKAIT / ATASAN) -->
                    <div class="p-3 mb-3 rounded" style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 10px;">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <label class="form-label-custom mb-0 font-weight-bold" style="color: #0f172a; font-size: 12px;">
                                <span><i class="fas fa-eye text-info mr-1"></i> Mengetahui (Pihak Terkait / Atasan)</span>
                            </label>
                            <span class="badge badge-light border text-muted" style="font-size: 11px; border-radius: 6px;">Opsional</span>
                        </div>
                        <select name="id_mengetahui" id="select_mengetahui_task" class="form-control" style="width: 100%;">
                            <option value="">-- Pilih Karyawan / Atasan yang Mengetahui (Opsional) --</option>
                            <?php foreach ($list_semua_karyawan_all as $kw):
                                if ((int)$kw->pengguna_id === (int)$myId) continue;
                            ?>
                                <option value="<?= (int)$kw->pengguna_id ?>">
                                    <?= htmlspecialchars($kw->nama) ?> <?= !empty($kw->jabatan) ? '(' . htmlspecialchars($kw->jabatan) . ')' : '' ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <small class="text-muted d-block mt-2" style="font-size: 11.5px; line-height: 1.4;">
                            <i class="fas fa-bell text-warning mr-1"></i> Pihak yang dipilih di kolom Mengetahui <strong>hanya akan dikirimkan notifikasi WhatsApp setelah task disetujui (ACC) oleh HRD</strong>.
                        </small>
                    </div>

                    <!-- ROW: LINK LAMPIRAN / REFERENSI -->
                    <div class="form-group mb-3">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <label class="form-label-custom mb-0">
                                <span><i class="fas fa-paperclip text-muted mr-1"></i> Link Lampiran / Referensi dari Atasan</span>
                            </label>
                            <span class="badge badge-light border text-muted" style="font-size: 11px; border-radius: 6px;">Opsional</span>
                        </div>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-link"></i></span>
                            </div>
                            <input type="text" name="lampiran_atasan" class="form-control" placeholder="https://drive.google.com/... (Link dokumen, desain, atau referensi)">
                        </div>
                        <small class="text-muted d-block mt-1" style="font-size: 11.5px;">
                            <i class="fas fa-info-circle text-primary mr-1"></i> Catatan: <strong>Checklist langkah kerja / sub-task</strong> akan diisi dan dicentang langsung oleh <strong>Karyawan Penerima Tugas</strong> sebagai dasar perhitungan progres.
                        </small>
                    </div>

                    <!-- ROW: NOTIFIKASI WHATSAPP -->
                    <div class="p-3 rounded mb-1 d-flex align-items-center" style="background: #f0fdf4; border: 1.5px solid #bbf7d0; border-radius: 10px;">
                        <input type="checkbox" id="chk_kirim_wa" name="kirim_wa" value="1" checked style="width: 18px; height: 18px; accent-color: #10b981; cursor: pointer; margin-right: 12px;">
                        <label for="chk_kirim_wa" class="font-weight-bold text-success mb-0" style="cursor: pointer; font-size: 13px; user-select: none;">
                            <i class="fab fa-whatsapp mr-1" style="font-size: 15px;"></i> Kirim Notifikasi WhatsApp Otomatis ke Karyawan
                        </label>
                    </div>
                </div>

                <div class="modal-footer d-flex justify-content-between align-items-center">
                    <button type="button" class="btn btn-light px-4 py-2 font-weight-bold" data-dismiss="modal" style="border-radius: 8px; border: 1.5px solid #cbd5e1; color: #475569; background: #ffffff;">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-success px-4 py-2 font-weight-bold shadow-sm" id="btnSubmitBuatTask" <?= empty($penerimaList) ? 'disabled title="Tidak ada karyawan yang dapat dipilih"' : '' ?> style="border-radius: 8px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none; font-size: 13.5px; letter-spacing: 0.2px;">
                        <i class="fas fa-paper-plane mr-1"></i> Simpan & Tugaskan Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- =========================================================================
     MODAL 2: DETAIL TASK, UPDATE PROGRES, SUBMIT SELESAI & REVIEW BINTANG 5
     ========================================================================= -->
<div class="modal fade" id="modalDetailTask" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center" style="min-width: 0; flex: 1;">
                    <div style="width: 42px; height: 42px; border-radius: 10px; background: #eff6ff; border: 1.5px solid #bfdbfe; display: flex; align-items: center; justify-content: center; font-size: 18px; color: #2563eb; flex-shrink: 0; margin-right: 14px;">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <div style="min-width: 0;">
                        <h5 class="modal-title font-weight-bold mb-0" style="color: #0f172a; font-size: 17px; letter-spacing: -0.2px; word-break: break-word;">
                            Detail Task: <span id="dt_kode_task" class="text-primary">-</span>
                        </h5>
                        <small class="text-muted d-block" style="font-size: 12px; margin-top: 2px;">
                            Monitoring progres pekerjaan, verifikasi checklist & submit review
                        </small>
                    </div>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #64748b; font-size: 26px; font-weight: 300; opacity: 0.7; transition: opacity 0.2s; outline: none; margin-top: -6px; flex-shrink: 0;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <!-- INFO RINGKAS TASK -->
                <div class="p-3 mb-3 rounded" style="background: #f8fafc; border: 1px solid #e2e8f0; overflow-wrap: break-word; word-break: break-word;">
                    <div class="d-flex justify-content-between align-items-start flex-wrap" style="gap: 12px;">
                        <div style="flex: 1 1 300px; min-width: 0;">
                            <div class="d-flex flex-wrap align-items-center" style="gap: 6px;">
                                <span class="badge badge-info" id="dt_nama_proyek" style="white-space: normal; text-align: left;">Proyek</span>
                                <span class="badge badge-secondary" id="dt_jenis">Jenis</span>
                                <span id="dt_prioritas_badge"></span>
                            </div>
                            <div id="dt_jobdesk_badge" style="display:none;"></div>
                            <h4 class="font-weight-bold mt-2 mb-1" id="dt_judul" style="color:#0f172a; white-space: normal; word-break: break-word; overflow-wrap: anywhere; line-height: 1.35; font-size: 18px;">-</h4>
                        </div>
                        <div class="text-md-right" style="flex-shrink: 0;">
                            <div id="dt_status_badge"></div>
                            <div class="small text-muted mt-1">Deadline: <strong id="dt_deadline">-</strong></div>
                        </div>
                    </div>
                    <hr class="my-2">
                    <div class="row small">
                        <div class="col-md-6 mb-1 mb-md-0">
                            <strong>Pemberi Tugas / Pengaju:</strong> <span id="dt_pemberi">-</span>
                        </div>
                        <div class="col-md-6">
                            <strong>Pelaksana (Karyawan):</strong> <span id="dt_penerima">-</span>
                        </div>
                    </div>
                    <div class="row small mt-1">
                        <div class="col-md-6 mb-1 mb-md-0">
                            <strong><i class="fas fa-eye text-primary mr-1"></i> Mengetahui:</strong> <span id="dt_mengetahui">-</span>
                        </div>
                        <div class="col-md-6">
                            <strong><i class="fas fa-check-double text-success mr-1"></i> Status ACC HRD:</strong> <span id="dt_acc_hrd_info">-</span>
                        </div>
                    </div>
                    <div class="mt-2">
                        <strong class="small text-uppercase text-muted">Instruksi / Deskripsi Pekerjaan:</strong>
                        <div id="dt_deskripsi" class="p-2 mt-1 rounded bg-white border" style="white-space: pre-line; word-break: break-word; overflow-wrap: anywhere; font-size: 13px;">-</div>
                    </div>
                    <div id="dt_lampiran_atasan_wrap" class="mt-2" style="display:none;">
                        <a href="#" id="dt_lampiran_atasan_link" target="_blank" class="btn btn-xs btn-outline-primary">
                            <i class="fas fa-paperclip"></i> Buka Lampiran Referensi Atasan
                        </a>
                    </div>
                    <div id="dt_revisi_wrap" class="alert alert-danger mt-2 mb-0 py-2" style="display:none;">
                        <strong><i class="fas fa-redo"></i> Catatan Permintaan Revisi dari Atasan:</strong>
                        <div id="dt_catatan_revisi"></div>
                    </div>
                    <div id="dt_sp_alert_wrap" class="mt-2" style="display:none;"></div>
                    <div id="dt_readonly_checklist_wrap" class="mt-2 p-2 rounded bg-white border" style="display:none;"></div>
                </div>

                <!-- HASIL PENILAIAN BINTANG 5 (TAMPIL JIKA SUDAH DINILAI) -->
                                <!-- KOTAK PERSETUJUAN ACC HRD (HANYA MUNCUL JIKA STATUS MENUNGGU ACC HRD) -->
                <div id="dt_hrd_acc_box" class="p-3 mb-3 rounded" style="display: none; background: #fffbeb; border: 2px solid #f59e0b;">
                    <div class="d-flex justify-content-between align-items-center flex-wrap" style="gap: 10px;">
                        <div>
                            <div class="font-weight-bold text-dark" style="font-size: 14px;"><i class="fas fa-hourglass-start text-warning mr-1"></i> Pengajuan Task Mandiri (Menunggu ACC HRD)</div>
                            <div class="small text-muted mt-1" id="dt_hrd_acc_desc">Task ini diajukan secara mandiri oleh karyawan dan memerlukan persetujuan (ACC) HRD sebelum pengerjaan dapat dimulai.</div>
                        </div>
                        <div id="dt_hrd_acc_actions" style="display: none; gap: 6px;">
                            <button type="button" class="btn btn-sm btn-success font-weight-bold btn-modal-acc-hrd" id="btn_modal_acc_hrd">
                                <i class="fas fa-check-circle mr-1"></i> ACC / Setujui Task
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger font-weight-bold btn-modal-tolak-hrd" id="btn_modal_tolak_hrd">
                                <i class="fas fa-times-circle mr-1"></i> Tolak
                            </button>
                        </div>
                    </div>
                </div>

                <div id="dt_hasil_rating_box" class="p-3 mb-3 rounded" style="display:none; background:#fffbeb; border: 2px solid #f59e0b;">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <div>
                            <div class="text-uppercase small font-weight-bold" style="color:#b45309;">🌟 Hasil Penilaian Atasan</div>
                            <div id="dt_rating_stars_html" style="font-size: 20px;" class="my-1"></div>
                            <div id="dt_ulasan_atasan_text" class="font-italic text-dark"></div>
                        </div>
                        <div class="text-right small text-muted" id="dt_tgl_dinilai"></div>
                    </div>
                </div>

                <!-- PANEL 1: CHECKLIST & UPDATE PROGRES (DIISI OLEH PELAKSANA / KARYAWAN PENERIMA TASK) -->
                <div id="panelBawahanProgres" class="card mb-3 border-primary">
                    <div class="card-header bg-light py-2 d-flex justify-content-between align-items-center flex-wrap">
                        <strong class="text-primary"><i class="fas fa-tasks mr-1"></i> Checklist Langkah Kerja Saya & Update Progres</strong>
                        <button type="button" class="btn btn-xs btn-outline-primary font-weight-bold" id="btnAddBawahanChecklist">
                            <i class="fas fa-plus mr-1"></i> Tambah Item Checklist
                        </button>
                    </div>
                    <div class="card-body py-3">
                        <form id="formUpdateProgres">
                            <input type="hidden" name="id_task" id="up_id_task">
                            <input type="hidden" name="auto_calc_checklist" id="up_auto_calc" value="1">

                            <div class="small text-muted mb-2">
                                Tambahkan langkah-langkah kerja (sub-task) Anda di bawah ini, lalu centang item yang sudah dikerjakan untuk menghitung persentase progres secara otomatis:
                            </div>
                            <div id="dt_checklist_container" class="mb-3"></div>

                            <div class="form-group mb-2">
                                <label class="font-weight-bold d-flex justify-content-between">
                                    <span>Persentase Progres Pengerjaan:</span>
                                    <span class="badge badge-primary" style="font-size:14px;"><span id="up_progres_label">0</span>%</span>
                                </label>
                                <input type="range" name="progres" id="up_progres_slider" class="form-control-range" min="0" max="100" step="5" value="0">
                            </div>

                            <div class="form-group mb-2">
                                <input type="text" name="catatan" id="up_catatan" class="form-control form-control-sm" placeholder="Catatan update progres (opsional, misal: Sedang proses instalasi tahap 1)...">
                            </div>

                            <div class="text-right">
                                <button type="submit" class="btn btn-sm btn-primary font-weight-bold">
                                    <i class="fas fa-save mr-1"></i> Simpan Update Progres
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- PANEL 2: SUBMIT LAPORAN SELESAI 100% KE ATASAN -->
                <div id="panelBawahanSubmit" class="card mb-3 border-success">
                    <div class="card-header bg-light py-2">
                        <strong class="text-success"><i class="fas fa-check-circle mr-1"></i> Laporkan Pekerjaan Selesai (100%) ke Atasan</strong>
                    </div>
                    <div class="card-body py-3">
                        <form id="formSubmitSelesai">
                            <input type="hidden" name="id_task" id="sub_id_task">
                            <div class="form-group mb-2">
                                <label class="font-weight-bold small">Ringkasan Hasil Pekerjaan / Penyelesaian <span class="text-danger">*</span></label>
                                <textarea name="catatan_bawahan" id="sub_catatan_bawahan" rows="2" class="form-control" required placeholder="Jelaskan hasil pekerjaan yang telah diselesaikan..."></textarea>
                            </div>
                            <div class="form-group mb-2">
                                <label class="font-weight-bold small">Link Bukti Selesai / Foto / Google Drive (Opsional)</label>
                                <input type="text" name="bukti_selesai" id="sub_bukti_selesai" class="form-control form-control-sm" placeholder="https://drive.google.com/...">
                            </div>
                            <div class="text-right">
                                <button type="submit" id="btnSubmitSelesai" class="btn btn-sm btn-success font-weight-bold">
                                    <i class="fas fa-paper-plane mr-1"></i> Kirim Hasil Pekerjaan & Minta Rating ⭐
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- NOTIFIKASI VALIDASI SUDAH TERKIRIM (MENUNGGU PENILAIAN) -->
                <div id="panelSudahDisubmitInfo" class="alert alert-success py-3 px-3 mb-3" style="display:none; border-left: 4px solid #10b981; background: #ecfdf5;">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-hourglass-half fa-2x text-success mr-3"></i>
                        <div>
                            <h6 class="mb-1 font-weight-bold text-success"><i class="fas fa-check-circle mr-1"></i> Hasil Pekerjaan Sudah Terkirim!</h6>
                            <div class="small text-muted">Laporan penyelesaian tugas ini telah berhasil diserahkan (100%) dan saat ini sedang <strong>Menunggu Penilaian / Rating Bintang ⭐ dari HRD / Manajemen / Atasan</strong>.</div>
                            <div class="small text-muted mt-1" style="font-size: 11.5px;"><i class="fas fa-shield-alt text-warning mr-1"></i> Sesuai standar penilaian objektif perusahaan, tugas yang diajukan ke diri sendiri dinilai langsung oleh HRD dan Manajemen.</div>
                        </div>
                    </div>
                </div>

                <!-- PANEL 3: FORM PENILAIAN RATING BINTANG 5 & REVIEW ATASAN -->
                <div id="panelAtasanReview" class="card mb-3" style="border: 2px solid #f59e0b; display:none;">
                    <div class="card-header py-2" style="background: #fef3c7;">
                        <strong style="color:#b45309;"><i class="fas fa-star mr-1"></i> Form Evaluasi & Penilaian Bintang 5 (Khusus Atasan / HRD / Manajemen)</strong>
                    </div>
                    <div class="card-body py-3">
                        <form id="formReviewAtasan">
                            <input type="hidden" name="id_task" id="rev_id_task">
                            <input type="hidden" name="rating_bintang" id="rev_rating_bintang" value="5">

                            <div class="form-group mb-2">
                                <label class="font-weight-bold">Keputusan Review Atasan:</label>
                                <div class="mt-1">
                                    <label class="mr-3" style="cursor:pointer;">
                                        <input type="radio" name="keputusan" value="terima" checked>
                                        <span class="badge badge-success p-1"><i class="fas fa-check"></i> Setujui Selesai & Beri Rating Bintang ⭐</span>
                                    </label>
                                    <label style="cursor:pointer;">
                                        <input type="radio" name="keputusan" value="revisi">
                                        <span class="badge badge-danger p-1"><i class="fas fa-redo"></i> Kembalikan untuk Revisi</span>
                                    </label>
                                </div>
                            </div>

                            <!-- PILIH BINTANG 1 - 5 INTERAKTIF -->
                            <div id="wrapStarPicker" class="p-3 mb-3 rounded text-center" style="background:#fffbeb; border:1px dashed #f59e0b;">
                                <div id="wrapOverdueMaxStarAlert" class="alert alert-danger py-2 px-3 mb-2 text-left small" style="display:none;">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    <strong>Melewati Batas Deadline (<span id="lblOverdueDays">0</span> hari):</strong> Sesuai aturan kedisiplinan, task yang terlambat <strong>maksimal mendapatkan Rating Bintang <span id="lblMaxAllowedStars">1</span> (⭐ <span id="lblMaxAllowedStars2">1</span>/5)</strong>.
                                </div>
                                <div class="small font-weight-bold text-uppercase mb-1" style="color:#b45309;">Klik Bintang untuk Memberikan Nilai Kualitas Kerja Karyawan:</div>
                                <div class="star-rating-picker my-1" id="starPickerWidget">
                                    <i class="fas fa-star star-btn active" data-val="1" title="1 Bintang - Kurang Memuaskan"></i>
                                    <i class="fas fa-star star-btn active" data-val="2" title="2 Bintang - Perlu Banyak Perbaikan"></i>
                                    <i class="fas fa-star star-btn active" data-val="3" title="3 Bintang - Cukup Baik / Sesuai Standar"></i>
                                    <i class="fas fa-star star-btn active" data-val="4" title="4 Bintang - Sangat Baik & Rapi"></i>
                                    <i class="fas fa-star star-btn active" data-val="5" title="5 Bintang - Istimewa / Sempurna & Cepat!"></i>
                                </div>
                                <div id="starPickerLabel" class="font-weight-bold mt-1" style="color:#d97706; font-size:14px;">
                                    ⭐⭐⭐⭐⭐ (5 / 5 — Istimewa / Sangat Memuaskan!)
                                </div>
                            </div>

                            <div class="form-group mb-2">
                                <label class="font-weight-bold small">Ulasan Apresiasi / Catatan Evaluasi Atasan:</label>
                                <div class="mb-1">
                                    <button type="button" class="btn btn-xs btn-outline-secondary btn-quick-ulasan" data-text="Kerja bagus, selesai tepat waktu dan hasil sangat rapi!">👍 Kerja bagus & tepat waktu</button>
                                    <button type="button" class="btn btn-xs btn-outline-secondary btn-quick-ulasan" data-text="Eksekusi sangat cepat, komunikasi dan laporan sangat jelas. Pertahankan!">🌟 Eksekusi cepat & jelas</button>
                                    <button type="button" class="btn btn-xs btn-outline-secondary btn-quick-ulasan" data-text="Pekerjaan sudah selesai sesuai instruksi, namun ke depannya mohon lebih teliti.">📝 Sesuai standar, tingkatkan ketelitian</button>
                                </div>
                                <textarea name="ulasan_atasan" id="rev_ulasan_atasan" rows="2" class="form-control" placeholder="Tulis komentar penilaian atau catatan revisi untuk karyawan..."></textarea>
                            </div>

                            <div class="text-right">
                                <button type="submit" class="btn btn-warning font-weight-bold" id="btnSubmitReviewAtasan">
                                    <i class="fas fa-award mr-1"></i> Simpan Penilaian & Kirim WA ke Karyawan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- RIWAYAT LOG AKTIVITAS TASK -->
                <div>
                    <h6 class="font-weight-bold text-muted text-uppercase mb-2"><i class="fas fa-history mr-1"></i> Riwayat Aktivitas & Log Progres</h6>
                    <div id="dt_logs_container" style="max-height: 220px; overflow-y: auto;"></div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL 3: ATUR ATASAN LANGSUNG SAYA -->
<div class="modal fade" id="modalAturAtasanSaya" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="formAturAtasanSaya">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title font-weight-bold">
                        <i class="fas fa-user-shield mr-1"></i> Pilih Atasan Langsung Anda
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="small text-muted mb-2">
                        Tentukan siapa <strong>Atasan Langsung / Supervisor</strong> yang membawahi Anda:
                    </p>
                    <div class="form-group">
                        <label class="font-weight-bold">Pilih Atasan Langsung</label>
                        <select id="select_atasan_saya_tp" name="id_atasan" class="form-control">
                            <option value="">-- Belum Ditentukan / Tidak Ada --</option>
                            <?php foreach ($list_semua_karyawan as $atOpt):
                                if ((int)$atOpt->pengguna_id === (int)$myId) continue;
                            ?>
                                <option value="<?= (int)$atOpt->pengguna_id ?>" <?= ((int)($me->id_atasan ?? 0) === (int)$atOpt->pengguna_id) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($atOpt->nama) ?><?= !empty($atOpt->jabatan) ? ' (' . htmlspecialchars($atOpt->jabatan) . ')' : '' ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary font-weight-bold" id="btnSimpanAtasanSaya">
                        <i class="fas fa-save mr-1"></i> Simpan Atasan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL 4: JADWALKAN PERTEMUAN HRD (SAAT TASK KENA SP 3) -->
<div class="modal fade" id="modalJadwalHrdSp3" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="formJadwalHrdSp3">
                <input type="hidden" name="id_task" id="jhrd_id_task">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title font-weight-bold">
                        <i class="fas fa-gavel mr-1"></i> Penjadwalan Pertemuan HRD (Tindak Lanjut SP 3)
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger py-2 px-3 mb-3 small">
                        <strong><i class="fas fa-exclamation-triangle mr-1"></i> Peringatan Eskalasi SP 3:</strong><br>
                        Task <strong id="jhrd_kode_task">-</strong> (<span id="jhrd_judul_task">-</span>) untuk karyawan <strong id="jhrd_nama_karyawan">-</strong> telah melewati deadline selama <strong id="jhrd_hari_telat">21</strong> hari (&ge; 3 minggu). Silakan atur jadwal pertemuan evaluasi dengan HRD.
                    </div>
                    <div class="row">
                        <div class="col-md-7 form-group">
                            <label class="font-weight-bold small">Tanggal Pertemuan HRD <span class="text-danger">*</span></label>
                            <input type="date" name="hrd_jadwal_tgl" id="jhrd_tgl" class="form-control" required value="<?= date('Y-m-d', strtotime('+1 day')) ?>">
                        </div>
                        <div class="col-md-5 form-group">
                            <label class="font-weight-bold small">Jam Pertemuan <span class="text-danger">*</span></label>
                            <input type="time" name="hrd_jadwal_jam" id="jhrd_jam" class="form-control" required value="10:00">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold small">Metode Pertemuan</label>
                            <select name="hrd_jadwal_metode" id="jhrd_metode" class="form-control">
                                <option value="Tatap Muka (Offline)">Tatap Muka (Offline)</option>
                                <option value="Online (Zoom / Google Meet)">Online (Zoom / Google Meet)</option>
                                <option value="Telepon / WhatsApp Call">Telepon / WhatsApp Call</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold small">Lokasi / Link Meeting <span class="text-danger">*</span></label>
                            <input type="text" name="hrd_jadwal_lokasi" id="jhrd_lokasi" class="form-control" required placeholder="Ruang HRD / Link Meet" value="Ruang HRD">
                        </div>
                    </div>
                    <div class="form-group mb-0">
                        <label class="font-weight-bold small">Agenda / Catatan Pemanggilan HRD</label>
                        <textarea name="hrd_jadwal_catatan" id="jhrd_catatan" rows="2" class="form-control" placeholder="Evaluasi keterlambatan penyelesaian task proyek & pembinaan disiplin SP 3..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger font-weight-bold" id="btnSubmitJadwalHrdSp3">
                        <i class="fas fa-calendar-check mr-1"></i> Simpan Jadwal & Kirim Notifikasi WA
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var $ = window.jQuery;
    if (!$) return;

    var baseUrl = '<?= base_url() ?>';
    var csrfName = '<?= $this->security->get_csrf_token_name() ?>';

    function getCsrfHash() {
        return $('input[name="token"]').val() || '<?= $this->security->get_csrf_hash() ?>';
    }

    // Scrollbar horizontal atas + sticky viewport agar tidak perlu scroll jauh ke bawah
    function setupTableHorizontalUX(selector) {
        var $table = $(selector);
        if (!$table.length || $table.closest('.tp-dt-scroll').length) return;

        var $resp = $table.closest('.table-responsive');
        if ($resp.length) {
            $resp.addClass('tp-has-dt-scroll');
        }

        $table.wrap('<div class="tp-dt-scroll"></div>');
        var $scrollBox = $table.closest('.tp-dt-scroll');
        var $syncWrap = $(
            '<div class="tp-dt-sync-wrap">' +
                '<span class="tp-dt-sync-hint"><i class="fas fa-arrows-alt-h mr-1"></i>Geser Tabel</span>' +
                '<div class="tp-dt-sync-top"><div class="tp-dt-sync-spacer"></div></div>' +
                '<div class="tp-dt-sync-btns">' +
                    '<button type="button" class="tp-scroll-step" data-dir="-1" title="Geser Kiri"><i class="fas fa-chevron-left"></i></button>' +
                    '<button type="button" class="tp-scroll-step" data-dir="1" title="Geser Kanan"><i class="fas fa-chevron-right"></i></button>' +
                '</div>' +
            '</div>'
        );
        $syncWrap.insertBefore($scrollBox);

        var $topScroll = $syncWrap.find('.tp-dt-sync-top');
        var $spacer = $syncWrap.find('.tp-dt-sync-spacer');
        var syncing = false;

        function syncMetrics() {
            var tblW = $table[0].scrollWidth || 0;
            var boxW = $scrollBox[0].clientWidth || 0;
            if (tblW > boxW + 4) {
                $syncWrap.addClass('is-scrollable');
                $spacer.width(tblW);
                $topScroll.scrollLeft($scrollBox.scrollLeft());
            } else {
                $syncWrap.removeClass('is-scrollable');
            }
        }

        $topScroll.on('scroll', function() {
            if (syncing) return;
            syncing = true;
            $scrollBox.scrollLeft($topScroll.scrollLeft());
            syncing = false;
        });

        $scrollBox.on('scroll', function() {
            if (syncing) return;
            syncing = true;
            $topScroll.scrollLeft($scrollBox.scrollLeft());
            syncing = false;
        });

        $scrollBox.on('wheel', function(e) {
            if (e.originalEvent && e.originalEvent.shiftKey && $table[0].scrollWidth > $scrollBox[0].clientWidth) {
                var delta = e.originalEvent.deltaY || e.originalEvent.deltaX;
                if (delta) {
                    e.preventDefault();
                    $scrollBox.scrollLeft($scrollBox.scrollLeft() + delta);
                }
            }
        });

        $syncWrap.on('click', '.tp-scroll-step', function() {
            var dir = parseInt($(this).attr('data-dir'), 10) || 1;
            var step = Math.max(220, Math.round($scrollBox.width() * 0.45));
            $scrollBox.stop().animate({ scrollLeft: $scrollBox.scrollLeft() + (dir * step) }, 220);
        });

        $table.on('draw.dt', function() {
            setTimeout(syncMetrics, 20);
        });
        $(window).on('resize', syncMetrics);
        $('.nav-tabs-tp a[data-toggle="tab"]').on('shown.bs.tab', function() {
            setTimeout(syncMetrics, 30);
        });

        setTimeout(syncMetrics, 40);
    }

    // Inisialisasi DataTables
    if ($.fn.DataTable) {
        $('#datatable_tugas_saya').DataTable({ pageLength: 10, order: [], autoWidth: false });
        $('#datatable_tugas_atasan').DataTable({ pageLength: 10, order: [], autoWidth: false });
        $('#datatable_leaderboard').DataTable({ pageLength: 15, order: [], autoWidth: false });
        setupTableHorizontalUX('#datatable_tugas_saya');
        setupTableHorizontalUX('#datatable_tugas_atasan');
        setupTableHorizontalUX('#datatable_leaderboard');
    }

    // Animasi confetti podium ringan saat tab Leaderboard KPI dibuka
    var confettiRafId = null;
    function launchLeaderboardConfetti() {
        var canvas = document.getElementById('tpLbConfettiCanvas');
        var tabEl = document.getElementById('tab_leaderboard');
        if (!canvas || !tabEl || !canvas.getContext) return;
        if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

        if (confettiRafId) {
            window.cancelAnimationFrame(confettiRafId);
            confettiRafId = null;
        }
        canvas.width = tabEl.clientWidth || 800;
        canvas.height = 440;
        var ctx = canvas.getContext('2d');
        var colors = ['#facc15', '#38bdf8', '#4ade80', '#f472b6', '#a78bfa', '#fb923c'];
        var pieces = [];
        for (var i = 0; i < 64; i++) {
            pieces.push({
                x: (canvas.width * 0.15) + Math.random() * (canvas.width * 0.7),
                y: -15 - Math.random() * 80,
                w: 5 + Math.random() * 6,
                h: 3 + Math.random() * 5,
                vx: (Math.random() - 0.5) * 3.4,
                vy: 1.8 + Math.random() * 3.2,
                rot: Math.random() * Math.PI * 2,
                vRot: (Math.random() - 0.5) * 0.18,
                color: colors[i % colors.length],
                alpha: 0.95
            });
        }
        var startTime = null;
        function drawConfetti(ts) {
            if (!startTime) startTime = ts;
            var elapsed = ts - startTime;
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            var alive = false;
            for (var j = 0; j < pieces.length; j++) {
                var p = pieces[j];
                p.x += p.vx + Math.sin(elapsed * 0.004 + j) * 0.45;
                p.y += p.vy;
                p.vy += 0.025;
                p.rot += p.vRot;
                if (elapsed > 1700) {
                    p.alpha = Math.max(0, p.alpha - 0.022);
                }
                if (p.alpha > 0.02 && p.y < canvas.height + 20) {
                    alive = true;
                    ctx.save();
                    ctx.globalAlpha = p.alpha;
                    ctx.translate(p.x, p.y);
                    ctx.rotate(p.rot);
                    ctx.fillStyle = p.color;
                    ctx.fillRect(-p.w / 2, -p.h / 2, p.w, p.h);
                    ctx.restore();
                }
            }
            if (alive && elapsed < 3200) {
                confettiRafId = window.requestAnimationFrame(drawConfetti);
            } else {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                confettiRafId = null;
            }
        }
        confettiRafId = window.requestAnimationFrame(drawConfetti);
    }

    // Animasi & count-up angka saat tab Leaderboard KPI dibuka
    var lbAnimTimer = null;
    function playLeaderboardAnimations() {
        var $tabLb = $('#tab_leaderboard');
        if (!$tabLb.length) return;
        $tabLb.removeClass('tp-lb-play');
        void $tabLb[0].offsetWidth; // paksa reflow agar animasi CSS ulang mulus
        $tabLb.addClass('tp-lb-play');

        $tabLb.find('#datatable_leaderboard tbody tr').each(function(idx) {
            $(this).css('animation-delay', Math.min(idx * 0.03, 0.45) + 's');
        });

        launchLeaderboardConfetti();

        if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            return;
        }
        $tabLb.find('.tp-lb-countup').each(function() {
            var el = this;
            var target = parseFloat(el.getAttribute('data-score'));
            if (isNaN(target)) return;
            var startTs = null;
            var duration = 680;
            function step(ts) {
                if (!startTs) startTs = ts;
                var p = Math.min(1, (ts - startTs) / duration);
                var eased = 1 - Math.pow(1 - p, 3);
                el.textContent = (target * eased).toFixed(1);
                if (p < 1) {
                    window.requestAnimationFrame(step);
                } else {
                    el.textContent = target.toFixed(1);
                }
            }
            window.requestAnimationFrame(step);
        });
    }

    $('a[href="#tab_leaderboard"]').on('shown.bs.tab click', function() {
        clearTimeout(lbAnimTimer);
        lbAnimTimer = setTimeout(function() {
            if ($('#tab_leaderboard').hasClass('active')) {
                playLeaderboardAnimations();
            }
        }, 45);
    });
    if ($('#tab_leaderboard').hasClass('active')) {
        playLeaderboardAnimations();
    }

    // Inisialisasi Select2 pada pilihan karyawan
    if ($.fn.select2) {
        $('#select_penerima_task').select2({
            dropdownParent: $('#modalBuatTask'),
            placeholder: 'Pilih karyawan penerima tugas (bisa lebih dari 1)...',
            allowClear: true,
            width: '100%'
        });
    }

    // Tombol Pilih Semua Karyawan yang Dibawahi Langsung
    $('#btnSelectSupervisedEmployees').on('click', function() {
        var allIds = [];
        $('#select_penerima_task option').each(function() {
            allIds.push($(this).val());
        });
        $('#select_penerima_task').val(allIds).trigger('change');
    });

    function escapeHtml(text) {
        if (!text) return '';
        return String(text)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    // Toggle Menu Dropdown Jobdesk
    $('#btn_toggle_jobdesk').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $('#menu_jobdesk_list').toggle();
        if ($('#menu_jobdesk_list').is(':visible')) {
            $('#filter_jobdesk_search').val('').trigger('input').focus();
        }
    });

    // Cegah dropdown tertutup saat mengklik/mengetik di search box
    $('#filter_jobdesk_search').on('click keydown keyup input', function(e) {
        e.stopPropagation();
        var q = $(this).val().toLowerCase().trim();
        $('#container_jobdesk_items .jobdesk-choice-item').each(function() {
            var text = $(this).text().toLowerCase();
            $(this).toggle(text.indexOf(q) !== -1);
        });
        $('#container_jobdesk_items .jobdesk-group-header').each(function() {
            var $groupItems = $(this).nextUntil('.jobdesk-group-header', '.jobdesk-choice-item:visible');
            $(this).toggle($groupItems.length > 0);
        });
    });

    // Tutup dropdown saat klik di luar
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#dropdown_jobdesk_container').length) {
            $('#menu_jobdesk_list').hide();
        }
    });

    // Saat salah satu opsi jobdesk diklik
    $(document).on('click', '.jobdesk-choice-item', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        var nama = $(this).data('nama');
        var detail = $(this).data('detail') || '';
        var sasaran = $(this).data('sasaran') || '';

        $('#select_jobdesk_karyawan').val(id);
        $('#input_nama_jobdesk').val(nama);

        $('#label_selected_jobdesk')
            .html('<strong class="text-success mr-1"><i class="fas fa-check-circle"></i> Terpilih:</strong> ' + escapeHtml(nama))
            .css('color', '#0f172a');

        if (nama) {
            $('#text_jobdesk_title').text(nama);
            var detailText = '';
            if (detail) detailText += '• Detail Proyek: ' + detail;
            if (sasaran) detailText += (detailText ? '\n' : '') + '• Sasaran Mutu: ' + sasaran;
            $('#text_jobdesk_detail').text(detailText || '(Tidak ada sasaran mutu khusus)');
            $('#jobdesk_detail_preview').slideDown(150);
        } else {
            $('#jobdesk_detail_preview').hide();
        }

        $('#menu_jobdesk_list').hide();
    });

    // Handle Dropdown Jobdesk Sasaran Mutu berdasarkan penerima tugas
    $('#select_penerima_task').on('change', function() {
        var selected = $(this).val() || [];
        if (selected.length === 1) {
            var empId = selected[0];
            $('#container_jobdesk_karyawan').slideDown(200);
            loadJobdeskKaryawan(empId);
        } else {
            $('#container_jobdesk_karyawan').slideUp(200);
            $('#select_jobdesk_karyawan').val('');
            $('#input_nama_jobdesk').val('');
            $('#label_selected_jobdesk').text('-- Pilih Jobdesk Sasaran Mutu Terkait (Wajib) --').css('color', '#64748b');
            $('#jobdesk_detail_preview').hide();
            $('#menu_jobdesk_list').hide();
        }
    });

    function loadJobdeskKaryawan(empId) {
        $('#jobdesk_loading').show();
        $('#container_jobdesk_items').empty();
        $('#jobdesk_detail_preview').hide();
        $('#select_jobdesk_karyawan').val('');
        $('#input_nama_jobdesk').val('');
        $('#label_selected_jobdesk').text('-- Memuat daftar jobdesk... --').css('color', '#94a3b8');

        $.ajax({
            url: baseUrl + 'task_proyek/get_karyawan_jobdesks',
            type: 'GET',
            data: { id_pengguna: empId },
            dataType: 'json',
            success: function(res) {
                $('#jobdesk_loading').hide();

                try {
                    if (res && res.status === 'success') {
                        if (res.karyawan) {
                            var kInfo = res.karyawan.nama + ' (' + (res.karyawan.posisi_matched || res.karyawan.jabatan || res.karyawan.divisi) + ')';
                            $('#nama_karyawan_terpilih').text(kInfo);
                        }

                        var itemsHtml = '';
                        if (res.jobdesks && res.jobdesks.length > 0) {
                            var currentGroup = '';
                            $.each(res.jobdesks, function(i, it) {
                                var groupTitle = it.posisi ? it.posisi : (it.divisi ? 'Divisi ' + it.divisi : 'Jobdesk Karyawan');
                                if (groupTitle !== currentGroup) {
                                    currentGroup = groupTitle;
                                    itemsHtml += '<div class="jobdesk-group-header"><i class="fas fa-briefcase mr-1"></i> Jobdesk Resmi: ' + escapeHtml(currentGroup) + '</div>';
                                }
                                itemsHtml += '<div class="jobdesk-choice-item" data-id="' + escapeHtml(it.id) + '" data-nama="' + escapeHtml(it.jobdesk) + '" data-detail="' + escapeHtml(it.detail_proyek || '') + '" data-sasaran="' + escapeHtml(it.sasaran_mutu || '') + '">' +
                                    '<strong class="text-success mr-1">' + (i + 1) + '.</strong> ' + escapeHtml(it.jobdesk) +
                                    '</div>';
                            });
                        } else {
                            itemsHtml += '<div class="jobdesk-choice-item" data-id="SM_CUSTOM_TASK" data-nama="Tugas & Tanggung Jawab Operasional Tambahan">' +
                                '<strong class="text-success mr-1">1.</strong> Tugas & Tanggung Jawab Operasional Tambahan' +
                                '</div>';
                        }
                        $('#container_jobdesk_items').html(itemsHtml);
                        $('#label_selected_jobdesk').text('-- Klik untuk Memilih Jobdesk Terkait (Wajib) --').css('color', '#64748b');
                    } else {
                        $('#container_jobdesk_items').html('<div class="p-3 text-muted small text-center">Tidak ada jobdesk khusus untuk posisi ini.</div>');
                        $('#label_selected_jobdesk').text('-- Tidak ada jobdesk --').css('color', '#ef4444');
                    }
                } catch (renderErr) {
                    console.error('Error rendering jobdesks:', renderErr);
                    $('#container_jobdesk_items').html('<div class="jobdesk-choice-item" data-id="SM_CUSTOM_TASK" data-nama="Tugas Operasional Karyawan"><strong class="text-success mr-1">1.</strong> Tugas Operasional Karyawan</div>');
                    $('#label_selected_jobdesk').text('-- Klik untuk Memilih Jobdesk Terkait --').css('color', '#64748b');
                }
            },
            error: function() {
                $('#jobdesk_loading').hide();
                $('#container_jobdesk_items').html('<div class="jobdesk-choice-item" data-id="SM_CUSTOM_TASK" data-nama="Tugas Operasional Karyawan"><strong class="text-success mr-1">1.</strong> Tugas Operasional Karyawan</div>');
                $('#label_selected_jobdesk').text('-- Klik untuk Memilih Jobdesk Terkait --').css('color', '#64748b');
            }
        });
    }



    // Simpan Atasan Langsung Saya dari halaman Task Proyek
    $('#formAturAtasanSaya').on('submit', function(e) {
        e.preventDefault();
        var $btn = $('#btnSimpanAtasanSaya');
        var orig = $btn.html();
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');
        $.ajax({
            url: baseUrl + 'pengguna/update/atasan',
            type: 'POST',
            dataType: 'json',
            data: {
                pengguna_id: '<?= (int)$myId ?>',
                id_atasan: $('#select_atasan_saya_tp').val(),
                [csrfName]: getCsrfHash()
            },
            success: function(res) {
                $btn.prop('disabled', false).html(orig);
                if (res && res.status === 'success') {
                    window.location.reload();
                } else {
                    alert((res && res.msg) ? res.msg : 'Gagal menyimpan atasan.');
                }
            },
            error: function() {
                $btn.prop('disabled', false).html(orig);
                alert('Terjadi kesalahan jaringan.');
            }
        });
    });

    // Buka Modal Buat Task
    $('#btnOpenModalBuatTask').on('click', function() {
        <?php if (!$canAssignToAll && empty($list_bawahan)): ?>
            if (window.swal) {
                swal('Perhatian', 'Anda belum memiliki karyawan yang dibawahi langsung, sehingga belum dapat memberikan tugas. Karyawan harus memilih Anda sebagai Atasan Langsung terlebih dahulu.', 'warning');
            } else {
                alert('Anda belum memiliki karyawan yang dibawahi langsung, sehingga belum dapat memberikan tugas. Karyawan harus memilih Anda sebagai Atasan Langsung terlebih dahulu.');
            }
            return;
        <?php endif; ?>
        $('#formBuatTask')[0].reset();
        $('#container_jobdesk_karyawan').hide();
        $('#select_jobdesk_karyawan').val('');
        $('#input_nama_jobdesk').val('');
        $('#label_selected_jobdesk').text('-- Pilih Jobdesk Sasaran Mutu Terkait (Wajib) --').css('color', '#64748b');
        $('#jobdesk_detail_preview').hide();
        $('#menu_jobdesk_list').hide();
        if ($.fn.select2) {
            $('#select_penerima_task').val(null).trigger('change');
        }
        $('#select_mengetahui_task').val('');
        $('#modalBuatTask').modal('show');
    });

    // Dynamic Checklist Builder
    $('#btnAddChecklistRow').on('click', function() {
        var count = $('#checklistBuilderContainer .checklist-input-row').length + 1;
        var html = '<div class="input-group input-group-sm mb-1 checklist-input-row">' +
            '<input type="text" name="checklist_items[]" class="form-control" placeholder="Item checklist ke-' + count + '...">' +
            '<div class="input-group-append">' +
            '<button type="button" class="btn btn-outline-danger btn-remove-checklist"><i class="fas fa-times"></i></button>' +
            '</div></div>';
        $('#checklistBuilderContainer').append(html);
    });

    $(document).on('click', '.btn-remove-checklist', function() {
        if ($('#checklistBuilderContainer .checklist-input-row').length > 1) {
            $(this).closest('.checklist-input-row').remove();
        } else {
            $(this).closest('.checklist-input-row').find('input').val('');
        }
    });

    // Submit Form Buat Task Baru
    $('#formBuatTask').on('submit', function(e) {
        e.preventDefault();

        var selectedPenerima = $('#select_penerima_task').val() || [];
        if (selectedPenerima.length === 1 && !$('#select_jobdesk_karyawan').val()) {
            if (window.swal) {
                swal('Perhatian', 'Jobdesk Sasaran Mutu wajib dipilih untuk penugasan karyawan ini!', 'warning');
            } else {
                alert('Jobdesk Sasaran Mutu wajib dipilih untuk penugasan karyawan ini!');
            }
            $('#menu_jobdesk_list').show();
            $('#filter_jobdesk_search').focus();
            return false;
        }

        var $btn = $('#btnSubmitBuatTask');
        var origHtml = $btn.html();
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

        var formData = $(this).serializeArray();
        formData.push({ name: csrfName, value: getCsrfHash() });

        $.ajax({
            url: baseUrl + 'task_proyek/add',
            type: 'POST',
            data: $.param(formData),
            dataType: 'json',
            success: function(res) {
                $btn.prop('disabled', false).html(origHtml);
                if (res && res.status === 'success') {
                    $('#modalBuatTask').modal('hide');
                    var nextUrl = baseUrl + 'task_proyek?tab=atasan<?= $switch === "kepegawaian" ? "&nav=kepegawaian" : "" ?>';
                    if (window.swal) {
                        swal('Berhasil!', res.msg, 'success').then(function() { window.location.href = nextUrl; });
                    } else {
                        alert(res.msg);
                        window.location.href = nextUrl;
                    }
                } else {
                    alert((res && res.msg) ? res.msg : 'Gagal membuat task.');
                }
            },
            error: function() {
                $btn.prop('disabled', false).html(origHtml);
                alert('Terjadi kesalahan jaringan saat menyimpan task.');
            }
        });
    });

    // Helper render bintang di JS
    function buildStarsJs(rating) {
        var r = Math.round(parseFloat(rating) || 0);
        var out = '<span class="tp-star-display">';
        for (var i = 1; i <= 5; i++) {
            out += (i <= r) ? '<i class="fas fa-star"></i>' : '<i class="far fa-star tp-star-empty"></i>';
        }
        out += '</span> <strong>(' + r + '/5)</strong>';
        return out;
    }

    // Buka Modal Detail Task
    $(document).on('click', '.btn-detail-task', function() {
        var idTask = $(this).data('id');
        var focusTarget = $(this).data('focus') || 'progres';

        $.ajax({
            url: baseUrl + 'task_proyek/get_detail',
            type: 'POST',
            data: { id_task: idTask, [csrfName]: getCsrfHash() },
            dataType: 'json',
            success: function(res) {
                if (!res || res.status !== 'success') {
                    alert(res && res.msg ? res.msg : 'Gagal memuat detail task.');
                    return;
                }
                var t = res.task;
                var myId = parseInt(res.my_id, 10);
                var isPenerima = (parseInt(t.id_penerima, 10) === myId);

                // ATURAN MUTLAK: Pelaksana (penerima tugas) TIDAK BOLEH menilai tugas diri sendiri!
                // canReview hanya bernilai true jika user BUKAN penerima dan backend memberikan izin review
                var canReview = !isPenerima && (res.can_review !== undefined ? !!res.can_review : ((parseInt(t.id_pemberi, 10) === myId) || !!res.is_admin_reviewer));

                var stInt = parseInt(t.status_task, 10) || 0;
                if (stInt === 1 && (parseInt(t.progres, 10) >= 100 || !!t.tgl_submit)) {
                    stInt = 2;
                }
                var statusBadgeHtml = '';
                if (stInt === 5) {
                    statusBadgeHtml = '<span class="tp-status-badge tp-st-5" style="background:#fef3c7; color:#92400e; border:1px solid #fde68a;"><i class="fas fa-hourglass-start"></i> Menunggu ACC HRD</span>';
                } else if (stInt === 6) {
                    statusBadgeHtml = '<span class="tp-status-badge tp-st-6" style="background:#fee2e2; color:#991b1b; border:1px solid #fecaca;"><i class="fas fa-times-circle"></i> Ditolak HRD</span>';
                } else if (stInt === 4) {
                    statusBadgeHtml = '<span class="tp-status-badge tp-st-4"><i class="fas fa-check-circle"></i> Selesai & Dinilai</span>';
                } else if (stInt === 2) {
                    statusBadgeHtml = '<span class="tp-status-badge tp-st-2"><i class="fas fa-hourglass-half"></i> Menunggu Penilaian</span>';
                } else if (stInt === 3) {
                    statusBadgeHtml = '<span class="tp-status-badge tp-st-3"><i class="fas fa-redo"></i> Perlu Revisi</span>';
                } else if (stInt === 1) {
                    statusBadgeHtml = '<span class="tp-status-badge tp-st-1"><i class="fas fa-spinner fa-spin"></i> Sedang Dikerjakan</span>';
                } else {
                    statusBadgeHtml = '<span class="tp-status-badge tp-st-0"><i class="far fa-clock"></i> Baru / Belum Mulai</span>';
                }
                $('#dt_status_badge').html(statusBadgeHtml);

                $('#dt_mengetahui').text(t.nama_mengetahui ? t.nama_mengetahui : '-');
                var accHrdText = '-';
                if (parseInt(t.status_task, 10) === 5) {
                    accHrdText = '<span class="badge badge-warning text-dark"><i class="fas fa-hourglass-start mr-1"></i> Menunggu ACC HRD</span>';
                } else if (parseInt(t.status_task, 10) === 6) {
                    accHrdText = '<span class="badge badge-danger"><i class="fas fa-times-circle mr-1"></i> Ditolak: ' + $('<div>').text(t.acc_hrd_catatan || '').html() + '</span>';
                } else if (t.nama_acc_hrd) {
                    accHrdText = '<span class="text-success"><i class="fas fa-check-circle mr-1"></i> Disetujui (' + $('<div>').text(t.nama_acc_hrd).html() + (t.acc_hrd_at_fmt ? ', ' + t.acc_hrd_at_fmt : '') + ')</span>';
                }
                $('#dt_acc_hrd_info').html(accHrdText);

                if (parseInt(t.status_task, 10) === 5) {
                    $('#dt_hrd_acc_box').show();
                    if (res.is_hrd_admin) {
                        $('#btn_modal_acc_hrd').data('id', t.id_task).data('kode', t.kode_task).data('judul', t.judul);
                        $('#btn_modal_tolak_hrd').data('id', t.id_task).data('kode', t.kode_task).data('judul', t.judul);
                        $('#dt_hrd_acc_actions').css('display', 'flex');
                    } else {
                        $('#dt_hrd_acc_actions').hide();
                    }
                } else {
                    $('#dt_hrd_acc_box').hide();
                }

                $('#dt_kode_task').text(t.kode_task);
                $('#dt_nama_proyek').text(t.nama_proyek || 'Umum');
                $('#dt_jenis').text(t.jenis || 'Task Harian');
                $('#dt_prioritas_badge').html('<span class="tp-priority-badge tp-prio-' + t.prioritas + '">' + t.prioritas + '</span>');
                if (t.nama_jobdesk) {
                    $('#dt_jobdesk_badge').html('<div class="d-flex align-items-start mt-2 px-2 py-1 rounded" style="background:#dcfce7; border:1px solid #86efac; color:#166534; font-size:12px; font-weight:600; line-height:1.45; white-space:normal; word-break:break-word; max-width:100%;"><i class="fas fa-bullseye mr-2 mt-1 flex-shrink-0"></i><span><strong>Jobdesk:</strong> ' + $('<div>').text(t.nama_jobdesk).html() + '</span></div>').show();
                } else {
                    $('#dt_jobdesk_badge').hide().empty();
                }
                $('#dt_judul').text(t.judul);
                $('#dt_deadline').text(t.tgl_deadline_fmt + (t.is_overdue ? ' (OVERDUE)' : ''));
                $('#dt_pemberi').text((t.nama_pemberi || '-') + (t.jabatan_pemberi ? ' (' + t.jabatan_pemberi + ')' : ''));
                $('#dt_penerima').text((t.nama_penerima || '-') + (t.jabatan_penerima ? ' (' + t.jabatan_penerima + ')' : ''));
                $('#dt_deskripsi').text(t.deskripsi || 'Tidak ada deskripsi tambahan.');

                if (t.lampiran_atasan) {
                    $('#dt_lampiran_atasan_link').attr('href', t.lampiran_atasan);
                    $('#dt_lampiran_atasan_wrap').show();
                } else {
                    $('#dt_lampiran_atasan_wrap').hide();
                }

                if (parseInt(t.status_task, 10) === 3 && t.catatan_revisi) {
                    $('#dt_catatan_revisi').text(t.catatan_revisi);
                    $('#dt_revisi_wrap').show();
                } else {
                    $('#dt_revisi_wrap').hide();
                }

                // Tampilkan info keterlambatan, SP 1/2/3 & jadwal pertemuan HRD
                var spLevel = parseInt(t.sp_level, 10) || 0;
                var hariTelat = parseInt(t.hari_telat, 10) || 0;
                var isLate = !!t.is_late || (parseInt(t.max_bintang, 10) === 1);
                if (isLate || spLevel >= 1) {
                    var spHtml = '<div class="alert ' + (spLevel >= 1 ? 'alert-danger' : 'alert-warning') + ' py-2 px-3 mb-0 small">';
                    spHtml += '<div class="d-flex justify-content-between align-items-center flex-wrap" style="gap:8px;">';
                    spHtml += '<div>';
                    if (spLevel >= 1) {
                        spHtml += '<span class="badge badge-danger mr-1" style="font-size:11.5px;"><i class="fas fa-gavel mr-1"></i> STATUS SANKSI: ' + (t.sp_label || ('SP ' + spLevel)) + '</span> ';
                    }
                    spHtml += '<strong>Terlambat H+' + hariTelat + ' hari dari deadline (' + (t.tgl_deadline_fmt || '-') + ').</strong> ';
                    spHtml += 'Maksimal nilai bintang terkunci pada <strong>⭐ 1/5</strong>.';
                    if (t.sp_desc) {
                        spHtml += '<div class="mt-1">' + $('<div>').text(t.sp_desc).html() + '</div>';
                    }
                    if (t.hrd_jadwal_tgl) {
                        spHtml += '<div class="mt-1 p-1 px-2 rounded bg-white text-dark border border-danger">' +
                            '<strong><i class="fas fa-handshake text-danger mr-1"></i> Jadwal Pertemuan HRD:</strong> ' +
                            (t.hrd_jadwal_tgl_fmt || t.hrd_jadwal_tgl) + ' pukul ' + (t.hrd_jadwal_jam || '10:00') + ' WIB — ' +
                            $('<div>').text((t.hrd_jadwal_metode || 'Tatap Muka') + ' (' + (t.hrd_jadwal_lokasi || 'Ruang HRD') + ')').html() +
                            (t.hrd_jadwal_catatan ? '<br><em>Catatan: ' + $('<div>').text(t.hrd_jadwal_catatan).html() + '</em>' : '') +
                            '</div>';
                    }
                    spHtml += '</div>';
                    if (spLevel >= 3) {
                        spHtml += '<div>' +
                            '<button type="button" class="btn btn-xs btn-danger font-weight-bold btn-jadwal-hrd-sp3" ' +
                            'data-id="' + t.id_task + '" ' +
                            'data-kode="' + $('<div>').text(t.kode_task || '').html() + '" ' +
                            'data-judul="' + $('<div>').text(t.judul || '').html() + '" ' +
                            'data-karyawan="' + $('<div>').text(t.nama_penerima || '').html() + '" ' +
                            'data-telat="' + hariTelat + '" ' +
                            'data-tgl="' + (t.hrd_jadwal_tgl || '') + '" ' +
                            'data-jam="' + (t.hrd_jadwal_jam || '10:00') + '" ' +
                            'data-metode="' + $('<div>').text(t.hrd_jadwal_metode || 'Tatap Muka (Offline)').html() + '" ' +
                            'data-lokasi="' + $('<div>').text(t.hrd_jadwal_lokasi || 'Ruang HRD').html() + '" ' +
                            'data-catatan="' + $('<div>').text(t.hrd_jadwal_catatan || '').html() + '">' +
                            '<i class="fas fa-calendar-check mr-1"></i> ' + (t.hrd_jadwal_tgl ? 'Ubah Jadwal HRD' : 'Jadwalkan Pertemuan HRD') +
                            '</button>' +
                            '</div>';
                    }
                    spHtml += '</div></div>';
                    $('#dt_sp_alert_wrap').html(spHtml).show();
                } else {
                    $('#dt_sp_alert_wrap').hide().empty();
                }

                // Tampilkan kotak hasil rating jika sudah dinilai
                if (parseInt(t.status_task, 10) === 4 && t.rating_bintang) {
                    $('#dt_rating_stars_html').html(buildStarsJs(t.rating_bintang));
                    $('#dt_ulasan_atasan_text').text('"' + (t.ulasan_atasan || 'Pekerjaan selesai dengan baik.') + '"');
                    $('#dt_tgl_dinilai').text('Dinilai pada: ' + (t.tgl_dinilai || '-'));
                    $('#dt_hasil_rating_box').show();
                } else {
                    $('#dt_hasil_rating_box').hide();
                }

                // Isi form update progres & checklist
                $('#up_id_task').val(t.id_task);
                $('#sub_id_task').val(t.id_task);
                $('#rev_id_task').val(t.id_task);

                $('#up_progres_slider').val(t.progres || 0);
                $('#up_progres_label').text(t.progres || 0);
                $('#up_catatan').val('');
                $('#up_auto_calc').val('0');

                $('#sub_catatan_bawahan').val(t.catatan_bawahan || '');
                $('#sub_bukti_selesai').val(t.bukti_selesai || '');

                var isPenerima = (parseInt(t.id_penerima, 10) === myId);
                var canEditChecklist = isPenerima && stInt < 4 && stInt !== 2 && stInt !== 5 && stInt !== 6;

                // Render Checklist untuk Penerima Task (Bisa tambah, edit, hapus & centang)
                var chkHtml = '';
                if (canEditChecklist) {
                    if (t.checklist && t.checklist.length > 0) {
                        for (var i = 0; i < t.checklist.length; i++) {
                            var item = t.checklist[i];
                            var isChecked = parseInt(item.done, 10) === 1;
                            chkHtml += buildEditableChecklistRowHtml(item.item || '', isChecked, i + 1);
                        }
                    } else {
                        chkHtml += buildEditableChecklistRowHtml('', false, 1);
                    }
                    $('#dt_checklist_container').html(chkHtml);
                    $('#dt_readonly_checklist_wrap').hide().empty();
                } else {
                    $('#dt_checklist_container').empty();
                    if (t.checklist && t.checklist.length > 0) {
                        var doneCntRo = 0;
                        var roHtml = '<div class="small font-weight-bold text-primary mb-1"><i class="fas fa-tasks mr-1"></i> Checklist Langkah Kerja Karyawan:</div>';
                        for (var k = 0; k < t.checklist.length; k++) {
                            var roItem = t.checklist[k];
                            var roDone = parseInt(roItem.done, 10) === 1;
                            if (roDone) doneCntRo++;
                            roHtml += '<div class="tp-checklist-row ' + (roDone ? 'done' : '') + ' mb-1">' +
                                '<i class="fas ' + (roDone ? 'fa-check-square text-success' : 'fa-square text-muted') + ' mr-1"></i>' +
                                '<span>' + $('<div>').text(roItem.item || '').html() + '</span>' +
                                '</div>';
                        }
                        roHtml = '<div class="d-flex justify-content-between align-items-center mb-1">' +
                            '<span class="small font-weight-bold text-primary"><i class="fas fa-tasks mr-1"></i> Checklist Langkah Kerja Karyawan</span>' +
                            '<span class="badge badge-info">' + doneCntRo + ' / ' + t.checklist.length + ' Selesai</span>' +
                            '</div>' + roHtml.replace('<div class="small font-weight-bold text-primary mb-1"><i class="fas fa-tasks mr-1"></i> Checklist Langkah Kerja Karyawan:</div>', '');
                        $('#dt_readonly_checklist_wrap').html(roHtml).show();
                    } else {
                        $('#dt_readonly_checklist_wrap').hide().empty();
                    }
                }

                // Tampilkan / sembunyikan panel sesuai status & role
                // Form Update Progres & Submit Selesai HANYA untuk Karyawan Penerima Tugas (Pelaksana) jika belum disubmit / belum tuntas
                if (canEditChecklist) {
                    $('#panelBawahanProgres').show();
                    $('#panelBawahanSubmit').show();
                    $('#panelSudahDisubmitInfo').hide();
                } else {
                    $('#panelBawahanProgres').hide();
                    $('#panelBawahanSubmit').hide();
                    if (isPenerima && stInt === 2) {
                        $('#panelSudahDisubmitInfo').show();
                    } else {
                        $('#panelSudahDisubmitInfo').hide();
                    }
                }

                // Panel Review / Beri Rating Bintang Atasan HANYA jika canReview dan statusnya Menunggu Penilaian (2) serta BELUM pernah diberi bintang
                var isAlreadyRated = (stInt === 4 && parseInt(t.rating_bintang, 10) > 0);
                var allowedMaxStar = 5;
                if (isLate) {
                    if (hariTelat === 1) {
                        allowedMaxStar = 4;
                    } else {
                        allowedMaxStar = 1;
                    }
                }
                if (parseInt(t.sp_level, 10) > 0) {
                    allowedMaxStar = 1;
                }
                window.currentTaskMaxStar = allowedMaxStar;

                if (isLate) {
                    $('#lblOverdueDays').text(hariTelat);
                    $('#lblMaxAllowedStars').text(allowedMaxStar);
                    $('#lblMaxAllowedStars2').text(allowedMaxStar);
                    $('#wrapOverdueMaxStarAlert').show();
                    $('#starPickerWidget .star-btn').each(function() {
                        var sVal = parseInt($(this).data('val'), 10);
                        if (sVal > allowedMaxStar) {
                            $(this).css({ opacity: 0.25, cursor: 'not-allowed' }).attr('title', 'Terkunci: Maksimal Bintang ' + allowedMaxStar + ' karena keterlambatan deadline');
                        } else {
                            $(this).css({ opacity: 1, cursor: 'pointer' }).removeAttr('title');
                        }
                    });
                } else {
                    $('#wrapOverdueMaxStarAlert').hide();
                    $('#starPickerWidget .star-btn').css({ opacity: 1, cursor: 'pointer' }).removeAttr('title');
                }

                if (canReview && stInt === 2 && !isAlreadyRated) {
                    $('#panelAtasanReview').show();
                    var initStar = allowedMaxStar;
                    setStarPickerValue(initStar);
                    $('#rev_ulasan_atasan').val('');
                } else {
                    $('#panelAtasanReview').hide();
                }

                // Render Riwayat Log
                var logsHtml = '';
                if (res.logs && res.logs.length > 0) {
                    for (var j = 0; j < res.logs.length; j++) {
                        var lg = res.logs[j];
                        logsHtml += '<div class="tp-log-item">' +
                            '<div class="d-flex justify-content-between small">' +
                            '<strong>' + $('<div>').text(lg.nama_pengguna || 'User').html() + ' — <span class="text-primary">' + $('<div>').text(lg.aksi).html() + '</span></strong>' +
                            '<span class="text-muted">' + (lg.created_at || '') + '</span>' +
                            '</div>' +
                            '<div class="small mt-1">' + $('<div>').text(lg.catatan || '').html() + '</div>' +
                            (lg.lampiran ? '<div class="small mt-1"><a href="' + $('<div>').text(lg.lampiran).html() + '" target="_blank"><i class="fas fa-link"></i> Lampiran Bukti</a></div>' : '') +
                            '</div>';
                    }
                } else {
                    logsHtml = '<div class="text-muted small">Belum ada riwayat aktivitas.</div>';
                }
                $('#dt_logs_container').html(logsHtml);

                $('#modalDetailTask').modal('show');

                setTimeout(function() {
                    if (focusTarget === 'submit' && $('#panelBawahanSubmit').is(':visible')) {
                        $('#sub_catatan_bawahan').trigger('focus');
                    } else if (focusTarget === 'review' && $('#panelAtasanReview').is(':visible')) {
                        $('#rev_ulasan_atasan').trigger('focus');
                    }
                }, 400);
            }
        });
    });

    // Helper pembuat baris checklist interaktif milik Penerima Task
    function buildEditableChecklistRowHtml(textVal, isChecked, num) {
        var safeVal = $('<div>').text(textVal || '').html().replace(/"/g, '&quot;');
        return '<div class="input-group input-group-sm mb-2 tp-checklist-edit-row">' +
            '<div class="input-group-prepend">' +
            '<div class="input-group-text bg-white">' +
            '<input type="checkbox" class="chk-subtask-item" title="Centang jika langkah ini sudah selesai" ' + (isChecked ? 'checked' : '') + '>' +
            '<input type="hidden" name="checklist_done_flags[]" class="chk-subtask-flag" value="' + (isChecked ? '1' : '0') + '">' +
            '</div>' +
            '</div>' +
            '<input type="text" name="checklist_items[]" class="form-control txt-subtask-item" value="' + safeVal + '" placeholder="Langkah kerja ke-' + (num || 1) + ' (ketik langkah kerja Anda)..." style="' + (isChecked ? 'text-decoration: line-through; color: #16a34a; background:#f0fdf4;' : '') + '">' +
            '<div class="input-group-append">' +
            '<button type="button" class="btn btn-outline-danger btn-remove-subtask-item" title="Hapus item checklist"><i class="fas fa-times"></i></button>' +
            '</div>' +
            '</div>';
    }

    function recalcChecklistProgres() {
        var total = 0;
        var done = 0;
        $('#dt_checklist_container .tp-checklist-edit-row').each(function() {
            var txt = $.trim($(this).find('.txt-subtask-item').val() || '');
            var isChk = $(this).find('.chk-subtask-item').is(':checked');
            $(this).find('.chk-subtask-flag').val(isChk ? '1' : '0');
            $(this).find('.txt-subtask-item').css({
                'text-decoration': isChk ? 'line-through' : 'none',
                'color': isChk ? '#16a34a' : '#1e293b',
                'background': isChk ? '#f0fdf4' : '#ffffff'
            });
            if (txt !== '') {
                total++;
                if (isChk) done++;
            }
        });
        if (total > 0) {
            var pct = Math.round((done / total) * 100);
            $('#up_progres_slider').val(pct);
            $('#up_progres_label').text(pct);
            $('#up_auto_calc').val('1');
        }
    }

    $('#btnAddBawahanChecklist').on('click', function() {
        var nextNum = $('#dt_checklist_container .tp-checklist-edit-row').length + 1;
        $('#dt_checklist_container').append(buildEditableChecklistRowHtml('', false, nextNum));
        $('#dt_checklist_container .tp-checklist-edit-row:last .txt-subtask-item').trigger('focus');
        recalcChecklistProgres();
    });

    $(document).on('click', '.btn-remove-subtask-item', function() {
        if ($('#dt_checklist_container .tp-checklist-edit-row').length > 1) {
            $(this).closest('.tp-checklist-edit-row').remove();
        } else {
            var $row = $(this).closest('.tp-checklist-edit-row');
            $row.find('.txt-subtask-item').val('');
            $row.find('.chk-subtask-item').prop('checked', false);
        }
        recalcChecklistProgres();
    });

    // Slider Progres & Checklist Auto-Calculation
    $('#up_progres_slider').on('input change', function() {
        $('#up_progres_label').text($(this).val());
        $('#up_auto_calc').val('0');
    });

    $(document).on('change', '.chk-subtask-item', function() {
        recalcChecklistProgres();
    });

    $(document).on('input', '.txt-subtask-item', function() {
        recalcChecklistProgres();
    });

    // Submit Update Progres
    $('#formUpdateProgres').on('submit', function(e) {
        e.preventDefault();
        var formData = $(this).serializeArray();
        formData.push({ name: csrfName, value: getCsrfHash() });

        $.ajax({
            url: baseUrl + 'task_proyek/update_progres',
            type: 'POST',
            data: $.param(formData),
            dataType: 'json',
            success: function(res) {
                if (res && res.status === 'success') {
                    window.location.reload();
                } else {
                    alert(res && res.msg ? res.msg : 'Gagal memperbarui progres.');
                }
            }
        });
    });

    // Submit Selesai 100% (Bawahan -> Atasan)
    $('#formSubmitSelesai').on('submit', function(e) {
        e.preventDefault();

        var idTask = $('#sub_id_task').val();
        var catatan = $.trim($('#sub_catatan_bawahan').val());
        if (!catatan) {
            if (window.swal) {
                swal('Perhatian', 'Harap isi ringkasan hasil pekerjaan / penyelesaian terlebih dahulu!', 'warning');
            } else {
                alert('Harap isi ringkasan hasil pekerjaan / penyelesaian terlebih dahulu!');
            }
            $('#sub_catatan_bawahan').focus();
            return;
        }

        var $btn = $(this).find('button[type="submit"]');
        var originalBtnHtml = $btn.html();

        // Validasi: Cegah double click saat sedang proses kirim
        if ($btn.prop('disabled')) {
            return;
        }
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Mengirim Laporan...');

        var formData = $(this).serializeArray();
        $('#dt_checklist_container .txt-subtask-item').each(function() {
            var v = $.trim($(this).val() || '');
            if (v !== '') {
                formData.push({ name: 'checklist_items[]', value: v });
            }
        });
        formData.push({ name: csrfName, value: getCsrfHash() });

        $.ajax({
            url: baseUrl + 'task_proyek/submit_selesai',
            type: 'POST',
            data: $.param(formData),
            dataType: 'json',
            success: function(res) {
                $btn.prop('disabled', false).html(originalBtnHtml);

                if (res && res.status === 'success') {
                    // 1. Tutup modal detail task
                    $('#modalDetailTask').modal('hide');

                    var resData = res.data || {};
                    var autoStar = parseInt(resData.rating_bintang, 10) || 5;
                    var starBadgeHtml = buildStarsJs(autoStar);

                    // 2. Update langsung row di tabel Tugas Saya tanpa refresh Chrome
                    if ($.fn.dataTable && $.fn.dataTable.isDataTable('#datatable_tugas_saya')) {
                        var dtSaya = $('#datatable_tugas_saya').DataTable();
                        var $trSaya = $('#datatable_tugas_saya').find('tr[data-id="' + idTask + '"], #row_task_saya_' + idTask);
                        if ($trSaya.length) {
                            // Kolom Progres (Index 5)
                            $trSaya.find('td').eq(5).html(
                                '<div class="progress progress-sm mb-1" style="height: 10px; border-radius: 8px;">' +
                                '<div class="progress-bar bg-success" role="progressbar" style="width: 100%;"></div>' +
                                '</div>' +
                                '<div class="text-center small font-weight-bold">100%</div>'
                            );
                            // Kolom Status (Index 6)
                            $trSaya.find('td').eq(6).html(
                                '<span class="tp-status-badge tp-st-4"><i class="fas fa-check-circle"></i> Selesai & Dinilai</span>'
                            );
                            // Kolom Rating (Index 7)
                            $trSaya.find('td').eq(7).html(
                                '<div style="white-space: nowrap;">' + starBadgeHtml + '</div>'
                            );
                            // Kolom Aksi (Index 8) -> Hilangkan tombol submit selesai jika ada
                            $trSaya.find('button[data-focus="submit"]').remove();

                            // Re-index row DataTables tanpa mereset halaman / sorting
                            dtSaya.row($trSaya).invalidate().draw(false);
                        }
                    }

                    // 3. Update juga di tabel Penugasan ke Karyawan jika ada
                    if ($.fn.dataTable && $.fn.dataTable.isDataTable('#datatable_tugas_atasan')) {
                        var dtAtasan = $('#datatable_tugas_atasan').DataTable();
                        var $trAtasan = $('#datatable_tugas_atasan').find('tr[data-id="' + idTask + '"], #row_task_atasan_' + idTask);
                        if ($trAtasan.length) {
                            // Kolom Progres (Index 6)
                            $trAtasan.find('td').eq(6).html(
                                '<div class="progress progress-sm mb-1" style="height: 10px; border-radius: 8px;">' +
                                '<div class="progress-bar bg-success" role="progressbar" style="width: 100%;"></div>' +
                                '</div>' +
                                '<div class="text-center small font-weight-bold">100%</div>'
                            );
                            // Kolom Status (Index 7)
                            $trAtasan.find('td').eq(7).html(
                                '<span class="tp-status-badge tp-st-4"><i class="fas fa-check-circle"></i> Selesai & Dinilai</span>'
                            );
                            // Kolom Rating (Index 8)
                            $trAtasan.find('td').eq(8).html(
                                '<div style="white-space: nowrap;">' + starBadgeHtml + '</div>'
                            );
                            // Kolom Aksi Atasan (Index 9)
                            var btnReviewHtml = '<div class="tp-action-stack">' +
                                '<button type="button" class="btn btn-xs btn-primary btn-detail-task" data-id="' + idTask + '" data-focus="detail">' +
                                '<i class="fas fa-eye"></i> Detail' +
                                '</button></div>';
                            $trAtasan.find('td').eq(9).html(btnReviewHtml);

                            dtAtasan.row($trAtasan).invalidate().draw(false);
                        }
                    }

                    // 4. Update badge counter tab & KPI card jika ada
                    var $badgeReview = $('#badge_need_review');
                    if ($badgeReview.length) {
                        var curReview = parseInt($badgeReview.text(), 10) || 0;
                        if (curReview > 0) {
                            $badgeReview.html((curReview - 1) + ' Perlu Rating <i class="fas fa-star text-warning"></i>');
                        }
                    }

                    // 5. Tampilkan SweetAlert sukses
                    if (window.swal) {
                        swal('Selesai & Dinilai!', res.msg || 'Hasil pekerjaan berhasil diselesaikan dan dinilai otomatis!', 'success');
                    } else {
                        alert(res.msg || 'Hasil pekerjaan berhasil diselesaikan dan dinilai otomatis!');
                    }
                } else {
                    if (window.swal) {
                        swal('Gagal', (res && res.msg ? res.msg : 'Gagal mengirim laporan selesai.'), 'error');
                    } else {
                        alert(res && res.msg ? res.msg : 'Gagal mengirim laporan selesai.');
                    }
                }
            },
            error: function(xhr) {
                $btn.prop('disabled', false).html(originalBtnHtml);
                var errTxt = 'Terjadi kesalahan jaringan atau server saat mengirim laporan.';
                try {
                    var jErr = JSON.parse(xhr.responseText);
                    if (jErr && (jErr.msg || jErr.message)) {
                        errTxt = jErr.msg || jErr.message;
                    }
                } catch(e) {}
                if (window.swal) {
                    swal('Error', errTxt, 'error');
                } else {
                    alert(errTxt);
                }
            }
        });
    });

    // Interactive 5-Star Rating Picker Logic
    var starDescriptions = {
        1: '⭐ (1 / 5 — Kurang Memuaskan / Banyak Kekurangan)',
        2: '⭐⭐ (2 / 5 — Perlu Perbaikan)',
        3: '⭐⭐⭐ (3 / 5 — Cukup Baik / Sesuai Standar)',
        4: '⭐⭐⭐⭐ (4 / 5 — Sangat Baik, Rapi & Tepat Waktu)',
        5: '⭐⭐⭐⭐⭐ (5 / 5 — Istimewa / Sempurna & Sangat Memuaskan!)'
    };

    function setStarPickerValue(val) {
        val = parseInt(val, 10) || 5;
        var maxAllowed = parseInt(window.currentTaskMaxStar, 10) || 5;
        if (val > maxAllowed) {
            val = maxAllowed;
        }
        $('#rev_rating_bintang').val(val);
        $('#starPickerWidget .star-btn').each(function() {
            var sVal = parseInt($(this).data('val'), 10);
            if (sVal <= val) {
                $(this).addClass('active');
            } else {
                $(this).removeClass('active');
            }
        });
        if (maxAllowed === 1) {
            $('#starPickerLabel').text('⭐ (1 / 5 — Maksimal Bintang 1 karena melewati batas Deadline)');
        } else {
            $('#starPickerLabel').text(starDescriptions[val] || '');
        }
    }

    $(document).on('click', '#starPickerWidget .star-btn', function() {
        var val = parseInt($(this).data('val'), 10) || 1;
        var maxAllowed = parseInt(window.currentTaskMaxStar, 10) || 5;
        if (val > maxAllowed) {
            setStarPickerValue(1);
            return;
        }
        setStarPickerValue(val);
    });

    $('input[name="keputusan"]').on('change', function() {
        if ($(this).val() === 'revisi') {
            $('#wrapStarPicker').slideUp(150);
        } else {
            $('#wrapStarPicker').slideDown(150);
        }
    });

    $(document).on('click', '.btn-quick-ulasan', function() {
        $('#rev_ulasan_atasan').val($(this).data('text'));
    });

    // Submit Review & Rating Bintang 5 Atasan
    $('#formReviewAtasan').on('submit', function(e) {
        e.preventDefault();
        var $btn = $('#btnSubmitReviewAtasan');
        var origHtml = $btn.html();
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan Penilaian...');

        var formData = $(this).serializeArray();
        formData.push({ name: csrfName, value: getCsrfHash() });

        $.ajax({
            url: baseUrl + 'task_proyek/review_atasan',
            type: 'POST',
            data: $.param(formData),
            dataType: 'json',
            success: function(res) {
                $btn.prop('disabled', false).html(origHtml);
                if (res && res.status === 'success') {
                    $('#modalDetailTask').modal('hide');
                    if (window.swal) {
                        swal('Berhasil!', res.msg, 'success').then(function() { window.location.reload(); });
                    } else {
                        alert(res.msg);
                        window.location.reload();
                    }
                } else {
                    alert(res && res.msg ? res.msg : 'Gagal menyimpan penilaian.');
                }
            },
            error: function() {
                $btn.prop('disabled', false).html(origHtml);
                alert('Terjadi kesalahan jaringan.');
            }
        });
    });

    // Buka Modal Jadwalkan Pertemuan HRD (SP 3)
    $(document).on('click', '.btn-jadwal-hrd-sp3', function() {
        var $b = $(this);
        $('#jhrd_id_task').val($b.data('id'));
        $('#jhrd_kode_task').text($b.data('kode') || '-');
        $('#jhrd_judul_task').text($b.data('judul') || '-');
        $('#jhrd_nama_karyawan').text($b.data('karyawan') || '-');
        $('#jhrd_hari_telat').text($b.data('telat') || '21');
        var tgl = $b.data('tgl');
        if (tgl) {
            $('#jhrd_tgl').val(tgl);
        }
        $('#jhrd_jam').val($b.data('jam') || '10:00');
        $('#jhrd_metode').val($b.data('metode') || 'Tatap Muka (Offline)');
        $('#jhrd_lokasi').val($b.data('lokasi') || 'Ruang HRD');
        $('#jhrd_catatan').val($b.data('catatan') || '');
        $('#modalDetailTask').modal('hide');
        setTimeout(function() {
            $('#modalJadwalHrdSp3').modal('show');
        }, 200);
    });

    $('#formJadwalHrdSp3').on('submit', function(e) {
        e.preventDefault();
        var $btn = $('#btnSubmitJadwalHrdSp3');
        var orig = $btn.html();
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan Jadwal...');
        var formData = $(this).serializeArray();
        formData.push({ name: csrfName, value: getCsrfHash() });
        $.ajax({
            url: baseUrl + 'task_proyek/jadwalkan_hrd',
            type: 'POST',
            data: $.param(formData),
            dataType: 'json',
            success: function(res) {
                $btn.prop('disabled', false).html(orig);
                if (res && res.status === 'success') {
                    $('#modalJadwalHrdSp3').modal('hide');
                    if (window.swal) {
                        swal('Terjadwal!', res.msg, 'success').then(function() { window.location.reload(); });
                    } else {
                        alert(res.msg);
                        window.location.reload();
                    }
                } else {
                    alert((res && res.msg) ? res.msg : 'Gagal menyimpan jadwal pertemuan HRD.');
                }
            },
            error: function() {
                $btn.prop('disabled', false).html(orig);
                alert('Terjadi kesalahan jaringan.');
            }
        });
    });

    // Deteksi Pengajuan Mandiri pada dropdown Penerima
    $('#select_penerima_task').on('change', function() {
        var val = $(this).val() || [];
        var myUserId = <?= (int)$myId ?>;
        var isSelf = (val.length === 1 && parseInt(val[0], 10) === myUserId);
        if (isSelf) {
            $('#alert_task_mandiri').slideDown();
            $('#btnSubmitBuatTask').html('<i class="fas fa-paper-plane mr-1"></i> Ajukan Task Mandiri ke HRD');
            $('label[for="chk_kirim_wa"]').html('<i class="fab fa-whatsapp text-success"></i> Kirim Notifikasi Konfirmasi Pengajuan ke WhatsApp Saya');
        } else {
            $('#alert_task_mandiri').slideUp();
            $('#btnSubmitBuatTask').html('<i class="fas fa-paper-plane mr-1"></i> Simpan & Tugaskan Sekarang');
            $('label[for="chk_kirim_wa"]').html('<i class="fab fa-whatsapp text-success"></i> Kirim Notifikasi WhatsApp Otomatis ke Penerima Tugas');
        }
    });

    // Event Handler ACC Task Mandiri oleh HRD
    $(document).on('click', '.btn-acc-hrd, .btn-modal-acc-hrd', function() {
        var idTask = $(this).data('id');
        var kode = $(this).data('kode') || '';
        var judul = $(this).data('judul') || '';

        var confirmMsg = 'Setujui (ACC) pengajuan task mandiri ' + (kode ? '[' + kode + '] ' : '') + judul + '?\n\nKaryawan akan dapat mulai mengerjakan dan notifikasi WhatsApp otomatis dikirimkan ke Pelaksana serta pihak Mengetahui.';
        if (!confirm(confirmMsg)) return;

        var $btn = $(this);
        var origHtml = $btn.html();
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Memproses...');

        $.ajax({
            url: baseUrl + 'task_proyek/acc_hrd',
            type: 'POST',
            data: { id_task: idTask, [csrfName]: getCsrfHash() },
            dataType: 'json',
            success: function(res) {
                $btn.prop('disabled', false).html(origHtml);
                if (res && res.status === 'success') {
                    if (window.swal) {
                        swal('Berhasil!', res.msg, 'success').then(function() { location.reload(); });
                    } else {
                        alert(res.msg);
                        location.reload();
                    }
                } else {
                    alert((res && res.msg) ? res.msg : 'Gagal menyetujui task.');
                }
            },
            error: function() {
                $btn.prop('disabled', false).html(origHtml);
                alert('Terjadi kesalahan jaringan.');
            }
        });
    });

    // Event Handler Tolak Task Mandiri oleh HRD
    $(document).on('click', '.btn-tolak-hrd, .btn-modal-tolak-hrd', function() {
        var idTask = $(this).data('id');
        var kode = $(this).data('kode') || '';
        var judul = $(this).data('judul') || '';

        var alasan = prompt('Masukkan alasan penolakan pengajuan task mandiri ' + (kode ? '[' + kode + '] ' : '') + ':', '');
        if (alasan === null) return;
        alasan = $.trim(alasan);
        if (!alasan) {
            alert('Alasan penolakan wajib diisi!');
            return;
        }

        var $btn = $(this);
        var origHtml = $btn.html();
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Memproses...');

        $.ajax({
            url: baseUrl + 'task_proyek/tolak_hrd',
            type: 'POST',
            data: { id_task: idTask, alasan: alasan, [csrfName]: getCsrfHash() },
            dataType: 'json',
            success: function(res) {
                $btn.prop('disabled', false).html(origHtml);
                if (res && res.status === 'success') {
                    if (window.swal) {
                        swal('Ditolak', res.msg, 'info').then(function() { location.reload(); });
                    } else {
                        alert(res.msg);
                        location.reload();
                    }
                } else {
                    alert((res && res.msg) ? res.msg : 'Gagal menolak task.');
                }
            },
            error: function() {
                $btn.prop('disabled', false).html(origHtml);
                alert('Terjadi kesalahan jaringan.');
            }
        });
    });

    // Inisialisasi DataTable Semua Task Perusahaan
    if ($.fn.dataTable && $('#datatable_semua_task').length) {
        var dtSemua = $('#datatable_semua_task').DataTable({
            language: { url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json' },
            order: [[0, 'asc']],
            pageLength: 25,
            autoWidth: false,
            initComplete: function() {
                setupTableHorizontalUX('#datatable_semua_task');
            }
        });

        $('#filter_semua_karyawan').on('change', function() {
            var val = $(this).val();
            dtSemua.column(3).search(val ? val : '', true, false).draw();
        });

        $('#filter_semua_status').on('change', function() {
            var val = $(this).val();
            dtSemua.column(8).search(val ? val : '', true, false).draw();
        });
    }

    // Hapus Task
    $(document).on('click', '.btn-hapus-task', function() {
        var idTask = $(this).data('id');
        var kode = $(this).data('kode');
        if (!confirm('Apakah Anda yakin ingin menghapus Task ' + kode + '?')) return;

        $.ajax({
            url: baseUrl + 'task_proyek/delete',
            type: 'POST',
            data: { id_task: idTask, [csrfName]: getCsrfHash() },
            dataType: 'json',
            success: function(res) {
                if (res && res.status === 'success') {
                    window.location.reload();
                } else {
                    alert(res && res.msg ? res.msg : 'Gagal menghapus task.');
                }
            }
        });
    });

    // Sinkronkan kelas li.active pada nav-tabs-tp (kompatibel Bootstrap 3 & 4)
    $('.nav-tabs-tp a[data-toggle="tab"]').on('shown.bs.tab', function() {
        $('.nav-tabs-tp > li').removeClass('active');
        $(this).closest('li').addClass('active');
        var href = $(this).attr('href') || '';
        $('#tpTopFilterTab').val(href === '#tab_leaderboard' ? 'leaderboard' : '');
        if ($.fn.dataTable) {
            $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
        }
    });

    // Sinkronkan pilihan Bulan & Semester di Filter Bar atas agar tidak bertentangan
    $('#tpTopFilterBulan').on('change', function() {
        var m = parseInt($(this).val(), 10);
        if (!isNaN(m) && m >= 1 && m <= 12) {
            $('#tpTopFilterSemester').val(m <= 6 ? '1' : '2');
        }
    });
    $('#tpTopFilterSemester').on('change', function() {
        $('#tpTopFilterBulan').val('');
    });

    // Quick-jump dari Arena Hero ke Tab Leaderboard KPI
    $('#btnJumpLeaderboard').on('click', function() {
        var $lbTab = $('.nav-tabs-tp a[href="#tab_leaderboard"]').first();
        if ($lbTab.length) {
            $lbTab.tab('show');
        }
        var $panel = $('.tp-workspace-card').first();
        if ($panel.length && $('html, body').length) {
            $('html, body').animate({
                scrollTop: Math.max(0, $panel.offset().top - 70)
            }, 380);
        }
    });
});
</script>
