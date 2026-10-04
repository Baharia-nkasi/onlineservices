<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-slate-800">Admin Dashboard</h2></x-slot>

    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-4 text-emerald-800">{{ session('success') }}</div>
            @endif

            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-4">
                @foreach([
                    ['Applications',$stats['applications']],
                    ['Pending',$stats['pending']],
                    ['Processing',$stats['processing']],
                    ['Completed',$stats['completed']],
                    ['Rejected',$stats['rejected']],
                    ['Documents',$stats['documents']],
                    ['Active Services',$stats['services']]
                ] as [$label,$value])
                    <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm">
                        <div class="text-xs text-slate-500">{{ $label }}</div>
                        <div class="mt-2 text-2xl font-extrabold text-slate-900">{{ $value }}</div>
                    </div>
                @endforeach
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-5 border-b border-slate-200">
                    <h3 class="font-bold text-lg">Recent Applications</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50 text-left">
                            <tr><th class="p-4">ID</th><th class="p-4">Customer</th><th class="p-4">Service</th><th class="p-4">Status</th><th class="p-4">Date</th><th class="p-4"></th></tr>
                        </thead>
                        <tbody>
                        @forelse($applications as $application)
                            <tr class="border-t border-slate-100">
                                <td class="p-4 font-semibold">#{{ $application->id }}</td>
                                <td class="p-4">{{ $application->user->name }}<div class="text-xs text-slate-500">{{ $application->user->email }}</div></td>
                                <td class="p-4">{{ $application->service->name }}</td>
                                <td class="p-4"><span class="px-3 py-1 rounded-full text-xs font-bold bg-slate-100">{{ ucfirst($application->status) }}</span></td>
                                <td class="p-4">{{ $application->created_at->format('d M Y H:i') }}</td>
                                <td class="p-4"><a class="text-blue-600 font-bold hover:underline" href="{{ route('admin.applications.show',$application) }}">Review</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="p-10 text-center text-slate-500">No applications yet.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t">{{ $applications->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
