import React from 'react';
import { Chart as ChartJS, CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend, ArcElement } from 'chart.js';
import { Bar, Doughnut } from 'react-chartjs-2';
import { Users, TrendingUp } from 'lucide-react';

ChartJS.register(CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend, ArcElement);

const DemographyChart = () => {
    // Data demografi berdasarkan umur
    const ageData = {
        labels: ['0-10', '11-20', '21-30', '31-40', '41-50', '51-60', '60+'],
        datasets: [
            {
                label: 'Laki-Laki',
                data: [450, 520, 680, 550, 420, 280, 178],
                backgroundColor: 'rgba(37, 99, 235, 0.8)', // primary-600
                borderColor: 'rgba(37, 99, 235, 1)',
                borderWidth: 2,
                borderRadius: 8,
            },
            {
                label: 'Perempuan',
                data: [430, 510, 650, 530, 400, 260, 176],
                backgroundColor: 'rgba(2, 132, 199, 0.8)', // accent-600
                borderColor: 'rgba(2, 132, 199, 1)',
                borderWidth: 2,
                borderRadius: 8,
            }
        ]
    };

    const ageOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'top',
                labels: {
                    font: {
                        size: 12,
                        family: 'Inter, sans-serif'
                    },
                    usePointStyle: true,
                    padding: 15
                }
            },
            title: {
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
                    }
                }
            },
            x: {
                grid: {
                    display: false,
                },
                ticks: {
                    font: {
                        size: 11,
                        family: 'Inter, sans-serif'
                    }
                }
            }
        }
    };

    // Data demografi berdasarkan pendidikan
    const educationData = {
        labels: ['Tidak Sekolah', 'SD', 'SMP', 'SMA', 'Diploma', 'S1', 'S2/S3'],
        datasets: [{
            data: [280, 1250, 980, 1450, 320, 780, 174],
            backgroundColor: [
                'rgba(239, 68, 68, 0.8)',   // red
                'rgba(249, 115, 22, 0.8)',  // orange
                'rgba(234, 179, 8, 0.8)',   // yellow
                'rgba(34, 197, 94, 0.8)',   // green
                'rgba(59, 130, 246, 0.8)',  // blue
                'rgba(168, 85, 247, 0.8)',  // purple
                'rgba(236, 72, 153, 0.8)',  // pink
            ],
            borderColor: [
                'rgba(239, 68, 68, 1)',
                'rgba(249, 115, 22, 1)',
                'rgba(234, 179, 8, 1)',
                'rgba(34, 197, 94, 1)',
                'rgba(59, 130, 246, 1)',
                'rgba(168, 85, 247, 1)',
                'rgba(236, 72, 153, 1)',
            ],
            borderWidth: 2,
        }]
    };

    const educationOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'right',
                labels: {
                    font: {
                        size: 11,
                        family: 'Inter, sans-serif'
                    },
                    usePointStyle: true,
                    padding: 12,
                    generateLabels: (chart) => {
                        const data = chart.data;
                        if (data.labels.length && data.datasets.length) {
                            return data.labels.map((label, i) => {
                                const value = data.datasets[0].data[i];
                                return {
                                    text: `${label}: ${value}`,
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
                        return `${label}: ${value} (${percentage}%)`;
                    }
                }
            }
        }
    };

    return (
        <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {/* Age Distribution Chart */}
            <div className="bg-white rounded-2xl border border-gray-200 p-6 shadow-lg hover:shadow-xl transition-shadow duration-300">
                <div className="flex items-center justify-between mb-6">
                    <div className="flex items-center space-x-3">
                        <div className="p-2 bg-primary-50 rounded-lg">
                            <Users className="w-5 h-5 text-primary-600" />
                        </div>
                        <div>
                            <h3 className="text-lg font-bold text-gray-900">Demografi Usia</h3>
                            <p className="text-sm text-gray-500">Distribusi penduduk per kelompok umur</p>
                        </div>
                    </div>
                </div>
                <div className="h-80">
                    <Bar data={ageData} options={ageOptions} />
                </div>
            </div>

            {/* Education Distribution Chart */}
            <div className="bg-white rounded-2xl border border-gray-200 p-6 shadow-lg hover:shadow-xl transition-shadow duration-300">
                <div className="flex items-center justify-between mb-6">
                    <div className="flex items-center space-x-3">
                        <div className="p-2 bg-accent-50 rounded-lg">
                            <TrendingUp className="w-5 h-5 text-accent-600" />
                        </div>
                        <div>
                            <h3 className="text-lg font-bold text-gray-900">Tingkat Pendidikan</h3>
                            <p className="text-sm text-gray-500">Distribusi berdasarkan jenjang pendidikan</p>
                        </div>
                    </div>
                </div>
                <div className="h-80">
                    <Doughnut data={educationData} options={educationOptions} />
                </div>
            </div>
        </div>
    );
};

export default DemographyChart;
