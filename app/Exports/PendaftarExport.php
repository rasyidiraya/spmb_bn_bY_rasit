<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PendaftarExport implements FromCollection, WithHeadings, WithMapping
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
            'Email',
            'No HP',
            'Jurusan',
            'Gelombang',
            'Status',
            'Biaya',
            'Tanggal Daftar'
        ];
    }

    public function map($row): array
    {
        return [
            $row->no_pendaftaran,
            $row->nama,
            $row->email,
            $row->no_hp,
            $row->jurusan->nama ?? '-',
            $row->gelombang->nama ?? '-',
            $row->status,
            $row->biaya_daftar,
            $row->created_at->format('d/m/Y H:i')
        ];
    }
}