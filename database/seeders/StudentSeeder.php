<?php

namespace Database\Seeders;

use App\Models\Student;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $students = [
            ['created_at' => now(),'updated_at' => now(),'roll' => '5', 'name' => 'Khalequzzaman Labonno', 'phone' => '01710503837', 'email' => 'khalequzzaman17@gmail.com'],
            ['created_at' => now(),'updated_at' => now(),'roll' => '6', 'name' => 'Riya Akter', 'phone' => '01301149264', 'email' => 'mostriayakter2002@gmail.com'],
            ['created_at' => now(),'updated_at' => now(),'roll' => '7', 'name' => 'Md. Rumon Ahmed', 'phone' => '01776956453', 'email' => 'rumonahmed563@gmail.com'],
            ['created_at' => now(),'updated_at' => now(),'roll' => '11', 'name' => 'Md Rulin Rahman', 'phone' => '01644323065', 'email' => 'contact.rulinrahman@gmail.com'],
            ['created_at' => now(),'updated_at' => now(),'roll' => '12', 'name' => 'Jannatul Ferdous', 'phone' => '01720420023', 'email' => 'jannatulferdous20420023@gmail.com'],
            ['created_at' => now(),'updated_at' => now(),'roll' => '13', 'name' => 'Mahruful Alam', 'phone' => '01881969060', 'email' => 'mahruf9060@gmail.com'],
            ['created_at' => now(),'updated_at' => now(),'roll' => '15', 'name' => 'Md Mehedi Hasan Joy', 'phone' => '01739605553', 'email' => 'mehedi@mehedi.io'],
            ['created_at' => now(),'updated_at' => now(),'roll' => '16', 'name' => 'Joy Karmokar', 'phone' => '01765859636', 'email' => 'rajkumer975@gmail.com'],
            ['created_at' => now(),'updated_at' => now(),'roll' => '18', 'name' => 'Md. Shariful Islam', 'phone' => '01751449880', 'email' => 'mdsharifulislam9880@gmail.com'],
            ['created_at' => now(),'updated_at' => now(),'roll' => '19', 'name' => 'Aklima Akter', 'phone' => '01637099120', 'email' => 'akhia7811@gmail.com'],
            ['created_at' => now(),'updated_at' => now(),'roll' => '20', 'name' => 'CR - Md. Mosttakim Billah', 'phone' => '01984633775', 'email' => 'mosttakim01@gmail.com'],
            ['created_at' => now(),'updated_at' => now(),'roll' => '21', 'name' => 'Akhi Moni', 'phone' => '01701942670', 'email' => 'missalonaldanga@gmail.com'],
            ['created_at' => now(),'updated_at' => now(),'roll' => '22', 'name' => 'Mahmuda Akter', 'phone' => '01322956912', 'email' => 'mahmudamunni84@gmail.com'],
            ['created_at' => now(),'updated_at' => now(),'roll' => '23', 'name' => 'Zamil Uddin', 'phone' => '01719181239', 'email' => 'zamilgraphicsdesigner@gmail.com'],
            ['created_at' => now(),'updated_at' => now(),'roll' => '24', 'name' => 'Md. Al-Amin', 'phone' => '01745473151', 'email' => 'mdalamin.diu.cse@gmail.com'],
            ['created_at' => now(),'updated_at' => now(),'roll' => '25', 'name' => 'Md. Hadaitullah', 'phone' => '01796460002', 'email' => 'hadaitullah808@gmail.com'],
            ['created_at' => now(),'updated_at' => now(),'roll' => '26', 'name' => 'Sumi Akter', 'phone' => '01610714131', 'email' => 'sumiakter59664@gmail.com'],
            ['created_at' => now(),'updated_at' => now(),'roll' => '27', 'name' => 'Mitua Das', 'phone' => '01816045267', 'email' => 'Mituadas59@gmail.com'],
            ['created_at' => now(),'updated_at' => now(),'roll' => '28', 'name' => 'Urmi Dey', 'phone' => '01621798062', 'email' => 'urmidey460@gmail.com'],
            ['created_at' => now(),'updated_at' => now(),'roll' => '31', 'name' => 'Md. Jibon Islam Santo', 'phone' => '01947563737', 'email' => 'jisjibonpb07@gmail.com'],
            ['created_at' => now(),'updated_at' => now(),'roll' => '32', 'name' => 'Mohammad Mahfuz Hasan Sourav', 'phone' => '01724589124', 'email' => 'mhsourav79@gmail.com'],
            ['created_at' => now(),'updated_at' => now(),'roll' => '33', 'name' => 'Mahmudul Hasan', 'phone' => '01703041072', 'email' => 'mridulmh400@gmail.com'],
            ['created_at' => now(),'updated_at' => now(),'roll' => '34', 'name' => 'CR - Shakhawat Hosen', 'phone' => '01884897611', 'email' => 'shakhawat9083@gmail.com'],
            ['created_at' => now(),'updated_at' => now(),'roll' => '35', 'name' => 'Atiqur Rahman', 'phone' => '01825465820', 'email' => 'atik74734@gmail.com'],
            ['created_at' => now(),'updated_at' => now(),'roll' => '36', 'name' => 'Murad Mia', 'phone' => '01834749557', 'email' => 'muradhosain01834@gmail.com'],
            ['created_at' => now(),'updated_at' => now(),'roll' => '37', 'name' => 'Shahriar Ahmed', 'phone' => '01757101864', 'email' => 'shahriarshazid0@gmail.com'],
            ['created_at' => now(),'updated_at' => now(),'roll' => '39', 'name' => 'Md Abdullah', 'phone' => '01995082091', 'email' => 'abdullah21bd@gmail.com'],
            ['created_at' => now(),'updated_at' => now(),'roll' => '41', 'name' => 'MD Shafin islam', 'phone' => '01948476917', 'email' => 'mdshafinislam183@gmail.com'],
            ['created_at' => now(),'updated_at' => now(),'roll' => '42', 'name' => 'Md Mahim Mahmud', 'phone' => '01948746451', 'email' => 'mdmahimmahmud6244@gmail.com'],
            ['created_at' => now(),'updated_at' => now(),'roll' => '43', 'name' => 'Md. Abu Nasim', 'phone' => '01770167590', 'email' => 'nasimcse1@gmail.com'],
            ['created_at' => now(),'updated_at' => now(),'roll' => '44', 'name' => 'Md. Tarek Ahammed Emran', 'phone' => '01792446110', 'email' => 'emran.ahammed26@gmail.com'],
            ['created_at' => now(),'updated_at' => now(),'roll' => '46', 'name' => 'Md Sultan Basunia', 'phone' => '01400703776', 'email' => 'sultanmahamudkpi@gmail.com'],
        ];

        Student::insert($students);
    }
}
