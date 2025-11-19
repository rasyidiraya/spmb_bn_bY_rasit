<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class VerifikasiExport implements FromCollection, WithHeadings, WithMapping
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
            'Status Verifikasi',
            'Tanggal Verifikasi',
            'Tanggal Daftar'
        ];
    }

    public function map($row): array
    {
        return [
            $row->no_pendaftaran,
            $row->nama,
            $row->jurusan->nama ?? '-',
            $row->status,
            $row->tgl_verifikasi_adm ? date('d/m/Y H:i', strtotime($row->tgl_verifikasi_adm)) : '-',
            $row->created_at->format('d/m/Y H:i')
        ];
    }
}