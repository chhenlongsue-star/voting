<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PollMaster - Trending</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
    
    <nav class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-black text-blue-600">POLL<span class="text-gray-800 dark:text-white">MASTER</span></h1>
            <div class="space-x-4">
                @auth
                    <a href="{{ url('/dashboard') }}" class="font-bold text-gray-600 hover:text-blue-600">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-gray-600 font-medium">Log in</a>
                    <a href="{{ route('register') }}" class="bg-blue-600 text-white px-5 py-2 rounded-lg font-bold hover:bg-blue-700 transition">Get Started</a>
                @endauth
            </div>
        </div>
    </nav>

    <header class="max-w-7xl mx-auto px-6 py-16 text-center">
        <h2 class="text-5xl md:text-6xl font-extrabold tracking-tight mb-4">
            The World's <span class="text-blue-600">Favorite</span> Voting App
        </h2>
        <p class="text-xl text-gray-500 max-w-2xl mx-auto">
            Join thousands of users creating real-time polls and making data-driven decisions every day.
        </p>
    </header>

    <section class="max-w-3xl mx-auto px-6 pb-24">
        <div class="flex items-center space-x-2 mb-6 justify-center">
            <span class="flex h-3 w-3 rounded-full bg-red-500 animate-pulse"></span>
            <h3 class="text-lg font-black uppercase tracking-widest text-red-500">Trending Now</h3>
        </div>

        @if($trendingPoll)
            <div class="bg-white dark:bg-gray-800 p-10 rounded-3xl shadow-2xl border border-gray-100 dark:border-gray-700">
                <h4 class="text-2xl font-bold mb-8 text-center text-gray-800 dark:text-white">
                    {{ $trendingPoll->question }}
                </h4>
                
                <div class="space-y-6">
                    @php
                        $totalVotes = $trendingPoll->options->sum('votes_count');
                    @endphp

                    @foreach ($trendingPoll->options as $option)
                        @php
                            $percentage = $totalVotes > 0 ? ($option->votes_count / $totalVotes) * 100 : 0;
                        @endphp
                        
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="font-bold text-gray-700 dark:text-gray-300">{{ $option->option_text }}</span>
                                <span class="text-sm font-black text-blue-600">{{ round($percentage) }}%</span>
                            </div>
                            <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-4">
                                <div class="bg-blue-600 h-4 rounded-full transition-all duration-1000" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="mt-10 pt-6 border-t border-gray-50 dark:border-gray-700 text-center">
                    <p class="text-gray-500 text-sm mb-6 font-medium">
                        Currently <span class="text-blue-600 font-bold">{{ $totalVotes }}</span> people have participated in this poll.
                    </p>
                    <a href="{{ route('register') }}" class="inline-block w-full bg-gray-900 dark:bg-white dark:text-gray-900 text-white font-black py-4 rounded-xl hover:scale-[1.02] transition-transform">
                        CREATE AN ACCOUNT TO VOTE
                    </a>
                </div>
            </div>
        @else
            <div class="text-center p-12 bg-white rounded-3xl shadow-sm border border-dashed border-gray-300">
                <p class="text-gray-500 italic">No active polls at the moment. Be the first to create one!</p>
            </div>
        @endif
    </section>

</body>
</html>