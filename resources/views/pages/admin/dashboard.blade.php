<?php

use Livewire\Attributes\Layout;
use Livewire\Component;

new class extends Component {
    public array $stats = [
        'total_pendaftar' => 128,
        'menunggu_verifikasi' => 32,
        'lulus' => 74,
        'cadangan' => 12,
    ];

    public function refreshStats(): void
    {
        $this->dispatch('toast', type: 'success', message: 'Statistik dashboard berhasil diperbarui.');
    }
};
?>
<div>

    <!-- Welcome Hero Section -->
    <section
        class="mb-10 bg-gradient-to-r from-primary to-primary-container p-10 rounded-[32px] text-white shadow-xl relative overflow-hidden">
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-8">
            <div class="max-w-xl">
                <span
                    class="bg-white/20 px-4 py-1 rounded-full font-label-md text-[12px] uppercase tracking-wider mb-4 inline-block">Portal
                    Akademik</span>
                <h1 class="font-headline-xl text-headline-xl mb-4">Selamat Datang Kembali, Admin!</h1>
                <p class="font-body-lg text-body-lg opacity-90">Pantau perkembangan sekolah, kelola data akademik,
                    dan respon pesan pengunjung dalam satu dasbor terpadu. Hari ini ada 5 laporan baru yang
                    membutuhkan perhatian Anda.</p>
                <div class="flex gap-4 mt-8">
                    <button
                        class="bg-secondary-container text-on-secondary-container font-bold px-8 py-3 rounded-2xl hover:scale-105 transition-transform">Lihat
                        Laporan Harian</button>
                    <button
                        class="bg-white/10 hover:bg-white/20 border border-white/30 backdrop-blur-md px-8 py-3 rounded-2xl transition-all">Pengaturan
                        Cepat</button>
                </div>
            </div>
            <div class="hidden lg:block w-72 h-72">
                <img class="w-full h-full object-cover rounded-3xl rotate-3 shadow-2xl border-4 border-white/20"
                    data-alt="A high-energy, vibrant photo of a group of diverse elementary school students laughing and running in a sunny schoolyard. The shot captures a sense of joyful discovery and childhood wonder. The lighting is golden and warm, echoing a professional and modern school environment that bridges institutional reliability with playful energy."
                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuCwUTPy-QLqANW5PtnB8CEPK3uRBWWX00i0QkW6hImMZw2nnW3yJoLwBbbeHrRSH3qvBNE2v2AE8I3DJWkgKwul0eOeNxZqdyGpdGlsDsk-Oc1MXzGs08oRbr8QmFbceDQwqCihWN0p-HeDOGO-OFqLSvg1LiFMayR3Q0aAXHR0S5RG9BbG0u7EaN6G1fbriVJUkEEPgvNst5YIDTHz_nL3bSWYByEn99I4zOTEodiPGI10U9azUUlVFHY55Y8nqC8NRbwpH-JE8IU" />
            </div>
        </div>
        <!-- Decorative Elements -->
        <div class="absolute top-[-50px] right-[-50px] w-64 h-64 bg-secondary rounded-full opacity-20 blur-3xl">
        </div>
        <div class="absolute bottom-[-20px] left-[20%] w-40 h-40 bg-white rounded-full opacity-10 blur-2xl"></div>
    </section>
    <!-- Stats Bento Grid -->
    <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <!-- Stat 1 -->
        <div
            class="bg-surface-container-lowest p-6 rounded-[24px] shadow-sm hover:shadow-md transition-shadow border border-outline-variant/10">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 bg-primary/10 rounded-2xl flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined" data-icon="groups">groups</span>
                </div>
                <span class="text-green-600 font-bold text-xs bg-green-50 px-2 py-1 rounded-lg">+12%</span>
            </div>
            <h3 class="font-label-md text-on-surface-variant">Total Siswa</h3>
            <p class="font-stats-number text-stats-number text-on-surface">1,248</p>
            <div class="mt-4 h-1 w-full bg-surface-container rounded-full overflow-hidden">
                <div class="h-full bg-primary w-[75%] rounded-full"></div>
            </div>
        </div>
        <!-- Stat 2 -->
        <div
            class="bg-surface-container-lowest p-6 rounded-[24px] shadow-sm hover:shadow-md transition-shadow border border-outline-variant/10">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 bg-secondary/10 rounded-2xl flex items-center justify-center text-secondary">
                    <span class="material-symbols-outlined" data-icon="person">person</span>
                </div>
                <span class="text-blue-600 font-bold text-xs bg-blue-50 px-2 py-1 rounded-lg">Tetap</span>
            </div>
            <h3 class="font-label-md text-on-surface-variant">Total Guru</h3>
            <p class="font-stats-number text-stats-number text-on-surface">84</p>
            <div class="mt-4 h-1 w-full bg-surface-container rounded-full overflow-hidden">
                <div class="h-full bg-secondary w-[90%] rounded-full"></div>
            </div>
        </div>
        <!-- Stat 3 -->
        <div
            class="bg-surface-container-lowest p-6 rounded-[24px] shadow-sm hover:shadow-md transition-shadow border border-outline-variant/10">
            <div class="flex justify-between items-start mb-4">
                <div
                    class="w-12 h-12 bg-tertiary-fixed-dim/30 rounded-2xl flex items-center justify-center text-tertiary">
                    <span class="material-symbols-outlined" data-icon="app_registration">app_registration</span>
                </div>
                <span class="text-secondary font-bold text-xs bg-secondary-fixed/30 px-2 py-1 rounded-lg">Baru</span>
            </div>
            <h3 class="font-label-md text-on-surface-variant">Pendaftar SPMB</h3>
            <p class="font-stats-number text-stats-number text-on-surface">156</p>
            <div class="mt-4 h-1 w-full bg-surface-container rounded-full overflow-hidden">
                <div class="h-full bg-tertiary-container w-[45%] rounded-full"></div>
            </div>
        </div>
        <!-- Stat 4 -->
        <div
            class="bg-surface-container-lowest p-6 rounded-[24px] shadow-sm hover:shadow-md transition-shadow border border-outline-variant/10">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 bg-error-container/20 rounded-2xl flex items-center justify-center text-error">
                    <span class="material-symbols-outlined" data-icon="mail">mail</span>
                </div>
                <span class="text-error font-bold text-xs bg-error-container/40 px-2 py-1 rounded-lg">Urgent</span>
            </div>
            <h3 class="font-label-md text-on-surface-variant">Pesan Masuk</h3>
            <p class="font-stats-number text-stats-number text-on-surface">09</p>
            <div class="mt-4 h-1 w-full bg-surface-container rounded-full overflow-hidden">
                <div class="h-full bg-error w-[20%] rounded-full"></div>
            </div>
        </div>
    </section>
    <!-- Main Dashboard Content Area -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Activity List (Bento Column 1 & 2) -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-surface-container-lowest rounded-[32px] p-8 shadow-sm border border-outline-variant/10">
                <div class="flex justify-between items-center mb-8">
                    <div>
                        <h2 class="font-headline-md text-headline-md text-primary">Aktivitas Terkini</h2>
                        <p class="font-body-md text-on-surface-variant">Pantauan sistem log administratif terakhir.
                        </p>
                    </div>
                    <button class="text-primary font-bold hover:underline">Lihat Semua</button>
                </div>
                <div class="space-y-6">
                    <!-- Activity Item 1 -->
                    <div class="flex gap-4 p-4 hover:bg-surface-container-low rounded-2xl transition-colors group">
                        <div class="w-12 h-12 shrink-0 rounded-full bg-primary/5 flex items-center justify-center">
                            <span class="material-symbols-outlined text-primary" data-icon="edit_note">edit_note</span>
                        </div>
                        <div class="flex-1">
                            <div class="flex justify-between">
                                <p class="font-body-md font-bold text-on-surface">Pembaruan Visi &amp; Misi</p>
                                <span class="font-label-md text-[12px] text-on-surface-variant">2 jam yang
                                    lalu</span>
                            </div>
                            <p class="text-on-surface-variant text-sm mt-1">Admin Budi memperbarui konten halaman
                                visi misi sekolah untuk periode 2024.</p>
                        </div>
                        <div class="flex items-center opacity-0 group-hover:opacity-100 transition-opacity">
                            <span class="material-symbols-outlined text-outline"
                                data-icon="chevron_right">chevron_right</span>
                        </div>
                    </div>
                    <!-- Activity Item 2 -->
                    <div class="flex gap-4 p-4 hover:bg-surface-container-low rounded-2xl transition-colors group">
                        <div class="w-12 h-12 shrink-0 rounded-full bg-secondary/10 flex items-center justify-center">
                            <span class="material-symbols-outlined text-secondary"
                                data-icon="verified_user">verified_user</span>
                        </div>
                        <div class="flex-1">
                            <div class="flex justify-between">
                                <p class="font-body-md font-bold text-on-surface">Verifikasi Siswa SPMB</p>
                                <span class="font-label-md text-[12px] text-on-surface-variant">5 jam yang
                                    lalu</span>
                            </div>
                            <p class="text-on-surface-variant text-sm mt-1">12 berkas calon siswa baru telah
                                diverifikasi dan dipindahkan ke status 'Diterima'.</p>
                        </div>
                        <div class="flex items-center opacity-0 group-hover:opacity-100 transition-opacity">
                            <span class="material-symbols-outlined text-outline"
                                data-icon="chevron_right">chevron_right</span>
                        </div>
                    </div>
                    <!-- Activity Item 3 -->
                    <div class="flex gap-4 p-4 hover:bg-surface-container-low rounded-2xl transition-colors group">
                        <div
                            class="w-12 h-12 shrink-0 rounded-full bg-tertiary-fixed/30 flex items-center justify-center">
                            <span class="material-symbols-outlined text-tertiary" data-icon="campaign">campaign</span>
                        </div>
                        <div class="flex-1">
                            <div class="flex justify-between">
                                <p class="font-body-md font-bold text-on-surface">Pengumuman Libur Nasional</p>
                                <span class="font-label-md text-[12px] text-on-surface-variant">Kemarin,
                                    14:20</span>
                            </div>
                            <p class="text-on-surface-variant text-sm mt-1">Sistem menjadwalkan pengiriman
                                notifikasi pengumuman libur Idul Fitri kepada seluruh wali murid.</p>
                        </div>
                        <div class="flex items-center opacity-0 group-hover:opacity-100 transition-opacity">
                            <span class="material-symbols-outlined text-outline"
                                data-icon="chevron_right">chevron_right</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Side Bento Column -->
        <div class="space-y-6">
            <!-- Quick Info Card -->
            <div
                class="bg-surface-container-highest/30 backdrop-blur-sm p-8 rounded-[32px] border border-white relative overflow-hidden">
                <h3 class="font-headline-md text-on-surface mb-6 relative z-10">Agenda Sekolah</h3>
                <div class="space-y-4 relative z-10">
                    <div class="bg-white p-4 rounded-2xl shadow-sm flex gap-4 items-center">
                        <div class="text-center bg-primary-fixed text-primary px-3 py-1 rounded-xl">
                            <span class="block font-bold text-lg">15</span>
                            <span class="block text-[10px] uppercase">Okt</span>
                        </div>
                        <div>
                            <p class="font-label-md text-on-surface">Rapat Guru Bulanan</p>
                            <p class="text-[12px] text-on-surface-variant">08:00 - 10:00 WIB</p>
                        </div>
                    </div>
                    <div class="bg-white p-4 rounded-2xl shadow-sm flex gap-4 items-center">
                        <div class="text-center bg-secondary-fixed text-secondary px-3 py-1 rounded-xl">
                            <span class="block font-bold text-lg">18</span>
                            <span class="block text-[10px] uppercase">Okt</span>
                        </div>
                        <div>
                            <p class="font-label-md text-on-surface">Pekan Seni Siswa</p>
                            <p class="text-[12px] text-on-surface-variant">Auditorium Utama</p>
                        </div>
                    </div>
                </div>
                <div class="absolute -bottom-10 -right-10 opacity-10">
                    <span class="material-symbols-outlined text-[150px]"
                        data-icon="calendar_month">calendar_month</span>
                </div>
            </div>
            <!-- Admin Profile Short-card -->
            <div class="bg-primary p-8 rounded-[32px] text-white">
                <div class="flex items-center gap-4 mb-6">
                    <img alt="Sub-Admin Avatar" class="w-16 h-16 rounded-full border-4 border-white/20 object-cover"
                        data-alt="A portrait of a senior school administrator, an elderly man with gray hair and glasses, looking professional and trustworthy. He is wearing a formal batik shirt, typical of Indonesian school administrators. The background is a clean school library with soft lighting, reflecting the institutional reliability and warmth of the Elementary Modernism style."
                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuCISCIkisITcLKwbIjiy5UvJRTbwmSicgL57XdKwfGmdMKO1ARMVAeTolmr-XwJ2I-UGv_rNpT4wp1vcTL_14V0_5kicYl0_q8fAtsSi4tcrY6cPT3OpSAJj4xAt0mCBUBQsZvhrw1XxTfO4rO6HsJu2XNsr4kMC1XK2iTikI9CddJW8Ff_p9sWdaAdULvlxi-PFct_nd-6E-FysHYt0ydZZ6V8DzRaxLrimYUduYZQfY4p75nC7t8vnod4E_QKZlaCl__5s2zJK1o" />
                    <div>
                        <p class="font-bold text-lg">Drs. M. Sulaiman</p>
                        <p class="text-sm opacity-80">Kepala Administrasi</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-white/10 p-4 rounded-2xl">
                        <p class="text-xs opacity-70">Shift Hari Ini</p>
                        <p class="font-bold">07:00 - 16:00</p>
                    </div>
                    <div class="bg-white/10 p-4 rounded-2xl">
                        <p class="text-xs opacity-70">Izin Pending</p>
                        <p class="font-bold">2 Guru</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- News & Announcements (Asymmetric Grid) -->
    <section class="mt-12">
        <div class="flex items-center justify-between mb-8">
            <h2 class="font-headline-md text-headline-md text-primary">Berita &amp; Pengumuman Sekolah</h2>
            <button
                class="flex items-center gap-2 bg-primary text-white px-6 py-2 rounded-full font-label-md hover:shadow-lg transition-shadow">
                <span class="material-symbols-outlined text-sm" data-icon="add">add</span> Post Berita Baru
            </button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- News Card 1 -->
            <div class="group cursor-pointer">
                <div class="relative overflow-hidden rounded-3xl h-64 mb-4">
                    <img alt="School News"
                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                        data-alt="A brightly lit classroom filled with creative student artwork and modern educational tools. The sun shines through large windows, highlighting a clean and inviting learning environment. The image uses a light-mode aesthetic with soft shadows and a professional, modern corporate-friendly feel suitable for a primary school's news section."
                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuAOkVBSHCVlYza3gjZa7WaDDisd9NhIR60y62Xk27fGODNuo60Vt9Lbm3OVeqAQLi5Y2e5MLk3BwSe6rvHX-eunN_6d9Ij1BffoM1sjDX7MRtF4ivUJ2n0qyeSSGx2nGrftkDOMZBQsYvfSKUiE-b5tbdnzbhhUyaOLoeORwOtt5Nta-fAItyJw-q5IliOlbdlhs6LIPUvlEw6TLY7sLgZWz9nUfOc-e51Iq_fGjlaptB9rQeZ7fvsuTm1_OGL_2PlQLTihjTsJzWA" />
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent">
                    </div>
                    <div class="absolute bottom-4 left-4">
                        <span
                            class="bg-secondary-container text-on-secondary-container px-3 py-1 rounded-full text-xs font-bold">Akademik</span>
                    </div>
                </div>
                <h4 class="font-bold text-lg text-on-surface group-hover:text-primary transition-colors">Juara Umum
                    Lomba Matematika Nasional</h4>
                <p class="text-sm text-on-surface-variant mt-2 line-clamp-2">Siswa kelas 5B SD Cerdas berhasil
                    membawa pulang piala bergilir dalam ajang kompetisi matematika yang diselenggarakan oleh
                    Kemdikbud...</p>
                <p class="text-xs text-outline mt-3 flex items-center gap-1">
                    <span class="material-symbols-outlined text-xs" data-icon="calendar_today">calendar_today</span>
                    12
                    Okt 2023
                </p>
            </div>
            <!-- News Card 2 -->
            <div class="group cursor-pointer">
                <div class="relative overflow-hidden rounded-3xl h-64 mb-4">
                    <img alt="School Infrastructure"
                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                        data-alt="A modern, minimalist school auditorium with wooden accents and blue seating that matches the primary brand color. The space is clean, airy, and well-lit with professional photography style. The atmosphere communicates trust, reliability, and institutional excellence, reflecting a modern educational facility in its prime."
                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuAESSgWqPIn_0MUTHd3NNQIsPJqh5Qhqs8garj6DTSuNwDnUeUacSC2B7HzHbAb0hyoQVggBRS9gMmiqt88dSh_FS7PlFjN9OxCaru2ypH2mKNNf1Ndeg4GlV2yRVx4hcmIdws5pFRiPatLoZ3tn8eiNhdYk1YoHYGlc1lrc568pCmC2eeXfkuwJPmZap5eLgNF1wlhzDXZncN1pF38YoYhEA9DBQzKlYKPn8t_dLlXUDha5ZkHDZOTR44aBAG6XcSyz5C25xZKjpY" />
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent">
                    </div>
                    <div class="absolute bottom-4 left-4">
                        <span class="bg-primary text-white px-3 py-1 rounded-full text-xs font-bold">Fasilitas</span>
                    </div>
                </div>
                <h4 class="font-bold text-lg text-on-surface group-hover:text-primary transition-colors">Renovasi
                    Gedung Olahraga Selesai</h4>
                <p class="text-sm text-on-surface-variant mt-2 line-clamp-2">Kabar gembira! Fasilitas GOR baru kini
                    siap digunakan untuk kegiatan ekstrakurikuler basket dan badminton mulai minggu depan...</p>
                <p class="text-xs text-outline mt-3 flex items-center gap-1">
                    <span class="material-symbols-outlined text-xs" data-icon="calendar_today">calendar_today</span>
                    10
                    Okt 2023
                </p>
            </div>
            <!-- News Card 3 -->
            <div class="group cursor-pointer">
                <div class="relative overflow-hidden rounded-3xl h-64 mb-4">
                    <img alt="Education Technology"
                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                        data-alt="Close-up of a student's hand using a sleek tablet device in a bright, modern classroom setting. The screen displays colorful educational software. The lighting is high-key and optimistic, focusing on the blend of technology and education. The composition is clean and follows the modern corporate-friendly style of the design system."
                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuBM-_iEe2-2PCB1oTHzsLPnA0GN7RogNxqJmrjQ073PGj2-uXikoAcJdOGk1uCZCnDLPC4lEU71x21NyjJK7ZBasa3LtrYxG136JSHaen815Ad01L_hbxDfnfzVnEfLz4EV-DOxuZUii2fYq5M2MceuEJNO6jQgPkO_k8hmL2nGYkbPYcZFGh_cMrr98W_LioxcBy4kUgUYHC9vU302pit6x4lkNqidofgnmUBqaaibU2TC7SKWbA4n7su5ox8KVMeKr_bvrwVGB20" />
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent">
                    </div>
                    <div class="absolute bottom-4 left-4">
                        <span class="bg-secondary text-white px-3 py-1 rounded-full text-xs font-bold">SPMB</span>
                    </div>
                </div>
                <h4 class="font-bold text-lg text-on-surface group-hover:text-primary transition-colors">Gelombang
                    Kedua SPMB Resmi Dibuka</h4>
                <p class="text-sm text-on-surface-variant mt-2 line-clamp-2">Pendaftaran calon siswa baru gelombang
                    kedua telah dibuka secara online mulai hari ini. Pastikan berkas pendaftaran lengkap...</p>
                <p class="text-xs text-outline mt-3 flex items-center gap-1">
                    <span class="material-symbols-outlined text-xs" data-icon="calendar_today">calendar_today</span>
                    08
                    Okt 2023
                </p>
            </div>
        </div>
    </section>
</div>
