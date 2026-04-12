<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Available Polls') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Success Message --}}
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 shadow-sm rounded">
                    <p class="font-bold">Success!</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            {{-- Error Message --}}
            @if (session('error'))
                <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 shadow-sm rounded">
                    <p class="font-bold">Wait a minute...</p>
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach ($polls as $poll)
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 flex flex-col justify-between border {{ $poll->user_has_voted ? 'border-blue-200 dark:border-blue-900' : 'border-transparent' }}">
                        
                        <div>
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">
                                    {{ $poll->question }}
                                </h3>
                                @if($poll->user_has_voted)
                                    <span class="text-[10px] bg-blue-600 text-white px-2 py-1 rounded-full uppercase font-black ml-2 whitespace-nowrap">Voted</span>
                                @endif
                            </div>

                            <div class="mb-4">
                                <span class="bg-gray-100 text-gray-800 text-xs font-semibold px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">
                                    {{ $poll->category->name ?? 'Uncategorized' }}
                                </span>
                            </div>

                            @if($poll->user_has_voted)
                                {{-- Results View --}}
                                <div class="space-y-4 mt-4">
                                    @php
                                        $totalVotes = $poll->options->sum('votes_count');
                                    @endphp

                                    @foreach ($poll->options as $option)
                                        @php
                                            $percentage = $totalVotes > 0 ? ($option->votes_count / $totalVotes) * 100 : 0;
                                        @endphp
                                        
                                        <div class="relative pt-1">
                                            <div class="flex mb-2 items-center justify-between">
                                                <div>
                                                    <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-blue-600 bg-blue-100 dark:text-blue-200 dark:bg-blue-900">
                                                        {{ $option->option_text }}
                                                    </span>
                                                </div>
                                                <div class="text-right">
                                                    <span class="text-xs font-semibold inline-block text-blue-600 dark:text-blue-300">
                                                        {{ round($percentage) }}% ({{ $option->votes_count }})
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-blue-100 dark:bg-gray-700">
                                                <div style="width:{{ $percentage }}%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-blue-500 rounded">
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    <p class="text-center text-xs text-gray-400 mt-4 italic">You have already submitted your choice.</p>
                                </div>
                            @else
                                {{-- Voting Form - FIX: Route name changed to votes.store --}}
                                <form action="{{ route('votes.store', $poll->id) }}" method="POST">
                                    @csrf
                                    <div class="space-y-3">
                                        @foreach ($poll->options as $option)
                                            <div class="flex items-center">
                                                <input type="radio" name="option_id" id="option_{{ $option->id }}" value="{{ $option->id }}" class="mr-2 text-green-600 focus:ring-green-500" required>
                                                <label for="option_{{ $option->id }}" class="text-gray-700 dark:text-gray-300 cursor-pointer">
                                                    {{ $option->option_text }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>

                                    <button type="submit" class="mt-6 bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded w-full transition duration-150 shadow-sm">
                                        Submit Vote
                                    </button>
                                </form>
                            @endif
                        </div>

                        <div class="mt-6 pt-4 border-t border-gray-100 dark:border-gray-700 flex justify-between items-center text-xs text-gray-500">
                            <span>Total Participation:</span>
                            <span class="font-bold text-gray-800 dark:text-gray-200">
                                {{ $poll->options->sum('votes_count') }} 🗳️
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>
</x-app-layout>
