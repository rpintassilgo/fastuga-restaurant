<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductsSeeder extends Seeder
{
    private $productsPath = 'public/products';

    private $contadorGlobal = 0;
    private $totaProducts = 60;
    private $faker = null;

    /* Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->line("");
        $this->command->line("##################################################################################");
        $this->command->line("Creating Products");
        $this->command->line("##################################################################################");

        Storage::deleteDirectory($this->productsPath);
        Storage::makeDirectory($this->productsPath);

        $this->faker = \Faker\Factory::create('pt_PT');

        $this->addProduct($this->faker, '7-up.jpg', '7-Up', 'drink', 1.4);
        $this->addProduct($this->faker, 'agua-das-pedras.jpg', 'Água das Pedras', 'drink', 1.4);
        $this->addProduct($this->faker, 'agua.jpg', 'Água do Luso', 'drink', 1.0);
        $this->addProduct($this->faker, 'aletria.jpeg', 'Aletria', 'dessert', 3.4, true);
        $this->addProduct($this->faker, 'alheira.jpg', 'Alheira', 'hot dish', 9.9);
        $this->addProduct($this->faker, 'arroz-de-marisco.jpg', 'Arroz de Marisco', 'hot dish', 19.9);
        $this->addProduct($this->faker, 'arroz-doce.jpg', 'Arroz Doce', 'dessert', 3.1);
        $this->addProduct($this->faker, 'bacalao-a-casa.jpg', 'Bacalhau à Casa', 'hot dish', 12.9);
        $this->addProduct($this->faker, 'bacalhau-a-braz.jpg', 'Bacalhau à Braz', 'hot dish', 9.0);
        $this->addProduct($this->faker, 'bacalhau-gomes-sa.jpg', 'Bacalhau à Gomes de Sá', 'hot dish', 8.9);
        $this->addProduct($this->faker, 'batatas-fritas.jpg', 'Batatas Fritas', 'hot dish', 2.5);
        $this->addProduct($this->faker, 'bife-vaca-grelhado.jpg', 'Bife de Vaca Grelhado', 'hot dish', 14.5);
        $this->addProduct($this->faker, 'bitoque.jpg', 'Bitoque', 'hot dish', 9.5);
        $this->addProduct($this->faker, 'bobo-camarao.jpg', 'Bobó de Camarão', 'hot dish', 18.9);
        $this->addProduct($this->faker, 'caldeirada-frutos-do-mar.jpg', 'Caldeirada de Frutos do Mar', 'hot dish', 21.5);
        $this->addProduct($this->faker, 'caldo-verde.jpg', 'Caldo Verde', 'hot dish', 2.9);
        $this->addProduct($this->faker, 'calzone.jpg', 'Calzone', 'hot dish', 14.3);
        $this->addProduct($this->faker, 'Carne-de-Porco-alentejana.jpg', 'Carne de Porco à Alentejana', 'hot dish', 9.0);
        $this->addProduct($this->faker, 'cavala-grelhada.jpg', 'Cavala Grelhada', 'hot dish', 8.0, true);
        $this->addProduct($this->faker, 'cerveja-sagres.jpg', 'Cerveja Sagres', 'drink', 1.8);
        $this->addProduct($this->faker, 'coca-cola.jpg', 'Coca-Cola', 'drink', 1.4);
        $this->addProduct($this->faker, 'compal-pera.jpg', 'Compal de Pera', 'drink', 1.4);
        $this->addProduct($this->faker, 'compal-pessego.jpg', 'Compal de Pêssego', 'drink', 1.4);
        $this->addProduct($this->faker, 'doca-casa.jpg', 'Doce da Casa', 'dessert', 3.9);
        $this->addProduct($this->faker, 'espaguete-carbonara.jpg', 'Esparguete à Carbonara', 'hot dish', 11.2);
        $this->addProduct($this->faker, 'Espargete-alho.jpg', 'Esparguete de Alho', 'hot dish', 3.9, true);
        $this->addProduct($this->faker, 'esparguete-bolonhesa.jpg', 'Esparguete à Bolonhesa', 'hot dish', 8.0);
        $this->addProduct($this->faker, 'fanta.jpg', 'Fanta', 'drink', 1.4);
        $this->addProduct($this->faker, 'ide-tea.jpg', 'Ice-Tea', 'drink', 1.4);
        $this->addProduct($this->faker, 'jardineira-de-vaca.jpg', 'Jardineira de Vaca', 'hot dish', 7.9);
        $this->addProduct($this->faker, 'lasanha_a_bolonhesa.jpg', 'Lasanha à Bolonhesa', 'hot dish', 9.2);
        $this->addProduct($this->faker, 'lasanha-peixe.jpg', 'Lasanha de Peixe', 'hot dish', 12.4);
        $this->addProduct($this->faker, 'lulas-grelhadas.jpg', 'Lulas Grelhadas', 'hot dish', 11.9);
        $this->addProduct($this->faker, 'lulas-recheadas.jpg', 'Lulas Recheadas', 'hot dish', 9.1);
        $this->addProduct($this->faker, 'melao.jpg', 'Melão', 'dessert', 2.5);
        $this->addProduct($this->faker, 'mousse-chocolate.jpg', 'Mousse de Chocolate', 'dessert', 3.9);
        $this->addProduct($this->faker, 'naco-vaca.jpg', 'Naco de Vaca', 'hot dish', 19.0);
        $this->addProduct($this->faker, 'perca-grelhada.jpg', 'Perca Grelhada', 'hot dish', 17.9);
        $this->addProduct($this->faker, 'pizza.jpg', 'Pizza', 'hot dish', 6.8);
        $this->addProduct($this->faker, 'polvo-a-lagareiro.jpg', 'Polvo à Lagareiro', 'hot dish', 17.5);
        $this->addProduct($this->faker, 'pudim-ovos.jpg', 'Pudim de Ovos', 'dessert', 3.9);
        $this->addProduct($this->faker, 'robalo-grelhado.jpg', 'Robalo Grelhado', 'hot dish', 15.9);
        $this->addProduct($this->faker, 'rosbife.jpg', 'Rosbife', 'hot dish', 16.5);
        $this->addProduct($this->faker, 'salada-de-frutas.jpg', 'Salada de Frutas', 'dessert', 2.5);
        $this->addProduct($this->faker, 'salmao-grelhado.jpg', 'Salmão Grelhado', 'hot dish', 13.3);
        $this->addProduct($this->faker, 'sopa-de-legumes.jpg', 'Sopa de Legumes', 'hot dish', 2.5);
        $this->addProduct($this->faker, 'sopa-peixe.jpg', 'Sopa de Peixe', 'hot dish', 3.9);
        $this->addProduct($this->faker, 'strogonoff.jpg', 'Strogonoff', 'hot dish', 12.2);
        $this->addProduct($this->faker, 'sumo-ananas.jpg', 'Sumo de Ananás', 'drink', 2.0);
        $this->addProduct($this->faker, 'tigelada.jpg', 'Tigelada', 'dessert', 3.4);
        $this->addProduct($this->faker, 'vinho-branco-casa.jpg', 'Vinho Branco da Casa', 'drink', 2.5);
        $this->addProduct($this->faker, 'vinho-branco-conde-ervideira.jpg', 'Vinho Branco Conde de Ervideira', 'drink', 18.3);
        $this->addProduct($this->faker, 'vinho-branco-periquita.jpg', 'Vinho Branco Piriquita', 'drink', 11.1);
        $this->addProduct($this->faker, 'vinho-tinto-borba.jpg', 'Vinho Tinto Borba', 'drink', 11.1);
        $this->addProduct($this->faker, 'vinho-tinto-cartucha.jpg', 'Vinho Tinto Cartucha', 'drink', 14.5);
        $this->addProduct($this->faker, 'vinho-tinto-casa.jpg', 'Vinho Tinto da Casa', 'drink', 2.5);
        $this->addProduct($this->faker, 'vinho-tinto-dao.jpg', 'Vinho Tinto do Dão', 'drink', 8.0);
        $this->addProduct($this->faker, 'vinho-tinto-douro.jpg', 'Vinho Tinto do Douro', 'drink', 9.5);
        $this->addProduct($this->faker, 'vinho-verde-messias.jpg', 'Vinho Tinto Messias', 'drink', 7.8);
        $this->addProduct($this->faker, 'salada-fria.jpg', 'Salada Fria', 'cold dish', 5.4);
        $this->addProduct($this->faker, 'salada_ervilhas.jpg', 'Salada de Ervilhas', 'cold dish', 3.9);
        $this->addProduct($this->faker, 'salada_frango_com_frutas.jpg', 'Salada de Frango com Frutas', 'cold dish', 4.2);
        $this->addProduct($this->faker, 'salada_quinoa.jpg', 'Salada de Quinoa', 'cold dish', 8.9);
        $this->addProduct($this->faker, 'lasanha_vegan.jpg', 'Lasanha Vegan', 'cold dish', 6.9);
        $this->addProduct($this->faker, 'salada_thai.jpg', 'Salada Thai', 'cold dish', 8.9);
        $this->addProduct($this->faker, 'salmao_sashimi.jpg', 'Salmão Sashimi', 'cold dish', 11.9);
        $this->addProduct($this->faker, 'salada_camarao.jpg', 'Salada de Camarão', 'cold dish', 10.2);
        $this->addProduct($this->faker, 'salada_brocolos_amendoa.jpg', 'Salda de Bróculos com Amêndoa', 'cold dish', 9.9);
        $this->addProduct($this->faker, 'salada_fria_frango.jpg', 'Salada Fria de Frango', 'cold dish', 4.0);
        $this->addProduct($this->faker, 'salada_noodles.jpg', 'Salada decNoodles', 'cold dish', 3.8);
    }

    private function copyProfilePhoto($filename)
    {
        $targetDir = storage_path('app/' . $this->productsPath);

        $newFileName = Str::random(16) . ".jpg";

        File::copy(database_path('seeders/products_photos') . "/$filename", $targetDir . '/' . $newFileName);
        return $newFileName;
    }


    private function addProduct(\Faker\Generator $faker, $filename, $name, $type, $price, $softDelete = false)
    {
        $newFileName = $this->copyProfilePhoto($filename);
        $createdAt = Carbon::now()->subDays(600);
        $product = [
            'name' => $name,
            'type' => $type,
            'description' => $faker->realText(200),
            'photo_url' => $newFileName,
            'price' => $price,
            'created_at' => $createdAt,
            'updated_at' => $faker->dateTimeBetween($createdAt),
            'deleted_at' => $softDelete ? $this->faker->dateTimeBetween($createdAt) : null,
        ];
        $this->contadorGlobal++;
        DB::table('products')->insert($product);
        $this->command->info("Created Product {$this->contadorGlobal}/{$this->totaProducts}: " . $product['name']);
    }
}
