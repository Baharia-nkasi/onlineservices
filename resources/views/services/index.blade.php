<x-app-layout>

    <style>
        .btn-apply {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #2563eb;
            color: #ffffff !important;
            font-size: 15px;
            font-weight: 700;
            text-decoration: none;
            padding: 11px 22px;
            border-radius: 8px;
            border: 2px solid #2563eb;
            box-shadow: 0 3px 8px rgba(37, 99, 235, 0.25);
            transition: all 0.2s ease;
        }

        .btn-apply:hover {
            background: #1d4ed8;
            border-color: #1d4ed8;
            color: #ffffff !important;
            transform: translateY(-1px);
            box-shadow: 0 5px 12px rgba(37, 99, 235, 0.35);
        }

        .btn-apply:focus {
            outline: 3px solid rgba(37, 99, 235, 0.30);
            outline-offset: 2px;
        }
    </style>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Available Services') }}
        </h2>
    </x-slot>

    <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

@if(session('success'))
    <style>
        .success-alert {
            display: flex;
            align-items: center;
            gap: 14px;
            background: #ecfdf5;
            border: 1px solid #86efac;
            border-left: 5px solid #16a34a;
            color: #166534;
            padding: 16px 20px;
            margin-bottom: 24px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(22, 163, 74, 0.12);
        }

        .success-icon {
            width: 34px;
            height: 34px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #16a34a;
            color: #ffffff;
            border-radius: 50%;
            font-size: 20px;
            font-weight: bold;
            flex-shrink: 0;
        }

        .success-content {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .success-title {
            font-size: 15px;
            font-weight: 700;
            color: #15803d;
        }

        .success-message {
            font-size: 14px;
            color: #166534;
        }
    </style>

    <div class="success-alert">
        <div class="success-icon">
            ✓
        </div>

        <div class="success-content">
            <div class="success-title">
                Application Submitted Successfully
            </div>

            <div class="success-message">
                {{ session('success') }}
            </div>
        </div>
    </div>
@endif

        @if($services->count() > 0)

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                @foreach($services as $service)

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                        <h3 class="text-lg font-bold text-gray-900">
                            {{ $service->name }}
                        </h3>

                        <p class="mt-3 text-sm text-gray-600">
                            {{ $service->description }}
                        </p>

                        <div class="mt-4">
                            <span class="font-semibold">
                                Service Fee:
                            </span>

                            TSh {{ number_format($service->service_fee, 2) }}
                        </div>

                        <div class="mt-4">
                            <a href="{{ route('applications.create', $service) }}"
                               class="btn-apply">
                                Apply Now
                            </a>
                        </div>

                    </div>

                @endforeach

            </div>

        @else

            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <p class="text-gray-600">
                    No services are currently available.
                </p>
            </div>

        @endif

    </div>
</div>

</x-app-layout>