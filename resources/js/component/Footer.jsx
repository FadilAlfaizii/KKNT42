import { Link } from '@inertiajs/react';
import Logo from '../../../public/images/logo-2.png'

const Footer = () => {
    return (
      <footer className="md:px-[120px] px-6 bg-green text-white py-6 items-center justify-center pb-10">
        <div className="container mx-auto flex flex-col md:flex-row justify-between px-4">
          <div className="mb-4">
            <h3 className="text-xl font-semibold">KKN Tematik Desa Sindang Anom</h3>
            <h1 className='text-sm'>Institut Teknologi Sumatera</h1>
            <img src={Logo} alt="logoFooter" className="h-16 md:h-20 mt-5" />
          </div>

          <div>
            <h3 className="text-xl md:mt-0 mt-3 font-semibold">Menu</h3>
            <Link href='/'><p className="mt-2">Beranda</p></Link>
            <Link href='/tentang'><p className="mt-2">Tentang</p></Link>
            <Link href='/peternakan'><p className="mt-2">Peternakan</p></Link>
            <Link href='/profil'><p className="mt-2">Profil Pemilik Ternak</p></Link>
          </div>

          <div>
            <h3 className="text-xl md:mt-0 mt-5 font-semibold">Kontak Kami</h3>
            <p className="mt-2">Jl. Pasar Desa Sindang Anom, Kecamatan Sekampung Udik, <br /> Kabupaten Lampung Timur, Provinsi Lampung, Kode Pos 34183</p>
            <p className="mt-2">+62 856-5876-3990</p>
          </div>
        </div>
      </footer>
    );
  };
  
  export default Footer;
  