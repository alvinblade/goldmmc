<?php

use App\Enums\AttendanceLogDayTypes;
use App\Enums\CompanyCategoriesEnum;
use App\Enums\CompanyObligationsEnum;
use App\Enums\EducationTypesEnum;
use App\Enums\EmployeeTypes;
use App\Enums\EnvelopeTypes;
use App\Enums\GenderTypes;
use App\Enums\RentalTypes;
use App\Enums\StatusTypesEnum;
use App\Enums\UserTypesEnum;
use App\Models\Orders\HiringOrder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use NumberToWords\Exception\NumberToWordsException;
use NumberToWords\NumberToWords;

if (!function_exists("getEducationTypes")) {
    function getEducationTypes(): array
    {
        return array_map(function ($type) {
            return [
                'value' => $type,
                'label' => trans('education_types.' . $type)
            ];
        }, EducationTypesEnum::toArray());
    }
}
if (!function_exists("getEnvelopeTypes")) {
    function getEnvelopeTypes(): array
    {
        return array_map(function ($type) {
            return [
                'value' => $type,
                'label' => trans('envelope_types.' . $type)
            ];
        }, EnvelopeTypes::toArray());
    }
}
if (!function_exists("getAccountStatusTypes")) {
    function getAccountStatusTypes(): array
    {
        return array_map(function ($type) {
            return [
                'value' => $type,
                'label' => trans('status_types.' . $type)
            ];
        }, StatusTypesEnum::toArray());
    }
}
if (!function_exists("getGenderTypes")) {
    function getGenderTypes(): array
    {
        return array_map(function ($type) {
            return [
                'value' => $type,
                'label' => trans('gender_types.' . $type)
            ];
        }, GenderTypes::toArray());
    }
}
if (!function_exists("getEmployeeTypes")) {
    function getEmployeeTypes(): array
    {
        return array_map(function ($type) {
            return [
                'value' => $type,
                'label' => trans('employee_types.' . $type)
            ];
        }, EmployeeTypes::toArray());
    }
}
if (!function_exists("getCompanyCategoryTypes")) {
    function getCompanyCategoryTypes(): array
    {
        return array_map(function ($type) {
            return [
                'value' => $type,
                'label' => trans('company_categories.' . $type)
            ];
        }, CompanyCategoriesEnum::toArray());
    }
}
if (!function_exists("getCompanyObligationTypes")) {
    function getCompanyObligationTypes(): array
    {
        return array_map(function ($type) {
            return [
                'value' => $type,
                'label' => trans('company_obligations.' . $type)
            ];
        }, CompanyObligationsEnum::toArray());
    }
}
if (!function_exists("getCompanyOwnerTypes")) {
    function getCompanyOwnerTypes(): array
    {
        return array_map(function ($type) {
            return [
                'value' => $type,
                'label' => trans('company_types.' . $type)
            ];
        }, UserTypesEnum::toArray());
    }
}
if (!function_exists("getRentalTypes")) {
    function getRentalTypes(): array
    {
        return array_map(function ($type) {
            return [
                'value' => $type,
                'label' => trans('rental_types.' . $type)
            ];
        }, RentalTypes::toArray());
    }
}
if (!function_exists("getElementByKey")) {
    function getElementByKey($array, $searchKey, $value)
    {
        foreach ($array as $element) {
            if ($element[$searchKey] == $value[$searchKey]) {
                return $element;
            }
        }

        return null;
    }
}
if (!function_exists("returnFilesArray")) {
    function returnFilesArray(array $files, $strForPath): array
    {
        $returnedArray = [];

        foreach ($files as $file) {
            $fileName = $strForPath . uniqid() . time() . '.' . $file->getClientOriginalExtension();

            Storage::putFileAs('public/' . $strForPath, $file, $fileName);

            $returnedArray[] = [
                'path' => config('app.url') . ':' . config('app.port') . '/storage/' . $strForPath . '/' . $fileName,
                'folder' => $strForPath,
                'generated_name' => $fileName,
                'original_name' => $file->getClientOriginalName(),
            ];
        }

        return $returnedArray;
    }
}
if (!function_exists('checkFilesAndDeleteFromStorage')) {
    function checkFilesAndDeleteFromStorage($files): void
    {
        foreach ($files as $file) {
            $path = ltrim(parse_url($file['path'], PHP_URL_PATH), '/storage');
            $exists = Storage::disk('public')->exists($path);

            if ($exists) {
                Storage::disk('public')->delete($path);
            }
        }
    }
}
if (!function_exists('deleteFiles')) {
    function deleteFiles($deletedFiles, array $currentFiles, bool $notEmpty = false): bool|array
    {
        foreach ($deletedFiles as $file) {
            if ($notEmpty && count($deletedFiles) >= count($currentFiles)) {
                return false;
            }

            $deletedFile = getElementByKey($currentFiles, 'generated_name', $file);

            $key = array_search($deletedFile, $currentFiles);

            $path = isset($deletedFile['path']) ?
                preg_replace('#^/storage/#', '', parse_url($deletedFile['path'],
                    PHP_URL_PATH)) : null;

            if ($deletedFile != null &&
                Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
                unset($currentFiles[$key]);
            }
        }

        return $currentFiles;
    }

}

if (!function_exists('deleteFilesAws')) {
    function deleteFilesAws($deletedFiles, $currentFiles, bool $notEmpty = false)
    {
        $s3 = App::make('aws')->createClient('s3');

        foreach ($deletedFiles as $file) {
            if ($notEmpty && count($deletedFiles) >= count($currentFiles)) {
                return false;
            }

            $deletedFile = getElementByKey($currentFiles, 'generated_name', $file);
            $key = array_search($deletedFile, $currentFiles);

            if ($deletedFile != null) {
                $s3->deleteObject(array(
                    'Bucket' => $deletedFile['bucket'],
                    'Key' => $deletedFile['generated_name']
                ));

                $s3->waitUntil('ObjectNotExists', array(
                    'Bucket' => $deletedFile['bucket'],
                    'Key' => $deletedFile['generated_name']
                ));

                unset($currentFiles[$key]);
            }
        }

        return $currentFiles;
    }

}
if (!function_exists("returnFilesArrayAws")) {
    function returnFilesArrayAws(array $files, $strForPath): array
    {
        $returnedArray = [];
        $s3 = App::make('aws')->createClient('s3');
        $listBuckets = $s3->listBuckets()->search('Buckets[].Name');
        $checkIfBucketExists = in_array($strForPath, $listBuckets);

        if (!$checkIfBucketExists) {
            $s3->createBucketAsync([
                'Bucket' => $strForPath
            ]);
        }

        foreach ($files as $file) {
            $fileName = $strForPath . uniqid() . '.' . $file->getClientOriginalExtension();

            $s3->putObject(array(
                'Bucket' => $strForPath,
                'Key' => $fileName,
                'SourceFile' => $file->getRealPath(),
            ));

            $returnedArray[] = [
                'path' => env('APP_URL') . '/api/show-s3-file/' . $strForPath . '/' . $fileName,
                'bucket' => $strForPath,
                'generated_name' => $fileName,
                'original_name' => $file->getClientOriginalName(),
            ];
        }

        return $returnedArray;
    }
}
if (!function_exists("returnOrderFile")) {
    function returnOrderFile($file, $fileName, $bucket): array
    {
        $returnedArray = [];
        $s3 = App::make('aws')->createClient('s3');
        $listBuckets = $s3->listBucketsAsync();
        $listBuckets = $listBuckets->wait();
        $listBuckets = $listBuckets->search('Buckets[].Name');
        $checkIfBucketExists = in_array($bucket, $listBuckets);

        if (!$checkIfBucketExists) {
            $s3->createBucketAsync([
                'Bucket' => $bucket
            ]);
        }

        $s3->putObject(array(
            'Bucket' => $bucket,
            'Key' => $fileName,
            'SourceFile' => $file,
        ));

        $returnedArray[] = [
            'path' => env('BASE_URL') . '/api/show-s3-file/' . $bucket . '/' . $fileName,
            'bucket' => $bucket,
            'generated_name' => $fileName,
            'original_name' => $fileName,
        ];

        return $returnedArray;
    }
}
if (!function_exists('checkFilesAndDeleteFromStorageAws')) {
    function checkFilesAndDeleteFromStorageAws($files): void
    {
        $s3 = App::make('aws')->createClient('s3');

        foreach ($files as $file) {
            $s3->deleteObject(array(
                'Bucket' => $file['bucket'],
                'Key' => $file['generated_name']
            ));
        }
    }
}
if (!function_exists('getNumberEnd')) {
    function getNumberEnd($char, $lastChar = null): string
    {
        $lastChar .= match ($char) {
            '06', '16', '26', '36', '40', '46', '56', '60', '66', '76', '86', '90', '96' => '-cı',
            '04', '03', '13', '14', '23', '24', '33', '34', '43', '44',
            '53', '54', '63', '64', '73', '74', '83', '84', '93', '94' => '-cü',
            '09', '10', '19', '29', '30', '39', '49', '59', '69', '79', '89', '99' => '-cu',
            default => '-ci',
        };

        return $lastChar;
    }
}
if (!function_exists('getGender')) {
    function getGender($gender): string|null
    {
        return match ($gender) {
            'MALE' => 'oğlu',
            'FEMALE' => 'qızı',
            default => null,
        };
    }
}
if (!function_exists('getCbaRates')) {
    function getCbaRates($today)
    {
        $xml = simplexml_load_file("https://cbar.az/currencies/$today.xml") or die("Error");

        $exchangesArray = $xml->children()[1]->children();

        $azn = [
            'code' => 'AZN',
            'name' => 'Azərbaycan manatı',
            'rate' => "1",
            'symbol' => '₼',
            'bank' => 'CBAR'
        ];

        $usd = [
            'code' => $exchangesArray[0]->attributes()->Code->__toString(),
            'name' => trim(preg_replace('/\d+/', '', $exchangesArray[0]->Name->__toString())),
            'rate' => $exchangesArray[0]->Value->__toString(),
            'symbol' => '$',
            'bank' => 'CBAR'
        ];

        $eur = [
            'code' => $exchangesArray[1]->attributes()->Code->__toString(),
            'name' => trim(preg_replace('/\d+/', '', $exchangesArray[1]->Name->__toString())),
            'rate' => $exchangesArray[1]->Value->__toString(),
            'symbol' => '€',
            'bank' => 'CBAR'
        ];

        $gbp = [
            'code' => $exchangesArray[16]->attributes()->Code->__toString(),
            'name' => trim(preg_replace('/\d+/', '', $exchangesArray[16]->Name->__toString())),
            'rate' => $exchangesArray[16]->Value->__toString(),
            'symbol' => '£',
            'bank' => 'CBAR'
        ];

        $try = [
            'code' => $exchangesArray[38]->attributes()->Code->__toString(),
            'name' => trim(preg_replace('/\d+/', '', $exchangesArray[38]->Name->__toString())),
            'rate' => $exchangesArray[38]->Value->__toString(),
            'symbol' => '₺',
            'bank' => 'CBAR'
        ];

        $rub = [
            'code' => $exchangesArray[34]->attributes()->Code->__toString(),
            'name' => trim(preg_replace('/\d+/', '', $exchangesArray[34]->Name->__toString())),
            'rate' => $exchangesArray[34]->Value->__toString(),
            'symbol' => '₽',
            'bank' => 'CBAR'
        ];

        return [$azn, $usd, $eur, $gbp, $try, $rub];
    }
}
if (!function_exists('getLabelValue')) {
    function getLabelValue(string $type, array $arr): array
    {
        return [
            'label' => collect($arr)->where('value', $type)->first()['label'],
            'value' => $type
        ];
    }
}
if (!function_exists('generateOrderNumber')) {
    function generateOrderNumber($model, $companyName): string
    {
        $count = $model::count() + 1;

        return $companyName . '-' . $count . '/' . date('Y');
    }
}
if (!function_exists('toFloat')) {
    function toFloat(float|int|string $value): float
    {
        return number_format(floatval($value), 2, '.', '');
    }
}
if (!function_exists('returnMonthDaysAsArray')) {
    function returnMonthDaysAsArray(int $count): array
    {
        $days = [];

        for ($i = 0; $i < $count; $i++) {
            $days[] = $i + 1;
        }

        return $days;
    }
}
if (!function_exists('checkMonthDaysUnique')) {
    function checkMonthDaysUnique(array $yearConfig, array $requestConfig): bool|int|string
    {

        $yearConfigDays = [];
        $requestConfigDays = [];

        foreach ($yearConfig as $yearConfigDetail) {
            $yearConfigDays[$yearConfigDetail['month_name']] = collect($yearConfigDetail['days'])->map(function ($day) {
                return $day['day'];
            })->toArray();
        }

        foreach ($requestConfig as $requestConfigDay) {
            $requestConfigDays[$requestConfigDay['month_name']] =
                collect($requestConfigDay['days'])->map(function ($day) {
                    return $day['day'];
                })->toArray();
        }

        $duplicatedMonths = array_diff(array_keys($yearConfigDays), array_keys($requestConfigDays));

        if (!empty($duplicatedMonths)) {
            return implode(',', array_values($duplicatedMonths));
        }

        foreach ($yearConfigDays as $key => $ycd) {
            if ($requestConfigDays[$key] != $ycd) {
                return $key;
            }
        }

        return true;
    }
}
if (!function_exists('getMonthWorkDayHours')) {
    function getMonthWorkDayHours(array $config): float|int
    {
        return array_sum(array_column($config, 'status'));
    }
}
if (!function_exists('getCelebrationRestDaysCount')) {
    function getCelebrationRestDaysCount(array $config): int
    {
        $dayTypes = array_diff(array_values(AttendanceLogDayTypes::toArray()),
            [AttendanceLogDayTypes::NULL_DAY->value]);
        $array = array_count_values(array_column($config, 'status'));

        $totalDays = 0;

        foreach ($dayTypes as $dayType) {
            if (array_key_exists($dayType, $array)) {
                $totalDays += $array[$dayType];
            }
        }

        return $totalDays;
    }
}
if (!function_exists('getMonthWorkDaysCount')) {
    function getMonthWorkDaysCount(array $config): float|int
    {
        $config = collect($config)->where('status', '!=', AttendanceLogDayTypes::NULL_DAY->value)
            ->where('status', '!=', AttendanceLogDayTypes::DEFAULT_HOLIDAY->value)
            ->where('status', '!=', AttendanceLogDayTypes::LEAVING_WORK->value)
            ->where('status', '!=', AttendanceLogDayTypes::ILLNESS->value)
            ->where('status', '!=', AttendanceLogDayTypes::BUSINESS_TRIP->value)
            ->toArray();

        return count($config) - getCelebrationRestDaysCount($config);
    }
}
if (!function_exists('getNumberAsWords')) {
    /**
     * @throws NumberToWordsException
     * @throws \NumberToWords\Exception\InvalidArgumentException
     */
    function getNumberAsWords(int|float $number): string
    {
        $numberToWords = new NumberToWords();
        $numberToWordsTransformer = $numberToWords->getNumberTransformer('az');

        return $numberToWordsTransformer->toWords($number);
    }
}

if (!function_exists('getHeaderCompanyId')) {
    function getHeaderCompanyId(): int|bool
    {
        $companyId = request()->header('company-id');

        if (auth()->check()) {
            $authUserCompanies = auth()->user()->companiesServed()->pluck('id')->toArray();

            if (auth()->user()->hasAnyRole(['leading_expert', 'department_head'])) {
                return $companyId;
            }

            if (auth()->user()->hasRole(['accountant']) && $companyId && in_array($companyId, $authUserCompanies)) {
                return $companyId;
            }
        }

        return false;
    }
}
