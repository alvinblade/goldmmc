<?php

return [

    'accepted' => ':attribute qəbul edilməlidir',
    'active_url' => ':attribute doğru URL deyil',
    'after' => ':attribute :date tarixindən sonra olmalıdır',
    'after_or_equal' => ':attribute :date tarixi ilə eyni və ya sonra olmalıdır',
    'alpha' => ':attribute yalnız hərflərdən ibarət ola bilər',
    'alpha_dash' => ':attribute yalnız hərf, rəqəm və tire simvolundan ibarət ola bilər',
    'alpha_num' => ':attribute yalnız hərf və rəqəmlərdən ibarət ola bilər',
    'array' => ':attribute massiv formatında olmalıdır',
    'before' => ':attribute :date tarixindən əvvəl olmalıdır',
    'before_or_equal' => ':attribute :date tarixindən əvvəl və ya bərabər olmalıdır',
    'between' => [
        'numeric' => ':attribute :min ilə :max arasında olmalıdır',
        'file' => ':attribute :min ilə :max KB ölçüsü intervalında olmalıdır',
        'string' => ':attribute :min ilə :max simvolu intervalında olmalıdır',
        'array' => ':attribute :min ilə :max intervalında hissədən ibarət olmalıdır',
    ],
    'boolean' => ' :attribute doğru və ya yanlış ola bilər',
    'confirmed' => ':attribute doğrulanması yanlışdır',
    'date' => ' :attribute tarix formatında olmalıdır',
    'date_format' => ':attribute :format formatında olmalıdır',
    'different' => ':attribute və :other fərqli olmalıdır',
    'digits' => ':attribute :digits rəqəmli olmalıdır',
    'digits_between' => ':attribute :min ilə :max rəqəmləri intervalında olmalıdır',
    'dimensions' => ':attribute doğru şəkil ölçülərində deyil',
    'distinct' => ':attribute dublikat qiymətlidir',
    'email' => ':attribute doğru email formatında deyil',
    'exists' => 'seçilmiş :attribute yanlışdır',
    'file' => ':attribute fayl formatında olmalıdır',
    'filled' => ':attribute qiyməti olmalıdır',
    'image' => ':attribute şəkil formatında olmalıdır',
    'in' => 'seçilmiş :attribute yanlışdır',
    'in_array' => ':attribute :other qiymətləri arasında olmalıdır',
    'integer' => ':attribute tam ədəd olmalıdır',
    'ip' => ':attribute İP adres formatında olmalıdır',
    'ipv4' => ':attribute İPv4 adres formatında olmalıdır',
    'ipv6' => ':attribute İPv6 adres formatında olmalıdır',
    'json' => ':attribute JSON formatında olmalıdır',
    'max' => [
        'numeric' => ':attribute maksiumum :max rəqəmdən ibarət ola bilər',
        'file' => ':attribute maksimum :max KB ölçüsündə ola bilər',
        'string' => ':attribute maksimum :max simvoldan ibarət ola bilər',
        'array' => ':attribute maksimum :max hədd\'dən ibarət ola bilər',
    ],
    'mimes' => ':attribute :values tipində fayl olmalıdır',
    'mimetypes' => ':attribute :values tipində fayl olmalıdır',
    'min' => [
        'numeric' => ':attribute minimum :min rəqəmdən ibarət ola bilər',
        'file' => ':attribute minimum :min KB ölçüsündə ola bilər',
        'string' => ':attribute minimum :min simvoldan ibarət ola bilər',
        'array' => ':attribute minimum :min hədd\'dən ibarət ola bilər',
    ],
    'not_in' => 'seçilmiş :attribute yanlışdır',
    'numeric' => ':attribute rəqəmlərdən ibarət olmalıdır',
    'present' => ':attribute iştirak etməlidir',
    'regex' => ':attribute formatı yanlışdır',
    'required' => ':attribute mütləqdir',
    'required_if' => ':attribute (:other :value ikən) mütləqdir',
    'required_unless' => ':attribute (:other :values \'ə daxil ikən) mütləqdir',
    'required_with' => ':attribute (:values var ikən) mütləqdir',
    'required_with_all' => ':attribute (:values var ikən) mütləqdir',
    'required_without' => ':attribute (:values yox ikən) mütləqdir',
    'required_without_all' => ':attribute (:values yox ikən) mütləqdir',
    'same' => ':attribute və :other eyni olmalıdır',
    'size' => [
        'numeric' => ':attribute :size ölçüsündə olmalıdır',
        'file' => ':attribute :size KB ölçüsündə olmalıdır',
        'string' => ':attribute :size simvoldan ibarət olmalıdır',
        'array' => ':attribute :size hədd\'dən ibarət olmalıdır',
    ],
    'string' => ':attribute hərf formatında olmalıdır',
    'timezone' => ':attribute ərazi formatında olmalıdır',
    'unique' => ':attribute artıq iştirak edib',
    'uploaded' => ':attribute yüklənməsi mümkün olmadı',
    'url' => ':attribute formatı yanlışdır',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    |  following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [
        'email' => 'E-poçt',
        'password' => 'Şifrə',
        'name' => 'Ad',
        'surname' => 'Soyad',
        'father_name' => 'Ata adı',
        'username' => 'İstifadəçi adı',
        'phone' => 'Telefon',
        'birth_date' => 'Doğum tarixi',
        'education' => 'Təhsil',
        'education_files' => 'Təhsil faylları',
        'certificate_files' => 'Sertifikat faylları',
        'cv_files' => 'CV faylları',
        'self_photo_files' => 'Şəkil faylları',
        'previous_job' => 'Əvvəlki iş',
        'account_status' => 'Hesabın statusu',
        'email_verified_at' => 'E-poçt təsdiqlənən tarix',
        'company_name' => 'Şirkət adı',
        'company_short_name' => 'Şirkət qısa adı',
        'company_category' => 'Şirkət kateqoriyası',
        'company_obligation' => 'Şirkət mükəlləfiyəti',
        'owner_type' => 'Tip',
        'company_emails' => 'Şirkət e-poçtları',
        'company_addresses' => 'Şirkət ünvanı',
        'tax_id_number' => 'VÖEN',
        'tax_id_number_date' => 'VÖEN tarixi',
        'dsmf_number' => 'DSMF nömrəsi',
        'main_employee_id' => 'Səlahiyyətli şəxs',
        'director_id' => 'Direktor',
        'accountant_id' => 'Mühasib',
        'accountant_assign_date' => 'Mühasibin əlavə olunma tarixi',
        'tax_id_number_files' => 'VÖEN faylları',
        'charter_files' => 'Nizamnamə faylları',
        'extract_files' => 'Çıxarış faylları',
        'director_id_card_files' => 'Direktorun ŞV faylları',
        'creators_files' => 'Təsisçi faylları',
        'fixed_asset_files_exists' => 'Əsas vəsaitlərin mövcud olub olmaması',
        'fixed_asset_files' => 'Əsas vəsaitlərin faylları',
        'founding_decision_files' => 'Təsisçi qərarları faylları',
        'asan_sign' => 'ASAN imza nömrəsi',
        'asan_sign_start_date' => 'ASAN imza başlama vaxtı',
        'asan_sign_expired_at' => 'ASAN imza bitmə vaxtı',
        'asan_id' => 'ASAN ID',
        'pin1' => 'PIN 1',
        'pin2' => 'PIN 2',
        'puk' => 'PUK',
        'statistic_code' => 'Statistika kodu',
        'statistic_password' => 'Statistika şifrəsi',
        'operator_azercell_account' => 'Operator kabineti (Azercell)',
        'operator_azercell_password' => 'Operator şifrəsi (Azercell)',
        'ydm_account_email' => 'YDM e-poçt',
        'ydm_account_password' => 'YDM şifrəsi',
        'ydm_card_expired_at' => 'YDM kartının bitmə tarixi',
        'is_vat_payer' => 'ƏDV ödəyicisi',
        'company_id' => 'Şirkət',
        'position_id' => 'Vəzifə',
        'id_card_serial' => 'ŞV seriyası',
        'fin_code' => 'Fin kod',
        'id_card_date' => 'ŞV verilmə tarixi',
        'ssn' => 'Sosial sığorta nömrəsi (SSN)',
        'start_date_of_employment' => 'İşə başlama tarixi',
        'end_date_of_employment' => 'İşə bitmə tarixi',
        'work_experience' => 'İş təcrübəsi',
        'gender' => 'Cins',
        'salary' => 'Əmək haqqı',
        'salary_card_expired_at' => 'Əmək haqqı kartının bitmə tarixi',
        'employee_type' => 'İşçi tipi',
        'code' => 'Kod',
        'short_title' => 'Qısa ad',
        'title' => 'Başlıq',
        'symbol' => 'Simvol',
        'rate' => 'Norma',
        'currency_id' => 'Valyuta',
    ],
];
