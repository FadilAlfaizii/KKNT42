import React from "react";
import { Link } from "@inertiajs/react";
import Navbar from "../component/Navbar";
import HeroCustom from "../component/HeroCustom";
import Layout from "../Layouts/Layout";
import { User } from "lucide-react";
import { getBaseUrl } from "../helpers/baseUrl";
import SEOHead from "../component/SeoHead";

const Ternak = ({ farms }) => {
    const pageUrl = window.location.href;

    // SEO Data
    const seoData = {
        title: "Daftar Peternakan Desa Sindang Anom",
        description: `Katalog lengkap ${farms.length} peternakan di Desa Sindang Anom, Lampung Timur. Tersedia informasi kandang, kapasitas, dan monitoring suhu kandang terintegrasi IoT.`,
        image: "https://sindanganomfarm.com/og-image.jpg",
        url: pageUrl,
        type: "website",
    };

    // CollectionPage Schema
    const collectionSchema = {
        "@context": "https://schema.org",
        "@type": "CollectionPage",
        "@id": pageUrl,
        name: "Daftar Peternakan Desa Sindang Anom",
        description: "Katalog peternakan di Desa Sindang Anom",
        url: pageUrl,
        numberOfItems: farms.length,
        hasPart: farms.map((farm) => ({
            "@type": "LocalBusiness",
            name: farm.name,
            description: farm.description,
            image: getBaseUrl(farm.image_url),
            address: {
                "@type": "PostalAddress",
                addressLocality: "Sindang Anom",
                addressRegion: "Lampung Timur",
                addressCountry: "ID",
            },
            owns: {
                "@type": "Place",
                name: `Kandang ${farm.name}`,
                containsPlace: {
                    "@type": "Place",
                    description: `Kandang dengan kapasitas ${farm.capacity} kepala`,
                },
            },
        })),
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
                item: pageUrl,
            },
        ],
    };

    // ItemList Schema
    const itemListSchema = {
        "@context": "https://schema.org",
        "@type": "ItemList",
        itemListElement: farms.map((farm, index) => ({
            "@type": "ListItem",
            position: index + 1,
            item: {
                "@type": "LocalBusiness",
                name: farm.name,
                description: farm.description,
                image: getBaseUrl(farm.image_url),
                url: `${window.location.origin}/detailternak/${farm.id}`,
            },
        })),
    };
    return (
        <>
            <SEOHead {...seoData} />
            <script type="application/ld+json">
                {JSON.stringify(collectionSchema)}
            </script>
            <script type="application/ld+json">
                {JSON.stringify(breadcrumbSchema)}
            </script>
            <script type="application/ld+json">
                {JSON.stringify(itemListSchema)}
            </script>
            <Navbar />
            <HeroCustom name="Peternakan di Desa Sindang Anom" />
            <Layout>
                <div className="bg-white flex justify-center mb-20">
                    <div className="container px-5 md:px-[50px]">
                        {/* Title Section */}
                        <div className="text-justify mb-[100px] mt-[100px]">
                            <h1 className="text-3xl md:text-3xl md:mt-0 mt-[130px] font-bold text-green text-center">
                                Daftar Peternakan
                            </h1>
                        </div>

                        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 px-5 md:px-[10px]">
                            {farms.map((farm) => (
                                <div
                                    key={farm.id}
                                    className="bg-white rounded-lg shadow-md overflow-hidden flex flex-col"
                                >
                                    {/* Image Placeholder */}
                                    <div className="bg-gray-200">
                                        <img
                                            src={`${getBaseUrl(
                                                farm.image_url
                                            )}`}
                                            alt={`perternakan sindang anom lampung timur milik ${farm.name}`}
                                            className="w-full h-auto object-cover"
                                        />
                                    </div>

                                    {/* Card Content */}
                                    <div className="p-4 flex flex-col flex-grow">
                                        {/* Author and Rating */}
                                        <div className="flex items-center justify-between mb-2">
                                            <div className="flex items-center text-sm text-white font-bold bg-green px-3 py-1 rounded-full">
                                                <span className="text-white rounded-full mr-1 flex items-center justify-center text-xs">
                                                    <User size={16} />
                                                </span>
                                                {farm.user
                                                    ? farm.user.fullname
                                                    : "Tidak ada data pengguna"}
                                            </div>
                                        </div>

                                        {/* Description and Capacity */}
                                        <div className="text-sm text-gray-600 mb-4 flex-grow">
                                            <p>
                                                {farm.description
                                                    ? farm.description.slice(
                                                          0,
                                                          50
                                                      ) +
                                                      (farm.description.length >
                                                      100
                                                          ? "..."
                                                          : "")
                                                    : "Tidak ada deskripsi"}
                                            </p>
                                            <p className="mt-1 font-bold text-HoverGreen">
                                                Kapasitas: {farm.capacity}{" "}
                                                Kepala
                                            </p>
                                        </div>

                                        {/* Detail Link */}
                                        <div className="mt-auto">
                                            <Link
                                                href={`/detailternak/${farm.id}`}
                                                className="text-green text-sm font-semibold hover:underline flex items-center"
                                            >
                                                Lihat Detail
                                            </Link>
                                        </div>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>
                </div>
            </Layout>
        </>
    );
};

export default Ternak;
