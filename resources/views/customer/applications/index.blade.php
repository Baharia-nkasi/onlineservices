<x-app-layout>

    <style>
        /* ================================
           PAGE BACKGROUND
        ================================= */

        .applications-page {
            min-height: calc(100vh - 65px);
            padding: 45px 0 70px;
            background:
                radial-gradient(circle at top left, rgba(59, 130, 246, 0.10), transparent 35%),
                radial-gradient(circle at bottom right, rgba(16, 185, 129, 0.08), transparent 35%),
                #f8fafc;
        }


        /* ================================
           APPLICATION CARD
        ================================= */

        .application-card {
            background: rgba(255, 255, 255, 0.97);
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            padding: 24px;
            overflow: hidden;
            box-shadow:
                0 4px 8px rgba(15, 23, 42, 0.04),
                0 12px 30px rgba(15, 23, 42, 0.07);
            transition: all 0.25s ease;
        }

        .application-card:hover {
            transform: translateY(-3px);
            border-color: #bfdbfe;
            box-shadow:
                0 8px 15px rgba(15, 23, 42, 0.05),
                0 18px 40px rgba(37, 99, 235, 0.10);
        }


        /* ================================
           SERVICE SECTION
        ================================= */

        .service-section {
            display: flex;
            align-items: center;
            gap: 18px;
            min-width: 0;
        }

        .service-icon {
            width: 58px;
            height: 58px;
            min-width: 58px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 15px;
            background: linear-gradient(135deg, #eff6ff, #dbeafe);
            font-size: 25px;
            box-shadow: inset 0 0 0 1px rgba(37, 99, 235, 0.08);
        }

        .service-name {
            font-size: 19px;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.4;
        }


        /* ================================
           APPLICATION INFORMATION
        ================================= */

        .application-info {
            display: flex;
            flex-wrap: wrap;
            gap: 8px 22px;
            margin-top: 8px;
        }

        .info-item {
            font-size: 13px;
            color: #64748b;
        }

        .info-item strong {
            color: #334155;
            font-weight: 700;
        }


        /* ================================
           STATUS + BUTTON AREA
        ================================= */

        .application-actions {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            justify-content: center;
            gap: 10px;
            flex-shrink: 0;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-width: 125px;
            padding: 9px 16px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 800;
            white-space: nowrap;
            box-shadow: inset 0 0 0 1px rgba(0, 0, 0, 0.03);
        }

        .status-dot {
            width: 9px;
            height: 9px;
            border-radius: 50%;
            display: inline-block;
        }


        /* ================================
           PENDING
        ================================= */

        .status-pending {
            background: linear-gradient(135deg, #fffbeb, #fef3c7);
            color: #92400e;
            border: 1px solid #fde68a;
        }

        .status-pending .status-dot {
            background: #f59e0b;
            box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.13);
        }


        /* ================================
           PROCESSING
        ================================= */

        .status-processing {
            background: linear-gradient(135deg, #eff6ff, #dbeafe);
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
        }

        .status-processing .status-dot {
            background: #2563eb;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.13);
        }


        /* ================================
           COMPLETED
        ================================= */

        .status-completed {
            background: linear-gradient(135deg, #f0fdf4, #dcfce7);
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .status-completed .status-dot {
            background: #16a34a;
            box-shadow: 0 0 0 4px rgba(22, 163, 74, 0.13);
        }


        /* ================================
           REJECTED
        ================================= */

        .status-rejected {
            background: linear-gradient(135deg, #fef2f2, #fee2e2);
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .status-rejected .status-dot {
            background: #dc2626;
            box-shadow: 0 0 0 4px rgba(220, 38, 38, 0.13);
        }


        /* ================================
           DEFAULT
        ================================= */

        .status-default {
            background: #f1f5f9;
            color: #374151;
            border: 1px solid #cbd5e1;
        }

        .status-default .status-dot {
            background: #64748b;
        }


        /* ================================
           VIEW DETAILS BUTTON
        ================================= */

        .view-details-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 125px;
            padding: 9px 16px;
            border-radius: 9px;
            background: #ffffff;
            color: #2563eb !important;
            border: 1px solid #bfdbfe;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .view-details-button:hover {
            background: #eff6ff;
            border-color: #2563eb;
            color: #1d4ed8 !important;
            transform: translateY(-1px);
            box-shadow: 0 5px 12px rgba(37, 99, 235, 0.12);
        }


        /* ================================
           NOTES
        ================================= */

        .application-notes {
            margin-top: 20px;
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
        }

        .notes-text {
            margin-top: 5px;
            font-size: 14px;
            line-height: 1.6;
            color: #64748b;
        }


        /* ================================
           EMPTY APPLICATIONS
        ================================= */

        .empty-applications {
            background: rgba(255, 255, 255, 0.97);
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            padding: 65px 30px;
            text-align: center;
            box-shadow:
                0 5px 12px rgba(15, 23, 42, 0.04),
                0 15px 35px rgba(15, 23, 42, 0.06);
        }

        .empty-icon {
            width: 75px;
            height: 75px;
            margin: 0 auto 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 20px;
            background: linear-gradient(135deg, #eff6ff, #dbeafe);
            font-size: 32px;
        }

        .empty-title {
            font-size: 22px;
            font-weight: 800;
            color: #0f172a;
        }

        .empty-text {
            margin-top: 8px;
            font-size: 14px;
            color: #64748b;
        }


        /* ================================
           BROWSE SERVICES BUTTON
        ================================= */

        .browse-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-top: 22px;
            padding: 11px 22px;
            border-radius: 9px;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: #ffffff !important;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            box-shadow: 0 5px 12px rgba(37, 99, 235, 0.20);
            transition: all 0.2s ease;
        }

        .browse-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 18px rgba(37, 99, 235, 0.28);
        }


        /* ================================
           MOBILE
        ================================= */

        @media (max-width: 768px) {

            .applications-page {
                padding: 30px 0 50px;
            }

            .application-card {
                padding: 20px;
            }

            .application-actions {
                align-items: flex-start;
                width: 100%;
            }

            .application-actions .status-badge,
            .application-actions .view-details-button {
                min-width: 120px;
            }

            .service-section {
                align-items: flex-start;
            }
        }


        @media (max-width: 480px) {

            .application-card {
                padding: 17px;
            }

            .service-icon {
                width: 48px;
                height: 48px;
                min-width: 48px;
                font-size: 21px;
            }

            .service-name {
                font-size: 16px;
            }

            .application-info {
                flex-direction: column;
                align-items: flex-start;
                gap: 5px;
            }

            .application-actions {
                margin-top: 5px;
            }
        }
    </style>


    <!-- HEADER -->

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Applications') }}
        </h2>
    </x-slot>


    <!-- PAGE -->

    <div class="applications-page">

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            @if($applications->count() > 0)

                <div class="space-y-5">

                    @foreach($applications as $application)

                        <div class="application-card">

                            <!-- MAIN FLEX -->

                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">

                                <!-- SERVICE -->

                                <div class="service-section">

                                    <div class="service-icon">
                                        📄
                                    </div>

                                    <div>

                                        <div class="service-name">
                                            {{ $application->service->name }}
                                        </div>

                                        <div class="application-info">

                                            <div class="info-item">
                                                <strong>Application ID:</strong>
                                                #{{ $application->id }}
                                            </div>

                                            <div class="info-item">
                                                <strong>Applied:</strong>
                                                {{ $application->created_at->format('d M Y, H:i') }}
                                            </div>

                                        </div>

                                    </div>

                                </div>


                                <!-- STATUS + VIEW DETAILS -->

                                <div class="application-actions">

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


                                    <a href="{{ route('customer.applications.show', $application) }}"
                                       class="view-details-button">
                                        View Details →
                                    </a>

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

                <!-- EMPTY STATE -->

                <div class="empty-applications">

                    <div class="empty-icon">
                        📄
                    </div>

                    <div class="empty-title">
                        No Applications Yet
                    </div>

                    <div class="empty-text">
                        You have not submitted any applications yet.
                    </div>

                    <a href="{{ route('services.index') }}"
                       class="browse-button">
                        Browse Services →
                    </a>

                </div>

            @endif

        </div>

    </div>

</x-app-layout>