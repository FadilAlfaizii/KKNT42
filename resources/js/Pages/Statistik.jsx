import React, { useState } from 'react';
import { Head } from '@inertiajs/react';
import Layout from '../Layouts/Layout';
import {
    Users, Heart, TrendingUp, GraduationCap, Leaf, Building2,
    ChevronDown, ChevronUp, Calendar, MapPin, Activity,
    Home, Baby, UserCheck, Briefcase, Stethoscope, School
} from 'lucide-react';

export default function Statistik() {
    const [expandedSection, setExpandedSection] = useState(null);
    const [selectedYear, setSelectedYear] = useState('2026');
    const [hoveredStat, setHoveredStat] = useState(null);

    // Data Statistik Desa (bisa diganti dengan data dari backend)
    const statistics = {
        totalPenduduk: 2547,
        totalKK: 654,
        lakiLaki: 1245,
        perempuan: 1302,
        pertumbuhanPenduduk: 2.3,
        
        // Breakdown Usia
        usia: {
            balita: 234,
            anakAnak: 456,
            remaja: 387,
            dewasa: 1245,
            lansia: 225
        },
        
        // Kesehatan
        kesehatan: {
            posyandu: 4,
            tenagaKesehatan: 12,
            bayi: 89,
            ibuHamil: 34,
            stunting: 8
        },
        
        // Ekonomi
        ekonomi: {
            umkm: 87,
            petani: 234,
            peternak: 156,
            pedagang: 98,
            pns: 45
        },
        
        // Pendidikan
        pendidikan: {
            tk: 3,
            sd: 5,
            smp: 2,
            sma: 1,
            perguruan: 234
        },
        
        // Pertanian
        pertanian: {
            lahanSawah: 450,
            lahanKering: 230,
            perkebunan: 180,
            peternakan: 156,
            produktivitas: 4.2
        }
    };

    const toggleSection = (section) => {
        setExpandedSection(expandedSection === section ? null : section);
    };

    const formatNumber = (num) => {
        return new Intl.NumberFormat('id-ID').format(num || 0);
    };

    // Hero Stats Cards Data
    const heroStats = [
        {
            id: 'penduduk',
            label: 'Total Penduduk',
            value: statistics.totalPenduduk,
            unit: 'Jiwa',
            icon: Users,
            color: 'forest',
            gradient: 'from-forest-500 to-forest-600',
            bgLight: 'bg-forest-50',
            change: '+12 dari tahun lalu'
        },
        {
            id: 'kk',
            label: 'Kepala Keluarga',
            value: statistics.totalKK,
            unit: 'KK',
            icon: Home,
            color: 'emerald',
            gradient: 'from-emerald-500 to-emerald-600',
            bgLight: 'bg-emerald-50',
            change: '+8 dari tahun lalu'
        },
        {
            id: 'lakilaki',
            label: 'Laki-laki',
            value: statistics.lakiLaki,
            unit: 'Jiwa',
            icon: UserCheck,
            color: 'sage',
            gradient: 'from-sage-500 to-sage-600',
            bgLight: 'bg-sage-50',
            change: `${((statistics.lakiLaki/statistics.totalPenduduk)*100).toFixed(1)}%`
        },
        {
            id: 'perempuan',
            label: 'Perempuan',
            value: statistics.perempuan,
            unit: 'Jiwa',
            icon: Users,
            color: 'teal',
            gradient: 'from-teal-500 to-teal-600',
            bgLight: 'bg-teal-50',
            change: `${((statistics.perempuan/statistics.totalPenduduk)*100).toFixed(1)}%`
        }
    ];

    // Quick Stats Categories
    const quickStats = [
        {
            id: 'kesehatan',
            title: 'Kesehatan',
            icon: Heart,
            color: 'emerald',
            gradient: 'from-emerald-500 to-emerald-600',
            value: statistics.kesehatan.posyandu + statistics.kesehatan.tenagaKesehatan,
            unit: 'Fasilitas',
            description: 'Posyandu & Tenaga Medis',
            percentage: 92
        },
        {
            id: 'ekonomi',
            title: 'Ekonomi',
            icon: TrendingUp,
            color: 'sage',
            gradient: 'from-sage-500 to-sage-600',
            value: statistics.ekonomi.umkm,
            unit: 'UMKM',
            description: 'Usaha Mikro Aktif',
            percentage: 78
        },
        {
            id: 'pendidikan',
            title: 'Pendidikan',
            icon: GraduationCap,
            color: 'teal',
            gradient: 'from-teal-500 to-teal-600',
            value: statistics.pendidikan.tk + statistics.pendidikan.sd + statistics.pendidikan.smp + statistics.pendidikan.sma,
            unit: 'Sekolah',
            description: 'Lembaga Pendidikan',
            percentage: 85
        },
        {
            id: 'pertanian',
            title: 'Pertanian',
            icon: Leaf,
            color: 'lime',
            gradient: 'from-lime-500 to-lime-600',
            value: statistics.pertanian.lahanSawah,
            unit: 'Ha',
            description: 'Lahan Produktif',
            percentage: 88
        }
    ];

    return (
        <Layout>
            <Head title="Statistik Desa Sindang Anom" />
            
            {/* Custom Styles for Charts and Animations */}
            <style>{`
                @keyframes fadeInUp {
                    from {
                        opacity: 0;
                        transform: translateY(20px);
                    }
                    to {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }
                
                @keyframes scaleIn {
                    from {
                        opacity: 0;
                        transform: scale(0.95);
                    }
                    to {
                        opacity: 1;
                        transform: scale(1);
                    }
                }

                @keyframes slideDown {
                    from {
                        opacity: 0;
                        max-height: 0;
                    }
                    to {
                        opacity: 1;
                        max-height: 1000px;
                    }
                }
                
                .animate-fade-in-up {
                    animation: fadeInUp 0.6s ease-out forwards;
                }
                
                .animate-scale-in {
                    animation: scaleIn 0.4s ease-out forwards;
                }

                .animate-slide-down {
                    animation: slideDown 0.3s ease-out forwards;
                }
                
                .stat-card {
                    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                }
                
                .stat-card:hover {
                    transform: translateY(-6px);
                }
                
                .progress-bar {
                    transition: width 1s ease-out;
                }
                
                /* Custom Scrollbar */
                .custom-scrollbar::-webkit-scrollbar {
                    width: 6px;
                    height: 6px;
                }
                
                .custom-scrollbar::-webkit-scrollbar-track {
                    background: #f0fdf4;
                    border-radius: 3px;
                }
                
                .custom-scrollbar::-webkit-scrollbar-thumb {
                    background: #16a34a;
                    border-radius: 3px;
                }
                
                .custom-scrollbar::-webkit-scrollbar-thumb:hover {
                    background: #15803d;
                }
            `}</style>

            <div className="min-h-screen bg-gradient-to-br from-gray-50 via-white to-forest-50">
                
                {/* HERO SECTION - Big Numbers */}
                <div className="relative bg-gradient-to-r from-forest-600 via-forest-700 to-forest-800 text-white overflow-hidden">
                    {/* Decorative Elements */}
                    <div className="absolute inset-0 opacity-10">
                        <div className="absolute top-0 left-0 w-96 h-96 bg-white rounded-full filter blur-3xl -translate-x-1/2 -translate-y-1/2"></div>
                        <div className="absolute bottom-0 right-0 w-96 h-96 bg-white rounded-full filter blur-3xl translate-x-1/2 translate-y-1/2"></div>
                    </div>
                    
                    <div className="relative container mx-auto px-4 py-12 lg:py-20">
                        {/* Header */}
                        <div className="flex flex-col lg:flex-row items-start lg:items-center justify-between mb-12">
                            <div className="mb-6 lg:mb-0">
                                <div className="flex items-center gap-3 mb-4">
                                    <Activity className="w-10 h-10 lg:w-12 lg:h-12" strokeWidth={2.5} />
                                    <h1 className="text-4xl lg:text-5xl font-bold">
                                        Statistik Desa
                                    </h1>
                                </div>
                                <p className="text-xl text-forest-100 flex items-center gap-2">
                                    <MapPin className="w-5 h-5" />
                                    Desa Sindang Anom, Lampung Timur
                                </p>
                            </div>
                            
                            {/* Year Selector */}
                            <div className="flex items-center gap-3 bg-white/10 backdrop-blur-md px-4 py-3 rounded-xl border border-white/20">
                                <Calendar className="w-5 h-5 text-forest-100" />
                                <select
                                    value={selectedYear}
                                    onChange={(e) => setSelectedYear(e.target.value)}
                                    className="bg-transparent text-white font-semibold text-lg outline-none cursor-pointer"
                                >
                                    <option value="2026" className="text-gray-900">2026</option>
                                    <option value="2025" className="text-gray-900">2025</option>
                                    <option value="2024" className="text-gray-900">2024</option>
                                </select>
                            </div>
                        </div>
                        
                        {/* Hero Stats Grid */}
                        <div className="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
                            {heroStats.map((stat, index) => {
                                const Icon = stat.icon;
                                return (
                                    <div
                                        key={stat.id}
                                        className="bg-white/10 backdrop-blur-md rounded-2xl p-6 lg:p-8 border border-white/20 hover:bg-white/15 transition-all duration-300 cursor-pointer group"
                                        style={{ animationDelay: `${index * 100}ms` }}
                                        onMouseEnter={() => setHoveredStat(stat.id)}
                                        onMouseLeave={() => setHoveredStat(null)}
                                    >
                                        <div className="flex items-start justify-between mb-4">
                                            <div className={`w-14 h-14 lg:w-16 lg:h-16 rounded-xl bg-gradient-to-br ${stat.gradient} flex items-center justify-center transform group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300 shadow-lg`}>
                                                <Icon className="w-7 h-7 lg:w-8 lg:h-8 text-white" strokeWidth={2.5} />
                                            </div>
                                        </div>
                                        
                                        <div>
                                            <p className="text-forest-100 text-sm lg:text-base mb-2 font-medium">
                                                {stat.label}
                                            </p>
                                            <p className="text-4xl lg:text-5xl font-bold mb-1 tabular-nums">
                                                {formatNumber(stat.value)}
                                            </p>
                                            <p className="text-2xl lg:text-3xl font-semibold text-forest-200 mb-3">
                                                {stat.unit}
                                            </p>
                                            <p className="text-sm text-forest-200 flex items-center gap-1">
                                                <TrendingUp className="w-4 h-4" />
                                                {stat.change}
                                            </p>
                                        </div>
                                    </div>
                                );
                            })}
                        </div>
                    </div>
                </div>

                {/* QUICK STATS SECTION - Category Cards */}
                <div className="container mx-auto px-4 py-12 lg:py-16">
                    <div className="text-center mb-12">
                        <h2 className="text-3xl lg:text-4xl font-bold text-gray-900 mb-3">
                            Ringkasan Kategori
                        </h2>
                        <p className="text-lg text-gray-600">
                            Data utama per sektor di Desa Sindang Anom
                        </p>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        {quickStats.map((stat, index) => {
                            const Icon = stat.icon;
                            return (
                                <div
                                    key={stat.id}
                                    className="stat-card bg-white rounded-2xl shadow-lg hover:shadow-2xl border border-gray-100 overflow-hidden group"
                                    style={{ 
                                        animationDelay: `${index * 100}ms`,
                                        boxShadow: '0 4px 20px -2px rgba(22, 163, 74, 0.08), 0 2px 8px -2px rgba(22, 163, 74, 0.04)'
                                    }}
                                >
                                    {/* Header with Gradient */}
                                    <div className={`p-6 bg-gradient-to-r ${stat.gradient} text-white`}>
                                        <div className="flex items-center justify-between mb-3">
                                            <div className="w-14 h-14 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                                <Icon className="w-7 h-7" strokeWidth={2.5} />
                                            </div>
                                        </div>
                                        <h3 className="text-xl font-bold mb-1">{stat.title}</h3>
                                        <p className="text-sm opacity-90">{stat.description}</p>
                                    </div>
                                    
                                    {/* Content */}
                                    <div className="p-6">
                                        <div className="flex items-baseline gap-2 mb-4">
                                            <span className="text-4xl font-bold text-gray-900 tabular-nums">
                                                {formatNumber(stat.value)}
                                            </span>
                                            <span className="text-xl font-semibold text-gray-500">
                                                {stat.unit}
                                            </span>
                                        </div>
                                        
                                        {/* Progress Bar */}
                                        <div className="space-y-2">
                                            <div className="flex items-center justify-between text-sm">
                                                <span className="text-gray-600">Status</span>
                                                <span className={`font-bold text-${stat.color}-700`}>
                                                    {stat.percentage}%
                                                </span>
                                            </div>
                                            <div className="h-2 bg-gray-100 rounded-full overflow-hidden">
                                                <div
                                                    className={`progress-bar h-full bg-gradient-to-r ${stat.gradient} rounded-full`}
                                                    style={{ width: `${stat.percentage}%` }}
                                                ></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            );
                        })}
                    </div>
                </div>

                {/* DETAIL SECTIONS - Accordions */}
                <div className="container mx-auto px-4 py-8 lg:py-12">
                    
                    {/* Section 1: Kependudukan */}
                    <div className="mb-6">
                        <div
                            className="bg-white rounded-2xl shadow-md hover:shadow-lg transition-all duration-300 border border-gray-100 overflow-hidden cursor-pointer"
                            onClick={() => toggleSection('kependudukan')}
                        >
                            <div className="flex items-center justify-between p-6 lg:p-8 bg-gradient-to-r from-forest-50 to-white">
                                <div className="flex items-center gap-4">
                                    <div className="w-14 h-14 rounded-xl bg-gradient-to-br from-forest-500 to-forest-600 flex items-center justify-center shadow-lg">
                                        <Users className="w-7 h-7 text-white" strokeWidth={2.5} />
                                    </div>
                                    <div>
                                        <h3 className="text-2xl font-bold text-gray-900">Kependudukan</h3>
                                        <p className="text-gray-600">Distribusi dan demografi penduduk</p>
                                    </div>
                                </div>
                                {expandedSection === 'kependudukan' ? (
                                    <ChevronUp className="w-6 h-6 text-forest-600" strokeWidth={2.5} />
                                ) : (
                                    <ChevronDown className="w-6 h-6 text-forest-600" strokeWidth={2.5} />
                                )}
                            </div>
                            
                            {expandedSection === 'kependudukan' && (
                                <div className="p-6 lg:p-8 border-t border-gray-100 animate-slide-down">
                                    <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                        {/* Chart Area */}
                                        <div>
                                            <h4 className="text-lg font-bold text-gray-900 mb-6">Distribusi Usia</h4>
                                            <div className="space-y-4">
                                                {Object.entries(statistics.usia).map(([key, value]) => {
                                                    const labels = {
                                                        balita: 'Balita (0-5 tahun)',
                                                        anakAnak: 'Anak-anak (6-12 tahun)',
                                                        remaja: 'Remaja (13-17 tahun)',
                                                        dewasa: 'Dewasa (18-60 tahun)',
                                                        lansia: 'Lansia (60+ tahun)'
                                                    };
                                                    const percentage = (value / statistics.totalPenduduk * 100).toFixed(1);
                                                    
                                                    return (
                                                        <div key={key} className="group">
                                                            <div className="flex items-center justify-between mb-2">
                                                                <span className="text-gray-700 font-medium">{labels[key]}</span>
                                                                <span className="text-gray-900 font-bold tabular-nums">
                                                                    {formatNumber(value)} ({percentage}%)
                                                                </span>
                                                            </div>
                                                            <div className="h-8 bg-gray-100 rounded-lg overflow-hidden group-hover:shadow-md transition-shadow">
                                                                <div
                                                                    className="h-full bg-gradient-to-r from-forest-500 to-forest-600 rounded-lg flex items-center justify-end pr-3 text-white text-sm font-semibold transition-all duration-1000"
                                                                    style={{ width: `${percentage}%` }}
                                                                >
                                                                    {percentage}%
                                                                </div>
                                                            </div>
                                                        </div>
                                                    );
                                                })}
                                            </div>
                                        </div>
                                        
                                        {/* Detail Table */}
                                        <div>
                                            <h4 className="text-lg font-bold text-gray-900 mb-6">Detail Statistik</h4>
                                            <div className="space-y-3">
                                                <div className="flex items-center justify-between p-4 bg-forest-50 rounded-xl">
                                                    <span className="text-gray-700 font-medium">Total Penduduk</span>
                                                    <span className="text-2xl font-bold text-forest-700 tabular-nums">
                                                        {formatNumber(statistics.totalPenduduk)}
                                                    </span>
                                                </div>
                                                <div className="flex items-center justify-between p-4 bg-emerald-50 rounded-xl">
                                                    <span className="text-gray-700 font-medium">Kepala Keluarga</span>
                                                    <span className="text-2xl font-bold text-emerald-700 tabular-nums">
                                                        {formatNumber(statistics.totalKK)}
                                                    </span>
                                                </div>
                                                <div className="flex items-center justify-between p-4 bg-sage-50 rounded-xl">
                                                    <span className="text-gray-700 font-medium">Laki-laki</span>
                                                    <span className="text-2xl font-bold text-sage-700 tabular-nums">
                                                        {formatNumber(statistics.lakiLaki)}
                                                    </span>
                                                </div>
                                                <div className="flex items-center justify-between p-4 bg-teal-50 rounded-xl">
                                                    <span className="text-gray-700 font-medium">Perempuan</span>
                                                    <span className="text-2xl font-bold text-teal-700 tabular-nums">
                                                        {formatNumber(statistics.perempuan)}
                                                    </span>
                                                </div>
                                                <div className="flex items-center justify-between p-4 bg-lime-50 rounded-xl">
                                                    <span className="text-gray-700 font-medium">Pertumbuhan</span>
                                                    <span className="text-2xl font-bold text-lime-700 flex items-center gap-1">
                                                        <TrendingUp className="w-5 h-5" />
                                                        {statistics.pertumbuhanPenduduk}%
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            )}
                        </div>
                    </div>

                    {/* Section 2: Kesehatan */}
                    <div className="mb-6">
                        <div
                            className="bg-white rounded-2xl shadow-md hover:shadow-lg transition-all duration-300 border border-gray-100 overflow-hidden cursor-pointer"
                            onClick={() => toggleSection('kesehatan')}
                        >
                            <div className="flex items-center justify-between p-6 lg:p-8 bg-gradient-to-r from-emerald-50 to-white">
                                <div className="flex items-center gap-4">
                                    <div className="w-14 h-14 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center shadow-lg">
                                        <Heart className="w-7 h-7 text-white" strokeWidth={2.5} />
                                    </div>
                                    <div>
                                        <h3 className="text-2xl font-bold text-gray-900">Kesehatan</h3>
                                        <p className="text-gray-600">Fasilitas dan data kesehatan</p>
                                    </div>
                                </div>
                                {expandedSection === 'kesehatan' ? (
                                    <ChevronUp className="w-6 h-6 text-emerald-600" strokeWidth={2.5} />
                                ) : (
                                    <ChevronDown className="w-6 h-6 text-emerald-600" strokeWidth={2.5} />
                                )}
                            </div>
                            
                            {expandedSection === 'kesehatan' && (
                                <div className="p-6 lg:p-8 border-t border-gray-100 animate-slide-down">
                                    <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                                        {[
                                            { label: 'Posyandu', value: statistics.kesehatan.posyandu, icon: Building2 },
                                            { label: 'Tenaga Kesehatan', value: statistics.kesehatan.tenagaKesehatan, icon: Stethoscope },
                                            { label: 'Bayi', value: statistics.kesehatan.bayi, icon: Baby },
                                            { label: 'Ibu Hamil', value: statistics.kesehatan.ibuHamil, icon: Heart },
                                            { label: 'Stunting', value: statistics.kesehatan.stunting, icon: Activity }
                                        ].map((item, index) => {
                                            const Icon = item.icon;
                                            return (
                                                <div key={index} className="bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-xl p-6 text-center hover:shadow-lg transition-all transform hover:scale-105">
                                                    <div className="w-12 h-12 mx-auto mb-3 rounded-lg bg-emerald-500 flex items-center justify-center">
                                                        <Icon className="w-6 h-6 text-white" strokeWidth={2.5} />
                                                    </div>
                                                    <p className="text-3xl font-bold text-emerald-700 mb-1 tabular-nums">
                                                        {formatNumber(item.value)}
                                                    </p>
                                                    <p className="text-sm text-gray-600 font-medium">{item.label}</p>
                                                </div>
                                            );
                                        })}
                                    </div>
                                </div>
                            )}
                        </div>
                    </div>

                    {/* Section 3: Ekonomi */}
                    <div className="mb-6">
                        <div
                            className="bg-white rounded-2xl shadow-md hover:shadow-lg transition-all duration-300 border border-gray-100 overflow-hidden cursor-pointer"
                            onClick={() => toggleSection('ekonomi')}
                        >
                            <div className="flex items-center justify-between p-6 lg:p-8 bg-gradient-to-r from-sage-50 to-white">
                                <div className="flex items-center gap-4">
                                    <div className="w-14 h-14 rounded-xl bg-gradient-to-br from-sage-500 to-sage-600 flex items-center justify-center shadow-lg">
                                        <TrendingUp className="w-7 h-7 text-white" strokeWidth={2.5} />
                                    </div>
                                    <div>
                                        <h3 className="text-2xl font-bold text-gray-900">Ekonomi</h3>
                                        <p className="text-gray-600">Mata pencaharian dan UMKM</p>
                                    </div>
                                </div>
                                {expandedSection === 'ekonomi' ? (
                                    <ChevronUp className="w-6 h-6 text-sage-600" strokeWidth={2.5} />
                                ) : (
                                    <ChevronDown className="w-6 h-6 text-sage-600" strokeWidth={2.5} />
                                )}
                            </div>
                            
                            {expandedSection === 'ekonomi' && (
                                <div className="p-6 lg:p-8 border-t border-gray-100 animate-slide-down">
                                    <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                                        {[
                                            { label: 'UMKM', value: statistics.ekonomi.umkm, color: 'sage' },
                                            { label: 'Petani', value: statistics.ekonomi.petani, color: 'lime' },
                                            { label: 'Peternak', value: statistics.ekonomi.peternak, color: 'emerald' },
                                            { label: 'Pedagang', value: statistics.ekonomi.pedagang, color: 'teal' },
                                            { label: 'PNS/ASN', value: statistics.ekonomi.pns, color: 'forest' }
                                        ].map((item, index) => (
                                            <div key={index} className={`bg-gradient-to-br from-${item.color}-50 to-${item.color}-100 rounded-xl p-6 text-center hover:shadow-lg transition-all transform hover:scale-105`}>
                                                <p className={`text-3xl font-bold text-${item.color}-700 mb-1 tabular-nums`}>
                                                    {formatNumber(item.value)}
                                                </p>
                                                <p className="text-sm text-gray-600 font-medium">{item.label}</p>
                                            </div>
                                        ))}
                                    </div>
                                </div>
                            )}
                        </div>
                    </div>

                    {/* Section 4: Pendidikan */}
                    <div className="mb-6">
                        <div
                            className="bg-white rounded-2xl shadow-md hover:shadow-lg transition-all duration-300 border border-gray-100 overflow-hidden cursor-pointer"
                            onClick={() => toggleSection('pendidikan')}
                        >
                            <div className="flex items-center justify-between p-6 lg:p-8 bg-gradient-to-r from-teal-50 to-white">
                                <div className="flex items-center gap-4">
                                    <div className="w-14 h-14 rounded-xl bg-gradient-to-br from-teal-500 to-teal-600 flex items-center justify-center shadow-lg">
                                        <GraduationCap className="w-7 h-7 text-white" strokeWidth={2.5} />
                                    </div>
                                    <div>
                                        <h3 className="text-2xl font-bold text-gray-900">Pendidikan</h3>
                                        <p className="text-gray-600">Lembaga dan tingkat pendidikan</p>
                                    </div>
                                </div>
                                {expandedSection === 'pendidikan' ? (
                                    <ChevronUp className="w-6 h-6 text-teal-600" strokeWidth={2.5} />
                                ) : (
                                    <ChevronDown className="w-6 h-6 text-teal-600" strokeWidth={2.5} />
                                )}
                            </div>
                            
                            {expandedSection === 'pendidikan' && (
                                <div className="p-6 lg:p-8 border-t border-gray-100 animate-slide-down">
                                    <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                                        {[
                                            { label: 'TK/PAUD', value: statistics.pendidikan.tk },
                                            { label: 'SD/MI', value: statistics.pendidikan.sd },
                                            { label: 'SMP/MTs', value: statistics.pendidikan.smp },
                                            { label: 'SMA/SMK', value: statistics.pendidikan.sma },
                                            { label: 'Perguruan Tinggi', value: statistics.pendidikan.perguruan }
                                        ].map((item, index) => (
                                            <div key={index} className="bg-gradient-to-br from-teal-50 to-teal-100 rounded-xl p-6 text-center hover:shadow-lg transition-all transform hover:scale-105">
                                                <div className="w-12 h-12 mx-auto mb-3 rounded-lg bg-teal-500 flex items-center justify-center">
                                                    <School className="w-6 h-6 text-white" strokeWidth={2.5} />
                                                </div>
                                                <p className="text-3xl font-bold text-teal-700 mb-1 tabular-nums">
                                                    {formatNumber(item.value)}
                                                </p>
                                                <p className="text-sm text-gray-600 font-medium">{item.label}</p>
                                            </div>
                                        ))}
                                    </div>
                                </div>
                            )}
                        </div>
                    </div>

                    {/* Section 5: Pertanian */}
                    <div className="mb-6">
                        <div
                            className="bg-white rounded-2xl shadow-md hover:shadow-lg transition-all duration-300 border border-gray-100 overflow-hidden cursor-pointer"
                            onClick={() => toggleSection('pertanian')}
                        >
                            <div className="flex items-center justify-between p-6 lg:p-8 bg-gradient-to-r from-lime-50 to-white">
                                <div className="flex items-center gap-4">
                                    <div className="w-14 h-14 rounded-xl bg-gradient-to-br from-lime-500 to-lime-600 flex items-center justify-center shadow-lg">
                                        <Leaf className="w-7 h-7 text-white" strokeWidth={2.5} />
                                    </div>
                                    <div>
                                        <h3 className="text-2xl font-bold text-gray-900">Pertanian</h3>
                                        <p className="text-gray-600">Lahan dan produktivitas</p>
                                    </div>
                                </div>
                                {expandedSection === 'pertanian' ? (
                                    <ChevronUp className="w-6 h-6 text-lime-600" strokeWidth={2.5} />
                                ) : (
                                    <ChevronDown className="w-6 h-6 text-lime-600" strokeWidth={2.5} />
                                )}
                            </div>
                            
                            {expandedSection === 'pertanian' && (
                                <div className="p-6 lg:p-8 border-t border-gray-100 animate-slide-down">
                                    <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                                        {[
                                            { label: 'Lahan Sawah', value: statistics.pertanian.lahanSawah, unit: 'Ha' },
                                            { label: 'Lahan Kering', value: statistics.pertanian.lahanKering, unit: 'Ha' },
                                            { label: 'Perkebunan', value: statistics.pertanian.perkebunan, unit: 'Ha' },
                                            { label: 'Peternakan', value: statistics.pertanian.peternakan, unit: 'Unit' },
                                            { label: 'Produktivitas', value: statistics.pertanian.produktivitas, unit: 'Ton/Ha' }
                                        ].map((item, index) => (
                                            <div key={index} className="bg-gradient-to-br from-lime-50 to-lime-100 rounded-xl p-6 text-center hover:shadow-lg transition-all transform hover:scale-105">
                                                <p className="text-3xl font-bold text-lime-700 mb-1 tabular-nums">
                                                    {formatNumber(item.value)}
                                                </p>
                                                <p className="text-xs text-lime-600 font-semibold mb-1">{item.unit}</p>
                                                <p className="text-sm text-gray-600 font-medium">{item.label}</p>
                                            </div>
                                        ))}
                                    </div>
                                </div>
                            )}
                        </div>
                    </div>

                </div>

                {/* FOOTER INFO */}
                <div className="container mx-auto px-4 py-8 lg:py-12">
                    <div className="bg-gradient-to-r from-forest-600 via-forest-700 to-forest-800 rounded-2xl p-8 lg:p-12 text-white shadow-2xl">
                        <div className="flex flex-col lg:flex-row items-center justify-between gap-6">
                            <div className="text-center lg:text-left">
                                <h3 className="text-2xl lg:text-3xl font-bold mb-3">
                                    Data Terkini {selectedYear}
                                </h3>
                                <p className="text-forest-100 text-lg mb-4">
                                    Statistik lengkap Desa Sindang Anom, Lampung Timur
                                </p>
                                <p className="text-sm text-forest-200">
                                    Terakhir diperbarui: {new Date().toLocaleDateString('id-ID', { 
                                        day: 'numeric', 
                                        month: 'long', 
                                        year: 'numeric' 
                                    })}
                                </p>
                            </div>
                            <div className="flex flex-col items-center gap-3">
                                <div className="w-20 h-20 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center">
                                    <Activity className="w-10 h-10" strokeWidth={2.5} />
                                </div>
                                <p className="text-sm text-forest-100">Real-time Data</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </Layout>
    );
}
