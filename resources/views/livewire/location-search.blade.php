<div>
    <break/>
    <input
        type="text"
        class="border border-grey-300 rounded text-black"
        placeholder="Enter post location..."
        wire:model="query"
    />
    @if (!empty($suggestions))
        <ul class="absolute left-0 w-full bg-white border border-gray-300 rounded mt-1 z-10">
            @foreach ($suggestions as $suggestion)
                <li
                    class="cursor-pointer p-2 text-black hover:bg-gray-200"
                    wire:click="selectLocation('{{$suggestion}}')"
                >
                {{ $suggestion }}
                </li>
            @endforeach
        </ul>
    @endif
</div>
