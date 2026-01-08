import React from "react";
import Layout from "../Layouts/Layout";
import { Link } from "@inertiajs/react";
import { User, Star, MapPin, Phone } from "lucide-react";
import { getBaseUrl } from "../helpers/baseUrl";
import SEOHead from "../component/SeoHead";

const DetailProfil = ({ user }) => {
    const pageUrl = window.location.href;

    // SEO Data
    const seoData = {
        title: `Profil ${user.fullname}`,
        description: `Profil peternak ${
            user.fullname
        } di Desa Sindang Anom. Memiliki ${
            user.farms.length
        } peternakan aktif. ${user.bio?.slice(0, 120)}`,
        image: user.avatar,
        url: pageUrl,
        type: "profile",
        siteName: "Sindanganomfarm.com",
    };

    // Person Schema
    const personSchema = {
        "@context": "https://schema.org",
        "@type": "Person",
        "@id": pageUrl,
        name: user.fullname,
        description: user.bio,
        image: user.avatar,
        telephone: user.phone_number,
        url: pageUrl,
        owns: user.farms.map((farm) => ({
            "@type": "LocalBusiness",
            name: farm.name,
            description: farm.description,
            image: getBaseUrl(farm.image_url),
            address: {
                "@type": "PostalAddress",
                streetAddress: farm.location,
                addressLocality: "Sindang Anom",
                addressRegion: "Lampung Timur",
                addressCountry: "ID",
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
                name: "Profil",
                item: `${window.location.origin}/profil`,
            },
            {
                "@type": "ListItem",
                position: 3,
                name: user.fullname,
                item: pageUrl,
            },
        ],
    };
    return (
        <Layout>
            <SEOHead {...seoData} />
            <script type="application/ld+json">
                {JSON.stringify(personSchema)}
            </script>
            <script type="application/ld+json">
                {JSON.stringify(breadcrumbSchema)}
            </script>
            <div className="min-h-screen p-3 md:p-12 mt-10 px-2 md:px-[180px]">
                {/* Profil Section */}
                <section className="max-w-3xl mx-auto p-4">
                    <div className="flex flex-col md:flex-row md:gap-12 bg-white rounded-lg p-6">
                        {/* Profile Image */}
                        <img
                            src={user.avatar}
                            alt={`Avatar of ${user.fullname}`}
                            className="rounded-lg w-full md:w-1/2 md:h-auto object-cover"
                        />

                        {/* Profile Information */}
                        <div className="space-y-6 mt-6 md:mt-0 md:w-1/2">
                            <h1 className="text-2xl font-bold text-green">
                                {user.fullname}
                            </h1>

                            <div className="space-y-4">
                                {/* Bio Section */}
                                <div>
                                    <h2 className="text-green font-semibold text-lg">
                                        Bio
                                    </h2>
                                    <p className="mt-2 text-gray-700">
                                        {user.bio}
                                    </p>
                                </div>

                                {/* Jumlah Kandang Section */}
                                <div>
                                    <p className="text-green font-bold text-md">
                                        Jumlah Kandang:{" "}
                                        <span className="text-black">
                                            {user.farms.length}
                                        </span>
                                    </p>
                                </div>

                                {/* Hubungi Button Section */}
                                <Link
                                    href={`https://wa.me/${user.phone_number}`}
                                    target="_blank"
                                    rel="noopener noreferrer"
                                >
                                    <button className="flex justify-center text-sm text-white bg-green px-6 py-3 mt-6 rounded-md font-semibold w-full md:w-auto">
                                        Hubungi : {user.phone_number}
                                    </button>
                                </Link>
                            </div>
                        </div>
                    </div>
                </section>

                {/* Peternakan Section */}
                <section className="text-center mb-8 mt-20">
                    <h2 className="font-bold text-3xl text-green-600 mb-10">
                        Peternakan {user.fullname}
                    </h2>
                    <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        {user.farms.map((item) => (
                            <article
                                key={item.id}
                                className="bg-white rounded-lg shadow-md overflow-hidden flex flex-col transition duration-300 hover:shadow-lg"
                            >
                                {/* Farm Image */}
                                <div className="relative h-48">
                                    <img
                                        src={`${getBaseUrl(item.image_url)}`}
                                        alt={`Image of ${item.name}`}
                                        className="w-full h-full object-cover"
                                    />
                                    <div className="absolute top-0 left-0 m-2 bg-green text-white px-2 py-1 rounded-full flex items-center space-x-1">
                                        <User size={14} />
                                        <span className="text-xs font-semibold">
                                            {user.fullname}
                                        </span>
                                    </div>
                                </div>

                                {/* Card Content */}
                                <div className="p-4 flex flex-col flex-grow">
                                    <div className="flex items-center justify-between mb-2">
                                        <h3 className="text-lg font-semibold text-gray-800">
                                            {item.name}
                                        </h3>
                                    </div>

                                    {/* Description */}
                                    <p className="text-gray-600 text-sm text-left mb-4">
                                        {item.description
                                            ? item.description.slice(0, 100) +
                                              (item.description.length > 100
                                                  ? "..."
                                                  : "")
                                            : "Tidak ada deskripsi"}
                                    </p>

                                    {/* Capacity */}
                                    <p className="text-green text-sm text-left font-semibold mb-4">
                                        Kapasitas:{" "}
                                        <span className="text-gray-700">
                                            {item.capacity} Kepala
                                        </span>
                                    </p>

                                    {/* Detail Link */}
                                    <div className="mt-auto">
                                        <Link
                                            href={`/detailternak/${item.id}`}
                                            className="text-green text-sm font-semibold hover:underline flex items-center"
                                        >
                                            Lihat Detail
                                        </Link>
                                    </div>
                                </div>
                            </article>
                        ))}
                    </div>
                </section>
            </div>
        </Layout>
    );
};

export default DetailProfil;
