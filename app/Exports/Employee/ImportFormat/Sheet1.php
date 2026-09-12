<?php

namespace App\Exports\Employee\ImportFormat;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class Sheet1 implements FromView, WithTitle, WithColumnFormatting
{
    private $organizations;
    private $divisions;
    private $companies;
    private $placements;
    private $genders;
    private $maritals;
    private $religions;
    private $banks;
    private $emergency_relations;
    private $cities;
    private $provinces;
    private $contracts;
    private $careers;
    private $offices;

    public function __construct($organizations, $divisions, $companies, $placements, $genders, $maritals, $religions, $banks, $emergency_relations, $cities, $provinces, $contracts, $careers, $offices)
    {
        $this->organizations = $organizations;
        $this->divisions = $divisions;
        $this->companies = $companies;
        $this->placements = $placements;
        $this->genders = $genders;
        $this->maritals = $maritals;
        $this->religions = $religions;
        $this->banks = $banks;
        $this->emergency_relations = $emergency_relations;
        $this->cities = $cities;
        $this->provinces = $provinces;
        $this->contracts = $contracts;
        $this->careers = $careers;
        $this->offices = $offices;
    }

    public function view(): View
    {
        $organizations = $this->organizations;
        $divisions = $this->divisions;
        $companies = $this->companies;
        $placements = $this->placements;
        $genders = $this->genders;
        $maritals = $this->maritals;
        $religions = $this->religions;
        $banks = $this->banks;
        $emergency_relations = $this->emergency_relations;
        $cities = $this->cities;
        $provinces = $this->provinces;
        $contracts = $this->contracts;
        $careers = $this->careers;
        $offices = $this->offices;

        return view('exports.excel.employee.import_format.sheet1', [
            'organizations' => $organizations,
            'divisions' => $divisions,
            'companies' => $companies,
            'placements' => $placements,
            'genders' => $genders,
            'maritals' => $maritals,
            'religions' => $religions,
            'banks' => $banks,
            'emergency_relations' => $emergency_relations,
            'cities' => $cities,
            'provinces' => $provinces,
            'contracts' => $contracts,
            'careers' => $careers,
            'offices' => $offices,
        ]);
    }

    public function columnFormats(): array
    {
        return [
            // 'Q' => NumberFormat::FORMAT_TEXT,
            // 'R' => NumberFormat::FORMAT_TEXT,
            // 'Z' => NumberFormat::FORMAT_TEXT,
            // 'AN' => NumberFormat::FORMAT_TEXT,
            // 'AW' => NumberFormat::FORMAT_TEXT,
            // 'AX' => NumberFormat::FORMAT_TEXT,
        ];
    }

    public function title(): string
    {
        return 'DATA';
    }
}
