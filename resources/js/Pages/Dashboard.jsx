import React from 'react';
import { Head } from '@inertiajs/react';
import Navbar from '../component/Navbar';
import DashboardStats from '../component/DashboardStats';
import DemographyChart from '../component/DemographyChart';
import BudgetChart from '../component/BudgetChart';
import VillageMap from '../component/VillageMap';
import { LayoutDashboard, Calendar, Clock } from 'lucide-react';

const Dashboard = () => {
    // Get current date and time
    const getCurrentDate = () => {
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        return new Date().toLocaleDateString('id-ID', options);
    };

    const getCurrentTime = () => {
        return new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
    };

    return (
        <>
            <Head title="Dashboard - Sistem Informasi Desa" />
            
            {/* Navbar */}
            <Navbar />

            {/* Main Content */}
            <div className="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50 to-green-50">
                {/* Hero Section with Gradient */}
                <div className="relative bg-gradient-to-br from-primary-600 via-primary-700 to-accent-600 overflow-hidden">
                    {/* Animated Background Pattern */}
                    <div className="absolute inset-0 opacity-10">
                        <div className="absolute inset-0" style={{
                            backgroundImage: `radial-gradient(circle at 2px 2px, white 1px, transparent 0)`,
                            backgroundSize: '40px 40px'
                        }}></div>
                    </div>

                    {/* Floating Orbs */}
                    <div className="absolute top-20 left-20 w-72 h-72 bg-accent-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-float"></div>
                    <div className="absolute top-40 right-20 w-96 h-96 bg-primary-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-float" style={{ animationDelay: '2s' }}></div>

                    <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                        <div className="flex flex-col md:flex-row items-center justify-between">
                            <div className="text-white mb-8 md:mb-0">
                                <div className="flex items-center space-x-3 mb-4">
                                    <div className="p-3 bg-white/20 backdrop-blur-sm rounded-xl">
                                        <LayoutDashboard className="w-8 h-8" />
                                    </div>
                                    <div>
                                        <h1 className="text-4xl md:text-5xl font-bold mb-2">
                                            Dashboard SID
                                        </h1>
                                        <p className="text-xl text-blue-100">
                                            Sistem Informasi Desa Sindang Anom
                                        </p>
                                    </div>
                                </div>
                                <p className="text-blue-100 max-w-2xl text-lg leading-relaxed">
                                    Transparansi data dan informasi desa untuk masyarakat. 
                                    Memantau perkembangan demografi, anggaran, dan wilayah desa secara real-time.
                                </p>
                            </div>

                            {/* Date Time Card */}
                            <div className="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20 min-w-[280px]">
                                <div className="flex items-center space-x-2 mb-3">
                                    <Calendar className="w-5 h-5 text-white" />
                                    <span className="text-white font-semibold">Tanggal & Waktu</span>
                                </div>
                                <div className="text-white">
                                    <p className="text-sm mb-2">{getCurrentDate()}</p>
                                    <div className="flex items-center space-x-2">
                                        <Clock className="w-4 h-4" />
                                        <span className="text-2xl font-bold">{getCurrentTime()} WIB</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Dashboard Content */}
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-12">
                    {/* Statistics Cards */}
                    <section className="animate-fade-in-up">
                        <div className="mb-6">
                            <h2 className="text-2xl font-bold text-gray-900 mb-2">Ringkasan Statistik</h2>
                            <p className="text-gray-600">Data terkini penduduk dan wilayah desa</p>
                        </div>
                        <DashboardStats />
                    </section>

                    {/* Demography Charts */}
                    <section className="animate-fade-in-up" style={{ animationDelay: '100ms' }}>
                        <div className="mb-6">
                            <h2 className="text-2xl font-bold text-gray-900 mb-2">Demografi Penduduk</h2>
                            <p className="text-gray-600">Visualisasi data demografi berdasarkan usia dan pendidikan</p>
                        </div>
                        <DemographyChart />
                    </section>

                    {/* Budget Transparency */}
                    <section className="animate-fade-in-up" style={{ animationDelay: '200ms' }}>
                        <div className="mb-6">
                            <h2 className="text-2xl font-bold text-gray-900 mb-2">Transparansi Anggaran</h2>
                            <p className="text-gray-600">APBDes: Alokasi dan realisasi anggaran desa</p>
                        </div>
                        <BudgetChart />
                    </section>

                    {/* Village Map */}
                    <section className="animate-fade-in-up" style={{ animationDelay: '300ms' }}>
                        <div className="mb-6">
                            <h2 className="text-2xl font-bold text-gray-900 mb-2">Peta Wilayah</h2>
                            <p className="text-gray-600">Lokasi fasilitas umum dan titik penting di desa</p>
                        </div>
                        <VillageMap />
                    </section>

                    {/* Quick Access Cards */}
                    <section className="animate-fade-in-up" style={{ animationDelay: '400ms' }}>
                        <div className="mb-6">
                            <h2 className="text-2xl font-bold text-gray-900 mb-2">Akses Cepat</h2>
                            <p className="text-gray-600">Navigasi ke halaman lainnya</p>
                        </div>
                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            <a href="/tentang" className="group bg-white rounded-2xl border border-gray-200 p-6 hover:shadow-xl hover:-translate-y-2 transition-all duration-300">
                                <div className="inline-flex p-3 bg-primary-50 rounded-xl mb-4 group-hover:bg-primary-100 transition-colors">
                                    <svg className="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <h3 className="text-lg font-bold text-gray-900 mb-2">Tentang Desa</h3>
                                <p className="text-sm text-gray-600">Profil dan sejarah Desa Sindang Anom</p>
                            </a>

                            <a href="/artikel" className="group bg-white rounded-2xl border border-gray-200 p-6 hover:shadow-xl hover:-translate-y-2 transition-all duration-300">
                                <div className="inline-flex p-3 bg-accent-50 rounded-xl mb-4 group-hover:bg-accent-100 transition-colors">
                                    <svg className="w-6 h-6 text-accent-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                    </svg>
                                </div>
                                <h3 className="text-lg font-bold text-gray-900 mb-2">Artikel & Berita</h3>
                                <p className="text-sm text-gray-600">Informasi terkini kegiatan desa</p>
                            </a>

                            <a href="/peta-interaktif" className="group bg-white rounded-2xl border border-gray-200 p-6 hover:shadow-xl hover:-translate-y-2 transition-all duration-300">
                                <div className="inline-flex p-3 bg-nature-50 rounded-xl mb-4 group-hover:bg-nature-100 transition-colors">
                                    <svg className="w-6 h-6 text-nature-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                                    </svg>
                                </div>
                                <h3 className="text-lg font-bold text-gray-900 mb-2">Peta Interaktif</h3>
                                <p className="text-sm text-gray-600">Jelajahi wilayah desa secara detail</p>
                            </a>

                            <a href="/statistik" className="group bg-white rounded-2xl border border-gray-200 p-6 hover:shadow-xl hover:-translate-y-2 transition-all duration-300">
                                <div className="inline-flex p-3 bg-purple-50 rounded-xl mb-4 group-hover:bg-purple-100 transition-colors">
                                    <svg className="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                </div>
                                <h3 className="text-lg font-bold text-gray-900 mb-2">Statistik Lengkap</h3>
                                <p className="text-sm text-gray-600">Data statistik detail penduduk</p>
                            </a>
                        </div>
                    </section>
                </div>

                {/* Footer */}
                <footer className="bg-white border-t border-gray-200 mt-20">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                        <div className="text-center text-gray-600">
                            <p className="text-sm">
                                © 2026 Desa Sindang Anom, Kecamatan Sekampung Udik. 
                                <span className="text-primary-600 font-semibold"> Sistem Informasi Desa</span>
                            </p>
                            <p className="text-xs mt-2 text-gray-500">
                                Dashboard didesain dengan modern UI/UX untuk transparansi dan kemudahan akses informasi
                            </p>
                        </div>
                    </div>
                </footer>
            </div>
        </>
    );
};

export default Dashboard;
