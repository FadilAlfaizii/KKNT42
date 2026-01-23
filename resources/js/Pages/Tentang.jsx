import React, { useState } from "react";
import { Head, Link } from "@inertiajs/react";
import Layout from "../Layouts/Layout";
import { MapPin, Users, Award, Leaf, TrendingUp, Building2, Phone, Mail, Calendar, CheckCircle, Target, Eye, Heart, Sprout, Mountain, Factory, TreePine, Wheat, Home } from "lucide-react";

export default function Tentang() {
    const [selectedMember, setSelectedMember] = useState(null);

    // Data Statistik Kunci
    const keyStats = [
        { icon: Users, value: "5,234", label: "Jiwa Penduduk", color: "forest", description: "Total populasi aktif" },
        { icon: Home, value: "1,456", label: "Kepala Keluarga", color: "sage", description: "Rumah tangga terdaftar" },
        { icon: Leaf, value: "850 Ha", label: "Lahan Produktif", color: "wood", description: "Area pertanian & perkebunan" },
        { icon: Award, value: "12+", label: "Penghargaan", color: "forest", description: "Prestasi desa sejak 2020" },
    ];

    // Timeline Sejarah
    const history = [
        {
            year: "1945",
            title: "Pembentukan Desa",
            description: "Desa Sindang Anom resmi terbentuk pasca kemerdekaan Indonesia sebagai bagian dari Kecamatan Sekampung Udik.",
            icon: "🏛️"
        },
        {
            year: "1980",
            title: "Era Pembangunan",
            description: "Dimulainya pembangunan infrastruktur dasar seperti jalan desa, sekolah, dan fasilitas kesehatan.",
            icon: "🏗️"
        },
        {
            year: "2005",
            title: "Modernisasi Pertanian",
            description: "Transformasi menuju pertanian modern dengan penerapan teknologi dan sistem irigasi yang lebih baik.",
            icon: "🌾"
        },
        {
            year: "2020",
            title: "Desa Digital",
            description: "Peluncuran sistem informasi desa digital dan layanan online untuk mempermudah administrasi warga.",
            icon: "💻"
        },
        {
            year: "2026",
            title: "Smart Village",
            description: "Implementasi IoT dan teknologi pintar untuk monitoring pertanian dan pelayanan publik terintegrasi.",
            icon: "🚀"
        }
    ];

    // Visi Misi
    const vision = "Terwujudnya Desa Sindang Anom sebagai Desa Mandiri, Sejahtera, dan Berdaya Saing melalui Pembangunan Berkelanjutan yang Berbasis Teknologi Digital dan Kelestarian Alam";

    const missions = [
        {
            icon: TrendingUp,
            title: "Meningkatkan Kesejahteraan",
            description: "Mendorong pertumbuhan ekonomi lokal melalui pemberdayaan UMKM dan optimalisasi potensi pertanian."
        },
        {
            icon: Leaf,
            title: "Menjaga Kelestarian Lingkungan",
            description: "Menerapkan prinsip pembangunan hijau dan konservasi sumber daya alam untuk generasi mendatang."
        },
        {
            icon: Users,
            title: "Memberdayakan Masyarakat",
            description: "Meningkatkan kapasitas SDM melalui pelatihan keterampilan dan pendidikan berbasis kompetensi."
        },
        {
            icon: Building2,
            title: "Membangun Infrastruktur",
            description: "Menyediakan infrastruktur dasar yang memadai untuk mendukung aktivitas ekonomi dan sosial masyarakat."
        }
    ];

    // Profil Geografis
    const geographicProfile = {
        luas: "2,450 Hektar",
        ketinggian: "45-180 mdpl",
        suhu: "24-32°C",
        curahHujan: "2.200-2.800 mm/tahun",
        batas: {
            utara: "Desa Sekampung Udik",
            selatan: "Desa Way Haru",
            timur: "Desa Sukamaju",
            barat: "Desa Purwodadi"
        },
        orbitasi: {
            kecamatan: "8 Km",
            kabupaten: "42 Km",
            provinsi: "185 Km"
        }
    };

    // Struktur Organisasi
    const organizationStructure = [
        {
            position: "Kepala Desa",
            name: "Aminudin",
            photo: "/images/team/kepala-desa.jpg",
            phone: "0812-3456-7890",
            email: "kepaladesa@sindanganom.id",
            period: "2022-2028"
        },
        {
            position: "Sekretaris Desa",
            name: "Ibu Siti Aminah, S.AP",
            photo: "/images/team/sekdes.jpg",
            phone: "0813-4567-8901",
            email: "sekdes@sindanganom.id",
            period: "2022-2028"
        },
        {
            position: "Kaur Keuangan",
            name: "Bapak Ahmad Fauzi, SE",
            photo: "/images/team/kaur-keuangan.jpg",
            phone: "0814-5678-9012",
            email: "keuangan@sindanganom.id",
            period: "2022-2028"
        },
        {
            position: "Kaur Perencanaan",
            name: "Ibu Nur Hasanah, ST",
            photo: "/images/team/kaur-perencanaan.jpg",
            phone: "0815-6789-0123",
            email: "perencanaan@sindanganom.id",
            period: "2022-2028"
        },
        {
            position: "Kaur Umum",
            name: "Bapak Joko Susilo",
            photo: "/images/team/kaur-umum.jpg",
            phone: "0816-7890-1234",
            email: "umum@sindanganom.id",
            period: "2022-2028"
        },
        {
            position: "Kasi Pemerintahan",
            name: "Bapak Hadi Purnomo, S.IP",
            photo: "/images/team/kasi-pemerintahan.jpg",
            phone: "0817-8901-2345",
            email: "pemerintahan@sindanganom.id",
            period: "2022-2028"
        }
    ];

    // Potensi Desa
    const potentials = [
        {
            category: "Pertanian",
            icon: Wheat,
            color: "forest",
            items: [
                { name: "Padi", description: "Produksi 1.200 ton/tahun dengan lahan 450 Ha" },
                { name: "Jagung", description: "Komoditas unggulan dengan hasil 800 ton/tahun" },
                { name: "Sayuran", description: "Cabai, tomat, dan sayuran organik untuk pasar lokal" }
            ]
        },
        {
            category: "Perkebunan",
            icon: TreePine,
            color: "sage",
            items: [
                { name: "Kopi Robusta", description: "Kopi berkualitas ekspor dari ketinggian 800-1200 mdpl" },
                { name: "Kelapa Sawit", description: "Lahan 250 Ha dengan produktivitas tinggi" },
                { name: "Kakao", description: "Tanaman kakao organik untuk industri cokelat premium" }
            ]
        },
        {
            category: "Peternakan",
            icon: Home,
            color: "wood",
            items: [
                { name: "Kambing Perah", description: "Farm modern dengan 500+ ekor dan teknologi IoT" },
                { name: "Ayam Kampung", description: "Budidaya ayam organik untuk konsumsi lokal" },
                { name: "Ikan Air Tawar", description: "Kolam budidaya lele dan nila 15 Ha" }
            ]
        },
        {
            category: "Wisata Alam",
            icon: Mountain,
            color: "forest",
            items: [
                { name: "Curug Sindang Indah", description: "Air terjun alami dengan pemandangan hijau" },
                { name: "Camping Ground", description: "Area berkemah dengan fasilitas lengkap" },
                { name: "Agrowisata", description: "Wisata edukasi pertanian dan peternakan modern" }
            ]
        },
        {
            category: "Kerajinan",
            icon: Factory,
            color: "sage",
            items: [
                { name: "Anyaman Bambu", description: "Produk kerajinan tangan berkualitas ekspor" },
                { name: "Batik Tulis", description: "Motif khas Lampung dengan pewarna alami" },
                { name: "Olahan Susu", description: "Yogurt dan keju dari susu kambing segar" }
            ]
        }
    ];

    return (
        <Layout>
            <Head title="Tentang Desa Sindang Anom" />
            
            {/* Hero Section */}
            <div className="relative bg-gradient-to-br from-forest-900 via-forest-800 to-sage-800 text-white py-32 overflow-hidden">
                <div className="absolute inset-0 opacity-10">
                    <div className="absolute top-20 left-20 w-96 h-96 bg-forest-400 rounded-full blur-3xl animate-float"></div>
                    <div className="absolute bottom-20 right-20 w-96 h-96 bg-sage-400 rounded-full blur-3xl animate-float" style={{ animationDelay: '2s' }}></div>
                </div>
                
                <div className="absolute inset-0 bg-wood-texture opacity-5"></div>
                
                <div className="container mx-auto px-4 relative z-10">
                    <div className="max-w-4xl mx-auto text-center">
                        <div className="inline-flex items-center gap-2 px-6 py-3 bg-white/10 backdrop-blur-xl rounded-full border border-forest-400/30 mb-6 animate-fade-in-down">
                            <Building2 size={20} className="text-forest-300" />
                            <span className="font-medium">Profil Desa</span>
                        </div>
                        
                        <h1 className="text-5xl md:text-6xl lg:text-7xl font-bold mb-6 animate-fade-in-up leading-tight">
                            <span className="bg-gradient-to-r from-white via-forest-100 to-sage-200 bg-clip-text text-transparent">
                                Tentang Desa Sindang Anom
                            </span>
                        </h1>
                        
                        <p className="text-xl md:text-2xl text-forest-100 mb-8 animate-fade-in-up leading-relaxed" style={{ animationDelay: '200ms' }}>
                            Desa Mandiri, Sejahtera, dan Berdaya Saing di Era Digital
                        </p>
                        
                        <div className="flex items-center justify-center gap-2 text-forest-200 animate-fade-in-up" style={{ animationDelay: '300ms' }}>
                            <MapPin size={20} />
                            <span>Kec. Sekampung Udik, Kab. Lampung Timur, Provinsi Lampung</span>
                        </div>
                    </div>
                </div>
                
                <div className="absolute bottom-0 left-0 right-0">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 80" className="w-full h-auto">
                        <path fill="#f0fdf4" fillOpacity="1" d="M0,32L48,37.3C96,43,192,53,288,58.7C384,64,480,64,576,58.7C672,53,768,43,864,42.7C960,43,1056,53,1152,56C1248,59,1344,53,1392,50.7L1440,48L1440,80L1392,80C1344,80,1248,80,1152,80C1056,80,960,80,864,80C768,80,672,80,576,80C480,80,384,80,288,80C192,80,96,80,48,80L0,80Z"></path>
                    </svg>
                </div>
            </div>

            <div className="bg-gradient-to-b from-forest-50 to-white">
                <div className="container mx-auto px-4 -mt-16 relative z-20">
                    
                    {/* Statistik Kunci */}
                    <div className="max-w-6xl mx-auto mb-20">
                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            {keyStats.map((stat, index) => (
                                <div key={index} className="group bg-white rounded-3xl p-8 shadow-xl border border-forest-100/30 hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 animate-fade-in-up" style={{ animationDelay: `${index * 100}ms` }}>
                                    <div className={`inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-${stat.color}-500 to-${stat.color}-600 rounded-2xl mb-6 group-hover:scale-110 transition-transform shadow-lg`}>
                                        <stat.icon size={32} className="text-white" />
                                    </div>
                                    <div className="text-4xl font-bold text-gray-900 mb-2">{stat.value}</div>
                                    <div className="text-lg font-semibold text-forest-700 mb-2">{stat.label}</div>
                                    <div className="text-sm text-gray-600">{stat.description}</div>
                                </div>
                            ))}
                        </div>
                    </div>

                    {/* Sejarah Desa - Timeline */}
                    <div className="max-w-6xl mx-auto mb-20">
                        <div className="text-center mb-12">
                            <div className="inline-flex items-center gap-2 px-6 py-3 bg-forest-100 rounded-full mb-4">
                                <Calendar className="text-forest-700" size={20} />
                                <span className="text-forest-700 font-semibold">Perjalanan Kami</span>
                            </div>
                            <h2 className="text-4xl md:text-5xl font-bold text-gray-900 mb-4">Sejarah Desa Sindang Anom</h2>
                            <p className="text-xl text-gray-600 max-w-3xl mx-auto">Dari desa tradisional menuju smart village yang modern dan berkelanjutan</p>
                        </div>

                        <div className="relative">
                            {/* Vertical Line */}
                            <div className="absolute left-1/2 transform -translate-x-1/2 h-full w-1 bg-gradient-to-b from-forest-600 via-sage-500 to-forest-600 hidden lg:block"></div>
                            
                            {history.map((item, index) => (
                                <div key={index} className={`relative mb-12 ${index % 2 === 0 ? 'lg:pr-12' : 'lg:pl-12'} ${index % 2 === 0 ? 'lg:text-right' : 'lg:text-left'}`}>
                                    <div className={`lg:w-1/2 ${index % 2 === 0 ? 'lg:ml-auto' : 'lg:mr-auto'}`}>
                                        <div className="bg-white rounded-3xl p-8 shadow-xl border border-forest-200/50 hover:shadow-2xl hover:border-forest-400 transition-all duration-500 group">
                                            <div className="flex items-center gap-4 mb-4">
                                                <div className="text-4xl">{item.icon}</div>
                                                <div className="flex-1">
                                                    <div className="inline-block px-4 py-2 bg-gradient-to-r from-forest-600 to-sage-600 text-white rounded-full text-sm font-bold mb-2">{item.year}</div>
                                                    <h3 className="text-2xl font-bold text-gray-900 group-hover:text-forest-700 transition">{item.title}</h3>
                                                </div>
                                            </div>
                                            <p className="text-gray-600 leading-relaxed">{item.description}</p>
                                        </div>
                                    </div>
                                    
                                    {/* Center Dot */}
                                    <div className="absolute left-1/2 top-1/2 transform -translate-x-1/2 -translate-y-1/2 w-6 h-6 bg-forest-600 rounded-full border-4 border-white shadow-lg hidden lg:block"></div>
                                </div>
                            ))}
                        </div>
                    </div>

                    {/* Visi & Misi */}
                    <div className="max-w-6xl mx-auto mb-20">
                        <div className="text-center mb-12">
                            <div className="inline-flex items-center gap-2 px-6 py-3 bg-forest-100 rounded-full mb-4">
                                <Target className="text-forest-700" size={20} />
                                <span className="text-forest-700 font-semibold">Arah & Tujuan</span>
                            </div>
                            <h2 className="text-4xl md:text-5xl font-bold text-gray-900 mb-4">Visi & Misi Desa</h2>
                        </div>

                        {/* Visi */}
                        <div className="bg-gradient-to-br from-forest-600 to-sage-600 rounded-3xl p-10 md:p-12 text-white mb-8 shadow-2xl">
                            <div className="flex items-center gap-4 mb-6">
                                <div className="w-16 h-16 bg-white/20 backdrop-blur-xl rounded-2xl flex items-center justify-center">
                                    <Eye size={32} />
                                </div>
                                <h3 className="text-3xl font-bold">Visi</h3>
                            </div>
                            <p className="text-xl md:text-2xl leading-relaxed font-medium">{vision}</p>
                        </div>

                        {/* Misi */}
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {missions.map((mission, index) => (
                                <div key={index} className="bg-white rounded-3xl p-8 shadow-lg border border-forest-200/50 hover:shadow-2xl hover:border-forest-400 hover:-translate-y-2 transition-all duration-500 group">
                                    <div className="flex items-start gap-4">
                                        <div className="flex-shrink-0 w-14 h-14 bg-gradient-to-br from-forest-500 to-sage-500 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform shadow-lg">
                                            <mission.icon size={28} className="text-white" />
                                        </div>
                                        <div>
                                            <h4 className="text-xl font-bold text-gray-900 mb-3 group-hover:text-forest-700 transition">{mission.title}</h4>
                                            <p className="text-gray-600 leading-relaxed">{mission.description}</p>
                                        </div>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>

                    {/* Profil Geografis */}
                    <div className="max-w-6xl mx-auto mb-20">
                        <div className="text-center mb-12">
                            <div className="inline-flex items-center gap-2 px-6 py-3 bg-forest-100 rounded-full mb-4">
                                <MapPin className="text-forest-700" size={20} />
                                <span className="text-forest-700 font-semibold">Lokasi & Wilayah</span>
                            </div>
                            <h2 className="text-4xl md:text-5xl font-bold text-gray-900 mb-4">Profil Geografis</h2>
                        </div>

                        <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            {/* Data Wilayah */}
                            <div className="bg-white rounded-3xl p-8 shadow-xl border border-forest-200/50">
                                <h3 className="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                                    <div className="w-10 h-10 bg-forest-600 rounded-xl flex items-center justify-center">
                                        <Mountain size={24} className="text-white" />
                                    </div>
                                    Data Wilayah
                                </h3>
                                <div className="space-y-4">
                                    {[
                                        { label: "Luas Wilayah", value: geographicProfile.luas },
                                        { label: "Ketinggian", value: geographicProfile.ketinggian },
                                        { label: "Suhu Rata-rata", value: geographicProfile.suhu },
                                        { label: "Curah Hujan", value: geographicProfile.curahHujan }
                                    ].map((item, index) => (
                                        <div key={index} className="flex justify-between items-center py-3 border-b border-gray-200 last:border-0">
                                            <span className="text-gray-600 font-medium">{item.label}</span>
                                            <span className="text-forest-700 font-bold">{item.value}</span>
                                        </div>
                                    ))}
                                </div>
                            </div>

                            {/* Batas Wilayah */}
                            <div className="bg-white rounded-3xl p-8 shadow-xl border border-forest-200/50">
                                <h3 className="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                                    <div className="w-10 h-10 bg-sage-600 rounded-xl flex items-center justify-center">
                                        <MapPin size={24} className="text-white" />
                                    </div>
                                    Batas Wilayah
                                </h3>
                                <div className="space-y-4">
                                    {Object.entries(geographicProfile.batas).map(([direction, village], index) => (
                                        <div key={index} className="flex justify-between items-center py-3 border-b border-gray-200 last:border-0">
                                            <span className="text-gray-600 font-medium capitalize">Sebelah {direction}</span>
                                            <span className="text-sage-700 font-bold">{village}</span>
                                        </div>
                                    ))}
                                </div>
                            </div>

                            {/* Orbitasi */}
                            <div className="lg:col-span-2 bg-gradient-to-br from-forest-600 to-sage-600 rounded-3xl p-8 shadow-xl text-white">
                                <h3 className="text-2xl font-bold mb-6 flex items-center gap-3">
                                    <div className="w-10 h-10 bg-white/20 backdrop-blur-xl rounded-xl flex items-center justify-center">
                                        <MapPin size={24} />
                                    </div>
                                    Jarak Orbitasi (dari Pusat Desa)
                                </h3>
                                <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    {Object.entries(geographicProfile.orbitasi).map(([level, distance], index) => (
                                        <div key={index} className="bg-white/10 backdrop-blur-xl rounded-2xl p-6 border border-white/20">
                                            <div className="text-3xl font-bold mb-2">{distance}</div>
                                            <div className="text-forest-100 capitalize">Ke Ibu Kota {level}</div>
                                        </div>
                                    ))}
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* Struktur Organisasi */}
                    <div className="max-w-7xl mx-auto mb-20">
                        <div className="text-center mb-12">
                            <div className="inline-flex items-center gap-2 px-6 py-3 bg-forest-100 rounded-full mb-4">
                                <Users className="text-forest-700" size={20} />
                                <span className="text-forest-700 font-semibold">Pemerintahan Desa</span>
                            </div>
                            <h2 className="text-4xl md:text-5xl font-bold text-gray-900 mb-4">Struktur Organisasi</h2>
                            <p className="text-xl text-gray-600 max-w-3xl mx-auto">Perangkat desa yang siap melayani dengan transparan dan akuntabel</p>
                        </div>

                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            {organizationStructure.map((member, index) => (
                                <div 
                                    key={index}
                                    onClick={() => setSelectedMember(member)}
                                    className="group bg-white rounded-3xl overflow-hidden shadow-lg border border-forest-200/50 hover:shadow-2xl hover:border-forest-400 hover:-translate-y-2 transition-all duration-500 cursor-pointer"
                                >
                                    <div className="relative h-64 bg-gradient-to-br from-forest-100 to-sage-100 overflow-hidden">
                                        <img 
                                            src={member.photo} 
                                            alt={member.name}
                                            className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                                            onError={(e) => { e.target.src = 'https://via.placeholder.com/400x400/16a34a/ffffff?text=' + member.position.split(' ')[0]; }}
                                        />
                                        <div className="absolute inset-0 bg-gradient-to-t from-forest-900/80 to-transparent"></div>
                                        <div className="absolute bottom-4 left-4 right-4">
                                            <div className="inline-block px-4 py-2 bg-forest-600 text-white rounded-full text-xs font-bold mb-2">{member.position}</div>
                                        </div>
                                    </div>
                                    <div className="p-6">
                                        <h3 className="text-xl font-bold text-gray-900 mb-2 group-hover:text-forest-700 transition">{member.name}</h3>
                                        <div className="text-sm text-gray-600 mb-4">Periode: {member.period}</div>
                                        <div className="space-y-2">
                                            <div className="flex items-center gap-2 text-sm text-gray-600">
                                                <Phone size={16} className="text-forest-600" />
                                                <span>{member.phone}</span>
                                            </div>
                                            <div className="flex items-center gap-2 text-sm text-gray-600">
                                                <Mail size={16} className="text-forest-600" />
                                                <span className="truncate">{member.email}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>

                    {/* Potensi Desa */}
                    <div className="max-w-7xl mx-auto mb-20">
                        <div className="text-center mb-12">
                            <div className="inline-flex items-center gap-2 px-6 py-3 bg-forest-100 rounded-full mb-4">
                                <Sprout className="text-forest-700" size={20} />
                                <span className="text-forest-700 font-semibold">Kekayaan Lokal</span>
                            </div>
                            <h2 className="text-4xl md:text-5xl font-bold text-gray-900 mb-4">Potensi Desa</h2>
                            <p className="text-xl text-gray-600 max-w-3xl mx-auto">Beragam potensi unggulan yang menjadi kebanggaan Sindang Anom</p>
                        </div>

                        <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            {potentials.map((potential, index) => (
                                <div key={index} className="bg-white rounded-3xl p-8 shadow-xl border border-forest-200/50 hover:shadow-2xl hover:border-forest-400 transition-all duration-500">
                                    <div className="flex items-center gap-4 mb-6">
                                        <div className={`w-16 h-16 bg-gradient-to-br from-${potential.color}-500 to-${potential.color}-600 rounded-2xl flex items-center justify-center shadow-lg`}>
                                            <potential.icon size={32} className="text-white" />
                                        </div>
                                        <h3 className="text-2xl font-bold text-gray-900">{potential.category}</h3>
                                    </div>
                                    <div className="space-y-4">
                                        {potential.items.map((item, itemIndex) => (
                                            <div key={itemIndex} className="flex gap-4 group/item">
                                                <div className="flex-shrink-0 w-2 h-2 bg-forest-600 rounded-full mt-2 group-hover/item:scale-150 transition-transform"></div>
                                                <div>
                                                    <h4 className="font-bold text-gray-900 mb-1 group-hover/item:text-forest-700 transition">{item.name}</h4>
                                                    <p className="text-sm text-gray-600">{item.description}</p>
                                                </div>
                                            </div>
                                        ))}
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>

                    {/* Call to Action */}
                    <div className="max-w-4xl mx-auto mb-20">
                        <div className="bg-gradient-to-br from-forest-600 via-sage-600 to-forest-700 rounded-3xl p-12 text-white text-center shadow-2xl relative overflow-hidden">
                            <div className="absolute inset-0 bg-wood-texture opacity-10"></div>
                            <div className="relative z-10">
                                <Heart className="w-16 h-16 mx-auto mb-6 text-forest-100" />
                                <h2 className="text-3xl md:text-4xl font-bold mb-4">Mari Bersama Membangun Desa</h2>
                                <p className="text-xl text-forest-100 mb-8 leading-relaxed">Partisipasi dan kontribusi Anda sangat berarti untuk kemajuan Desa Sindang Anom</p>
                                <div className="flex flex-col sm:flex-row gap-4 justify-center">
                                    <Link href="/artikel">
                                        <button className="px-8 py-4 bg-white text-forest-700 rounded-2xl font-bold hover:bg-forest-50 transition-all duration-300 hover:scale-105 shadow-xl">
                                            Baca Berita Desa
                                        </button>
                                    </Link>
                                    <Link href="/peta-interaktif">
                                        <button className="px-8 py-4 bg-white/10 backdrop-blur-xl border-2 border-white/30 text-white rounded-2xl font-bold hover:bg-white/20 transition-all duration-300 hover:scale-105">
                                            Jelajahi Peta Desa
                                        </button>
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {/* Modal Detail Perangkat */}
            {selectedMember && (
                <div 
                    className="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4"
                    onClick={() => setSelectedMember(null)}
                >
                    <div 
                        className="bg-white rounded-3xl max-w-lg w-full overflow-hidden shadow-2xl animate-fade-in"
                        onClick={(e) => e.stopPropagation()}
                    >
                        <div className="relative h-80 bg-gradient-to-br from-forest-100 to-sage-100">
                            <img 
                                src={selectedMember.photo} 
                                alt={selectedMember.name}
                                className="w-full h-full object-cover"
                                onError={(e) => { e.target.src = 'https://via.placeholder.com/400x400/16a34a/ffffff?text=' + selectedMember.position.split(' ')[0]; }}
                            />
                            <div className="absolute inset-0 bg-gradient-to-t from-forest-900/80 to-transparent"></div>
                            <button 
                                onClick={() => setSelectedMember(null)}
                                className="absolute top-4 right-4 w-10 h-10 bg-white/90 hover:bg-white rounded-full flex items-center justify-center transition"
                            >
                                ✕
                            </button>
                        </div>
                        <div className="p-8">
                            <div className="inline-block px-4 py-2 bg-forest-600 text-white rounded-full text-sm font-bold mb-4">{selectedMember.position}</div>
                            <h3 className="text-3xl font-bold text-gray-900 mb-2">{selectedMember.name}</h3>
                            <div className="text-gray-600 mb-6">Masa Jabatan: {selectedMember.period}</div>
                            <div className="space-y-4">
                                <div className="flex items-center gap-4 p-4 bg-forest-50 rounded-2xl">
                                    <div className="w-10 h-10 bg-forest-600 rounded-xl flex items-center justify-center flex-shrink-0">
                                        <Phone size={20} className="text-white" />
                                    </div>
                                    <div>
                                        <div className="text-sm text-gray-600 mb-1">Telepon</div>
                                        <div className="font-bold text-gray-900">{selectedMember.phone}</div>
                                    </div>
                                </div>
                                <div className="flex items-center gap-4 p-4 bg-forest-50 rounded-2xl">
                                    <div className="w-10 h-10 bg-forest-600 rounded-xl flex items-center justify-center flex-shrink-0">
                                        <Mail size={20} className="text-white" />
                                    </div>
                                    <div>
                                        <div className="text-sm text-gray-600 mb-1">Email</div>
                                        <div className="font-bold text-gray-900">{selectedMember.email}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            )}
        </Layout>
    );
}
