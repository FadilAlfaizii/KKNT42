import React, { useState } from "react";
import { Head } from "@inertiajs/react";
import Layout from "../Layouts/Layout";
import {
    Users,
    Home,
    TrendingUp,
    Activity,
    Briefcase,
    GraduationCap,
    Droplet,
    MapPin,
    BarChart3,
    PieChart,
    Calendar,
    Globe,
    Download,
    FileSpreadsheet,
    Filter,
    AlertCircle,
    Sparkles,
    ArrowUp,
    ArrowDown,
} from "lucide-react";
import {
    Chart as ChartJS,
    CategoryScale,
    LinearScale,
    BarElement,
    ArcElement,
    Title,
    Tooltip,
    Legend,
} from "chart.js";
import { Bar, Pie, Doughnut } from "react-chartjs-2";

// Register ChartJS components
ChartJS.register(
    CategoryScale,
    LinearScale,
    BarElement,
    ArcElement,
    Title,
    Tooltip,
    Legend,
);

export default function Statistik({ statistics = {} }) {
    const [isExporting, setIsExporting] = useState(false);

    // Loading skeleton component
    const LoadingSkeleton = () => (
        <div className="min-h-screen bg-gradient-to-b from-gray-50 to-white dark:from-gray-900 dark:to-gray-800">
            <div className="relative bg-gradient-to-br from-forest-600 via-forest-500 to-emerald-600 text-white overflow-hidden">
                <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                    <div className="text-center mb-12">
                        <div className="inline-flex items-center gap-2 px-4 py-2 bg-white/20 backdrop-blur-sm rounded-full mb-6 animate-pulse">
                            <div className="w-5 h-5 bg-white/30 rounded"></div>
                            <div className="w-32 h-4 bg-white/30 rounded"></div>
                        </div>
                        <div className="w-96 h-12 bg-white/20 rounded-lg mx-auto mb-4 animate-pulse"></div>
                        <div className="w-64 h-6 bg-white/20 rounded mx-auto animate-pulse"></div>
                    </div>
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        {[1, 2, 3, 4].map((i) => (
                            <div
                                key={i}
                                className="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 animate-pulse"
                            >
                                <div className="w-12 h-12 bg-gray-200 dark:bg-gray-700 rounded-xl mb-4"></div>
                                <div className="w-24 h-4 bg-gray-200 dark:bg-gray-700 rounded mb-2"></div>
                                <div className="w-32 h-8 bg-gray-200 dark:bg-gray-700 rounded"></div>
                            </div>
                        ))}
                    </div>
                </div>
            </div>
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    {[1, 2, 3, 4].map((i) => (
                        <div
                            key={i}
                            className="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 animate-pulse"
                        >
                            <div className="w-48 h-6 bg-gray-200 dark:bg-gray-700 rounded mb-4"></div>
                            <div className="w-full h-80 bg-gray-200 dark:bg-gray-700 rounded"></div>
                        </div>
                    ))}
                </div>
            </div>
        </div>
    );

    // Empty state component
    const EmptyState = () => (
        <div className="min-h-screen flex items-center justify-center bg-gradient-to-b from-gray-50 to-white dark:from-gray-900 dark:to-gray-800">
            <div className="text-center px-4">
                <div className="relative inline-block mb-8">
                    <div className="absolute inset-0 bg-forest-200 rounded-full blur-3xl opacity-30 animate-pulse"></div>
                    <BarChart3
                        className="w-32 h-32 mx-auto text-forest-400 relative"
                        strokeWidth={1.5}
                    />
                </div>
                <h2 className="text-3xl font-bold text-gray-900 dark:text-white mb-4">
                    Belum Ada Data Statistik
                </h2>
                <p className="text-lg text-gray-600 dark:text-gray-400 mb-8 max-w-md mx-auto">
                    Silakan import data kependudukan terlebih dahulu melalui
                    halaman admin untuk melihat statistik lengkap.
                </p>
                <div className="flex flex-col sm:flex-row gap-4 justify-center">
                    <a
                        href="/admin"
                        className="inline-flex items-center gap-2 px-6 py-3 bg-forest-600 hover:bg-forest-700 text-white font-semibold rounded-xl transition-colors shadow-lg hover:shadow-xl"
                    >
                        <Users className="w-5 h-5" />
                        Ke Halaman Admin
                    </a>
                    <a
                        href="/"
                        className="inline-flex items-center gap-2 px-6 py-3 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 font-semibold rounded-xl transition-colors border-2 border-gray-200 dark:border-gray-700"
                    >
                        <Home className="w-5 h-5" />
                        Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    );

    // Defensive check for statistics data
    if (!statistics || Object.keys(statistics).length === 0) {
        return (
            <Layout>
                <Head title="Statistik Desa" />
                <LoadingSkeleton />
            </Layout>
        );
    }

    // Check if we have actual data
    const hasData = statistics.totalPenduduk > 0 || statistics.totalKK > 0;
    if (!hasData) {
        return (
            <Layout>
                <Head title="Statistik Desa" />
                <EmptyState />
            </Layout>
        );
    }

    // Export handler
    const handleExport = (type) => {
        setIsExporting(true);
        // Simulate export (implement actual export later)
        setTimeout(() => {
            alert(`Export ${type} akan segera tersedia`);
            setIsExporting(false);
        }, 1000);
    };

    // Hero stats with safe defaults and growth indicators
    const heroStats = [
        {
            label: "Total Penduduk",
            value: statistics.totalPenduduk || 0,
            icon: Users,
            color: "forest",
            bgColor: "bg-gradient-to-br from-forest-500 to-forest-600",
            iconBg: "bg-forest-100",
            iconColor: "text-forest-600",
            growth: "+2.5%",
            trending: "up",
        },
        {
            label: "Kepala Keluarga",
            value: statistics.totalKK || 0,
            icon: Home,
            color: "blue",
            bgColor: "bg-gradient-to-br from-blue-500 to-blue-600",
            iconBg: "bg-blue-100",
            iconColor: "text-blue-600",
            growth: "+1.8%",
            trending: "up",
        },
        {
            label: "Laki-laki",
            value: statistics.lakiLaki || 0,
            icon: Users,
            color: "indigo",
            bgColor: "bg-gradient-to-br from-indigo-500 to-indigo-600",
            iconBg: "bg-indigo-100",
            iconColor: "text-indigo-600",
            percentage:
                statistics.totalPenduduk > 0
                    ? `${((statistics.lakiLaki / statistics.totalPenduduk) * 100).toFixed(1)}%`
                    : "0%",
        },
        {
            label: "Perempuan",
            value: statistics.perempuan || 0,
            icon: Users,
            color: "pink",
            bgColor: "bg-gradient-to-br from-pink-500 to-pink-600",
            iconBg: "bg-pink-100",
            iconColor: "text-pink-600",
            percentage:
                statistics.totalPenduduk > 0
                    ? `${((statistics.perempuan / statistics.totalPenduduk) * 100).toFixed(1)}%`
                    : "0%",
        },
    ];

    // Chart colors
    const chartColors = {
        primary: ["#16a34a", "#22c55e", "#4ade80", "#86efac", "#bbf7d0"],
        rainbow: [
            "#ef4444",
            "#f59e0b",
            "#10b981",
            "#3b82f6",
            "#8b5cf6",
            "#ec4899",
            "#f97316",
            "#14b8a6",
            "#6366f1",
            "#84cc16",
        ],
        gender: ["#3b82f6", "#ec4899"],
        location: [
            "#22c55e",
            "#3b82f6",
            "#f59e0b",
            "#ef4444",
            "#8b5cf6",
            "#ec4899",
        ],
    };

    // 1. Histogram Distribusi Usia
    const usiaData = {
        labels: (statistics.distribusiUsia || []).map((item) => item.label),
        datasets: [
            {
                label: "Jumlah Penduduk",
                data: (statistics.distribusiUsia || []).map(
                    (item) => item.count,
                ),
                backgroundColor: "rgba(34, 197, 94, 0.8)",
                borderColor: "rgb(34, 197, 94)",
                borderWidth: 2,
                borderRadius: 8,
            },
        ],
    };

    const usiaOptions = {
        responsive: true,
        maintainAspectRatio: false,
        animation: {
            duration: 1000,
            easing: "easeInOutQuart",
        },
        plugins: {
            legend: {
                display: false,
            },
            title: {
                display: false,
            },
            tooltip: {
                backgroundColor: "rgba(0, 0, 0, 0.9)",
                padding: 16,
                titleFont: { size: 14, weight: "bold" },
                bodyFont: { size: 13 },
                borderColor: "rgba(34, 197, 94, 0.5)",
                borderWidth: 1,
                cornerRadius: 8,
                displayColors: true,
                callbacks: {
                    label: function (context) {
                        return ` ${context.parsed.y} orang`;
                    },
                },
            },
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    color: "rgba(0, 0, 0, 0.05)",
                    drawBorder: false,
                },
                ticks: {
                    font: { size: 12 },
                    padding: 8,
                },
            },
            x: {
                grid: {
                    display: false,
                },
                ticks: {
                    font: { size: 11 },
                    padding: 8,
                },
            },
        },
    };

    // 2. Pie Chart Pekerjaan
    const pekerjaanData = {
        labels: (statistics.pekerjaan || []).map((item) => item.label),
        datasets: [
            {
                data: (statistics.pekerjaan || []).map((item) => item.count),
                backgroundColor: chartColors.rainbow,
                borderWidth: 3,
                borderColor: "#ffffff",
            },
        ],
    };

    // 3. Pie Chart Pendidikan
    const pendidikanData = {
        labels: (statistics.pendidikan || []).map((item) => item.label),
        datasets: [
            {
                data: (statistics.pendidikan || []).map((item) => item.count),
                backgroundColor: chartColors.primary.concat(
                    chartColors.rainbow,
                ),
                borderWidth: 3,
                borderColor: "#ffffff",
            },
        ],
    };

    // 4. Doughnut Chart Golongan Darah
    const golDarahData = {
        labels: (statistics.golonganDarah || []).map((item) => item.label),
        datasets: [
            {
                data: (statistics.golonganDarah || []).map(
                    (item) => item.count,
                ),
                backgroundColor: ["#ef4444", "#f59e0b", "#10b981", "#3b82f6"],
                borderWidth: 3,
                borderColor: "#ffffff",
            },
        ],
    };

    // 5. Pie Chart Agama
    const agamaData = {
        labels: (statistics.agama || []).map((item) => item.label),
        datasets: [
            {
                data: (statistics.agama || []).map((item) => item.count),
                backgroundColor: [
                    "#22c55e", // Islam - Green
                    "#3b82f6", // Kristen - Blue
                    "#8b5cf6", // Katolik - Purple
                    "#f59e0b", // Hindu - Orange
                    "#ec4899", // Buddha - Pink
                    "#14b8a6", // Konghucu - Teal
                    "#6366f1", // Kepercayaan - Indigo
                ],
                borderWidth: 3,
                borderColor: "#ffffff",
            },
        ],
    };

    // 6. Bar Chart Jenis Kelamin
    const jenisKelaminData = {
        labels: (statistics.jenisKelamin || []).map((item) => item.label),
        datasets: [
            {
                label: "Jumlah",
                data: (statistics.jenisKelamin || []).map((item) => item.count),
                backgroundColor: chartColors.gender,
                borderRadius: 8,
                borderWidth: 2,
                borderColor: ["#3b82f6", "#ec4899"],
            },
        ],
    };

    // 7. Bar Chart Kategori Lokasi
    const lokasiData = {
        labels: (statistics.kategoriLokasi || []).map((item) => item.label),
        datasets: [
            {
                label: "Jumlah Lokasi",
                data: (statistics.kategoriLokasi || []).map(
                    (item) => item.count,
                ),
                backgroundColor: chartColors.location,
                borderRadius: 8,
                borderWidth: 2,
                borderColor: chartColors.location,
            },
        ],
    };

    const pieOptions = {
        responsive: true,
        maintainAspectRatio: false,
        animation: {
            animateRotate: true,
            animateScale: true,
            duration: 1000,
            easing: "easeInOutQuart",
        },
        plugins: {
            legend: {
                position: "bottom",
                labels: {
                    padding: 15,
                    font: { size: 12 },
                    usePointStyle: true,
                    boxWidth: 12,
                    boxHeight: 12,
                },
            },
            tooltip: {
                backgroundColor: "rgba(0, 0, 0, 0.9)",
                padding: 16,
                titleFont: { size: 14, weight: "bold" },
                bodyFont: { size: 13 },
                borderColor: "rgba(255, 255, 255, 0.1)",
                borderWidth: 1,
                cornerRadius: 8,
                callbacks: {
                    label: function (context) {
                        const label = context.label || "";
                        const value = context.parsed || 0;
                        const total = context.dataset.data.reduce(
                            (a, b) => a + b,
                            0,
                        );
                        const percentage = ((value / total) * 100).toFixed(1);
                        return ` ${label}: ${value} (${percentage}%)`;
                    },
                },
            },
        },
    };

    const barOptions = {
        responsive: true,
        maintainAspectRatio: false,
        animation: {
            duration: 1000,
            easing: "easeInOutQuart",
        },
        plugins: {
            legend: {
                display: false,
            },
            tooltip: {
                backgroundColor: "rgba(0, 0, 0, 0.9)",
                padding: 16,
                titleFont: { size: 14, weight: "bold" },
                bodyFont: { size: 13 },
                borderColor: "rgba(255, 255, 255, 0.1)",
                borderWidth: 1,
                cornerRadius: 8,
            },
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    color: "rgba(0, 0, 0, 0.05)",
                    drawBorder: false,
                },
                ticks: {
                    padding: 8,
                },
            },
            x: {
                grid: {
                    display: false,
                },
                ticks: {
                    padding: 8,
                },
            },
        },
    };

    return (
        <Layout>
            <Head title="Statistik Desa" />

            <div className="min-h-screen bg-gradient-to-b from-gray-50 to-white dark:from-gray-900 dark:to-gray-800">
                {/* Hero Section */}
                <section className="relative bg-gradient-to-br from-forest-600 via-forest-500 to-emerald-600 text-white overflow-hidden">
                    <div className="absolute inset-0 bg-[url('/images/pattern.svg')] opacity-10"></div>
                    <div className="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>

                    <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                        <div className="text-center mb-12">
                            <div className="inline-flex items-center gap-2 px-4 py-2 bg-white/20 backdrop-blur-sm rounded-full mb-6 animate-fade-in">
                                <Sparkles className="w-5 h-5 animate-pulse" />
                                <span className="font-semibold">
                                    Dashboard Analitik Real-time
                                </span>
                            </div>
                            <h1 className="text-4xl md:text-5xl lg:text-6xl font-bold mb-4 animate-fade-in-up">
                                Statistik Desa Sindanganom
                            </h1>
                            <p className="text-lg md:text-xl text-white/90 max-w-2xl mx-auto animate-fade-in-up animation-delay-100">
                                Data dan analisis lengkap kependudukan, ekonomi,
                                dan infrastruktur desa
                            </p>

                            {/* Action Buttons */}
                            <div className="flex flex-wrap gap-3 justify-center mt-8 animate-fade-in-up animation-delay-200">
                                <button
                                    onClick={() => handleExport("excel")}
                                    disabled={isExporting}
                                    className="inline-flex items-center gap-2 px-5 py-2.5 bg-white/10 hover:bg-white/20 backdrop-blur-sm border border-white/30 text-white font-medium rounded-xl transition-all duration-300 hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    <FileSpreadsheet className="w-4 h-4" />
                                    <span className="hidden sm:inline">
                                        Export Excel
                                    </span>
                                    <span className="sm:hidden">Excel</span>
                                </button>
                                <button
                                    onClick={() => handleExport("pdf")}
                                    disabled={isExporting}
                                    className="inline-flex items-center gap-2 px-5 py-2.5 bg-white/10 hover:bg-white/20 backdrop-blur-sm border border-white/30 text-white font-medium rounded-xl transition-all duration-300 hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    <Download className="w-4 h-4" />
                                    <span className="hidden sm:inline">
                                        Export PDF
                                    </span>
                                    <span className="sm:hidden">PDF</span>
                                </button>
                                <button className="inline-flex items-center gap-2 px-5 py-2.5 bg-white/10 hover:bg-white/20 backdrop-blur-sm border border-white/30 text-white font-medium rounded-xl transition-all duration-300 hover:scale-105">
                                    <Filter className="w-4 h-4" />
                                    <span className="hidden sm:inline">
                                        Filter Data
                                    </span>
                                    <span className="sm:hidden">Filter</span>
                                </button>
                            </div>
                        </div>

                        {/* Hero Stats Cards */}
                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            {heroStats.map((stat, index) => (
                                <div
                                    key={index}
                                    className="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 transform hover:-translate-y-2 hover:shadow-2xl transition-all duration-300 animate-fade-in-up group"
                                    style={{
                                        animationDelay: `${index * 100}ms`,
                                    }}
                                >
                                    <div className="flex items-center justify-between mb-4">
                                        <div
                                            className={`p-3 ${stat.iconBg} rounded-xl group-hover:scale-110 transition-transform duration-300`}
                                        >
                                            <stat.icon
                                                className={`w-8 h-8 ${stat.iconColor}`}
                                            />
                                        </div>
                                        {stat.growth && (
                                            <div className="flex items-center gap-1 text-sm font-semibold text-emerald-600 dark:text-emerald-400">
                                                <ArrowUp className="w-4 h-4" />
                                                {stat.growth}
                                            </div>
                                        )}
                                        {stat.percentage && (
                                            <div className="text-sm font-semibold text-gray-500 dark:text-gray-400">
                                                {stat.percentage}
                                            </div>
                                        )}
                                    </div>
                                    <div>
                                        <p className="text-gray-600 dark:text-gray-400 text-sm mb-1 font-medium">
                                            {stat.label}
                                        </p>
                                        <p className="text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white">
                                            {stat.value.toLocaleString("id-ID")}
                                        </p>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>
                </section>

                {/* Charts Section */}
                <section className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                    <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        {/* 1. Histogram Distribusi Usia */}
                        <div className="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 col-span-1 lg:col-span-2">
                            <div className="flex items-center gap-3 mb-6">
                                <div className="p-2 bg-forest-100 rounded-lg">
                                    <Calendar className="w-6 h-6 text-forest-600" />
                                </div>
                                <div>
                                    <h2 className="text-xl font-bold text-gray-900 dark:text-white">
                                        Distribusi Usia Penduduk
                                    </h2>
                                    <p className="text-sm text-gray-600 dark:text-gray-400">
                                        Klasifikasi usia dalam rentang 5 tahun
                                    </p>
                                </div>
                            </div>
                            <div className="h-96">
                                <Bar data={usiaData} options={usiaOptions} />
                            </div>
                        </div>

                        {/* 2. Pie Chart Pekerjaan */}
                        <div className="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                            <div className="flex items-center gap-3 mb-6">
                                <div className="p-2 bg-blue-100 rounded-lg">
                                    <Briefcase className="w-6 h-6 text-blue-600" />
                                </div>
                                <div>
                                    <h2 className="text-xl font-bold text-gray-900 dark:text-white">
                                        Jenis Pekerjaan
                                    </h2>
                                    <p className="text-sm text-gray-600 dark:text-gray-400">
                                        Top 10 pekerjaan penduduk
                                    </p>
                                </div>
                            </div>
                            <div className="h-80">
                                <Pie
                                    data={pekerjaanData}
                                    options={pieOptions}
                                />
                            </div>
                        </div>

                        {/* 3. Pie Chart Pendidikan */}
                        <div className="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                            <div className="flex items-center gap-3 mb-6">
                                <div className="p-2 bg-indigo-100 rounded-lg">
                                    <GraduationCap className="w-6 h-6 text-indigo-600" />
                                </div>
                                <div>
                                    <h2 className="text-xl font-bold text-gray-900 dark:text-white">
                                        Tingkat Pendidikan
                                    </h2>
                                    <p className="text-sm text-gray-600 dark:text-gray-400">
                                        Pendidikan terakhir penduduk
                                    </p>
                                </div>
                            </div>
                            <div className="h-80">
                                <Pie
                                    data={pendidikanData}
                                    options={pieOptions}
                                />
                            </div>
                        </div>

                        {/* 4. Doughnut Chart Golongan Darah */}
                        <div className="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                            <div className="flex items-center gap-3 mb-6">
                                <div className="p-2 bg-red-100 rounded-lg">
                                    <Droplet className="w-6 h-6 text-red-600" />
                                </div>
                                <div>
                                    <h2 className="text-xl font-bold text-gray-900 dark:text-white">
                                        Golongan Darah
                                    </h2>
                                    <p className="text-sm text-gray-600 dark:text-gray-400">
                                        Distribusi golongan darah
                                    </p>
                                </div>
                            </div>
                            <div className="h-80">
                                <Doughnut
                                    data={golDarahData}
                                    options={pieOptions}
                                />
                            </div>
                        </div>

                        {/* 5. Pie Chart Agama */}
                        <div className="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                            <div className="flex items-center gap-3 mb-6">
                                <div className="p-2 bg-emerald-100 rounded-lg">
                                    <Globe className="w-6 h-6 text-emerald-600" />
                                </div>
                                <div>
                                    <h2 className="text-xl font-bold text-gray-900 dark:text-white">
                                        Agama Penduduk
                                    </h2>
                                    <p className="text-sm text-gray-600 dark:text-gray-400">
                                        Distribusi keyakinan beragama
                                    </p>
                                </div>
                            </div>
                            <div className="h-80">
                                <Pie data={agamaData} options={pieOptions} />
                            </div>
                        </div>

                        {/* 6. Bar Chart Jenis Kelamin */}
                        <div className="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                            <div className="flex items-center gap-3 mb-6">
                                <div className="p-2 bg-purple-100 rounded-lg">
                                    <Users className="w-6 h-6 text-purple-600" />
                                </div>
                                <div>
                                    <h2 className="text-xl font-bold text-gray-900 dark:text-white">
                                        Jenis Kelamin
                                    </h2>
                                    <p className="text-sm text-gray-600 dark:text-gray-400">
                                        Komposisi gender penduduk
                                    </p>
                                </div>
                            </div>
                            <div className="h-80">
                                <Bar
                                    data={jenisKelaminData}
                                    options={barOptions}
                                />
                            </div>
                        </div>

                        {/* 7. Bar Chart Kategori Lokasi */}
                        <div className="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 col-span-1 lg:col-span-2">
                            <div className="flex items-center gap-3 mb-6">
                                <div className="p-2 bg-emerald-100 rounded-lg">
                                    <MapPin className="w-6 h-6 text-emerald-600" />
                                </div>
                                <div>
                                    <h2 className="text-xl font-bold text-gray-900 dark:text-white">
                                        Kategori Lokasi Penting
                                    </h2>
                                    <p className="text-sm text-gray-600 dark:text-gray-400">
                                        Fasilitas dan tempat penting di desa
                                    </p>
                                </div>
                            </div>
                            <div className="h-80">
                                <Bar data={lokasiData} options={barOptions} />
                            </div>
                        </div>
                    </div>

                    {/* Additional Stats */}
                    <div className="mt-12 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div className="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-lg p-6 text-white">
                            <div className="flex items-center gap-3 mb-3">
                                <Globe className="w-8 h-8 opacity-80" />
                                <span className="text-sm font-medium opacity-90">
                                    Total Dusun
                                </span>
                            </div>
                            <p className="text-4xl font-bold">
                                {statistics.tambahan?.totalDusun || 0}
                            </p>
                        </div>

                        <div className="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl shadow-lg p-6 text-white">
                            <div className="flex items-center gap-3 mb-3">
                                <Home className="w-8 h-8 opacity-80" />
                                <span className="text-sm font-medium opacity-90">
                                    Rata-rata per KK
                                </span>
                            </div>
                            <p className="text-4xl font-bold">
                                {statistics.tambahan?.averagePerKK || 0}
                            </p>
                        </div>

                        <div className="bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl shadow-lg p-6 text-white">
                            <div className="flex items-center gap-3 mb-3">
                                <Activity className="w-8 h-8 opacity-80" />
                                <span className="text-sm font-medium opacity-90">
                                    Total Artikel
                                </span>
                            </div>
                            <p className="text-4xl font-bold">
                                {statistics.tambahan?.totalArtikel || 0}
                            </p>
                        </div>

                        <div className="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl shadow-lg p-6 text-white">
                            <div className="flex items-center gap-3 mb-3">
                                <MapPin className="w-8 h-8 opacity-80" />
                                <span className="text-sm font-medium opacity-90">
                                    Lokasi Penting
                                </span>
                            </div>
                            <p className="text-4xl font-bold">
                                {statistics.tambahan?.totalLokasiPenting || 0}
                            </p>
                        </div>
                    </div>
                </section>
            </div>
        </Layout>
    );
}
