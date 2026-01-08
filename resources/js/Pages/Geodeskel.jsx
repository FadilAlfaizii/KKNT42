import React from 'react';
import { MapContainer, TileLayer, Marker, Popup } from 'react-leaflet';
import 'leaflet/dist/leaflet.css';
import Layout from '../Layouts/Layout';
import SEOHead from '../component/SeoHead';
import L from 'leaflet';

// Fix for default marker icons in React-Leaflet
delete L.Icon.Default.prototype._getIconUrl;
L.Icon.Default.mergeOptions({
    iconRetinaUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-icon-2x.png',
    iconUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-icon.png',
    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
});

// Custom icon for schools
const schoolIcon = new L.Icon({
    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
    iconSize: [25, 41],
    iconAnchor: [12, 41],
    popupAnchor: [1, -34],
    shadowSize: [41, 41]
});


// Page Container

// Map Container
export default function Geodeskel({
    fasum,
    sekolah,
    tempah_ibadah,
    lokasi_usaha,
    fasilitas_kesehatan,
    pertanian,
    perikanan,
    perkebunan,
}) {
    const pageUrl = window.location.href;
    const seoData = {
        title: "Peta Desa Sindang Anom",
        description:
            "Peta interaktif Desa Sindang Anom menampilkan fasilitas umum, sekolah, tempat ibadah, dan lokasi usaha di desa.",
        image: "https://sindanganomfarm.com/og-image.jpg",
        url: pageUrl,
    };

    return (
        <>
            <SEOHead {...seoData} />
            <Layout>
                <div className="min-h-screen pt-24 pb-10 px-4">
                    <div className="max-w-7xl mx-auto">
                        <div className="text-center mb-6">
                            <h1 className="text-2xl md:text-3xl font-bold text-HoverGreen">
                                Peta Desa Sindang Anom
                            </h1>
                            <p className="text-gray-600 mt-2">
                                Jelajahi fasilitas dan lokasi penting di desa kami
                            </p>
                        </div>
                        
                        <div className="h-[600px] w-full rounded-lg overflow-hidden shadow-lg border-2 border-gray-200">
                            <MapContainer
                                center={[-5.3971, 105.2668]} 
                                zoom={13}
                                className="h-full w-full"
                            >
                                <TileLayer
                                    url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
                                    attribution='&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
                                />
                                
                                {/* Sekolah Markers */}
                                {sekolah && sekolah.map((school) => (
                                    <Marker
                                        key={school.id}
                                        position={[school.latitude, school.longitude]}
                                        icon={schoolIcon}
                                    >
                                        <Popup>
                                            <div className="p-2">
                                                <h3 className="font-bold text-lg mb-2">{school.name}</h3>
                                                {school.image_url && (
                                                    <img 
                                                        src={school.image_url} 
                                                        alt={school.name}
                                                        className="w-full h-32 object-cover rounded mb-2"
                                                    />
                                                )}
                                                <p className="text-sm text-gray-600 mb-1">
                                                    <strong>Alamat:</strong> {school.location}
                                                </p>
                                                {school.description && (
                                                    <p className="text-sm text-gray-600">
                                                        <strong>Deskripsi:</strong> {school.description}
                                                    </p>
                                                )}
                                            </div>
                                        </Popup>
                                    </Marker>
                                ))}
                            </MapContainer>
                        </div>
                    </div>
                </div>
            </Layout>
        </>
    );
}