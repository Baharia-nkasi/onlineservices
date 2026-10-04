<x-app-layout>

    <style>

        /* =========================================
           DASHBOARD PAGE
        ========================================= */

        .dashboard-page {
            min-height: calc(100vh - 65px);
            padding: 40px 0 70px;

            background:
                radial-gradient(
                    circle at 10% 0%,
                    rgba(37, 99, 235, 0.10),
                    transparent 28%
                ),
                radial-gradient(
                    circle at 95% 15%,
                    rgba(16, 185, 129, 0.08),
                    transparent 25%
                ),
                linear-gradient(
                    180deg,
                    #f8fafc 0%,
                    #eef4f9 100%
                );
        }

        .dashboard-wrapper {
            max-width: 1200px;
            margin: auto;
        }

        /* =========================================
           WELCOME BANNER
        ========================================= */

        .welcome-banner {
            position: relative;
            padding: 30px;
            margin-bottom: 28px;

            background:
                linear-gradient(
                    135deg,
                    #1d4ed8 0%,
                    #2563eb 55%,
                    #3b82f6 100%
                );

            border-radius: 22px;
            color: white;
            overflow: hidden;

            box-shadow:
                0 18px 40px rgba(37, 99, 235, 0.20);
        }

        .welcome-banner::before {
            content: "";
            position: absolute;

            width: 250px;
            height: 250px;

            right: -80px;
            top: -120px;

            border-radius: 50%;
            background: rgba(255,255,255,0.08);
        }

        .welcome-banner::after {
            content: "";
            position: absolute;

            width: 180px;
            height: 180px;

            left: 45%;
            bottom: -120px;

            border-radius: 50%;
            background: rgba(255,255,255,0.06);
        }

        .welcome-content {
            position: relative;
            z-index: 2;

            display: flex;
            align-items: center;
            justify-content: space-between;

            gap: 25px;
        }

        .welcome-text h1 {
            margin: 0 0 8px;

            font-size: 30px;
            font-weight: 800;
            letter-spacing: -0.5px;
        }

        .welcome-text p {
            margin: 0;

            font-size: 14px;
            color: rgba(255,255,255,0.85);
        }

        .welcome-icon {
            width: 70px;
            height: 70px;

            display: flex;
            align-items: center;
            justify-content: center;

            background: rgba(255,255,255,0.15);

            border: 1px solid rgba(255,255,255,0.25);

            border-radius: 18px;

            font-size: 32px;

            backdrop-filter: blur(8px);

            animation: dashboardFloat 3s ease-in-out infinite;
        }

        @keyframes dashboardFloat {

            0%, 100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-5px);
            }

        }

        /* =========================================
           STATISTICS
        ========================================= */

        .stats-grid {
            display: grid;

            grid-template-columns:
                repeat(4, minmax(0, 1fr));

            gap: 18px;

            margin-bottom: 30px;
        }

        .stat-card {
            position: relative;

            padding: 22px;

            background: white;

            border: 1px solid #e5e7eb;

            border-radius: 18px;

            box-shadow:
                0 8px 25px rgba(15,23,42,0.05);

            transition: all 0.25s ease;

            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-4px);

            box-shadow:
                0 15px 30px rgba(15,23,42,0.09);
        }

        .stat-top {
            display: flex;

            align-items: center;
            justify-content: space-between;

            margin-bottom: 18px;
        }

        .stat-icon {
            width: 45px;
            height: 45px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 12px;

            font-size: 20px;
        }

        .stat-blue {
            background: #eff6ff;
            color: #2563eb;
        }

        .stat-yellow {
            background: #fffbeb;
            color: #d97706;
        }

        .stat-purple {
            background: #f5f3ff;
            color: #7c3aed;
        }

        .stat-green {
            background: #ecfdf5;
            color: #16a34a;
        }

        .stat-label {
            color: #64748b;

            font-size: 13px;
            font-weight: 600;
        }

        .stat-number {
            color: #0f172a;

            font-size: 28px;
            font-weight: 850;
        }

        /* =========================================
           SECTION
        ========================================= */

        .dashboard-section {
            margin-bottom: 30px;
        }

        .section-header {
            display: flex;

            align-items: center;
            justify-content: space-between;

            gap: 15px;

            margin-bottom: 18px;
        }

        .section-header h2 {
            margin: 0;

            color: #111827;

            font-size: 20px;
            font-weight: 800;
        }

        .section-header p {
            margin: 4px 0 0;

            color: #94a3b8;

            font-size: 13px;
        }

        .section-link {
            color: #2563eb;

            font-size: 13px;
            font-weight: 700;

            text-decoration: none;
        }

        .section-link:hover {
            color: #1d4ed8;
        }

        /* =========================================
           SERVICES
        ========================================= */

        .services-grid {
            display: grid;

            grid-template-columns:
                repeat(3, minmax(0, 1fr));

            gap: 18px;
        }

        .service-card {
            padding: 22px;

            background: white;

            border: 1px solid #e5e7eb;

            border-radius: 17px;

            box-shadow:
                0 7px 20px rgba(15,23,42,0.04);

            transition: all 0.25s ease;
        }

        .service-card:hover {
            transform: translateY(-3px);

            border-color: #bfdbfe;

            box-shadow:
                0 12px 25px rgba(37,99,235,0.09);
        }

        .service-card-icon {
            width: 45px;
            height: 45px;

            display: flex;
            align-items: center;
            justify-content: center;

            background: #eff6ff;

            color: #2563eb;

            border-radius: 12px;

            font-size: 20px;

            margin-bottom: 15px;
        }

        .service-card h3 {
            margin: 0 0 8px;

            color: #111827;

            font-size: 15px;
            font-weight: 800;
        }

        .service-card p {
            margin: 0 0 17px;

            color: #64748b;

            font-size: 13px;

            line-height: 1.6;
        }

        .service-button {
            display: inline-flex;

            align-items: center;
            justify-content: center;

            padding: 9px 15px;

            background: #2563eb;

            color: white !important;

            border-radius: 8px;

            font-size: 12px;
            font-weight: 700;

            text-decoration: none;

            transition: all 0.2s ease;
        }

        .service-button:hover {
            background: #1d4ed8;

            transform: translateY(-1px);
        }

        /* =========================================
           RECENT APPLICATIONS
        ========================================= */

        .applications-card {
            background: white;

            border: 1px solid #e5e7eb;

            border-radius: 18px;

            overflow: hidden;

            box-shadow:
                0 8px 25px rgba(15,23,42,0.05);
        }

        .application-row {
            display: flex;

            align-items: center;
            justify-content: space-between;

            gap: 20px;

            padding: 18px 22px;

            border-bottom: 1px solid #f1f5f9;
        }

        .application-row:last-child {
            border-bottom: none;
        }

        .application-main {
            display: flex;

            align-items: center;

            gap: 14px;

            min-width: 0;
        }

        .application-icon {
            width: 42px;
            height: 42px;

            min-width: 42px;

            display: flex;
            align-items: center;
            justify-content: center;

            background: #eff6ff;

            color: #2563eb;

            border-radius: 11px;

            font-size: 18px;
        }

        .application-name {
            color: #111827;

            font-size: 14px;
            font-weight: 750;
        }

        .application-date {
            margin-top: 3px;

            color: #94a3b8;

            font-size: 11px;
        }

        .application-right {
            display: flex;

            align-items: center;

            gap: 12px;

            flex-shrink: 0;
        }

        .status {
            padding: 6px 10px;

            border-radius: 999px;

            font-size: 11px;

            font-weight: 800;
        }

        .status-pending {
            background: #fffbeb;
            color: #92400e;
        }

        .status-processing {
            background: #eff6ff;
            color: #1d4ed8;
        }

        .status-completed {
            background: #ecfdf5;
            color: #166534;
        }

        .status-rejected {
            background: #fef2f2;
            color: #991b1b;
        }

        .details-button {
            color: #2563eb;

            font-size: 12px;

            font-weight: 700;

            text-decoration: none;
        }

        .details-button:hover {
            color: #1d4ed8;
        }

        /* =========================================
           EMPTY STATE
        ========================================= */

        .empty-state {
            padding: 45px 20px;

            text-align: center;

            color: #94a3b8;
        }

        .empty-icon {
            font-size: 35px;

            margin-bottom: 10px;
        }

        .empty-state h3 {
            margin: 0 0 5px;

            color: #475569;

            font-size: 15px;

            font-weight: 750;
        }

        .empty-state p {
            margin: 0;

            font-size: 13px;
        }

        /* =========================================
           MOBILE
        ========================================= */

        @media (max-width: 900px) {

            .stats-grid {
                grid-template-columns:
                    repeat(2, minmax(0, 1fr));
            }

            .services-grid {
                grid-template-columns:
                    repeat(2, minmax(0, 1fr));
            }

        }

        @media (max-width: 640px) {

            .dashboard-page {
                padding: 25px 0 50px;
            }

            .welcome-banner {
                padding: 23px;

                border-radius: 18px;
            }

            .welcome-content {
                align-items: flex-start;
            }

            .welcome-text h1 {
                font-size: 23px;
            }

            .welcome-text p {
                font-size: 12px;
            }

            .welcome-icon {
                width: 55px;
                height: 55px;

                min-width: 55px;

                font-size: 25px;
            }

            .stats-grid {
                grid-template-columns: 1fr;

                gap: 12px;
            }

            .services-grid {
                grid-template-columns: 1fr;
            }

            .application-row {
                align-items: flex-start;

                flex-direction: column;

                gap: 12px;
            }

            .application-right {
                width: 100%;

                justify-content: space-between;
            }

            .section-header {
                align-items: flex-start;
            }

        }

    </style>


    <!-- HEADER -->

    <x-slot name="header">

        <h2 class="font-semibold text-xl text-gray-800 leading-tight">

            {{ __('Customer Dashboard') }}

        </h2>

    </x-slot>


    <!-- DASHBOARD -->

    <div class="dashboard-page">

        <div class="dashboard-wrapper px-4 sm:px-6 lg:px-8">


            <!-- WELCOME -->

            <div class="welcome-banner">

                <div class="welcome-content">

                    <div class="welcome-text">

                        <h1>
                            Welcome, {{ Auth::user()->name }} 👋
                        </h1>

                        <p>
                            Manage your online service applications
                            and track their progress from one place.
                        </p>

                    </div>


                    <div class="welcome-icon">
                        🖥️
                    </div>

                </div>

            </div>


            <!-- STATISTICS -->

            <div class="stats-grid">


                <!-- TOTAL -->

                <div class="stat-card">

                    <div class="stat-top">

                        <div class="stat-icon stat-blue">
                            📋
                        </div>

                    </div>

                    <div class="stat-label">
                        Total Applications
                    </div>

                    <div class="stat-number">
                        {{ $totalApplications }}
                    </div>

                </div>


                <!-- PENDING -->

                <div class="stat-card">

                    <div class="stat-top">

                        <div class="stat-icon stat-yellow">
                            ⏳
                        </div>

                    </div>

                    <div class="stat-label">
                        Pending
                    </div>

                    <div class="stat-number">
                        {{ $pendingApplications }}
                    </div>

                </div>


                <!-- PROCESSING -->

                <div class="stat-card">

                    <div class="stat-top">

                        <div class="stat-icon stat-purple">
                            ⚙️
                        </div>

                    </div>

                    <div class="stat-label">
                        Processing
                    </div>

                    <div class="stat-number">
                        {{ $processingApplications }}
                    </div>

                </div>


                <!-- COMPLETED -->

                <div class="stat-card">

                    <div class="stat-top">

                        <div class="stat-icon stat-green">
                            ✓
                        </div>

                    </div>

                    <div class="stat-label">
                        Completed
                    </div>

                    <div class="stat-number">
                        {{ $completedApplications }}
                    </div>

                </div>

            </div>


            <!-- AVAILABLE SERVICES -->

            <div class="dashboard-section">


                <div class="section-header">

                    <div>

                        <h2>
                            Available Services
                        </h2>

                        <p>
                            Choose a service and submit your application.
                        </p>

                    </div>


                    <a
                        href="{{ route('services.index') }}"
                        class="section-link"
                    >
                        View All →
                    </a>

                </div>


                <div class="services-grid">


                    @forelse($services as $service)


                        <div class="service-card">


                            <div class="service-card-icon">
                                📄
                            </div>


                            <h3>
                                {{ $service->name }}
                            </h3>


                            <p>
                                {{ \Illuminate\Support\Str::limit($service->description, 90) }}
                            </p>


                            <a
                                href="{{ route('applications.create', $service) }}"
                                class="service-button"
                            >
                                Apply Now →
                            </a>


                        </div>


                    @empty


                        <div class="empty-state">

                            <div class="empty-icon">
                                📭
                            </div>

                            <h3>
                                No services available
                            </h3>

                            <p>
                                Please check again later.
                            </p>

                        </div>


                    @endforelse


                </div>

            </div>


            <!-- RECENT APPLICATIONS -->

            <div class="dashboard-section">


                <div class="section-header">

                    <div>

                        <h2>
                            Recent Applications
                        </h2>

                        <p>
                            View the latest applications you have submitted.
                        </p>

                    </div>


                    <a
                        href="{{ route('customer.applications.index') }}"
                        class="section-link"
                    >
                        View All →
                    </a>

                </div>


                <div class="applications-card">


                    @forelse($recentApplications as $application)


                        <div class="application-row">


                            <div class="application-main">


                                <div class="application-icon">
                                    📄
                                </div>


                                <div>

                                    <div class="application-name">

                                        {{ $application->service->name }}

                                    </div>


                                    <div class="application-date">

                                        Application #{{ $application->id }}

                                        •

                                        {{ $application->created_at->format('d M Y, H:i') }}

                                    </div>

                                </div>

                            </div>


                            <div class="application-right">


                                @if($application->status === 'pending')

                                    <span class="status status-pending">
                                        Pending
                                    </span>


                                @elseif($application->status === 'processing')

                                    <span class="status status-processing">
                                        Processing
                                    </span>


                                @elseif($application->status === 'completed')

                                    <span class="status status-completed">
                                        Completed
                                    </span>


                                @elseif($application->status === 'rejected')

                                    <span class="status status-rejected">
                                        Rejected
                                    </span>


                                @else

                                    <span class="status">
                                        {{ ucfirst($application->status) }}
                                    </span>

                                @endif


                                <a
                                    href="{{ route('customer.applications.show', $application) }}"
                                    class="details-button"
                                >
                                    Details →
                                </a>


                            </div>

                        </div>


                    @empty


                        <div class="empty-state">

                            <div class="empty-icon">
                                📋
                            </div>

                            <h3>
                                No applications yet
                            </h3>

                            <p>
                                Start by selecting one of our available services.
                            </p>

                        </div>


                    @endforelse


                </div>

            </div>


        </div>

    </div>

</x-app-layout>