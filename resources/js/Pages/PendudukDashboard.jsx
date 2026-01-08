import Layout from '../Layouts/Layout';
import SEOHead from '../component/SeoHead';

export default function PendudukDashboard({
    total,
    laki_laki,
    perempuan,
    data,
}) {
    const seoData = {
        title: "Dashboard Penduduk Desa",
        description: "Ringkasan data kependudukan Desa Sindang Anom",
        url: window.location.href,
    };

    return (
        <>
            <SEOHead {...seoData} />
            <Layout>
                <div className="min-h-screen pt-24 px-4">
                    <div className="max-w-7xl mx-auto">
                        <h1 className="text-2xl font-bold mb-6 text-HoverGreen">
                            Dashboard Penduduk Desa
                        </h1>

                        <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <StatCard title="Total Penduduk" value={total} />
                            <StatCard title="Laki-laki" value={laki_laki} />
                            <StatCard title="Perempuan" value={perempuan} />
                        </div>
                    </div>
                </div>
            </Layout>
        </>
    );
}

function StatCard({ title, value}) {
    return (
        <div className="bg-white rounded-lg shadow p-4 text-center">
            <p className="text-gray-500">{title}</p>
            <p className="text-2xl font-bold text-HoverGreen mt-2">{value}</p>
        </div>
    );
}