<x-app-layout>

    <style>
        /* ========================================
           MAIN PAGE BACKGROUND
        ======================================== */

        .applications-wrapper {
            min-height: calc(100vh - 65px);
            padding: 45px 0 70px;
            background:
                radial-gradient(circle at top left, rgba(59, 130, 246, 0.10), transparent 35%),
                radial-gradient(circle at bottom right, rgba(16, 185, 129, 0.08), transparent 35%),
                #f8fafc;
        }


        /* ========================================
           PAGE HEADER
        ======================================== */

        .page-header-box {
            margin-bottom: 30px;
        }

        .page-title {
            font-size: 30px;
            font-weight: 800;
            letter-spacing: -0.5px;
            color: #111827;
        }

        .page-description {
            margin-top: 7px;
            font-size: 15px;
            color: #64748b;
        }


        /* ========================================
           APPLICATION LIST
        ======================================== */

        .applications-list {
            display: flex;
            flex-direction: column;
            gap: 18px;
        }


        /* ========================================
           APPLICATION CARD
        ======================================== */

        .application-card {
            position: relative;
            background: rgba(255, 255, 255, 0.96);
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            padding: 24px;
            box-shadow:
                0 4px 6px rgba(15, 23, 42, 0.03),
                0 12px 30px rgba(15, 23, 42, 0.06);
            transition:
                transform 0.25s ease,
                box-shadow 0.25s ease,
                border-color 0.25s ease;
        }

        .application-card:hover {
            transform: translateY(-3px);
            border-color: #bfdbfe;
            box-shadow:
                0 8px 12px rgba(15, 23, 42, 0.04),
                0 18px 40px rgba(37, 99, 235, 0.10);
        }


        /* ========================================
           CARD FLEX LAYOUT
        ======================================== */

        .application-flex {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 25px;
        }

        .application-left {
            display: flex;
            align-items: center;
            gap: 18px;
            flex: 1;
            min-width: 0;
        }


        /* ========================================
           SERVICE ICON
        ======================================== */

        .service-icon {
            width: 58px;
            height: 58px;
            min-width: 58px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 15px;
            background: linear-gradient(135deg, #eff6ff, #dbeafe);
            color: #2563eb;
            font-size: 24px;
            box-shadow:
                inset 0 0 0 1px rgba(37, 99, 235, 0.08);
        }


        /* ========================================
           SERVICE INFORMATION
        ======================================== */

        .service-content {
            min-width: 0;
        }

        .service-name {
            font-size: 18px;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.4;
            margin-bottom: 8px;
        }

        .application-details {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px 20px;
        }

        .detail-item {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 13px;
            color: #64748b;
        }

        .detail-item strong {
            color: #334155;
            font-weight: 700;
        }


        /* ========================================
           STATUS AREA
        ======================================== */

        .status-area {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            min-width: 125px;
            padding: 10px 16px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 800;
            letter-spacing: 0.1px;
            white-space: nowrap;
            box-shadow: inset 0 0 0 1px rgba(0, 0, 0, 0.04);
        }

        .status-dot {
            width: 9px;
            height: 9px;
            border-radius: 50%;
            display: inline-block;
            flex-shrink: 0;
        }


        /* ========================================
           PENDING
        ======================================== */

        .status-pending {
            color: #92400e;
            background: linear-gradient(135deg, #fffbeb, #fef3c7);
            border: 1px solid #fde68a;
        }

        .status-pending .status-dot {
            background: #f59e0b;
            box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.13);
        }


        /* ========================================
           PROCESSING
        ======================================== */

        .status-processing {
            color: #1d4ed8;
            background: linear-gradient(135deg, #eff6ff, #dbeafe);
            border: 1px solid #bfdbfe;
        }

        .status-processing .status-dot {
            background: #2563eb;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.13);
        }


        /* ========================================
           COMPLETED
        ======================================== */

        .status-completed {
            color: #166534;
            background: linear-gradient(135deg, #f0fdf4, #dcfce7);
            border: 1px solid #bbf7d0;
        }

        .status-completed .status-dot {
            background: #16a34a;
            box-shadow: 0 0 0 4px rgba(22, 163, 74, 0.13);
        }


        /* ========================================
           REJECTED
        ======================================== */

        .status-rejected {
            color: #991b1b;
            background: linear-gradient(135deg, #fef2f2, #fee2e2);
            border: 1px solid #fecaca;
        }

        .status-rejected .status-dot {
            background: #dc2626;
            box-shadow: 0 0 0 4px rgba(220, 38, 38, 0.13);
        }


        /* ========================================
           DEFAULT STATUS
        ======================================== */

        .status-default {
            color: #374151;
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
        }

        .status-default .status-dot {
            background: #64748b;
        }


        /* ========================================
           NOTES
        ======================================== */

        .application-notes {
            margin-top: 22px;
            padding: 15px 17px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
        }

        .notes-title {
            font-size: 12px;
            font-weight: 800;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }

        .notes-text {
            font-size: 14px;
            color: #64748b;
            line-height: 1.6;
        }


        /* ========================================
           EMPTY STATE
        ======================================== */

        .empty-state {
            background: rgba(255, 255, 255, 0.96);
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            padding: 70px 30px;
            text-align: center;
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06);
        }

        .empty-icon {
            width: 78px;
            height: 78px;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 22px;
            background: linear-gradient(135deg, #eff6ff, #dbeafe);
            color: #2563eb;
            font-size: 34px;
        }

        .empty-title {
            font-size: 22px;
            font-weight: 800;
            color: #0f172a;
        }

        .empty-description {
            margin-top: 8px;
            color: #64748b;
            font-size: 14px;
        }

        .browse-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-top: 24px;
            padding: 12px 22px;
            border-radius: 9px;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: #ffffff !important;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            box-shadow: 0 5px 12px rgba(37, 99, 235, 0.22);
            transition: all 0.2s ease;
        }

        .browse-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 18px rgba(37, 99, 235, 0.30);
        }


        /* ========================================
           MOBILE
        ======================================== */

        @media (max-width: 768px) {

            .applications-wrapper {
                padding: 30px 0 50px;
            }

            .page-title {
                font-size: 25px;
            }

            .application-flex {
                align-items: flex-start;
                flex-direction: column;
                gap: 18px;
            }

            .application-left {
                width: 100%;
            }

            .status-area {
                width: 100%;
                justify-content: flex-start;
            }

            .status-badge {
                min-width: 115px;
            }
        }


        @media (max-width: 480px) {

            .application-card {
                padding: 18px;
                border-radius: 15px;
            }

            .application-left {
                align-items: flex-start;
            }

            .service-icon {
                width: 48px;
                height: 48px;
                min-width: 48px;
                font-size: 20px;
                border-radius: 12px;
            }

            .service-name {
                font-size: 16px;
            }

            .application-details {
                flex-direction: column;
                align-items: flex-start;
                gap: 5px;
            }
        }
    </style>


    <!-- HEADER -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Applications') }}
        </h2>
    </x-slot>


    <!-- MAIN CONTENT -->

    <div class="applications-wrapper">

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- PAGE TITLE -->

            <div class="page-header-box">

                <h1 class="page-title">
                    My Applications
                </h1>

                <p class="page-description">
                    Track the progress of all your submitted service applications.
                </p>

            </div>


            @if($applications->count() > 0)

                <!-- APPLICATION LIST -->

                <div class="applications-list">

                    @foreach($applications as $application)

                        <div class="application-card">

                            <div class="application-flex">

                                <!-- LEFT SIDE -->

                                <div class="application-left">

                                    <div class="service-icon">
                                        📄
                                    </div>

                                    <div class="service-content">

                                        <div class="service-name">
                                            {{ $application->service->name }}
                                        </div>

                                        <div class="application-details">

                                            <div class="detail-item">
                                                <strong>ID:</strong>
                                                #{{ $application->id }}
                                            </div>

                                            <div class="detail-item">
                                                <strong>Applied:</strong>
                                                {{ $application->created_at->format('d M Y, H:i') }}
                                            </div>

                                        </div>

                                    </div>

                                </div>


                                <!-- RIGHT SIDE / STATUS -->

                                <div class="status-area">

                                    @if($application->status === 'pending')

                                        <span class="status-badge status-pending">
                                            <span class="status-dot"></span>
                                            🟡 Pending
                                        </span>

                                    @elseif($application->status === 'processing')

                                        <span class="status-badge status-processing">
                                            <span class="status-dot"></span>
                                            🔵 Processing
                                        </span>

                                    @elseif($application->status === 'completed')

                                        <span class="status-badge status-completed">
                                            <span class="status-dot"></span>
                                            🟢 Completed
                                        </span>

                                    @elseif($application->status === 'rejected')

                                        <span class="status-badge status-rejected">
                                            <span class="status-dot"></span>
                                            🔴 Rejected
                                        </span>

                                    @else

                                        <span class="status-badge status-default">
                                            <span class="status-dot"></span>
                                            {{ ucfirst($application->status) }}
                                        </span>

                                    @endif

                                </div>

                            </div>


                            <!-- NOTES -->

                            @if($application->notes)

                                <div class="application-notes">

                                    <div class="notes-title">
                                        Additional Information
                                    </div>

                                    <div class="notes-text">
                                        {{ $application->notes }}
                                    </div>

                                </div>

                            @endif

                        </div>

                    @endforeach

                </div>


            @else

                <!-- EMPTY APPLICATIONS -->

                <div class="empty-state">

                    <div class="empty-icon">
                        📄
                    </div>

                    <div class="empty-title">
                        No Applications Yet
                    </div>

                    <div class="empty-description">
                        You have not submitted any applications yet.
                    </div>

                    <a href="{{ route('services.index') }}"
                       class="browse-button">
                        Browse Services
                    </a>

                </div>

            @endif

        </div>

    </div>

</x-app-layout>