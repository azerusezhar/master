<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Data Master') }}
            </h2>
            <a href="{{ route('master.create') }}" 
               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                + Tambah Data
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    {{-- Alert Success --}}
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- Form Search & Filter --}}
                    <form method="GET" action="{{ route('master.index') }}" class="mb-6 flex gap-4">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Cari kode/nama..."
                               class="border rounded px-3 py-2 w-64">
                        <select name="status" class="border rounded px-3 py-2">
                            <option value="">Semua Status</option>
                            <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                        <button type="submit" class="bg-gray-500 hover:bg-gray-700 text-white px-4 py-2 rounded">
                            Cari
                        </button>
                        <a href="{{ route('master.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded">
                            Reset
                        </a>
                    </form>

                    {{-- Tabel Data --}}
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($data as $index => $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $data->firstItem() + $index }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap font-mono">{{ $item->kode }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $item->nama }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs rounded {{ $item->status == 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ ucfirst($item->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <a href="{{ route('master.edit', $item) }}" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                                        <form action="{{ route('master.destroy', $item) }}" method="POST" class="inline" 
                                              onsubmit="return confirm('Yakin hapus data ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">Tidak ada data.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{-- Pagination --}}
                    <div class="mt-4">
                        {{ $data->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
