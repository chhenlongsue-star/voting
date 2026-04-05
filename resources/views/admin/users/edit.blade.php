<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit User Permissions') }}: <span class="text-blue-500">{{ $user->name }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-8 border border-gray-200 dark:border-gray-700">
                
                <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-6">
                    @csrf 
                    @method('PUT')
                    
                    <div class="grid grid-cols-2 gap-4 mb-6 p-4 bg-gray-50 dark:bg-gray-900 rounded-lg border border-gray-100 dark:border-gray-700">
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">Full Name</p>
                            <input type="text" name="name" value="{{ $user->name }}" class="mt-1 block w-full bg-transparent border-gray-300 dark:border-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500">
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">Email Address</p>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400 font-medium">{{ $user->email }}</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Assign System Role</label>
                        <select name="role" class="w-full bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                            <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>Regular User (Voter Only)</option>
                            <option value="sub_admin" {{ $user->role == 'sub_admin' ? 'selected' : '' }}>Sub-Admin (Manage Polls)</option>
                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Full Admin (Master Control)</option>
                        </select>
                        <p class="mt-2 text-xs text-gray-500">Warning: Promoting a user to Admin gives them full access to the database.</p>
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t border-gray-100 dark:border-gray-700">
                        <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition underline">
                            Cancel and go back
                        </a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-bold shadow-lg transform active:scale-95 transition duration-150">
                            Update Permissions
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>