#!/usr/bin/env python3
"""
KK PDF Extractor - CLI Version
Dapat dipanggil dari PHP/Laravel untuk ekstraksi data KK
Usage: python extract_kk.py <pdf_file_path> [--output <output_path>] [--mapping]
"""

import sys
import json
import pypdf
import re
from pathlib import Path

TARGET_COLUMNS = [
    "alamat", "dusun", "rw", "rt", "nama", "no_kk", "nik", "sex", "tempatlahir", 
    "tanggallahir", "agama_id", "pendidikan_kk_id", "pendidikan_sedang_id", 
    "pekerjaan_id", "status_kawin", "kk_level", "warganegara_id", "ayah_nik", 
    "nama_ayah", "ibu_nik", "nama_ibu", "golongan_darah_id", "akta_lahir", 
    "dokumen_pasport", "tanggal_akhir_paspor", "dokumen_kitas", "akta_perkawinan", 
    "tanggalperkawinan", "akta_perceraian", "tanggalperceraian", "cacat_id", 
    "cara_kb_id", "hamil", "ktp_el", "status_rekam", "alamat_sekarang", 
    "status_dasar", "suku", "tag_id_card", "id_asuransi", "no_asuransi"
]

MASTER_MAPPINGS = {
    "sex": {
        "LAKI-LAKI": 1,
        "PEREMPUAN": 2
    },
    "agama_id": {
        "ISLAM": 1,
        "KRISTEN": 2,
        "KATHOLIK": 3,
        "HINDU": 4,
        "BUDHA": 5,
        "KHONGHUCU": 6,
        "KEPERCAYAAN TERHADAP TUHAN YME / LAINNYA": 7
    },
    "pendidikan_kk_id": {
        "TIDAK / BELUM SEKOLAH": 1,
        "BELUM TAMAT SD/SEDERAJAT": 2,
        "TAMAT SD / SEDERAJAT": 3,
        "SLTP/SEDERAJAT": 4,
        "SLTA / SEDERAJAT": 5,
        "DIPLOMA I / II": 6,
        "AKADEMI/ DIPLOMA III/S. MUDA": 7,
        "DIPLOMA IV/ STRATA I": 8,
        "STRATA II": 9,
        "STRATA III": 10
    },
    "pendidikan_sedang_id": {
        "BELUM MASUK TK/KELOMPOK BERMAIN": 1,
        "SEDANG TK/KELOMPOK BERMAIN": 2,
        "TIDAK PERNAH SEKOLAH": 3,
        "SEDANG SD/SEDERAJAT": 4,
        "TIDAK TAMAT SD/SEDERAJAT": 5,
        "SEDANG SLTP/SEDERAJAT": 6,
        "SEDANG SLTA/SEDERAJAT": 7,
        "SEDANG  D-1/SEDERAJAT": 8,
        "SEDANG D-2/SEDERAJAT": 9,
        "SEDANG D-3/SEDERAJAT": 10,
        "SEDANG  S-1/SEDERAJAT": 11,
        "SEDANG S-2/SEDERAJAT": 12,
        "SEDANG S-3/SEDERAJAT": 13,
        "SEDANG SLB A/SEDERAJAT": 14,
        "SEDANG SLB B/SEDERAJAT": 15,
        "SEDANG SLB C/SEDERAJAT": 16,
        "TIDAK DAPAT MEMBACA DAN MENULIS HURUF LATIN/ARAB": 17,
        "TIDAK SEDANG SEKOLAH": 18
    },
    "pekerjaan_id": {
        "BELUM/TIDAK BEKERJA": 1,
        "MENGURUS RUMAH TANGGA": 2,
        "PELAJAR/MAHASISWA": 3,
        "PENSIUNAN": 4,
        "PEGAWAI NEGERI SIPIL (PNS)": 5,
        "TENTARA NASIONAL INDONESIA (TNI)": 6,
        "KEPOLISIAN RI (POLRI)": 7,
        "PERDAGANGAN": 8,
        "PETANI/PEKEBUN": 9,
        "PETERNAK": 10,
        "NELAYAN/PERIKANAN": 11,
        "INDUSTRI": 12,
        "KONSTRUKSI": 13,
        "TRANSPORTASI": 14,
        "KARYAWAN SWASTA": 15,
        "KARYAWAN BUMN": 16,
        "KARYAWAN BUMD": 17,
        "KARYAWAN HONORER": 18,
        "BURUH HARIAN LEPAS": 19,
        "BURUH TANI/PERKEBUNAN": 20,
        "BURUH NELAYAN/PERIKANAN": 21,
        "BURUH PETERNAKAN": 22,
        "PEMBANTU RUMAH TANGGA": 23,
        "TUKANG CUKUR": 24,
        "TUKANG LISTRIK": 25,
        "TUKANG BATU": 26,
        "TUKANG KAYU": 27,
        "TUKANG SOL SEPATU": 28,
        "TUKANG LAS/PANDAI BESI": 29,
        "TUKANG JAHIT": 30,
        "TUKANG GIGI": 31,
        "PENATA RIAS": 32,
        "PENATA BUSANA": 33,
        "PENATA RAMBUT": 34,
        "MEKANIK": 35,
        "SENIMAN": 36,
        "TABIB": 37,
        "PARAJI": 38,
        "PERANCANG BUSANA": 39,
        "PENTERJEMAH": 40,
        "IMAM MASJID": 41,
        "PENDETA": 42,
        "PASTOR": 43,
        "WARTAWAN": 44,
        "USTADZ/MUBALIGH": 45,
        "JURU MASAK": 46,
        "PROMOTOR ACARA": 47,
        "ANGGOTA DPR-RI": 48,
        "ANGGOTA DPD": 49,
        "ANGGOTA BPK": 50,
        "PRESIDEN": 51,
        "WAKIL PRESIDEN": 52,
        "ANGGOTA MAHKAMAH KONSTITUSI": 53,
        "ANGGOTA KABINET KEMENTERIAN": 54,
        "DUTA BESAR": 55,
        "GUBERNUR": 56,
        "WAKIL GUBERNUR": 57,
        "BUPATI": 58,
        "WAKIL BUPATI": 59,
        "WALIKOTA": 60,
        "WAKIL WALIKOTA": 61,
        "ANGGOTA DPRD PROVINSI": 62,
        "ANGGOTA DPRD KABUPATEN/KOTA": 63,
        "DOSEN": 64,
        "GURU": 65,
        "PILOT": 66,
        "PENGACARA": 67,
        "NOTARIS": 68,
        "ARSITEK": 69,
        "AKUNTAN": 70,
        "KONSULTAN": 71,
        "DOKTER": 72,
        "BIDAN": 73,
        "PERAWAT": 74,
        "APOTEKER": 75,
        "PSIKIATER/PSIKOLOG": 76,
        "PENYIAR TELEVISI": 77,
        "PENYIAR RADIO": 78,
        "PELAUT": 79,
        "PENELITI": 80,
        "SOPIR": 81,
        "PIALANG": 82,
        "PARANORMAL": 83,
        "PEDAGANG": 84,
        "PERANGKAT DESA": 85,
        "KEPALA DESA": 86,
        "BIARAWATI": 87,
        "WIRASWASTA": 88,
        "LAINNYA": 89
    },
    "status_kawin": {
        "BELUM KAWIN": 1,
        "KAWIN TERCATAT": 2,
        "KAWIN TIDAK TERCATAT": 3,
        "KAWIN BELUM TERCATAT": 3,
        "CERAI HIDUP TERCATAT": 4,
        "CERAI HIDUP TIDAK TERCATAT": 5,
        "CERAI MATI": 6
    },
    "kk_level": {
        "KEPALA KELUARGA": 1,
        "SUAMI": 2,
        "ISTRI": 3,
        "ANAK": 4,
        "MENANTU": 5,
        "CUCU": 6,
        "ORANGTUA": 7,
        "MERTUA": 8,
        "FAMILI LAIN": 9,
        "PEMBANTU": 10,
        "LAINNYA": 11
    },
    "warganegara_id": {
        "WNI": 1,
        "WNA": 2,
        "DUA KEWARGANEGARAAN": 3
    },
    "golongan_darah_id": {
        "A": 1,
        "B": 2,
        "AB": 3,
        "O": 4,
        "A+": 5,
        "A-": 6,
        "B+": 7,
        "B-": 8,
        "AB+": 9,
        "AB-": 10,
        "O+": 11,
        "O-": 12,
        "TIDAK TAHU": 13
    },
    "cacat_id": {
        "CACAT FISIK": 1,
        "CACAT NETRA/BUTA": 2,
        "CACAT RUNGU/WICARA": 3,
        "CACAT MENTAL/JIWA": 4,
        "CACAT FISIK DAN MENTAL": 5,
        "CACAT LAINNYA": 6,
        "TIDAK CACAT": 7
    },
    "cara_kb_id": {
        "PIL": 1,
        "IUD": 2,
        "SUNTIK": 3,
        "KONDOM": 4,
        "SUSUK KB": 5,
        "STERILISASI WANITA": 6,
        "STERILISASI PRIA": 7,
        "LAINNYA": 8,
        "TIDAK MENGGUNAKAN": 1002
    },
    "hamil": {
        "HAMIL": 1,
        "TIDAK HAMIL": 2
    },
    "ktp_el": {
        "BELUM": 1,
        "KTP-EL": 2
    },
    "status_rekam": {
        "BELUM WAJIB": 1,
        "BELUM REKAM": 2,
        "SUDAH REKAM": 3,
        "CARD PRINTED": 4,
        "PRINT READY RECORD": 5,
        "CARD SHIPPED": 6,
        "SENT FOR CARD PRINTING": 7,
        "CARD ISSUED": 8
    },
    "status_dasar": {
        "HIDUP": 1,
        "MATI": 2,
        "PINDAH": 3,
        "HILANG": 4,
        "PERGI": 6,
        "TIDAK VALID": 9
    },
    "id_asuransi": {
        "TIDAK/BELUM PUNYA": 1,
        "BPJS PENERIMA BANTUAN IURAN": 2,
        "BPJS NON PENERIMA BANTUAN IURAN": 3,
        "BPJS BANTUAN DAERAH": 4,
        "ASURANSI LAINNYA": 99
    }
}

# Keywords
EDU_KEYWORDS = ["TIDAK", "BELUM", "TAMAT", "SD/SEDERAJAT", "SLTP/SEDERAJAT", "SLTA/SEDERAJAT", "DIPLOMA", "AKADEMI", "STRATA", "SEDERAJAT"]
RELATION_KEYWORDS = ["KEPALA KELUARGA", "SUAMI", "ISTRI", "ANAK", "MENANTU", "CUCU", "ORANGTUA", "MERTUA", "FAMILI LAIN", "PEMBANTU", "LAINNYA"]

def extract_kk_pure_pdf(pdf_path):
    """Extract KK data from PDF file"""
    reader = pypdf.PdfReader(pdf_path)
    text = reader.pages[0].extract_text()
    
    # 1. HEADER LOGIC
    def get_header_val(label, full_text):
        pattern = rf"{label}\s*[:：]\s*(.*?)(?=Nama|Alamat|RT/RW|Kode|Desa|Kecamatan|Kabupaten|Provinsi|\n|$)"
        match = re.search(pattern, full_text)
        return match.group(1).strip() if match else "-"

    alamat = get_header_val("Desa/Kelurahan", text)
    dusun = get_header_val("Alamat", text)
    rt_rw_str = get_header_val("RT/RW", text)
    
    rt, rw = "-", "-"
    if "/" in rt_rw_str:
        parts = [p.strip() for p in rt_rw_str.split("/")]
        if len(parts) >= 2:
            rt, rw = parts[0], parts[1]

    no_kk_match = re.search(r"No\.\s*(\d{16})", text)
    no_kk = no_kk_match.group(1) if no_kk_match else "-"
    
    header = {'alamat': alamat, 'dusun': dusun, 'rt': rt, 'rw': rw, 'no_kk': no_kk}

    # 2. TABLE PARSING
    lines = text.split('\n')
    table1_data, table2_data = {}, {}
    in_t1, in_t2 = False, False
    
    for line in lines:
        line = line.strip()
        if "(1)" in line and "(9)" in line: in_t1, in_t2 = True, False; continue
        if "(10)" in line and "(17)" in line: in_t1, in_t2 = False, True; continue
        
        parts = line.split()
        if not parts or not parts[0].isdigit(): continue
        row_idx = parts[0]
        
        if in_t1:  # Table 1: Biodata
            nik_cand = [p for p in parts if len(p) == 16 and p.isdigit()]
            date_cand = [p for p in parts if re.match(r"\d{2}-\d{2}-\d{4}", p)]
            if nik_cand and date_cand:
                nik, tgl_l = nik_cand[0], date_cand[0]
                n_pos, t_pos = parts.index(nik), parts.index(tgl_l)
                
                nama = " ".join(parts[1:n_pos])
                sex = parts[n_pos+1]
                tempat_l = " ".join(parts[n_pos+2:t_pos])
                
                if " ".join(parts[-2:]).upper() == "TIDAK TAHU":
                    gol_d, end_i = "TIDAK TAHU", -2
                else: gol_d, end_i = parts[-1], -1
                
                mid = parts[t_pos+2:end_i]
                edu, occ, is_occ = [], [], False
                for i, w in enumerate(mid):
                    if is_occ: occ.append(w)
                    else:
                        edu.append(w)
                        if "SEDERAJAT" in w or (i+1 < len(mid) and mid[i+1] not in EDU_KEYWORDS):
                            is_occ = True
                
                table1_data[row_idx] = {
                    'nama': nama, 'nik': nik, 'sex': sex, 'tempatlahir': tempat_l,
                    'tanggallahir': tgl_l, 'agama_id': parts[t_pos+1],
                    'pendidikan_kk_id': " ".join(edu), 'pekerjaan_id': " ".join(occ),
                    'golongan_darah_id': gol_d
                }
        
        elif in_t2:  # Table 2: Status & Parents
            cit_idx = -1
            for kw in ["WNI", "WNA"]:
                if kw in parts: cit_idx = parts.index(kw); break
            
            if cit_idx != -1:
                rel_parts, rel_start = [], cit_idx - 1
                for i in range(cit_idx-1, 0, -1):
                    rel_parts.insert(0, parts[i])
                    if " ".join(rel_parts) in RELATION_KEYWORDS:
                        rel_start = i; break
                
                stat_chunk = parts[1:rel_start]
                tgl_p, stat_parts = "-", []
                for p in stat_chunk:
                    if re.match(r"\d{2}-\d{2}-\d{4}", p): tgl_p = p
                    else: stat_parts.append(p)
                
                status_kawin = " ".join(stat_parts).replace("-", "").strip()
                
                parents = [p for p in parts[cit_idx+1:] if p != "-"]
                if len(parents) >= 2:
                    mid = len(parents) // 2
                    ayah, ibu = " ".join(parents[:mid]), " ".join(parents[mid:])
                else:
                    ayah = parents[0] if parents else "-"; ibu = "-"
                
                table2_data[row_idx] = {
                    'status_kawin': status_kawin, 'tanggalperkawinan': tgl_p,
                    'kk_level': " ".join(rel_parts), 'warganegara_id': parts[cit_idx],
                    'nama_ayah': ayah, 'nama_ibu': ibu
                }

    # 3. MERGE & Auto-link Parent NIKs
    merged_people = [{**header, **table1_data[idx], **table2_data.get(idx, {})} for idx in table1_data]
    
    name_to_nik = {p['nama'].upper().strip(): p['nik'] for p in merged_people if 'nama' in p and 'nik' in p}
    
    for p in merged_people:
        f_name = p.get('nama_ayah', '').upper().strip()
        p['ayah_nik'] = name_to_nik.get(f_name, "-")
        
        m_name = p.get('nama_ibu', '').upper().strip()
        p['ibu_nik'] = name_to_nik.get(m_name, "-")

    return merged_people

def apply_mapping(data_list, use_mapping=True):
    """Apply master mappings to convert text to IDs"""
    final_rows = []
    for p in data_list:
        row = {}
        for col in TARGET_COLUMNS:
            val = p.get(col, "-")
            
            if isinstance(val, str):
                temp_val = val.strip()
                if temp_val == "-":
                    clean_val = "-"
                else:
                    clean_val = re.sub(r'\s+', ' ', temp_val.rstrip('-').strip()).upper()
                clean_val = clean_val.replace(" / ", "/")
            else:
                clean_val = val

            if use_mapping and col in MASTER_MAPPINGS:
                mapping_dict = MASTER_MAPPINGS[col]
                normalized_map = {k.replace(" / ", "/").upper(): v for k, v in mapping_dict.items()}
                row[col] = normalized_map.get(clean_val, val)
            else:
                row[col] = val
                
        final_rows.append(row)
    
    return final_rows

def main():
    """Main CLI entry point"""
    if len(sys.argv) < 2:
        print(json.dumps({
            "success": False,
            "error": "Usage: python extract_kk.py <pdf_file_path> [--mapping]"
        }))
        sys.exit(1)
    
    pdf_path = sys.argv[1]
    use_mapping = "--mapping" in sys.argv
    
    # Validate file exists
    if not Path(pdf_path).exists():
        print(json.dumps({
            "success": False,
            "error": f"File not found: {pdf_path}"
        }))
        sys.exit(1)
    
    try:
        # Extract data
        extracted_data = extract_kk_pure_pdf(pdf_path)
        
        # Apply mapping if requested
        if use_mapping:
            extracted_data = apply_mapping(extracted_data, use_mapping=True)
        
        # Return JSON output
        print(json.dumps({
            "success": True,
            "data": extracted_data,
            "count": len(extracted_data)
        }, ensure_ascii=False, indent=2))
        
    except Exception as e:
        print(json.dumps({
            "success": False,
            "error": str(e)
        }))
        sys.exit(1)

if __name__ == "__main__":
    main()
