@extends('layouts.app')

@section('content')
    <div class="grid grid-cols-1 gap-6">
        <div class="bg-white border border-gray-200 shadow-2xs rounded-xl p-6">

            <form action="{{ route('admin.guru_mapel.store') }}" method="POST" class="space-y-4">
                @csrf

                {{-- Guru --}}
                <div>
                    <label for="user_id" class="block text-sm font-medium mb-2">
                        Guru
                    </label>

                    <select id="user_id" name="user_id" class="w-full">
                        <option value="" disabled {{ old('user_id') ? '' : 'selected' }}>
                            Pilih Guru
                        </option>
                        @foreach ($guru as $g)
                            <option value="{{ $g->id }}" {{ old('user_id') == $g->id ? 'selected' : '' }}>
                                {{ $g->name }}
                            </option>
                        @endforeach
                    </select>

                    @error('user_id')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Mata Pelajaran --}}
                <div>
                    <label for="mata_pelajaran_id" class="block text-sm font-medium mb-2">
                        Mata Pelajaran
                    </label>

                    <select id="mata_pelajaran_id" name="mata_pelajaran_id" class="w-full">
                        <option value="" disabled {{ old('mata_pelajaran_id') ? '' : 'selected' }}>
                            Pilih Mata Pelajaran
                        </option>
                        @foreach ($mapel as $m)
                            <option value="{{ $m->id }}" {{ old('mata_pelajaran_id') == $m->id ? 'selected' : '' }}>
                                {{ $m->nama_mapel }}
                            </option>
                        @endforeach
                    </select>

                    @error('mata_pelajaran_id')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tombol --}}
                <div class="grid grid-cols-1 gap-2 mt-6 sm:flex sm:justify-end sm:items-center">
                    <button type="submit"
                        class="w-full sm:w-auto py-3 px-4 inline-flex items-center justify-center gap-x-2
                        text-sm font-medium rounded-lg
                        bg-blue-600 text-white hover:bg-blue-700 focus:outline-none">
                        Simpan
                    </button>

                    <a href="{{ route('admin.guru_mapel.index') }}"
                        class="w-full sm:w-auto py-3 px-4 inline-flex items-center justify-center gap-x-2
                        text-sm font-medium rounded-lg border border-gray-500
                        text-gray-500 hover:border-gray-800 hover:text-gray-800">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- SELECT2 STYLE --}}
    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <style>
            /* container */
            .select2-container {
                width: 100% !important;
            }

            /* main input */
            .select2-container--default .select2-selection--single {
                min-height: 44px;
                padding: 10px 10px;              /* lebih natural */
                border-radius: 0.75rem;         /* lebih smooth */
                border: 1px solid #e5e7eb;      /* gray-200 */
                background-color: #ffffff;
                font-size: 0.875rem;
                display: flex;
                align-items: flex-start;
                box-sizing: border-box;
            }

            /* text */
            .select2-container--default .select2-selection__rendered {
                padding: 0;
                line-height: 1.5rem;
                color: #111827;
            }

            /* HIDE default arrow */
            .select2-container--default .select2-selection__arrow {
                display: none;
            }

            /* custom chevron */
            .select2-container--default .select2-selection--single::after {
                content: "";
                position: absolute;
                top: 50%;
                right: 14px;
                width: 18px;
                height: 18px;
                transform: translateY(-50%);
                background-repeat: no-repeat;
                background-size: 18px;
                background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
                pointer-events: none;
            }

            /* focus */
            .select2-container--default.select2-container--focus
            .select2-selection--single {
                border-color: #3b82f6;
                box-shadow: 0 0 0 1px #3b82f6;
            }

            /* dropdown */
            .select2-dropdown {
                border-radius: 0.75rem;
                border: 1px solid #e5e7eb;
                overflow: hidden;
            }

            /* search input */
            .select2-search__field {
                padding: 10px 14px;
                border-radius: 0.5rem;
                border: 1px solid #e5e7eb;
                font-size: 0.875rem;
                outline: none;
            }
        </style>
    @endpush

    {{-- SELECT2 SCRIPT --}}
    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#user_id, #mata_pelajaran_id').select2({
                    width: '100%',
                    allowClear: true
                });
            });
        </script>
    @endpush
@endsection
