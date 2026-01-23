import React, { useState, useEffect } from "react";
import {
    MapContainer,
    TileLayer,
    Marker,
    Popup,
    useMapEvents,
} from "react-leaflet";
import { Link } from "@inertiajs/react";
import {
    GraduationCap,
    Heart,
    Building2,
    Church,
    MapPin,
    Filter,
    Store,
    Dumbbell,
} from "lucide-react";
import Navbar from "../component/Navbar";
import Footer from "../component/Footer";
import "leaflet/dist/leaflet.css";
import L from "leaflet";

// Helper function untuk konversi Google Drive URL ke direct image
const getGoogleDriveImageUrl = (url) => {
    if (!url) return null;

    // Extract file ID from various Google Drive URL formats
    let fileId = null;

    // Format: https://drive.google.com/file/d/FILE_ID/view?usp=drive_link
    const match1 = url.match(/\/file\/d\/([^\/]+)/);
    if (match1) {
        fileId = match1[1];
    }

    // Format: https://drive.google.com/open?id=FILE_ID
    const match2 = url.match(/[?&]id=([^&]+)/);
    if (match2) {
        fileId = match2[1];
    }

    // Return thumbnail URL if fileId found
    if (fileId) {
        return `https://drive.google.com/thumbnail?id=${fileId}&sz=w400`;
    }

    return url;
};

// Category badge color mapping
const categoryBadgeColors = {
    Pendidikan: "bg-forest-100 text-forest-800",
    Kesehatan: "bg-red-100 text-red-800",
    Pemerintahan: "bg-yellow-100 text-yellow-800",
    Ibadah: "bg-green-100 text-green-800",
    UMKM: "bg-purple-100 text-purple-800",
    Olahraga: "bg-orange-100 text-orange-800",
};

// Fix for default marker icons in React Leaflet
delete L.Icon.Default.prototype._getIconUrl;
L.Icon.Default.mergeOptions({
    iconRetinaUrl:
        "https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-icon-2x.png",
    iconUrl:
        "https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-icon.png",
    shadowUrl:
        "https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png",
});

// Marker icons dengan warna berbeda untuk setiap kategori (menggunakan marker default Leaflet)
const categoryIcons = {
    Pendidikan: L.icon({
        iconUrl:
            "https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png",
        shadowUrl:
            "https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png",
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41],
    }),
    Kesehatan: L.icon({
        iconUrl:
            "https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png",
        shadowUrl:
            "https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png",
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41],
    }),
    Pemerintahan: L.icon({
        iconUrl:
            "https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-yellow.png",
        shadowUrl:
            "https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png",
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41],
    }),
    Ibadah: L.icon({
        iconUrl:
            "https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png",
        shadowUrl:
            "https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png",
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41],
    }),
    UMKM: L.icon({
        iconUrl:
            "https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-violet.png",
        shadowUrl:
            "https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png",
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41],
    }),
    Olahraga: L.icon({
        iconUrl:
            "https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-orange.png",
        shadowUrl:
            "https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png",
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41],
    }),
};

export default function PetaInteraktif() {
    const [locations, setLocations] = useState([]);
    const [filteredLocations, setFilteredLocations] = useState([]);
    const [selectedCategory, setSelectedCategory] = useState("all");
    const [loading, setLoading] = useState(true);

    const categories = [
        {
            value: "all",
            label: "Semua Lokasi",
            color: "bg-gradient-to-r from-slate-500 to-slate-600",
            icon: MapPin,
            textColor: "text-slate-700",
            bgLight: "bg-slate-50",
        },
        {
            value: "Pendidikan",
            label: "Pendidikan",
            color: "bg-gradient-to-r from-forest-500 to-forest-600",
            icon: GraduationCap,
            textColor: "text-forest-700",
            bgLight: "bg-forest-50",
        },
        {
            value: "Kesehatan",
            label: "Kesehatan",
            color: "bg-gradient-to-r from-red-500 to-red-600",
            icon: Heart,
            textColor: "text-red-700",
            bgLight: "bg-red-50",
        },
        {
            value: "Pemerintahan",
            label: "Pemerintahan",
            color: "bg-gradient-to-r from-yellow-500 to-yellow-600",
            icon: Building2,
            textColor: "text-yellow-700",
            bgLight: "bg-yellow-50",
        },
        {
            value: "Ibadah",
            label: "Ibadah",
            color: "bg-gradient-to-r from-green-500 to-green-600",
            icon: Church,
            textColor: "text-green-700",
            bgLight: "bg-green-50",
        },
        {
            value: "UMKM",
            label: "UMKM",
            color: "bg-gradient-to-r from-purple-500 to-purple-600",
            icon: Store,
            textColor: "text-purple-700",
            bgLight: "bg-purple-50",
        },
        {
            value: "Olahraga",
            label: "Olahraga",
            color: "bg-gradient-to-r from-orange-500 to-orange-600",
            icon: Dumbbell,
            textColor: "text-orange-700",
            bgLight: "bg-orange-50",
        },
    ];

    useEffect(() => {
        fetchLocations();
    }, []);

    useEffect(() => {
        if (selectedCategory === "all") {
            setFilteredLocations(locations);
        } else {
            setFilteredLocations(
                locations.filter((loc) => loc.category === selectedCategory)
            );
        }
    }, [selectedCategory, locations]);

    const fetchLocations = async () => {
        try {
            const response = await fetch("/api/map/locations");
            const data = await response.json();
            setLocations(data);
            setFilteredLocations(data);
            setLoading(false);
        } catch (error) {
            console.error("Error fetching locations:", error);
            setLoading(false);
        }
    };

    const handleCategoryChange = (category) => {
        setSelectedCategory(category);
    };

    const getCategoryStats = () => {
        const stats = {
            total: locations.length,
            Pendidikan: locations.filter((l) => l.category === "Pendidikan")
                .length,
            Kesehatan: locations.filter((l) => l.category === "Kesehatan")
                .length,
            Pemerintahan: locations.filter((l) => l.category === "Pemerintahan")
                .length,
            Ibadah: locations.filter((l) => l.category === "Ibadah").length,
            UMKM: locations.filter((l) => l.category === "UMKM").length,
            Olahraga: locations.filter((l) => l.category === "Olahraga").length,
        };
        return stats;
    };

    const stats = getCategoryStats();

    return (
        <div className="min-h-screen bg-gradient-to-br from-gray-50 to-forest-50">
            <Navbar />

            {/* Header Section */}
            <div className="bg-gradient-to-r from-forest-600 via-forest-700 to-forest-800 text-white py-20">
                <div className="container mx-auto px-4">
                    <div className="flex items-center gap-4 mb-4">
                        <MapPin className="w-12 h-12" />
                        <h1 className="text-5xl font-bold">
                            Peta Interaktif Desa Sindang Anom
                        </h1>
                    </div>
                    <p className="text-xl text-forest-100">
                        Jelajahi fasilitas umum di Desa Sindang Anom, Lampung
                        Timur
                    </p>
                </div>
            </div>

            <div className="container mx-auto px-4 py-8">
                {/* Statistics Cards dengan ikon */}
                <div className="grid grid-cols-2 md:grid-cols-6 gap-4 mb-8">
                    <div className="bg-white rounded-xl shadow-lg p-6 text-center transform hover:scale-105 transition-transform">
                        <MapPin className="w-8 h-8 mx-auto mb-2 text-slate-600" />
                        <div className="text-3xl font-bold text-slate-800">
                            {stats.total}
                        </div>
                        <div className="text-slate-600 text-sm font-medium">
                            Total Lokasi
                        </div>
                    </div>
                    <div className="bg-gradient-to-br from-forest-50 to-forest-100 rounded-xl shadow-lg p-6 text-center transform hover:scale-105 transition-transform">
                        <GraduationCap className="w-8 h-8 mx-auto mb-2 text-forest-600" />
                        <div className="text-3xl font-bold text-forest-700">
                            {stats.Pendidikan}
                        </div>
                        <div className="text-forest-800 text-sm font-medium">
                            Pendidikan
                        </div>
                    </div>
                    <div className="bg-gradient-to-br from-red-50 to-red-100 rounded-xl shadow-lg p-6 text-center transform hover:scale-105 transition-transform">
                        <Heart className="w-8 h-8 mx-auto mb-2 text-red-600" />
                        <div className="text-3xl font-bold text-red-700">
                            {stats.Kesehatan}
                        </div>
                        <div className="text-red-800 text-sm font-medium">
                            Kesehatan
                        </div>
                    </div>
                    <div className="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl shadow-lg p-6 text-center transform hover:scale-105 transition-transform">
                        <Building2 className="w-8 h-8 mx-auto mb-2 text-yellow-600" />
                        <div className="text-3xl font-bold text-yellow-700">
                            {stats.Pemerintahan}
                        </div>
                        <div className="text-yellow-800 text-sm font-medium">
                            Pemerintahan
                        </div>
                    </div>
                    <div className="bg-gradient-to-br from-green-50 to-green-100 rounded-xl shadow-lg p-6 text-center transform hover:scale-105 transition-transform">
                        <Church className="w-8 h-8 mx-auto mb-2 text-green-600" />
                        <div className="text-3xl font-bold text-green-700">
                            {stats.Ibadah}
                        </div>
                        <div className="text-green-800 text-sm font-medium">
                            Ibadah
                        </div>
                    </div>
                    <div className="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl shadow-lg p-6 text-center transform hover:scale-105 transition-transform">
                        <Store className="w-8 h-8 mx-auto mb-2 text-purple-600" />
                        <div className="text-3xl font-bold text-purple-700">
                            {stats.UMKM}
                        </div>
                        <div className="text-purple-800 text-sm font-medium">
                            UMKM
                        </div>
                    </div>
                </div>

                {/* Map dengan Filter di Kiri */}
                <div className="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
                    {/* Filter Sidebar */}
                    <div className="lg:col-span-1">
                        <div className="bg-white rounded-xl shadow-lg p-6 sticky top-24">
                            <div className="flex items-center gap-2 mb-6">
                                <Filter className="w-6 h-6 text-forest-600" />
                                <h2 className="text-xl font-bold text-gray-800">
                                    Filter Kategori
                                </h2>
                            </div>
                            <div className="space-y-3">
                                {categories.map((cat) => {
                                    const IconComponent = cat.icon;
                                    return (
                                        <button
                                            key={cat.value}
                                            onClick={() =>
                                                handleCategoryChange(cat.value)
                                            }
                                            className={`w-full flex items-center gap-3 px-4 py-4 rounded-xl font-medium transition-all transform hover:scale-105 ${
                                                selectedCategory === cat.value
                                                    ? `${cat.color} text-white shadow-xl`
                                                    : `${cat.bgLight} ${cat.textColor} hover:shadow-md border-2 border-transparent hover:border-gray-200`
                                            }`}
                                        >
                                            <IconComponent className="w-5 h-5 flex-shrink-0" />
                                            <span className="flex-1 text-left">
                                                {cat.label}
                                            </span>
                                            {cat.value !== "all" && (
                                                <span
                                                    className={`px-3 py-1 rounded-full text-xs font-bold ${
                                                        selectedCategory ===
                                                        cat.value
                                                            ? "bg-white bg-opacity-30 text-white"
                                                            : "bg-gray-100 text-gray-700 border border-gray-200"
                                                    }`}
                                                >
                                                    {stats[cat.value] || 0}
                                                </span>
                                            )}
                                        </button>
                                    );
                                })}
                            </div>

                            {/* Info box */}
                            <div className="mt-6 p-4 bg-forest-50 rounded-lg border-l-4 border-forest-500">
                                <p className="text-sm text-forest-800">
                                    <strong>Tips:</strong> Klik marker di peta
                                    untuk melihat detail lokasi
                                </p>
                            </div>
                        </div>
                    </div>

                    {/* Map Container */}
                    <div className="lg:col-span-3">
                        <div className="bg-white rounded-xl shadow-lg overflow-hidden">
                            <div className="p-4 bg-gradient-to-r from-forest-500 to-forest-600 text-white">
                                <h2 className="text-xl font-bold flex items-center gap-2">
                                    <MapPin className="w-5 h-5" />
                                    Peta Lokasi{" "}
                                    {selectedCategory !== "all"
                                        ? `- ${selectedCategory}`
                                        : ""}
                                </h2>
                                <p className="text-forest-100 text-sm mt-1">
                                    Menampilkan {filteredLocations.length} dari{" "}
                                    {stats.total} lokasi
                                </p>
                            </div>

                            {loading ? (
                                <div className="h-[600px] flex items-center justify-center">
                                    <div className="text-center">
                                        <div className="animate-spin rounded-full h-16 w-16 border-b-4 border-forest-600 mx-auto mb-4"></div>
                                        <p className="text-gray-600 font-medium">
                                            Memuat peta...
                                        </p>
                                    </div>
                                </div>
                            ) : (
                                <MapContainer
                                    center={[-5.295555, 105.446361]}
                                    zoom={13}
                                    style={{ height: "600px", width: "100%" }}
                                    className="z-0"
                                >
                                    <TileLayer
                                        attribution='&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                                        url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
                                    />

                                    {filteredLocations.map((location) => (
                                        <Marker
                                            key={location.id}
                                            position={[
                                                parseFloat(location.latitude),
                                                parseFloat(location.longitude),
                                            ]}
                                            icon={
                                                categoryIcons[location.category]
                                            }
                                        >
                                            <Popup maxWidth={300}>
                                                <div className="p-2 min-w-[250px]">
                                                    {location.image_url && (
                                                        <div className="mb-3 -mx-2 -mt-2">
                                                            <img
                                                                src={getGoogleDriveImageUrl(
                                                                    location.image_url
                                                                )}
                                                                alt={
                                                                    location.name
                                                                }
                                                                className="w-full h-40 object-cover rounded-t-lg"
                                                                onError={(
                                                                    e
                                                                ) => {
                                                                    e.target.style.display =
                                                                        "none";
                                                                }}
                                                            />
                                                        </div>
                                                    )}
                                                    <h3 className="font-bold text-lg mb-2 text-gray-800">
                                                        {location.name}
                                                    </h3>
                                                    <div className="mb-3">
                                                        <span
                                                            className={`inline-block px-3 py-1 rounded-full text-xs font-semibold ${
                                                                categoryBadgeColors[
                                                                    location
                                                                        .category
                                                                ] ||
                                                                "bg-gray-100 text-gray-800"
                                                            }`}
                                                        >
                                                            {location.category}
                                                        </span>
                                                    </div>
                                                    {location.address && (
                                                        <p className="text-gray-700 text-sm mb-2">
                                                            <strong>
                                                                📍 Alamat:
                                                            </strong>
                                                            <br />
                                                            {location.address}
                                                        </p>
                                                    )}
                                                    {location.description && (
                                                        <p className="text-gray-600 text-sm mb-2">
                                                            <strong>
                                                                ℹ️ Info:
                                                            </strong>
                                                            <br />
                                                            {
                                                                location.description
                                                            }
                                                        </p>
                                                    )}
                                                    <div className="mt-3 pt-2 border-t text-xs text-gray-500">
                                                        <strong>
                                                            Koordinat:
                                                        </strong>
                                                        <br />
                                                        {
                                                            location.latitude
                                                        }, {location.longitude}
                                                    </div>
                                                </div>
                                            </Popup>
                                        </Marker>
                                    ))}
                                </MapContainer>
                            )}
                        </div>
                    </div>
                </div>

                {/* Location List */}
                <div className="bg-white rounded-xl shadow-lg p-6">
                    <h2 className="text-2xl font-bold mb-6 text-gray-800 flex items-center gap-2">
                        <MapPin className="w-6 h-6 text-forest-600" />
                        Daftar Lokasi{" "}
                        {selectedCategory !== "all"
                            ? `- ${selectedCategory}`
                            : ""}
                    </h2>
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        {filteredLocations.map((location) => {
                            const category = categories.find(
                                (c) => c.value === location.category
                            );
                            const IconComponent = category?.icon || MapPin;
                            return (
                                <div
                                    key={location.id}
                                    className="border-2 border-gray-200 rounded-xl overflow-hidden hover:shadow-xl hover:border-forest-300 transition-all transform hover:-translate-y-1"
                                >
                                    {location.image_url && (
                                        <div className="w-full h-48 bg-gray-100">
                                            <img
                                                src={getGoogleDriveImageUrl(
                                                    location.image_url
                                                )}
                                                alt={location.name}
                                                className="w-full h-full object-cover"
                                                onError={(e) => {
                                                    e.target.parentElement.style.display =
                                                        "none";
                                                }}
                                            />
                                        </div>
                                    )}
                                    <div className="p-5">
                                        <div className="flex items-start gap-3 mb-3">
                                            <div
                                                className={`p-3 rounded-lg ${
                                                    category?.bgLight ||
                                                    "bg-gray-50"
                                                }`}
                                            >
                                                <IconComponent
                                                    className={`w-6 h-6 ${
                                                        category?.textColor ||
                                                        "text-gray-600"
                                                    }`}
                                                />
                                            </div>
                                            <div className="flex-1">
                                                <h3 className="font-bold text-lg text-gray-800 mb-1">
                                                    {location.name}
                                                </h3>
                                                <span
                                                    className={`inline-block px-3 py-1 rounded-full text-xs font-semibold ${
                                                        categoryBadgeColors[
                                                            location.category
                                                        ] ||
                                                        "bg-gray-100 text-gray-800"
                                                    }`}
                                                >
                                                    {location.category}
                                                </span>
                                            </div>
                                        </div>
                                        {location.address && (
                                            <p className="text-gray-700 text-sm mb-2 flex items-start gap-2">
                                                <MapPin className="w-4 h-4 text-gray-400 flex-shrink-0 mt-0.5" />
                                                <span>{location.address}</span>
                                            </p>
                                        )}
                                        {location.description && (
                                            <p className="text-gray-600 text-sm line-clamp-2">
                                                {location.description}
                                            </p>
                                        )}
                                    </div>
                                </div>
                            );
                        })}
                    </div>
                    {filteredLocations.length === 0 && (
                        <div className="text-center py-12">
                            <MapPin className="w-16 h-16 mx-auto mb-4 text-gray-300" />
                            <p className="text-gray-500 text-lg">
                                Tidak ada lokasi untuk kategori ini.
                            </p>
                        </div>
                    )}
                </div>
            </div>

            <Footer />
        </div>
    );
}
