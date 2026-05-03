@extends('layouts.master')

@section('title', 'User Management')

@section('page-header')
<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <h1 class="text-2xl md:text-3xl font-bold text-slate-900">User Management</h1>
        <p class="text-slate-500 mt-1">Kelola akun pengguna dan hak akses sistem.</p>
    </div>
    <button x-data @click="$dispatch('open-modal', 'add-user')" class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-all">
        <i data-lucide="user-plus" class="w-4 h-4"></i> Tambah User
    </button>
</div>
@endsection

@section('content')
<div x-data="userManagement()">
    @if(session('success'))
        <div class="mb-4 px-4 py-3 bg-emerald-500/15 border border-emerald-500/30 text-emerald-400 rounded-xl text-sm font-medium flex items-center gap-2">
            <i data-lucide="check-circle" class="w-4 h-4"></i>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 px-4 py-3 bg-red-500/15 border border-red-500/30 text-red-400 rounded-xl text-sm font-medium flex items-center gap-2">
            <i data-lucide="alert-circle" class="w-4 h-4"></i>
            {{ session('error') }}
        </div>
    @endif
    @if($errors->any())
        <div class="mb-4 px-4 py-3 bg-red-500/15 border border-red-500/30 text-red-400 rounded-xl text-sm font-medium">
            <div class="flex items-center gap-2 mb-2">
                <i data-lucide="alert-circle" class="w-4 h-4"></i>
                <span class="font-bold">Gagal menyimpan data:</span>
            </div>
            <ul class="list-disc list-inside ml-2 text-xs">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <x-card :noPadding="true">
        <x-table :headers="['User', 'Email', 'Role', 'Status', 'Ditambahkan Pada', 'Aksi']">
            @foreach($users as $u)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-3">
                            <div class="relative">
                                @php
                                    $initial = strtoupper(substr($u->name, 0, 1));
                                    $colors = ['from-brand-500 to-purple-600', 'from-emerald-500 to-teal-600', 'from-amber-500 to-orange-600', 'from-pink-500 to-rose-600'];
                                    $color = $colors[$u->id % count($colors)];
                                    $isOnline = $u->id === auth()->id();
                                @endphp
                                <div class="w-10 h-10 bg-gradient-to-br {{ $color }} rounded-xl flex items-center justify-center text-slate-800 text-sm font-bold">{{ $initial }}</div>
                                @if($isOnline)
                                    <div class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-emerald-400 rounded-full border-2 border-slate-300"></div>
                                @endif
                            </div>
                            <span class="text-sm font-medium text-slate-800">{{ $u->name }}</span>
                        </div>
                    </td>
                    <td class="px-5 py-3.5 text-sm text-slate-500">{{ $u->email }}</td>
                    <td class="px-5 py-3.5">
                        <x-badge :color="$u->role==='admin'?'brand':($u->role==='manager'?'purple':'emerald')">{{ ucfirst($u->role) }}</x-badge>
                    </td>
                    <td class="px-5 py-3.5"><x-badge :color="$isOnline?'emerald':'slate'">{{ $isOnline ? 'Online' : 'Offline' }}</x-badge></td>
                    <td class="px-5 py-3.5 text-sm text-slate-500">{{ $u->created_at->format('d M Y') }}</td>
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-1">
                            <button @click="editUser({{ $u->toJson() }})" class="p-2 text-slate-500 hover:text-blue-600 hover:bg-blue-700/10 rounded-lg transition-colors" title="Edit">
                                <i data-lucide="pencil" class="w-4 h-4"></i>
                            </button>
                            @if($u->id !== auth()->id())
                            <button @click="confirmDelete({{ $u->id }})" class="p-2 text-slate-500 hover:text-red-400 hover:bg-red-500/10 rounded-lg transition-colors" title="Hapus">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach
        </x-table>
    </x-card>

    {{-- Modal Form (Add & Edit) --}}
    <div x-show="showFormModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/40 backdrop-blur-sm" @open-modal.window="if($event.detail === 'add-user') { openAdd(); }">
        <div @click.away="closeForm()" class="bg-white border border-slate-300 rounded-2xl p-6 w-full max-w-md shadow-lg">
            <h2 class="text-xl font-bold text-slate-800 mb-4" x-text="isEdit ? 'Edit User' : 'Tambah User Baru'"></h2>
            <form :action="formAction" method="POST" class="space-y-4">
                @csrf
                <template x-if="isEdit">
                    <input type="hidden" name="_method" value="PUT">
                </template>
                <input type="hidden" name="id" x-model="form.id">
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Nama Lengkap</label>
                    <input type="text" name="name" x-model="form.name" required class="w-full bg-white border border-slate-300 rounded-xl px-4 py-2.5 text-slate-900 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Email</label>
                    <input type="email" name="email" x-model="form.email" required class="w-full bg-white border border-slate-300 rounded-xl px-4 py-2.5 text-slate-900 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Role</label>
                    <select name="role" x-model="form.role" required class="w-full bg-white border border-slate-300 rounded-xl px-4 py-2.5 text-slate-900 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all appearance-none">
                        <option value="kasir">Kasir</option>
                        <option value="admin">Administrator</option>
                        <option value="manager">Manager</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">
                        Password <span x-show="isEdit" class="text-xs text-slate-500 font-normal">(Kosongkan jika tidak ingin diubah)</span>
                    </label>
                    <input type="password" name="password" x-bind:required="!isEdit" class="w-full bg-white border border-slate-300 rounded-xl px-4 py-2.5 text-slate-900 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all">
                </div>
                
                <div class="flex gap-3 mt-6">
                    <button type="button" @click="closeForm()" class="flex-1 py-2.5 bg-white hover:bg-slate-50 text-slate-700 rounded-xl transition-colors font-medium">Batal</button>
                    <button type="submit" class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-sm transition-all font-medium">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Delete Modal --}}
    <div x-show="showDeleteModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/40 backdrop-blur-sm">
        <div @click.away="showDeleteModal = false" class="bg-white border border-slate-300 rounded-2xl p-6 w-full max-w-sm shadow-lg text-center">
            <div class="w-12 h-12 bg-red-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                <i data-lucide="alert-triangle" class="w-6 h-6 text-red-400"></i>
            </div>
            <h2 class="text-lg font-bold text-slate-800 mb-2">Hapus User?</h2>
            <p class="text-sm text-slate-500 mb-6">Tindakan ini tidak dapat dibatalkan. User akan dihapus secara permanen.</p>
            <form :action="deleteAction" method="POST" class="flex gap-3">
                @csrf
                @method('DELETE')
                <button type="button" @click="showDeleteModal = false" class="flex-1 py-2.5 bg-white hover:bg-slate-50 text-slate-700 rounded-xl transition-colors font-medium">Batal</button>
                <button type="submit" class="flex-1 py-2.5 bg-red-500/20 hover:bg-red-500/30 text-red-400 border border-red-500/20 rounded-xl transition-all font-medium">Ya, Hapus</button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function userManagement() {
    return {
        showFormModal: false,
        showDeleteModal: false,
        isEdit: false,
        formAction: '{{ old('_method') === 'PUT' && old('id') ? route("users.update", old("id")) : route("users.store") }}',
        deleteAction: '',
        form: { 
            id: '{{ old("id", "") }}', 
            name: '{{ old("name", "") }}', 
            email: '{{ old("email", "") }}', 
            role: '{{ old("role", "kasir") }}' 
        },
        
        init() {
            @if($errors->any())
                this.showFormModal = true;
            @endif
        },
        
        openAdd() {
            this.isEdit = false;
            this.formAction = '{{ route("users.store") }}';
            this.form = { id: '', name: '', email: '', role: 'kasir' };
            this.showFormModal = true;
        },
        
        editUser(user) {
            this.isEdit = true;
            this.formAction = `/users/${user.id}`;
            this.form = { id: user.id, name: user.name, email: user.email, role: user.role };
            this.showFormModal = true;
        },
        
        closeForm() {
            this.showFormModal = false;
        },
        
        confirmDelete(id) {
            this.deleteAction = `/users/${id}`;
            this.showDeleteModal = true;
        }
    }
}
</script>
@endpush
@endsection
