import { Link } from "@inertiajs/react";
import Image from "../../../public/images/imgAboutHome.png";


const TentangHome = () => {
  return (
    <>
    <div className="relative w-full md:mt-20 z-10">
      <div className="relative mx-auto bg-white rounded-t-xl md:rounded-t-[50px] overflow-hidden">
        <div className="flex flex-col-reverse md:flex-row mt-1 px-5 md:px-[150px] gap-6">
          <div className="md:w-1/2">
            <p className="text-green text-sm mt-4 font-semibold mb-2 text-center md:text-left">
              DESA SINDANG ANOM
            </p>
            <h2 className="text-3xl text-HoverGreen md:text-4xl font-bold text-gray-800 mb-6 text-center md:text-left">
              Tentang Desa Sindang Anom
            </h2>
            <p className="text-gray-600 text-justify">
            Desa Sindang Anom berkomitmen untuk menghadirkan berbagai program unggulan yang dirancang khusus untuk memfasilitasi dan mendukung pengembangan ide-ide kreatif serta inovatif di tingkat desa. Dengan fokus pada pemberdayaan masyarakat, kami berupaya menciptakan lingkungan yang kondusif bagi lahirnya solusi-solusi terobosan untuk meningkatkan kesejahteraan warga.
            </p>
            <div className="flex justify-center md:justify-start mt-5">
                <Link href={"/tentang"}>
                    <button className="bg-green hover:bg-HoverGreen text-white font-semibold px-6 py-2 rounded-md transition duration-200">
                        Lihat Selengkapnya
                    </button>
                </Link>
            </div>
          </div>
          <div className="md:w-1/2 flex justify-center md:justify-end mt-10">
            <img src={Image} alt="image" className="w-[50%] h-[85%]"/>
          </div>
        </div>
      </div>
    </div>
    
    </>
  );
};

export default TentangHome;
