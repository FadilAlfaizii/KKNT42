import React from "react";
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
    // Defensive check for statistics data
    if (!statistics || Object.keys(statistics).length === 0) {
        return (
            <Layout>
                <Head title="Statistik Desa" />
                <div className="min-h-screen flex items-center justify-center">
                    <div className="text-center">
                        <Activity className="w-16 h-16 mx-auto text-gray-400 mb-4" />
                        <p className="text-gray-600">
                            Memuat data statistik...
                        </p>
                    </div>
                </div>
            </Layout>
        );
    }

    // Hero stats with safe defaults
    const heroStats = [
        {
            label: "Total Penduduk",
            value: statistics.totalPenduduk || 0,
            icon: Users,
            color: "forest",
            bgColor: "bg-gradient-to-br from-forest-500 to-forest-600",
            iconBg: "bg-forest-100",
            iconColor: "text-forest-600",
        },
        {
            label: "Kepala Keluarga",
            value: statistics.totalKK || 0,
            icon: Home,
            color: "blue",
            bgColor: "bg-gradient-to-br from-blue-500 to-blue-600",
            iconBg: "bg-blue-100",
            iconColor: "text-blue-600",
        },
        {
            label: "Laki-laki",
            value: statistics.lakiLaki || 0,
            icon: Users,
            color: "indigo",
            bgColor: "bg-gradient-to-br from-indigo-500 to-indigo-600",
            iconBg: "bg-indigo-100",
            iconColor: "text-indigo-600",
        },
        {
            label: "Perempuan",
            value: statistics.perempuan || 0,
            icon: Users,
            color: "pink",
            bgColor: "bg-gradient-to-br from-pink-500 to-pink-600",
            iconBg: "bg-pink-100",
            iconColor: "text-pink-600",
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
        plugins: {
            legend: {
                display: false,
            },
            title: {
                display: true,
                text: "Distribusi Usia Penduduk",
                font: { size: 18, weight: "bold" },
                padding: 20,
            },
            tooltip: {
                backgroundColor: "rgba(0, 0, 0, 0.8)",
                padding: 12,
                titleFont: { size: 14 },
                bodyFont: { size: 13 },
            },
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    color: "rgba(0, 0, 0, 0.05)",
                },
                ticks: {
                    font: { size: 12 },
                },
            },
            x: {
                grid: {
                    display: false,
                },
                ticks: {
                    font: { size: 11 },
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
        plugins: {
            legend: {
                position: "bottom",
                labels: {
                    padding: 15,
                    font: { size: 12 },
                    usePointStyle: true,
                },
            },
            tooltip: {
                backgroundColor: "rgba(0, 0, 0, 0.8)",
                padding: 12,
                titleFont: { size: 14 },
                bodyFont: { size: 13 },
            },
        },
    };

    const barOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false,
            },
            tooltip: {
                backgroundColor: "rgba(0, 0, 0, 0.8)",
                padding: 12,
            },
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    color: "rgba(0, 0, 0, 0.05)",
                },
            },
            x: {
                grid: {
                    display: false,
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
                            <div className="inline-flex items-center gap-2 px-4 py-2 bg-white/20 backdrop-blur-sm rounded-full mb-6">
                                <BarChart3 className="w-5 h-5" />
                                <span className="font-semibold">
                                    Dashboard Analitik
                                </span>
                            </div>
                            <h1 className="text-4xl md:text-5xl font-bold mb-4">
                                Statistik Desa Sindanganom
                            </h1>
                            <p className="text-lg text-white/90 max-w-2xl mx-auto">
                                Data dan analisis lengkap kependudukan, ekonomi,
                                dan infrastruktur desa
                            </p>
                        </div>

                        {/* Hero Stats Cards */}
                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            {heroStats.map((stat, index) => (
                                <div
                                    key={index}
                                    className="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 transform hover:-translate-y-2 transition-all duration-300"
                                >
                                    <div className="flex items-center justify-between mb-4">
                                        <div
                                            className={`p-3 ${stat.iconBg} rounded-xl`}
                                        >
                                            <stat.icon
                                                className={`w-8 h-8 ${stat.iconColor}`}
                                            />
                                        </div>
                                    </div>
                                    <div>
                                        <p className="text-gray-600 dark:text-gray-400 text-sm mb-1">
                                            {stat.label}
                                        </p>
                                        <p className="text-3xl font-bold text-gray-900 dark:text-white">
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
