import React, { useState, useEffect } from 'react';
import { Users, Home, TrendingUp, DollarSign, MapPin, FileText, Activity, Award } from 'lucide-react';

const StatCard = ({ icon: Icon, title, value, change, trend, color = "primary", delay = 0 }) => {
    const [isVisible, setIsVisible] = useState(false);
    const [count, setCount] = useState(0);

    useEffect(() => {
        setIsVisible(true);
        
        // Counter animation
        const duration = 2000;
        const steps = 60;
        const increment = value / steps;
        let current = 0;

        const timer = setInterval(() => {
            current += increment;
            if (current >= value) {
                setCount(value);
                clearInterval(timer);
            } else {
                setCount(Math.floor(current));
            }
        }, duration / steps);

        return () => clearInterval(timer);
    }, [value]);

    const colorMap = {
        primary: {
            gradient: 'from-primary-600 to-primary-500',
            text: 'text-primary-600',
            bg: 'bg-primary-50',
            border: 'border-primary-200',
            shadow: 'hover:shadow-primary-500/20',
        },
        accent: {
            gradient: 'from-accent-600 to-accent-500',
            text: 'text-accent-600',
            bg: 'bg-accent-50',
            border: 'border-accent-200',
            shadow: 'hover:shadow-accent-500/20',
        },
        nature: {
            gradient: 'from-nature-600 to-nature-500',
            text: 'text-nature-600',
            bg: 'bg-nature-50',
            border: 'border-nature-200',
            shadow: 'hover:shadow-nature-500/20',
        },
        purple: {
            gradient: 'from-purple-600 to-purple-500',
            text: 'text-purple-600',
            bg: 'bg-purple-50',
            border: 'border-purple-200',
            shadow: 'hover:shadow-purple-500/20',
        }
    };

    const colors = colorMap[color];

    return (
        <div
            className={`
                relative overflow-hidden rounded-2xl bg-white
                border ${colors.border}
                transition-all duration-500
                hover:-translate-y-2 hover:shadow-2xl ${colors.shadow}
                ${isVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'}
            `}
            style={{ transitionDelay: `${delay}ms` }}
        >
            {/* Background gradient overlay */}
            <div className={`absolute inset-0 bg-gradient-to-br ${colors.gradient} opacity-0 hover:opacity-5 transition-opacity duration-300`} />
            
            <div className="relative p-6">
                {/* Icon */}
                <div className={`inline-flex p-3 rounded-xl ${colors.bg} mb-4`}>
                    <Icon className={`w-6 h-6 ${colors.text}`} />
                </div>

                {/* Content */}
                <div className="space-y-2">
                    <p className="text-sm font-medium text-gray-600">{title}</p>
                    <div className="flex items-baseline space-x-3">
                        <h3 className="text-3xl font-bold text-gray-900">
                            {typeof value === 'number' && value > 1000 
                                ? count.toLocaleString('id-ID')
                                : count}
                        </h3>
                        {change && (
                            <span className={`inline-flex items-center text-sm font-semibold ${
                                trend === 'up' ? 'text-nature-600' : 'text-red-600'
                            }`}>
                                {trend === 'up' ? '↗' : '↘'} {change}
                            </span>
                        )}
                    </div>
                </div>

                {/* Progress bar */}
                <div className="mt-4 h-1 bg-gray-100 rounded-full overflow-hidden">
                    <div 
                        className={`h-full bg-gradient-to-r ${colors.gradient} transition-all duration-2000 ease-out`}
                        style={{ 
                            width: isVisible ? '100%' : '0%',
                            transitionDelay: `${delay + 300}ms`
                        }}
                    />
                </div>
            </div>

            {/* Shine effect */}
            <div className="absolute inset-0 opacity-0 hover:opacity-100 transition-opacity duration-500">
                <div className="absolute inset-0 bg-gradient-to-r from-transparent via-white to-transparent opacity-20 transform -skew-x-12 translate-x-[-200%] hover:translate-x-[200%] transition-transform duration-1000" />
            </div>
        </div>
    );
};

const DashboardStats = ({ data }) => {
    const defaultData = {
        totalPenduduk: 5234,
        jumlahKK: 1456,
        jumlahRT: 8,
        jumlahRW: 3,
        laki: 2678,
        perempuan: 2556,
        anggaran: 2450000000,
        realisasi: 1850000000,
    };

    const stats = data || defaultData;
    const realisasiPersen = ((stats.realisasi / stats.anggaran) * 100).toFixed(1);

    const statsConfig = [
        {
            icon: Users,
            title: 'Total Penduduk',
            value: stats.totalPenduduk,
            change: '+2.5%',
            trend: 'up',
            color: 'primary',
            delay: 0
        },
        {
            icon: Home,
            title: 'Jumlah KK',
            value: stats.jumlahKK,
            change: '+1.8%',
            trend: 'up',
            color: 'accent',
            delay: 100
        },
        {
            icon: MapPin,
            title: 'Wilayah RT/RW',
            value: `${stats.jumlahRT}/${stats.jumlahRW}`,
            color: 'nature',
            delay: 200
        },
        {
            icon: DollarSign,
            title: 'APBDes',
            value: `Rp ${(stats.anggaran / 1000000000).toFixed(1)}M`,
            change: `${realisasiPersen}%`,
            trend: 'up',
            color: 'purple',
            delay: 300
        },
        {
            icon: Activity,
            title: 'Laki-Laki',
            value: stats.laki,
            color: 'primary',
            delay: 400
        },
        {
            icon: Users,
            title: 'Perempuan',
            value: stats.perempuan,
            color: 'accent',
            delay: 500
        },
        {
            icon: TrendingUp,
            title: 'Realisasi Anggaran',
            value: `Rp ${(stats.realisasi / 1000000000).toFixed(1)}M`,
            change: `${realisasiPersen}%`,
            trend: 'up',
            color: 'nature',
            delay: 600
        },
        {
            icon: Award,
            title: 'Program Aktif',
            value: 12,
            change: '+3',
            trend: 'up',
            color: 'purple',
            delay: 700
        }
    ];

    return (
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            {statsConfig.map((stat, index) => (
                <StatCard key={index} {...stat} />
            ))}
        </div>
    );
};

export default DashboardStats;
