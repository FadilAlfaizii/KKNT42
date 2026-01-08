import { Link } from "@inertiajs/react";
import React from "react";
import { getBaseUrl } from "../helpers/baseUrl";

const TernakHome = ({ farms }) => {
    console.log(farms);

    return (
        <div className="bg-gray-100 min-h-screen py-10 mt-20">
            {/* Header */}
            <div className="text-center mb-[60px]">
                <h1 className="text-2xl font-bold text-gray-800">
                    Profil Peternakan Di Desa Sindang Anom
                </h1>
                <p clpssName="text-green-500 text-sm underline">
                    Lihat Semua Peternakan disini
                </p>
            </div>

            {/* Card Grid */}
            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 px-5 md:px-[100px]">
                {farms.map((item) => (
                    <div
                        key={item.id}
                        className="bg-white rounded-lg shadow-md overflow-hidden"
                    >
                        {/* Image Placeholder */}
                        <div className="bg-gray-200">
                            <img
                                src={`${getBaseUrl(item.image_url)}`}
                                alt={`perternakan sindang anom lampung timur milik ${item.name}`}
                                className="w-full h-auto object-cover"
                            />
                        </div>

                        {/* Card Content */}
                        <div className="p-4">
                            {/* Author and Rating */}
                            <div className="flex items-center justify-between mb-2">
                                <div className="flex items-center text-sm text-green font-bold">
                                    <span className="text-white rounded-full w-6 h-6 flex items-center justify-center text-xs mr-1">
                                        👤
                                    </span>
                                    {item.name}
                                </div>
                            </div>

                            {/* Title */}
                            <h2 className="text-gray-800 text-sm font-semibold mb-4">
                                {item.description}
                            </h2>

                            {/* Detail Link */}
                            <Link
                                href="/detailternak"
                                // href={`/ternak/${item.id}`}
                                className="text-green text-sm font-semibold hover:underline flex items-center"
                            >
                                Lihat Detail
                            </Link>
                        </div>
                    </div>
                ))}
            </div>
            <div className="flex justify-center md:justify-center mt-10">
                <Link href={"/peternakan"}>
                    <button className="bg-green hover:bg-HoverGreen text-white font-semibold px-6 py-2 rounded-md transition duration-200">
                        Lihat Selengkapnya
                    </button>
                </Link>
            </div>
        </div>
    );
};

export default TernakHome;
