<?php

namespace App\Http\Controllers;

use App\Models\Struk;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\ImageManager;


class StrukController extends Controller
{
    protected $imageManager;

    public function __construct()
    {
        $this->imageManager = ImageManager::withDriver(new GdDriver());
    }

    // Form input
    public function c_geutanyo_cell()
    {
        return view('struk.geutanyo_cell');
    }

    // Simpan & generate struk
    public function s_geutanyo_cell(Request $request)
    {
        $validated = $request->validate([
            'no_meter'      => 'required|string',
            'tgl_bayar'     => 'required|date', // hanya tanggal dipilih user
            'id_pelanggan'  => 'required|string',
            'nama'          => 'required|string',
            'tarif_daya'    => 'required|string',
            'rp_bayar'      => 'required|numeric',
        ]);

        // Acak jam:menit:detik antara pukul 09:00:00 s/d 15:00:00
        $randomTime = Carbon::createFromTime(
            rand(9, 15),       // Jam: antara 09 dan 15
            rand(0, 59),       // Menit
            rand(0, 59)        // Detik
        );

        // Gabungkan dengan tanggal dari form
        $tanggalBayar = Carbon::parse($validated['tgl_bayar'])->setTimeFrom($randomTime);
        $validated['tgl_bayar'] = $tanggalBayar;

        // Lanjut seperti biasa:
        $validated['status'] = 'Berhasil';
        $validated['id_transaksi'] = $this->generateIdTransaksi();
        $validated['no_reff'] = $this->generateNoReff();
        $validated['token'] = $this->generateToken();

        $struk = (object) $validated;

        // Load template & edit gambar
        $image = $this->imageManager->read(public_path('template/geutanyo.png'));
        // Pisahkan no_reff jadi dua baris
        $reffLine1 = substr($struk->no_reff, 0, 20);
        $reffLine2 = substr($struk->no_reff, 20);

        // Tampilkan teks (ukuran font 12 dan Regular)
        $image->text($reffLine1, 260, 570, fn($font) => $this->fontGeutanyo($font, 'Regular', 14));
        $image->text($reffLine2, 260, 590, fn($font) => $this->fontGeutanyo($font, 'Regular', 14));

        // Tambahkan teks ke posisi yang sesuai
        // $image->text($struk->status, 290, 270, fn($font) => $this->fontPayFazz($font, 'Regular', 12));
        $image->text($struk->tgl_bayar->format('d-m-Y H:i:s'), 260, 310, fn($font) => $this->fontGeutanyo($font, 'Regular', 14));
        $image->text($struk->id_transaksi, 260, 348, fn($font) => $this->fontGeutanyo($font, 'Regular', 14));

        $image->text($struk->no_meter, 260, 425, fn($font) => $this->fontGeutanyo($font, 'Regular', 14));
        $image->text($struk->id_pelanggan, 260, 461, fn($font) => $this->fontGeutanyo($font, 'Regular', 14));
        $image->text($struk->nama, 260, 497, fn($font) => $this->fontGeutanyo($font, 'Regular', 14));
        $image->text($struk->tarif_daya, 260, 534, fn($font) => $this->fontGeutanyo($font, 'Regular', 14));

        // Nomor Referensi split 2 baris
        $image->text($reffLine1, 260, 570, fn($font) => $this->fontGeutanyo($font, 'Regular', 14));
        $image->text($reffLine2, 260, 590, fn($font) => $this->fontGeutanyo($font, 'Regular', 14));

        // rp_bayar bold 12
        $image->text('Rp' . number_format($struk->rp_bayar, 0, ',', '.'), 260, 683, fn($font) => $this->fontGeutanyo($font, 'SemiBold', 14));

        // Token, bold ukuran 19
        $image->text($struk->token, 87, 777, fn($font) => $this->fontGeutanyo($font, 'SemiBold', 26));

        // Simpan hasil
        $filename = strtoupper($struk->nama) . '_' . $struk->tgl_bayar->format('d_m_Y') . '.png';
        $outputPath = public_path('generated/' . $filename);
        $image->save($outputPath);

        return response()->download($outputPath)->deleteFileAfterSend();
    }

    public function createDanaPay()
    {
        return view('struk.dana_pay');
    }

    public function storeDanaPay(Request $request)
    {
        $validated = $request->validate([
            'tgl' => 'required|date',
            'no_meter' => 'required|string',
            'nama' => 'required|string',
            'rp_bayar' => 'required|numeric',
            'kwh' => 'required|numeric'
        ]);

        // Format data
        $nameTanggal = Carbon::parse($validated['tgl'])->format('d_M_Y');
        $tanggal = Carbon::parse($validated['tgl'])->setTime(rand(9, 15), rand(0, 59), rand(0, 59));
        $token = $this->generateToken();
        $groupId = '••••' . rand(1000, 9999);
        $idTrans = Carbon::now()->format('Ymd') . strtoupper(substr(str_shuffle('ABCDEFGH1234567890'), 0, 26));
        $merchantId = '••••' . rand(1000, 9999);
        $jumlahText = number_format($validated['rp_bayar'], 0, ',', '.');

        // Potong ID Transaksi
        $idTransLine1 = substr($idTrans, 0, 19);
        $idTransLine2 = substr($idTrans, 19);

        $imageManager = ImageManager::withDriver(new GdDriver());
        $image = $imageManager->read(public_path('template/dana_pay.png'));

        // Text Drawing
        $image->text($tanggal->translatedFormat('d M Y • H:i'), 100, 521, fn($font) => $this->fontDana($font, 'Roboto', 32, false,'#5a5a5a'));
        $image->text('DANA ID 0852••••2902', 670, 521, fn($font) => $this->fontDana($font, 'Roboto', 30, false, '#5a5a5a'));
        $image->text('50 Rb ' . $validated['no_meter'] . ' ' . strtoupper($validated['nama']), 108, 651, fn($font) => $this->fontDana($font, 'Roboto', 28, true));
        $image->text($token, 125, 850, fn($font) => $this->fontDana($font, 'Roboto', 50, true, '#FFF'));
        $image->text('Jumlah kWH : ' . $validated['kwh'], 137, 896, fn($font) => $this->fontDana($font, 'OpenSans', 24, true, '#FFF'));
        $image->text('Rp' . $jumlahText, 669, 1134, fn($font) => $this->fontDana($font, 'Roboto', 46, false, '#000000'));
        $image->text($groupId, 830, 1440, fn($font) => $this->fontDana($font, 'OpenSans', 34, false,'#676767'));
        $image->text($idTransLine1, 587, 1565, fn($font) => $this->fontDana($font, 'OpenSans', 34, false, '#676767'));
        $image->text($idTransLine2, 821, 1616, fn($font) => $this->fontDana($font, 'OpenSans', 34, false, '#676767'));
        $image->text($merchantId, 830, 1690, fn($font) => $this->fontDana($font, 'OpenSans', 34, false, '#676767'));

        // // Save
        $filename = $validated['nama'] . '_' . $nameTanggal . '.png';
        $outputPath = public_path('generated/' . $filename);
        $image->save($outputPath);

        return response()->download($outputPath)->deleteFileAfterSend();
    }

    public function createPayfazz()
    {
        return view('struk.pay_fazz');
    }

    public function storePayfazz(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'total' => 'required|numeric',
            'no_meter' => 'required|string',
            'nama' => 'required|string',
            'no_pelanggan' => 'required|string',
            'tarif_daya' => 'required|string',
            'kwh' => 'required|numeric',
            'rp_bayar' => 'required|numeric',
            'ppj' => 'required|numeric',
            'rp_stroom' => 'required|numeric',
            'admin_bank' => 'required|numeric',
        ]);

        // Tanggal + waktu acak 09:00 - 14:59 WIB
        $jamAcak = rand(9, 14);
        $menitAcak = rand(0, 59);
        $tanggal = Carbon::parse($validated['tanggal'])->setTime($jamAcak, $menitAcak);

        // Format hari (Indonesia)
        $hari = Carbon::parse($validated['tanggal'])->locale('id')->isoFormat('dddd');

        // Generate data
        $orderId = '#' . strtoupper(Str::random(10));
        $noReff1 = strtoupper(Str::random(15));
        $noReff2 = strtoupper(Str::random(5));
        $token = $this->generateToken(); // gunakan function yang sama

        // Tambahan default
        $materai = 0;
        $ppn = 0;
        $angsuran = 0;

        // Gambar template
        $image = $this->imageManager->read(public_path('template/payfazz.png'));

        // Tulis teks ke gambar
        $image->text(strtoupper($hari) . ', ' . $tanggal->format('d M Y'), 60, 130, fn($font) => $this->fontPayFazz($font, 'Regular', 36));
        $image->text($tanggal->format('H:i') . ' WIB', 680, 130, fn($font) => $this->fontPayFazz($font, 'Regular', 36));

        $image->text($orderId, 260, 182, fn($font) => $this->fontPayFazz($font, 'Regular', 36));
        $image->text(number_format($validated['total'], 0, ',', '.') . ' - ' . $validated['no_pelanggan'], 135, 300, fn($font) => $this->fontPayFazz($font));

        $image->text('Rp ' . number_format($validated['total'], 0, ',', '.'), 680, 350, fn($font) => $this->fontPayFazz($font));
        $image->text('Rp ' . number_format($validated['total'], 0, ',', '.'), 680, 407, fn($font) => $this->fontPayFazz($font));

        // // Referensi
        $image->text($noReff1, 440, 540, fn($font) => $this->fontPayFazz($font));
        $image->text($noReff2, 440, 590, fn($font) => $this->fontPayFazz($font));

        $image->text($validated['no_meter'], 440, 651, fn($font) => $this->fontPayFazz($font));
        $image->text($validated['no_pelanggan'], 440, 708, fn($font) => $this->fontPayFazz($font));
        $image->text($validated['nama'], 440, 765, fn($font) => $this->fontPayFazz($font));

        $image->text($validated['tarif_daya'], 440, 822, fn($font) => $this->fontPayFazz($font));
        $image->text($token, 330, 877, fn($font) => $this->fontPayFazz($font));
        $image->text($validated['kwh'], 440, 936, fn($font) => $this->fontPayFazz($font));
        $image->text('Rp' .number_format($validated['rp_bayar'], 0, ',', '.'), 440, 991, fn($font) => $this->fontPayFazz($font));
        $image->text('Rp' . $materai, 440, 1047, fn($font) => $this->fontPayFazz($font));
        $image->text('Rp' . $ppn, 440, 1104, fn($font) => $this->fontPayFazz($font));
        $image->text('Rp' . number_format($validated['ppj'], 0, ',', '.'), 440, 1163, fn($font) => $this->fontPayFazz($font));
        $image->text('Rp' . $angsuran, 440, 1218, fn($font) => $this->fontPayFazz($font));
        $image->text('Rp' . number_format($validated['rp_stroom'], 0, ',', '.'), 440, 1274, fn($font) => $this->fontPayFazz($font));
        $image->text('Rp' . number_format($validated['admin_bank'], 0, ',', '.'), 440, 1330, fn($font) => $this->fontPayFazz($font));

        // Informasi akhir
        $image->text('Informasi Hubungi Ca', 440, 1388, fn($font) => $this->fontPayFazz($font, 'Regular', 36));
        $image->text('ll Center 123 Atau', 440, 1444, fn($font) => $this->fontPayFazz($font, 'Regular', 36));
        $image->text('hubungi PLN Terdekat', 440, 1501, fn($font) => $this->fontPayFazz($font, 'Regular', 36));

        // Simpan gambar
        $filename = $validated['nama'] . '_'. $tanggal->format('d_M_Y') . '.png';
        $outputPath = public_path('generated/' . $filename);
        $image->save($outputPath);

        return response()->download($outputPath)->deleteFileAfterSend();
    }

    public function createGoldCell()
    {
        return view('struk.gold_cell');
    }

    public function formAsaCellular()
    {
        return view('struk.asa_celullar');
    }

    public function storeGoldCell(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'idpel' => 'required',
            'nama' => 'required',
            'tarif_daya' => 'required',
            'nominal' => 'required|numeric',
            'rp_token' => 'required|numeric',
            'kwh' => 'required',
            'total_bayar' => 'required|numeric',
            'jasa_outlet' => 'required|numeric',
            'total_final' => 'required|numeric',
        ]);
        [$tokenLine1, $tokenLine2] = $this->generateTokenLines(); // akan menghasilkan array berisi 4 baris seperti "1234-5678"
        // dd($tokenLine1, $tokenLine2);
        $imageManager = ImageManager::withDriver(new GdDriver());
        $image = $imageManager->read(public_path('template/gold_cell.png'));

        // Waktu random antara pukul 08:00 dan 20:00
        $waktu = Carbon::parse($validated['tanggal'])
            ->setTime(rand(8, 20), rand(0, 59), rand(0, 59))
            ->format('d/m/Y H:i:s');

        // Generate token 4 baris
        $waktuName = Carbon::parse($validated['tanggal'])->format('d_m_Y');

        // Posisi Y untuk setiap elemen
        $image->text($waktu, 40, 100, fn($font) => $this->fontPayFazz($font, 'Regular', 32));
        $image->text($validated['idpel'], 445, 520, fn($font) => $this->fontPayFazz($font, 'Regular', 48));
        $image->text($validated['nama'], 445, 590, fn($font) => $this->fontPayFazz($font, 'Regular', 48));
        $image->text($validated['tarif_daya'], 445, 660, fn($font) => $this->fontPayFazz($font, 'Regular', 48));
        $image->text('RP. ' . number_format($validated['nominal'], 2, ',', '.'), 445, 730, fn($font) => $this->fontPayFazz($font, 'Regular', 48));
        $image->text('RP ' . number_format($validated['rp_token'], 2, ',', '.'), 445, 800, fn($font) => $this->fontPayFazz($font, 'Regular', 48));
        $image->text('RP. 0,00/0,00', 445, 870, fn($font) => $this->fontPayFazz($font, 'Regular', 48));
        $image->text('RP. ' . number_format($validated['nominal'], 2, ',', '.'), 445, 940, fn($font) => $this->fontPayFazz($font, 'Regular', 48));
        $image->text($validated['kwh'], 445, 1010, fn($font) => $this->fontPayFazz($font, 'Regular', 48));
        $image->text('RP. 0,00', 445, 1080, fn($font) => $this->fontPayFazz($font, 'Regular', 48));
        $image->text('RP ' . number_format($validated['total_bayar'], 2, ',', '.'), 445, 1150, fn($font) => $this->fontPayFazz($font, 'Regular', 48));
        $image->text('RP ' . number_format($validated['jasa_outlet'], 2, ',', '.'), 445, 1290, fn($font) => $this->fontPayFazz($font, 'Regular', 48));
        $image->text('RP ' . number_format($validated['total_final'], 2, ',', '.'), 445, 1360, fn($font) => $this->fontPayFazz($font, 'Regular', 48));

        // // Token (empat baris)
        $image->text($tokenLine1, 130, 1580 , fn($font) => $this->fontPayFazz($font, 'Bold', 80));
        $image->text($tokenLine2, 255, 1651 , fn($font) => $this->fontPayFazz($font, 'Bold', 80));

        $filename = $validated['nama'] . '_' . $waktuName . '.png';
        $path = public_path('generated/' . $filename);
        $image->save($path);

        return response()->download($path)->deleteFileAfterSend();
    }

    public function storeAsaCellular(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'nominal' => 'required|numeric',
            'no_hp' => 'required|string',
        ]);

        $imageManager = ImageManager::withDriver(new GdDriver());
        $image = $imageManager->read(public_path('template/asa_cellular.png'));
        Carbon::setLocale('id'); // Atur ke bahasa Indonesia
        // Tanggal + waktu acak 24 jam
        $carbon = Carbon::parse($validated['tanggal'])
            ->setTime(rand(0, 23), rand(0, 59), rand(0, 59));

        $tanggal = $carbon->translatedFormat('l, d M Y'); // contoh: "Selasa, 10 Sep 2024"
        $tanggalName = $carbon->format('d_M_Y'); // contoh: "10_Sep_2024"
        $jam     = $carbon->format('H:i') . ' WIB';       // contoh: "09:34 WIB"

        $prefix = $this->generateRandomLetters(5);   // Misal: "CLTGS"
        $suffix = $this->generateRandomLetters(3);   // Misal: "UNR"
        $year = now()->format('y');                  // Misal: "24"

        $orderId = '#' . $prefix . $year . $suffix;

        // Format nominal
        $nominalFormatted = 'Rp' . number_format($validated['nominal'], 0, ',', '.');
        $nominalTok = number_format($validated['nominal'], 0, ',', '.');

        // Nomor referensi random 16 digit
        $referensi = '';
        for ($i = 0; $i < 16; $i++) {
            $referensi .= random_int(0, 9);
        }
        $line = str_repeat('-', 70);
        // Ambil 4 digit terakhir referensi
        $kode4 = substr($referensi, -4);

        // Text placement
        $image->text('ASA CELLULER', 350, 150, fn($font) => $this->fontAsaCell($font, 'Regular', 68, '#000000'));
        $image->text($tanggal,       100, 230, fn($font) => $this->fontAsaCell($font, 'Regular', 48, '#000000'));
        $image->text($jam,           790, 230, fn($font) => $this->fontAsaCell($font, 'Regular', 48, '#000000'));
        $image->text('ORDER ID ' . $orderId, 100, 290, fn($font) => $this->fontAsaCell($font, 'Regular', 48, '#000000'));

        $image->text($line, 100, 350, fn($font) => $this->fontAsaCell($font, 'Regular', 23, '#000000'));
        $image->text('Transaksi Berhasil', 350, 430, fn($font) => $this->fontAsaCell($font, 'Regular', 48, '#000000'));

        // // $image->text($transaksiDetail, 50, 215, fn($font) => $this->fontAsaCell($font, 'Regular', 18, '#000000'));
        $image->text('Telkomsel ' . $nominalTok . ' - ' . $validated['no_hp'] , 100, 490, fn($font) => $this->fontAsaCell($font, 'Regular', 45, '#000000'));
        $image->text($nominalFormatted , 790, 570, fn($font) => $this->fontAsaCell($font, 'Regular', 45, '#000000'));
        $image->text($line, 100, 600, fn($font) => $this->fontAsaCell($font, 'Regular', 23, '#000000'));
        $image->text('Total', 100, 640, fn($font) => $this->fontAsaCell($font, 'Regular', 45, '#000000'));
        $image->text($nominalFormatted, 790, 640, fn($font) => $this->fontAsaCell($font, 'Regular', 45, '#000000'));

        // $image->text('Total', 50, 270, fn($font) => $this->fontAsaCell($font, 'Regular', 45, '#000000'));
        // $image->text($nominalFormatted, 320, 270, fn($font) => $this->fontAsaCell($font, 'Regular', 45, '#000000'));

        $image->text('Rincian Transaksi', 100, 800, fn($font) => $this->fontAsaCell($font, 'Regular', 45, '#000000'));
        $image->text('No. Referensi : ' . $referensi, 100, 870, fn($font) => $this->fontAsaCell($font, 'Regular', 45, '#000000'));
        $image->text($kode4, 530, 930, fn($font) => $this->fontAsaCell($font, 'Regular', 45, '#000000'));

        // Footer
        // $footerY = 1555;
        $line2 = str_repeat('=', 34);
        $image->text($line2, 100, 1030, fn($font) => $this->fontAsaCell($font, 'Regular', 48, '#000000'));
        $image->text('Struk ini merupakan bukti', 250, 1110, fn($font) => $this->fontAsaCell($font, 'Regular', 48, '#000000'));
        $image->text('pembayaran yang sah dari', 260, 1165, fn($font) => $this->fontAsaCell($font, 'Regular', 48, '#000000'));
        $image->text('PT PAYFAZZ TEKNOLOGI NUSANTARA', 180, 1230 , fn($font) => $this->fontAsaCell($font, 'Regular', 48, '#000000'));

        // Simpan file
        $filename = $tanggalName . ' - ' . Str::slug($validated['no_hp']) . '.png';
        $path = public_path('generated/' . $filename);
        $image->save($path);

        return response()->download($path)->deleteFileAfterSend();
    }

    public function storePesanTelkomsel(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
        ]);

        // Atur locale dan waktu
        Carbon::setLocale('id');
        $tanggal = Carbon::parse($validated['tanggal'])->format('d/m/Y');
        $jam = '23:59 WIB';

        $imageManager = ImageManager::withDriver(new GdDriver());
        $image = $imageManager->read(public_path('template/telkomsel.png'));

        // Format pesan
        $textLines = [
            'Selamat, Paket Combo Sakti MAX',
            '29GB, 100 Mnt Tsel, 200 SMS Tsel &',
            'Langganan Prime Video Mobile,',
            'WeTV selama 30 hari telah aktif,',
            "berlaku s/d tgl $tanggal pkl.",
            '23:59 WIB. Cek status/berhenti',
            'berlangganan melalui My Telkoms',
            'el Apps atau hub *363#. Info : 188.'
        ];

        $startY = 80; // Titik awal Y
        $lineHeight = 58; // Jarak antar baris (tambah sesuai ukuran font)

        foreach ($textLines as $index => $line) {
            $image->text($line, 55, $startY + ($index * $lineHeight), function ($font) {
                $this->fontPesan($font, 'Regular', 47, '#ffffff');
            });
        }
        // Simpan gambar
        $filename = 'pesan_' . now()->timestamp . '.png';
        $path = public_path('generated/' . $filename);
        $image->save($path);

        return response()->download($path)->deleteFileAfterSend();
    }

    private function fontPesan($font, $weight = 'Regular', $size = 47, $color = '#ffffff')
    {
        $fontPath = public_path("fonts/Poppins-{$weight}.ttf");
        $font->file($fontPath);
        $font->size($size);
        $font->color($color);
    }

    private function fontDana($font, $family = 'Roboto', $size = 14, $bold = false, $color = '#000000')
    {
        $weight = $bold ? 'Bold' : 'Regular';
        $fontPath = public_path("fonts/{$family}-{$weight}.ttf");
        $font->file($fontPath);
        $font->size($size);
        $font->color($color);
    }

    private function fontPayFazz($font, $weight = 'Regular', $size = 36, $color = '#000000')
    {
        $fontPath = public_path("fonts/JetBrainsMono-{$weight}.ttf");
        $font->file($fontPath);
        $font->size($size);
        $font->color($color);
    }

    private function fontAsaCell($font, $weight = 'Regular', $size = 36, $color = '#000000')
    {
        $fontPath = public_path("fonts/JetBrainsMono-{$weight}.ttf");
        $font->file($fontPath);
        $font->size($size);
        $font->color($color);
    }

    // Font Poppins
    private function fontGeutanyo($font, $weight = 'Regular', $size = 12, $color = '#000000')
    {
        $fontPath = public_path("fonts/Poppins-{$weight}.ttf");
        $font->file($fontPath);
        $font->size($size);
        $font->color($color);
    }


    // ID transaksi: 10 digit dimulai 6–9
    private function generateIdTransaksi()
    {
        $prefix = rand(6, 9);
        return $prefix . str_pad((string)rand(0, 999999999), 9, '0', STR_PAD_LEFT);
    }

    // 32 karakter huruf besar/angka
    private function generateNoReff()
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        return substr(str_shuffle(str_repeat($chars, 32)), 0, 32);
    }

    // Token 20 angka, format 4 4 4 4 4
    private function generateToken(): string
    {
        $raw = '';
        for ($i = 0; $i < 20; $i++) {
            $raw .= random_int(0, 9);
        }
        return trim(chunk_split($raw, 4, ' '));
    }

    private function generateTokenLines(): array
    {
        $token = '';
        for ($i = 0; $i < 20; $i++) {
            $token .= random_int(0, 9);
        }

        // Potong token menjadi array 5 blok isi 4 digit
        $chunks = str_split($token, 4); // hasil: ['1234', '5678', '9012', '3456', '7890']

        // Pastikan ada 5 blok
        if (count($chunks) < 5) {
            throw new \Exception('Token tidak terbentuk dengan benar.');
        }

        // Baris pertama: 3 blok pertama
        $line1 = implode('-', array_slice($chunks, 0, 3)); // e.g., 1234-5678-9012

        // Baris kedua: 2 blok terakhir
        $line2 = implode('-', array_slice($chunks, 3, 2)); // e.g., 3456-7890

        return [$line1, $line2];
    }

    private function generateRandomLetters(int $length = 5): string
    {
        $letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return substr(str_shuffle(str_repeat($letters, ceil($length / strlen($letters)))), 0, $length);
    }


}
