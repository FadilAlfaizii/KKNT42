<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        $articles = [
            [
                'title' => 'Panduan Lengkap Perawatan Kambing Modern',
                'excerpt' => 'Pelajari teknik-teknik terbaru dalam perawatan kambing untuk hasil yang optimal.',
                'content' => '<h2>Pengenalan</h2><p>Perawatan kambing yang baik adalah kunci sukses dalam usaha peternakan. Dengan mengikuti panduan ini, Anda akan belajar tentang berbagai aspek perawatan kambing mulai dari pemberian makan hingga kesehatan.</p><h2>Pemberian Pakan</h2><p>Kambing memerlukan makanan yang seimbang包含蛋白质、纤维和矿物质。确保每天提供干净的饮用水。</p><h2>Kesehatan Ternak</h2><p>定期进行健康检查，及时发现和处理潜在问题非常重要。</p>',
                'category' => 'Perawatan',
                'image' => 'https://images.unsplash.com/photo-1484557985045-edf25e08da73?w=800',
                'author' => 'Dr. Ahmad Susilo',
                'author_bio' => 'Dokter Hewan dengan 15 tahun pengalaman',
                'tags' => 'Perawatan, Kambing, Ternak, Tips',
                'is_published' => true,
                'published_at' => now(),
            ],
            [
                'title' => 'Teknologi IoT untuk Monitoring Kesehatan Ternak',
                'excerpt' => 'Manfaatkan sensor IoT untuk memantau kesehatan ternak secara real-time dan meningkatkan efisiensi peternakan.',
                'content' => '<h2>Apa itu IoT?</h2><p>IoT (Internet of Things) adalah teknologi yang memungkinkan perangkat terhubung ke internet untuk mengumpulkan dan bertukar data.</p><h2>Implementasi di Peternakan</h2><p>Dengan sensor IoT, Anda dapat memantau suhu tubuh, aktivitas, dan pola makan hewan secara real-time.</p>',
                'category' => 'Teknologi',
                'image' => 'https://images.unsplash.com/photo-1518770660439-4636190af475?w=800',
                'author' => 'Ir. Budi Santoso',
                'author_bio' => 'Ahli Teknologi dengan 10 tahun pengalaman di bidang peternakan',
                'tags' => 'IoT, Teknologi, Monitoring, Sensor',
                'is_published' => true,
                'published_at' => now()->subDays(2),
            ],
            [
                'title' => 'Strategi Pemasaran Produk Peternakan di Era Digital',
                'excerpt' => 'Tips dan trik memasarkan produk peternakan Anda melalui platform digital untuk menjangkau lebih banyak pelanggan.',
                'content' => '<h2>Mengapa Digital Marketing?</h2><p>Di era digital, pemasaran online memungkinkan Anda menjangkau pelanggan di seluruh Indonesia.</p><h2>Strategi yang Efektif</h2><p>Gunakan media sosial, marketplace, dan website untuk mempromosikan produk peternakan Anda.</p>',
                'category' => 'Bisnis',
                'image' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=800',
                'author' => 'Siti Nurhaliza',
                'author_bio' => 'Konsultan Pemasaran dengan 8 tahun pengalaman',
                'tags' => 'Pemasaran, Bisnis, Digital, Produk',
                'is_published' => true,
                'published_at' => now()->subDays(5),
            ],
            [
                'title' => 'Tips Memilih Bibit Kambing Berkualitas',
                'excerpt' => 'Panduan lengkap memilih bibit kambing yang berkualitas untuk memulai usaha peternakan yang sukses.',
                'content' => '<h2>Ciri-ciri Bibit Berkualitas</h2><p>Bibit yang baik memiliki postur tubuh yang tegap, mata bersinar, dan nafsu makan yang baik.</p><h2>Jenis Kambing Populer</h2><p>了解不同品种的特点可以帮助您做出更好的选择。</p>',
                'category' => 'Perawatan',
                'image' => 'https://images.unsplash.com/photo-1545289411-50c75c7f23a8?w=800',
                'author' => 'Prof. Dr. Ir. Joko Prasetyo',
                'author_bio' => 'Profesor Peternakan dari Universitas Gadjah Mada',
                'tags' => 'Bibit, Kambing, Ternak, Tips',
                'is_published' => true,
                'published_at' => now()->subDays(7),
            ],
            [
                'title' => 'Pentingnya Sanitasi Kandang untuk Mencegah Penyakit',
                'excerpt' => 'Pelajari cara menjaga kebersihan kandang untuk mencegah penyakit pada ternak.',
                'content' => '<h2>Mengapa Sanitasi Penting?</h2><p>Kandang yang bersih mengurangi risiko penyakit dan meningkatkan produktivitas hewan.</p><h2>Tahap Sanitasi</h2><p>Pembersihan rutin dan消毒是保持动物健康的关键步骤。</p>',
                'category' => 'Kesehatan',
                'image' => 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=800',
                'author' => 'Dr. Vet. Dewi Lestari',
                'author_bio' => 'Dokter Hewan Spesialis',
                'tags' => 'Sanitasi, Kesehatan, Kandang, Pencegahan',
                'is_published' => true,
                'published_at' => now()->subDays(10),
            ],
            [
                'title' => 'Cara Membuat Pakan Fermentasi untuk Ternak',
                'excerpt' => 'Tutorial lengkap membuat fermentasi饲料以提高营养价值并降低饲养成本。',
                'content' => '<h2>Apa itu Pakan Fermentasi?</h2><p>Pakan fermentasi adalah饲料经过微生物处理，提高其营养价值和消化率。</p><h2>Bahan yang Diperlukan</h2><p>准备好必要的原料和发酵剂，开始制作自己的发酵饲料。</p>',
                'category' => 'Pakan',
                'image' => 'https://images.unsplash.com/photo-1500937386664-56d1dfef3854?w=800',
                'author' => 'Ir. Hartono',
                'author_bio' => 'Ahli Nutrisi Ternak',
                'tags' => 'Pakan, Fermentasi, Ternak, Tips',
                'is_published' => true,
                'published_at' => now()->subDays(14),
            ],
        ];

        foreach ($articles as $article) {
            Article::create($article);
        }

        $this->command->info('Artikel sample berhasil dibuat!');
    }
}

