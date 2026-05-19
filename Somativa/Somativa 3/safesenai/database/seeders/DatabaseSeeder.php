<?php

namespace Database\Seeders;

use App\Models\Classroom;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 2 cursos
        $inforematica = Classroom::create([
            'name'   => 'Informática',
            'grade'  => 'Técnico em Informática',
            'shift'  => 'morning',
            'year'   => 2026,
            'active' => true,
        ]);

        $administracao = Classroom::create([
            'name'   => 'Administração',
            'grade'  => 'Técnico em Administração',
            'shift'  => 'afternoon',
            'year'   => 2026,
            'active' => true,
        ]);

        // 1 coordenador
        User::create([
            'name'     => 'Coordenação SENAI',
            'email'    => 'coordenacao@safe.test',
            'password' => Hash::make('1234'),
            'role'     => 'coordinator',
            'active'   => true,
        ]);

        // 2 professores, cada um vinculado a um curso
        User::create([
            'name'         => 'Prof. Carlos Souza',
            'email'        => 'carlos.souza@safe.test',
            'password'     => Hash::make('1234'),
            'role'         => 'teacher',
            'classroom_id' => $inforematica->id,
            'active'       => true,
        ]);

        User::create([
            'name'         => 'Profa. Ana Lima',
            'email'        => 'ana.lima@safe.test',
            'password'     => Hash::make('1234'),
            'role'         => 'teacher',
            'classroom_id' => $administracao->id,
            'active'       => true,
        ]);

        // Porteiro (necessário para o fluxo de saída antecipada)
        User::create([
            'name'     => 'José Porteiro',
            'email'    => 'portaria@safe.test',
            'password' => Hash::make('1234'),
            'role'     => 'gatekeeper',
            'active'   => true,
        ]);

        // 20 alunos — 10 por curso
        $studentsInformatica = [
            ['name' => 'Lucas Ferreira',    'registration' => '2026001'],
            ['name' => 'Beatriz Santos',    'registration' => '2026002'],
            ['name' => 'Gabriel Oliveira',  'registration' => '2026003'],
            ['name' => 'Mariana Costa',     'registration' => '2026004'],
            ['name' => 'Rafael Almeida',    'registration' => '2026005'],
            ['name' => 'Júlia Rodrigues',   'registration' => '2026006'],
            ['name' => 'Thiago Pereira',    'registration' => '2026007'],
            ['name' => 'Fernanda Lima',     'registration' => '2026008'],
            ['name' => 'Diego Nascimento',  'registration' => '2026009'],
            ['name' => 'Amanda Carvalho',   'registration' => '2026010'],
        ];

        $studentsAdministracao = [
            ['name' => 'Pedro Mendes',      'registration' => '2026011'],
            ['name' => 'Camila Barbosa',    'registration' => '2026012'],
            ['name' => 'Rodrigo Martins',   'registration' => '2026013'],
            ['name' => 'Larissa Cardoso',   'registration' => '2026014'],
            ['name' => 'Felipe Araújo',     'registration' => '2026015'],
            ['name' => 'Isabela Rocha',     'registration' => '2026016'],
            ['name' => 'Vinícius Gomes',    'registration' => '2026017'],
            ['name' => 'Natália Ribeiro',   'registration' => '2026018'],
            ['name' => 'Eduardo Sousa',     'registration' => '2026019'],
            ['name' => 'Carolina Silva',    'registration' => '2026020'],
        ];

        foreach ($studentsInformatica as $data) {
            Student::create(array_merge($data, [
                'classroom_id'    => $inforematica->id,
                'guardian_name'   => 'Responsável ' . explode(' ', $data['name'])[0],
                'guardian_phone'  => '(11) 9' . rand(1000, 9999) . '-' . rand(1000, 9999),
                'active'          => true,
            ]));
        }

        foreach ($studentsAdministracao as $data) {
            Student::create(array_merge($data, [
                'classroom_id'    => $administracao->id,
                'guardian_name'   => 'Responsável ' . explode(' ', $data['name'])[0],
                'guardian_phone'  => '(11) 9' . rand(1000, 9999) . '-' . rand(1000, 9999),
                'active'          => true,
            ]));
        }
    }
}
