import { Link } from '@inertiajs/react';
import { useState, useEffect } from 'react';
import { MapPin, Download, ChevronDown, Leaf, ArrowRight, Sparkles, TreePine } from 'lucide-react';
import hero from '../../../public/images/hero.png'

const Hero = () => {
    const [scrollY, setScrollY] = useState(0);
    const [isVisible, setIsVisible] = useState(false);
    const [mousePosition, setMousePosition] = useState({ x: 0, y: 0 });

    useEffect(() => {
        setIsVisible(true);
        const handleScroll = () => setScrollY(window.scrollY);
        const handleMouseMove = (e) => {
            setMousePosition({
                x: (e.clientX / window.innerWidth) * 20 - 10,
                y: (e.clientY / window.innerHeight) * 20 - 10
            });
        };
        
        window.addEventListener('scroll', handleScroll);
        window.addEventListener('mousemove', handleMouseMove);
        
        return () => {
            window.removeEventListener('scroll', handleScroll);
            window.removeEventListener('mousemove', handleMouseMove);
        };
    }, []);

    const scrollToContent = () => {
        window.scrollTo({ top: window.innerHeight, behavior: 'smooth' });
    };

    return (
        <div className="relative min-h-screen overflow-hidden bg-gradient-to-br from-forest-900 via-forest-800 to-sage-800 md:-mt-[120px]">
            {/* Animated Natural Background */}
            <div className="absolute inset-0">
                {/* Background Image with Parallax - Beautiful Village Scenery */}
                <div 
                    className="absolute inset-0 opacity-30"
                    style={{ transform: `translateY(${scrollY * 0.5}px)` }}
                >
                    <img 
                        src={hero} 
                        alt="Pemandangan Desa Sindang Anom" 
                        className="w-full h-full object-cover scale-110" 
                    />
                </div>

                {/* Natural Gradient Overlay */}
                <div className="absolute inset-0 bg-gradient-to-br from-forest-900/90 via-forest-800/85 to-sage-900/90"></div>
                
                {/* Wood Texture Accent */}
                <div className="absolute inset-0 bg-wood-texture opacity-5"></div>

                {/* Animated Forest Green Orbs */}
                <div 
                    className="absolute top-1/4 left-1/4 w-96 h-96 bg-forest-600/30 rounded-full blur-3xl animate-float"
                    style={{ transform: `translate(${mousePosition.x}px, ${mousePosition.y}px)` }}
                ></div>
                <div 
                    className="absolute bottom-1/4 right-1/4 w-96 h-96 bg-sage-500/25 rounded-full blur-3xl animate-float"
                    style={{ 
                        transform: `translate(${-mousePosition.x}px, ${-mousePosition.y}px)`,
                        animationDelay: '2s'
                    }}
                ></div>
                
                {/* Subtle Grid Pattern */}
                <div className="absolute inset-0 opacity-5" style={{
                    backgroundImage: `
                        linear-gradient(rgba(22, 163, 74, 0.2) 1px, transparent 1px),
                        linear-gradient(90deg, rgba(22, 163, 74, 0.2) 1px, transparent 1px)
                    `,
                    backgroundSize: '60px 60px'
                }}></div>

                {/* Floating Leaf Particles */}
                <div className="absolute inset-0">
                    {[...Array(15)].map((_, i) => (
                        <div
                            key={i}
                            className="absolute text-forest-300/20"
                            style={{
                                left: `${Math.random() * 100}%`,
                                top: `${Math.random() * 100}%`,
                                animation: `float ${8 + Math.random() * 12}s ease-in-out infinite`,
                                animationDelay: `${Math.random() * 5}s`,
                                fontSize: `${16 + Math.random() * 16}px`
                            }}
                        >
                            🍃
                        </div>
                    ))}
                </div>
            </div>

            {/* Main Content */}
            <div className={`relative z-10 min-h-screen flex flex-col items-center justify-center px-4 transition-all duration-1000 ${isVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'}`}>
                
                {/* Badge with Natural Glassmorphism */}
                <div className="mb-8 inline-flex items-center gap-3 px-8 py-4 bg-white/10 backdrop-blur-xl rounded-full border border-forest-400/30 text-white text-sm shadow-2xl hover:bg-white/15 hover:border-forest-400/50 transition-all duration-300 animate-fade-in-down group">
                    <Leaf size={20} className="text-forest-300 group-hover:rotate-12 transition-transform" />
                    <span className="font-medium text-base">🌱 Desa Hijau & Sejahtera</span>
                    <div className="w-2 h-2 bg-forest-400 rounded-full animate-pulse"></div>
                </div>

                {/* Main Title - Bigger & More Prominent */}
                <h1 className="text-6xl sm:text-7xl md:text-8xl lg:text-9xl font-bold mb-8 text-center animate-fade-in-up leading-tight">
                    <span className="bg-gradient-to-r from-white via-forest-100 to-sage-200 bg-clip-text text-transparent drop-shadow-lg">
                        Desa Sindang Anom
                    </span>
                </h1>

                {/* Location Badge with Wood Accent */}
                <div className="flex items-center gap-3 mb-8 px-6 py-3 bg-wood-400/20 backdrop-blur-md rounded-full border border-wood-400/30 animate-fade-in-up" style={{ animationDelay: '200ms' }}>
                    <MapPin size={22} className="text-forest-300" />
                    <span className="text-lg font-medium text-forest-100">Lampung Timur, Indonesia</span>
                </div>

                {/* Subtitle with Natural Typography */}
                <p className="text-lg md:text-xl text-forest-100/90 text-center max-w-3xl mb-6 leading-relaxed animate-fade-in-up font-light" style={{ animationDelay: '300ms' }}>
                    Jl. Pasar Desa Sindang Anom, Kecamatan Sekampung Udik<br />
                    Kabupaten Lampung Timur, Provinsi Lampung | Kode Pos 34183
                </p>

                {/* Tagline with Natural Emphasis */}
                <div className="mb-12 animate-fade-in-up" style={{ animationDelay: '400ms' }}>
                    <p className="text-2xl md:text-3xl bg-gradient-to-r from-sage-200 via-forest-300 to-sage-200 bg-clip-text text-transparent font-bold text-center mb-3 italic tracking-wide">
                        "Bersama Membangun Desa yang Maju dan Sejahtera"
                    </p>
                    <div className="flex items-center justify-center gap-2 text-sage-200/80">
                        <TreePine size={18} />
                        <span className="text-sm">Hijau, Asri, dan Harmonis</span>
                        <TreePine size={18} />
                    </div>
                </div>

                {/* CTA Buttons - Natural Wood Style */}
                <div className="flex flex-col sm:flex-row gap-4 mb-8 animate-fade-in-up" style={{ animationDelay: '500ms' }}>
                    <Link href={"/peta-interaktif"}>
                        <button className="group relative px-10 py-5 bg-gradient-to-r from-forest-600 to-forest-700 text-white rounded-2xl font-semibold overflow-hidden transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:shadow-forest-500/50 border-2 border-forest-500/30">
                            <div className="absolute inset-0 bg-gradient-to-r from-forest-500 to-sage-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            <div className="relative flex items-center gap-3 text-lg">
                                <MapPin size={22} />
                                <span>Jelajahi Peta Desa</span>
                                <ArrowRight size={22} className="group-hover:translate-x-1 transition-transform" />
                            </div>
                        </button>
                    </Link>
                    
                    <Link href={"/"}>
                        <button className="group relative px-10 py-5 bg-wood-400/20 backdrop-blur-xl text-white rounded-2xl font-semibold border-2 border-wood-400/40 overflow-hidden transition-all duration-300 hover:bg-wood-400/30 hover:border-wood-400/60 hover:scale-105">
                            <div className="flex items-center gap-3 text-lg">
                                <Download size={22} />
                                <span>Unduh Dokumen</span>
                            </div>
                        </button>
                    </Link>
                </div>

                {/* Stats Preview with Natural Cards */}
                <div className="grid grid-cols-3 gap-6 md:gap-10 animate-fade-in-up" style={{ animationDelay: '600ms' }}>
                    {[
                        { label: 'Penduduk', value: '5.2K+', icon: '👨‍👩‍👧‍👦', color: 'forest' },
                        { label: 'Keluarga', value: '1.4K+', icon: '🏡', color: 'sage' },
                        { label: 'Wilayah RT', value: '8', icon: '🗺️', color: 'wood' }
                    ].map((stat, index) => (
                        <div key={index} className="relative group cursor-default">
                            <div className="relative bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20 group-hover:bg-white/15 group-hover:border-forest-400/40 transition-all duration-300">
                                <div className="text-4xl mb-3 group-hover:scale-110 transition-transform">{stat.icon}</div>
                                <div className="text-3xl md:text-4xl font-bold text-white mb-2">{stat.value}</div>
                                <div className="text-sm font-medium text-forest-200">{stat.label}</div>
                                <div className={`absolute inset-0 bg-gradient-to-br from-${stat.color}-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity rounded-2xl`}></div>
                            </div>
                        </div>
                    ))}
                </div>

                {/* Scroll Indicator - Natural Style */}
                <button 
                    onClick={scrollToContent}
                    className="absolute bottom-12 left-1/2 transform -translate-x-1/2 cursor-pointer group"
                    aria-label="Scroll ke konten"
                >
                    <div className="flex flex-col items-center gap-3">
                        <span className="text-xs uppercase tracking-widest text-forest-300 group-hover:text-white transition-colors font-semibold">Gulir ke Bawah</span>
                        <div className="w-7 h-11 border-2 border-forest-400 rounded-full flex items-start justify-center p-2 group-hover:border-white transition-colors">
                            <div className="w-1.5 h-2.5 bg-forest-400 rounded-full animate-bounce group-hover:bg-white"></div>
                        </div>
                    </div>
                </button>
            </div>

            {/* Bottom Wave - Natural Green Themed */}
            <div className="absolute bottom-0 left-0 right-0 pointer-events-none">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 120" className="w-full h-auto">
                    <path 
                        fill="#ffffff" 
                        fillOpacity="1" 
                        d="M0,64L48,69.3C96,75,192,85,288,80C384,75,480,53,576,48C672,43,768,53,864,58.7C960,64,1056,64,1152,58.7C1248,53,1344,43,1392,37.3L1440,32L1440,120L1392,120C1344,120,1248,120,1152,120C1056,120,960,120,864,120C768,120,672,120,576,120C480,120,384,120,288,120C192,120,96,120,48,120L0,120Z"
                    ></path>
                </svg>
            </div>
        </div>
    );
};

export default Hero;
