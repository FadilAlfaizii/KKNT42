import React from 'react';
import { Chart as ChartJS, CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend, ArcElement } from 'chart.js';
import { Bar, Pie } from 'react-chartjs-2';
import { DollarSign, TrendingUp, PieChart as PieChartIcon } from 'lucide-react';

ChartJS.register(CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend, ArcElement);

const BudgetChart = () => {
    // Data APBDes - Alokasi Anggaran per Bidang
    const allocationData = {
        labels: ['Penyelenggaraan', 'Pelaksanaan Pembangunan', 'Pembinaan Masyarakat', 'Pemberdayaan', 'Penanggulangan Bencana', 'Operasional'],
        datasets: [{
            label: 'Anggaran (Juta Rp)',
            data: [450, 820, 380, 450, 150, 200],
            backgroundColor: [
                'rgba(37, 99, 235, 0.8)',   // primary-600
                'rgba(2, 132, 199, 0.8)',    // accent-600
                'rgba(22, 163, 74, 0.8)',    // nature-600
                'rgba(168, 85, 247, 0.8)',   // purple
                'rgba(249, 115, 22, 0.8)',   // orange
                'rgba(236, 72, 153, 0.8)',   // pink
            ],
            borderColor: [
                'rgba(37, 99, 235, 1)',
                'rgba(2, 132, 199, 1)',
                'rgba(22, 163, 74, 1)',
                'rgba(168, 85, 247, 1)',
                'rgba(249, 115, 22, 1)',
                'rgba(236, 72, 153, 1)',
            ],
            borderWidth: 2,
            borderRadius: 8,
        }]
    };

    const allocationOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false,
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                padding: 12,
                titleFont: {
                    size: 14,
                    family: 'Inter, sans-serif'
                },
                bodyFont: {
                    size: 13,
                    family: 'Inter, sans-serif'
                },
                cornerRadius: 8,
                callbacks: {
                    label: function(context) {
                        return `Rp ${context.parsed.y} Juta`;
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    color: 'rgba(0, 0, 0, 0.05)',
                },
                ticks: {
                    font: {
                        size: 11,
                        family: 'Inter, sans-serif'
                    },
                    callback: function(value) {
                        return 'Rp ' + value + 'M';
                    }
                }
            },
            x: {
                grid: {
                    display: false,
                },
                ticks: {
                    font: {
                        size: 10,
                        family: 'Inter, sans-serif'
                    },
                    maxRotation: 45,
                    minRotation: 45
                }
            }
        }
    };

    // Data Realisasi vs Anggaran
    const realisasiData = {
        labels: ['Anggaran', 'Realisasi', 'Sisa'],
        datasets: [{
            data: [2450, 1850, 600],
            backgroundColor: [
                'rgba(37, 99, 235, 0.8)',    // primary-600 - Anggaran
                'rgba(22, 163, 74, 0.8)',     // nature-600 - Realisasi
                'rgba(249, 115, 22, 0.8)',    // orange - Sisa
            ],
            borderColor: [
                'rgba(37, 99, 235, 1)',
                'rgba(22, 163, 74, 1)',
                'rgba(249, 115, 22, 1)',
            ],
            borderWidth: 3,
        }]
    };

    const realisasiOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    font: {
                        size: 12,
                        family: 'Inter, sans-serif'
                    },
                    usePointStyle: true,
                    padding: 20,
                    generateLabels: (chart) => {
                        const data = chart.data;
                        if (data.labels.length && data.datasets.length) {
                            return data.labels.map((label, i) => {
                                const value = data.datasets[0].data[i];
                                return {
                                    text: `${label}: Rp ${value}M`,
                                    fillStyle: data.datasets[0].backgroundColor[i],
                                    strokeStyle: data.datasets[0].borderColor[i],
                                    lineWidth: 2,
                                    hidden: false,
                                    index: i
                                };
                            });
                        }
                        return [];
                    }
                }
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                padding: 12,
                titleFont: {
                    size: 14,
                    family: 'Inter, sans-serif'
                },
                bodyFont: {
                    size: 13,
                    family: 'Inter, sans-serif'
                },
                cornerRadius: 8,
                callbacks: {
                    label: function(context) {
                        const label = context.label || '';
                        const value = context.parsed;
                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                        const percentage = ((value / total) * 100).toFixed(1);
                        return `${label}: Rp ${value} Juta (${percentage}%)`;
                    }
                }
            }
        }
    };

    // Summary stats
    const totalAnggaran = 2450;
    const totalRealisasi = 1850;
    const persentaseRealisasi = ((totalRealisasi / totalAnggaran) * 100).toFixed(1);

    return (
        <div className="space-y-6">
            {/* Summary Cards */}
            <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div className="bg-gradient-to-br from-primary-600 to-primary-500 rounded-2xl p-6 text-white shadow-lg">
                    <div className="flex items-center justify-between mb-3">
                        <div className="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                            <DollarSign className="w-6 h-6" />
                        </div>
                        <span className="text-sm font-medium opacity-90">Total</span>
                    </div>
                    <h3 className="text-3xl font-bold mb-1">Rp {totalAnggaran}M</h3>
                    <p className="text-sm opacity-80">Total Anggaran APBDes</p>
                </div>

                <div className="bg-gradient-to-br from-nature-600 to-nature-500 rounded-2xl p-6 text-white shadow-lg">
                    <div className="flex items-center justify-between mb-3">
                        <div className="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                            <TrendingUp className="w-6 h-6" />
                        </div>
                        <span className="text-sm font-medium opacity-90">Progress</span>
                    </div>
                    <h3 className="text-3xl font-bold mb-1">Rp {totalRealisasi}M</h3>
                    <p className="text-sm opacity-80">Realisasi Anggaran</p>
                </div>

                <div className="bg-gradient-to-br from-accent-600 to-accent-500 rounded-2xl p-6 text-white shadow-lg">
                    <div className="flex items-center justify-between mb-3">
                        <div className="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                            <PieChartIcon className="w-6 h-6" />
                        </div>
                        <span className="text-sm font-medium opacity-90">Rate</span>
                    </div>
                    <h3 className="text-3xl font-bold mb-1">{persentaseRealisasi}%</h3>
                    <p className="text-sm opacity-80">Persentase Realisasi</p>
                </div>
            </div>

            {/* Charts */}
            <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {/* Allocation Chart */}
                <div className="bg-white rounded-2xl border border-gray-200 p-6 shadow-lg hover:shadow-xl transition-shadow duration-300">
                    <div className="flex items-center justify-between mb-6">
                        <div className="flex items-center space-x-3">
                            <div className="p-2 bg-primary-50 rounded-lg">
                                <DollarSign className="w-5 h-5 text-primary-600" />
                            </div>
                            <div>
                                <h3 className="text-lg font-bold text-gray-900">Alokasi Anggaran</h3>
                                <p className="text-sm text-gray-500">Per bidang pembangunan</p>
                            </div>
                        </div>
                    </div>
                    <div className="h-80">
                        <Bar data={allocationData} options={allocationOptions} />
                    </div>
                </div>

                {/* Realization Chart */}
                <div className="bg-white rounded-2xl border border-gray-200 p-6 shadow-lg hover:shadow-xl transition-shadow duration-300">
                    <div className="flex items-center justify-between mb-6">
                        <div className="flex items-center space-x-3">
                            <div className="p-2 bg-nature-50 rounded-lg">
                                <TrendingUp className="w-5 h-5 text-nature-600" />
                            </div>
                            <div>
                                <h3 className="text-lg font-bold text-gray-900">Transparansi APBDes</h3>
                                <p className="text-sm text-gray-500">Anggaran vs realisasi (juta rupiah)</p>
                            </div>
                        </div>
                    </div>
                    <div className="h-80 flex items-center justify-center">
                        <div className="w-full max-w-sm">
                            <Pie data={realisasiData} options={realisasiOptions} />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default BudgetChart;
