<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnidadesServicoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('unidades_servico')->insert(['id'   => 1 ,'nome' => 'Pronto-socorro']);
        DB::table('unidades_servico')->insert(['id'   => 2 ,'nome' => 'Ambulatório']);
        DB::table('unidades_servico')->insert(['id'   => 3 ,'nome' => 'Centro cirúrgico']);
        DB::table('unidades_servico')->insert(['id'   => 4 ,'nome' => 'Centro obstétrico']);
        DB::table('unidades_servico')->insert(['id'   => 5 ,'nome' => 'UTI']);
        DB::table('unidades_servico')->insert(['id'   => 6 ,'nome' => 'Outro setor ou serviço de assistência direta ao paciente']);
        DB::table('unidades_servico')->insert(['id'   => 7 ,'nome' => 'Laboratório clínico']);
        DB::table('unidades_servico')->insert(['id'   => 8 ,'nome' => 'Diagnóstico por imagem']);
        DB::table('unidades_servico')->insert(['id'   => 9 ,'nome' => 'Hemodinâmica ou cardiologia intervencionista']);
        DB::table('unidades_servico')->insert(['id'   => 10 ,'nome' => 'Nefrologia ou terapia renal substitutiva']);
        DB::table('unidades_servico')->insert(['id'   => 11 ,'nome' => 'Outro setor ou serviço de apoio diagnóstico e terapêutico']);
        DB::table('unidades_servico')->insert(['id'   => 12 ,'nome' => 'Triagem e acolhimento']);
        DB::table('unidades_servico')->insert(['id'   => 13 ,'nome' => 'Acolhimento psicossocial']);
        DB::table('unidades_servico')->insert(['id'   => 14 ,'nome' => 'Alimentação e assistência nutricional e dietética']);
        DB::table('unidades_servico')->insert(['id'   => 15 ,'nome' => 'Farmácia e assistência farmacêutica']);
        DB::table('unidades_servico')->insert(['id'   => 16 ,'nome' => 'Fisioterapia, fonoaudiologia e terapia ocupacional']);
        DB::table('unidades_servico')->insert(['id'   => 17 ,'nome' => 'Esterilização de materiais']);
        DB::table('unidades_servico')->insert(['id'   => 18 ,'nome' => 'Arquivo médico e estatística']);
        DB::table('unidades_servico')->insert(['id'   => 19 ,'nome' => 'Epidemiologia, vigilância epidemiológica e registro de óbito']);
        DB::table('unidades_servico')->insert(['id'   => 20 ,'nome' => 'Outro setor ou serviço de apoio técnico']);
        DB::table('unidades_servico')->insert(['id'   => 21 ,'nome' => 'Gestão estratégica']);
        DB::table('unidades_servico')->insert(['id'   => 22 ,'nome' => 'Regulação, contas hospitalares e gestão de riscos']);
        DB::table('unidades_servico')->insert(['id'   => 23 ,'nome' => 'Gestão de pessoas']);
        DB::table('unidades_servico')->insert(['id'   => 24 ,'nome' => 'Formação ou qualificação profissional']);
        DB::table('unidades_servico')->insert(['id'   => 25 ,'nome' => 'Estágio profissional']);
        DB::table('unidades_servico')->insert(['id'   => 26 ,'nome' => 'Residência médica ou multiprofissional']);
        DB::table('unidades_servico')->insert(['id'   => 27 ,'nome' => 'Saúde e segurança do trabalho']);
        DB::table('unidades_servico')->insert(['id'   => 28 ,'nome' => 'Apoio administrativo']);
        DB::table('unidades_servico')->insert(['id'   => 29 ,'nome' => 'Higiene e limpeza']);
        DB::table('unidades_servico')->insert(['id'   => 30 ,'nome' => 'Transporte e segurança']);
        DB::table('unidades_servico')->insert(['id'   => 31 ,'nome' => 'Materiais e suprimentos']);
        DB::table('unidades_servico')->insert(['id'   => 32 ,'nome' => 'Manutenção e reparos']);
        DB::table('unidades_servico')->insert(['id'   => 33 ,'nome' => 'Outro setor ou serviço de apoio administrativo']);
    }
}