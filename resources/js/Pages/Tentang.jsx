import React from "react";
import { Link } from "@inertiajs/react";
import Navbar from "../component/Navbar";
import HeroCustom from "../component/HeroCustom";
import Layout from "../Layouts/Layout";
import SEOHead from "../component/SeoHead";

const About = () => {
    const pageUrl = window.location.href;
    const originUrl = window.location.origin;
    // SEO Data
    const seoData = {
        title: "Tentang Desa Sindang Anom",
        description:
            "Informasi lengkap mengenai Desa Sindang Anom, Lampung Timur. Sejarah, visi misi, dan program-program pembangunan desa untuk kesejahteraan masyarakat.",
        // image: "https://sindanganomfarm.com/og-image-desa.jpg",
        url: pageUrl,
        type: "article",
        siteName: "Sindanganomfarm.com",
    };

    // Organization Schema
    const organizationSchema = {
        "@context": "https://schema.org",
        "@type": "GovernmentOrganization",
        name: "Desa Sindang Anom",
        url: pageUrl,
        description: "Pemerintahan Desa Sindang Anom, Lampung Timur",
        address: {
            "@type": "PostalAddress",
            addressLocality: "Sindang Anom",
            addressRegion: "Lampung Timur",
            addressCountry: "ID",
        },
    };

    // Article Schema
    const articleSchema = {
        "@context": "https://schema.org",
        "@type": "Article",
        headline: "Tentang Desa Sindang Anom",
        image: "https://sindanganomfarm.com/og-image-desa.jpg",
        publisher: {
            "@type": "Organization",
            name: "Desa Sindang Anom",
        },
        url: pageUrl,
        mainEntityOfPage: pageUrl,
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
                item: originUrl,
            },
            {
                "@type": "ListItem",
                position: 2,
                name: "Tentang",
                item: pageUrl,
            },
        ],
    };

    return (
        <>
            <SEOHead {...seoData} />
            <script type="application/ld+json">
                {JSON.stringify(organizationSchema)}
            </script>
            <script type="application/ld+json">
                {JSON.stringify(articleSchema)}
            </script>
            <script type="application/ld+json">
                {JSON.stringify(breadcrumbSchema)}
            </script>
            <Navbar />
            <HeroCustom name="Informasi Tentang Desa Sindang Anom" />
            <Layout>
                <div className="bg-white flex justify-center">
                    <div className="container px-5 md:px-[150px]">
                        {/* Title Section */}
                        <div className="text-justify mb-8 mt-[150px]">
                            <h1 className="text-2xl md:text-4xl font-bold text-green">
                                Tentang Desa Sindang Anom
                            </h1>
                        </div>

                        {/* Description Section */}
                        <div className="text-gray-700 text-semibold leading-relaxed">
                            <p className="text-justify">
                                Alamat Desa Sindang Anom : Jl. Pasar Desa
                                Sindang Anom, Kecamatan Sekampung Udik Kabupaten
                                Lampung Timur, Provinsi Lampung, Kode Pos 34183
                            </p>
                            <p className="text-justify mt-3">
                                Secara topografi, wilayah Desa Sindang Anom,
                                Kecamatan Sekampung Udik Kabupaten Lampung Timur
                                Propinsi Lampung merupakan dataran rendah dengan
                                ketinggian tempat 50 m dari permukaan laut. Desa
                                Sindang Anom memiliki struktur tanah yang subur
                                untuk pertanian dengan jenis tanah sebagian
                                besar merupakan Podosolik M.K. Secara
                                Klimatologis Desa Sindang Anom memiliki iklim
                                tropis yang mengalami dua musim yakni musim
                                kemarau dan musim penghujan. Suhu udara berkisar
                                antara 30o C sampai dengan 32oC. Penggunaan
                                lahan di Desa Sindang Anom secara luas merupakan
                                persawahan dan pekarangan/tegalan. Luas
                                penggunaan untuk lahan sawah seluas 422 Ha dan
                                luas lahan untuk digunakan Tegalan seluas 1138
                                Ha.
                            </p>
                            <p className="text-justify mt-3">
                                Berdasarkan data registrasi tahun 2022,
                                kelembagaan petani Desa Sindang Anom terdiri
                                dari 1 Gapoktan dengan 35 Kelompok tani. Semua
                                kelompok tani terklasifikasi sebagai kelompok
                                tani kelas pemula dan kelas lanjut.
                            </p>
                        </div>
                    </div>
                </div>
            </Layout>
        </>
    );
};

export default About;
