@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">Shop by Department</h1>

        {{-- Tabs (Department names) --}}
        <div role="tablist" class="tabs tabs-boxed mb-4">
            @foreach ($departments as $index => $department)
                <a role="tab"
                   class="tab {{ $index === 0 ? 'tab-active' : '' }}"
                   id="tab-{{ $department->id }}"
                   onclick="showTab({{ $department->id }})">
                    {{ $department->name }}
                </a>
            @endforeach
        </div>

        {{-- Tab Content --}}
        @foreach ($departments as $index => $department)
            <div id="content-{{ $department->id }}"
                 class="{{ $index !== 0 ? 'hidden' : '' }}">
                <h2 class="text-xl font-semibold mb-4">{{ $department->name }} Categories</h2>

                @if($department->categories->count())
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach ($department->categories as $category)
                            <a href="{{ route('categories.show', $category->slug) }}" class="block border rounded-xl p-4 hover:shadow-md bg-white">
                                <h3 class="text-lg font-bold">{{ $category->name }}</h3>
                                <p class="text-sm text-gray-600">{{ $category->description }}</p>
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500">No active categories in this department yet.</p>
                @endif
            </div>
        @endforeach
    </div>

    {{-- Tab Switching Script --}}
    <script>
        function showTab(deptId) {
            // Hide all content sections
            document.querySelectorAll('[id^="content-"]').forEach(el => el.classList.add('hidden'));

            // Remove 'tab-active' from all tabs
            document.querySelectorAll('[role="tab"]').forEach(el => el.classList.remove('tab-active'));

            // Show selected content section
            document.getElementById('content-' + deptId).classList.remove('hidden');

            // Add active class to selected tab
            document.getElementById('tab-' + deptId).classList.add('tab-active');
        }
    </script>
@endsection
