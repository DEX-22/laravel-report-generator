<?php

declare(strict_types=1);

namespace App\Exports;

use App\Services\IReportService;
use Generator;
use Maatwebsite\Excel\Concerns\FromGenerator;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class GeneralReportExport implements FromGenerator, WithHeadings, ShouldAutoSize
{
    public function __construct(
        protected IReportService $service,
        public string $start,
        public string $end
    ) {
    }

    public function generator(): Generator
    {
        foreach ($this->service->getGeneralDebtsReport($this->start, $this->end) as $row) {
            yield $row;
        }
    }

    public function headings(): array
    {
        return [
            'ID',               //	Identificador del reporte - listo
            'Nombre Completo',  // 	Nombre del suscriptor - listo
            'DNI',              //	Documento de identidad - listo
            'Email',            //	Correo electrónico - listo
            'Teléfono',         //	Número de contacto - listo
            'Compañía',         //	Banco o entidad asociada a la deuda
            'Tipo de deuda',    // 	Préstamo, Tarjeta de crédito u Otra deuda
            'Situación',        //	Estado del crédito (NOR, CPP, DEF, PER)
            'Atraso',           //	Días de vencimiento
            'Entidad',          //	Entidad financiera o comercial
            'Monto total',      // 	Monto de la deuda
            'Línea total',      // 	Línea de crédito aprobada (aplica para tarjetas)
            'Línea usada',      // 	Línea de crédito utilizada (aplica para tarjetas)
            'Reporte subido el', // Fecha de creación del reporte
            'Estado',           //	Estado general del registro
        ];
    }
}
