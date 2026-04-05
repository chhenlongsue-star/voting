<x-app-layout>
    <div class="py-12 bg-gray-900 min-h-screen text-gray-200">
        <div class="max-w-[95rem] mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-8 bg-gray-800 p-6 rounded-xl border border-gray-700 shadow-lg">
                <div class="flex justify-between items-center">
                    <div>
                        <span class="text-blue-400 text-xs font-bold uppercase tracking-widest">{{ $poll->category->name }}</span>
                        <h2 class="text-2xl font-bold mt-1 text-white">{{ $poll->question }}</h2>
                    </div>
                    <div class="text-right">
                        <p class="text-gray-400 text-xs uppercase font-bold">Total Participation</p>
                        <p class="text-4xl font-black text-blue-500">{{ $poll->options->sum('votes_count') }}</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
                
                <div class="xl:col-span-6">
                    <div class="bg-gray-800 rounded-xl border border-gray-700 p-6">
                        <h3 class="text-lg font-bold mb-6 flex items-center text-white">
                            <span class="mr-2 text-blue-500">🕒</span> Latest Polls
                        </h3>
                        <div class="space-y-4">
                            @foreach($latestPolls as $lp)
                                <div class="p-4 bg-gray-700/30 rounded-lg border border-gray-600 hover:border-blue-500 transition group">
                                    <div class="flex justify-between items-center">
                                        <div class="w-3/4">
                                            <span class="text-[10px] text-blue-400 font-bold uppercase">{{ $lp->category->name }}</span>
                                            <p class="font-semibold text-gray-100 truncate">{{ $lp->question }}</p>
                                        </div>
                                        <a href="{{ route('admin.polls.show', $lp->id) }}" class="text-xs bg-blue-600 hover:bg-blue-500 text-white px-3 py-1 rounded transition">View</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="xl:col-span-3">
                    <div class="bg-gray-800 rounded-xl border border-gray-700 p-6">
                        <h3 class="text-lg font-bold mb-6 flex items-center text-white">
                            <span class="mr-2 text-orange-500">🔥</span> Trending
                        </h3>
                        <div class="space-y-6">
                            @foreach($trendingPolls as $tp)
                                <a href="{{ route('admin.polls.show', $tp->id) }}" class="block group">
                                    <p class="text-sm font-bold text-gray-300 group-hover:text-white transition line-clamp-2">{{ $tp->question }}</p>
                                    <div class="flex justify-between mt-2">
                                        <span class="text-[10px] text-gray-500">{{ $tp->category->name }}</span>
                                        <span class="text-[10px] text-orange-400 font-bold">{{ $tp->total_votes }} votes</span>
                                    </div>
                                    <div class="w-full bg-gray-700 h-1 mt-2 rounded-full overflow-hidden">
                                        <div class="bg-orange-500 h-full" style="width: 65%"></div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="xl:col-span-3">
                    <div class="bg-gray-800 rounded-xl border border-gray-700 p-6">
                        <h3 class="text-lg font-bold mb-6 flex items-center text-white">
                            <span class="mr-2 text-purple-500">📁</span> Categories
                        </h3>
                        
                        <div class="mb-6">
                            <select onchange="window.location.href='/admin/polls?category_id=' + this.value" 
                                    class="w-full bg-gray-700 border-gray-600 text-gray-200 rounded-lg text-sm p-2.5">
                                <option value="">Select Category...</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex flex-wrap gap-2">
                            @foreach($categories as $cat)
                                <a href="/admin/polls?category_id={{ $cat->id }}" class="text-[10px] bg-purple-900/40 text-purple-400 px-3 py-1 rounded-full border border-purple-500/30 hover:bg-purple-500 hover:text-white transition">
                                    {{ $cat->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>