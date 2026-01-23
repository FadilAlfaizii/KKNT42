import React, { useState } from 'react';
import { MapContainer, TileLayer, Marker, Popup, Circle } from 'react-leaflet';
import { MapPin, Navigation, Maximize2 } from 'lucide-react';
import 'leaflet/dist/leaflet.css';
import L from 'leaflet';

// Fix marker icon issue with Webpack
delete L.Icon.Default.prototype._getIconUrl;
L.Icon.Default.mergeOptions({
    iconRetinaUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-icon-2x.png',
    iconUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-icon.png',
    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
});

const VillageMap = () => {
    const [isFullscreen, setIsFullscreen] = useState(false);
    
    // Koordinat Desa Sindang Anom (contoh - sesuaikan dengan koordinat sebenarnya)
    const desaCenter = [-6.234567, 106.789012]; // Latitude, Longitude
    const zoom = 15;

    // Data titik penting di desa
    const landmarks = [
        { id: 1, name: 'Kantor Desa', position: [-6.234567, 106.789012], type: 'office' },
        { id: 2, name: 'Masjid Al-Ikhlas', position: [-6.235000, 106.788500], type: 'worship' },
        { id: 3, name: 'SDN 01', position: [-6.234000, 106.789500], type: 'education' },
        { id: 4, name: 'Puskesmas Pembantu', position: [-6.235500, 106.789800], type: 'health' },
        { id: 5, name: 'Balai RW 01', position: [-6.233800, 106.788800], type: 'community' },
    ];

    // Custom icons untuk setiap tipe landmark
    const createCustomIcon = (type) => {
        const colors = {
            office: '#2563eb', // primary
            worship: '#16a34a', // nature
            education: '#0284c7', // accent
            health: '#dc2626', // red
            community: '#7c3aed', // purple
        };

        return L.divIcon({
            className: 'custom-marker',
            html: `
                <div style="
                    background-color: ${colors[type] || '#2563eb'};
                    width: 32px;
                    height: 32px;
                    border-radius: 50% 50% 50% 0;
                    transform: rotate(-45deg);
                    border: 3px solid white;
                    box-shadow: 0 4px 6px rgba(0,0,0,0.2);
                ">
                    <div style="
                        transform: rotate(45deg);
                        width: 100%;
                        height: 100%;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        color: white;
                        font-size: 16px;
                    ">📍</div>
                </div>
            `,
            iconSize: [32, 32],
            iconAnchor: [16, 32],
            popupAnchor: [0, -32],
        });
    };

    const toggleFullscreen = () => {
        setIsFullscreen(!isFullscreen);
    };

    return (
        <div className={`${isFullscreen ? 'fixed inset-0 z-50' : 'relative'} bg-white rounded-2xl border border-gray-200 shadow-lg overflow-hidden`}>
            {/* Header */}
            <div className="absolute top-0 left-0 right-0 z-10 bg-white/95 backdrop-blur-sm border-b border-gray-200 p-4">
                <div className="flex items-center justify-between">
                    <div className="flex items-center space-x-3">
                        <div className="p-2 bg-primary-50 rounded-lg">
                            <MapPin className="w-5 h-5 text-primary-600" />
                        </div>
                        <div>
                            <h3 className="text-lg font-bold text-gray-900">Peta Wilayah Desa</h3>
                            <p className="text-sm text-gray-500">Desa Sindang Anom, Kec. Sekampung Udik</p>
                        </div>
                    </div>
                    <button
                        onClick={toggleFullscreen}
                        className="p-2 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors"
                        title={isFullscreen ? 'Exit Fullscreen' : 'Fullscreen'}
                    >
                        <Maximize2 className="w-5 h-5 text-gray-700" />
                    </button>
                </div>
            </div>

            {/* Legend */}
            <div className="absolute bottom-4 left-4 z-10 bg-white/95 backdrop-blur-sm rounded-xl p-4 shadow-lg max-w-xs">
                <h4 className="font-semibold text-sm text-gray-900 mb-2">Legenda:</h4>
                <div className="space-y-2 text-xs">
                    <div className="flex items-center space-x-2">
                        <div className="w-3 h-3 rounded-full bg-primary-600"></div>
                        <span className="text-gray-700">Kantor Desa</span>
                    </div>
                    <div className="flex items-center space-x-2">
                        <div className="w-3 h-3 rounded-full bg-nature-600"></div>
                        <span className="text-gray-700">Tempat Ibadah</span>
                    </div>
                    <div className="flex items-center space-x-2">
                        <div className="w-3 h-3 rounded-full bg-accent-600"></div>
                        <span className="text-gray-700">Pendidikan</span>
                    </div>
                    <div className="flex items-center space-x-2">
                        <div className="w-3 h-3 rounded-full bg-red-600"></div>
                        <span className="text-gray-700">Kesehatan</span>
                    </div>
                    <div className="flex items-center space-x-2">
                        <div className="w-3 h-3 rounded-full bg-purple-600"></div>
                        <span className="text-gray-700">Fasilitas Umum</span>
                    </div>
                </div>
            </div>

            {/* Map */}
            <div className={isFullscreen ? 'h-screen' : 'h-[500px]'} style={{ paddingTop: '68px' }}>
                <MapContainer
                    center={desaCenter}
                    zoom={zoom}
                    style={{ height: '100%', width: '100%' }}
                    zoomControl={true}
                >
                    <TileLayer
                        attribution='&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                        url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
                    />
                    
                    {/* Circle untuk menandai area desa */}
                    <Circle
                        center={desaCenter}
                        radius={500}
                        pathOptions={{
                            color: '#2563eb',
                            fillColor: '#3b82f6',
                            fillOpacity: 0.1,
                            weight: 2,
                        }}
                    />

                    {/* Markers untuk landmarks */}
                    {landmarks.map((landmark) => (
                        <Marker
                            key={landmark.id}
                            position={landmark.position}
                            icon={createCustomIcon(landmark.type)}
                        >
                            <Popup>
                                <div className="p-2">
                                    <h4 className="font-bold text-sm text-gray-900">{landmark.name}</h4>
                                    <p className="text-xs text-gray-600 mt-1">
                                        📍 {landmark.position[0].toFixed(6)}, {landmark.position[1].toFixed(6)}
                                    </p>
                                    <button className="mt-2 text-xs bg-primary-600 text-white px-3 py-1 rounded-lg hover:bg-primary-700 transition-colors">
                                        Lihat Detail
                                    </button>
                                </div>
                            </Popup>
                        </Marker>
                    ))}
                </MapContainer>
            </div>

            {/* Navigation hint */}
            <div className="absolute bottom-4 right-4 z-10 bg-white/95 backdrop-blur-sm rounded-lg p-3 shadow-lg">
                <div className="flex items-center space-x-2 text-xs text-gray-600">
                    <Navigation className="w-4 h-4" />
                    <span>Gunakan mouse untuk navigasi peta</span>
                </div>
            </div>
        </div>
    );
};

export default VillageMap;
