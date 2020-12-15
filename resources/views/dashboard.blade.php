<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-2xl mb-8">Your photo collages</h3>

                    <table class="w-full">
                        <thead>
                            <tr>
                                <th class="border-b-2">ID</th>
                                <th class="border-b-2">PHOTO</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($photoCollages as $photoCollage)
                                <tr>
                                    <td class="text-center border-b-2">{{ $photoCollage->id }}</td>
                                    <td class="border-b-2">
                                        <a href="{{ asset("storage/{$photoCollage->path}") }}" target="_blank">
                                            <img src="{{ asset("storage/{$photoCollage->path}") }}" class="w-40 mx-auto" />
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
