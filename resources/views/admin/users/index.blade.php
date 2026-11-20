@extends('layouts.admin')

@section('title', 'Manajemen Pengguna & Hak Akses (RBAC)')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Manajemen Pengguna & Hak Akses (RBAC)</h1>
            <p class="text-sm text-gray-500">Pengaturan akun Gembala, Bendahara, Ketua COOL per wilayah, dan Administrator.</p>
        </div>
        <div>
            <button type="button" onclick="openCreateModal()" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-primary rounded-lg hover:bg-primary-dark transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Pengguna Baru
            </button>
        </div>
    </div>

    <!-- Matriks Hak Akses Info Card -->
    <div class="bg-amber-50/70 border border-amber-200/80 rounded-xl p-4 text-xs text-amber-900 space-y-1.5">
        <span class="font-bold flex items-center gap-1.5 text-amber-950">
            <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            Panduan Pembagian Hak Akses (Role-Based Access Control)
        </span>
        <ul class="list-disc list-inside space-y-0.5 text-amber-800">
            <li><strong>Gembala:</strong> Akses penuh seluruh jemaat, sakramen, pastoral & warta; <em>Hanya Baca</em> laporan keuangan.</li>
            <li><strong>Bendahara:</strong> Akses penuh pencatatan kas/keuangan; akses baca data jemaat; <em>tidak bisa</em> ubah jadwal/sakramen.</li>
            <li><strong>Ketua COOL:</strong> Khusus mengelola jemaat & input kehadiran COOL di <strong>wilayah binaannya</strong>; <em>tidak bisa</em> lihat keuangan.</li>
            <li><strong>Admin Web:</strong> Mengelola konten web, jadwal ibadah, sakramen, pelayan ibadah, dan wilayah.</li>
        </ul>
    </div>

    <!-- Table of Users -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50/80">
                    <tr>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama & Email</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Peran / Role</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Wilayah Tugas (COOL)</th>
                        <th class="px-5 py-3.5 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-5 py-3.5 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white text-sm">
                    @foreach($users as $u)
                        <tr class="hover:bg-gray-50/80 transition">
                            <td class="px-5 py-4 whitespace-nowrap">
                                <div class="font-bold text-gray-900">{{ $u->name }}</div>
                                <div class="text-xs text-gray-500 font-mono">{{ $u->email }}</div>
                                @if($u->telepon)
                                    <div class="text-xs text-gray-400">WA: {{ $u->telepon }}</div>
                                @endif
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                @if($u->role === 'developer' || $u->role === 'super_admin')
                                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-bold bg-purple-50 text-purple-700 border border-purple-200">
                                        {{ strtoupper(str_replace('_', ' ', $u->role)) }}
                                    </span>
                                @elseif($u->role === 'gembala')
                                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                        Gembala Sidang
                                    </span>
                                @elseif($u->role === 'bendahara')
                                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Bendahara Jemaat
                                    </span>
                                @elseif($u->role === 'ketua_cool')
                                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                        Ketua COOL
                                    </span>
                                @else
                                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-bold bg-gray-100 text-gray-700">
                                        Admin Web
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-xs">
                                @if($u->role === 'ketua_cool')
                                    <span class="font-bold text-gray-900">
                                        {{ $u->wilayah?->nama_wilayah ?? 'Belum Ditugaskan' }}
                                    </span>
                                    <div class="text-xs text-gray-400">{{ $u->wilayah?->wilayah_besar }}</div>
                                @else
                                    <span class="text-gray-400">Seluruh Wilayah</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-center">
                                <span class="inline-flex px-2 py-0.5 rounded text-xs font-semibold {{ $u->status === 'aktif' ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                                    {{ ucfirst($u->status) }}
                                </span>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="inline-flex items-center gap-2">
                                    <button type="button" onclick='openEditModal(@json($u))' class="text-gray-500 hover:text-primary transition" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    @if(Auth::id() != $u->id)
                                        <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST" onsubmit="return confirm('Hapus akun pengguna {{ $u->name }}?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-gray-400 hover:text-rose-600 transition" title="Hapus">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah User Baru -->
<div id="createModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-gray-900/50 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl relative animate-in fade-in duration-200">
        <div class="flex items-center justify-between pb-4 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-900">Tambah Akun Pengguna</h3>
            <button type="button" onclick="closeCreateModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form action="{{ route('admin.users.store') }}" method="POST" class="mt-4 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Lengkap *</label>
                <input type="text" name="name" required placeholder="Contoh: Hendrik Ndolu" class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Email Login *</label>
                <input type="email" name="email" required placeholder="hendrik@shekinahglory.org" class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Password *</label>
                <input type="password" name="password" required minlength="6" placeholder="Minimal 6 karakter" class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Peran / Role *</label>
                    <select name="role" id="modal_role" required onchange="toggleWilayah(this.value, 'modal_wilayah_box')" class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                        <option value="ketua_cool">Ketua COOL</option>
                        <option value="bendahara">Bendahara</option>
                        <option value="gembala">Gembala Sidang</option>
                        <option value="admin">Admin Web</option>
                        <option value="super_admin">Super Admin</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">No. WhatsApp</label>
                    <input type="text" name="telepon" placeholder="08..." class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                </div>
            </div>

            <div id="modal_wilayah_box">
                <label class="block text-xs font-semibold text-gray-700 mb-1">Wilayah COOL yang Diampu *</label>
                <select name="wilayah_id" class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary font-medium">
                    @foreach($wilayahList as $w)
                        <option value="{{ $w->id }}">{{ $w->nama_wilayah }} ({{ $w->wilayah_besar }})</option>
                    @endforeach
                </select>
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100">
                <button type="button" onclick="closeCreateModal()" class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200">Batal</button>
                <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-primary rounded-lg hover:bg-primary-dark">Simpan Pengguna</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit User -->
<div id="editModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-gray-900/50 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl relative animate-in fade-in duration-200">
        <div class="flex items-center justify-between pb-4 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-900">Edit Akun Pengguna</h3>
            <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form id="editForm" method="POST" class="mt-4 space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Lengkap *</label>
                <input type="text" name="name" id="edit_name" required class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Email Login *</label>
                <input type="email" name="email" id="edit_email" required class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Reset Password (Kosongkan jika tidak diganti)</label>
                <input type="password" name="password" minlength="6" placeholder="Ketik password baru..." class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Peran / Role *</label>
                    <select name="role" id="edit_role" required onchange="toggleWilayah(this.value, 'edit_wilayah_box')" class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                        <option value="ketua_cool">Ketua COOL</option>
                        <option value="bendahara">Bendahara</option>
                        <option value="gembala">Gembala Sidang</option>
                        <option value="admin">Admin Web</option>
                        <option value="super_admin">Super Admin</option>
                        <option value="developer">Developer</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Status Akun</label>
                    <select name="status" id="edit_status" required class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">No. WhatsApp</label>
                <input type="text" name="telepon" id="edit_telepon" placeholder="08..." class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
            </div>

            <div id="edit_wilayah_box">
                <label class="block text-xs font-semibold text-gray-700 mb-1">Wilayah COOL yang Diampu *</label>
                <select name="wilayah_id" id="edit_wilayah" class="w-full text-sm rounded-lg border-gray-300 focus:border-primary focus:ring-primary font-medium">
                    <option value="">Pilih Wilayah</option>
                    @foreach($wilayahList as $w)
                        <option value="{{ $w->id }}">{{ $w->nama_wilayah }} ({{ $w->wilayah_besar }})</option>
                    @endforeach
                </select>
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200">Batal</button>
                <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-primary rounded-lg hover:bg-primary-dark">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleWilayah(role, boxId) {
    const box = document.getElementById(boxId);
    if (role === 'ketua_cool') {
        box.classList.remove('hidden');
    } else {
        box.classList.add('hidden');
    }
}

function openCreateModal() {
    document.getElementById('createModal').classList.remove('hidden');
    toggleWilayah(document.getElementById('modal_role').value, 'modal_wilayah_box');
}
function closeCreateModal() {
    document.getElementById('createModal').classList.add('hidden');
}

function openEditModal(user) {
    const form = document.getElementById('editForm');
    form.action = `/admin/users/${user.id}`;

    document.getElementById('edit_name').value = user.name;
    document.getElementById('edit_email').value = user.email;
    document.getElementById('edit_role').value = user.role;
    document.getElementById('edit_status').value = user.status;
    document.getElementById('edit_telepon').value = user.telepon || '';
    document.getElementById('edit_wilayah').value = user.wilayah_id || '';

    toggleWilayah(user.role, 'edit_wilayah_box');
    document.getElementById('editModal').classList.remove('hidden');
}
function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}
</script>
@endsection

