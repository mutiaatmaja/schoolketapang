<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Rekap Pendaftaran {{ $registration->registration_number }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #1f2937;
            font-size: 12px;
            line-height: 1.5;
        }

        .page {
            padding: 24px 28px;
        }

        .header {
            border-bottom: 2px solid #1d4f45;
            padding-bottom: 12px;
            margin-bottom: 18px;
        }

        .title {
            font-size: 22px;
            font-weight: bold;
            margin: 0;
            color: #18352f;
        }

        .subtitle {
            margin: 6px 0 0;
            color: #4b5563;
            font-size: 12px;
        }

        .badge {
            display: inline-block;
            margin-top: 10px;
            padding: 5px 10px;
            background: #e7f6ef;
            border: 1px solid #b9e6ce;
            color: #166534;
            border-radius: 999px;
            font-size: 11px;
            font-weight: bold;
        }

        .qr-layout {
            margin-top: 18px;
            border: 1px solid #d1d5db;
            border-radius: 14px;
            padding: 14px;
            background: #f8fafc;
        }

        .qr-title {
            margin: 0 0 10px;
            font-size: 13px;
            font-weight: bold;
            color: #18352f;
        }

        .qr-description {
            margin: 0 0 12px;
            font-size: 11px;
            color: #4b5563;
        }

        .qr-code {
            width: 120px;
            height: 120px;
        }

        .qr-code img {
            width: 120px;
            height: 120px;
            display: block;
        }

        .qr-link {
            margin-top: 10px;
            font-size: 10px;
            color: #2563eb;
            word-break: break-all;
        }

        .section {
            margin-top: 18px;
        }

        .section-title {
            margin: 0 0 8px;
            font-size: 14px;
            font-weight: bold;
            color: #18352f;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #d1d5db;
            padding: 8px 10px;
            vertical-align: top;
        }

        th {
            width: 32%;
            text-align: left;
            background: #f8fafc;
            color: #374151;
        }

        .status-table th {
            width: 70%;
        }

        .footer-note {
            margin-top: 22px;
            font-size: 11px;
            color: #6b7280;
        }
    </style>
</head>

<body>
    <div class="page">
        <div class="header">
            <h1 class="title">Rekap Pendaftaran SPMB</h1>
            <p class="subtitle">SD Ketapang</p>
            <p class="subtitle">Nomor pendaftaran: {{ $registration->registration_number }}</p>
            <span class="badge">Berkas sudah diterima sistem</span>

            <div class="qr-layout">
                <p class="qr-title">QR Code Detail Pendaftaran</p>
                <p class="qr-description">Scan QR ini untuk membuka halaman detail pendaftaran peserta dari perangkat
                    mobile.</p>
                <div class="qr-code"><img src="{{ $qrCodeDataUri }}" alt="QR Code Detail Pendaftaran"></div>
                <p class="qr-link">{{ $detailUrl }}</p>
            </div>
        </div>

        <div class="section">
            <h2 class="section-title">Identitas Pendaftaran</h2>
            <table>
                <tr>
                    <th>Nomor Pendaftaran</th>
                    <td>{{ $registration->registration_number }}</td>
                </tr>
                <tr>
                    <th>Tanggal Kirim</th>
                    <td>{{ $registration->submitted_at?->format('d/m/Y H:i') ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>{{ strtoupper($registration->status) }}</td>
                </tr>
            </table>
        </div>

        <div class="section">
            <h2 class="section-title">Data Calon Siswa</h2>
            <table>
                <tr>
                    <th>Nama Lengkap</th>
                    <td>{{ $registration->name }}</td>
                </tr>
                <tr>
                    <th>NIK</th>
                    <td>{{ $registration->nik }}</td>
                </tr>
                <tr>
                    <th>No. KK</th>
                    <td>{{ $registration->family_card_number }}</td>
                </tr>
                <tr>
                    <th>Tempat, Tanggal Lahir</th>
                    <td>{{ $registration->birth_place }}, {{ $registration->birth_date?->format('d/m/Y') ?? '-' }}
                    </td>
                </tr>
                <tr>
                    <th>Jenis Kelamin</th>
                    <td>{{ $registration->gender }}</td>
                </tr>
                <tr>
                    <th>Agama</th>
                    <td>{{ $registration->religion }}</td>
                </tr>
                <tr>
                    <th>Alamat</th>
                    <td>{{ $registration->address }}</td>
                </tr>
                <tr>
                    <th>Catatan</th>
                    <td>{{ $registration->notes ?: '-' }}</td>
                </tr>
            </table>
        </div>

        <div class="section">
            <h2 class="section-title">Data Orang Tua / Wali</h2>
            <table>
                <tr>
                    <th>Nama Ayah</th>
                    <td>{{ $registration->father_name }}</td>
                </tr>
                <tr>
                    <th>Pekerjaan Ayah</th>
                    <td>{{ $registration->father_occupation ?: '-' }}</td>
                </tr>
                <tr>
                    <th>No. HP Ayah / Wali</th>
                    <td>{{ $registration->father_phone }}</td>
                </tr>
                <tr>
                    <th>Nama Ibu</th>
                    <td>{{ $registration->mother_name }}</td>
                </tr>
                <tr>
                    <th>Pekerjaan Ibu</th>
                    <td>{{ $registration->mother_occupation ?: '-' }}</td>
                </tr>
                <tr>
                    <th>No. HP Ibu</th>
                    <td>{{ $registration->mother_phone ?: '-' }}</td>
                </tr>
            </table>
        </div>

        <div class="section">
            <h2 class="section-title">Status Berkas</h2>
            <table class="status-table">
                <tr>
                    <th>Akte Lahir</th>
                    <td>{{ $registration->birth_certificate_path ? 'Sudah dikirim' : 'Belum ada' }}</td>
                </tr>
                <tr>
                    <th>Kartu Keluarga</th>
                    <td>{{ $registration->family_card_path ? 'Sudah dikirim' : 'Belum ada' }}</td>
                </tr>
                <tr>
                    <th>Foto Siswa Latar Merah</th>
                    <td>{{ $registration->student_photo_path ? 'Sudah dikirim' : 'Belum ada' }}</td>
                </tr>
                <tr>
                    <th>Ijazah TK</th>
                    <td>{{ $registration->kindergarten_certificate_path ? 'Sudah dikirim' : 'Tidak dilampirkan' }}</td>
                </tr>
            </table>
        </div>

        <p class="footer-note">Dokumen ini merupakan rekap otomatis dari formulir pendaftaran SPMB yang telah dikirim
            melalui sistem.</p>
    </div>
</body>

</html>
