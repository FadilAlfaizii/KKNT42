import React, { useState, useEffect } from 'react';
import { Head, Link } from '@inertiajs/react';
import Layout from '../Layouts/Layout';
import { Calendar, Clock, User, Search, Filter, ChevronLeft, ChevronRight, Leaf, BookOpen, Eye } from 'lucide-react';

export default function Artikel({ latestArticles = [], articles = [] }) {
    const [searchTerm, setSearchTerm] = useState('');
    const [selectedCategory, setSelectedCategory] = useState('all');
    const [currentPage, setCurrentPage] = useState(1);
    const articlesPerPage = 9;

    const sampleArticles = [
        { id: 1, title: 'Panduan Lengkap Perawatan Kambing Modern', excerpt: 'Pelajari teknik-teknik terbaru dalam perawatan kambing untuk hasil yang optimal. Dengan metode modern, produktivitas ternak dapat meningkat signifikan.', category: 'Perawatan', image: '/images/articles/kambing-1.jpg', author: 'Dr. Ahmad Susilo', date: '15 Jan 2026', readTime: '5', views: '1.2K' },
        { id: 2, title: 'Teknologi IoT untuk Monitoring Kesehatan Ternak', excerpt: 'Manfaatkan sensor IoT untuk memantau kesehatan ternak secara real-time dan tingkatkan efisiensi pengelolaan peternakan Anda.', category: 'Teknologi', image: '/images/articles/iot-sensor.jpg', author: 'Ir. Budi Santoso', date: '12 Jan 2026', readTime: '7', views: '2.5K' },
        { id: 3, title: 'Strategi Pemasaran Produk Peternakan di Era Digital', excerpt: 'Tips dan trik memasarkan produk peternakan Anda melalui platform digital untuk menjangkau pasar yang lebih luas.', category: 'Bisnis', image: '/images/articles/marketing.jpg', author: 'Siti Nurhaliza', date: '10 Jan 2026', readTime: '6', views: '980' },
        { id: 4, title: 'Manfaat Pakan Organik untuk Ternak Kambing', excerpt: 'Mengenal berbagai jenis pakan organik yang dapat meningkatkan kualitas daging dan kesehatan kambing secara alami.', category: 'Pakan', image: '/images/articles/pakan.jpg', author: 'Prof. Hadi Wijaya', date: '08 Jan 2026', readTime: '4', views: '1.5K' },
        { id: 5, title: 'Cara Mencegah Penyakit Ternak di Musim Hujan', excerpt: 'Panduan lengkap mencegah dan mengatasi berbagai penyakit yang sering menyerang ternak di musim hujan.', category: 'Kesehatan', image: '/images/articles/health.jpg', author: 'Dr. Lisa Permata', date: '05 Jan 2026', readTime: '8', views: '3.1K' },
        { id: 6, title: 'Inovasi Kandang Pintar untuk Peternakan Modern', excerpt: 'Desain kandang dengan sistem otomatis yang meningkatkan kenyamanan ternak dan efisiensi kerja peternak.', category: 'Teknologi', image: '/images/articles/kandang.jpg', author: 'Ir. Joko Susilo', date: '02 Jan 2026', readTime: '6', views: '1.8K' },
        { id: 7, title: 'Tips Memulai Usaha Peternakan Kambing Pemula', excerpt: 'Langkah-langkah praktis memulai usaha peternakan kambing dari nol hingga sukses dengan modal terbatas.', category: 'Bisnis', image: '/images/articles/bisnis.jpg', author: 'Muhammad Rizki', date: '28 Des 2025', readTime: '10', views: '4.2K' },
        { id: 8, title: 'Nutrisi Penting untuk Kambing Bunting', excerpt: 'Mengenal kebutuhan nutrisi khusus untuk kambing bunting agar anak kambing lahir sehat dan kuat.', category: 'Perawatan', image: '/images/articles/nutrisi.jpg', author: 'Dr. Sarah Utami', date: '25 Des 2025', readTime: '5', views: '2.3K' },
        { id: 9, title: 'Teknologi Blockchain dalam Sertifikasi Ternak', excerpt: 'Penggunaan blockchain untuk menjamin keaslian dan kualitas produk peternakan di pasar global.', category: 'Teknologi', image: '/images/articles/blockchain.jpg', author: 'Bambang Tech', date: '20 Des 2025', readTime: '9', views: '1.6K' },
    ];

    const normalizedArticles = Array.isArray(articles) ? articles : (articles?.data ?? []);
    const displayArticles = normalizedArticles.length > 0 ? normalizedArticles : sampleArticles;
    const categories = ['all', 'Perawatan', 'Teknologi', 'Bisnis', 'Kesehatan', 'Pakan'];
    
    const filteredArticles = displayArticles.filter(article => {
        const matchesSearch = article.title.toLowerCase().includes(searchTerm.toLowerCase()) || article.excerpt.toLowerCase().includes(searchTerm.toLowerCase());
        const matchesCategory = selectedCategory === 'all' || article.category === selectedCategory;
        return matchesSearch && matchesCategory;
    });

    // Pagination Logic
    const indexOfLastArticle = currentPage * articlesPerPage;
    const indexOfFirstArticle = indexOfLastArticle - articlesPerPage;
    const currentArticles = filteredArticles.slice(indexOfFirstArticle, indexOfLastArticle);
    const totalPages = Math.ceil(filteredArticles.length / articlesPerPage);

    const paginate = (pageNumber) => {
        setCurrentPage(pageNumber);
        window.scrollTo({ top: 0, behavior: 'smooth' });
    };

    return (
        <Layout>
            <Head title="Kumpulan Artikel - Desa Sindang Anom" />
            <div className="min-h-screen bg-gradient-to-b from-forest-50 to-white">
                
                {/* Beautiful Header with Forest Green Theme */}
                <div className="relative bg-gradient-to-br from-forest-800 via-forest-700 to-sage-700 text-white py-24 overflow-hidden">
                    {/* Decorative Background Elements */}
                    <div className="absolute inset-0 opacity-10">
                        <div className="absolute top-10 left-10 w-64 h-64 bg-forest-400 rounded-full blur-3xl animate-float"></div>
                        <div className="absolute bottom-10 right-10 w-96 h-96 bg-sage-400 rounded-full blur-3xl animate-float" style={{ animationDelay: '2s' }}></div>
                    </div>
                    
                    <div className="absolute inset-0 bg-wood-texture opacity-5"></div>
                    
                    <div className="container mx-auto px-4 relative z-10">
                        <div className="max-w-4xl mx-auto text-center">
                            {/* Badge */}
                            <div className="inline-flex items-center gap-2 px-6 py-3 bg-white/10 backdrop-blur-xl rounded-full border border-forest-400/30 mb-6 animate-fade-in-down">
                                <BookOpen size={20} className="text-forest-300" />
                                <span className="font-medium">Pusat Informasi Desa</span>
                                <Leaf size={16} className="text-forest-300" />
                            </div>
                            
                            {/* Title */}
                            <h1 className="text-5xl md:text-6xl lg:text-7xl font-bold mb-6 animate-fade-in-up leading-tight">
                                <span className="bg-gradient-to-r from-white via-forest-100 to-sage-200 bg-clip-text text-transparent">
                                    Kumpulan Artikel
                                </span>
                            </h1>
                            
                            {/* Subtitle */}
                            <p className="text-xl md:text-2xl text-forest-100 mb-8 animate-fade-in-up leading-relaxed" style={{ animationDelay: '200ms' }}>
                                Jelajahi berbagai artikel, berita, dan informasi terkini seputar Desa Sindang Anom
                            </p>
                            
                            {/* Stats */}
                            <div className="flex items-center justify-center gap-8 text-sm animate-fade-in-up" style={{ animationDelay: '300ms' }}>
                                <div className="flex items-center gap-2">
                                    <BookOpen size={18} className="text-forest-300" />
                                    <span>{displayArticles.length} Artikel</span>
                                </div>
                                <div className="w-1 h-4 bg-forest-400/30"></div>
                                <div className="flex items-center gap-2">
                                    <Leaf size={18} className="text-forest-300" />
                                    <span>{categories.length - 1} Kategori</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    {/* Bottom Wave */}
                    <div className="absolute bottom-0 left-0 right-0">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 80" className="w-full h-auto">
                            <path fill="#f0fdf4" fillOpacity="1" d="M0,32L48,37.3C96,43,192,53,288,58.7C384,64,480,64,576,58.7C672,53,768,43,864,42.7C960,43,1056,53,1152,56C1248,59,1344,53,1392,50.7L1440,48L1440,80L1392,80C1344,80,1248,80,1152,80C1056,80,960,80,864,80C768,80,672,80,576,80C480,80,384,80,288,80C192,80,96,80,48,80L0,80Z"></path>
                        </svg>
                    </div>
                </div>

                <div className="container mx-auto px-4 -mt-16 relative z-20">
                    
                    {/* Search & Filter Section */}
                    <div className="max-w-6xl mx-auto mb-12">
                        <div className="bg-white rounded-3xl shadow-2xl border border-forest-100/20 p-8 backdrop-blur-xl">
                            <div className="flex flex-col lg:flex-row gap-6">
                                {/* Search Bar */}
                                <div className="flex-1 relative group">
                                    <Search className="absolute left-4 top-1/2 -translate-y-1/2 text-forest-600 group-focus-within:text-forest-700 transition" size={20} />
                                    <input 
                                        type="text" 
                                        placeholder="Cari artikel berdasarkan judul atau konten..." 
                                        value={searchTerm} 
                                        onChange={(e) => setSearchTerm(e.target.value)} 
                                        className="w-full pl-12 pr-4 py-4 border-2 border-forest-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-forest-500/20 focus:border-forest-600 transition text-gray-700 placeholder-gray-400"
                                    />
                                </div>
                                
                                {/* Category Filter Icon */}
                                <div className="flex items-center gap-2 text-forest-700 font-medium">
                                    <Filter size={20} />
                                    <span className="hidden lg:block">Kategori:</span>
                                </div>
                            </div>
                            
                            {/* Category Pills */}
                            <div className="flex flex-wrap gap-3 mt-6">
                                {categories.map(category => (
                                    <button 
                                        key={category} 
                                        onClick={() => {
                                            setSelectedCategory(category);
                                            setCurrentPage(1);
                                        }} 
                                        className={`px-6 py-3 rounded-2xl font-medium transition-all duration-300 ${
                                            selectedCategory === category 
                                                ? 'bg-gradient-to-r from-forest-600 to-sage-600 text-white shadow-lg shadow-forest-500/30 scale-105' 
                                                : 'bg-forest-50 text-forest-700 hover:bg-forest-100 hover:shadow-md border border-forest-200/50'
                                        }`}
                                    >
                                        {category === 'all' ? '🌿 Semua Artikel' : `📚 ${category}`}
                                    </button>
                                ))}
                            </div>
                        </div>
                    </div>
                    
                    {/* Articles Grid - 3 Columns */}
                    <div className="max-w-7xl mx-auto mb-16">
                        {currentArticles.length > 0 ? (
                            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                                {currentArticles.map((article, index) => (
                                    <Link 
                                        key={article.id} 
                                        href={`/artikel/${article.id}`}
                                        className="group bg-white rounded-3xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-500 border border-forest-100/30 hover:border-forest-400/50 hover:-translate-y-2 animate-fade-in-up"
                                        style={{ animationDelay: `${index * 100}ms` }}
                                    >
                                        {/* Image with Hover Effect */}
                                        <div className="relative h-56 bg-gradient-to-br from-forest-100 to-sage-100 overflow-hidden">
                                            <img 
                                                src={article.image_url || article.image} 
                                                alt={article.title} 
                                                className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" 
                                                onError={(e) => { e.target.src = 'https://via.placeholder.com/400x300/16a34a/ffffff?text=Artikel+Desa'; }}
                                            />
                                            
                                            {/* Green Gradient Overlay */}
                                            <div className="absolute inset-0 bg-gradient-to-t from-forest-900/60 via-forest-900/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                                            
                                            {/* Category Badge - Light Green */}
                                            <div className="absolute top-4 right-4">
                                                <span className="inline-flex items-center gap-1.5 px-4 py-2 bg-forest-100 text-forest-700 rounded-full text-xs font-bold shadow-lg backdrop-blur-sm border border-forest-300/50">
                                                    <Leaf size={14} />
                                                    {article.category}
                                                </span>
                                            </div>
                                            
                                            {/* Views Badge */}
                                            <div className="absolute top-4 left-4 flex items-center gap-1.5 px-3 py-1.5 bg-white/90 backdrop-blur-sm rounded-full text-xs font-medium text-gray-700 shadow-md">
                                                <Eye size={14} className="text-forest-600" />
                                                {article.views}
                                            </div>
                                        </div>
                                        
                                        {/* Card Content */}
                                        <div className="p-6">
                                            {/* Title */}
                                            <h3 className="text-xl font-bold text-gray-800 mb-3 line-clamp-2 group-hover:text-forest-700 transition leading-tight">
                                                {article.title}
                                            </h3>
                                            
                                            {/* Excerpt */}
                                            <p className="text-gray-600 text-sm mb-5 line-clamp-3 leading-relaxed">
                                                {article.excerpt}
                                            </p>
                                            
                                            {/* Divider */}
                                            <div className="w-full h-px bg-gradient-to-r from-transparent via-forest-300 to-transparent mb-5"></div>
                                            
                                            {/* Meta Info */}
                                            <div className="flex items-center justify-between text-xs text-gray-500 mb-4">
                                                <div className="flex items-center gap-2">
                                                    <User size={14} className="text-forest-600" />
                                                    <span className="font-medium">{article.author}</span>
                                                </div>
                                                <div className="flex items-center gap-2">
                                                    <Clock size={14} className="text-forest-600" />
                                                    <span>{article.readTime} menit</span>
                                                </div>
                                            </div>
                                            
                                            {/* Date & Read Button */}
                                            <div className="flex items-center justify-between">
                                                <div className="flex items-center gap-2 text-xs text-gray-400">
                                                    <Calendar size={14} />
                                                    <span>{article.date}</span>
                                                </div>
                                                <div className="flex items-center gap-2 text-forest-700 font-semibold text-sm group-hover:text-forest-600 transition">
                                                    <span>Baca</span>
                                                    <ChevronRight size={16} className="group-hover:translate-x-1 transition-transform" />
                                                </div>
                                            </div>
                                        </div>
                                    </Link>
                                ))}
                            </div>
                        ) : (
                            <div className="text-center py-20">
                                <div className="inline-flex items-center justify-center w-24 h-24 bg-forest-100 rounded-full mb-6">
                                    <Search size={40} className="text-forest-600" />
                                </div>
                                <h3 className="text-2xl font-bold text-gray-800 mb-3">Artikel Tidak Ditemukan</h3>
                                <p className="text-gray-600 mb-6">Tidak ada artikel yang sesuai dengan pencarian Anda</p>
                                <button 
                                    onClick={() => { setSearchTerm(''); setSelectedCategory('all'); }}
                                    className="px-8 py-3 bg-gradient-to-r from-forest-600 to-sage-600 text-white rounded-2xl font-medium hover:shadow-xl transition"
                                >
                                    Reset Pencarian
                                </button>
                            </div>
                        )}
                    </div>

                    {/* Beautiful Pagination */}
                    {totalPages > 1 && (
                        <div className="max-w-4xl mx-auto mb-16">
                            <div className="bg-white rounded-3xl shadow-xl border border-forest-100/30 p-6">
                                <div className="flex items-center justify-center gap-2 flex-wrap">
                                    {/* Previous Button */}
                                    <button
                                        onClick={() => paginate(currentPage - 1)}
                                        disabled={currentPage === 1}
                                        className={`flex items-center gap-2 px-5 py-3 rounded-2xl font-medium transition-all ${
                                            currentPage === 1
                                                ? 'bg-gray-100 text-gray-400 cursor-not-allowed'
                                                : 'bg-forest-50 text-forest-700 hover:bg-forest-600 hover:text-white hover:shadow-lg border border-forest-200'
                                        }`}
                                    >
                                        <ChevronLeft size={18} />
                                        <span className="hidden sm:inline">Sebelumnya</span>
                                    </button>
                                    
                                    {/* Page Numbers */}
                                    <div className="flex gap-2">
                                        {[...Array(totalPages)].map((_, index) => {
                                            const pageNumber = index + 1;
                                            
                                            // Show first, last, current, and adjacent pages
                                            if (
                                                pageNumber === 1 ||
                                                pageNumber === totalPages ||
                                                (pageNumber >= currentPage - 1 && pageNumber <= currentPage + 1)
                                            ) {
                                                return (
                                                    <button
                                                        key={pageNumber}
                                                        onClick={() => paginate(pageNumber)}
                                                        className={`w-12 h-12 rounded-2xl font-bold transition-all ${
                                                            currentPage === pageNumber
                                                                ? 'bg-gradient-to-r from-forest-600 to-sage-600 text-white shadow-lg shadow-forest-500/30 scale-110'
                                                                : 'bg-forest-50 text-forest-700 hover:bg-forest-100 hover:shadow-md border border-forest-200/50'
                                                        }`}
                                                    >
                                                        {pageNumber}
                                                    </button>
                                                );
                                            } else if (
                                                pageNumber === currentPage - 2 ||
                                                pageNumber === currentPage + 2
                                            ) {
                                                return <span key={pageNumber} className="flex items-center px-2 text-forest-600 font-bold">...</span>;
                                            }
                                            return null;
                                        })}
                                    </div>
                                    
                                    {/* Next Button */}
                                    <button
                                        onClick={() => paginate(currentPage + 1)}
                                        disabled={currentPage === totalPages}
                                        className={`flex items-center gap-2 px-5 py-3 rounded-2xl font-medium transition-all ${
                                            currentPage === totalPages
                                                ? 'bg-gray-100 text-gray-400 cursor-not-allowed'
                                                : 'bg-forest-50 text-forest-700 hover:bg-forest-600 hover:text-white hover:shadow-lg border border-forest-200'
                                        }`}
                                    >
                                        <span className="hidden sm:inline">Selanjutnya</span>
                                        <ChevronRight size={18} />
                                    </button>
                                </div>
                                
                                {/* Page Info */}
                                <div className="text-center mt-6 text-sm text-gray-600">
                                    Menampilkan <span className="font-bold text-forest-700">{indexOfFirstArticle + 1}</span> - <span className="font-bold text-forest-700">{Math.min(indexOfLastArticle, filteredArticles.length)}</span> dari <span className="font-bold text-forest-700">{filteredArticles.length}</span> artikel
                                </div>
                            </div>
                        </div>
                    )}
                </div>
            </div>
        </Layout>
    );
}
