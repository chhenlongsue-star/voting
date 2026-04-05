<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manage Polls') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('admin.categories.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">Categories</a>
                <a href="{{ route('admin.polls.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-700">+ Create Poll</a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white p-4 mb-6 rounded-lg shadow-sm border border-gray-200">
                <form action="{{ route('admin.polls.index') }}" method="GET" class="flex flex-col md:flex-row items-end space-y-4 md:space-y-0 md:space-x-4">
                    <div class="flex-1 w-full">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Search Question</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search..." class="w-full border-gray-300 rounded-md shadow-sm">
                    </div>

                    <div class="w-full md:w-64">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <select name="category_id" class="w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex space-x-2">
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">Search</button>
                        @if(request('category_id') || request('search'))
                            <a href="{{ route('admin.polls.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300">Reset</a>
                        @endif
                    </div>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                <table class="w-full text-left table-auto">
                    <thead>
                        <tr class="border-b bg-gray-50">
                            <th class="p-4 text-sm font-semibold text-gray-600">Poll Question</th>
                            <th class="p-4 text-sm font-semibold text-gray-600">Category</th>
                            <th class="p-4 text-sm font-semibold text-gray-600 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($polls as $poll)
                            <tr class="border-b hover:bg-gray-50 transition">
                                <td class="p-4">{{ $poll->question }}</td>
                                <td class="p-4">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium {{ $poll->category ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-600' }}">
                                        {{ $poll->category->name ?? 'Uncategorized' }}
                                    </span>
                                </td>
                                
                                <td class="p-4 flex justify-end space-x-3">
                                    <a href="{{ route('admin.polls.edit', $poll->id) }}" class="text-yellow-600 hover:text-yellow-900">Edit</a>
                                    <form action="{{ route('admin.polls.destroy', $poll->id) }}" method="POST" onsubmit="return confirm('Delete this poll?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                    </form>
                                </td>
                                <td class="p-4">
                                    @if($poll->is_active)
                                        <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full font-bold">Active</span>
                                    @else
                                        <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full font-bold">Closed</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="p-8 text-center text-gray-500">No polls found matching your criteria.</td></tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="p-4 bg-gray-50 border-t">
                    {{ $polls->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>