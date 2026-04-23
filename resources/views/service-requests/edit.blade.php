@php View::share('pageTitle', $serviceRequest->reference_code ?? 'Edit Request'); @endphp
<x-guest-layout>
    @php
        $isAdmin = strtoupper((string) auth()->user()?->department) === 'ADMIN';
        $isKmits = strtoupper((string) auth()->user()?->department) === 'KMITS';
        $canModerateChat = auth()->check();
        $canPersonnelChat = auth()->check();
        $isReadOnlyForm = auth()->check();
        $hospitalOfficeMap = [
            'Philippine Heart Center' => 'East Avenue, Quezon City, Metro Manila',
            'National Kidney and Transplant Institute' => 'East Avenue, Quezon City, Metro Manila',
            'Lung Center of the Philippines' => 'Quezon Avenue, Quezon City, Metro Manila',
            'Philippine Children\'s Medical Center' => 'Quezon Avenue, Quezon City, Metro Manila',
            'National Center for Mental Health' => 'Nueve de Febrero, Mandaluyong City, Metro Manila',
            'Research Institute for Tropical Medicine' => '9002 Research Dr, Alabang, Muntinlupa',
            'Amang Rodriguez Memorial Medical Center' => 'Sumulong Hwy, Marikina, Metro Manila',
            'Dr. Jose N. Rodriguez Memorial Hospital and Sanitarium' => 'Tala, Caloocan City, Metro Manila',
            'Jose R. Reyes Memorial Medical Center' => 'Rizal Avenue, Sta. Cruz, Manila',
            'San Lazaro Hospital' => 'Quiricada St, Sta. Cruz, Manila',
            'Tondo Medical Center' => 'Honorio Lopez Blvd, Tondo, Manila',
            'Quirino Memorial Medical Center' => 'Project 4, Quezon City, Metro Manila',
            'East Avenue Medical Center' => 'East Avenue, Diliman, Quezon City',
            'Rizal Medical Center' => 'Pasig Blvd, Pasig, Metro Manila',
            'Las Piñas General Hospital and Satellite Trauma Center' => 'P. Diego Cera Ave, Las Piñas',
            'Valenzuela Medical Center' => 'Padrigal St, Karuhatan, Valenzuela',
            'Philippine General Hospital' => 'Taft Ave, Ermita, Manila',
            'Baguio General Hospital and Medical Center' => 'Gov. Pack Rd, Baguio City, Benguet',
            'Ilocos Training and Regional Medical Center' => 'Parian, San Fernando City, La Union',
            'Mariano Marcos Memorial Hospital and Medical Center' => 'Batac City, Ilocos Norte',
            'Cagayan Valley Medical Center' => 'Carig, Tuguegarao City, Cagayan',
            'Jose B. Lingad Memorial General Hospital' => 'San Fernando, Pampanga',
            'Bicol Regional Hospital and Medical Center' => 'Concepcion Pequeña, Naga City, Camarines Sur',
            'Batangas Medical Center' => 'Bihi-Road, Kumintang Ibaba, Batangas City',
            'Western Visayas Medical Center' => 'Q. Abeto St, Mandurriao, Iloilo City',
            'Vicente Sotto Memorial Medical Center' => 'B. Rodriguez St, Cebu City',
            'Corazon Locsin Montelibano Memorial Regional Hospital' => 'Lacson St, Bacolod City',
            'Eastern Visayas Medical Center' => 'Brgy. Bagasumbol, Tacloban City',
            'Zamboanga City Medical Center' => 'Dr. Evangelista St, Zamboanga City',
            'Northern Mindanao Medical Center' => 'Capitol Compound, Cagayan de Oro City',
            'Southern Philippines Medical Center' => 'J.P. Laurel Ave, Davao City',
            'Cotabato Regional and Medical Center' => 'Sinsuat Ave, Cotabato City',
            'Davao Regional Medical Center' => 'Apokon, Tagum City, Davao del Norte',
            'Maguindanao Provincial Hospital' => 'Shariff Aguak, Maguindanao',
        ];
    @endphp

    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&display=swap');

        .srf-root {
            position: relative;
            z-index: 5;
            font-family: 'DM Sans', sans-serif;
            font-size: 15px;
            color: #000;
        }

        .srf-root .auth-login-topbar {
            gap: 1rem;
            padding: 0.55rem 0.95rem;
        }

        .srf-root .auth-login-brand {
            gap: 0.65rem;
        }

        .srf-root .auth-login-brand-logo {
            width: 48px;
            height: 48px;
        }

        .srf-root .auth-login-brand-title {
            font-size: 1.18rem;
            line-height: 1.04;
            letter-spacing: 0.01em;
        }

        .srf-root .auth-login-brand-subtitle {
            display: block;
            font-size: 0.78rem;
            margin-top: 0;
            line-height: 1.12;
            color: #334155;
            max-width: 420px;
            white-space: normal;
        }

        .srf-card {
            background: #fff;
            border-radius: 12px;
            border: 1.5px solid #cbd5e1;
            overflow: hidden;
            box-shadow: 0 2px 16px 0 rgba(15, 118, 110, 0.07);
        }

        .srf-form-header {
            background: #0f766e;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .srf-header-back {
            display: inline-flex;
            align-items: center;
            justify-content: flex-start;
            font-size: 18px;
            font-weight: 700;
            letter-spacing: 0.02em;
            text-transform: none;
            color: #fff;
            text-decoration: none;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.42);
            border-radius: 8px;
            padding: 3px 9px;
            transition: background 0.15s ease, border-color 0.15s ease, transform 0.15s ease;
            flex-shrink: 0;
        }

        .srf-header-back:hover {
            background: rgba(255, 255, 255, 0.22);
            border-color: rgba(255, 255, 255, 0.7);
            transform: translateY(-1px);
        }

        .srf-form-header-text {
            font-size: 21px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #fff;
            margin: 0;
        }

        .srf-form-header-line {
            flex: 1;
            height: 1px;
            background: rgba(255, 255, 255, 0.2);
        }

        .srf-section {
            padding: 16px 20px 0;
        }

        .srf-section-label {
            font-size: 17px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #000;
            margin: 0 0 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .srf-section-label::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }

        .srf-status-block {
            padding-top: 14px;
            padding-bottom: 14px;
            border-bottom: 1px solid #e2e8f0;
            background: #f8fafc;
        }

        .srf-timeline {
            margin-top: 12px;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            background: #fff;
            padding: 12px;
        }

        .srf-timeline-track {
            display: flex;
            gap: 8px;
            overflow-x: auto;
            padding-bottom: 2px;
        }

        .srf-timeline-step {
            min-width: 170px;
            flex: 1;
        }

        .srf-timeline-node-row {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 6px;
        }

        .srf-timeline-node {
            width: 18px;
            height: 18px;
            border-radius: 999px;
            border: 2px solid #cbd5e1;
            background: #fff;
            position: relative;
            flex-shrink: 0;
        }

        .srf-timeline-node.reached {
            border-color: #0f766e;
            background: #0f766e;
        }

        .srf-timeline-node.reached::after {
            content: '';
            position: absolute;
            inset: 4px;
            border-radius: 999px;
            background: #fff;
            opacity: 0.95;
        }

        .srf-timeline-node.current {
            box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.18);
        }

        .srf-timeline-node.rejected {
            border-color: #dc2626;
            background: #dc2626;
        }

        .srf-timeline-node.rejected.current {
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.2);
        }

        .srf-timeline-link {
            height: 2px;
            background: #e2e8f0;
            flex: 1;
            border-radius: 999px;
        }

        .srf-timeline-link.active {
            background: #0f766e;
        }

        .srf-timeline-link.danger {
            background: #dc2626;
        }

        .srf-timeline-label {
            margin: 0;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: #334155;
        }

        .srf-timeline-time {
            margin: 2px 0 0;
            font-size: 11px;
            color: #64748b;
            font-weight: 600;
            white-space: nowrap;
        }

        .srf-table {
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            overflow: hidden;
        }

        .srf-table td {
            border-color: #e2e8f0 !important;
            vertical-align: top;
        }

        .srf-table input[type="text"],
        .srf-table input[type="date"],
        .srf-table input[type="time"],
        .srf-table select,
        .srf-table textarea {
            font-family: 'DM Sans', sans-serif !important;
            font-size: 13px !important;
            color: #0f172a !important;
            font-weight: 600 !important;
            background: #f8fafc !important;
            border: 1.5px solid #e2e8f0 !important;
            border-radius: 6px !important;
            padding: 6px 10px !important;
            outline: none !important;
            transition: border-color 0.18s, background 0.18s, box-shadow 0.18s !important;
        }

        .srf-table input[type="text"]:focus,
        .srf-table input[type="date"]:focus,
        .srf-table input[type="time"]:focus,
        .srf-table select:focus,
        .srf-table textarea:focus {
            border-color: #0f766e !important;
            background: #fff !important;
            box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.1) !important;
        }

        .srf-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 20px;
            background: #f8fafc;
            border-top: 1.5px solid #e2e8f0;
        }

        .srf-btn-back {
            font-family: 'DM Sans', sans-serif;
            font-size: 15px;
            font-weight: 700;
            color: #475569;
            background: #fff;
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 22px;
            text-decoration: none;
            transition: border-color 0.18s, color 0.18s;
        }

        .srf-btn-back:hover {
            border-color: #94a3b8;
            color: #1e293b;
        }

        .srf-btn-back svg {
            width: 16px;
            height: 16px;
        }

        .srf-btn-submit {
            font-family: 'DM Sans', sans-serif;
            font-size: 15px;
            font-weight: 700;
            color: #fff;
            background: #0f766e;
            border: none;
            border-radius: 8px;
            padding: 11px 26px;
            cursor: pointer;
            letter-spacing: 0.03em;
            transition: background 0.18s, box-shadow 0.18s;
        }

        .srf-btn-submit:hover {
            background: #134e4a;
            box-shadow: 0 4px 12px rgba(15, 118, 110, 0.25);
        }

        .srf-chat-list {
            display: flex;
            flex-direction: column;
            gap: 0.55rem;
            max-height: 280px;
            overflow-y: auto;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            background: #f8fafc;
            padding: 0.8rem;
        }

        .srf-chat-item {
            display: flex;
        }

        .srf-chat-item.admin {
            justify-content: flex-end;
        }

        .srf-chat-item.requestor {
            justify-content: flex-start;
        }

        .srf-chat-bubble {
            max-width: min(680px, 92%);
            border-radius: 10px;
            border: 1px solid #cbd5e1;
            padding: 0.5rem 0.72rem;
            background: #fff;
        }

        .srf-chat-bubble.admin {
            background: #ecfeff;
            border-color: #99f6e4;
        }

        .srf-chat-bubble.requestor {
            background: #fff;
            border-color: #cbd5e1;
        }

        .srf-chat-meta {
            margin: 0 0 0.2rem;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: #64748b;
        }

        .srf-chat-text {
            margin: 0;
            font-size: 13px;
            color: #0f172a;
            white-space: pre-wrap;
            word-break: break-word;
        }

        .srf-signature-watermark-wrap {
            position: relative;
            display: inline-block;
        }

        .srf-signature-watermark-wrap::after {
            content: 'VIEW ONLY';
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            letter-spacing: 0.08em;
            font-weight: 700;
            color: rgba(15, 23, 42, 0.4);
            background: rgba(255, 255, 255, 0.14);
            pointer-events: none;
            text-transform: uppercase;
        }

        .srf-log-signature-cell {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .srf-log-signature-trigger {
            width: 100%;
            min-height: 42px;
            border: 1px dashed #94a3b8;
            border-radius: 6px;
            background: #f8fafc;
            padding: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            overflow: hidden;
            transition: border-color 0.15s ease, background 0.15s ease;
        }

        .srf-log-signature-trigger:hover {
            border-color: #0f766e;
            background: #f0fdfa;
        }

        .srf-log-signature-preview {
            width: 100%;
            max-height: 34px;
            object-fit: contain;
        }

        .srf-log-signature-placeholder {
            font-size: 11px;
            font-weight: 600;
            color: #64748b;
            text-align: center;
        }

        .srf-log-signature-clear {
            align-self: flex-end;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            background: #fff;
            color: #475569;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            padding: 2px 8px;
            cursor: pointer;
        }

        .srf-log-signature-clear:hover {
            border-color: #94a3b8;
            color: #0f172a;
        }

        .srf-log-signature-modal {
            position: fixed;
            inset: 0;
            z-index: 140;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            background: rgba(2, 6, 23, 0.78);
            backdrop-filter: blur(2px);
        }

        .srf-log-signature-modal.open {
            display: flex;
        }

        .srf-log-signature-dialog {
            width: min(900px, 100%);
            height: min(72vh, 620px);
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 24px 50px rgba(15, 23, 42, 0.35);
            padding: 12px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .srf-log-signature-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            padding-bottom: 8px;
            border-bottom: 1px solid #e2e8f0;
        }

        .srf-log-signature-title {
            margin: 0;
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
            letter-spacing: 0.03em;
            text-transform: uppercase;
        }

        .srf-log-signature-close {
            width: 32px;
            height: 32px;
            border: 1px solid #cbd5e1;
            border-radius: 999px;
            background: #fff;
            color: #0f172a;
            font-size: 18px;
            line-height: 1;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .srf-log-signature-close:hover {
            background: #f8fafc;
        }

        .srf-log-signature-canvas-wrap {
            flex: 1;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            overflow: hidden;
            background: #f8fafc;
        }

        .srf-log-signature-canvas {
            width: 100%;
            height: 100%;
            display: block;
            background: #fff;
            cursor: default;
            touch-action: none;
        }

        .srf-log-signature-actions {
            display: flex;
            justify-content: flex-end;
            gap: 8px;
        }

        .srf-log-signature-btn {
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            background: #fff;
            color: #0f172a;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.03em;
            text-transform: uppercase;
            padding: 7px 12px;
            cursor: pointer;
        }

        .srf-log-signature-btn:hover {
            background: #f8fafc;
            border-color: #94a3b8;
        }

        .srf-log-signature-btn.primary {
            background: #0f766e;
            border-color: #0f766e;
            color: #fff;
        }

        .srf-log-signature-btn.primary:hover {
            background: #115e59;
            border-color: #115e59;
        }

        .srf-chat-attachment {
            margin-top: 0.45rem;
            display: inline-block;
            max-width: min(360px, 100%);
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #cbd5e1;
            background: #fff;
            padding: 0;
            cursor: pointer;
        }

        .srf-chat-attachment img {
            display: block;
            width: 100%;
            height: auto;
            max-height: 300px;
            object-fit: cover;
            cursor: pointer;
        }

        .srf-image-modal {
            position: fixed;
            inset: 0;
            z-index: 130;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            background: rgba(2, 6, 23, 0.82);
            backdrop-filter: blur(2px);
        }

        .srf-image-modal.open {
            display: flex;
        }

        .srf-image-modal-content {
            position: relative;
            width: 100%;
            max-width: 960px;
        }

        .srf-image-modal-content img {
            display: block;
            width: 100%;
            max-height: 88vh;
            object-fit: contain;
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            background: #020617;
        }

        .srf-image-modal-close {
            position: absolute;
            top: -14px;
            right: -14px;
            width: 34px;
            height: 34px;
            border: 0;
            border-radius: 999px;
            background: #fff;
            color: #0f172a;
            font-size: 22px;
            line-height: 1;
            cursor: pointer;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.28);
        }

        .srf-print-preview-backdrop {
            position: fixed;
            inset: 0;
            z-index: 150;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            background: rgba(2, 6, 23, 0.72);
            backdrop-filter: blur(2px);
        }

        .srf-print-preview-backdrop.open {
            display: flex;
        }

        .srf-print-preview-dialog {
            width: min(1200px, 100%);
            height: min(92vh, 900px);
            background: #fff;
            border: 1px solid #cbd5e1;
            border-radius: 14px;
            box-shadow: 0 24px 54px rgba(15, 23, 42, 0.35);
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .srf-print-preview-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            padding: 10px 14px;
            border-bottom: 1px solid #e2e8f0;
            background: #f8fafc;
        }

        .srf-print-preview-title {
            margin: 0;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: #0f172a;
        }

        .srf-print-preview-close {
            width: 32px;
            height: 32px;
            border: 1px solid #cbd5e1;
            border-radius: 999px;
            background: #fff;
            color: #0f172a;
            font-size: 18px;
            line-height: 1;
            cursor: pointer;
        }

        .srf-print-preview-close:hover {
            background: #f1f5f9;
        }

        .srf-print-preview-frame {
            width: 100%;
            flex: 1;
            border: 0;
            background: #e2e8f0;
        }

        .srf-print-preview-actions {
            display: flex;
            justify-content: flex-end;
            gap: 8px;
            padding: 10px 14px;
            border-top: 1px solid #e2e8f0;
            background: #fff;
        }

        .srf-print-preview-btn {
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            background: #fff;
            color: #0f172a;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            padding: 7px 12px;
            cursor: pointer;
            text-decoration: none;
        }

        .srf-print-preview-btn:hover {
            background: #f8fafc;
        }

        .srf-print-preview-btn.primary {
            background: #0f766e;
            border-color: #0f766e;
            color: #fff;
        }

        .srf-print-preview-btn.primary:hover {
            background: #115e59;
            border-color: #115e59;
        }

        .srf-chat-locked {
            margin-top: 0.7rem;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            background: #f8fafc;
            padding: 0.55rem 0.72rem;
            font-size: 12px;
            color: #334155;
        }

        .srf-notif-wrap {
            position: relative;
        }

        .srf-notif-btn {
            width: 38px;
            height: 38px;
            border-radius: 999px;
            border: 1px solid #cbd5e1;
            background: #fff;
            color: #0f172a;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .srf-notif-btn:hover {
            border-color: #94a3b8;
            background: #f8fafc;
        }

        .srf-notif-btn svg {
            width: 18px;
            height: 18px;
        }

        .srf-notif-count {
            position: absolute;
            top: -4px;
            right: -4px;
            min-width: 18px;
            height: 18px;
            border-radius: 999px;
            background: #dc2626;
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            line-height: 18px;
            text-align: center;
            padding: 0 5px;
            border: 2px solid #fff;
        }

        .srf-notif-panel {
            position: absolute;
            top: 46px;
            right: 0;
            width: min(360px, calc(100vw - 24px));
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 18px 36px rgba(15,23,42,0.25);
            overflow: hidden;
            z-index: 85;
        }

        .srf-notif-panel-head {
            padding: 10px 12px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #0f172a;
            border-bottom: 1px solid #e2e8f0;
            background: #f8fafc;
        }

        .srf-notif-search-wrap {
            padding: 8px 10px;
            border-bottom: 1px solid #e2e8f0;
            background: #fff;
        }

        .srf-notif-search {
            width: 100%;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            padding: 6px 10px;
            font-size: 12px;
            color: #0f172a;
            background: #f8fafc;
            outline: none;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }

        .srf-notif-search:focus {
            border-color: #0f766e;
            box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.12);
            background: #fff;
        }

        .srf-notif-list {
            max-height: 300px;
            overflow-y: auto;
            padding: 8px;
            display: flex;
            flex-direction: column;
            gap: 7px;
        }

        .srf-notif-empty {
            margin: 0;
            font-size: 13px;
            color: #64748b;
            padding: 6px 4px;
        }

        .srf-notif-item {
            border: 1px solid #bfdbfe;
            border-radius: 9px;
            background: #eff6ff;
            padding: 8px 10px;
        }

        .srf-notif-item-title {
            margin: 0;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: #1d4ed8;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .srf-notif-item-new {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 32px;
            border-radius: 999px;
            padding: 2px 7px;
            font-size: 10px;
            line-height: 1;
            font-weight: 800;
            color: #fff;
            background: #dc2626;
        }

        .srf-notif-item-text {
            margin: 3px 0 0;
            font-size: 13px;
            font-weight: 600;
            color: #0f172a;
            word-break: break-word;
        }

        .srf-notif-item-time {
            margin: 4px 0 0;
            font-size: 11px;
            color: #64748b;
        }

        .srf-status-modal-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.55);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 95;
            padding: 16px;
        }

        .srf-status-modal-backdrop.open {
            display: flex;
        }

        .srf-status-modal {
            width: min(460px, calc(100vw - 32px));
            border-radius: 14px;
            border: 1px solid #d1d5db;
            background: #fff;
            box-shadow: 0 24px 50px rgba(15, 23, 42, 0.35);
            overflow: hidden;
        }

        .srf-status-modal-head {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 14px 16px 12px;
            border-bottom: 1px solid #e5e7eb;
            background: #f8fafc;
        }

        .srf-status-modal-icon {
            width: 30px;
            height: 30px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 16px;
            flex-shrink: 0;
        }

        .srf-status-modal-icon.pending {
            background: #fef3c7;
            color: #92400e;
        }

        .srf-status-modal-icon.approved {
            background: #dcfce7;
            color: #166534;
        }

        .srf-status-modal-icon.rejected {
            background: #fee2e2;
            color: #b91c1c;
        }

        .srf-status-modal-title {
            margin: 0;
            font-size: 16px;
            font-weight: 700;
            color: #0f172a;
        }

        .srf-status-modal-body {
            padding: 14px 16px;
        }

        .srf-status-modal-message {
            margin: 0;
            font-size: 14px;
            line-height: 1.5;
            color: #334155;
            white-space: pre-line;
        }

        .srf-status-modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 8px;
            padding: 0 16px 16px;
        }

        .srf-status-modal-cancel,
        .srf-status-modal-confirm {
            border-radius: 9px;
            border: 1px solid #cbd5e1;
            padding: 8px 14px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            cursor: pointer;
        }

        .srf-status-modal-cancel {
            background: #fff;
            color: #475569;
        }

        .srf-status-modal-cancel:hover {
            background: #f8fafc;
            border-color: #94a3b8;
        }

        .srf-status-modal-confirm {
            background: #0f766e;
            border-color: #0f766e;
            color: #fff;
        }

        .srf-status-modal-confirm:hover {
            background: #115e59;
            border-color: #115e59;
        }

        .srf-toast-stack {
            position: fixed;
            top: 18px;
            right: 18px;
            z-index: 80;
            display: flex;
            flex-direction: column;
            gap: 8px;
            pointer-events: none;
        }

        .srf-toast {
            min-width: 260px;
            max-width: min(420px, calc(100vw - 24px));
            border-radius: 10px;
            border: 1px solid #99f6e4;
            background: #ecfeff;
            color: #0f172a;
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.2);
            padding: 10px 12px;
            pointer-events: auto;
            animation: srf-toast-in 180ms ease-out;
        }

        .srf-toast-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #0f766e;
            margin: 0 0 3px;
        }

        .srf-toast-text {
            margin: 0;
            font-size: 13px;
            font-weight: 600;
            color: #0f172a;
        }

        @keyframes srf-toast-in {
            from {
                transform: translateY(-8px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .srf-sticky-bar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 90;
            display: none;
            align-items: center;
            gap: 10px;
            padding: 8px 20px;
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(10px);
            border-bottom: 1.5px solid #e2e8f0;
            box-shadow: 0 4px 20px rgba(15, 23, 42, 0.1);
            animation: srf-sticky-slide-in 200ms ease-out;
        }

        .srf-sticky-bar.visible {
            display: flex;
        }

        @keyframes srf-sticky-slide-in {
            from { transform: translateY(-100%); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .srf-sticky-ref {
            font-size: 13px;
            font-weight: 700;
            color: #0f172a;
            letter-spacing: 0.02em;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 220px;
        }

        .srf-sticky-status {
            display: inline-flex;
            border-radius: 999px;
            border: 1px solid;
            padding: 2px 10px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .srf-sticky-actions {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .srf-scroll-top {
            position: fixed;
            bottom: 28px;
            right: 24px;
            z-index: 88;
            width: 44px;
            height: 44px;
            border-radius: 999px;
            border: 1.5px solid #cbd5e1;
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(8px);
            color: #0f172a;
            box-shadow: 0 6px 20px rgba(15, 23, 42, 0.15);
            cursor: pointer;
            display: none;
            align-items: center;
            justify-content: center;
            transition: background 0.15s ease, border-color 0.15s ease, transform 0.15s ease;
        }

        .srf-scroll-top.visible {
            display: inline-flex;
        }

        .srf-scroll-top:hover {
            background: #f0fdfa;
            border-color: #0f766e;
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(15, 118, 110, 0.2);
        }

        .srf-scroll-top svg {
            width: 20px;
            height: 20px;
        }
    </style>

    <div class="srf-root">

    <header class="auth-login-topbar">
        <div class="auth-login-brand">
            <img src="{{ asset('images/dohlogo.svg') }}" alt="DOH Logo" class="auth-login-brand-logo">
            <div>
                <h1 class="auth-login-brand-title">KMITS</h1>
                <p class="auth-login-brand-subtitle">Knowledge Management and Information Technology Service</p>
            </div>
        </div>

        <div class="auth-login-top-actions">
            @if ($canModerateChat)
                <div class="srf-notif-wrap" id="admin-chat-notif-wrap">
                    <button type="button" class="srf-notif-btn" id="admin-chat-notif-toggle" aria-label="View notifications">
                        <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path d="M10 2a5.5 5.5 0 00-5.5 5.5v2.8L3 12.5h14l-1.5-2.2V7.5A5.5 5.5 0 0010 2z"></path>
                            <path d="M8.5 15.8a1.5 1.5 0 003 0"></path>
                        </svg>
                        <span class="srf-notif-count hidden" id="admin-chat-notif-count">0</span>
                    </button>

                    <div class="srf-notif-panel hidden" id="admin-chat-notif-panel">
                        <div class="srf-notif-panel-head">Notifications</div>
                        <div class="srf-notif-search-wrap">
                            <input id="admin-chat-notif-search" name="notification_search" type="text" class="srf-notif-search" placeholder="Search notifications..." autocomplete="off">
                        </div>
                        <div class="srf-notif-list" id="admin-chat-notif-list">
                            <p class="srf-notif-empty" id="admin-chat-notif-empty">No notifications yet.</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </header>

    <section style="max-width: 1300px; margin: 1.5rem auto; padding: 0 1rem 2rem;">
    @if (session('status'))
        <div class="mb-3 rounded-xl border border-emerald-300 bg-emerald-50 px-3 py-2 text-sm font-semibold text-emerald-700">
            {{ session('status') }}
        </div>
    @endif

    <div class="mb-3 hidden rounded-xl border border-sky-300 bg-sky-50 px-3 py-2 text-sm font-semibold text-sky-800" data-admin-live-notice></div>

    <div class="srf-card">
        <div class="srf-form-header">
            <a href="{{ route('service-requests.index') }}" class="srf-header-back" aria-label="Back to Service Requests list">&larr; Back</a>
            <span class="srf-form-header-text">Service Request Form</span>
            <div class="srf-form-header-line"></div>
        </div>

        <div class="srf-section srf-status-block">
            <div class="flex flex-wrap items-center gap-3">
                <p class="text-sm font-semibold text-slate-700">Status :</p>
                @php
                    $currentStatusValue = strtolower((string) $serviceRequest->status);
                    $showDecisionButtons = in_array($currentStatusValue, ['pending', 'checking', 'rejected'], true);
                    $statusClasses = match ($serviceRequest->status) {
                        'checking' => 'border-sky-300 bg-sky-100 text-sky-800',
                        'approved' => 'border-emerald-300 bg-emerald-100 text-emerald-800',
                        'completed', 'closed' => 'border-teal-300 bg-teal-100 text-teal-800',
                        'rejected' => 'border-rose-300 bg-rose-100 text-rose-800',
                        default => 'border-amber-300 bg-amber-100 text-amber-800',
                    };
                @endphp
                <span class="inline-flex rounded-full border px-3 py-1 text-xs font-semibold uppercase {{ $statusClasses }}">
                    {{ $serviceRequest->status }}
                </span>

                @if ($canModerateChat)
                    <div class="ms-auto flex flex-wrap items-center gap-2">
                        <a id="admin-print-button" href="{{ route('service-requests.print', $serviceRequest) }}" class="rounded-xl border border-slate-300 bg-slate-50 px-5 py-2.5 text-sm font-bold uppercase tracking-[0.06em] text-slate-800 transition hover:bg-slate-100">Print</a>
                        <form method="POST" action="{{ route('service-requests.update-status', $serviceRequest) }}" class="flex flex-wrap items-center gap-2" data-status-action-form>
                            @csrf
                            @method('PATCH')
                            <button type="submit" name="status" value="pending" data-status-target="pending" class="rounded-xl border border-amber-300 bg-amber-50 px-3 py-1.5 text-xs font-semibold uppercase text-amber-800 transition hover:bg-amber-100">Set Pending</button>
                            @if ($showDecisionButtons)
                                <button type="submit" name="status" value="approved" data-status-target="approved" class="rounded-xl border border-emerald-300 bg-emerald-50 px-3 py-1.5 text-xs font-semibold uppercase text-emerald-800 transition hover:bg-emerald-100">Approve</button>
                                <button type="submit" name="status" value="rejected" data-status-target="rejected" class="rounded-xl border border-rose-300 bg-rose-50 px-3 py-1.5 text-xs font-semibold uppercase text-rose-800 transition hover:bg-rose-100">Reject</button>
                            @endif
                        </form>
                    </div>
                @endif
            </div>

            @php
                $resolvedStatus = strtolower((string) $serviceRequest->status);
                if (in_array($resolvedStatus, ['completed', 'closed'], true)) {
                    $resolvedStatus = filled($serviceRequest->approved_at)
                        ? 'approved'
                        : (filled($serviceRequest->rejected_at) ? 'rejected' : 'checking');
                }

                $timelineSteps = [
                    [
                        'key' => 'pending',
                        'label' => 'Pending',
                        'at' => $serviceRequest->pending_at ?? $serviceRequest->created_at,
                    ],
                    [
                        'key' => 'checking',
                        'label' => 'Checking',
                        'at' => $serviceRequest->checking_at,
                    ],
                ];

                if ($resolvedStatus === 'approved') {
                    $timelineSteps[] = [
                        'key' => 'approved',
                        'label' => 'Approved',
                        'at' => $serviceRequest->approved_at,
                    ];
                } elseif ($resolvedStatus === 'rejected') {
                    $timelineSteps[] = [
                        'key' => 'rejected',
                        'label' => 'Rejected',
                        'at' => $serviceRequest->rejected_at,
                    ];
                } else {
                    $timelineSteps[] = [
                        'key' => 'approved',
                        'label' => 'Approved',
                        'at' => $serviceRequest->approved_at,
                    ];

                    $timelineSteps[] = [
                        'key' => 'rejected',
                        'label' => 'Rejected',
                        'at' => $serviceRequest->rejected_at,
                    ];
                }

                $isStepReached = static function (string $stepKey) use ($serviceRequest, $resolvedStatus): bool {
                    return match ($stepKey) {
                        'pending' => true,
                        'checking' => filled($serviceRequest->checking_at) || in_array($resolvedStatus, ['checking', 'approved', 'rejected'], true),
                        'approved' => filled($serviceRequest->approved_at) || $resolvedStatus === 'approved',
                        'rejected' => filled($serviceRequest->rejected_at) || $resolvedStatus === 'rejected',
                        default => false,
                    };
                };
            @endphp

            <div class="srf-timeline" aria-label="Request status timeline">
                <div class="srf-timeline-track">
                    @foreach ($timelineSteps as $index => $step)
                        @php
                            $stepReached = $isStepReached($step['key']);
                            $stepCurrent = $resolvedStatus === $step['key'];
                            $stepTimeLabel = filled($step['at'])
                                ? \Illuminate\Support\Carbon::parse($step['at'])->format('M d, Y h:i A')
                                : '--';

                            $nextStep = $timelineSteps[$index + 1] ?? null;
                            $linkActive = $nextStep ? $isStepReached($nextStep['key']) : false;
                            $linkDanger = $nextStep
                                && $nextStep['key'] === 'rejected'
                                && $isStepReached('rejected');
                        @endphp

                        <div class="srf-timeline-step">
                            <div class="srf-timeline-node-row">
                                <span class="srf-timeline-node {{ $stepReached ? 'reached' : '' }} {{ $step['key'] === 'rejected' && $stepReached ? 'rejected' : '' }} {{ $stepCurrent ? 'current' : '' }}"></span>
                                @if ($nextStep)
                                    <span class="srf-timeline-link {{ $linkActive ? 'active' : '' }} {{ $linkDanger ? 'danger' : '' }}"></span>
                                @endif
                            </div>
                            <p class="srf-timeline-label">{{ $step['label'] }}</p>
                            <p class="srf-timeline-time">{{ $stepTimeLabel }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="overflow-x-auto bg-white">
            <form method="POST" action="{{ route('service-requests.update', $serviceRequest) }}" enctype="multipart/form-data" class="min-w-[1040px] space-y-0">
                @csrf
                @method('PUT')

                <fieldset @if ($isReadOnlyForm) disabled @endif>

                <div class="px-4 pb-3">
                    <p class="srf-section-label">Request Information</p>
                    <table class="srf-table w-full border-collapse text-[12px] text-slate-900">
                        <tr>
                            <td class="border border-slate-400 px-2 py-1 font-semibold">Reference Code :
                                <span class="inline-block min-w-64 border-b border-slate-400 px-1 py-0.5 text-center">{{ $serviceRequest->reference_code }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="border border-slate-400 px-2 py-1">Department for Reference :
                                @php
                                    $displayDepartmentCode = trim((string) old('department_code', $serviceRequest->department_code));
                                @endphp
                                <span class="inline-block min-w-40 border-b border-slate-400 px-1 py-0.5 text-center">{{ $displayDepartmentCode !== '' ? $displayDepartmentCode : 'N/A' }}</span>
                                <input type="hidden" id="department_code" name="department_code" value="{{ old('department_code', $serviceRequest->department_code) }}">
                                <x-input-error :messages="$errors->get('department_code')" class="mt-1" />
                            </td>
                        </tr>
                        <tr>
                            <td class="border border-slate-400 px-2 py-1">1) Date/Time of Request (mm/dd/yyyy h:m:s) :
                                <div class="ms-2 inline-flex items-center gap-2 align-middle">
                                    <input id="request_date" name="request_date" type="date" class="inline-block min-h-0 w-[170px] rounded-none border-0 border-b border-slate-400 bg-transparent px-0 py-0 text-[12px] align-middle focus:outline-none focus:ring-0" value="{{ old('request_date', $serviceRequest->request_date->toDateString()) }}" required>
                                    <input name="time_received" type="time" value="{{ old('time_received', $serviceRequest->time_received) }}" class="inline-block min-h-0 w-[130px] rounded-none border-0 border-b border-slate-400 bg-transparent px-0 py-0 text-[12px] align-middle focus:outline-none focus:ring-0">
                                </div>
                                <x-input-error :messages="$errors->get('request_date')" class="mt-1" />
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="px-4 pb-3">
                    <p class="srf-section-label">Requester Details</p>
                    <table class="srf-table w-full border-collapse text-[12px] text-slate-900">
                        <tr>
                            <td class="border border-slate-500 px-2 py-1">2) Request Category :
                                <select id="request_category" name="request_category" class="auth-input !inline-block align-middle !min-h-0 !w-[260px] !rounded-none !border-0 border-b border-slate-200 !bg-transparent px-0 py-0 text-[12px]">
                                    <option value="Technical Assistance" @selected(old('request_category', $serviceRequest->request_category) === 'Technical Assistance')>Technical Assistance</option>
                                    <option value="System Access" @selected(old('request_category', $serviceRequest->request_category) === 'System Access')>System Access</option>
                                    <option value="Network/Internet" @selected(old('request_category', $serviceRequest->request_category) === 'Network/Internet')>Network/Internet</option>
                                    <option value="Hardware Support" @selected(old('request_category', $serviceRequest->request_category) === 'Hardware Support')>Hardware Support</option>
                                    <option value="Software Installation" @selected(old('request_category', $serviceRequest->request_category) === 'Software Installation')>Software Installation</option>
                                    <option value="Data Request" @selected(old('request_category', $serviceRequest->request_category) === 'Data Request')>Data Request</option>
                                    <option value="Others" @selected(old('request_category', $serviceRequest->request_category) === 'Others')>Others</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="border border-slate-400 px-2 py-1">3) Application System Name : <input type="text" name="application_system_name" value="{{ old('application_system_name', $serviceRequest->application_system_name) }}" class="auth-input !inline-block !min-h-0 !w-[320px] !rounded-none !border-0 !bg-transparent px-1 py-0 text-[12px]"></td>
                        </tr>
                        <tr>
                            <td class="border border-slate-400 px-2 py-1">4) Expected Date / Time of Completion :
                                <input type="date" name="expected_completion_date" value="{{ old('expected_completion_date', optional($serviceRequest->expected_completion_date)->toDateString()) }}" class="inline-block min-h-0 w-[170px] rounded-none border-0 border-b border-slate-200 bg-transparent px-0 py-0 text-[12px] align-middle focus:outline-none focus:ring-0">
                                <input type="time" name="expected_completion_time" value="{{ old('expected_completion_time', $serviceRequest->expected_completion_time) }}" class="ms-2 inline-block min-h-0 w-[130px] rounded-none border-0 border-b border-slate-200 bg-transparent px-0 py-0 text-[12px] align-middle focus:outline-none focus:ring-0">
                            </td>
                        </tr>
                        <tr>
                            <td class="border border-slate-400 p-0">
                                <table class="w-full border-collapse table-fixed text-[12px]">
                                    <tr>
                                        <td class="border-0 px-2 py-1" style="width:32%;">5) Name of Contact Person :</td>
                                        <td class="border-0 border-b border-slate-400 px-1 py-1" style="width:17%;">
                                            <input name="contact_last_name" value="{{ old('contact_last_name', $serviceRequest->contact_last_name) }}" class="auth-input !min-h-0 !rounded-none !border-0 !bg-transparent px-0 py-0 text-center text-[12px]" autocomplete="family-name" required>
                                        </td>
                                        <td class="border-0 border-b border-slate-400 px-1 py-1" style="width:17%;">
                                            <input name="contact_first_name" value="{{ old('contact_first_name', $serviceRequest->contact_first_name) }}" class="auth-input !min-h-0 !rounded-none !border-0 !bg-transparent px-0 py-0 text-center text-[12px]" autocomplete="given-name" required>
                                        </td>
                                        <td class="border-0 border-b border-slate-400 px-1 py-1" style="width:17%;">
                                            <input name="contact_middle_name" value="{{ old('contact_middle_name', $serviceRequest->contact_middle_name) }}" class="auth-input !min-h-0 !rounded-none !border-0 !bg-transparent px-0 py-0 text-center text-[12px]" autocomplete="additional-name">
                                        </td>
                                        <td class="border-0 border-b border-slate-400 px-1 py-1" style="width:17%;">
                                            <input type="text" name="contact_suffix_name" value="{{ old('contact_suffix_name', $serviceRequest->contact_suffix_name) }}" class="auth-input !min-h-0 !rounded-none !border-0 !bg-transparent px-0 py-0 text-center text-[12px]" autocomplete="honorific-suffix">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="border-0 px-2 py-1"></td>
                                        <td class="border-0 px-1 py-1 text-center">Last Name</td>
                                        <td class="border-0 px-1 py-1 text-center">First Name</td>
                                        <td class="border-0 px-1 py-1 text-center">Middle Name</td>
                                        <td class="border-0 px-1 py-1 text-center">Suffix Name</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td class="border border-slate-400 px-2 py-1">6) Office :
                                <input id="office" list="hospital-office-options" name="office" value="{{ old('office', $serviceRequest->office) }}" autocomplete="off" class="auth-input !inline-block !min-h-0 !w-[450px] !rounded-none !border-0 !bg-transparent px-1 py-0 text-[12px]" required>
                                <p class="mt-1 text-[11px] text-slate-500">Type or pick from the regional hospital list.</p>
                            </td>
                        </tr>
                        <tr>
                            <td class="border border-slate-400 px-2 py-1">7) Address :
                                <input id="address" name="address" value="{{ old('address', $serviceRequest->address) }}" class="auth-input !inline-block !min-h-0 !w-[450px] !rounded-none !border-0 !bg-transparent px-1 py-0 text-[12px]" autocomplete="street-address" required>
                            </td>
                        </tr>
                        <tr>
                            <td class="border border-slate-400 p-0">
                                <table class="w-full border-collapse table-fixed text-[12px]">
                                    <tr>
                                        <td class="border-0 border-r border-slate-400 px-2 py-1" style="width:23%;">8) Landline :
                                            <input name="landline" value="{{ old('landline', $serviceRequest->landline) }}" inputmode="numeric" pattern="[0-9]*" oninput="this.value=this.value.replace(/[^0-9]/g,'');" class="auth-input !min-h-0 !rounded-none !border-0 !bg-transparent px-0 py-0 text-[12px]" autocomplete="tel">
                                        </td>
                                        <td class="border-0 border-r border-slate-400 px-2 py-1" style="width:23%;">9) Fax No :
                                            <input name="fax_no" value="{{ old('fax_no', $serviceRequest->fax_no) }}" inputmode="numeric" pattern="[0-9]*" oninput="this.value=this.value.replace(/[^0-9]/g,'');" class="auth-input !min-h-0 !rounded-none !border-0 !bg-transparent px-0 py-0 text-[12px]">
                                        </td>
                                        <td class="border-0 border-r border-slate-400 px-2 py-1" style="width:23%;">10) Mobile No :
                                            <input name="mobile_no" value="{{ old('mobile_no', $serviceRequest->mobile_no) }}" inputmode="numeric" pattern="[0-9]*" oninput="this.value=this.value.replace(/[^0-9]/g,'');" class="auth-input !min-h-0 !rounded-none !border-0 !bg-transparent px-0 py-0 text-[12px]" autocomplete="tel-national" required>
                                        </td>
                                        <td class="border-0 px-2 py-1" style="width:31%;">11) Email Address :
                                            <input type="text" name="email_address" value="{{ old('email_address', $serviceRequest->email_address) }}" class="auth-input !min-h-0 !rounded-none !border-0 !bg-transparent px-0 py-0 text-[12px]" autocomplete="email">
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="px-4 pb-3">
                    <p class="srf-section-label">Description of Request</p>
                    <div class="border border-slate-400 border-b-4 px-2 py-1 text-[12px] font-semibold">12) DESCRIPTION OF REQUEST : <span class="font-normal italic">(Please clearly write down the details of the request.)</span></div>
                        <div class="border border-t-0 border-slate-400 border-b-4 px-2 py-1">
                            <textarea name="description_request" style="height: 240px; min-height: 240px;" class="auth-input !h-[240px] !min-h-[240px] !rounded-none !border-0 !bg-transparent px-0 py-0 text-[12px]" required>{{ old('description_request', $serviceRequest->description_request) }}</textarea>

                            @if ($canModerateChat)
                                <div class="mt-3 border-t border-slate-300 pt-2">
                                    <p class="text-[12px] font-semibold text-slate-700">Uploaded Photos</p>

                                    <div id="uploaded-photos-content" class="mt-2">
                                        @if (is_array($serviceRequest->description_photos) && count($serviceRequest->description_photos) > 0)
                                            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 md:grid-cols-3">
                                                @foreach ($serviceRequest->description_photos as $photoPath)
                                                    <a
                                                        href="{{ \Illuminate\Support\Facades\Storage::url($photoPath) }}"
                                                        class="block overflow-hidden rounded-lg border border-slate-300 bg-white"
                                                        data-uploaded-photo-trigger
                                                        data-photo-src="{{ \Illuminate\Support\Facades\Storage::url($photoPath) }}"
                                                        data-photo-alt="Service Request Photo"
                                                    >
                                                        <img src="{{ \Illuminate\Support\Facades\Storage::url($photoPath) }}" alt="Service Request Photo" class="h-32 w-full object-cover">
                                                    </a>
                                                @endforeach
                                            </div>
                                        @else
                                            <p class="rounded-md border border-slate-200 bg-slate-50 px-3 py-2 text-[12px] text-slate-600">No uploaded photos for this request yet.</p>
                                        @endif
                                    </div>
                                </div>
                            @endif
                    </div>
                    <x-input-error :messages="$errors->get('description_request')" class="mt-1" />
                </div>

                <div class="px-4 pb-3">
                    <p class="srf-section-label">Approved By</p>
                    <table class="srf-table w-full border-collapse text-[12px] text-slate-900">
                        <tr>
                            <td class="w-48 border border-slate-400 px-2 py-1 font-semibold">13) APPROVED BY :</td>
                            <td class="border border-slate-400 px-2 py-1">
                                <div class="grid grid-cols-10 gap-3">
                                    <div class="col-span-6">
                                        @php
                                            $approvedSignatureUrl = trim((string) ($serviceRequest->approved_by_signature ?? '')) !== ''
                                                ? route('service-requests.signature.approved', [
                                                    'serviceRequest' => $serviceRequest,
                                                    'token' => (string) ($signatureViewToken ?? ''),
                                                ])
                                                : '';
                                        @endphp

                                        @if (! $isReadOnlyForm)
                                            <div class="mb-0 rounded-md border border-slate-300 bg-slate-50 p-1 pb-0">
                                                <div class="mb-1 flex flex-wrap items-center gap-3 text-[11px] text-slate-700">
                                                    <label class="inline-flex items-center gap-1">
                                                        <input type="radio" name="approved_by_signature_mode" value="draw" @checked(old('approved_by_signature_mode', 'draw') === 'draw')>
                                                        Draw Signature
                                                    </label>
                                                    <label class="inline-flex items-center gap-1">
                                                        <input type="radio" name="approved_by_signature_mode" value="upload" @checked(old('approved_by_signature_mode') === 'upload')>
                                                        Upload Signature
                                                    </label>
                                                </div>

                                                @if ($approvedSignatureUrl !== '' && old('approved_by_signature_drawn') === null)
                                                    <div class="mb-2">
                                                        <p class="mb-1 text-[11px] text-slate-600">Current Signature</p>
                                                        <div class="srf-signature-watermark-wrap">
                                                            <img src="{{ $approvedSignatureUrl }}" alt="Current Signature" draggable="false" class="h-16 rounded border border-slate-300 bg-white px-2 py-1" style="user-select:none; -webkit-user-drag:none;">
                                                        </div>
                                                    </div>
                                                @endif

                                                <div id="edit-signature-draw-wrap" class="space-y-1">
                                                    <canvas id="edit-signature-canvas" class="h-24 w-full rounded border border-slate-300 bg-white"></canvas>
                                                    <input type="hidden" name="approved_by_signature_drawn" id="edit-signature-drawn" value="{{ old('approved_by_signature_drawn') }}">
                                                    <input type="hidden" name="approved_by_signature_clear" id="edit-signature-clear-flag" value="0">
                                                    <button type="button" id="edit-signature-clear" class="rounded border border-slate-300 bg-white px-2 py-1 text-[11px] font-semibold text-slate-700">Clear</button>
                                                </div>

                                                <div id="edit-signature-upload-wrap" class="hidden">
                                                    <input type="file" name="approved_by_signature_upload" accept="image/*" class="block w-full text-[11px] text-slate-700 file:mr-2 file:rounded-md file:border-0 file:bg-slate-800 file:px-2 file:py-1 file:text-[11px] file:font-medium file:text-white">
                                                </div>

                                                <x-input-error :messages="$errors->get('approved_by_signature_upload')" class="mt-1" />
                                                <x-input-error :messages="$errors->get('approved_by_signature_drawn')" class="mt-1" />
                                            </div>
                                        @else
                                            <div class="mb-1 rounded-md border border-slate-300 bg-slate-50 p-2">
                                                <p class="text-[11px] font-semibold text-slate-700">Requester Signature (Read Only)</p>
                                                @if ($approvedSignatureUrl !== '')
                                                    <p class="mt-1 text-[11px] text-slate-500">Signature hidden in this edit view. Use Print Status Report to see the signature.</p>
                                                @else
                                                    <p class="mt-1 text-[11px] text-slate-500">No signature provided.</p>
                                                @endif
                                            </div>
                                        @endif

                                        <input name="approved_by_name" value="{{ old('approved_by_name', $serviceRequest->approved_by_name) }}" class="auth-input !-mt-2 !min-h-0 !rounded-none !border-0 border-b border-slate-400 !bg-transparent px-0 py-0 text-[12px]" required>
                                        <p class="text-center">Name &amp; Signature of Head of Office</p>

                                        <input name="approved_by_position" value="{{ old('approved_by_position', $serviceRequest->approved_by_position) }}" class="mt-2 auth-input !min-h-0 !rounded-none !border-0 border-b border-slate-400 !bg-transparent px-0 py-0 text-[12px]" required>
                                        <p class="text-center">Position</p>
                                    </div>
                                    <div class="col-span-4">
                                        <input name="approved_date" type="date" value="{{ old('approved_date', $serviceRequest->approved_date->toDateString()) }}" class="auth-input !min-h-0 !rounded-none !border-0 border-b border-slate-400 !bg-transparent px-0 py-0 text-[12px]" required>
                                        <p class="text-center">Date Signed</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
                </fieldset>

                @if ($canModerateChat)
                    @php
                        $existingLogs = $serviceRequest->action_logs ?? [];
                        $logDates = old('action_log_date', collect($existingLogs)->pluck('date')->pad(5, '')->values()->all());
                        $logTimes = old('action_log_time', collect($existingLogs)->pluck('time')->pad(5, '')->values()->all());
                        $logActionDates = old('action_log_action_date', collect($existingLogs)->pluck('action_date')->pad(5, '')->values()->all());
                        $logActionTimes = old('action_log_action_time', collect($existingLogs)->pluck('action_time')->pad(5, '')->values()->all());
                        $logActions = old('action_log_action_taken', collect($existingLogs)->pluck('action_taken')->pad(5, '')->values()->all());
                        $logOfficers = old('action_log_action_officer', collect($existingLogs)->pluck('action_officer')->pad(5, '')->values()->all());
                        $logSignatures = old('action_log_signature_drawn', collect($existingLogs)->pluck('signature')->pad(5, '')->values()->all());
                    @endphp

                    <div class="px-4 pb-3">
                        <div class="rounded-xl border border-slate-300 bg-slate-50 p-3">
                            <h3 class="text-[12px] font-semibold uppercase tracking-[0.08em] text-slate-700">For knowledge management and information technology service only</h3>

                            <div class="mt-4 overflow-x-auto rounded-lg border border-slate-300 bg-white">
                                <table class="min-w-full border-collapse text-[12px] text-slate-800">
                                    <thead class="bg-slate-100">
                                        <tr>
                                            <th class="border border-slate-300 px-2 py-1 text-left">Date (a) Received</th>
                                            <th class="border border-slate-300 px-2 py-1 text-left">Time (b) Received</th>
                                            <th class="border border-slate-300 px-2 py-1 text-left">Date (c) Accomplish</th>
                                            <th class="border border-slate-300 px-2 py-1 text-left">Time (d) Accomplish</th>
                                            <th class="border border-slate-300 px-2 py-1 text-left">Action Taken</th>
                                            <th class="border border-slate-300 px-2 py-1 text-left">Action Officer</th>
                                            <th class="border border-slate-300 px-2 py-1 text-left">Signature</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for ($i = 0; $i < 5; $i++)
                                            @php
                                                $rowSignature = (string) ($logSignatures[$i] ?? '');
                                            @endphp
                                            <tr>
                                                <td class="border border-slate-300 px-2 py-1"><input type="date" name="action_log_date[]" value="{{ $logDates[$i] ?? '' }}" class="w-full rounded-md border-slate-300 text-[12px] shadow-sm focus:border-sky-500 focus:ring-sky-500"></td>
                                                <td class="border border-slate-300 px-2 py-1"><input type="time" name="action_log_time[]" value="{{ $logTimes[$i] ?? '' }}" class="w-full rounded-md border-slate-300 text-[12px] shadow-sm focus:border-sky-500 focus:ring-sky-500"></td>
                                                <td class="border border-slate-300 px-2 py-1"><input type="date" name="action_log_action_date[]" value="{{ $logActionDates[$i] ?? '' }}" class="w-full rounded-md border-slate-300 text-[12px] shadow-sm focus:border-sky-500 focus:ring-sky-500"></td>
                                                <td class="border border-slate-300 px-2 py-1"><input type="time" name="action_log_action_time[]" value="{{ $logActionTimes[$i] ?? '' }}" class="w-full rounded-md border-slate-300 text-[12px] shadow-sm focus:border-sky-500 focus:ring-sky-500"></td>
                                                <td class="border border-slate-300 px-2 py-1"><input type="text" name="action_log_action_taken[]" value="{{ $logActions[$i] ?? '' }}" class="w-full rounded-md border-slate-300 text-[12px] shadow-sm focus:border-sky-500 focus:ring-sky-500"></td>
                                                <td class="border border-slate-300 px-2 py-1"><input type="text" name="action_log_action_officer[]" value="{{ $logOfficers[$i] ?? '' }}" class="w-full rounded-md border-slate-300 text-[12px] shadow-sm focus:border-sky-500 focus:ring-sky-500"></td>
                                                <td class="border border-slate-300 px-2 py-1">
                                                    <input type="hidden" name="action_log_signature_drawn[]" value="{{ $rowSignature }}">
                                                </td>
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-4 grid gap-3 md:grid-cols-2">
                                @php
                                    $notedBySignatureValue = (string) old('noted_by_signature_drawn', $serviceRequest->noted_by_signature);
                                @endphp
                                <label class="block text-[12px] text-slate-700 md:col-span-2">
                                    <span class="font-semibold">13. Noted by (Name of Supervisor)</span>
                                    <input type="text" name="noted_by_name" value="{{ old('noted_by_name', $serviceRequest->noted_by_name) }}" class="mt-1 w-full rounded-md border-slate-300 text-[12px] shadow-sm focus:border-sky-500 focus:ring-sky-500">
                                </label>

                                <label class="block text-[12px] text-slate-700 md:col-span-2">
                                    <span class="font-semibold">Supervisor Signature</span>
                                    <input type="hidden" name="noted_by_signature_drawn" value="{{ $notedBySignatureValue }}">
                                </label>

                                <label class="block text-[12px] text-slate-700">
                                    <span class="font-semibold">14. Position</span>
                                    <input type="text" name="noted_by_position" value="{{ old('noted_by_position', $serviceRequest->noted_by_position) }}" class="mt-1 w-full rounded-md border-slate-300 text-[12px] shadow-sm focus:border-sky-500 focus:ring-sky-500">
                                </label>

                                <label class="block text-[12px] text-slate-700">
                                    <span class="font-semibold">15. Date Signed</span>
                                    <input type="date" name="noted_by_date_signed" value="{{ old('noted_by_date_signed', optional($serviceRequest->noted_by_date_signed)->toDateString()) }}" class="mt-1 w-full rounded-md border-slate-300 text-[12px] shadow-sm focus:border-sky-500 focus:ring-sky-500">
                                </label>
                            </div>

                            <x-input-error :messages="$errors->get('action_log_date')" class="mt-1" />
                            <x-input-error :messages="$errors->get('action_log_time')" class="mt-1" />
                            <x-input-error :messages="$errors->get('action_log_action_date')" class="mt-1" />
                            <x-input-error :messages="$errors->get('action_log_action_time')" class="mt-1" />
                            <x-input-error :messages="$errors->get('action_log_action_taken')" class="mt-1" />
                            <x-input-error :messages="$errors->get('action_log_action_officer')" class="mt-1" />
                            <x-input-error :messages="$errors->get('action_log_signature_drawn')" class="mt-1" />
                            <x-input-error :messages="$errors->get('noted_by_name')" class="mt-1" />
                            <x-input-error :messages="$errors->get('noted_by_signature_drawn')" class="mt-1" />
                            <x-input-error :messages="$errors->get('noted_by_position')" class="mt-1" />
                            <x-input-error :messages="$errors->get('noted_by_date_signed')" class="mt-1" />
                        </div>
                    </div>
                @else
                    <input type="hidden" name="kmits_date" value="{{ old('kmits_date', optional($serviceRequest->kmits_date)->toDateString() ?? now()->toDateString()) }}">
                @endif

                <div class="srf-footer">
                    <a href="{{ route('service-requests.index') }}" class="srf-btn-back">
                        <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path d="M12 4L6 10L12 16"></path>
                        </svg>
                        Back
                    </a>
                    <button type="submit" class="srf-btn-submit">Save / Update Service Request</button>
                </div>
            </form>
        </div>
    </div>
    </section>

    @if ($canPersonnelChat)
        <section style="max-width: 1300px; margin: -0.7rem auto 1.8rem; padding: 0 1rem;">
            <div class="srf-card">
                <div class="srf-form-header">
                    <span class="srf-form-header-text">Requestor and Personnel Chat</span>
                    <div class="srf-form-header-line"></div>
                </div>

                @php
                    $adminChatStatus = strtolower((string) ($serviceRequest->contact_chat_status ?? ''));
                    $isChatPending = $adminChatStatus === 'pending';
                    $isChatAccepted = $adminChatStatus === 'accepted';
                @endphp

                <div class="p-4" data-admin-chat-panel data-admin-chat-status="{{ $adminChatStatus !== '' ? $adminChatStatus : 'none' }}" data-admin-chat-poll-endpoint="{{ route('service-requests.messages.index', $serviceRequest) }}" data-admin-reference-code="{{ $serviceRequest->reference_code }}">
                    <div class="rounded-lg border border-amber-200 bg-amber-50 px-3 py-3 text-sm text-amber-900 {{ $isChatPending ? '' : 'hidden' }}" data-admin-chat-state="pending">
                            <p class="font-semibold">Pending chat request from requestor.</p>
                            <p class="mt-1 text-xs">Turn chat on to unlock messaging.</p>

                            <form method="POST" action="{{ route('service-requests.chat-request.decision', $serviceRequest) }}" class="mt-3 flex flex-wrap gap-2">
                                @csrf
                                <button type="submit" name="decision" value="accepted" class="rounded-lg border border-emerald-300 bg-emerald-600 px-3 py-1.5 text-xs font-bold uppercase tracking-[0.05em] text-white transition hover:bg-emerald-700">Turn Chat On</button>
                            </form>
                    </div>

                    <div class="rounded-lg border border-rose-200 bg-rose-50 px-3 py-3 text-sm text-rose-800 {{ $adminChatStatus === 'rejected' ? '' : 'hidden' }}" data-admin-chat-state="rejected">
                        <p>Last chat request was declined. Wait for the requestor to send a new chat request.</p>
                        <div class="mt-3 flex flex-wrap gap-2">
                            <form method="POST" action="{{ route('service-requests.chat-toggle', $serviceRequest) }}">
                                @csrf
                                <input type="hidden" name="enabled" value="1">
                                <button type="submit" class="rounded-lg border border-emerald-300 bg-emerald-600 px-3 py-1.5 text-xs font-bold uppercase tracking-[0.05em] text-white transition hover:bg-emerald-700">Turn Chat On</button>
                            </form>
                        </div>
                    </div>

                    <div class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-3 text-sm text-slate-700 {{ (! $isChatAccepted && $adminChatStatus !== 'rejected' && ! $isChatPending) ? '' : 'hidden' }}" data-admin-chat-state="none">
                        <p>No chat request yet. Wait for the requestor to send a chat request.</p>
                        <div class="mt-3 flex flex-wrap gap-2">
                            <form method="POST" action="{{ route('service-requests.chat-toggle', $serviceRequest) }}">
                                @csrf
                                <input type="hidden" name="enabled" value="1">
                                <button type="submit" class="rounded-lg border border-emerald-300 bg-emerald-600 px-3 py-1.5 text-xs font-bold uppercase tracking-[0.05em] text-white transition hover:bg-emerald-700">Turn Chat On</button>
                            </form>
                        </div>
                    </div>

                    <div data-admin-chat-state="accepted" class="{{ $isChatAccepted ? '' : 'hidden' }}">
                        <div class="mb-3 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-xs font-semibold uppercase tracking-[0.04em] text-emerald-700" data-admin-chat-accepted-banner>
                            Chat request accepted
                        </div>

                        <form method="POST" action="{{ route('service-requests.chat-toggle', $serviceRequest) }}" class="mb-3 flex justify-end">
                            @csrf
                            <input type="hidden" name="enabled" value="0">
                            <button type="submit" class="rounded-lg border border-rose-300 bg-rose-600 px-3 py-1.5 text-xs font-bold uppercase tracking-[0.05em] text-white transition hover:bg-rose-700">Turn Chat Off</button>
                        </form>

                        <div class="srf-chat-list" data-chat-list data-chat-endpoint="{{ route('service-requests.messages.index', $serviceRequest) }}">
                            @forelse ($chatMessages as $chatMessage)
                                @php
                                    $isAdminMessage = strtolower((string) $chatMessage->sender_type) === 'admin';
                                    $senderLabel = $isAdminMessage
                                        ? ('Admin' . (filled($chatMessage->senderUser?->name) ? ' - ' . $chatMessage->senderUser->name : ''))
                                        : 'Requestor';
                                @endphp

                                <div class="srf-chat-item {{ $isAdminMessage ? 'admin' : 'requestor' }}">
                                    <div class="srf-chat-bubble {{ $isAdminMessage ? 'admin' : 'requestor' }}">
                                        <p class="srf-chat-meta">{{ $senderLabel }} • {{ $chatMessage->created_at?->format('M j, Y g:i A') }}</p>
                                        @if (filled($chatMessage->message))
                                            <p class="srf-chat-text">{{ $chatMessage->message }}</p>
                                        @endif
                                        @if (filled($chatMessage->attachment_path))
                                            <button type="button" class="srf-chat-attachment" data-chat-image-open data-chat-image-src="{{ '/storage/' . ltrim((string) $chatMessage->attachment_path, '/') }}" aria-label="View chat image">
                                                <img src="{{ '/storage/' . ltrim((string) $chatMessage->attachment_path, '/') }}" alt="Chat attachment" loading="lazy">
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-slate-500">No chat messages yet.</p>
                            @endforelse
                        </div>

                        <form method="POST" action="{{ route('service-requests.messages.store', $serviceRequest) }}" class="mt-3" data-chat-enter-form enctype="multipart/form-data">
                            @csrf
                            <label for="admin_chat_message" class="block text-xs font-semibold uppercase tracking-[0.06em] text-slate-600">Reply as Personnel</label>
                            <textarea id="admin_chat_message" name="message" class="mt-1 block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-teal-600 focus:ring-teal-600" rows="3" maxlength="1000">{{ old('message') }}</textarea>
                            <div class="mt-2 flex flex-wrap items-center gap-2">
                                <input type="file" name="attachment" accept="image/*" class="block min-w-[220px] grow text-xs text-slate-700 file:mr-2 file:rounded-md file:border-0 file:bg-slate-800 file:px-2 file:py-1 file:text-xs file:font-medium file:text-white">
                                <button type="submit" class="rounded-lg px-4 py-2 text-xs font-bold uppercase tracking-[0.06em] text-white transition hover:opacity-90" style="background:#0f766e; min-width:88px;">Send</button>
                            </div>
                            <p class="mt-1 hidden text-[11px] text-slate-500" data-chat-attachment-name></p>
                            <x-input-error :messages="$errors->get('message')" class="mt-1" />
                            <x-input-error :messages="$errors->get('attachment')" class="mt-1" />
                            <p class="mt-1 hidden text-xs text-rose-600" data-chat-error></p>
                            <p class="mt-1 text-[11px] text-slate-500">Press Enter to send. Use Shift+Enter for a new line.</p>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <div class="srf-image-modal" data-chat-image-modal>
        <div class="srf-image-modal-content">
            <button type="button" class="srf-image-modal-close" data-chat-image-close aria-label="Close image preview">×</button>
            <img src="" alt="Chat image preview" data-chat-image-preview>
        </div>
    </div>

    <div class="srf-print-preview-backdrop" id="print-preview-modal" aria-hidden="true">
        <div class="srf-print-preview-dialog" role="dialog" aria-modal="true" aria-labelledby="print-preview-title">
            <div class="srf-print-preview-head">
                <h3 class="srf-print-preview-title" id="print-preview-title">Print Preview</h3>
                <button type="button" class="srf-print-preview-close" id="print-preview-close" aria-label="Close print preview">×</button>
            </div>

            <iframe id="print-preview-frame" class="srf-print-preview-frame" title="Service Request Print Preview"></iframe>

            <div class="srf-print-preview-actions">
                <a id="print-preview-open-full" href="#" target="_blank" rel="noopener" class="srf-print-preview-btn">Open Full View</a>
                <button type="button" id="print-preview-signature" class="srf-print-preview-btn">Add Signature</button>
                <button type="button" id="print-preview-signature-smaller" class="srf-print-preview-btn">Signature -</button>
                <button type="button" id="print-preview-signature-larger" class="srf-print-preview-btn">Signature +</button>
                <button type="button" id="print-preview-trigger" class="srf-print-preview-btn primary">Print</button>
            </div>
        </div>
    </div>

    <div class="srf-status-modal-backdrop" id="status-confirm-modal" aria-hidden="true">
        <div class="srf-status-modal" role="dialog" aria-modal="true" aria-labelledby="status-confirm-title">
            <div class="srf-status-modal-head">
                <span class="srf-status-modal-icon pending" id="status-confirm-icon">!</span>
                <h3 class="srf-status-modal-title" id="status-confirm-title">Confirm Action</h3>
            </div>
            <div class="srf-status-modal-body">
                <p class="srf-status-modal-message" id="status-confirm-message">Do you want to continue?</p>
            </div>
            <div class="srf-status-modal-actions">
                <button type="button" class="srf-status-modal-cancel" id="status-confirm-cancel">No</button>
                <button type="button" class="srf-status-modal-confirm" id="status-confirm-accept">Yes</button>
            </div>
        </div>
    </div>

    <div id="uploaded-photo-modal" class="fixed inset-0 z-[120] hidden items-center justify-center bg-slate-950/80 backdrop-blur-2xl px-4" aria-hidden="true">
        <div class="relative w-full max-w-3xl">
            <button
                type="button"
                id="uploaded-photo-modal-close"
                class="absolute z-[121] inline-flex h-11 w-11 items-center justify-center rounded-full border border-slate-300 bg-white text-2xl font-bold leading-none text-slate-900 shadow-lg transition hover:bg-slate-100"
                style="top: -18px; right: -18px;"
                aria-label="Close photo preview"
            >
                ×
            </button>
            <div class="overflow-hidden rounded-2xl border border-white/20 bg-black shadow-2xl">
                <img id="uploaded-photo-modal-image" src="" alt="Uploaded photo preview" class="max-h-[65vh] w-full object-contain">
                <div class="flex justify-end border-t border-white/15 bg-black/70 px-4 py-3">
                    <button
                        type="button"
                        id="uploaded-photo-modal-cancel"
                        class="rounded-lg border border-white/40 bg-white/10 px-3 py-1.5 text-sm font-semibold text-white transition hover:bg-white/20"
                    >
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="srf-log-signature-modal" id="action-log-signature-modal" aria-hidden="true">
        <div class="srf-log-signature-dialog" role="dialog" aria-modal="true" aria-labelledby="action-log-signature-title">
            <div class="srf-log-signature-head">
                <h3 class="srf-log-signature-title" id="action-log-signature-title">Draw Signature</h3>
                <button type="button" class="srf-log-signature-close" id="action-log-signature-close" aria-label="Close signature modal">×</button>
            </div>

            <div class="srf-log-signature-canvas-wrap">
                <canvas id="action-log-signature-canvas" class="srf-log-signature-canvas"></canvas>
            </div>

            <div class="srf-log-signature-actions">
                <button type="button" class="srf-log-signature-btn" id="action-log-signature-clear">Clear</button>
                <button type="button" class="srf-log-signature-btn" id="action-log-signature-cancel">Cancel</button>
                <button type="button" class="srf-log-signature-btn primary" id="action-log-signature-apply">Use Signature</button>
            </div>
        </div>
    </div>

    @if ($canModerateChat)
        <div class="srf-sticky-bar" id="srf-sticky-bar">
            <span class="srf-sticky-ref">{{ $serviceRequest->reference_code }}</span>
            <span class="srf-sticky-status {{ $statusClasses }}">{{ $serviceRequest->status }}</span>
            <div class="srf-sticky-actions">
                <a id="sticky-print-button" href="{{ route('service-requests.print', $serviceRequest) }}" class="rounded-xl border border-slate-300 bg-slate-50 px-4 py-1.5 text-xs font-bold uppercase tracking-[0.06em] text-slate-800 transition hover:bg-slate-100">Print</a>
                <form method="POST" action="{{ route('service-requests.update-status', $serviceRequest) }}" class="flex items-center gap-2" data-status-action-form>
                    @csrf
                    @method('PATCH')
                    <button type="submit" name="status" value="pending" data-status-target="pending" class="rounded-xl border border-amber-300 bg-amber-50 px-3 py-1.5 text-xs font-semibold uppercase text-amber-800 transition hover:bg-amber-100">Set Pending</button>
                    @if ($showDecisionButtons)
                        <button type="submit" name="status" value="approved" data-status-target="approved" class="rounded-xl border border-emerald-300 bg-emerald-50 px-3 py-1.5 text-xs font-semibold uppercase text-emerald-800 transition hover:bg-emerald-100">Approve</button>
                        <button type="submit" name="status" value="rejected" data-status-target="rejected" class="rounded-xl border border-rose-300 bg-rose-50 px-3 py-1.5 text-xs font-semibold uppercase text-rose-800 transition hover:bg-rose-100">Reject</button>
                    @endif
                </form>
            </div>
        </div>
    @endif

    <button type="button" class="srf-scroll-top" id="srf-scroll-top" aria-label="Scroll to top">
        <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M10 15V5"></path><path d="M5 9l5-5 5 5"></path></svg>
    </button>

    </div>

    <datalist id="hospital-office-options">
        @foreach (array_keys($hospitalOfficeMap) as $hospitalOfficeOption)
            <option value="{{ $hospitalOfficeOption }}"></option>
        @endforeach
    </datalist>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const officeInput = document.getElementById('office');
            const addressInput = document.getElementById('address');
            const optionsList = document.getElementById('hospital-office-options');
            const officeAddressMap = @json($hospitalOfficeMap);

            if (!officeInput || !optionsList) {
                return;
            }

            const staticOptions = Object.keys(officeAddressMap);
            const initialDatalistOptions = Array.from(optionsList.querySelectorAll('option')).map(function (option) {
                return option.value;
            });

            if (initialDatalistOptions.length === 0) {
                return;
            }

            const setOptions = function (items) {
                optionsList.innerHTML = '';
                items.forEach(function (item) {
                    const option = document.createElement('option');
                    option.value = item;
                    optionsList.appendChild(option);
                });
            };

            officeInput.addEventListener('input', function () {
                const term = officeInput.value.trim();
                const termLower = term.toLowerCase();

                if (termLower === '') {
                    setOptions(staticOptions.slice(0, 50));
                    return;
                }

                const startsWithMatches = staticOptions.filter(function (item) {
                    return item.toLowerCase().startsWith(termLower);
                });
                const containsMatches = staticOptions.filter(function (item) {
                    return item.toLowerCase().includes(termLower);
                });
                const filtered = (startsWithMatches.length > 0 ? startsWithMatches : containsMatches).slice(0, 50);
                setOptions(filtered);
            });

            const syncAddress = function () {
                if (!addressInput) {
                    return;
                }

                const selectedOffice = officeInput.value.trim();
                const mappedAddress = officeAddressMap[selectedOffice] || '';

                if (mappedAddress !== '') {
                    addressInput.value = mappedAddress;
                }
            };

            officeInput.addEventListener('change', syncAddress);
            officeInput.addEventListener('blur', syncAddress);

            const initAdaptiveDescriptionFont = function () {
                const descriptionTextarea = document.querySelector('textarea[name="description_request"]');
                if (!descriptionTextarea) {
                    return;
                }

                const getFontSize = function (valueLength) {
                    if (valueLength <= 280) return 16;
                    if (valueLength <= 650) return 14;
                    if (valueLength <= 1000) return 13;
                    if (valueLength <= 1450) return 12;
                    return 11;
                };

                const applyAdaptiveSize = function () {
                    const length = descriptionTextarea.value.trim().length;
                    const size = getFontSize(length);
                    descriptionTextarea.style.fontSize = size + 'px';
                    descriptionTextarea.style.lineHeight = size >= 14 ? '1.45' : '1.35';
                };

                descriptionTextarea.addEventListener('input', applyAdaptiveSize);
                applyAdaptiveSize();
            };

            initAdaptiveDescriptionFont();

            const initSignatureInput = function () {
                const modeInputs = document.querySelectorAll('input[name="approved_by_signature_mode"]');
                const drawWrap = document.getElementById('edit-signature-draw-wrap');
                const uploadWrap = document.getElementById('edit-signature-upload-wrap');
                const canvas = document.getElementById('edit-signature-canvas');
                const hiddenDrawn = document.getElementById('edit-signature-drawn');
                const clearFlag = document.getElementById('edit-signature-clear-flag');
                const clearBtn = document.getElementById('edit-signature-clear');

                if (!drawWrap || !uploadWrap || !canvas || !hiddenDrawn) {
                    return;
                }

                const ctx = canvas.getContext('2d');
                if (!ctx) {
                    return;
                }

                const resizeCanvas = function () {
                    const ratio = window.devicePixelRatio || 1;
                    const rect = canvas.getBoundingClientRect();
                    canvas.width = Math.max(1, Math.floor(rect.width * ratio));
                    canvas.height = Math.max(1, Math.floor(rect.height * ratio));
                    ctx.setTransform(ratio, 0, 0, ratio, 0, 0);
                    ctx.lineWidth = 2;
                    ctx.lineCap = 'round';
                    ctx.strokeStyle = '#0f172a';
                };

                resizeCanvas();
                window.addEventListener('resize', resizeCanvas);

                if (hiddenDrawn.value) {
                    const img = new Image();
                    img.onload = function () {
                        ctx.clearRect(0, 0, canvas.width, canvas.height);
                        ctx.drawImage(img, 0, 0, canvas.clientWidth, canvas.clientHeight);
                    };
                    img.src = hiddenDrawn.value;
                }

                let drawing = false;

                const pointFromEvent = function (event) {
                    const rect = canvas.getBoundingClientRect();
                    const source = event.touches ? event.touches[0] : event;
                    return {
                        x: source.clientX - rect.left,
                        y: source.clientY - rect.top,
                    };
                };

                const start = function (event) {
                    drawing = true;
                    const point = pointFromEvent(event);
                    ctx.beginPath();
                    ctx.moveTo(point.x, point.y);
                    event.preventDefault();
                };

                const move = function (event) {
                    if (!drawing) {
                        return;
                    }

                    const point = pointFromEvent(event);
                    ctx.lineTo(point.x, point.y);
                    ctx.stroke();
                    hiddenDrawn.value = canvas.toDataURL('image/png');
                    if (clearFlag) {
                        clearFlag.value = '0';
                    }
                    event.preventDefault();
                };

                const end = function () {
                    drawing = false;
                };

                canvas.addEventListener('mousedown', start);
                canvas.addEventListener('mousemove', move);
                window.addEventListener('mouseup', end);
                canvas.addEventListener('touchstart', start, { passive: false });
                canvas.addEventListener('touchmove', move, { passive: false });
                canvas.addEventListener('touchend', end);

                if (clearBtn) {
                    clearBtn.addEventListener('click', function () {
                        ctx.clearRect(0, 0, canvas.width, canvas.height);
                        hiddenDrawn.value = '';
                        if (clearFlag) {
                            clearFlag.value = '1';
                        }
                    });
                }

                const syncMode = function () {
                    const selected = document.querySelector('input[name="approved_by_signature_mode"]:checked');
                    const mode = selected ? selected.value : 'draw';
                    drawWrap.classList.toggle('hidden', mode !== 'draw');
                    uploadWrap.classList.toggle('hidden', mode !== 'upload');

                    if (mode === 'upload' && clearFlag) {
                        clearFlag.value = '0';
                    }
                };

                modeInputs.forEach(function (input) {
                    input.addEventListener('change', syncMode);
                });
                syncMode();
            };

            const initChatEnterSubmit = function () {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                const adminChatPanel = document.querySelector('[data-admin-chat-panel]');
                const adminLiveNotice = document.querySelector('[data-admin-live-notice]');
                const notifWrap = document.getElementById('admin-chat-notif-wrap');
                const notifToggle = document.getElementById('admin-chat-notif-toggle');
                const notifPanel = document.getElementById('admin-chat-notif-panel');
                const notifList = document.getElementById('admin-chat-notif-list');
                const notifEmpty = document.getElementById('admin-chat-notif-empty');
                const notifCount = document.getElementById('admin-chat-notif-count');
                const notifSearch = document.getElementById('admin-chat-notif-search');
                const notifEndpoint = @json(route('service-requests.notifications'));
                const chatForms = document.querySelectorAll('[data-chat-enter-form]');
                const chatImageModal = document.querySelector('[data-chat-image-modal]');
                const chatImagePreview = chatImageModal ? chatImageModal.querySelector('[data-chat-image-preview]') : null;
                let unreadNotifications = 0;
                let knownNotificationKeys = new Set();
                let notificationItems = [];

                const escapeHtml = function (value) {
                    return String(value)
                        .replace(/&/g, '&amp;')
                        .replace(/</g, '&lt;')
                        .replace(/>/g, '&gt;')
                        .replace(/"/g, '&quot;')
                        .replace(/'/g, '&#039;');
                };

                const normalizeChatState = function (state) {
                    const normalized = String(state || '').toLowerCase();
                    return ['none', 'pending', 'accepted', 'rejected'].includes(normalized) ? normalized : 'none';
                };

                const isFreshNotification = function (item) {
                    const requestedAtUnix = Number(item.requested_at_unix || 0);
                    if (!Number.isFinite(requestedAtUnix) || requestedAtUnix <= 0) {
                        return false;
                    }

                    const ageSeconds = (Date.now() / 1000) - requestedAtUnix;
                    return ageSeconds >= 0 && ageSeconds < 120;
                };

                const getCurrentChatState = function () {
                    if (!adminChatPanel) {
                        return 'none';
                    }

                    return normalizeChatState(adminChatPanel.dataset.adminChatStatus || 'none');
                };

                const setCurrentChatState = function (state) {
                    if (!adminChatPanel) {
                        return;
                    }

                    adminChatPanel.dataset.adminChatStatus = normalizeChatState(state);
                };

                const openChatImageModal = function (src) {
                    if (!chatImageModal || !chatImagePreview || !src) {
                        return;
                    }

                    chatImagePreview.src = src;
                    chatImageModal.classList.add('open');
                    document.body.style.overflow = 'hidden';
                };

                const closeChatImageModal = function () {
                    if (!chatImageModal || !chatImagePreview) {
                        return;
                    }

                    chatImageModal.classList.remove('open');
                    chatImagePreview.src = '';
                    document.body.style.overflow = '';
                };

                document.addEventListener('click', function (event) {
                    const imageOpenTrigger = event.target.closest('[data-chat-image-open]');
                    if (imageOpenTrigger) {
                        const imageSrc = imageOpenTrigger.getAttribute('data-chat-image-src') || '';
                        if (imageSrc !== '') {
                            openChatImageModal(imageSrc);
                        }
                        return;
                    }

                    if (!chatImageModal || !chatImageModal.classList.contains('open')) {
                        return;
                    }

                    if (event.target === chatImageModal || event.target.closest('[data-chat-image-close]')) {
                        closeChatImageModal();
                    }
                });

                document.addEventListener('keydown', function (event) {
                    if (event.key === 'Escape' && chatImageModal && chatImageModal.classList.contains('open')) {
                        closeChatImageModal();
                    }
                });

                const showChatRequestToast = function (message) {
                    let stack = document.getElementById('admin-chat-toast-stack');

                    if (!stack) {
                        stack = document.createElement('div');
                        stack.id = 'admin-chat-toast-stack';
                        stack.className = 'srf-toast-stack';
                        document.body.appendChild(stack);
                    }

                    const toast = document.createElement('div');
                    toast.className = 'srf-toast';
                    toast.innerHTML = '<p class="srf-toast-title">New Chat Request</p><p class="srf-toast-text"></p>';
                    const textNode = toast.querySelector('.srf-toast-text');
                    if (textNode) {
                        textNode.textContent = message;
                    }

                    stack.appendChild(toast);

                    window.setTimeout(function () {
                        toast.remove();
                    }, 4500);
                };

                const updateNotifBadge = function () {
                    if (!notifCount) {
                        return;
                    }

                    if (unreadNotifications > 0) {
                        notifCount.textContent = String(unreadNotifications);
                        notifCount.classList.remove('hidden');
                        return;
                    }

                    notifCount.classList.add('hidden');
                };

                const renderNotificationList = function (items) {
                    if (!notifList || !notifEmpty) {
                        return;
                    }

                    const searchText = notifSearch
                        ? String(notifSearch.value || '').trim().toLowerCase()
                        : '';
                    const normalizedItems = Array.isArray(items) ? items : [];
                    const filteredItems = normalizedItems.filter(function (item) {
                        if (searchText === '') {
                            return true;
                        }

                        const haystack = [
                            item.message || '',
                            item.requested_at_label || '',
                            item.edit_url || '',
                        ].join(' ').toLowerCase();

                        return haystack.includes(searchText);
                    });

                    if (filteredItems.length === 0) {
                        notifList.innerHTML = '';
                        notifEmpty.textContent = searchText === '' ? 'No notifications yet.' : 'No matching notifications.';
                        notifEmpty.classList.remove('hidden');
                        notifList.appendChild(notifEmpty);
                        return;
                    }

                    notifEmpty.classList.add('hidden');
                    notifList.innerHTML = filteredItems.map(function (item) {
                        const message = escapeHtml(item.message || 'Chat request received');
                        const requestedAt = escapeHtml(item.requested_at_label || '');
                        const editUrl = escapeHtml(item.edit_url || '#');
                        const newBadge = isFreshNotification(item) ? '<span class="srf-notif-item-new">NEW</span>' : '';

                        return '<a href="' + editUrl + '" class="srf-notif-item">' +
                            '<p class="srf-notif-item-title">Chat Request' + newBadge + '</p>' +
                            '<p class="srf-notif-item-text">' + message + '</p>' +
                            '<p class="srf-notif-item-time">' + requestedAt + '</p>' +
                            '</a>';
                    }).join('');
                };

                const syncNotificationList = async function (countNewAsUnread) {
                    if (!notifList || notifEndpoint === '') {
                        return;
                    }

                    try {
                        const response = await fetch(notifEndpoint, {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                        });

                        if (!response.ok) {
                            return;
                        }

                        const payload = await response.json();
                        const items = Array.isArray(payload.notifications) ? payload.notifications : [];

                        if (countNewAsUnread) {
                            items.forEach(function (item) {
                                const key = String(item.key || '');
                                if (key !== '' && !knownNotificationKeys.has(key) && isFreshNotification(item)) {
                                    unreadNotifications += 1;
                                }
                            });
                        }

                        knownNotificationKeys = new Set(items.map(function (item) {
                            return String(item.key || '');
                        }));

                        notificationItems = items;
                        renderNotificationList(notificationItems);
                        updateNotifBadge();
                    } catch (error) {
                        // Keep existing notifications when polling fails.
                    }
                };

                if (notifToggle && notifPanel) {
                    if (notifSearch) {
                        notifSearch.addEventListener('input', function () {
                            renderNotificationList(notificationItems);
                        });
                    }

                    notifToggle.addEventListener('click', function () {
                        notifPanel.classList.toggle('hidden');
                        if (!notifPanel.classList.contains('hidden')) {
                            unreadNotifications = 0;
                            updateNotifBadge();
                            syncNotificationList(false);
                        }
                    });

                    document.addEventListener('click', function (event) {
                        if (!notifWrap || notifPanel.classList.contains('hidden')) {
                            return;
                        }

                        if (!notifWrap.contains(event.target)) {
                            notifPanel.classList.add('hidden');
                        }
                    });

                    notifList.addEventListener('click', function (event) {
                        const link = event.target.closest('.srf-notif-item');
                        if (!link) {
                            return;
                        }

                        const href = link.getAttribute('href') || '';
                        if (href === '') {
                            return;
                        }

                        event.preventDefault();
                        window.location.assign(href);
                    });
                }

                const showTopLiveNotice = function (message) {
                    if (!adminLiveNotice) {
                        return;
                    }

                    adminLiveNotice.textContent = message;
                    adminLiveNotice.classList.remove('hidden');

                    window.setTimeout(function () {
                        if (adminLiveNotice.textContent === message) {
                            adminLiveNotice.classList.add('hidden');
                        }
                    }, 8000);
                };

                const syncChatStateUi = function (state) {
                    if (!adminChatPanel) {
                        return;
                    }

                    const normalizedState = normalizeChatState(state);
                    const stateBlocks = adminChatPanel.querySelectorAll('[data-admin-chat-state]');

                    stateBlocks.forEach(function (block) {
                        const blockState = normalizeChatState(block.getAttribute('data-admin-chat-state') || 'none');
                        block.classList.toggle('hidden', blockState !== normalizedState);
                    });

                    setCurrentChatState(normalizedState);
                };

                syncChatStateUi(getCurrentChatState());
                syncNotificationList(true);
                window.setInterval(function () {
                    syncNotificationList(true);
                }, 4000);
                window.setInterval(function () {
                    renderNotificationList(notificationItems);
                }, 15000);

                chatForms.forEach(function (form) {
                    const textarea = form.querySelector('textarea[name="message"]');
                    const attachmentInput = form.querySelector('input[name="attachment"]');
                    const attachmentNameLine = form.querySelector('[data-chat-attachment-name]');
                    const chatSection = form.closest('.p-4');
                    const chatList = chatSection ? chatSection.querySelector('[data-chat-list]') : null;
                    const chatEndpoint = chatList ? chatList.dataset.chatEndpoint : '';
                    const errorBox = form.querySelector('[data-chat-error]');

                    if (!textarea || !chatList || chatEndpoint === '') {
                        return;
                    }

                    const refreshAttachmentLabel = function () {
                        if (!attachmentNameLine || !attachmentInput) {
                            return;
                        }

                        const file = attachmentInput.files && attachmentInput.files[0] ? attachmentInput.files[0] : null;
                        if (!file) {
                            attachmentNameLine.textContent = '';
                            attachmentNameLine.classList.add('hidden');
                            return;
                        }

                        attachmentNameLine.textContent = 'Attached image: ' + file.name;
                        attachmentNameLine.classList.remove('hidden');
                    };

                    const setAttachmentFile = function (file) {
                        if (!attachmentInput || !file) {
                            return false;
                        }

                        try {
                            const dataTransfer = new DataTransfer();
                            dataTransfer.items.add(file);
                            attachmentInput.files = dataTransfer.files;
                            refreshAttachmentLabel();
                            return true;
                        } catch (error) {
                            return false;
                        }
                    };

                    const renderMessages = function (messages, scrollToBottom) {
                        if (!Array.isArray(messages) || messages.length === 0) {
                            chatList.innerHTML = '<p class="text-sm text-slate-500">No chat messages yet.</p>';
                            return;
                        }

                        chatList.innerHTML = messages.map(function (message) {
                            const senderType = String(message.sender_type || '').toLowerCase() === 'admin' ? 'admin' : 'requestor';
                            const senderLabel = escapeHtml(message.sender_label || '');
                            const createdAt = escapeHtml(message.created_at_label || '');
                            const text = escapeHtml(message.message || '').replace(/\n/g, '<br>');
                            const attachmentUrl = escapeHtml(String(message.attachment_url || ''));
                            const textHtml = text !== '' ? ('<p class="srf-chat-text">' + text + '</p>') : '';
                            const attachmentHtml = attachmentUrl !== ''
                                ? ('<button type="button" class="srf-chat-attachment" data-chat-image-open data-chat-image-src="' + attachmentUrl + '" aria-label="View chat image"><img src="' + attachmentUrl + '" alt="Chat attachment" loading="lazy"></button>')
                                : '';

                            return '<div class="srf-chat-item ' + senderType + '">' +
                                '<div class="srf-chat-bubble ' + senderType + '">' +
                                '<p class="srf-chat-meta">' + senderLabel + ' • ' + createdAt + '</p>' +
                                textHtml +
                                attachmentHtml +
                                '</div>' +
                                '</div>';
                        }).join('');

                        if (scrollToBottom) {
                            chatList.scrollTop = chatList.scrollHeight;
                        }
                    };

                    const loadMessages = async function (scrollToBottom) {
                        try {
                            const response = await fetch(chatEndpoint, {
                                headers: {
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest',
                                },
                            });

                            if (!response.ok) {
                                return;
                            }

                            const payload = await response.json();
                            const nextState = payload.chat_accepted
                                ? 'accepted'
                                : normalizeChatState(payload.chat_status || 'none');

                            const currentState = getCurrentChatState();
                            if (currentState !== nextState) {
                                if (currentState !== 'pending' && nextState === 'pending' && adminChatPanel) {
                                    const referenceCode = adminChatPanel.dataset.adminReferenceCode || 'Unknown reference';
                                    const notifyMessage = referenceCode + ' send a request chat';
                                    showChatRequestToast(notifyMessage);
                                    showTopLiveNotice(notifyMessage);
                                    syncNotificationList(true);
                                }

                                syncChatStateUi(nextState);
                                if (nextState !== 'accepted') {
                                    return;
                                }
                            }

                            if (nextState !== 'accepted') {
                                return;
                            }

                            renderMessages(payload.messages || [], scrollToBottom);
                        } catch (error) {
                            // Keep previous chat state if refresh fails.
                        }
                    };

                    const sendMessage = async function () {
                        const messageValue = textarea.value.trim();
                        const attachmentFile = attachmentInput && attachmentInput.files ? attachmentInput.files[0] : null;

                        if (messageValue === '' && !attachmentFile) {
                            return;
                        }

                        if (errorBox) {
                            errorBox.classList.add('hidden');
                            errorBox.textContent = '';
                        }

                        const body = new FormData();
                        body.append('_token', csrfToken);
                        if (messageValue !== '') {
                            body.append('message', messageValue);
                        }
                        if (attachmentFile) {
                            body.append('attachment', attachmentFile);
                        }

                        try {
                            const response = await fetch(form.action, {
                                method: 'POST',
                                headers: {
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest',
                                },
                                body: body,
                            });

                            if (response.ok) {
                                textarea.value = '';
                                if (attachmentInput) {
                                    attachmentInput.value = '';
                                    refreshAttachmentLabel();
                                }
                                await loadMessages(true);
                                return;
                            }

                            if (response.status === 422) {
                                const payload = await response.json();
                                const messageError = payload?.errors?.message?.[0]
                                    || payload?.errors?.attachment?.[0]
                                    || 'Unable to send message.';

                                if (errorBox) {
                                    errorBox.textContent = messageError;
                                    errorBox.classList.remove('hidden');
                                    return;
                                }
                            }

                            form.submit();
                        } catch (error) {
                            form.submit();
                        }
                    };

                    form.addEventListener('submit', function (event) {
                        event.preventDefault();
                        sendMessage();
                    });

                    textarea.addEventListener('keydown', function (event) {
                        if (event.key === 'Enter' && !event.shiftKey) {
                            event.preventDefault();
                            sendMessage();
                        }
                    });

                    if (attachmentInput) {
                        attachmentInput.addEventListener('change', refreshAttachmentLabel);
                        refreshAttachmentLabel();
                    }

                    textarea.addEventListener('paste', function (event) {
                        const clipboardData = event.clipboardData;
                        if (!clipboardData || !clipboardData.items) {
                            return;
                        }

                        for (let i = 0; i < clipboardData.items.length; i += 1) {
                            const item = clipboardData.items[i];
                            if (!item || typeof item.type !== 'string' || !item.type.startsWith('image/')) {
                                continue;
                            }

                            const file = item.getAsFile();
                            if (file && setAttachmentFile(file)) {
                                event.preventDefault();
                            }
                            break;
                        }
                    });

                    loadMessages(true);
                    window.setInterval(function () {
                        loadMessages(false);
                    }, 4000);
                });

                if (adminChatPanel && chatForms.length === 0) {
                    const pollEndpoint = adminChatPanel.dataset.adminChatPollEndpoint || '';

                    if (pollEndpoint !== '') {
                        const pollStatus = async function () {
                            try {
                                const response = await fetch(pollEndpoint, {
                                    headers: {
                                        'Accept': 'application/json',
                                        'X-Requested-With': 'XMLHttpRequest',
                                    },
                                });

                                if (!response.ok) {
                                    return;
                                }

                                const payload = await response.json();
                                const nextState = payload.chat_accepted
                                    ? 'accepted'
                                    : normalizeChatState(payload.chat_status || 'none');

                                const currentState = getCurrentChatState();
                                if (currentState !== nextState) {
                                    if (currentState !== 'pending' && nextState === 'pending' && adminChatPanel) {
                                        const referenceCode = adminChatPanel.dataset.adminReferenceCode || 'Unknown reference';
                                        const notifyMessage = referenceCode + ' send a request chat';
                                        showChatRequestToast(notifyMessage);
                                        showTopLiveNotice(notifyMessage);
                                        syncNotificationList(true);
                                    }

                                    syncChatStateUi(nextState);

                                    // Show message panel right away once request becomes accepted.
                                    if (nextState === 'accepted') {
                                        window.location.reload();
                                    }
                                }
                            } catch (error) {
                                // Keep current UI on transient poll failures.
                            }
                        };

                        window.setInterval(pollStatus, 4000);
                    }
                }
            };

            const initStatusActionConfirm = function () {
                const statusForm = document.querySelector('[data-status-action-form]');
                if (!statusForm) {
                    return;
                }

                const modal = document.getElementById('status-confirm-modal');
                const modalTitle = document.getElementById('status-confirm-title');
                const modalMessage = document.getElementById('status-confirm-message');
                const modalIcon = document.getElementById('status-confirm-icon');
                const cancelButton = document.getElementById('status-confirm-cancel');
                const acceptButton = document.getElementById('status-confirm-accept');
                const buttons = statusForm.querySelectorAll('button[data-status-target]');
                const statusConfig = {
                    pending: {
                        title: 'Set Request to Pending?',
                        message: 'This action will clear chat history and require chat request again. Do you want to continue?',
                        icon: '!',
                        tone: 'pending',
                        confirmLabel: 'Yes, Set Pending',
                    },
                    approved: {
                        title: 'Approve This Request?',
                        message: 'Do you want to approve this service request now?',
                        icon: '!',
                        tone: 'approved',
                        confirmLabel: 'Yes, Approve',
                    },
                    rejected: {
                        title: 'Reject This Request?',
                        message: 'Do you want to reject this service request now?',
                        icon: '!',
                        tone: 'rejected',
                        confirmLabel: 'Yes, Reject',
                    },
                };
                let activeSubmitButton = null;

                const closeModal = function () {
                    if (!modal) {
                        return;
                    }

                    modal.classList.remove('open');
                    modal.setAttribute('aria-hidden', 'true');
                    activeSubmitButton = null;
                };

                const openModal = function (config, submitButton) {
                    if (!modal || !modalTitle || !modalMessage || !modalIcon || !acceptButton) {
                        const fallbackMessage = config && config.message ? config.message : 'Do you want to continue?';
                        if (window.confirm(fallbackMessage)) {
                            statusForm.requestSubmit(submitButton);
                        }
                        return;
                    }

                    activeSubmitButton = submitButton;
                    modalTitle.textContent = config.title;
                    modalMessage.textContent = config.message;
                    modalIcon.textContent = config.icon;
                    modalIcon.classList.remove('pending', 'approved', 'rejected');
                    modalIcon.classList.add(config.tone);
                    acceptButton.textContent = config.confirmLabel;

                    modal.classList.add('open');
                    modal.setAttribute('aria-hidden', 'false');
                };

                if (cancelButton) {
                    cancelButton.addEventListener('click', function () {
                        closeModal();
                    });
                }

                if (acceptButton) {
                    acceptButton.addEventListener('click', function () {
                        if (!activeSubmitButton) {
                            closeModal();
                            return;
                        }

                        const submitButton = activeSubmitButton;
                        closeModal();
                        statusForm.requestSubmit(submitButton);
                    });
                }

                if (modal) {
                    modal.addEventListener('click', function (event) {
                        if (event.target === modal) {
                            closeModal();
                        }
                    });

                    document.addEventListener('keydown', function (event) {
                        if (event.key === 'Escape' && modal.classList.contains('open')) {
                            closeModal();
                        }
                    });
                }

                buttons.forEach(function (button) {
                    button.addEventListener('click', function (event) {
                        const targetStatus = String(button.getAttribute('data-status-target') || '').toLowerCase();
                        const config = statusConfig[targetStatus] || {
                            title: 'Confirm Action',
                            message: 'Do you want to continue?',
                            icon: '!',
                            tone: 'pending',
                            confirmLabel: 'Yes, Continue',
                        };

                        event.preventDefault();
                        openModal(config, button);
                    });
                });
            };

            const initFloatingPrintPreview = function () {
                const printButton = document.getElementById('admin-print-button');
                const modal = document.getElementById('print-preview-modal');
                const frame = document.getElementById('print-preview-frame');
                const closeButton = document.getElementById('print-preview-close');
                const printTrigger = document.getElementById('print-preview-trigger');
                const openFullLink = document.getElementById('print-preview-open-full');
                const signatureTrigger = document.getElementById('print-preview-signature');
                const signatureSmallerTrigger = document.getElementById('print-preview-signature-smaller');
                const signatureLargerTrigger = document.getElementById('print-preview-signature-larger');

                if (
                    !printButton ||
                    !modal ||
                    !frame ||
                    !closeButton ||
                    !printTrigger ||
                    !openFullLink ||
                    !signatureTrigger ||
                    !signatureSmallerTrigger ||
                    !signatureLargerTrigger
                ) {
                    return;
                }

                const closeModal = function () {
                    modal.classList.remove('open');
                    modal.setAttribute('aria-hidden', 'true');
                    document.body.classList.remove('overflow-hidden');
                };

                const openModal = function (previewUrl, fullUrl) {
                    frame.src = previewUrl;
                    openFullLink.href = fullUrl;
                    modal.classList.add('open');
                    modal.setAttribute('aria-hidden', 'false');
                    document.body.classList.add('overflow-hidden');
                };

                printButton.addEventListener('click', function (event) {
                    event.preventDefault();

                    const baseUrl = printButton.getAttribute('href') || '';
                    if (baseUrl === '') {
                        return;
                    }

                    const previewUrl = baseUrl + (baseUrl.includes('?') ? '&' : '?') + 'embedded=1&print_ts=' + Date.now();
                    const fullUrl = baseUrl + (baseUrl.includes('?') ? '&' : '?') + 'print_ts=' + Date.now();
                    openModal(previewUrl, fullUrl);
                });

                var stickyPrintButton = document.getElementById('sticky-print-button');
                if (stickyPrintButton) {
                    stickyPrintButton.addEventListener('click', function (event) {
                        event.preventDefault();

                        var baseUrl = stickyPrintButton.getAttribute('href') || '';
                        if (baseUrl === '') {
                            return;
                        }

                        var previewUrl = baseUrl + (baseUrl.includes('?') ? '&' : '?') + 'embedded=1&print_ts=' + Date.now();
                        var fullUrl = baseUrl + (baseUrl.includes('?') ? '&' : '?') + 'print_ts=' + Date.now();
                        openModal(previewUrl, fullUrl);
                    });
                }

                closeButton.addEventListener('click', closeModal);

                modal.addEventListener('click', function (event) {
                    if (event.target === modal) {
                        closeModal();
                    }
                });

                document.addEventListener('keydown', function (event) {
                    if (event.key === 'Escape' && modal.classList.contains('open')) {
                        closeModal();
                    }
                });

                printTrigger.addEventListener('click', function () {
                    try {
                        frame.contentWindow.focus();
                        frame.contentWindow.print();
                    } catch (error) {
                        const fallbackUrl = openFullLink.getAttribute('href') || '';
                        if (fallbackUrl !== '') {
                            window.open(fallbackUrl, '_blank', 'noopener');
                        }
                    }
                });

                signatureTrigger.addEventListener('click', function () {
                    try {
                        frame.contentWindow.focus();
                        frame.contentWindow.postMessage({ type: 'open-print-signature-pad' }, '*');
                    } catch (error) {
                        // Ignore messaging failures.
                    }
                });

                signatureSmallerTrigger.addEventListener('click', function () {
                    try {
                        frame.contentWindow.focus();
                        frame.contentWindow.postMessage({ type: 'resize-print-signature', delta: -0.1 }, '*');
                    } catch (error) {
                        // Ignore messaging failures.
                    }
                });

                signatureLargerTrigger.addEventListener('click', function () {
                    try {
                        frame.contentWindow.focus();
                        frame.contentWindow.postMessage({ type: 'resize-print-signature', delta: 0.1 }, '*');
                    } catch (error) {
                        // Ignore messaging failures.
                    }
                });
            };

            const initActionLogSignaturePad = function () {
                const cells = Array.from(document.querySelectorAll('[data-log-signature-cell]'));
                const modal = document.getElementById('action-log-signature-modal');
                const canvas = document.getElementById('action-log-signature-canvas');
                const closeBtn = document.getElementById('action-log-signature-close');
                const cancelBtn = document.getElementById('action-log-signature-cancel');
                const clearBtn = document.getElementById('action-log-signature-clear');
                const applyBtn = document.getElementById('action-log-signature-apply');

                if (cells.length === 0 || !modal || !canvas || !closeBtn || !cancelBtn || !clearBtn || !applyBtn) {
                    return;
                }

                const ctx = canvas.getContext('2d');
                if (!ctx) {
                    return;
                }

                let activeCell = null;
                let drawing = false;

                const configureCanvas = function () {
                    const ratio = window.devicePixelRatio || 1;
                    const rect = canvas.getBoundingClientRect();
                    canvas.width = Math.max(1, Math.floor(rect.width * ratio));
                    canvas.height = Math.max(1, Math.floor(rect.height * ratio));
                    ctx.setTransform(ratio, 0, 0, ratio, 0, 0);
                    ctx.lineWidth = 2.4;
                    ctx.lineCap = 'round';
                    ctx.strokeStyle = '#0f172a';
                };

                const clearCanvas = function () {
                    ctx.clearRect(0, 0, canvas.width, canvas.height);
                };

                const pointFromEvent = function (event) {
                    const rect = canvas.getBoundingClientRect();
                    const source = event.touches ? event.touches[0] : event;
                    return {
                        x: source.clientX - rect.left,
                        y: source.clientY - rect.top,
                    };
                };

                const drawDataUrlOnCanvas = function (dataUrl) {
                    clearCanvas();
                    if (!dataUrl) {
                        return;
                    }

                    const img = new Image();
                    img.onload = function () {
                        clearCanvas();
                        ctx.drawImage(img, 0, 0, canvas.clientWidth, canvas.clientHeight);
                    };
                    img.src = dataUrl;
                };

                const getCenteredSignatureDataUrl = function () {
                    const width = canvas.width;
                    const height = canvas.height;
                    const imageData = ctx.getImageData(0, 0, width, height);
                    const data = imageData.data;

                    let minX = width;
                    let minY = height;
                    let maxX = -1;
                    let maxY = -1;

                    for (let y = 0; y < height; y++) {
                        for (let x = 0; x < width; x++) {
                            const alpha = data[(y * width + x) * 4 + 3];
                            if (alpha > 0) {
                                if (x < minX) minX = x;
                                if (y < minY) minY = y;
                                if (x > maxX) maxX = x;
                                if (y > maxY) maxY = y;
                            }
                        }
                    }

                    if (maxX < minX || maxY < minY) {
                        return '';
                    }

                    const cropWidth = maxX - minX + 1;
                    const cropHeight = maxY - minY + 1;

                    const targetCanvas = document.createElement('canvas');
                    targetCanvas.width = width;
                    targetCanvas.height = height;

                    const targetCtx = targetCanvas.getContext('2d');
                    if (!targetCtx) {
                        return canvas.toDataURL('image/png');
                    }

                    const scale = Math.min((width * 0.9) / cropWidth, (height * 0.8) / cropHeight, 1);
                    const drawWidth = cropWidth * scale;
                    const drawHeight = cropHeight * scale;
                    const drawX = (width - drawWidth) / 2;
                    const drawY = (height - drawHeight) / 2;

                    targetCtx.clearRect(0, 0, width, height);
                    targetCtx.drawImage(
                        canvas,
                        minX,
                        minY,
                        cropWidth,
                        cropHeight,
                        drawX,
                        drawY,
                        drawWidth,
                        drawHeight
                    );

                    return targetCanvas.toDataURL('image/png');
                };

                const syncCellSignature = function (cell, signatureData) {
                    const input = cell.querySelector('[data-log-signature-input]');
                    const preview = cell.querySelector('[data-log-signature-preview]');
                    const placeholder = cell.querySelector('[data-log-signature-placeholder]');
                    const clearControl = cell.querySelector('[data-log-signature-clear]');

                    if (input) {
                        input.value = signatureData;
                    }

                    if (preview) {
                        if (signatureData !== '') {
                            preview.src = signatureData;
                            preview.classList.remove('hidden');
                        } else {
                            preview.removeAttribute('src');
                            preview.classList.add('hidden');
                        }
                    }

                    if (placeholder) {
                        placeholder.classList.toggle('hidden', signatureData !== '');
                    }

                    if (clearControl) {
                        clearControl.classList.toggle('hidden', signatureData === '');
                    }
                };

                const closeModal = function () {
                    modal.classList.remove('open');
                    modal.setAttribute('aria-hidden', 'true');
                    document.body.style.overflow = '';
                    activeCell = null;
                    drawing = false;
                };

                const openModal = function (cell) {
                    activeCell = cell;
                    modal.classList.add('open');
                    modal.setAttribute('aria-hidden', 'false');
                    document.body.style.overflow = 'hidden';

                    const input = cell.querySelector('[data-log-signature-input]');
                    window.requestAnimationFrame(function () {
                        configureCanvas();
                        drawDataUrlOnCanvas(input ? String(input.value || '') : '');
                    });
                };

                const startDrawing = function (event) {
                    if (!modal.classList.contains('open')) {
                        return;
                    }

                    drawing = true;
                    const point = pointFromEvent(event);
                    ctx.beginPath();
                    ctx.moveTo(point.x, point.y);
                    event.preventDefault();
                };

                const moveDrawing = function (event) {
                    if (!drawing) {
                        return;
                    }

                    const point = pointFromEvent(event);
                    ctx.lineTo(point.x, point.y);
                    ctx.stroke();
                    event.preventDefault();
                };

                const endDrawing = function () {
                    drawing = false;
                };

                canvas.addEventListener('mousedown', startDrawing);
                canvas.addEventListener('mousemove', moveDrawing);
                window.addEventListener('mouseup', endDrawing);
                canvas.addEventListener('touchstart', startDrawing, { passive: false });
                canvas.addEventListener('touchmove', moveDrawing, { passive: false });
                canvas.addEventListener('touchend', endDrawing);

                cells.forEach(function (cell) {
                    const input = cell.querySelector('[data-log-signature-input]');
                    const trigger = cell.querySelector('[data-log-signature-trigger]');
                    const clearControl = cell.querySelector('[data-log-signature-clear]');

                    syncCellSignature(cell, input ? String(input.value || '') : '');

                    if (trigger) {
                        trigger.addEventListener('click', function () {
                            openModal(cell);
                        });
                    }

                    if (clearControl) {
                        clearControl.addEventListener('click', function () {
                            syncCellSignature(cell, '');
                        });
                    }
                });

                clearBtn.addEventListener('click', clearCanvas);

                applyBtn.addEventListener('click', function () {
                    if (!activeCell) {
                        closeModal();
                        return;
                    }

                    const signatureData = getCenteredSignatureDataUrl();
                    syncCellSignature(activeCell, signatureData);
                    closeModal();
                });

                closeBtn.addEventListener('click', closeModal);
                cancelBtn.addEventListener('click', closeModal);

                modal.addEventListener('click', function (event) {
                    if (event.target === modal) {
                        closeModal();
                    }
                });

                document.addEventListener('keydown', function (event) {
                    if (event.key === 'Escape' && modal.classList.contains('open')) {
                        closeModal();
                    }
                });

                window.addEventListener('resize', function () {
                    if (!modal.classList.contains('open') || !activeCell) {
                        return;
                    }

                    configureCanvas();
                    const input = activeCell.querySelector('[data-log-signature-input]');
                    drawDataUrlOnCanvas(input ? String(input.value || '') : '');
                });
            };

            const initUploadedPhotoPreview = function () {
                const triggers = document.querySelectorAll('[data-uploaded-photo-trigger]');
                const modal = document.getElementById('uploaded-photo-modal');
                const modalImage = document.getElementById('uploaded-photo-modal-image');
                const closeButton = document.getElementById('uploaded-photo-modal-close');
                const cancelButton = document.getElementById('uploaded-photo-modal-cancel');

                if (!triggers.length || !modal || !modalImage || !closeButton || !cancelButton) {
                    return;
                }

                const closeModal = function () {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                    modal.setAttribute('aria-hidden', 'true');
                    modalImage.src = '';
                    document.body.classList.remove('overflow-hidden');
                };

                const openModal = function (src, altText) {
                    if (!src) {
                        return;
                    }

                    modalImage.src = src;
                    modalImage.alt = altText || 'Uploaded photo preview';
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                    modal.setAttribute('aria-hidden', 'false');
                    document.body.classList.add('overflow-hidden');
                };

                triggers.forEach(function (trigger) {
                    trigger.addEventListener('click', function (event) {
                        event.preventDefault();
                        openModal(
                            String(trigger.getAttribute('data-photo-src') || ''),
                            String(trigger.getAttribute('data-photo-alt') || 'Uploaded photo preview')
                        );
                    });
                });

                closeButton.addEventListener('click', closeModal);
                cancelButton.addEventListener('click', closeModal);
                modal.addEventListener('click', function (event) {
                    if (event.target === modal) {
                        closeModal();
                    }
                });

                document.addEventListener('keydown', function (event) {
                    if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
                        closeModal();
                    }
                });
            };

            initSignatureInput();
            initFloatingPrintPreview();
            initChatEnterSubmit();
            initStatusActionConfirm();
            initActionLogSignaturePad();
            initUploadedPhotoPreview();

            // Sticky action bar + scroll-to-top
            var stickyBar = document.getElementById('srf-sticky-bar');
            var scrollTopBtn = document.getElementById('srf-scroll-top');
            var statusBlock = document.querySelector('.srf-status-block');

            if (scrollTopBtn) {
                scrollTopBtn.addEventListener('click', function () {
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                });
            }

            if (statusBlock) {
                var lastScrollY = 0;
                var ticking = false;

                var handleScroll = function () {
                    var rect = statusBlock.getBoundingClientRect();
                    var pastBlock = rect.bottom < 0;
                    var scrolledDown = window.scrollY > 200;

                    if (stickyBar) {
                        if (pastBlock) {
                            stickyBar.classList.add('visible');
                        } else {
                            stickyBar.classList.remove('visible');
                        }
                    }

                    if (scrollTopBtn) {
                        if (scrolledDown) {
                            scrollTopBtn.classList.add('visible');
                        } else {
                            scrollTopBtn.classList.remove('visible');
                        }
                    }

                    ticking = false;
                };

                window.addEventListener('scroll', function () {
                    if (!ticking) {
                        window.requestAnimationFrame(handleScroll);
                        ticking = true;
                    }
                }, { passive: true });
            }
        });

    </script>
</x-guest-layout>
