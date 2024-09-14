<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-row items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ __('Manage Courses') }}
            </h2>
            <a wire:navigate href="{{ route('admin.courses.create') }}"
                class="px-6 py-4 font-bold text-white bg-indigo-700 rounded-full">
                Add New
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="flex flex-col p-10 overflow-hidden bg-white shadow-sm sm:rounded-lg gap-y-5">
                @forelse ($courses as $course)
                    <div class="flex flex-col justify-between item-card md:flex-row gap-y-10 md:items-center">
                        <div class="flex flex-row items-center gap-x-3">
                            <img src="{{ Storage::url($course->thumbnail) }}" alt="{{ $course->name }}"
                                class="rounded-2xl object-cover w-[120px] h-[90px]">
                            <div class="flex flex-col">
                                <h3 class="text-xl font-bold text-indigo-950">{{ $course->name }}</h3>
                                <p class="text-sm text-slate-500">{{ $course->category->name }}</p>
                            </div>
                        </div>
                        <div class="flex-col hidden md:flex">
                            <p class="text-sm text-slate-500">Students</p>
                            <h3 class="text-xl font-bold text-indigo-950">{{ $course->students->count() }}</h3>
                        </div>
                        <div class="flex-col hidden md:flex">
                            <p class="text-sm text-slate-500">Videos</p>
                            <h3 class="text-xl font-bold text-indigo-950">{{ $course->course_videos->count() }}</h3>
                        </div>
                        <div class="flex-col hidden md:flex">
                            <p class="text-sm text-slate-500">Teacher</p>
                            <h3 class="text-xl font-bold text-indigo-950">{{ $course->teacher->user->name }}</h3>
                        </div>
                        <div class="flex-row items-center hidden md:flex gap-x-3">
                            <a wire:navigate href="{{ route('admin.courses.show', $course) }}"
                                class="px-6 py-4 font-bold text-white bg-indigo-700 rounded-full">
                                Manage
                            </a>
                            <form action="{{ route('admin.courses.destroy', $course) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-6 py-4 font-bold text-white bg-red-700 rounded-full">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p>Course empty</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
