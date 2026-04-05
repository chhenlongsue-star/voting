<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Poll') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form action="{{ route('admin.polls.update', $poll->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-md">
                            <ul class="list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Category:</label>
                        <select name="category_id" class="w-full border-gray-300 rounded-md shadow-sm" required>
                            <option value="">-- Select a Category --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $poll->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Poll Question:</label>
                        <input type="text" name="question" value="{{ old('question', $poll->question) }}" 
                               class="w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>

                    <div class="mb-6 p-4 bg-gray-50 rounded-lg border">
                        <label class="inline-flex items-center cursor-pointer">
                            {{-- Hidden input sends '0' if checkbox is unchecked --}}
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1" {{ $poll->is_active ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                            <span class="ml-2 text-gray-700 font-bold">Allow Voting (Active)</span>
                        </label>
                        <p class="text-xs text-gray-500 mt-1 ml-7">If unchecked, this poll will be hidden from the public voting page.</p>
                    </div>

                    <div id="existing-options-container" class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Existing Options:</label>
                        @foreach ($poll->options as $option)
                            <div class="flex items-center mb-2">
                                <input type="text" name="existing_options[{{ $option->id }}][option_text]" 
                                       value="{{ old('existing_options.' . $option->id . '.option_text', $option->option_text) }}" 
                                       class="w-full border-gray-300 rounded-md shadow-sm mr-2" required>
                                
                                <button type="button" 
                                   onclick="if(confirm('Delete this option permanently from database?')) { document.getElementById('delete-option-{{ $option->id }}').submit(); }"
                                   class="text-red-500 hover:text-red-700 text-sm font-bold px-2">
                                    &times;
                                </button>
                            </div>
                        @endforeach
                    </div>

                    <div id="new-options-container" class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Add New Options:</label>
                        {{-- New options will be injected here via JS --}}
                    </div>

                    <button type="button" id="add-option" class="text-blue-500 hover:text-blue-700 text-sm mb-4">
                        + Add another option
                    </button>

                    <div class="mt-6 flex items-center justify-between">
                        <a href="{{ route('admin.polls.index') }}" class="text-gray-600 hover:underline text-sm">Cancel</a>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded shadow">
                            Update Poll
                        </button>
                    </div>
                </form>

                {{-- Hidden forms for deleting existing options from DB --}}
                @foreach ($poll->options as $option)
                    <form id="delete-option-{{ $option->id }}" action="{{ route('admin.polls.options.destroy', [$poll->id, $option->id]) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                @endforeach

            </div>
        </div>
    </div>

    <script>
        // JavaScript to add more option input fields dynamically with a remove button
        document.getElementById('add-option').addEventListener('click', function() {
            let container = document.getElementById('new-options-container');
            
            let wrapper = document.createElement('div');
            wrapper.className = 'flex items-center mb-2';

            let input = document.createElement('input');
            input.type = 'text';
            input.name = 'new_options[]';
            input.placeholder = 'New Option';
            input.className = 'w-full border-gray-300 rounded-md shadow-sm mr-2';
            input.required = true;

            let removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.innerHTML = '&times;';
            removeBtn.className = 'text-red-500 hover:text-red-700 font-bold text-xl px-2';
            removeBtn.onclick = function() {
                wrapper.remove();
            };

            wrapper.appendChild(input);
            wrapper.appendChild(removeBtn);
            container.appendChild(wrapper);
        });
    </script>
</x-app-layout>