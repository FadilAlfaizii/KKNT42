import React, { useState } from "react";
import Layout from "../Layouts/Layout";
import { Link } from "@inertiajs/react";
import SeoHead from "../component/SeoHead";
import {
    User,
    Thermometer,
    Droplets,
    Wind,
    Sun,
    CalendarIcon,
    ChevronDown,
} from "lucide-react";
import { Line } from "react-chartjs-2";
import {
    Chart as ChartJS,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Legend,
} from "chart.js";
import { getBaseUrl } from "../helpers/baseUrl";
import SEOHead from "../component/SeoHead";

// Register Chart.js components
ChartJS.register(
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Legend
);

const DetailTernak = ({ farm }) => {
    const [startDate, setStartDate] = useState("");
    const [endDate, setEndDate] = useState("");
    const pageUrl = window.location.href;

    // SEO Data
    const seoData = {
        title: `${farm.name} - Detail Kandang dan Monitoring IoT`,
        description: `Monitoring kandang ${
            farm.name
        } di Desa Sindang Anom, Kapasitas: ${farm.capacity} kepala. ${farm.description?.slice(
            0,
            100
        )}`,
        image: getBaseUrl(farm.image_url),
        url: pageUrl,
        type: "article",
        publishedAt: farm.created_at,
        modifiedAt: farm.updated_at,
        siteName: "Sindanganomfarm.com",
    };

    // LocalBusiness Schema
    const farmSchema = {
        "@context": "https://schema.org",
        "@type": "LocalBusiness",
        "@id": pageUrl,
        name: farm.name,
        description: farm.description,
        image: getBaseUrl(farm.image_url),
        telephone: farm.user?.phone_number,
        url: pageUrl,
        address: {
            "@type": "PostalAddress",
            streetAddress: farm.location,
            addressLocality: "Sindang Anom",
            addressRegion: "Lampung Timur",
            addressCountry: "ID",
        },
        geo: {
            "@type": "GeoCoordinates",
            latitude: farm.latitude,
            longitude: farm.longitude,
        },
        aggregateRating: {
            "@type": "AggregateRating",
            ratingValue: "4.5",
            reviewCount: "100",
        },
    };

    // BreadcrumbList Schema
    const breadcrumbSchema = {
        "@context": "https://schema.org",
        "@type": "BreadcrumbList",
        itemListElement: [
            {
                "@type": "ListItem",
                position: 1,
                name: "Beranda",
                item: window.location.origin,
            },
            {
                "@type": "ListItem",
                position: 2,
                name: "Peternakan",
                item: `${window.location.origin}/peternakan`,
            },
            {
                "@type": "ListItem",
                position: 3,
                name: farm.name,
                item: pageUrl,
            },
        ],
    };

    // IoT Data Schema
    const iotDataSchema = {
        "@context": "https://schema.org",
        "@type": "Dataset",
        name: `Data Sensor ${farm.name}`,
        description: `Data monitoring IoT untuk kandang ${farm.name}`,
    };

    const filterDataByDate = (data) => {
        if (!startDate || !endDate) return data;
        const start = new Date(startDate);
        const end = new Date(endDate);
        return data.filter((d) => {
            const date = new Date(d.datetime);
            return date >= start && date <= end;
        });
    };

    const formatChartData = (sensorData, sensorType) => {
        if (!sensorData || sensorData.length === 0) return null;

        const filteredData = filterDataByDate(sensorData);

        return {
            labels: filteredData.map((data) =>
                new Date(data.createdAt).toLocaleString()
            ),
            datasets: [
                {
                    label: sensorType,
                    data: filteredData.map((data) =>
                        parseFloat(data[sensorType.toLowerCase()])
                    ),
                    fill: false,
                    borderColor: getChartColor(sensorType),
                    backgroundColor: getChartColor(sensorType, 0.2),
                    tension: 0.4,
                    pointRadius: 4,
                },
            ],
        };
    };

    // Helper function for chart colors
    const getChartColor = (sensorType, alpha = 1) => {
        const colors = {
            Temperature: `rgba(255, 99, 132, ${alpha})`,
            Humidity: `rgba(54, 162, 235, ${alpha})`,
            Ammonia: `rgba(75, 192, 192, ${alpha})`,
            Light_intensity: `rgba(255, 206, 86, ${alpha})`,
        };
        return colors[sensorType] || `rgba(75, 192, 192, ${alpha})`;
    };

    // Update the LineChart component
    const LineChart = ({ sensorData, sensorType }) => {
        const chartData = formatChartData(sensorData, sensorType);

        if (!chartData) {
            return (
                <div className="flex justify-center items-center h-full">
                    <p className="text-gray-500">Data tidak tersedia</p>
                </div>
            );
        }

        const options = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: "top",
                },
                title: {
                    display: true,
                    text: `${sensorType} Over Time`,
                },
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function (value) {
                            const units = {
                                Temperature: "°C",
                                Humidity: "%",
                                Ammonia: "ppm",
                                Light_intensity: "lux",
                            };
                            return `${value}${units[sensorType] || ""}`;
                        },
                    },
                },
                x: {
                    ticks: {
                        maxRotation: 45,
                        minRotation: 45,
                    },
                },
            },
        };

        return (
            <div className="bg-white p-4 rounded-lg shadow-lg">
                <div className="h-[300px]">
                    <Line data={chartData} options={options} />
                </div>
            </div>
        );
    };

    const InfoItem = ({ label, value }) => (
        <div>
            <p className="text-green font-semibold">{label}</p>
            <p className="text-gray-800">{value}</p>
        </div>
    );

    const SensorItem = ({ icon, label, value }) => (
        <div className="flex items-center space-x-4 bg-gray-50 p-4 rounded-lg shadow">
            {icon}
            <div>
                <p className="text-gray-600 font-semibold">{label}</p>
                <p className="text-gray-800 font-bold">{value}</p>
            </div>
        </div>
    );

    const SensorAverage = (data, name_column) => {
        // Handle empty/null cases
        if (!data || data.length === 0) return "-";

        // filtered data

        // Calculate sum, converting string values to numbers
        const sum = data.reduce((acc, cur) => {
            const value = parseFloat(cur[name_column]);
            return acc + (isNaN(value) ? 0 : value);
        }, 0);

        // Calculate and format average to 2 decimal places
        return (sum / data.length).toFixed(2);
    };

    if (!farm) {
        return (
            <Layout>
                <div className="min-h-screen flex items-center justify-center">
                    <p>Loading...</p>
                </div>
            </Layout>
        );
    }
    return (
        <>
            <SEOHead {...seoData} />
            <script type="application/ld+json">
                {JSON.stringify(farmSchema)}
            </script>
            <script type="application/ld+json">
                {JSON.stringify(breadcrumbSchema)}
            </script>
            <script type="application/ld+json">
                {JSON.stringify(iotDataSchema)}
            </script>
            <Layout>
                <div className="bg-gray-100 min-h-screen p-6 md:p-12 mt-20">
                    <div className="max-w-6xl mx-auto reverse">
                        {/* Farm Information */}
                        <div className="bg-white rounded-lg mb-8">
                            <div className="p-6">
                                <div className="flex flex-col-reverse md:flex-row gap-8">
                                    <div className="md:w-1/2 order-1 md:order-none">
                                        <img
                                            src={
                                                `${getBaseUrl(
                                                    farm.image_url
                                                )}` ||
                                                "https://github.com/user-attachments/assets/24fb7c2d-fad0-42a2-b588-09f90ad43b7e"
                                            }
                                            alt="farm"
                                            className="rounded-lg w-full h-full object-cover shadow-lg"
                                        />
                                    </div>
                                    <div className="md:w-1/2 space-y-6">
                                        <h1 className="text-3xl font-bold text-gray-800">
                                            {farm.name}
                                        </h1>
                                        <div>
                                            <h2 className="text-xl font-semibold text-green mb-2">
                                                Deskripsi
                                            </h2>
                                            <p className="text-gray-600">
                                                {farm.description}
                                            </p>
                                        </div>
                                        <div className="grid grid-cols-2 gap-4">
                                            <InfoItem
                                                label="Panjang Kandang"
                                                value={`${farm.pan_length} Meter`}
                                            />
                                            <InfoItem
                                                label="Lebar Kandang"
                                                value={`${farm.pan_width} Meter`}
                                            />
                                            <InfoItem
                                                label="Tinggi Kandang"
                                                value={`${farm.pan_height} Meter`}
                                            />
                                            <InfoItem
                                                label="Kapasitas Kandang"
                                                value={`${farm.capacity} Kepala`}
                                            />
                                        </div>
                                        <InfoItem
                                            label="Lokasi Kandang"
                                            value={farm.location}
                                        />
                                        <div className="flex items-center space-x-1">
                                            <User
                                                className="text-green"
                                                size={24}
                                            />
                                            <span className="font-semibold">
                                                {farm.user?.fullname ||
                                                    "Tidak ada data user"}
                                            </span>
                                        </div>
                                        <a
                                            href={`https://wa.me/${farm.user?.phone_number}`}
                                            target="_blank"
                                            rel="noopener noreferrer"
                                        >
                                            <button className="w-full text-white bg-green hover:bg-HoverGreen mt-2 px-6 py-3 rounded-md font-semibold transition duration-300">
                                                Hubungi:{" "}
                                                {farm.user?.phone_number ||
                                                    "Tidak ada data phone number"}
                                            </button>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* Sensor Information */}
                        <div className="bg-white rounded-lg shadow-md p-6 mb-8">
                            <h2 className="text-xl font-bold text-green mb-4">
                                Informasi Sensor
                            </h2>
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <SensorItem
                                    icon={
                                        <Thermometer
                                            className="text-red-500"
                                            size={24}
                                        />
                                    }
                                    label="Suhu :"
                                    value={
                                        SensorAverage(
                                            farm.iot_sensors,
                                            "temperature"
                                        ) || "-"
                                    }
                                />
                                <SensorItem
                                    icon={
                                        <Droplets
                                            className="text-blue-500"
                                            size={24}
                                        />
                                    }
                                    label="Kelembapan :"
                                    value={
                                        SensorAverage(
                                            farm.iot_sensors,
                                            "humidity"
                                        ) || "-"
                                    }
                                />
                                <SensorItem
                                    icon={
                                        <Wind
                                            className="text-green-500"
                                            size={24}
                                        />
                                    }
                                    label="Amonia :"
                                    // value={farm.iot_sensors?.ammonia || "-"}
                                    value={
                                        SensorAverage(
                                            farm.iot_sensors,
                                            "ammonia"
                                        ) || "-"
                                    }
                                />
                                <SensorItem
                                    icon={
                                        <Sun
                                            className="text-yellow-500"
                                            size={24}
                                        />
                                    }
                                    label="Intensitas Cahaya :"
                                    // value={farm.iot_sensors?.light_intensity || "-"}
                                    value={
                                        SensorAverage(
                                            farm.iot_sensors,
                                            "light_intensity"
                                        ) || "-"
                                    }
                                />
                            </div>
                        </div>

                        {/* Date Filters */}
                        <div className="rounded-lg shadow-lg p-6 mb-8">
                            <h2 className="text-xl font-bold text-green mb-4 flex items-center">
                                <CalendarIcon className="mr-2 h-6 w-6 text-green" />
                                Filter Data
                            </h2>
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label className="block text-green font-semibold mb-2">
                                        Tanggal Mulai
                                    </label>
                                    <div className="relative">
                                        <input
                                            type="date"
                                            className="w-full border-green rounded-lg shadow-sm focus:border-green focus:ring focus:ring-green focus:ring-opacity-50 pl-10 pr-4 py-2 text-green"
                                            value={startDate}
                                            onChange={(e) =>
                                                setStartDate(e.target.value)
                                            }
                                        />
                                        <CalendarIcon className="absolute left-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-green" />
                                    </div>
                                </div>
                                <div>
                                    <label className="block text-green font-semibold mb-2">
                                        Tanggal Akhir
                                    </label>
                                    <div className="relative">
                                        <input
                                            type="date"
                                            className="w-full border-green rounded-lg shadow-sm focus:border-green focus:ring focus:ring-green focus:ring-opacity-50 pl-10 pr-4 py-2 text-green"
                                            value={endDate}
                                            onChange={(e) =>
                                                setEndDate(e.target.value)
                                            }
                                        />
                                        <CalendarIcon className="absolute left-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-green" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        {/* Sensor Charts Section */}
                        <div className="bg-white rounded-lg shadow-md p-6">
                            <div className="mb-6">
                                <h2 className="text-xl font-bold text-green mb-2">
                                    Grafik Sensor
                                </h2>
                                <p className="text-gray-600 text-sm">
                                    Monitoring data sensor dalam rentang waktu
                                </p>
                            </div>

                            {/* Charts Grid Layout */}
                            <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                {/* Temperature Chart */}
                                <div className="bg-gray-50 rounded-lg p-4">
                                    <div className="mb-4">
                                        <h3 className="font-semibold text-gray-700 flex items-center">
                                            <Thermometer className="w-5 h-5 mr-2 text-red-500" />
                                            Suhu
                                        </h3>
                                    </div>
                                    <div className="h-[300px]">
                                        <LineChart
                                            sensorData={farm.iot_sensors}
                                            sensorType="Temperature"
                                        />
                                    </div>
                                </div>

                                {/* Humidity Chart */}
                                <div className="bg-gray-50 rounded-lg p-4">
                                    <div className="mb-4">
                                        <h3 className="font-semibold text-gray-700 flex items-center">
                                            <Droplets className="w-5 h-5 mr-2 text-blue-500" />
                                            Kelembapan
                                        </h3>
                                    </div>
                                    <div className="h-[300px]">
                                        <LineChart
                                            sensorData={farm.iot_sensors}
                                            sensorType="Humidity"
                                        />
                                    </div>
                                </div>

                                {/* Ammonia Chart */}
                                <div className="bg-gray-50 rounded-lg p-4">
                                    <div className="mb-4">
                                        <h3 className="font-semibold text-gray-700 flex items-center">
                                            <Wind className="w-5 h-5 mr-2 text-green-500" />
                                            Amonia
                                        </h3>
                                    </div>
                                    <div className="h-[300px]">
                                        <LineChart
                                            sensorData={farm.iot_sensors}
                                            sensorType="Ammonia"
                                        />
                                    </div>
                                </div>

                                {/* Light Intensity Chart */}
                                <div className="bg-gray-50 rounded-lg p-4">
                                    <div className="mb-4">
                                        <h3 className="font-semibold text-gray-700 flex items-center">
                                            <Sun className="w-5 h-5 mr-2 text-yellow-500" />
                                            Intensitas Cahaya
                                        </h3>
                                    </div>
                                    <div className="h-[300px]">
                                        <LineChart
                                            sensorData={farm.iot_sensors}
                                            sensorType="Light_intensity"
                                        />
                                    </div>
                                </div>
                            </div>

                            {/* Optional: Loading State */}
                            {!farm.iot_sensors && (
                                <div className="flex justify-center items-center h-64">
                                    <div className="animate-spin rounded-full h-8 w-8 border-b-2 border-green"></div>
                                </div>
                            )}
                        </div>
                    </div>
                </div>
            </Layout>
        </>
    );
};

export default DetailTernak;
