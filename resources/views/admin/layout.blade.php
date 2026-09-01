<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        @yield('title', 'پنل مدیریت')
        | فروشگاه فایل
    </title>

    <link
        rel="stylesheet"
        href="{{ asset('css/style.css') }}"
    >

    <style>

        /* =====================================================
           ADMIN DESIGN SYSTEM
        ===================================================== */

        :root {

            --admin-primary: #4f46e5;
            --admin-primary-dark: #4338ca;
            --admin-primary-soft: #eef2ff;

            --admin-bg: #f5f7fb;
            --admin-surface: #ffffff;
            --admin-surface-soft: #f8fafc;

            --admin-text: #172033;
            --admin-text-secondary: #667085;
            --admin-text-muted: #98a2b3;

            --admin-border: #e7eaf0;

            --admin-success: #12b76a;
            --admin-success-soft: #ecfdf3;

            --admin-warning: #f79009;
            --admin-warning-soft: #fffaeb;

            --admin-danger: #f04438;
            --admin-danger-soft: #fef3f2;

            --admin-sidebar-width: 270px;
            --admin-header-height: 70px;

            --admin-radius-sm: 8px;
            --admin-radius-md: 12px;
            --admin-radius-lg: 16px;

            --admin-shadow-sm:
                0 1px 2px rgba(16, 24, 40, .04);

            --admin-shadow-md:
                0 10px 30px rgba(16, 24, 40, .07);

        }


        /* =====================================================
           GLOBAL RESET
        ===================================================== */

        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        html {
            min-height: 100%;
            scrollbar-width: thin;
            scrollbar-color: rgba(79, 70, 229, .35) transparent;
        }

        body {

            margin: 0;

            min-height: 100vh;

            background: var(--admin-bg);
            color: var(--admin-text);

            font-family:
                Tahoma,
                Arial,
                sans-serif;

            overflow-y: auto;
            overflow-x: hidden;

            scrollbar-width: thin;
            scrollbar-color:
                rgba(79, 70, 229, .35)
                transparent;
        }


        /* =====================================================
           THIN MODERN BROWSER SCROLLBAR
        ===================================================== */

        html::-webkit-scrollbar,
        body::-webkit-scrollbar {

            width: 5px;
            height: 5px;
        }

        html::-webkit-scrollbar-track,
        body::-webkit-scrollbar-track {

            background: transparent;
        }

        html::-webkit-scrollbar-thumb,
        body::-webkit-scrollbar-thumb {

            background:
                rgba(79, 70, 229, .28);

            border-radius: 20px;

            border: 1px solid transparent;

            transition:
                background .2s ease,
                width .2s ease;
        }

        html::-webkit-scrollbar-thumb:hover,
        body::-webkit-scrollbar-thumb:hover {

            background:
                rgba(79, 70, 229, .68);
        }


        button,
        input,
        textarea,
        select {

            font-family: inherit;
        }

        a {
            color: inherit;
        }


        /* =====================================================
           SIDEBAR SCROLLBAR
        ===================================================== */

        .admin-sidebar-content {

            scrollbar-width: thin;

            scrollbar-color:
                rgba(79, 70, 229, .22)
                transparent;
        }

        .admin-sidebar-content::-webkit-scrollbar {

            width: 4px;
        }

        .admin-sidebar-content::-webkit-scrollbar-track {

            background: transparent;
        }

        .admin-sidebar-content::-webkit-scrollbar-thumb {

            background:
                rgba(79, 70, 229, .20);

            border-radius: 20px;
        }

        .admin-sidebar-content:hover::-webkit-scrollbar-thumb {

            background:
                rgba(79, 70, 229, .55);
        }


        /* =====================================================
           APP
        ===================================================== */

        .admin-app {

            min-height: 100vh;

            display: flex;

            width: 100%;
        }


        /* =====================================================
           SIDEBAR
        ===================================================== */

        .admin-sidebar {

            width: var(--admin-sidebar-width);

            flex:
                0 0 var(--admin-sidebar-width);

            position: fixed;

            top: 0;
            right: 0;
            bottom: 0;

            z-index: 1000;

            display: flex;
            flex-direction: column;

            background:
                rgba(255,255,255,.98);

            border-left:
                1px solid var(--admin-border);

            box-shadow:
                -4px 0 20px rgba(16,24,40,.025);

            transition:
                transform .25s ease;
        }


        /* =====================================================
           BRAND
        ===================================================== */

        .admin-brand {

            height:
                var(--admin-header-height);

            min-height:
                var(--admin-header-height);

            display: flex;

            align-items: center;

            gap: 12px;

            padding:
                0 20px;

            border-bottom:
                1px solid var(--admin-border);
        }

        .admin-brand-logo {

            width: 40px;
            height: 40px;

            flex: 0 0 40px;

            display: grid;

            place-items: center;

            color: #fff;

            background:
                linear-gradient(
                    135deg,
                    #4f46e5,
                    #7c3aed
                );

            border-radius: 11px;

            box-shadow:
                0 5px 14px
                rgba(79,70,229,.18);
        }

        .admin-brand-logo svg {

            width: 22px;
            height: 22px;
        }

        .admin-brand-text {

            min-width: 0;
        }

        .admin-brand-text strong {

            display: block;

            color:
                var(--admin-text);

            font-size: 15px;

            font-weight: 800;

            white-space: nowrap;
        }

        .admin-brand-text span {

            display: block;

            margin-top: 3px;

            color:
                var(--admin-text-muted);

            font-size: 10px;

            white-space: nowrap;
        }


        /* =====================================================
           SIDEBAR CONTENT
        ===================================================== */

        .admin-sidebar-content {

            flex: 1;

            padding:
                17px 12px;

            overflow-y: auto;

            overflow-x: hidden;
        }

        .admin-nav-section {

            margin-bottom: 22px;
        }

        .admin-nav-title {

            padding:
                0 11px;

            margin-bottom: 7px;

            color:
                #98a2b3;

            font-size: 10px;

            font-weight: 800;
        }

        .admin-nav {

            display: grid;

            gap: 3px;
        }

        .admin-nav-link {

            position: relative;

            min-height: 43px;

            display: flex;

            align-items: center;

            gap: 11px;

            padding:
                0 12px;

            color:
                #667085;

            text-decoration: none;

            border-radius: 10px;

            font-size: 12px;

            font-weight: 600;

            transition:
                background .18s ease,
                color .18s ease,
                transform .18s ease;
        }

        .admin-nav-link:hover {

            color:
                var(--admin-primary);

            background:
                #f8f9ff;
        }

        .admin-nav-link.active {

            color:
                var(--admin-primary);

            background:
                var(--admin-primary-soft);
        }

        .admin-nav-link.active::before {

            content: "";

            position: absolute;

            right: 0;

            top: 8px;
            bottom: 8px;

            width: 3px;

            background:
                var(--admin-primary);

            border-radius:
                4px 0 0 4px;
        }

        .admin-nav-icon {

            width: 20px;
            height: 20px;

            flex:
                0 0 20px;

            display: grid;

            place-items: center;
        }

        .admin-nav-icon svg {

            width: 18px;
            height: 18px;
        }

        .admin-nav-label {

            flex: 1;

            min-width: 0;
        }

        .admin-nav-badge {

            min-width: 20px;
            height: 20px;

            display: inline-flex;

            align-items: center;
            justify-content: center;

            padding:
                0 6px;

            color: #fff;

            background:
                var(--admin-danger);

            border-radius: 20px;

            font-size: 9px;

            font-weight: 800;
        }


        /* =====================================================
           SIDEBAR FOOTER
        ===================================================== */

        .admin-sidebar-footer {

            padding:
                13px;

            border-top:
                1px solid var(--admin-border);
        }

        .admin-logout-btn {

            width: 100%;
            height: 41px;

            display: flex;

            align-items: center;
            justify-content: center;

            gap: 8px;

            color:
                #667085;

            background:
                #f8fafc;

            border:
                1px solid var(--admin-border);

            border-radius: 10px;

            cursor: pointer;

            font-size: 11px;

            font-weight: 700;

            transition: .18s ease;
        }

        .admin-logout-btn:hover {

            color:
                var(--admin-danger);

            background:
                var(--admin-danger-soft);

            border-color:
                #fecdca;
        }


        /* =====================================================
           MAIN WRAPPER
        ===================================================== */

        .admin-main-wrapper {

            width:
                calc(100% - var(--admin-sidebar-width));

            margin-right:
                var(--admin-sidebar-width);

            min-width: 0;
        }


        /* =====================================================
           HEADER
        ===================================================== */

        .admin-header {

            height:
                var(--admin-header-height);

            position: sticky;

            top: 0;

            z-index: 900;

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 20px;

            padding:
                0 25px;

            background:
                rgba(255,255,255,.91);

            border-bottom:
                1px solid var(--admin-border);

            backdrop-filter:
                blur(16px);

            -webkit-backdrop-filter:
                blur(16px);
        }

        .admin-header-right,
        .admin-header-left {

            display: flex;

            align-items: center;

            gap: 10px;
        }


        /* =====================================================
           MOBILE MENU
        ===================================================== */

        .admin-mobile-menu {

            width: 39px;
            height: 39px;

            display: none;

            place-items: center;

            color:
                var(--admin-text);

            background:
                #fff;

            border:
                1px solid var(--admin-border);

            border-radius: 10px;

            cursor: pointer;
        }

        .admin-mobile-menu svg {

            width: 19px;
            height: 19px;
        }


        /* =====================================================
           SEARCH
        ===================================================== */

        .admin-search {

            position: relative;

            width:
                min(390px, 35vw);
        }

        .admin-search input {

            width: 100%;

            height: 40px;

            padding:
                0 41px 0 14px;

            color:
                var(--admin-text);

            background:
                #f8fafc;

            border:
                1px solid transparent;

            border-radius: 10px;

            outline: none;

            font-size: 11px;

            transition: .18s ease;
        }

        .admin-search input:focus {

            background:
                #fff;

            border-color:
                #c7d2fe;

            box-shadow:
                0 0 0 3px
                rgba(79,70,229,.07);
        }

        .admin-search-icon {

            position: absolute;

            right: 12px;

            top: 50%;

            transform:
                translateY(-50%);

            color:
                #98a2b3;

            pointer-events: none;
        }

        .admin-search-icon svg {

            width: 17px;
            height: 17px;
        }


        /* =====================================================
           HEADER ICON
        ===================================================== */

        .admin-icon-btn {

            width: 40px;
            height: 40px;

            position: relative;

            display: grid;

            place-items: center;

            color:
                #667085;

            background:
                #fff;

            border:
                1px solid var(--admin-border);

            border-radius: 10px;

            cursor: pointer;

            text-decoration: none;

            transition: .18s ease;
        }

        .admin-icon-btn:hover {

            color:
                var(--admin-primary);

            background:
                var(--admin-primary-soft);

            border-color:
                #c7d2fe;
        }

        .admin-icon-btn svg {

            width: 18px;
            height: 18px;
        }


        /* =====================================================
           NOTIFICATION DOT
        ===================================================== */

        .admin-notification-dot {

            position: absolute;

            top: 6px;
            right: 6px;

            width: 7px;
            height: 7px;

            background:
                var(--admin-danger);

            border:
                2px solid #fff;

            border-radius: 50%;
        }


        /* =====================================================
           USER
        ===================================================== */

        .admin-user {

            display: flex;

            align-items: center;

            gap: 9px;

            padding-right: 11px;

            border-right:
                1px solid var(--admin-border);
        }

        .admin-user-avatar {

            width: 37px;
            height: 37px;

            display: grid;

            place-items: center;

            color:
                var(--admin-primary);

            background:
                var(--admin-primary-soft);

            border-radius: 10px;

            font-size: 12px;

            font-weight: 800;
        }

        .admin-user-info strong {

            display: block;

            font-size: 11px;
        }

        .admin-user-info span {

            display: block;

            margin-top: 3px;

            color:
                var(--admin-text-muted);

            font-size: 9px;
        }


        /* =====================================================
           CONTENT
        ===================================================== */

        .admin-main {

            padding:
                25px;
        }

        .admin-page {

            width: 100%;

            max-width: 1500px;

            margin:
                0 auto;
        }


        /* =====================================================
           BREADCRUMB
        ===================================================== */

        .admin-breadcrumb {

            display: flex;

            align-items: center;

            gap: 7px;

            margin-bottom: 7px;

            color:
                var(--admin-text-muted);

            font-size: 10px;
        }

        .admin-breadcrumb a {

            text-decoration: none;
        }

        .admin-breadcrumb a:hover {

            color:
                var(--admin-primary);
        }

        .admin-breadcrumb-separator {

            color:
                #cbd5e1;
        }


        /* =====================================================
           PAGE HEADER
        ===================================================== */

        .admin-page-head {

            display: flex;

            align-items: flex-end;

            justify-content: space-between;

            gap: 20px;

            margin-bottom: 22px;
        }

        .admin-eyebrow {

            margin-bottom: 5px;

            color:
                var(--admin-primary);

            font-size: 10px;

            font-weight: 800;
        }

        .admin-page-head h1 {

            margin: 0;

            color:
                var(--admin-text);

            font-size: 25px;

            font-weight: 800;
        }

        .admin-page-head p {

            margin:
                6px 0 0;

            color:
                var(--admin-text-muted);

            font-size: 11px;
        }


        /* =====================================================
           STAT CARDS
        ===================================================== */

        .admin-stat-grid {

            display: grid;

            grid-template-columns:
                repeat(4, minmax(0,1fr));

            gap: 15px;

            margin-bottom: 18px;
        }

        .admin-stat-card {

            position: relative;

            padding: 19px;

            background:
                var(--admin-surface);

            border:
                1px solid var(--admin-border);

            border-radius:
                var(--admin-radius-lg);

            box-shadow:
                var(--admin-shadow-sm);

            overflow: hidden;
        }

        .admin-stat-card::after {

            content: "";

            position: absolute;

            width: 75px;
            height: 75px;

            left: -28px;
            bottom: -34px;

            background:
                var(--admin-primary-soft);

            border-radius: 50%;
        }

        .admin-stat-top {

            display: flex;

            align-items: center;

            justify-content: space-between;

            margin-bottom: 16px;
        }

        .admin-stat-label {

            color:
                var(--admin-text-secondary);

            font-size: 11px;

            font-weight: 600;
        }

        .admin-stat-icon {

            width: 37px;
            height: 37px;

            display: grid;

            place-items: center;

            color:
                var(--admin-primary);

            background:
                var(--admin-primary-soft);

            border-radius: 10px;
        }

        .admin-stat-icon svg {

            width: 18px;
            height: 18px;
        }

        .admin-stat-value {

            position: relative;

            z-index: 1;

            display: flex;

            align-items: baseline;

            gap: 6px;
        }

        .admin-stat-value strong {

            font-size: 24px;

            font-weight: 800;
        }

        .admin-stat-value small {

            color:
                var(--admin-text-muted);

            font-size: 9px;
        }

        .admin-stat-footer {

            position: relative;

            z-index: 1;

            margin-top: 8px;

            color:
                var(--admin-text-muted);

            font-size: 9px;
        }


        /* =====================================================
           ACTIONS
        ===================================================== */

        .admin-action-grid {

            display: grid;

            grid-template-columns:
                repeat(4,minmax(0,1fr));

            gap: 11px;

            margin-bottom: 18px;
        }

        .admin-action-card {

            display: flex;

            align-items: center;

            gap: 11px;

            min-height: 67px;

            padding:
                11px 13px;

            color:
                var(--admin-text);

            background:
                var(--admin-surface);

            border:
                1px solid var(--admin-border);

            border-radius:
                12px;

            text-decoration: none;

            box-shadow:
                var(--admin-shadow-sm);

            transition:
                transform .18s ease,
                box-shadow .18s ease,
                border-color .18s ease;
        }

        .admin-action-card:hover {

            transform:
                translateY(-2px);

            border-color:
                #c7d2fe;

            box-shadow:
                var(--admin-shadow-md);
        }

        .admin-action-icon {

            width: 37px;
            height: 37px;

            display: grid;

            place-items: center;

            color:
                var(--admin-primary);

            background:
                var(--admin-primary-soft);

            border-radius: 10px;
        }

        .admin-action-icon svg {

            width: 17px;
            height: 17px;
        }

        .admin-action-card strong {

            display: block;

            font-size: 11px;
        }

        .admin-action-card span {

            display: block;

            margin-top: 3px;

            color:
                var(--admin-text-muted);

            font-size: 9px;
        }


        /* =====================================================
           BOXES
        ===================================================== */

        .admin-box {

            padding: 20px;

            background:
                var(--admin-surface);

            border:
                1px solid var(--admin-border);

            border-radius:
                var(--admin-radius-lg);

            box-shadow:
                var(--admin-shadow-sm);
        }

        .admin-box-head {

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 15px;

            margin-bottom: 18px;
        }

        .admin-box-head h2 {

            margin:
                4px 0 0;

            font-size: 14px;

            font-weight: 800;
        }

        .admin-box-head > div:first-child span {

            color:
                var(--admin-text-muted);

            font-size: 9px;
        }


        /* =====================================================
           NEEDS ACTION
        ===================================================== */

        .admin-needs-action {

            margin-bottom: 18px;
        }

        .admin-needs-list {

            display: grid;

            grid-template-columns:
                repeat(4,minmax(0,1fr));

            gap: 9px;
        }

        .admin-needs-item {

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 10px;

            padding: 13px;

            background:
                var(--admin-surface-soft);

            border:
                1px solid var(--admin-border);

            border-radius: 11px;

            text-decoration: none;

            transition: .18s ease;
        }

        .admin-needs-item:hover {

            border-color:
                #c7d2fe;

            background:
                #fff;
        }

        .admin-needs-item-main {

            display: flex;

            align-items: center;

            gap: 9px;

            min-width: 0;
        }

        .admin-needs-item-icon {

            width: 31px;
            height: 31px;

            flex:
                0 0 31px;

            display: grid;

            place-items: center;

            color:
                var(--admin-warning);

            background:
                var(--admin-warning-soft);

            border-radius: 8px;
        }

        .admin-needs-item-icon svg {

            width: 15px;
            height: 15px;
        }

        .admin-needs-item strong {

            display: block;

            font-size: 10px;
        }

        .admin-needs-item span {

            display: block;

            margin-top: 3px;

            color:
                var(--admin-text-muted);

            font-size: 8px;
        }

        .admin-needs-count {

            min-width: 27px;
            height: 27px;

            display: grid;

            place-items: center;

            color:
                var(--admin-warning);

            background:
                #fff;

            border:
                1px solid #fedf89;

            border-radius: 8px;

            font-size: 10px;

            font-weight: 800;
        }


        /* =====================================================
           DASHBOARD GRID
        ===================================================== */

        .admin-dashboard-grid {

            display: grid;

            grid-template-columns:
                minmax(0,1.4fr)
                minmax(300px,.8fr);

            gap: 17px;

            margin-bottom: 17px;
        }


        /* =====================================================
           STORAGE
        ===================================================== */

        .storage-numbers {

            display: flex;

            align-items: baseline;

            gap: 7px;

            margin-bottom: 12px;
        }

        .storage-numbers strong {

            font-size: 27px;

            font-weight: 800;
        }

        .storage-numbers span {

            color:
                var(--admin-text-muted);

            font-size: 10px;
        }

        .storage-bar {

            height: 8px;

            overflow: hidden;

            background:
                #eef2f6;

            border-radius: 20px;
        }

        .storage-bar span {

            display: block;

            height: 100%;

            background:
                linear-gradient(
                    90deg,
                    var(--admin-primary),
                    #7c3aed
                );

            border-radius: inherit;
        }

        .storage-meta {

            display: flex;

            justify-content: space-between;

            margin-top: 10px;

            color:
                var(--admin-text-muted);

            font-size: 9px;
        }


        /* =====================================================
           CHART
        ===================================================== */

        .chart-placeholder {

            min-height: 225px;

            display: flex;

            align-items: center;

            justify-content: center;

            padding: 20px;

            color:
                var(--admin-text-muted);

            background:
                repeating-linear-gradient(
                    0deg,
                    transparent,
                    transparent 44px,
                    #f1f3f6 45px
                );

            border:
                1px dashed #dfe3ea;

            border-radius: 12px;

            font-size: 11px;

            text-align: center;
        }


        /* =====================================================
           BUTTONS
        ===================================================== */

        .admin-primary-btn,
        .admin-secondary-btn {

            min-height: 39px;

            display: inline-flex;

            align-items: center;

            justify-content: center;

            gap: 7px;

            padding:
                0 13px;

            border-radius: 9px;

            text-decoration: none;

            font-size: 10px;

            font-weight: 700;

            cursor: pointer;

            transition: .18s ease;
        }

        .admin-primary-btn {

            color: #fff;

            background:
                var(--admin-primary);

            border:
                1px solid var(--admin-primary);
        }

        .admin-primary-btn:hover {

            background:
                var(--admin-primary-dark);
        }

        .admin-secondary-btn {

            color:
                #475467;

            background:
                #fff;

            border:
                1px solid var(--admin-border);
        }

        .admin-secondary-btn:hover {

            color:
                var(--admin-primary);

            background:
                var(--admin-primary-soft);

            border-color:
                #c7d2fe;
        }


        /* =====================================================
           ALERTS
        ===================================================== */

        .admin-alert {

            padding:
                12px 15px;

            margin-bottom:
                17px;

            border-radius:
                10px;

            font-size: 11px;
        }

        .admin-alert.success {

            color:
                #067647;

            background:
                var(--admin-success-soft);

            border:
                1px solid #abefc6;
        }

        .admin-alert.error {

            color:
                #b42318;

            background:
                var(--admin-danger-soft);

            border:
                1px solid #fecdca;
        }


        /* =====================================================
           FORM
        ===================================================== */

        .admin-form-card {

            padding:
                22px;

            background:
                #fff;

            border:
                1px solid var(--admin-border);

            border-radius:
                var(--admin-radius-lg);

            box-shadow:
                var(--admin-shadow-sm);
        }

        .admin-form {

            width: 100%;
        }

        .form-row {

            display: grid;

            grid-template-columns:
                repeat(2,minmax(0,1fr));

            gap: 15px;

            margin-bottom: 15px;
        }

        .form-group {

            margin-bottom: 15px;
        }

        .form-group label {

            display: block;

            margin-bottom: 7px;

            color:
                var(--admin-text);

            font-size: 11px;

            font-weight: 700;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {

            width: 100%;

            box-sizing: border-box;

            color:
                var(--admin-text);

            background:
                #fff;

            border:
                1px solid #dfe3ea;

            border-radius:
                10px;

            padding:
                11px 12px;

            outline: none;

            font-family: inherit;

            font-size: 11px;

            transition: .18s ease;
        }

        .form-group input {

            height: 42px;
        }

        .form-group textarea {

            min-height: 100px;

            resize:
                vertical;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {

            border-color:
                #a5b4fc;

            box-shadow:
                0 0 0 3px
                rgba(79,70,229,.06);
        }

        .form-group small {

            display: block;

            margin-top: 6px;

            color:
                var(--admin-text-muted);

            font-size: 9px;
        }

        .checkbox-label {

            display: flex;

            align-items: center;

            gap: 8px;

            margin:
                15px 0;

            color:
                var(--admin-text-secondary);

            font-size: 11px;

            cursor: pointer;
        }

        .checkbox-label input {

            width: 16px;
            height: 16px;
        }

        .form-actions {

            display: flex;

            align-items: center;

            justify-content: flex-end;

            gap: 8px;

            margin-top: 20px;

            padding-top: 17px;

            border-top:
                1px solid var(--admin-border);
        }


        /* =====================================================
           TABLES
        ===================================================== */

        .admin-table-card {

            background:
                #fff;

            border:
                1px solid var(--admin-border);

            border-radius:
                var(--admin-radius-lg);

            box-shadow:
                var(--admin-shadow-sm);

            overflow: hidden;
        }

        .admin-table-wrap {

            width: 100%;

            overflow-x: auto;

            scrollbar-width: thin;

            scrollbar-color:
                rgba(79,70,229,.25)
                transparent;
        }

        .admin-table {

            width: 100%;

            border-collapse: collapse;

            min-width: 850px;

            font-size: 10px;
        }

        .admin-table th {

            padding:
                13px 15px;

            color:
                var(--admin-text-secondary);

            background:
                #f8fafc;

            border-bottom:
                1px solid var(--admin-border);

            text-align: right;

            white-space: nowrap;

            font-size: 9px;
        }

        .admin-table td {

            padding:
                14px 15px;

            color:
                var(--admin-text-secondary);

            border-bottom:
                1px solid #f1f3f5;

            vertical-align: middle;
        }

        .admin-table tr:last-child td {

            border-bottom: 0;
        }

        .admin-table strong {

            color:
                var(--admin-text);

            font-size: 10px;
        }

        .muted {

            color:
                var(--admin-text-muted);
        }


        /* =====================================================
           STATUS
        ===================================================== */

        .status-badge {

            display: inline-flex;

            align-items: center;

            min-height: 24px;

            padding:
                0 8px;

            border-radius: 20px;

            font-size: 8px;

            font-weight: 700;
        }

        .status-badge.active {

            color:
                #067647;

            background:
                var(--admin-success-soft);
        }

        .status-badge.inactive {

            color:
                #667085;

            background:
                #f2f4f7;
        }

        .status-badge.danger {

            color:
                #b42318;

            background:
                var(--admin-danger-soft);
        }


        /* =====================================================
           ACTION BUTTONS
        ===================================================== */

        .admin-actions {

            display: flex;

            align-items: center;

            gap: 5px;

            flex-wrap: wrap;
        }

        .action-btn {

            min-height: 29px;

            display: inline-flex;

            align-items: center;

            justify-content: center;

            padding:
                0 9px;

            color:
                var(--admin-text-secondary);

            background:
                #fff;

            border:
                1px solid var(--admin-border);

            border-radius: 7px;

            text-decoration: none;

            cursor: pointer;

            font-size: 8px;

            font-weight: 700;
        }

        .action-btn:hover {

            color:
                var(--admin-primary);

            border-color:
                #c7d2fe;

            background:
                var(--admin-primary-soft);
        }

        .action-btn.delete:hover {

            color:
                var(--admin-danger);

            border-color:
                #fecdca;

            background:
                var(--admin-danger-soft);
        }


        /* =====================================================
           EMPTY
        ===================================================== */

        .admin-empty {

            padding:
                45px 20px !important;

            color:
                var(--admin-text-muted) !important;

            text-align: center;

            font-size: 11px;
        }


        /* =====================================================
           DISCOUNT INFO
        ===================================================== */

        .discount-used-info {

            padding:
                12px 14px;

            margin:
                10px 0 15px;

            color:
                var(--admin-text-secondary);

            background:
                #f8fafc;

            border:
                1px solid var(--admin-border);

            border-radius:
                10px;

            font-size: 10px;

            line-height: 1.9;
        }

        .discount-used-info code {

            direction: ltr;

            unicode-bidi: plaintext;

            color:
                var(--admin-primary);

            font-size: 9px;
        }


        /* =====================================================
           NARROW
        ===================================================== */

        .admin-page.narrow {

            max-width: 1000px;
        }


        /* =====================================================
           MOBILE OVERLAY
        ===================================================== */

        .admin-sidebar-overlay {

            display: none;

            position: fixed;

            inset: 0;

            z-index: 950;

            background:
                rgba(15,23,42,.38);

            backdrop-filter:
                blur(2px);
        }


        /* =====================================================
           RESPONSIVE 1200
        ===================================================== */

        @media (max-width: 1200px) {

            .admin-stat-grid {

                grid-template-columns:
                    repeat(2,1fr);
            }

            .admin-action-grid {

                grid-template-columns:
                    repeat(2,1fr);
            }

            .admin-needs-list {

                grid-template-columns:
                    repeat(2,1fr);
            }
        }


        /* =====================================================
           RESPONSIVE 900
        ===================================================== */

        @media (max-width: 900px) {

            .admin-dashboard-grid {

                grid-template-columns: 1fr;
            }

            .admin-search {

                width: 280px;
            }

            .form-row {

                grid-template-columns: 1fr;
            }
        }


        /* =====================================================
           RESPONSIVE 760
        ===================================================== */

        @media (max-width: 760px) {

            .admin-sidebar {

                transform:
                    translateX(100%);

                box-shadow:
                    -10px 0 35px
                    rgba(16,24,40,.12);
            }

            .admin-sidebar.open {

                transform:
                    translateX(0);
            }

            .admin-sidebar-overlay.open {

                display: block;
            }

            .admin-main-wrapper {

                width: 100%;

                margin-right: 0;
            }

            .admin-mobile-menu {

                display: grid;
            }

            .admin-header {

                padding:
                    0 14px;
            }

            .admin-search {

                display: none;
            }

            .admin-user-info {

                display: none;
            }

            .admin-user {

                border-right: 0;

                padding-right: 0;
            }

            .admin-main {

                padding:
                    18px 13px;
            }

            .admin-page-head {

                align-items:
                    flex-start;

                flex-direction:
                    column;
            }

            .admin-stat-grid,
            .admin-action-grid,
            .admin-needs-list {

                grid-template-columns:
                    1fr;
            }
        }


        /* =====================================================
           RESPONSIVE 480
        ===================================================== */

        @media (max-width: 480px) {

            .admin-header {

                gap: 6px;
            }

            .admin-header-right,
            .admin-header-left {

                gap: 6px;
            }

            .admin-icon-btn,
            .admin-mobile-menu {

                width: 37px;
                height: 37px;
            }

            .admin-stat-card {

                padding: 16px;
            }

            .admin-page-head h1 {

                font-size: 21px;
            }

            .admin-form-card,
            .admin-box {

                padding: 16px;
            }
        }


        /* =====================================================
           REDUCE MOTION
        ===================================================== */

        @media (prefers-reduced-motion: reduce) {

            *,
            *::before,
            *::after {

                scroll-behavior: auto !important;

                transition-duration:
                    .01ms !important;
            }
        }

    </style>

    @stack('styles')

</head>


<body>

<div class="admin-app">

    <div
        class="admin-sidebar-overlay"
        id="adminSidebarOverlay"
        onclick="toggleAdminSidebar()"
    ></div>


    {{-- =====================================================
         SIDEBAR
    ====================================================== --}}

    <aside
        class="admin-sidebar"
        id="adminSidebar"
    >

        <div class="admin-brand">

            <div class="admin-brand-logo">

                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <path d="M4 7.5A2.5 2.5 0 0 1 6.5 5H20v14H6.5A2.5 2.5 0 0 1 4 16.5v-9Z"/>
                    <path d="M4 7.5C4 8.88 5.12 10 6.5 10H20"/>
                </svg>

            </div>

            <div class="admin-brand-text">

                <strong>
                    فایل‌مارکت
                </strong>

                <span>
                    پنل مدیریت اصلی
                </span>

            </div>

        </div>


        <div class="admin-sidebar-content">


            {{-- =================================================
                 اصلی
            ================================================== --}}

            <div class="admin-nav-section">

                <div class="admin-nav-title">
                    اصلی
                </div>

                <nav class="admin-nav">

                    <a
                        href="{{ route('admin.dashboard') }}"
                        class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                    >

                        <span class="admin-nav-icon">

                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <rect x="3" y="3" width="7" height="7" rx="1"/>
                                <rect x="14" y="3" width="7" height="7" rx="1"/>
                                <rect x="3" y="14" width="7" height="7" rx="1"/>
                                <rect x="14" y="14" width="7" height="7" rx="1"/>
                            </svg>

                        </span>

                        <span class="admin-nav-label">
                            داشبورد
                        </span>

                    </a>

                </nav>

            </div>


            {{-- =================================================
                 مدیریت فروشگاه
            ================================================== --}}

            <div class="admin-nav-section">

                <div class="admin-nav-title">
                    مدیریت فروشگاه
                </div>

                <nav class="admin-nav">

                    <a
                        href="{{ route('admin.products.index') }}"
                        class="admin-nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}"
                    >

                        <span class="admin-nav-icon">

                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <path d="M4 7.5 12 3l8 4.5v9L12 21l-8-4.5v-9Z"/>
                                <path d="M4.5 7.8 12 12l7.5-4.2"/>
                                <path d="M12 12v9"/>
                            </svg>

                        </span>

                        <span class="admin-nav-label">
                            محصولات
                        </span>

                    </a>


                    <a
                        href="{{ route('admin.categories.index') }}"
                        class="admin-nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}"
                    >

                        <span class="admin-nav-icon">

                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <path d="M4 5.5A1.5 1.5 0 0 1 5.5 4h4L12 7l-2.5 3h-4A1.5 1.5 0 0 1 4 8.5v-3Z"/>
                                <path d="M12 13.5A1.5 1.5 0 0 1 13.5 12h5A1.5 1.5 0 0 1 20 13.5v5a1.5 1.5 0 0 1-1.5 1.5h-5a1.5 1.5 0 0 1-1.5-1.5v-5Z"/>
                            </svg>

                        </span>

                        <span class="admin-nav-label">
                            دسته‌بندی‌ها
                        </span>

                    </a>


                    <a
                        href="#"
                        class="admin-nav-link"
                    >

                        <span class="admin-nav-icon">

                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <path d="M20 12a8 8 0 1 1-2.34-5.66"/>
                                <path d="M20 4v6h-6"/>
                                <path d="M8.5 12h7"/>
                                <path d="M12 8.5v7"/>
                            </svg>

                        </span>

                        <span class="admin-nav-label">
                            فروشندگان
                        </span>

                    </a>


<a
href="{{ route('admin.users.index') }}"
class="admin-nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"

>


<span class="admin-nav-icon">

    <svg
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="1.8"
    >
        <circle cx="12" cy="8" r="3"/>
        <path d="M5 20a7 7 0 0 1 14 0"/>
    </svg>

</span>

<span class="admin-nav-label">
    کاربران
</span>


</a>

<a
href="#"
class="admin-nav-link"

>


<span class="admin-nav-icon">

    <svg
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="1.8"
    >
        <rect x="3" y="5" width="18" height="14" rx="2"/>
        <path d="M3 10h18"/>
        <path d="M7 15h3"/>
    </svg>

</span>

<span class="admin-nav-label">
    سفارش‌ها
</span>

                    </a>


                    <a
                        href="#"
                        class="admin-nav-link"
                    >

                        <span class="admin-nav-icon">

                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <circle cx="12" cy="12" r="8.5"/>
                                <path d="M15 9.5c-.6-.7-1.6-1.1-3-1.1-1.8 0-3 .8-3 2 0 3 6 1.4 6 4 0 1.2-1.2 2-3 2-1.3 0-2.4-.4-3.1-1.2"/>
                                <path d="M12 6.5v11"/>
                            </svg>

                        </span>

                        <span class="admin-nav-label">
                            پرداخت‌ها
                        </span>

                    </a>


                    <a
                        href="{{ route('admin.discounts.index') }}"
                        class="admin-nav-link {{ request()->routeIs('admin.discounts.*') ? 'active' : '' }}"
                    >

                        <span class="admin-nav-icon">

                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <path d="m4 7 3-3h6l7 7-7 7-7-7V7Z"/>
                                <circle cx="8.5" cy="8.5" r="1"/>
                            </svg>

                        </span>

                        <span class="admin-nav-label">
                            تخفیف‌ها
                        </span>

                    </a>

                </nav>

            </div>


            {{-- =================================================
                 مالی و سیستم
            ================================================== --}}

            <div class="admin-nav-section">

                <div class="admin-nav-title">
                    مالی و سیستم
                </div>

                <nav class="admin-nav">


                    <a
                        href="#"
                        class="admin-nav-link"
                    >

                        <span class="admin-nav-icon">

                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <rect x="3" y="6" width="18" height="13" rx="2"/>
                                <path d="M7 6V4h10v2"/>
                                <path d="M8 13h8"/>
                            </svg>

                        </span>

                        <span class="admin-nav-label">
                            کیف پول و امور مالی
                        </span>

                    </a>


                    <a
                        href="#"
                        class="admin-nav-link"
                    >

                        <span class="admin-nav-icon">

                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <path d="M4 6h16"/>
                                <path d="M4 12h16"/>
                                <path d="M4 18h16"/>
                                <circle cx="8" cy="6" r="2"/>
                                <circle cx="15" cy="12" r="2"/>
                                <circle cx="10" cy="18" r="2"/>
                            </svg>

                        </span>

                        <span class="admin-nav-label">
                            گزارش‌ها
                        </span>

                    </a>


                    <a
                        href="{{ route('admin.storage.index') }}"
                        class="admin-nav-link {{ request()->routeIs('admin.storage.*') ? 'active' : '' }}"
                    >

                        <span class="admin-nav-icon">

                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <path d="M7 18a5 5 0 0 1 .6-9.96A6 6 0 0 1 19 10a4 4 0 0 1-1 7.9H7Z"/>
                                <path d="M12 12v6"/>
                                <path d="m9.5 14.5 2.5 2.5 2.5-2.5"/>
                            </svg>

                        </span>

                        <span class="admin-nav-label">
                            ذخیره‌سازی
                        </span>

                    </a>


                    <a
                        href="#"
                        class="admin-nav-link"
                    >

                        <span class="admin-nav-icon">

                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <path d="M4 5h16v14H4z"/>
                                <path d="M8 9h8"/>
                                <path d="M8 13h5"/>
                            </svg>

                        </span>

                        <span class="admin-nav-label">
                            فعالیت و حسابرسی
                        </span>

                    </a>


                    <a
                        href="#"
                        class="admin-nav-link"
                    >

                        <span class="admin-nav-icon">

                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <path d="M12 3v3"/>
                                <path d="M12 18v3"/>
                                <path d="m4.22 4.22 2.12 2.12"/>
                                <path d="m17.66 17.66 2.12 2.12"/>
                                <path d="M3 12h3"/>
                                <path d="M18 12h3"/>
                                <path d="m4.22 19.78 2.12-2.12"/>
                                <path d="m17.66 6.34 2.12-2.12"/>
                                <circle cx="12" cy="12" r="4"/>
                            </svg>

                        </span>

                        <span class="admin-nav-label">
                            تنظیمات
                        </span>

                    </a>

                </nav>

            </div>

        </div>


        {{-- =================================================
             LOGOUT
        ================================================== --}}

        <div class="admin-sidebar-footer">

            <form
                method="POST"
                action="{{ route('admin.logout') }}"
            >

                @csrf

                <button
                    type="submit"
                    class="admin-logout-btn"
                >

                    <svg
                        width="16"
                        height="16"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <path d="M10 17l5-5-5-5"/>
                        <path d="M15 12H3"/>
                        <path d="M21 3v18"/>
                    </svg>

                    خروج از پنل

                </button>

            </form>

        </div>

    </aside>


    {{-- =====================================================
         MAIN
    ====================================================== --}}

    <div class="admin-main-wrapper">


        {{-- =================================================
             HEADER
        ================================================== --}}

        <header class="admin-header">


            <div class="admin-header-right">

                <button
                    type="button"
                    class="admin-mobile-menu"
                    onclick="toggleAdminSidebar()"
                    aria-label="باز کردن منو"
                >

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <path d="M4 6h16"/>
                        <path d="M4 12h16"/>
                        <path d="M4 18h16"/>
                    </svg>

                </button>


                <div class="admin-search">

                    <span class="admin-search-icon">

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <circle cx="11" cy="11" r="7"/>
                            <path d="m20 20-4-4"/>
                        </svg>

                    </span>

                    <input
                        type="search"
                        placeholder="جست‌وجوی محصول، کاربر، فروشنده..."
                        aria-label="جست‌وجوی مدیریت"
                    >

                </div>

            </div>


            <div class="admin-header-left">


                {{-- Notification --}}

                <a
                    href="#"
                    class="admin-icon-btn"
                    aria-label="اعلان‌ها"
                >

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <path d="M18 9a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"/>
                        <path d="M10 21h4"/>
                    </svg>

                    <span class="admin-notification-dot"></span>

                </a>


                {{-- User --}}

                <div class="admin-user">

                    <div class="admin-user-avatar">
                        ا
                    </div>

                    <div class="admin-user-info">

                        <strong>
                            مدیر اصلی
                        </strong>

                        <span>
                            مدیریت فروشگاه
                        </span>

                    </div>

                </div>

            </div>

        </header>


        {{-- =================================================
             PAGE CONTENT
        ================================================== --}}

        <main class="admin-main">

            @if(session('success'))

                <div class="admin-alert success">
                    {{ session('success') }}
                </div>

            @endif


            @if(session('error'))

                <div class="admin-alert error">
                    {{ session('error') }}
                </div>

            @endif


            @yield('content')

        </main>

    </div>

</div>


<script>

    function toggleAdminSidebar() {

        const sidebar =
            document.getElementById(
                'adminSidebar'
            );

        const overlay =
            document.getElementById(
                'adminSidebarOverlay'
            );

        sidebar.classList.toggle('open');

        overlay.classList.toggle('open');
    }


    /*
     * Close mobile sidebar after
     * clicking a navigation link.
     */
    document.addEventListener(
        'DOMContentLoaded',
        function () {

            document
                .querySelectorAll(
                    '.admin-nav-link'
                )
                .forEach(function (link) {

                    link.addEventListener(
                        'click',
                        function () {

                            if (
                                window.innerWidth <= 760
                            ) {

                                const sidebar =
                                    document.getElementById(
                                        'adminSidebar'
                                    );

                                const overlay =
                                    document.getElementById(
                                        'adminSidebarOverlay'
                                    );

                                sidebar.classList.remove(
                                    'open'
                                );

                                overlay.classList.remove(
                                    'open'
                                );
                            }

                        }
                    );

                });

        }
    );

</script>


@stack('scripts')

</body>

</html>