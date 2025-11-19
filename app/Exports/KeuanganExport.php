<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class KeuanganExport implements FromCollection, WithHeadings, WithMapping
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'No Pendaftaran',
            'Nama',
            'Jurusan',
            'Status Pembayaran',
            'Biaya',
            'Tanggal Bayar',
            'Metode Pembayaran'
        ];
    }

    public function map($row): array
    {
        return [
            $row->no_pendaftaran,
            $row->nama,
            $row->jurusan->nama ?? '-',
            $row->status,
            $row->biaya_daftar,
            $row->tanggal_pembayaran ? date('d/m/Y', strtotime($row->tanggal_pembayaran)) : '-',
            $row->metode_pembayaran ?? '-'
        ];
    }
}