import React, { useState, useEffect, useRef } from 'react';
import { Users, Home, MapPin, TrendingUp, Sparkles } from 'lucide-react';

const StatCard = ({ icon: Icon, label, value, suffix = '', delay = 0, gradient = 'from-primary-600 to-accent-600' }) => {
    const [count, setCount] = useState(0);
    const [isVisible, setIsVisible] = useState(false);
    const cardRef = useRef(null);

    useEffect(() => {
        const observer = new IntersectionObserver(
            ([entry]) => {
                if (entry.isIntersecting) {
                    setIsVisible(true);
                }
            },
            { threshold: 0.1 }
        );

        if (cardRef.current) {
            observer.observe(cardRef.current);
        }

        return () => {
            if (cardRef.current) {
                observer.unobserve(cardRef.current);
            }
        };
    }, []);

    useEffect(() => {
        if (!isVisible) return;

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
    }, [isVisible, value]);

    return (
        <div 
            ref={cardRef}
            className={`group relative overflow-hidden bg-white rounded-3xl p-8 transition-all duration-500 hover:-translate-y-3 border border-gray-100 hover:border-transparent ${
                isVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'
            }`}
            style={{ transitionDelay: `${delay}ms` }}
        >
            {/* Gradient Background on Hover */}
            <div className={`absolute inset-0 bg-gradient-to-br ${gradient} opacity-0 group-hover:opacity-100 transition-opacity duration-500`}></div>
            
            {/* Animated Orb */}
            <div className={`absolute -top-10 -right-10 w-32 h-32 bg-gradient-to-br ${gradient} rounded-full blur-3xl opacity-0 group-hover:opacity-30 transition-opacity duration-500`}></div>
            
            {/* Content */}
            <div className="relative z-10">
                {/* Icon Container */}
                <div className="flex items-center justify-between mb-6">
                    <div className={`p-4 bg-gradient-to-br ${gradient} rounded-2xl shadow-lg shadow-primary-500/30 group-hover:shadow-primary-500/50 transition-shadow duration-300 group-hover:scale-110 transform`}>
                        <Icon className="w-8 h-8 text-white" />
                    </div>
                    <div className="opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <Sparkles className="w-6 h-6 text-accent-400 animate-pulse" />
                    </div>
                </div>
                
                {/* Stats */}
                <div className="space-y-2">
                    <h3 className="text-4xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent group-hover:from-white group-hover:to-white transition-all duration-300">
                        {count.toLocaleString('id-ID')}
                        {suffix && <span className="text-2xl ml-1">{suffix}</span>}
                    </h3>
                    <p className="text-sm font-medium text-gray-600 group-hover:text-white/90 transition-colors duration-300">
                        {label}
                    </p>
                </div>

                {/* Progress Bar */}
                <div className="mt-6 h-1.5 bg-gray-100 rounded-full overflow-hidden group-hover:bg-white/20">
                    <div 
                        className={`h-full bg-gradient-to-r ${gradient} transition-all duration-1000 ease-out`}
                        style={{ width: isVisible ? '100%' : '0%', transitionDelay: `${delay + 200}ms` }}
                    ></div>
                </div>
            </div>

            {/* Shine Effect */}
            <div className="absolute inset-0 -translate-x-full group-hover:translate-x-full transition-transform duration-1000 bg-gradient-to-r from-transparent via-white/20 to-transparent"></div>
        </div>
    );
};

const StatisticDesa = ({ data }) => {
    const defaultData = {
        totalPenduduk: 5234,
        jumlahKK: 1456,
        jumlahRT: 8,
        jumlahRW: 3,
    };

    const stats = data || defaultData;

    return (
        <section className="relative py-20 bg-gradient-to-br from-gray-50 via-white to-blue-50 overflow-hidden">
            {/* Background Decorations */}
            <div className="absolute inset-0 overflow-hidden">
                <div className="absolute top-1/4 right-0 w-96 h-96 bg-primary-500/10 rounded-full blur-3xl animate-float"></div>
                <div className="absolute bottom-1/4 left-0 w-96 h-96 bg-accent-500/10 rounded-full blur-3xl animate-float" style={{ animationDelay: '2s' }}></div>
            </div>

            <div className="container mx-auto px-4 relative z-10">
                {/* Section Header */}
                <div className="text-center mb-16 animate-fade-in-up">
                    <div className="inline-flex items-center gap-2 px-4 py-2 bg-primary-100 text-primary-700 rounded-full text-sm font-medium mb-6">
                        <TrendingUp size={16} />
                        <span>Statistik Real-time</span>
                    </div>
                    
                    <h2 className="text-4xl md:text-5xl font-bold mb-4">
                        <span className="bg-gradient-to-r from-primary-600 to-accent-600 bg-clip-text text-transparent">
                            Data Desa
                        </span>
                    </h2>
                    <p className="text-gray-600 max-w-2xl mx-auto text-lg">
                        Informasi terkini mengenai statistik dan demografi Desa Sindang Anom
                    </p>
                </div>

                {/* Stats Grid */}
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 max-w-7xl mx-auto">
                    <StatCard
                        icon={Users}
                        label="Total Penduduk"
                        value={stats.totalPenduduk}
                        suffix=" Jiwa"
                        delay={0}
                        gradient="from-primary-600 to-primary-500"
                    />
                    
                    <StatCard
                        icon={Home}
                        label="Kepala Keluarga"
                        value={stats.jumlahKK}
                        suffix=" KK"
                        delay={100}
                        gradient="from-accent-600 to-accent-500"
                    />
                    
                    <StatCard
                        icon={MapPin}
                        label="Rukun Tetangga"
                        value={stats.jumlahRT}
                        suffix=" RT"
                        delay={200}
                        gradient="from-primary-700 to-accent-600"
                    />
                    
                    <StatCard
                        icon={MapPin}
                        label="Rukun Warga"
                        value={stats.jumlahRW}
                        suffix=" RW"
                        delay={300}
                        gradient="from-accent-700 to-primary-600"
                    />
                </div>

                {/* Additional Info */}
                <div className="mt-16 text-center animate-fade-in-up">
                    <div className="inline-flex items-center gap-2 px-6 py-3 bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-gray-100">
                        <div className="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                        <p className="text-sm text-gray-600">
                            Terakhir diperbarui: {new Date().toLocaleDateString('id-ID', { 
                                day: 'numeric', 
                                month: 'long', 
                                year: 'numeric' 
                            })}
                        </p>
                    </div>
                </div>
            </div>
        </section>
    );
};

export default StatisticDesa;
