<?php

namespace App\Exports;

use App\Models\Employer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EmployerExport implements  FromCollection , WithMapping , WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $query = Employer::query();
        return $query->with('user', 'country', 'province', 'district', 'employerType', 'industry')->get();

     }


    public function headings():array{
        return[
            'Id',
            'Email',
            'First Name',
            'Last Name',
            'Phone',
            'Employer Type',
            'Industry',
            'Country',
            'Province',
            'District',
            'Title',
            'Organization Name',
            'Focal Person Title',
            'Focal Person First Name',
            'Focal Person Last Name',
            'Focal Person Phone',
            'Focal Person Email',
            'Company Registered In Pakistan',

        ];
    }

    public function map($employer): array
    {
        return [
            $employer->id,
            $employer->user->email,
            $employer->first_name,
            $employer->last_name,
            $employer->phone_no ,
            $employer->employerType->name,
            $employer->industry->name,
            $employer->country->name,
            $employer->province->name,
            $employer->district->name,
            $employer->title ,
            $employer->organization_name,
            $employer->focal_person_title,
            $employer->focal_person_first_name,
            $employer->focal_person_last_name,
            $employer->focal_person_phone,
            $employer->focal_person_email ,
            ($employer->is_registered_pak) == 0 ? 'NO' : 'YES',
        ];
    }
}
