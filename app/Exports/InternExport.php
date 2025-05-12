<?php

namespace App\Exports;

use App\Models\Intern;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class InternExport implements FromCollection, WithMapping, WithHeadings, WithColumnFormatting
{
    public function collection()
    {
        $query = Intern::query();
        return $query->with('user', 'industry', 'internshipRole', 'province', 'district', 'testResult', 'internshipProvince', 'internshipDistrict')->get();
    }

    public function columnFormats(): array
    {
        return [
            'F' => "######-#######-#",
        ];
    }

    public function headings(): array
    {
        return [
            'Id',
            'First Name',
            'Last Name',
            'Email',
            'Phone No',
            'CNIC',
            'PEC',
            'Gender',
            'Is Employed',
            'Country',
            'Province',
            'District',
            'Domicile',
            'CNIC Verified',
            'Internship Industry',
            'Internship Role',
            'Internship Province',
            'Internship District',
            'Employer Type',
            'Test Submitted',

        ];
    }

    public function map($intern): array
    {
//        dd($intern->testResult);
        return [
            $intern->id,
            $intern->intern_first_name,
            $intern->intern_last_name,
            $intern->email,
            $intern->phone_no,
            $intern->cnic,
            $intern->pec,
            $intern->gender->name,
            ($intern->is_employed == 1) ? 'NO' : 'YES',
            $intern->country->name,
            $intern->province->name,
            $intern->district->name,
            $intern->domicileDistrict->name,
            ($intern->is_cnic_verifired == 1) ? 'Verified' : 'Not Verified',
            $intern->industry->name,
            $intern->internshipRole->name,
            $intern->internshipProvince->name,
            $intern->internshipDistrict->name,
            $intern->employerType->name,
            $intern->testResult->name,
            ($intern->testResult->is_pass == 1) ? 'Submitted' : 'Not Submitted',
        ];
    }


}
