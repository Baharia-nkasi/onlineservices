<x-app-layout>

    <style>
        /* ================================
           MAIN PAGE
        ================================= */

        .details-page {
            min-height: calc(100vh - 65px);
            padding: 50px 0 80px;
            background:
                radial-gradient(circle at 10% 0%, rgba(37, 99, 235, 0.12), transparent 28%),
                radial-gradient(circle at 95% 20%, rgba(16, 185, 129, 0.10), transparent 25%),
                linear-gradient(180deg, #f8fafc 0%, #eef4f9 100%);
        }

        .details-wrapper {
            max-width: 1050px;
            margin: auto;
        }


        /* ================================
           TOP INTRO
        ================================= */

/* ================================
   ANIMATED PAGE INTRO
================================ */

.page-intro {
    position: relative;
    display: flex;
    align-items: center;
    gap: 16px;
    min-height: 88px;
    margin-bottom: 25px;
    padding: 15px 20px;

    background: linear-gradient(
        135deg,
        #ffffff 0%,
        #f8fbff 50%,
        #eff6ff 100%
    );

    border: 1px solid #dbeafe;
    border-radius: 18px;

    overflow: hidden;

    box-shadow:
        0 10px 30px rgba(37, 99, 235, 0.08),
        0 3px 8px rgba(15, 23, 42, 0.04);
}

/* Moving left border */
.page-intro::before {
    content: "";
    position: absolute;
    left: 0;
    top: 0;

    width: 5px;
    height: 100%;

    background: linear-gradient(
        180deg,
        #2563eb,
        #60a5fa,
        #22c55e,
        #2563eb
    );

    background-size: 100% 300%;

    animation: introLine 4s linear infinite;
}

@keyframes introLine {
    0% {
        background-position: 0% 0%;
    }

    100% {
        background-position: 0% 300%;
    }
}


/* Icon */
.page-intro-icon {
    position: relative;
    z-index: 2;

    width: 50px;
    height: 50px;
    min-width: 50px;

    display: flex;
    align-items: center;
    justify-content: center;

    background: linear-gradient(
        135deg,
        #2563eb,
        #3b82f6
    );

    color: #ffffff;

    border-radius: 14px;

    font-size: 22px;

    box-shadow:
        0 8px 18px rgba(37, 99, 235, 0.25);

    animation: introIcon 2.5s ease-in-out infinite;
}

@keyframes introIcon {
    0%, 100% {
        transform: translateY(0) rotate(0deg);
    }

    50% {
        transform: translateY(-3px) rotate(3deg);
    }
}


/* Text container */
.page-intro-content {
    flex: 1;
    min-width: 0;
}

.page-intro h1 {
    margin: 0 0 5px;

    font-size: 24px;
    font-weight: 850;

    color: #0f172a;

    letter-spacing: -0.4px;
}


/* Moving text area */
.page-intro-subtitle {
    width: 100%;
    overflow: hidden;
    white-space: nowrap;
}


/* ================================
   BEAUTIFUL MOVING INFORMATION TEXT
================================ */

.page-intro-moving-text {
    display: inline-block;

    padding-left: 100%;

    color: #475569;

    font-size: 16px;
    font-weight: 600;

    line-height: 1.7;

    letter-spacing: 0.15px;

    text-shadow: 0 1px 1px rgba(15, 23, 42, 0.04);

    animation: moveIntroText 28s linear infinite;
}

.intro-message {
    display: inline-flex;
    align-items: center;
    gap: 22px;

    color: #475569;

    font-size: 16px;
    font-weight: 600;

    line-height: 1.7;
    white-space: nowrap;
}

.intro-separator {
    display: inline-flex;
    align-items: center;
    justify-content: center;

    color: #3b82f6;

    font-size: 17px;
    font-weight: 800;

    opacity: 0.75;

    text-shadow:
        0 1px 3px rgba(59, 130, 246, 0.20);
}

/* Smooth and gentle movement */
@keyframes moveIntroText {

    0% {
        transform: translateX(0);
    }

    100% {
        transform: translateX(-100%);
    }

}

/* Live indicator */
.page-intro-live {
    position: relative;
    z-index: 2;

    display: inline-flex;
    align-items: center;
    gap: 7px;

    padding: 7px 11px;

    background: #ecfdf5;

    border: 1px solid #bbf7d0;

    border-radius: 999px;

    color: #15803d;

    font-size: 11px;
    font-weight: 800;

    flex-shrink: 0;
}


/* Live dot */
.page-intro-live-dot {
    width: 7px;
    height: 7px;

    background: #22c55e;

    border-radius: 50%;

    box-shadow:
        0 0 0 4px rgba(34, 197, 94, 0.12);

    animation: introLivePulse 1.5s ease-in-out infinite;
}

@keyframes introLivePulse {

    0%, 100% {
        opacity: 1;
        transform: scale(1);
    }

    50% {
        opacity: 0.45;
        transform: scale(0.75);
    }

}


/* Mobile */
@media (max-width: 640px) {

    .page-intro {
        min-height: 80px;
        padding: 12px 14px;
        gap: 11px;
        border-radius: 15px;
    }

    .page-intro-icon {
        width: 42px;
        height: 42px;
        min-width: 42px;

        font-size: 18px;

        border-radius: 11px;
    }

    .page-intro h1 {
        font-size: 19px;
    }

.page-intro-moving-text {
    font-size: 14px;
    font-weight: 600;
    animation-duration: 24s;
}

    .page-intro-live {
        padding: 6px 8px;
        font-size: 9px;
    }

}


        /* ================================
           MAIN CARD
        ================================= */

        .details-card {
            background: rgba(255, 255, 255, 0.96);
            border: 1px solid rgba(226, 232, 240, 0.9);
            border-radius: 22px;
            overflow: hidden;
            box-shadow:
                0 20px 45px rgba(15, 23, 42, 0.08),
                0 4px 12px rgba(15, 23, 42, 0.04);
        }


        /* ================================
           SERVICE HEADER
        ================================= */

        .service-header {
            position: relative;
            padding: 34px;
            background:
                linear-gradient(135deg, #1d4ed8 0%, #2563eb 55%, #3b82f6 100%);
            color: white;
            overflow: hidden;
        }

        .service-header::before {
            content: "";
            position: absolute;
            width: 260px;
            height: 260px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.08);
            top: -150px;
            right: -70px;
        }

        .service-header::after {
            content: "";
            position: absolute;
            width: 180px;
            height: 180px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.06);
            bottom: -110px;
            left: 30%;
        }

        .service-header-content {
            position: relative;
            z-index: 2;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 25px;
        }

        .service-left {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .service-icon {
            width: 70px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.16);
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 18px;
            font-size: 32px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
            backdrop-filter: blur(8px);
        }

        .service-label {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.8;
            margin-bottom: 5px;
        }

        .service-name {
            font-size: 25px;
            font-weight: 800;
            line-height: 1.25;
        }

        .application-id {
            margin-top: 7px;
            font-size: 14px;
            opacity: 0.85;
        }

        .application-id strong {
            color: white;
        }


        /* ================================
           STATUS
        ================================= */

        .header-status {
            position: relative;
            z-index: 2;
            flex-shrink: 0;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            padding: 10px 16px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 800;
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.10);
        }

        .status-dot {
            width: 9px;
            height: 9px;
            border-radius: 50%;
        }

        .status-pending {
            color: #92400e;
        }

        .status-pending .status-dot {
            background: #f59e0b;
            box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.15);
        }

        .status-processing {
            color: #1d4ed8;
        }

        .status-processing .status-dot {
            background: #2563eb;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.15);
        }

        .status-completed {
            color: #166534;
        }

        .status-completed .status-dot {
            background: #16a34a;
            box-shadow: 0 0 0 4px rgba(22, 163, 74, 0.15);
        }

        .status-rejected {
            color: #991b1b;
        }

        .status-rejected .status-dot {
            background: #dc2626;
            box-shadow: 0 0 0 4px rgba(220, 38, 38, 0.15);
        }

        .status-default {
            color: #374151;
        }

        .status-default .status-dot {
            background: #6b7280;
        }


        /* ================================
           BODY
        ================================= */

        .details-body {
            padding: 35px;
        }

        .section {
            margin-bottom: 35px;
        }

        .section:last-child {
            margin-bottom: 0;
        }

        .section-heading {
            display: flex;
            align-items: center;
            gap: 11px;
            margin-bottom: 20px;
        }

        .section-icon {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #eff6ff;
            color: #2563eb;
            border-radius: 10px;
            font-size: 17px;
        }

        .section-title {
            font-size: 18px;
            font-weight: 800;
            color: #111827;
        }

        .section-subtitle {
            font-size: 13px;
            color: #94a3b8;
            margin-top: 2px;
        }


        /* ================================
           INFORMATION GRID
        ================================= */

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
        }

        .info-box {
            position: relative;
            padding: 19px;
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            transition: all 0.25s ease;
        }

        .info-box:hover {
            background: #ffffff;
            border-color: #bfdbfe;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.07);
        }

        .info-label {
            display: block;
            margin-bottom: 7px;
            color: #94a3b8;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.7px;
        }

        .info-value {
            color: #111827;
            font-size: 15px;
            font-weight: 700;
        }


        /* ================================
           PAYMENT SECTION
        ================================= */

        .payment-section {
            padding-top: 30px;
            border-top: 1px solid #e5e7eb;
        }

        .payment-card {
            background: linear-gradient(145deg, #f8fafc, #ffffff);
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 22px;
        }

        .fee-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            padding: 15px 0;
            border-bottom: 1px solid #edf2f7;
            font-size: 14px;
        }

        .fee-row:last-of-type {
            border-bottom: none;
        }

        .fee-name {
            color: #64748b;
        }

        .fee-amount {
            color: #111827;
            font-weight: 750;
        }

        .total-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-top: 15px;
            padding: 19px 20px;
            background: linear-gradient(135deg, #eff6ff, #dbeafe);
            border: 1px solid #bfdbfe;
            border-radius: 13px;
        }

        .total-label {
            color: #1e3a8a;
            font-size: 15px;
            font-weight: 800;
        }

        .total-amount {
            color: #1d4ed8;
            font-size: 21px;
            font-weight: 900;
        }


        /* ================================
           NOTES
        ================================= */

        .notes-section {
            padding-top: 30px;
            border-top: 1px solid #e5e7eb;
        }

        .notes-box {
            padding: 20px;
            background: #fffbeb;
            border: 1px solid #fde68a;
            border-left: 4px solid #f59e0b;
            border-radius: 12px;
            color: #57534e;
            font-size: 14px;
            line-height: 1.75;
        }

        .no-notes {
            background: #f8fafc;
            border-color: #e2e8f0;
            border-left-color: #cbd5e1;
            color: #94a3b8;
            font-style: italic;
        }


        /* ================================
           FOOTER
        ================================= */

        .details-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            padding: 24px 35px;
            background: #f8fafc;
            border-top: 1px solid #e5e7eb;
        }

        .footer-message {
            color: #94a3b8;
            font-size: 13px;
        }

        .back-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            padding: 11px 20px;
            background: #ffffff;
            border: 1px solid #d1d5db;
            border-radius: 10px;
            color: #374151;
            font-size: 14px;
            font-weight: 800;
            text-decoration: none;
            transition: all 0.25s ease;
        }

        .back-button:hover {
            background: #2563eb;
            border-color: #2563eb;
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 7px 16px rgba(37, 99, 235, 0.20);
        }


        /* ================================
           MOBILE
        ================================= */

        @media (max-width: 768px) {

            .details-page {
                padding: 30px 0 50px;
            }

            .page-intro {
                padding: 0 5px;
            }

            .page-intro h1 {
                font-size: 24px;
            }

            .service-header {
                padding: 25px 22px;
            }

            .service-header-content {
                align-items: flex-start;
                flex-direction: column;
            }

            .service-left {
                align-items: flex-start;
            }

            .service-icon {
                width: 58px;
                height: 58px;
                font-size: 26px;
            }

            .service-name {
                font-size: 20px;
            }

            .header-status {
                margin-left: 76px;
            }

            .details-body {
                padding: 25px 20px;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .details-footer {
                padding: 20px;
                flex-direction: column;
                align-items: stretch;
            }

            .back-button {
                width: 100%;
            }

            .footer-message {
                text-align: center;
            }
        }


        @media (max-width: 480px) {

            .details-page {
                padding-top: 20px;
            }

            .service-left {
                gap: 13px;
            }

            .service-name {
                font-size: 18px;
            }

            .application-id {
                font-size: 12px;
            }

            .header-status {
                margin-left: 0;
            }

            .section-title {
                font-size: 16px;
            }

            .total-amount {
                font-size: 18px;
            }

            .payment-card {
                padding: 17px;
            }
        }

/* =========================================
   APPLICATION DOCUMENTS
========================================= */

.documents-section {
    margin-top: 30px;
    padding: 26px;

    background: #ffffff;

    border: 1px solid #e5e7eb;

    border-radius: 18px;

    box-shadow:
        0 8px 25px rgba(15, 23, 42, 0.05);
}


.documents-header {
    display: flex;

    align-items: center;
    justify-content: space-between;

    gap: 20px;

    margin-bottom: 22px;
}


.documents-header h2 {
    margin: 0;

    color: #111827;

    font-size: 20px;

    font-weight: 800;
}


.documents-header p {
    margin: 5px 0 0;

    color: #64748b;

    font-size: 13px;
}


.documents-icon {
    width: 48px;
    height: 48px;

    display: flex;

    align-items: center;
    justify-content: center;

    background: #eff6ff;

    border-radius: 12px;

    font-size: 22px;
}


/* SUCCESS */

.document-success {
    display: flex;

    align-items: center;

    gap: 10px;

    margin-bottom: 20px;

    padding: 13px 16px;

    background: #ecfdf5;

    border: 1px solid #bbf7d0;

    border-radius: 10px;

    color: #166534;

    font-size: 13px;

    font-weight: 600;
}


.success-check {
    width: 25px;
    height: 25px;

    display: flex;

    align-items: center;
    justify-content: center;

    background: #16a34a;

    color: white;

    border-radius: 50%;

    font-size: 14px;
}


/* UPLOAD FORM */

.document-upload-form {
    padding: 20px;

    background: #f8fafc;

    border: 1px dashed #cbd5e1;

    border-radius: 14px;

    margin-bottom: 28px;
}


.upload-grid {
    display: grid;

    grid-template-columns:
        repeat(2, minmax(0, 1fr));

    gap: 18px;

    margin-bottom: 18px;
}


.upload-field {
    display: flex;

    flex-direction: column;
}


.upload-field label {
    margin-bottom: 7px;

    color: #334155;

    font-size: 13px;

    font-weight: 700;
}


.upload-field input[type="text"],
.upload-field input[type="file"] {
    width: 100%;

    padding: 11px 13px;

    background: white;

    border: 1px solid #cbd5e1;

    border-radius: 8px;

    color: #334155;

    font-size: 13px;

    box-sizing: border-box;
}


.upload-field input:focus {
    outline: none;

    border-color: #2563eb;

    box-shadow:
        0 0 0 3px rgba(37, 99, 235, 0.10);
}


.upload-field small {
    margin-top: 6px;

    color: #94a3b8;

    font-size: 11px;
}


.field-error {
    margin-top: 6px;

    color: #dc2626;

    font-size: 11px;
}


.upload-button {
    display: inline-flex;

    align-items: center;
    justify-content: center;

    padding: 10px 18px;

    background: #2563eb;

    border: none;

    border-radius: 8px;

    color: white;

    font-size: 13px;

    font-weight: 700;

    cursor: pointer;

    transition: all 0.2s ease;
}


.upload-button:hover {
    background: #1d4ed8;

    transform: translateY(-1px);
}


/* UPLOADED DOCUMENTS */

.uploaded-documents h3 {
    margin: 0 0 14px;

    color: #334155;

    font-size: 15px;

    font-weight: 800;
}


.document-item {
    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 20px;

    padding: 15px;

    margin-bottom: 10px;

    background: #ffffff;

    border: 1px solid #e5e7eb;

    border-radius: 12px;

    transition: all 0.2s ease;
}


.document-item:hover {
    border-color: #bfdbfe;

    box-shadow:
        0 5px 15px rgba(37, 99, 235, 0.07);
}


.document-info {
    display: flex;

    align-items: center;

    gap: 12px;

    min-width: 0;
}


.document-file-icon {
    width: 40px;
    height: 40px;

    min-width: 40px;

    display: flex;

    align-items: center;
    justify-content: center;

    background: #eff6ff;

    border-radius: 10px;

    font-size: 18px;
}


.document-name {
    color: #111827;

    font-size: 13px;

    font-weight: 750;
}


.document-meta {
    margin-top: 4px;

    color: #94a3b8;

    font-size: 10px;
}


.document-actions {
    display: flex;

    align-items: center;

    gap: 10px;

    flex-shrink: 0;
}


.document-view,
.document-delete {
    padding: 7px 11px;

    border-radius: 7px;

    font-size: 11px;

    font-weight: 700;

    text-decoration: none;

    cursor: pointer;
}


.document-view {
    background: #eff6ff;

    color: #2563eb;
}


.document-view:hover {
    background: #dbeafe;
}


.document-delete {
    background: #fef2f2;

    border: 1px solid #fecaca;

    color: #dc2626;
}


.document-delete:hover {
    background: #fee2e2;
}


/* EMPTY */

.no-documents {
    padding: 30px 15px;

    text-align: center;

    background: #f8fafc;

    border-radius: 12px;
}


.no-documents-icon {
    font-size: 30px;

    margin-bottom: 8px;
}


.no-documents h4 {
    margin: 0 0 5px;

    color: #475569;

    font-size: 14px;

    font-weight: 750;
}


.no-documents p {
    margin: 0;

    color: #94a3b8;

    font-size: 12px;
}


/* MOBILE */

@media (max-width: 700px) {

    .documents-section {
        padding: 20px;
    }

    .upload-grid {
        grid-template-columns: 1fr;
    }

    .document-item {
        align-items: flex-start;

        flex-direction: column;
    }

    .document-actions {
        width: 100%;
    }

    .document-view,
    .document-delete {
        flex: 1;

        text-align: center;
    }

}

/* =========================================
   BEAUTIFUL APPLICATION DOCUMENTS HEADER
========================================= */

.documents-header {
    width: 100%;
    margin-bottom: 24px;
}

.documents-title-box {
    position: relative;

    display: flex;
    align-items: center;

    width: 100%;
    min-height: 92px;

    padding: 16px 20px;

    box-sizing: border-box;

    background:
        linear-gradient(
            135deg,
            #ffffff 0%,
            #f8fbff 45%,
            #eff6ff 100%
        );

    border: 1px solid #dbeafe;

    border-radius: 17px;

    overflow: hidden;

    box-shadow:
        0 10px 28px rgba(37, 99, 235, 0.08),
        0 3px 8px rgba(15, 23, 42, 0.04);
}


/* Blue animated line */

.documents-title-box::before {
    content: "";

    position: absolute;

    left: 0;
    top: 0;

    width: 5px;
    height: 100%;

    background:
        linear-gradient(
            180deg,
            #2563eb,
            #60a5fa,
            #22c55e,
            #2563eb
        );

    background-size: 100% 300%;

    animation:
        documentsLine 4s linear infinite;
}


@keyframes documentsLine {

    0% {
        background-position: 0% 0%;
    }

    100% {
        background-position: 0% 300%;
    }

}


/* =========================================
   ICON
========================================= */

.documents-title-icon {
    position: relative;
    z-index: 2;

    width: 58px;
    height: 58px;

    min-width: 58px;

    display: flex;
    align-items: center;
    justify-content: center;

    margin-left: 3px;
    margin-right: 17px;

    background:
        linear-gradient(
            135deg,
            #2563eb,
            #3b82f6
        );

    border-radius: 15px;

    color: #ffffff;

    font-size: 27px;

    box-shadow:
        0 8px 18px rgba(37, 99, 235, 0.25);

    animation:
        documentsIcon 3s ease-in-out infinite;
}


@keyframes documentsIcon {

    0%, 100% {
        transform: translateY(0);
    }

    50% {
        transform: translateY(-3px);
    }

}


/* =========================================
   TITLE
========================================= */

.documents-title-content {
    flex: 1;

    min-width: 0;

    overflow: hidden;
}


.documents-title-content h2 {
    margin: 0;

    color: #0f172a;

    font-size: 24px;

    font-weight: 850;

    line-height: 1.2;

    letter-spacing: -0.4px;
}


/* =========================================
   MOVING TEXT
========================================= */

.documents-moving-wrapper {
    width: 100%;

    margin-top: 6px;

    overflow: hidden;

    white-space: nowrap;
}


.documents-moving-text {

    display: inline-flex;

    align-items: center;

    gap: 30px;

    padding-left: 100%;

    color: #475569;

    font-size: 17px;

    font-weight: 650;

    line-height: 1.6;

    white-space: nowrap;

   animation:
    documentsMoveRightToLeft
    18s
    linear
    infinite;
}


/* =========================================
   SEPARATOR
========================================= */

.moving-separator {

    display: inline-flex;

    align-items: center;
    justify-content: center;

    color: #2563eb;

    font-size: 21px;

    font-weight: 900;

    text-shadow:
        0 2px 5px rgba(37, 99, 235, 0.25);
}


/* =========================================
   LEFT → RIGHT
========================================= */

@keyframes documentsMoveRightToLeft {

    0% {
        transform: translateX(0);
    }

    100% {
        transform: translateX(-100%);
    }

}


/* Pause when mouse is over text */

.documents-moving-wrapper:hover
.documents-moving-text {

    animation-play-state: paused;
}


/* =========================================
   MOBILE
========================================= */

@media (max-width: 700px) {

    .documents-title-box {
        min-height: 82px;

        padding: 14px 15px;
    }

    .documents-title-icon {
        width: 48px;
        height: 48px;

        min-width: 48px;

        margin-right: 12px;

        font-size: 22px;

        border-radius: 12px;
    }

    .documents-title-content h2 {
        font-size: 19px;
    }

    .documents-moving-text {
        font-size: 14px;

        gap: 20px;

        animation-duration: 16s;
    }

    .moving-separator {
        font-size: 17px;
    }

}
    </style>


    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Application Details') }}
        </h2>
    </x-slot>


    <div class="details-page">

        <div class="details-wrapper px-4 sm:px-6 lg:px-8">

<!-- ANIMATED PAGE INTRO -->

<div class="page-intro">

    <!-- Icon -->
    <div class="page-intro-icon">
        📋
    </div>

    <!-- Text -->
    <div class="page-intro-content">

        <h1>
            Application Details
        </h1>

        <div class="page-intro-subtitle">

            <div class="page-intro-moving-text">

        <div class="intro-message">

        <span>
             View the complete information and current status of your application.
        </span>

         <span class="intro-separator">✦</span>

        <span>
            Track your application progress easily.
        </span>

        <span class="intro-separator">✦</span>

        <span>
            Stay updated with your service request.
        </span>

        <span class="intro-separator">✦</span>

        <span>
            Your application information is available here.
        </span>

        <span class="intro-separator">✦</span>

    </div>

            </div>

        </div>

    </div>

    <!-- Live indicator -->
    <div class="page-intro-live">

        <span class="page-intro-live-dot"></span>

        Live

    </div>

</div>


            <!-- MAIN CARD -->
            <div class="details-card">


                <!-- SERVICE HEADER -->
                <div class="service-header">

                    <div class="service-header-content">

                        <div class="service-left">

                            <div class="service-icon">
                                📄
                            </div>

                            <div>

                                <div class="service-label">
                                    Service Application
                                </div>

                                <div class="service-name">
                                    {{ $application->service->name }}
                                </div>

                                <div class="application-id">
                                    Application ID:
                                    <strong>#{{ $application->id }}</strong>
                                </div>

                            </div>

                        </div>


                        <!-- STATUS -->
                        <div class="header-status">

                            @if($application->status === 'pending')

                                <span class="status-badge status-pending">
                                    <span class="status-dot"></span>
                                    Pending
                                </span>

                            @elseif($application->status === 'processing')

                                <span class="status-badge status-processing">
                                    <span class="status-dot"></span>
                                    Processing
                                </span>

                            @elseif($application->status === 'completed')

                                <span class="status-badge status-completed">
                                    <span class="status-dot"></span>
                                    Completed
                                </span>

                            @elseif($application->status === 'rejected')

                                <span class="status-badge status-rejected">
                                    <span class="status-dot"></span>
                                    Rejected
                                </span>

                            @else

                                <span class="status-badge status-default">
                                    <span class="status-dot"></span>
                                    {{ ucfirst($application->status) }}
                                </span>

                            @endif

                        </div>

                    </div>

                </div>


                <!-- BODY -->
                <div class="details-body">


                    <!-- APPLICATION INFORMATION -->
                    <div class="section">

                        <div class="section-heading">

                            <div class="section-icon">
                                📋
                            </div>

                            <div>
                                <div class="section-title">
                                    Application Information
                                </div>

                                <div class="section-subtitle">
                                    Basic information about your application
                                </div>
                            </div>

                        </div>


                        <div class="info-grid">

                            <div class="info-box">

                                <span class="info-label">
                                    Application ID
                                </span>

                                <span class="info-value">
                                    #{{ $application->id }}
                                </span>

                            </div>


                            <div class="info-box">

                                <span class="info-label">
                                    Applied On
                                </span>

                                <span class="info-value">
                                    {{ $application->created_at->format('d M Y, H:i') }}
                                </span>

                            </div>


                            <div class="info-box">

                                <span class="info-label">
                                    Service
                                </span>

                                <span class="info-value">
                                    {{ $application->service->name }}
                                </span>

                            </div>


                            <div class="info-box">

                                <span class="info-label">
                                    Current Status
                                </span>

                                <span class="info-value">

                                    {{ ucfirst($application->status) }}

                                </span>

                            </div>

                        </div>

                    </div>


                    <!-- PAYMENT -->
                    <div class="section payment-section">

                        <div class="section-heading">

                            <div class="section-icon">
                                💰
                            </div>

                            <div>
                                <div class="section-title">
                                    Payment Summary
                                </div>

                                <div class="section-subtitle">
                                    Breakdown of your service charges
                                </div>
                            </div>

                        </div>


                        <div class="payment-card">

                            <div class="fee-row">

                                <span class="fee-name">
                                    Government Fee
                                </span>

                                <span class="fee-amount">
                                    TSh
                                    {{ number_format($application->service->government_fee, 2) }}
                                </span>

                            </div>


                            <div class="fee-row">

                                <span class="fee-name">
                                    Service Fee
                                </span>

                                <span class="fee-amount">
                                    TSh
                                    {{ number_format($application->service->service_fee, 2) }}
                                </span>

                            </div>


                            <div class="total-row">

                                <span class="total-label">
                                    Total Amount
                                </span>

                                <span class="total-amount">
                                    TSh
                                    {{ number_format(
                                        $application->service->government_fee +
                                        $application->service->service_fee,
                                        2
                                    ) }}
                                </span>

                            </div>

                        </div>

                    </div>


                    <!-- ADDITIONAL INFORMATION -->
                    <div class="section notes-section">

                        <div class="section-heading">

                            <div class="section-icon">
                                📝
                            </div>

                            <div>
                                <div class="section-title">
                                    Additional Information
                                </div>

                                <div class="section-subtitle">
                                    Information provided with your application
                                </div>
                            </div>

                        </div>


                        @if($application->notes)

                            <div class="notes-box">
                                {{ $application->notes }}
                            </div>

                        @else

                            <div class="notes-box no-notes">
                                No additional information was provided with this application.
                            </div>

                        @endif

                    </div>

                </div>


                <!-- FOOTER -->
                <div class="details-footer">

                    <div class="footer-message">
                        Need to view your other applications?
                    </div>

                    <a href="{{ route('customer.applications.index') }}"
                       class="back-button">
                        ← Back to My Applications
                    </a>

                </div>

             </div>


            <!-- =========================================
                 APPLICATION DOCUMENTS
            ========================================= -->

            <div class="documents-section">

                ...

    <div class="documents-header">

        <div class="documents-title-box">

            <div class="documents-title-icon">
                📎
            </div>

            <div class="documents-title-content">

                <h2>
                    Application Documents
                </h2>

                <div class="documents-moving-wrapper">

                    <div class="documents-moving-text">

                        <span>
                            Upload the documents required for this application.
                        </span>

                        <span class="moving-separator">✦</span>

                        <span>
                            Make sure your documents are clear and readable.
                        </span>

                        <span class="moving-separator">✦</span>

                        <span>
                            Accepted formats: PDF, JPG, JPEG and PNG.
                        </span>

                        <span class="moving-separator">✦</span>

                        <span>
                            Maximum file size is 5MB.
                        </span>

                        <span class="moving-separator">✦</span>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- SUCCESS MESSAGE -->

    @if(session('success'))

        <div class="document-success">

            <span class="success-check">
                ✓
            </span>

            <span>
                {{ session('success') }}
            </span>

        </div>

    @endif

    @if(session('success'))

        <div class="document-success">

            <span class="success-check">
                ✓
            </span>

            <span>
                {{ session('success') }}
            </span>

        </div>

    @endif


    <!-- UPLOAD FORM -->

    <form
        method="POST"
        action="{{ route('application.documents.store', $application) }}"
        enctype="multipart/form-data"
        class="document-upload-form"
    >

        @csrf


        <div class="upload-grid">

            <!-- DOCUMENT NAME -->

            <div class="upload-field">

                <label for="document_name">
                    Document Name
                </label>

                <input
                    type="text"
                    id="document_name"
                    name="document_name"
                    placeholder="Example: National ID"
                    value="{{ old('document_name') }}"
                    required
                >

                @error('document_name')

                    <span class="field-error">
                        {{ $message }}
                    </span>

                @enderror

            </div>


            <!-- FILE -->

            <div class="upload-field">

                <label for="document">
                    Select Document
                </label>

                <input
                    type="file"
                    id="document"
                    name="document"
                    accept=".pdf,.jpg,.jpeg,.png"
                    required
                >

                <small>
                    Allowed: PDF, JPG, JPEG, PNG. Maximum size: 5MB.
                </small>

                @error('document')

                    <span class="field-error">
                        {{ $message }}
                    </span>

                @enderror

            </div>

        </div>


        <button
            type="submit"
            class="upload-button"
        >
            📤 Upload Document
        </button>

    </form>


    <!-- UPLOADED DOCUMENTS -->

    <div class="uploaded-documents">

        <h3>
            Uploaded Documents
        </h3>


        @forelse($application->documents as $document)

            <div class="document-item">

                <div class="document-info">

                    <div class="document-file-icon">
                        📄
                    </div>

                    <div>

                        <div class="document-name">
                            {{ $document->document_name }}
                        </div>

                        <div class="document-meta">

                            {{ $document->file_name }}

                            •
                            
                            {{ number_format($document->file_size / 1024, 1) }} KB

                            •

                            {{ $document->created_at->format('d M Y, H:i') }}

                        </div>

                    </div>

                </div>


                <div class="document-actions">

                    <a
                        href="{{ asset('storage/' . $document->file_path) }}"
                        target="_blank"
                        class="document-view"
                    >
                        View
                    </a>


                    <form
                        method="POST"
                        action="{{ route('application.documents.destroy', $document) }}"
                    >

                        @csrf

                        @method('DELETE')

                        <button
                            type="submit"
                            class="document-delete"
                            onclick="return confirm('Are you sure you want to delete this document?')"
                        >
                            Delete
                        </button>

                    </form>

                </div>

            </div>

        @empty

            <div class="no-documents">

                <div class="no-documents-icon">
                    📂
                </div>

                <h4>
                    No documents uploaded yet
                </h4>

                <p>
                    Upload the required documents using the form above.
                </p>

            </div>

        @endforelse

    </div>

            </div>

        </div>

    </div>

</x-app-layout>