import React, { useState, useEffect } from "react";
import { Link, Head } from "@inertiajs/react";
import Layout from "../Layouts/Layout";
import Hero from "../component/Hero";
import { 
    FileText, MapPin, TrendingUp, Users, Calendar, 
    BookOpen, Megaphone, Phone, Mail, Clock, 
    ChevronRight, Bell, AlertCircle, Info,
    Building2, Award, Home as HomeIcon, Leaf
} from "lucide-react";

const Home = ({ latestArticles = [], statistics = {}, announcements = [] }) => {
    const [currentAnnouncementIndex, setCurrentAnnouncementIndex] = useState(0);

    // Auto-rotate announcements
    useEffect(() => {
        if (announcements.length > 0) {
            const interval = setInterval(() => {
                setCurrentAnnouncementIndex((prev) => 
                    prev === announcements.length - 1 ? 0 : prev + 1
                );
            }, 5000);
            return () => clearInterval(interval);
        }
    }, [announcements.length]);

    // Quick Links - 5 menu paling sering dicari
    const quickLinks = [
        {
            title: "Surat Keterangan",
            description: "Buat surat keterangan usaha, domisili, dll",
            icon: FileText,
            href: "/layanan-surat",
            color: "forest",
            popular: true
        },
        {
            title: "Peta Desa",
            description: "Lihat peta interaktif wilayah desa",
            icon: MapPin,
            href: "/peta-interaktif",
            color: "sage",
            popular: true
        },
        {
            title: "Artikel & Berita",
            description: "Berita terkini dan informasi desa",
            icon: BookOpen,
            href: "/artikel",
            color: "wood",
            popular: true
        },
        {
            title: "Data Statistik",
            description: "Dashboard data desa lengkap",
            icon: TrendingUp,
            href: "/statistik",
            color: "forest",
            popular: false
        },
        {
            title: "Tentang Desa",
            description: "Profil, visi misi, dan struktur organisasi",
            icon: Building2,
            href: "/tentang",
            color: "sage",
            popular: false
        }
    ];

    // Statistik Desa
    const villageStats = [
        {
            icon: Users,
            value: "5,234",
            label: "Total Penduduk",
            subLabel: "Jiwa terdaftar",
            color: "forest",
            trend: "+2.3%"
        },
        {
            icon: HomeIcon,
            value: "1,456",
            label: "Kepala Keluarga",
            subLabel: "Rumah tangga",
            color: "sage",
            trend: "+1.8%"
        },
        {
            icon: Building2,
            value: "87",
            label: "UMKM Aktif",
            subLabel: "Usaha terdaftar",
            color: "wood",
            trend: "+12%"
        },
        {
            icon: Leaf,
            value: "850 Ha",
            label: "Lahan Produktif",
            subLabel: "Pertanian & kebun",
            color: "forest",
            trend: "Stabil"
        }
    ];

    // Sample announcements
    const defaultAnnouncements = [
        {
            type: "urgent",
            icon: Bell,
            title: "Jadwal Posyandu",
            message: "Posyandu Balita & Lansia tanggal 25 Januari 2026 di Balai Desa pukul 08:00 WIB",
            date: "2026-01-19"
        },
        {
            type: "info",
            icon: Info,
            title: "Pembagian BLT",
            message: "Penerima BLT bulan Januari dapat mengambil di Kantor Desa mulai 20-25 Januari 2026",
            date: "2026-01-18"
        },
        {
            type: "warning",
            icon: AlertCircle,
            title: "Gotong Royong",
            message: "Kerja bakti membersihkan jalan desa setiap hari Minggu jam 07:00 WIB",
            date: "2026-01-15"
        }
    ];

    const activeAnnouncements = announcements.length > 0 ? announcements : defaultAnnouncements;

    // Struktur Organisasi - LENGKAP dengan semua perangkat desa
    const organizationStructure = [
        {
            level: 1,
            position: "Kepala Desa",
            name: "Aminudin",
            photo: "/images/kades.jpeg",
            phone: "0812-3456-7890",
            email: "kepaladesa@sindanganom.id",
            period: "2022-2028"
        },
        {
            level: 2,
            position: "Sekretaris Desa",
            name: "Miswadi",
            photo: "http://metadesa.id/LAMPUNG/LAMPUNGTIMUR/SekampungUdik/SindangAnom/foto_aparat/18_07_12_2007_Aparat_1807120507690009.png",
            phone: "0813-4567-8901",
            email: "sekdes@sindanganom.id",
            period: "2022-2028"
        },
        {
            level: 3,
            position: "Kepala Seksi Pelayanan",
            name: "Mat Mujianto",
            photo: "http://metadesa.id/LAMPUNG/LAMPUNGTIMUR/SekampungUdik/SindangAnom/foto_aparat/18_07_12_2007_Aparat_1807121304950002.png",
            phone: "0814-5678-9012",
            email: "pelayanan@sindanganom.id"
        },
        {
            level: 3,
            position: "Kepala Urusan Tata Usaha dan Umum",
            name: "Selvi Yulia Harsusi",
            photo: "http://metadesa.id/LAMPUNG/LAMPUNGTIMUR/SekampungUdik/SindangAnom/foto_aparat/18_07_12_2007_Aparat_1807125404940007.png",
            phone: "0815-6789-0123",
            email: "tatausaha@sindanganom.id"
        },
        {
            level: 3,
            position: "Kepala Seksi Pemerintahan",
            name: "Deni Riyanto",
            photo: "http://metadesa.id/LAMPUNG/LAMPUNGTIMUR/SekampungUdik/SindangAnom/foto_aparat/18_07_12_2007_Aparat_1807121712860001.png",
            phone: "0816-7890-1234",
            email: "pemerintahan@sindanganom.id"
        },
        {
            level: 3,
            position: "Kepala Kesejahteraan",
            name: "Bayu Mochammad Fathoni",
            photo: "http://metadesa.id/LAMPUNG/LAMPUNGTIMUR/SekampungUdik/SindangAnom/foto_aparat/18_07_12_2007_Aparat_3273020502850011.png",
            phone: "0817-8901-2345",
            email: "kesejahteraan@sindanganom.id"
        },
        {
            level: 3,
            position: "Kepala Urusan Keuangan",
            name: "Uun Nursalim",
            photo: "http://metadesa.id/LAMPUNG/LAMPUNGTIMUR/SekampungUdik/SindangAnom/foto_aparat/18_07_12_2007_Aparat_1807120701890004.png",
            phone: "0818-9012-3456",
            email: "keuangan@sindanganom.id"
        },
        {
            level: 4,
            position: "Kepala Dusun",
            name: "Sanusi",
            photo: "http://metadesa.id/LAMPUNG/LAMPUNGTIMUR/SekampungUdik/SindangAnom/foto_aparat/18_07_12_2007_Aparat_1807120310190007.png",
            phone: "0819-0123-4567",
            email: "kadus1@sindanganom.id"
        },
        {
            level: 4,
            position: "Kepala Dusun",
            name: "Cecep Nurdin",
            photo: "http://metadesa.id/LAMPUNG/LAMPUNGTIMUR/SekampungUdik/SindangAnom/foto_aparat/18_07_12_2007_Aparat_1807122602890002.png",
            phone: "0820-1234-5678",
            email: "kadus2@sindanganom.id"
        },
        {
            level: 4,
            position: "Kepala Dusun",
            name: "Hermawan",
            photo: "http://metadesa.id/LAMPUNG/LAMPUNGTIMUR/SekampungUdik/SindangAnom/foto_aparat/18_07_12_2007_Aparat_1807121905970005.png",
            phone: "0821-2345-6789",
            email: "kadus3@sindanganom.id"
        },
        {
            level: 4,
            position: "Kepala Dusun",
            name: "Sutrisno",
            photo: "http://metadesa.id/LAMPUNG/LAMPUNGTIMUR/SekampungUdik/SindangAnom/foto_aparat/18_07_12_2007_Aparat_1807120506740003.png",
            phone: "0822-3456-7890",
            email: "kadus4@sindanganom.id"
        },
        {
            level: 4,
            position: "Kepala Dusun",
            name: "Elis Susilowati",
            photo: "http://metadesa.id/LAMPUNG/LAMPUNGTIMUR/SekampungUdik/SindangAnom/foto_aparat/18_07_12_2007_Aparat_1807125605000003.png",
            phone: "0823-4567-8901",
            email: "kadus5@sindanganom.id"
        },
        {
            level: 4,
            position: "Kepala Dusun",
            name: "Nasrudin",
            photo: "http://metadesa.id/LAMPUNG/LAMPUNGTIMUR/SekampungUdik/SindangAnom/foto_aparat/18_07_12_2007_Aparat_1807121605980007.png",
            phone: "0824-5678-9012",
            email: "kadus6@sindanganom.id"
        },
        {
            level: 4,
            position: "Kepala Dusun",
            name: "Badarudin",
            photo: "http://metadesa.id/LAMPUNG/LAMPUNGTIMUR/SekampungUdik/SindangAnom/foto_aparat/18_07_12_2007_Aparat_1807120402810003.png",
            phone: "0825-6789-0123",
            email: "kadus7@sindanganom.id"
        },
        {
            level: 4,
            position: "Kepala Dusun",
            name: "Eki Lustiana",
            photo: "http://metadesa.id/LAMPUNG/LAMPUNGTIMUR/SekampungUdik/SindangAnom/foto_aparat/18_07_12_2007_Aparat_1807121209940001.png",
            phone: "0826-7890-1234",
            email: "kadus8@sindanganom.id"
        },
        {
            level: 4,
            position: "Kepala Dusun",
            name: "Saepudin",
            photo: "http://metadesa.id/LAMPUNG/LAMPUNGTIMUR/SekampungUdik/SindangAnom/foto_aparat/18_07_12_2007_Aparat_1807120505870003.png",
            phone: "0827-8901-2345",
            email: "kadus9@sindanganom.id"
        },
        {
            level: 4,
            position: "Kepala Dusun",
            name: "Suradi",
            photo: "http://metadesa.id/LAMPUNG/LAMPUNGTIMUR/SekampungUdik/SindangAnom/foto_aparat/18_07_12_2007_Aparat_1807121505780006.png",
            phone: "0828-9012-3456",
            email: "kadus10@sindanganom.id"
        },
        {
            level: 4,
            position: "Kepala Dusun",
            name: "Muhadi",
            photo: "http://metadesa.id/LAMPUNG/LAMPUNGTIMUR/SekampungUdik/SindangAnom/foto_aparat/18_07_12_2007_Aparat_1807121303710001.png",
            phone: "0829-0123-4567",
            email: "kadus11@sindanganom.id"
        }
    ];

    return (
        <Layout>
            <Head title="Beranda - Desa Sindang Anom" />
            
            <Hero />

            {activeAnnouncements.length > 0 && (
                <div className="bg-gradient-to-r from-forest-700 to-sage-700 py-4 shadow-lg sticky top-0 z-40">
                    <div className="container mx-auto px-4">
                        <div className="flex items-center gap-4">
                            <div className="flex-shrink-0">
                                <Megaphone className="text-white w-6 h-6 animate-pulse" />
                            </div>
                            <div className="flex-1 overflow-hidden">
                                <div className="flex items-center gap-3">
                                    {React.createElement(activeAnnouncements[currentAnnouncementIndex].icon, {
                                        className: "text-forest-100 w-5 h-5"
                                    })}
                                    <span className="font-bold text-white">
                                        {activeAnnouncements[currentAnnouncementIndex].title}:
                                    </span>
                                    <span className="text-forest-50">
                                        {activeAnnouncements[currentAnnouncementIndex].message}
                                    </span>
                                </div>
                            </div>
                            <div className="flex gap-2">
                                {activeAnnouncements.map((_, index) => (
                                    <div
                                        key={index}
                                        className={`w-2 h-2 rounded-full transition-all ${
                                            index === currentAnnouncementIndex ? 'bg-white w-6' : 'bg-white/40'
                                        }`}
                                    />
                                ))}
                            </div>
                        </div>
                    </div>
                </div>
            )}

            <div className="bg-gradient-to-b from-forest-50 to-white">
                
                {/* Quick Links */}
                <div className="container mx-auto px-4 py-16">
                    <div className="text-center mb-12">
                        <div className="inline-flex items-center gap-2 px-6 py-3 bg-forest-100 rounded-full mb-4">
                            <ChevronRight className="text-forest-700" size={20} />
                            <span className="text-forest-700 font-semibold">Akses Cepat</span>
                        </div>
                        <h2 className="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                            Layanan Populer
                        </h2>
                        <p className="text-xl text-gray-600 max-w-3xl mx-auto">
                            Menu yang paling sering dicari warga Desa Sindang Anom
                        </p>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-6xl mx-auto">
                        {quickLinks.map((link, index) => (
                            <Link key={index} href={link.href}>
                                <div className={`group bg-white rounded-3xl p-8 shadow-lg border-2 border-${link.color}-200/50 hover:border-${link.color}-400 hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 cursor-pointer relative overflow-hidden`}>
                                    {link.popular && (
                                        <div className="absolute top-4 right-4 px-3 py-1 bg-gradient-to-r from-forest-600 to-sage-600 text-white text-xs font-bold rounded-full">
                                            Populer
                                        </div>
                                    )}
                                    <div className={`w-16 h-16 bg-gradient-to-br from-${link.color}-500 to-${link.color}-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform shadow-lg`}>
                                        <link.icon size={32} className="text-white" />
                                    </div>
                                    <h3 className="text-2xl font-bold text-gray-900 mb-3 group-hover:text-forest-700 transition">
                                        {link.title}
                                    </h3>
                                    <p className="text-gray-600 leading-relaxed mb-4">
                                        {link.description}
                                    </p>
                                    <div className="flex items-center text-forest-600 font-semibold group-hover:translate-x-2 transition-transform">
                                        <span>Akses Sekarang</span>
                                        <ChevronRight size={20} />
                                    </div>
                                </div>
                            </Link>
                        ))}
                    </div>
                </div>

                {/* Statistik */}
                <div className="bg-gradient-to-br from-forest-800 via-forest-700 to-sage-700 py-20">
                    <div className="container mx-auto px-4">
                        <div className="text-center mb-12">
                            <div className="inline-flex items-center gap-2 px-6 py-3 bg-white/10 backdrop-blur-xl rounded-full border border-white/20 mb-4">
                                <TrendingUp className="text-forest-100" size={20} />
                                <span className="text-white font-semibold">Data Desa</span>
                            </div>
                            <h2 className="text-4xl md:text-5xl font-bold text-white mb-4">
                                Statistik Desa Sindang Anom
                            </h2>
                            <p className="text-xl text-forest-100 max-w-3xl mx-auto">
                                Data terkini per {new Date().toLocaleDateString('id-ID', { 
                                    day: 'numeric', 
                                    month: 'long', 
                                    year: 'numeric' 
                                })}
                            </p>
                        </div>

                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 max-w-7xl mx-auto mb-8">
                            {villageStats.map((stat, index) => (
                                <div key={index} className="bg-white/10 backdrop-blur-xl rounded-3xl p-8 border border-white/20 hover:bg-white/20 hover:scale-105 transition-all duration-500">
                                    <div className="flex items-start justify-between mb-4">
                                        <div className={`w-14 h-14 bg-gradient-to-br from-${stat.color}-400 to-${stat.color}-500 rounded-2xl flex items-center justify-center shadow-lg`}>
                                            <stat.icon size={28} className="text-white" />
                                        </div>
                                        <div className="px-3 py-1 bg-forest-500/30 rounded-full text-forest-100 text-xs font-bold">
                                            {stat.trend}
                                        </div>
                                    </div>
                                    <div className="text-5xl font-bold text-white mb-2">
                                        {stat.value}
                                    </div>
                                    <div className="text-xl font-semibold text-forest-100 mb-1">
                                        {stat.label}
                                    </div>
                                    <div className="text-sm text-forest-200">
                                        {stat.subLabel}
                                    </div>
                                </div>
                            ))}
                        </div>

                        <div className="text-center">
                            <Link href="/statistik">
                                <button className="px-8 py-4 bg-white text-forest-700 rounded-2xl font-bold hover:bg-forest-50 transition-all duration-300 hover:scale-105 shadow-xl">
                                    Lihat Dashboard Lengkap
                                </button>
                            </Link>
                        </div>
                    </div>
                </div>

                {/* Section Kabar Terbaru */}
                <div className="container mx-auto px-4 py-20">
                    <div className="text-center mb-12">
                        <div className="inline-flex items-center gap-2 px-6 py-3 bg-forest-100 rounded-full mb-4">
                            <BookOpen className="text-forest-700" size={20} />
                            <span className="text-forest-700 font-semibold">Informasi Terkini</span>
                        </div>
                        <h2 className="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                            Kabar Terbaru
                        </h2>
                        <p className="text-xl text-gray-600 max-w-3xl mx-auto">
                            Berita dan artikel terbaru dari Desa Sindang Anom
                        </p>
                    </div>

                    {latestArticles.length > 0 ? (
                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-7xl mx-auto">
                            {latestArticles.slice(0, 6).map((article, index) => (
                                <Link key={index} href={`/artikel/${article.id}`}>
                                    <div className="group bg-white rounded-3xl overflow-hidden shadow-lg border border-forest-200/50 hover:shadow-2xl hover:border-forest-400 hover:-translate-y-2 transition-all duration-500">
                                        <div className="relative h-56 bg-gradient-to-br from-forest-100 to-sage-100 overflow-hidden">
                                            <img 
                                                src={article.image || 'https://via.placeholder.com/400x300/16a34a/ffffff?text=Berita+Desa'}
                                                alt={article.title}
                                                className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                                            />
                                            <div className="absolute top-4 left-4">
                                                <div className="px-4 py-2 bg-forest-600 text-white rounded-full text-xs font-bold">
                                                    {article.category || 'Berita'}
                                                </div>
                                            </div>
                                        </div>
                                        <div className="p-6">
                                            <h3 className="text-xl font-bold text-gray-900 mb-3 line-clamp-2 group-hover:text-forest-700 transition">
                                                {article.title}
                                            </h3>
                                            <p className="text-gray-600 text-sm line-clamp-3 mb-4">
                                                {article.excerpt || article.content?.substring(0, 120) + '...'}
                                            </p>
                                            <div className="flex items-center justify-between text-sm text-gray-500">
                                                <div className="flex items-center gap-2">
                                                    <Calendar size={16} />
                                                    <span>{new Date(article.created_at).toLocaleDateString('id-ID')}</span>
                                                </div>
                                                <div className="flex items-center gap-1 text-forest-600 font-semibold">
                                                    <span>Baca</span>
                                                    <ChevronRight size={16} />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </Link>
                            ))}
                        </div>
                    ) : (
                        <div className="text-center py-12">
                            <BookOpen className="w-16 h-16 text-gray-400 mx-auto mb-4" />
                            <p className="text-gray-500 text-lg">Belum ada artikel terbaru</p>
                        </div>
                    )}

                    <div className="text-center mt-12">
                        <Link href="/artikel">
                            <button className="px-8 py-4 bg-gradient-to-r from-forest-600 to-sage-600 text-white rounded-2xl font-bold hover:shadow-2xl transition-all duration-300 hover:scale-105">
                                Lihat Semua Artikel
                            </button>
                        </Link>
                    </div>
                </div>

                {/* Struktur Organisasi - LENGKAP */}
                <div className="bg-gradient-to-b from-white to-forest-50 py-20">
                    <div className="container mx-auto px-4">
                        <div className="text-center mb-12">
                            <div className="inline-flex items-center gap-2 px-6 py-3 bg-forest-100 rounded-full mb-4">
                                <Users className="text-forest-700" size={20} />
                                <span className="text-forest-700 font-semibold">Pemerintahan Desa</span>
                            </div>
                            <h2 className="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                                Struktur Organisasi
                            </h2>
                            <p className="text-xl text-gray-600 max-w-3xl mx-auto">
                                Perangkat desa yang siap melayani dengan transparan dan akuntabel
                            </p>
                        </div>

                        <div className="max-w-6xl mx-auto">
                            {/* Kepala Desa - Level 1 */}
                            <div className="flex justify-center mb-8">
                                {organizationStructure.filter(member => member.level === 1).map((member, index) => (
                                    <div key={index} className="w-full max-w-md">
                                        <div className="group bg-gradient-to-br from-forest-600 to-sage-600 rounded-3xl overflow-hidden shadow-2xl border-4 border-forest-400 hover:shadow-3xl hover:-translate-y-2 transition-all duration-500">
                                            <div className="relative h-80 bg-gradient-to-br from-forest-100 to-sage-100 overflow-hidden">
                                                <img 
                                                    src={member.photo}
                                                    alt={member.name}
                                                    className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                                                    onError={(e) => { e.target.src = 'https://via.placeholder.com/400x400/16a34a/ffffff?text=Kepala+Desa'; }}
                                                />
                                                <div className="absolute inset-0 bg-gradient-to-t from-forest-900/90 to-transparent"></div>
                                                <div className="absolute bottom-6 left-6 right-6">
                                                    <div className="inline-block px-5 py-2 bg-white text-forest-700 rounded-full text-sm font-bold mb-3 shadow-lg">
                                                        {member.position}
                                                    </div>
                                                </div>
                                            </div>
                                            <div className="p-8 text-white">
                                                <h3 className="text-3xl font-bold mb-2">{member.name}</h3>
                                                <div className="text-forest-100 mb-4">Periode: {member.period}</div>
                                                <div className="space-y-3">
                                                    <div className="flex items-center gap-3 bg-white/10 backdrop-blur-xl rounded-xl p-3">
                                                        <Phone size={20} />
                                                        <span>{member.phone}</span>
                                                    </div>
                                                    <div className="flex items-center gap-3 bg-white/10 backdrop-blur-xl rounded-xl p-3">
                                                        <Mail size={20} />
                                                        <span className="truncate">{member.email}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                ))}
                            </div>

                            {/* Sekretaris Desa - Level 2 */}
                            <div className="flex justify-center mb-8">
                                {organizationStructure.filter(member => member.level === 2).map((member, index) => (
                                    <div key={index} className="w-full max-w-sm">
                                        <div className="group bg-white rounded-3xl overflow-hidden shadow-xl border-2 border-sage-300 hover:shadow-2xl hover:border-sage-500 hover:-translate-y-2 transition-all duration-500">
                                            <div className="relative h-64 bg-gradient-to-br from-sage-100 to-forest-100 overflow-hidden">
                                                <img 
                                                    src={member.photo}
                                                    alt={member.name}
                                                    className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                                                    onError={(e) => { e.target.src = 'https://via.placeholder.com/400x400/9ba57c/ffffff?text=Sekdes'; }}
                                                />
                                                <div className="absolute inset-0 bg-gradient-to-t from-sage-900/80 to-transparent"></div>
                                                <div className="absolute bottom-4 left-4 right-4">
                                                    <div className="inline-block px-4 py-2 bg-sage-600 text-white rounded-full text-xs font-bold">
                                                        {member.position}
                                                    </div>
                                                </div>
                                            </div>
                                            <div className="p-6">
                                                <h3 className="text-2xl font-bold text-gray-900 mb-2 group-hover:text-sage-700 transition">
                                                    {member.name}
                                                </h3>
                                                <div className="text-sm text-gray-600 mb-4">Periode: {member.period}</div>
                                                <div className="space-y-2">
                                                    <div className="flex items-center gap-2 text-sm text-gray-600">
                                                        <Phone size={16} className="text-sage-600" />
                                                        <span>{member.phone}</span>
                                                    </div>
                                                    <div className="flex items-center gap-2 text-sm text-gray-600">
                                                        <Mail size={16} className="text-sage-600" />
                                                        <span className="truncate">{member.email}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                ))}
                            </div>

                            {/* Kasi/Kaur - Level 3 */}
                            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                                {organizationStructure.filter(member => member.level === 3).map((member, index) => (
                                    <div key={index} className="group bg-white rounded-3xl overflow-hidden shadow-lg border border-forest-200/50 hover:shadow-2xl hover:border-forest-400 hover:-translate-y-2 transition-all duration-500">
                                        <div className="relative h-56 bg-gradient-to-br from-forest-100 to-wood-100 overflow-hidden">
                                            <img 
                                                src={member.photo}
                                                alt={member.name}
                                                className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                                                onError={(e) => { e.target.src = 'https://via.placeholder.com/400x400/16a34a/ffffff?text=' + member.position.split(' ')[1]; }}
                                            />
                                            <div className="absolute inset-0 bg-gradient-to-t from-forest-900/70 to-transparent"></div>
                                            <div className="absolute bottom-3 left-3 right-3">
                                                <div className="inline-block px-3 py-1 bg-forest-600 text-white rounded-full text-xs font-bold">
                                                    {member.position}
                                                </div>
                                            </div>
                                        </div>
                                        <div className="p-5">
                                            <h3 className="text-lg font-bold text-gray-900 mb-3 group-hover:text-forest-700 transition">
                                                {member.name}
                                            </h3>
                                            <div className="space-y-2">
                                                <div className="flex items-center gap-2 text-xs text-gray-600">
                                                    <Phone size={14} className="text-forest-600" />
                                                    <span>{member.phone}</span>
                                                </div>
                                                <div className="flex items-center gap-2 text-xs text-gray-600">
                                                    <Mail size={14} className="text-forest-600" />
                                                    <span className="truncate">{member.email}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                ))}
                            </div>

                            {/* Kepala Dusun - Level 4 */}
                            <div className="mt-12">
                                <h3 className="text-3xl font-bold text-center text-gray-900 mb-8">
                                    Para Kepala Dusun
                                </h3>
                                <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4">
                                    {organizationStructure.filter(member => member.level === 4).map((member, index) => (
                                        <div key={index} className="group bg-white rounded-2xl overflow-hidden shadow-md border border-forest-200/50 hover:shadow-xl hover:border-forest-400 hover:-translate-y-1 transition-all duration-500">
                                            <div className="relative h-40 bg-gradient-to-br from-wood-100 to-sage-100 overflow-hidden">
                                                <img 
                                                    src={member.photo}
                                                    alt={member.name}
                                                    className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                                                    onError={(e) => { e.target.src = 'https://via.placeholder.com/300x300/c19a6b/ffffff?text=Kadus'; }}
                                                />
                                                <div className="absolute inset-0 bg-gradient-to-t from-forest-900/70 to-transparent"></div>
                                            </div>
                                            <div className="p-4">
                                                <div className="text-xs font-bold text-forest-600 mb-1">{member.position}</div>
                                                <h3 className="text-sm font-bold text-gray-900 mb-2 group-hover:text-forest-700 transition line-clamp-2">
                                                    {member.name}
                                                </h3>
                                                <div className="space-y-1">
                                                    <div className="flex items-center gap-1 text-xs text-gray-600">
                                                        <Phone size={12} className="text-forest-600 flex-shrink-0" />
                                                        <span className="truncate">{member.phone}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    ))}
                                </div>
                            </div>

                            <div className="text-center mt-12">
                                <Link href="/tentang#struktur-organisasi">
                                    <button className="px-8 py-4 bg-gradient-to-r from-forest-600 to-sage-600 text-white rounded-2xl font-bold hover:shadow-2xl transition-all duration-300 hover:scale-105">
                                        Lihat Struktur Lengkap
                                    </button>
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </Layout>
    );
};

export default Home;
