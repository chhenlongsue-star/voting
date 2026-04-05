<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manage Categories') }}
            </h2>
            <a href="{{ route('admin.categories.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                + Add Category
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-100">
                <table class="w-full text-left table-auto">
    <thead>
        <tr class="border-b bg-gray-50">
            <th class="p-4 text-sm font-semibold text-gray-600">Name</th>
            <th class="p-4 text-sm font-semibold text-gray-600 text-center">Active Polls</th> <th class="p-4 text-sm font-semibold text-gray-600 text-right">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($categories as $category)
            <tr class="border-b hover:bg-gray-50">
                <td class="p-4 font-medium">{{ $category->name }}</td>
                
                <td class="p-4 text-center">
                    <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-xs font-bold">
                        {{ $category->polls_count }}
                    </span>
                </td>

                <td class="p-4 flex justify-end space-x-3">
                    <a href="{{ route('admin.categories.edit', $category->id) }}" class="text-yellow-600 hover:text-yellow-900">Edit</a>
                    
                    @if($category->polls_count == 0)
                        <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Delete this category?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                        </form>
                    @else
                        <span class="text-gray-300 cursor-not-allowed" title="Move polls to another category first">Delete</span>
                    @endif
                </td>
            </tr>
        @empty
            <tr><td colspan="3" class="p-8 text-center text-gray-500">No categories found.</td></tr>
        @endforelse
    </tbody>
</table>
            </div>
        </div>
    </div>
</x-app-layout>