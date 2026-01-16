<?php

namespace App\Exports;

use Generator;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class GeneralReportExport implements FromArray,WithHeadings,ShouldAutoSize
{
    public string $start;
    public string $end;
    public function __construct(string $start,string $end) {
        $this->start = $start;
        $this->end = $end;
    }

    public function array(): array
    {
        return DB::select("CALL sp_general_debts_report(?,?)", [$this->start, $this->end]);
        
    }

    public function headings(): array
    {
        return [
        "ID",               //	Identificador del reporte - listo
        "Nombre Completo",  // 	Nombre del suscriptor - listo
        "DNI",              //	Documento de identidad - listo
        "Email",            //	Correo electrónico - listo
        "Teléfono",         //	Número de contacto - listo
        "Compañía",         //	Banco o entidad asociada a la deuda
        "Tipo de deuda",    // 	Préstamo, Tarjeta de crédito u Otra deuda
        "Situación",        //	Estado del crédito (NOR, CPP, DEF, PER)
        "Atraso",           //	Días de vencimiento
        "Entidad",          //	Entidad financiera o comercial
        "Monto total",      // 	Monto de la deuda
        "Línea total",      // 	Línea de crédito aprobada (aplica para tarjetas)
        "Línea usada",      // 	Línea de crédito utilizada (aplica para tarjetas)
        "Reporte subido el",// Fecha de creación del reporte
        "Estado",           //	Estado general del registro    
        ];
    }

}
