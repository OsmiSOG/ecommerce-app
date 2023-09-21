<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Enums\CategoryType;
use App\Models\Commerce\Category;
use App\Models\Commerce\Subcategory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->productCategories();
        $this->serviceCategories();
    }

    private function productCategories() {
        $category = Category::factory()->create([
            'name' => 'Mujer',
            'type' => CategoryType::Product->name
        ]);

        $category->subcategories()->createMany([
            [ 'name' => 'Ropa' ],
            [ 'name' => 'Ropa Interior y Pijamas' ],
            [ 'name' => 'Accesorios' ],
            [ 'name' => 'Ropa Deportiva' ],
            [ 'name' => 'Vestidos de Baño' ],
            [ 'name' => 'Zapatos' ],
        ]);

        $category = Category::factory()->create([
            'name' => 'Hombre',
            'type' => CategoryType::Product->name
        ]);

        $category->subcategories()->createMany([
            [ 'name' => 'Ropa' ],
            [ 'name' => 'Ropa Deportiva' ],
            [ 'name' => 'Accesorios' ],
            [ 'name' => 'Zapatos' ],
            [ 'name' => 'Ropa Interior y Pijamas' ],
        ]);

        $category = Category::factory()->create([
            'name' => 'Tecnología',
            'type' => CategoryType::Product->name
        ]);

        $category->subcategories()->createMany([
            [ 'name' => 'Celulares' ],
            [ 'name' => 'Computación' ],
            [ 'name' => 'TV y Video' ],
            [ 'name' => 'Smartwatch y Accesorios' ],
            [ 'name' => 'Fotografía' ],
            [ 'name' => 'Zona Gamer' ],
            [ 'name' => 'Audio' ],
            [ 'name' => 'Consolas' ],
            [ 'name' => 'Electromovilidad' ],
            [ 'name' => 'Drones' ],
            [ 'name' => 'Hogar Inteligente' ],
        ]);

        $category = Category::factory()->create([
            'name' => 'Electrohogar',
            'type' => CategoryType::Product->name
        ]);

        $category->subcategories()->createMany([
            [ 'name' => 'Refrigeración' ],
            [ 'name' => 'Climatización' ],
            [ 'name' => 'Lavado y Planchado' ],
            [ 'name' => 'Cocina' ],
            [ 'name' => 'Aspirado y Limpieza' ],
            [ 'name' => 'Cuidado Personal' ],
        ]);

        $category = Category::factory()->create([
            'name' => 'Muebles y Organización',
            'type' => CategoryType::Product->name
        ]);

        $category->subcategories()->createMany([
            [ 'name' => 'Sala' ],
            [ 'name' => 'Dormitorio' ],
            [ 'name' => 'Oficina y Escritorio' ],
            [ 'name' => 'Organización y Almacenamiento' ],
            [ 'name' => 'Comedor' ],
            [ 'name' => 'Exterior y Terraza' ],
        ]);

        $category = Category::factory()->create([
            'name' => 'Dormitorio',
            'type' => CategoryType::Product->name
        ]);

        $category->subcategories()->createMany([
            [ 'name' => 'Colchones' ],
            [ 'name' => 'Muebles Dormitorio' ],
            [ 'name' => 'Ropa de Cama' ],
            [ 'name' => 'Decoración' ],
        ]);

        $category = Category::factory()->create([
            'name' => 'Herramientas y Maquinaria',
            'type' => CategoryType::Product->name
        ]);

        $category->subcategories()->createMany([
            [ 'name' => 'Herramientas Electricas' ],
            [ 'name' => 'Herramientas Manuales' ],
            [ 'name' => 'Organización' ],
            [ 'name' => 'Maquinas y complementos' ],
            [ 'name' => 'Medición y Trazado' ],
            [ 'name' => 'Jardin' ],
        ]);

        $category = Category::factory()->create([
            'name' => 'Tenis y Zapatos',
            'type' => CategoryType::Product->name
        ]);
        $category->subcategories()->createMany([
            [ 'name' => 'Tenis' ],
            [ 'name' => 'Tenis Mujer' ],
            [ 'name' => 'Tenis Hombre' ],
            [ 'name' => 'Tenis Infantil' ],
            [ 'name' => 'Zapatos Mujer' ],
            [ 'name' => 'Zapatos Niña' ],
            [ 'name' => 'Zapatos Hombre' ],
            [ 'name' => 'Zapatos Niño' ],
        ]);

        $category = Category::factory()->create([
            'name' => 'Bebé',
            'type' => CategoryType::Product->name
        ]);
        $category->subcategories()->createMany([
            [ 'name' => 'Andaderas y Entretenimiento' ],
            [ 'name' => 'Baño bebe' ],
            [ 'name' => 'Coches y Sillas' ],
            [ 'name' => 'Habitación del bebe' ],
            [ 'name' => 'Higiene y Salud' ],
            [ 'name' => 'Lactancia y alimentación' ],
            [ 'name' => 'Ropa bebé' ],
        ]);

        $category = Category::factory()->create([
            'name' => 'Mascotas',
            'type' => CategoryType::Product->name
        ]);
        $category->subcategories()->createMany([
            [ 'name' => 'Perros' ],
            [ 'name' => 'Gatos' ],
            [ 'name' => 'Otros Animales' ],
        ]);

        $category = Category::factory()->create([
            'name' => 'Deportes y Aire Libre',
            'type' => CategoryType::Product->name
        ]);
        $category->subcategories()->createMany([
            [ 'name' => 'Ropa deportiva mujer' ],
            [ 'name' => 'Ropa deportiva hombre' ],
            [ 'name' => 'Ejercicio y fitness' ],
            [ 'name' => 'Disciplina deportiva' ],
            [ 'name' => 'Ciclismo' ],
            [ 'name' => 'Camping y aire libre' ],
            [ 'name' => 'Accesorios deportivos' ],
        ]);

        $category = Category::factory()->create([
            'name' => 'Jardin y Terraza',
            'type' => CategoryType::Product->name,
        ]);
        $category->subcategories()->createMany([
            [ 'name' => 'Muebles de terraza' ],
            [ 'name' => 'Asadores, Parrilla y BBQ' ],
            [ 'name' => 'Jardín' ],
            [ 'name' => 'Piscina' ],
        ]);

        $category = Category::factory()->create([
            'name' => 'Cocina y Menaje',
            'type' => CategoryType::Product->name
        ]);
        $category->subcategories()->createMany([
            [ 'name' => 'Cocina' ],
            [ 'name' => 'Refrigeración' ],
            [ 'name' => 'Plomeria y Fontaneria' ],
            [ 'name' => 'Electrodomesticos de Cocina' ],
            [ 'name' => 'Muebles de Cocina' ],
            [ 'name' => 'Menaje de comedor' ],
        ]);

        $category = Category::factory()->create([
            'name' => 'Ferreteria y Construcción',
            'type' => CategoryType::Product->name
        ]);
        $category->subcategories()->createMany([
            [ 'name' => 'Materiales de construcción' ],
            [ 'name' => 'Baños y plomeria' ],
            [ 'name' => 'Herramienta y maquinas' ],
            [ 'name' => 'Tornillos clavos y adhesivos' ],
            [ 'name' => 'Techos y aislantes' ],
            [ 'name' => 'Seguridad' ],
            [ 'name' => 'Electricidad' ],
            [ 'name' => 'Cerraduras y herrajes' ],
        ]);

        $category = Category::factory()->create([
            'name' => 'Belleza y Salud',
            'type' => CategoryType::Product->name
        ]);
        $category->subcategories()->createMany([
            [ 'name' => 'Dermocosmetica' ],
            [ 'name' => 'Perfumes' ],
            [ 'name' => 'Cuidado Personal' ],
            [ 'name' => 'Cuidado de la piel' ],
            [ 'name' => 'Cuidado Capilar' ],
            [ 'name' => 'Manos y Pies' ],
            [ 'name' => 'Maquillaje' ],
            [ 'name' => 'Salud y Bienestar' ],
        ]);

        $category = Category::factory()->create([
            'name' => 'Niños y Juguetes',
            'type' => CategoryType::Product->name
        ]);
        $category->subcategories()->createMany([
            [ 'name' => 'Juguetes' ],
            [ 'name' => 'Jugos de exterior' ],
            [ 'name' => 'Moda Infantil' ],
            [ 'name' => 'Disfraces' ],
            [ 'name' => 'Escolar' ],
        ]);

        $category = Category::factory()->create([
            'name' => 'Supermercado',
            'type' => CategoryType::Product->name
        ]);
        $category->subcategories()->createMany([
            [ 'name' => 'Bebidas y Licores' ],
            [ 'name' => 'Despensa' ],
            [ 'name' => 'Desayunos' ],
            [ 'name' => 'Dulces y Snacks' ],
            [ 'name' => 'Aseo y Limpieza' ],
            [ 'name' => 'Farmacias' ],
        ]);

        $category = Category::factory()->create([
            'name' => 'Automotriz',
            'type' => CategoryType::Product->name
        ]);
        $category->subcategories()->createMany([
            [ 'name' => 'Llantas y rines' ],
            [ 'name' => 'Motos' ],
            [ 'name' => 'Limpieza automotriz' ],
            [ 'name' => 'Accesorio e Interior' ],
            [ 'name' => 'Accesorios Exterior' ],
            [ 'name' => 'Herramienta mecanica' ],
            [ 'name' => 'Seguridad para autos' ],
        ]);

        $category = Category::factory()->create([
            'name' => 'Baño',
            'type' => CategoryType::Product->name
        ]);
        $category->subcategories()->createMany([
            [ 'name' => 'Sanitario y Lavamanos' ],
            [ 'name' => 'Griferia para baño' ],
            [ 'name' => 'Duchas y Cabinas' ],
            [ 'name' => 'Complementos de baño' ],
            [ 'name' => 'Muebles de baño' ],
        ]);

        $category = Category::factory()->create([
            'name' => 'Libros, Papeleria y Música',
            'type' => CategoryType::Product->name
        ]);
        $category->subcategories()->createMany([
            [ 'name' => 'Libros' ],
            [ 'name' => 'Literatura y novelas' ],
            [ 'name' => 'Instrumentos musicales' ],
            [ 'name' => 'Papeleria y utiles escolares' ],
        ]);
    }

    private function serviceCategories() {
        $category = Category::factory()->create([
            'name' => 'Streaming y Entretenimiento',
            'type' => CategoryType::Product->name
        ]);
        $category->subcategories()->createMany([
            [ 'name' => 'Musica' ],
            [ 'name' => 'Peliculas' ],
            [ 'name' => 'Series' ],
            [ 'name' => 'TV' ],
            [ 'name' => 'Deportes' ],
            [ 'name' => 'Podcasts' ],
        ]);

        $category = Category::factory()->create([
            'name' => 'Salud y Deporte',
            'type' => CategoryType::Product->name
        ]);
        $category->subcategories()->createMany([
            [ 'name' => 'Gimnasio' ],
            [ 'name' => 'Yoga' ],
            [ 'name' => 'Karate' ],
            [ 'name' => 'Entrenamientos' ],
            [ 'name' => 'Zonas Recreativas' ],
        ]);

        $category = Category::factory()->create([
            'name' => 'Educación',
            'type' => CategoryType::Product->name
        ]);
        $category->subcategories()->createMany([
            [ 'name' => 'Cursos Financieros' ],
            [ 'name' => 'Cursos Online' ],
            [ 'name' => 'Cursos Presenciales' ],
            [ 'name' => 'Cursos Desarrollo' ],
            [ 'name' => 'Cursos' ],
        ]);

    }
}
