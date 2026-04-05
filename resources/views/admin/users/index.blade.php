<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('User Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700">
                        {{ session('error') }}
                    </div>
                @endif

                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-gray-800 dark:text-gray-200 border-b border-gray-700 uppercase text-xs tracking-wider">
                            <th class="py-3 px-4">Name</th>
                            <th class="py-3 px-4">Email</th>
                            <th class="py-3 px-4">Role</th>
                            <th class="py-3 px-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 dark:text-gray-400">
                        @foreach($users as $user)
                            <tr class="border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-900 transition duration-150">
                                <td class="py-4 px-4">{{ $user->name }}</td>
                                <td class="py-4 px-4">{{ $user->email }}</td>
                                <td class="py-4 px-4">
                                    <span class="px-2 py-1 rounded-full text-xs font-bold {{ $user->role === 'admin' ? 'bg-red-500 text-white' : ($user->role === 'sub_admin' ? 'bg-purple-500 text-white' : 'bg-blue-500 text-white') }}">
                                        {{ strtoupper($user->role) }}
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-right">
                                    <div class="flex justify-end items-center space-x-3">
                                        {{-- 1. Edit Action with Hierarchy Protection --}}
                                        @if(Auth::user()->role === 'admin' || $user->role !== 'admin')
                                            <a href="{{ route('admin.users.edit', $user) }}" class="text-blue-500 hover:text-blue-700 hover:underline font-medium">
                                                Edit
                                            </a>
                                        @else
                                            <span class="text-gray-500 italic text-xs cursor-not-allowed" title="Admins are protected">
                                                Protected
                                            </span>
                                        @endif

                                        {{-- 2. Delete Action with Hierarchy Protection & Self-Check --}}
                                        @if(Auth::id() !== $user->id)
                                            @if(Auth::user()->role === 'admin' || $user->role !== 'admin')
                                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                                                    @csrf 
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            onclick="return confirm('Are you sure you want to delete {{ $user->name }}? This cannot be undone.')" 
                                                            class="text-red-500 hover:text-red-700 hover:underline font-medium">
                                                        Delete
                                                    </button>
                                                </form>
                                            @endif
                                        @else
                                            <span class="text-gray-500 text-xs">(You)</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-6">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>