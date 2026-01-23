import { Link } from "@inertiajs/react";
import React, { useState, useEffect, useRef } from "react";
import { Menu, X, ChevronDown } from "react-feather";
import { 
    Home, FileText, Users, BookOpen, Map, BarChart3, 
    Building2, AlertCircle, PhoneCall, Sparkles
} from "lucide-react";
import Logo from "../../../public/images/Logo.png";

const Navbar = () => {
    const [isMenuOpen, setIsMenuOpen] = useState(false);
    const [scrolled, setScrolled] = useState(false);
    const [activeSection, setActiveSection] = useState("/");
    const [openDropdown, setOpenDropdown] = useState(null);
    const dropdownRef = useRef(null);

    useEffect(() => {
        const handleScroll = () => {
            setScrolled(window.scrollY > 20);
        };

        const handleClickOutside = (event) => {
            if (dropdownRef.current && !dropdownRef.current.contains(event.target)) {
                setOpenDropdown(null);
            }
        };

        window.addEventListener("scroll", handleScroll);
        document.addEventListener("mousedown", handleClickOutside);
        setActiveSection(window.location.pathname);
        
        return () => {
            window.removeEventListener("scroll", handleScroll);
            document.removeEventListener("mousedown", handleClickOutside);
        };
    }, []);

    const toggleMenu = () => {
        setIsMenuOpen(!isMenuOpen);
        setOpenDropdown(null);
    };

    const closeMenu = () => {
        setIsMenuOpen(false);
        setOpenDropdown(null);
    };

    const toggleDropdown = (menuName) => {
        setOpenDropdown(openDropdown === menuName ? null : menuName);
    };

    const handleAdminClick = () => {
        window.location.href = "/admin";
    };

    const handleEmergencyClick = () => {
        window.location.href = "tel:+628123456789";
    };

    const navLinks = [
        { href: "/", label: "Beranda", icon: Home },
        { 
            type: "dropdown",
            label: "Layanan", 
            icon: FileText,
            submenu: [
                { href: "/layanan-surat", label: "Surat Keterangan", icon: FileText },
                { href: "/pengaduan", label: "Pengaduan Warga", icon: AlertCircle },
                { href: "/bantuan-sosial", label: "Bantuan Sosial", icon: Users }
            ]
        },
        { href: "/peta-interaktif", label: "Peta", icon: Map },
        { href: "/artikel", label: "Berita", icon: BookOpen },
        { 
            type: "dropdown",
            label: "Tentang", 
            icon: Building2,
            submenu: [
                { href: "/tentang#profil", label: "Profil Desa", icon: Building2 },
                { href: "/tentang#struktur-organisasi", label: "Struktur Organisasi", icon: Users }
            ]
        },
        { href: "/statistik", label: "Statistik", icon: BarChart3 }
    ];

    return (
        <>
            <nav 
                className={`fixed top-0 left-0 right-0 z-50 transition-all duration-300 ${
                    scrolled 
                        ? 'py-1 bg-white/95 backdrop-blur-md shadow-lg' 
                        : 'py-2 bg-white/90 backdrop-blur-sm shadow-md'
                }`}
            >
                <div className="max-w-7xl mx-auto px-3 sm:px-4">
                    <div className="flex items-center justify-between">
                        
                        <Link href={"/"} className="flex items-center gap-2 group py-1">
                            <img 
                                src={Logo} 
                                alt="Logo Desa Sindang Anom" 
                                className={`transition-all duration-300 ${scrolled ? 'h-8' : 'h-9'}`}
                            />
                            <div className="hidden md:block">
                                <div className={`text-gray-900 font-bold leading-tight transition-all ${scrolled ? 'text-sm' : 'text-base'}`}>
                                    Desa Sindang Anom
                                </div>
                                <div className="text-forest-600 text-xs">
                                    Lampung Timur
                                </div>
                            </div>
                        </Link>

                        <div className="hidden lg:flex items-center gap-1" ref={dropdownRef}>
                            {navLinks.map((link, index) => {
                                if (link.type === 'dropdown') {
                                    const Icon = link.icon;
                                    const isOpen = openDropdown === link.label;
                                    
                                    return (
                                        <div key={index} className="relative">
                                            <button
                                                onClick={() => toggleDropdown(link.label)}
                                                className={`flex items-center gap-1.5 px-3 py-1.5 rounded-lg font-medium text-sm transition-all duration-200 ${
                                                    isOpen
                                                        ? 'text-white bg-gradient-to-r from-forest-600 to-sage-600'
                                                        : 'text-gray-700 hover:text-forest-700 hover:bg-forest-50'
                                                }`}
                                            >
                                                <Icon size={16} />
                                                <span>{link.label}</span>
                                                <ChevronDown size={14} className={`transition-transform ${isOpen ? 'rotate-180' : ''}`} />
                                            </button>
                                            
                                            {isOpen && (
                                                <div className="absolute top-full mt-1 left-0 min-w-[220px] bg-white rounded-xl shadow-xl border border-forest-100 py-1 z-50">
                                                    {link.submenu.map((sublink, subIndex) => {
                                                        const SubIcon = sublink.icon;
                                                        return (
                                                            <Link
                                                                key={subIndex}
                                                                href={sublink.href}
                                                                onClick={closeMenu}
                                                                className="flex items-center gap-2 px-3 py-2 text-sm hover:bg-forest-50 text-gray-700 hover:text-forest-700 transition-colors"
                                                            >
                                                                <SubIcon size={16} />
                                                                <span>{sublink.label}</span>
                                                            </Link>
                                                        );
                                                    })}
                                                </div>
                                            )}
                                        </div>
                                    );
                                } else {
                                    const Icon = link.icon;
                                    const isActive = activeSection === link.href;
                                    
                                    return (
                                        <Link
                                            key={index}
                                            href={link.href}
                                            className={`flex items-center gap-1.5 px-3 py-1.5 rounded-lg font-medium text-sm transition-all duration-200 ${
                                                isActive 
                                                    ? 'text-white bg-gradient-to-r from-forest-600 to-sage-600'
                                                    : 'text-gray-700 hover:text-forest-700 hover:bg-forest-50'
                                            }`}
                                        >
                                            <Icon size={16} />
                                            <span>{link.label}</span>
                                        </Link>
                                    );
                                }
                            })}
                        </div>

                        <div className="hidden lg:flex items-center gap-2">
                            <button
                                onClick={handleEmergencyClick}
                                className="flex items-center gap-1.5 px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium transition-all duration-200"
                                title="Darurat"
                            >
                                <PhoneCall size={16} />
                                <span className="hidden xl:inline">Darurat</span>
                            </button>

                            <button
                                onClick={handleAdminClick}
                                className="flex items-center gap-1.5 px-3 py-1.5 bg-gradient-to-r from-forest-600 to-sage-600 hover:from-forest-700 hover:to-sage-700 text-white rounded-lg text-sm font-medium transition-all duration-200"
                            >
                                <Sparkles size={16} />
                                <span>Masuk</span>
                            </button>
                        </div>

                        <button
                            onClick={toggleMenu}
                            className="lg:hidden p-2 text-gray-700 hover:text-forest-600 hover:bg-forest-50 rounded-lg transition-colors"
                        >
                            {isMenuOpen ? <X size={24} /> : <Menu size={24} />}
                        </button>
                    </div>

                    <div 
                        className={`lg:hidden overflow-hidden transition-all duration-300 ${
                            isMenuOpen ? 'max-h-[600px] opacity-100 mt-2' : 'max-h-0 opacity-0'
                        }`}
                    >
                        <div className="bg-white rounded-2xl shadow-xl border border-forest-100 p-3 space-y-1">
                            {navLinks.map((link, index) => {
                                if (link.type === 'dropdown') {
                                    const Icon = link.icon;
                                    const isOpen = openDropdown === link.label;
                                    
                                    return (
                                        <div key={index}>
                                            <button
                                                onClick={() => toggleDropdown(link.label)}
                                                className="w-full flex items-center justify-between gap-2 px-3 py-2.5 rounded-xl text-gray-700 hover:text-forest-700 hover:bg-forest-50 transition-colors text-left"
                                            >
                                                <div className="flex items-center gap-2">
                                                    <Icon size={18} />
                                                    <span className="font-medium">{link.label}</span>
                                                </div>
                                                <ChevronDown size={16} className={`transition-transform ${isOpen ? 'rotate-180' : ''}`} />
                                            </button>
                                            
                                            {isOpen && (
                                                <div className="ml-6 mt-1 space-y-1">
                                                    {link.submenu.map((sublink, subIndex) => {
                                                        const SubIcon = sublink.icon;
                                                        return (
                                                            <Link
                                                                key={subIndex}
                                                                href={sublink.href}
                                                                onClick={closeMenu}
                                                                className="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-forest-50 text-sm text-gray-600 hover:text-forest-700 transition-colors"
                                                            >
                                                                <SubIcon size={16} />
                                                                <span>{sublink.label}</span>
                                                            </Link>
                                                        );
                                                    })}
                                                </div>
                                            )}
                                        </div>
                                    );
                                } else {
                                    const Icon = link.icon;
                                    const isActive = activeSection === link.href;
                                    
                                    return (
                                        <Link
                                            key={index}
                                            href={link.href}
                                            onClick={closeMenu}
                                            className={`flex items-center gap-2 px-3 py-2.5 rounded-xl font-medium transition-colors ${
                                                isActive 
                                                    ? 'text-white bg-gradient-to-r from-forest-600 to-sage-600'
                                                    : 'text-gray-700 hover:text-forest-700 hover:bg-forest-50'
                                            }`}
                                        >
                                            <Icon size={18} />
                                            <span>{link.label}</span>
                                        </Link>
                                    );
                                }
                            })}
                            
                            <div className="pt-2 mt-2 border-t border-forest-200 space-y-1">
                                <button
                                    onClick={handleEmergencyClick}
                                    className="w-full flex items-center justify-center gap-2 px-3 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-xl font-medium transition-colors"
                                >
                                    <PhoneCall size={18} />
                                    <span>Hubungi Darurat</span>
                                </button>
                                
                                <button
                                    onClick={handleAdminClick}
                                    className="w-full flex items-center justify-center gap-2 px-3 py-2.5 bg-gradient-to-r from-forest-600 to-sage-600 hover:from-forest-700 hover:to-sage-700 text-white rounded-xl font-medium transition-colors"
                                >
                                    <Sparkles size={18} />
                                    <span>Login Admin</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <div className={`transition-all duration-300 ${scrolled ? 'h-12' : 'h-14'}`}></div>
        </>
    );
};

export default Navbar;
