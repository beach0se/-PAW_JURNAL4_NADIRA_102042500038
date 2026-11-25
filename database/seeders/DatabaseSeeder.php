<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\MataKuliah;
use App\Models\Material;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Dosen Pengampu Default
        $dosen = User::updateOrCreate(
            ['email' => 'dosen@celoe.test'],
            [
                'name' => 'Dr. Ir. Budi Santoso, M.T.',
                'password' => Hash::make('dosen123'),
                'role' => 'dosen',
                'nomor_induk' => '198501012015041001',
            ]
        );

        // 2. Akun Mahasiswa Default
        $mahasiswa1 = User::updateOrCreate(
            ['email' => 'mahasiswa@celoe.test'],
            [
                'name' => 'Ahmad Pratama',
                'password' => Hash::make('mahasiswa123'),
                'role' => 'mahasiswa',
                'nomor_induk' => '1301210001',
            ]
        );

        // 3. Mata Kuliah Dasar (Modul 3)
        $course1 = MataKuliah::updateOrCreate(
            ['kode_mk' => 'IF301'],
            [
                'nama_mk' => 'Pengembangan Aplikasi Website',
                'deskripsi' => 'Mata kuliah yang membahas arsitektur aplikasi web modern menggunakan framework Laravel, MVC, Eloquent ORM, dan keamanan web.',
                'sks' => 4,
                'semester' => 'Ganjil 2026/2027',
                'dosen_id' => $dosen->id,
            ]
        );

        // 4. Daftarkan Mahasiswa ke Mata Kuliah Pertama (status approved)
        $course1->students()->syncWithoutDetaching([
            $mahasiswa1->id => ['status' => 'approved'],
        ]);

        // 5. Tambahkan Materi Contoh
        Material::updateOrCreate(
            ['course_id' => $course1->id, 'minggu_ke' => 1, 'urutan' => 1],
            [
                'judul' => 'Pengenalan Arsitektur MVC & Routing di Laravel 12',
                'konten' => 'Mempelajari alur HTTP Request, Route Middleware, Controller, dan pembuatan View menggunakan Blade Template.',
            ]
        );

        $mahasiswa2 = User::updateOrCreate(
            ['email' => 'mahasiswa2@celoe.test'],
            [
                'name' => 'Siti Nurhaliza',
                'password' => Hash::make('mahasiswa123'),
                'role' => 'mahasiswa',
                'nomor_induk' => '1301210002',
            ]
        );

        $course2 = MataKuliah::updateOrCreate(
            ['kode_mk' => 'IF302'],
            [
                'nama_mk' => 'Basis Data',
                'deskripsi' => 'Perancangan basis data relasional, normalisasi, SQL, indeks, dan transaksi.',
                'sks' => 3,
                'semester' => 'Genap 2026/2027',
                'dosen_id' => $dosen->id,
            ]
        );

        $course3 = MataKuliah::updateOrCreate(
            ['kode_mk' => 'IF303'],
            [
                'nama_mk' => 'Kecerdasan Buatan',
                'deskripsi' => 'Konsep pencarian, representasi pengetahuan, pembelajaran mesin, dan penerapan AI.',
                'sks' => 3,
                'semester' => 'Ganjil 2027/2028',
                'dosen_id' => $dosen->id,
            ]
        );

        $course2->students()->syncWithoutDetaching([
            $mahasiswa1->id => ['status' => 'approved'],
            $mahasiswa2->id => ['status' => 'approved'],
        ]);
        $course3->students()->syncWithoutDetaching([
            $mahasiswa2->id => ['status' => 'approved'],
        ]);

        $assignment2 = Assignment::updateOrCreate(
            ['course_id' => $course2->id, 'judul' => 'Perancangan Skema Basis Data'],
            [
                'deskripsi' => 'Buat skema basis data beserta normalisasi dan kueri SQL untuk studi kasus yang diberikan.',
                'deadline' => now()->addDays(14),
            ]
        );

        $assignment3 = Assignment::updateOrCreate(
            ['course_id' => $course3->id, 'judul' => 'Implementasi Algoritma Pencarian'],
            [
                'deskripsi' => 'Implementasikan algoritma pencarian dan jelaskan proses penelusuran serta hasilnya.',
                'deadline' => now()->addDays(21),
            ]
        );

        Submission::updateOrCreate(
            ['assignment_id' => $assignment2->id, 'user_id' => $mahasiswa1->id],
            [
                'jawaban' => 'Saya telah merancang skema relasional, menentukan primary key dan foreign key, serta menormalisasi tabel sampai bentuk normal ketiga.',
                'nilai' => 85,
                'catatan_dosen' => 'Skema dan normalisasi sudah baik. Lengkapi penjelasan mengenai indeks.',
                'submitted_at' => now()->subDays(2),
            ]
        );

        Submission::updateOrCreate(
            ['assignment_id' => $assignment2->id, 'user_id' => $mahasiswa2->id],
            [
                'jawaban' => 'Jawaban berisi perancangan tabel mahasiswa, mata kuliah, dan enrollments beserta kueri SQL dasar.',
                'nilai' => null,
                'catatan_dosen' => null,
                'submitted_at' => now()->subDay(),
            ]
        );

        Submission::updateOrCreate(
            ['assignment_id' => $assignment3->id, 'user_id' => $mahasiswa2->id],
            [
                'jawaban' => 'Implementasi mencakup algoritma pencarian BFS dan DFS beserta analisis kompleksitas waktu dan ruang.',
                'nilai' => 92,
                'catatan_dosen' => 'Analisis sudah lengkap dan implementasi mudah dipahami.',
                'submitted_at' => now()->subHours(5),
            ]
        );
    }
}
