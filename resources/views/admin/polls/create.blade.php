<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Voting Poll') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form action="{{ route('admin.polls.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Category:</label>
                        <select name="category_id" class="w-full border-gray-300 rounded-md shadow-sm" required>
                            <option value="">-- Select a Category --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Poll Question:</label>
                        <input type="text" name="question" placeholder="Example: Which is the best anime?" 
                               class="w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>

                    <div id="options-container" class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Options:</label>
                        
                        <div class="flex items-center mb-2">
                            <input type="text" name="options[]" placeholder="Option 1 (e.g. Demon Slayer)" 
                                   class="w-full border-gray-300 rounded-md shadow-sm mr-2" required>
                        </div>

                        <div class="flex items-center mb-2">
                            <input type="text" name="options[]" placeholder="Option 2 (e.g. Attack on Titan)" 
                                   class="w-full border-gray-300 rounded-md shadow-sm mr-2" required>
                        </div>
                    </div>

                    <button type="button" id="add-option" class="text-blue-500 hover:text-blue-700 text-sm mb-4">
                        + Add another option
                    </button>

                    <div class="mt-6 flex justify-between items-center">
                        <a href="{{ route('admin.polls.index') }}" class="text-gray-600 hover:underline text-sm">
                            &larr; Back to List
                        </a>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow">
                            Save Poll
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        document.getElementById('add-option').addEventListener('click', function() {
            let container = document.getElementById('options-container');
            
            // Create a wrapper div for the input and the remove button
            let wrapper = document.createElement('div');
            wrapper.className = 'flex items-center mb-2';

            // Create the new input field
            let input = document.createElement('input');
            input.type = 'text';
            input.name = 'options[]';
            input.placeholder = 'New Option';
            input.className = 'w-full border-gray-300 rounded-md shadow-sm mr-2';
            input.required = true;

            // Create the remove button (X)
            let removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.innerHTML = '&times;'; // HTML entity for the multiplication sign (X)
            removeBtn.className = 'text-red-500 hover:text-red-700 font-bold text-xl px-2';
            
            // Function to remove this specific row
            removeBtn.onclick = function() {
                wrapper.remove();
            };

            // Assemble and append
            wrapper.appendChild(input);
            wrapper.appendChild(removeBtn);
            container.appendChild(wrapper);
        });
    </script>
</x-app-layout>