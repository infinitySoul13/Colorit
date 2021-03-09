<?php

namespace App\Console\Commands;

use App\Flag;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Product;
use Doctrine\DBAL\Driver\PDOException;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ProductsLoader extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:load';
//{filePath} {extension} {--queue=}

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Log::info("test");

//        $extension = $this->argument('extension');
//        $filePath = $this->argument('filePath');
        $file = Flag::where('imported','=','0')
            ->orderBy('created_at', 'DESC')
            ->first();

        if( $file['extension']  == 'csv') {
            $reader = IOFactory::createReader('Csv');
        }
        else{
            $reader = IOFactory::createReader('Xlsx');
        }
//        $reader = IOFactory::createReader('Xlsx');
        $reader->setReadDataOnly(TRUE);
        $spreadsheet = $reader->load($file['file_name']);
//        env('APP_URL').$spreadsheet = $reader->load("e:\\OpenServer\\OSPanel\\domains\\autodon1\\storage\\app\\public\\test.xlsx");

        $worksheet = $spreadsheet->getActiveSheet();


        foreach ($worksheet->getRowIterator() as $row) {

            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(FALSE);
            if ($row->getRowIndex() <= 2)
                continue;

            $data = array();
            $category = '';
            $img = '/assets/images/cat.png';
            foreach ($cellIterator as $cell) {
                switch ($cell->getColumn()) {
                    case "B":
                        try {
                            $data["brand"] = $cell->getValue();
//                            if (!Brand::where("title", $cell->getValue())->first())
//                                Brand::create([
//                                    'title' => $cell->getValue(),
//                                    'alias' => strtolower($cell->getValue())
//                                ]);
//
//                            $data["brand_id"] = Brand::where("title", $cell->getValue())->first()->id;
                        } catch (PDOException $e) {

                        }

                        break;
                    case "C":
                        $data["manufacturer_number"] = $cell->getValue();
                        break;
                    case "D":
                        $data["title"] = $cell->getValue();
                        $delimiters = array(" ","  ",",",".","|",":");
                        $ready = str_replace($delimiters, $delimiters[0], $cell->getValue());
                        $launch = explode($delimiters[0], $ready);
                        $category = 'Другое';
                        if (in_array("Масло", $launch) || in_array("масло", $launch) || in_array("масл", $launch)) {
                            if (in_array("трансмиссионное", $launch) || in_array("трансмис", $launch) || in_array("трансмиссион", $launch)|| in_array("трансм", $launch)|| in_array("трансмиccионное", $launch) ) {
                                $category = 'Масло трансмиссионное';
                                $img='/assets/images/cat_6.jpg';
                            }
                            if (in_array("моторное", $launch) || in_array("мот", $launch) || in_array("мотор", $launch) ) {
                                $category = 'Масло моторное';
                                $img='/assets/images/cat_8.jpg';
                            }

                        }
                        if (in_array("АКБ", $launch))
                        {
                            $category = 'АКБ';
                            $img='/assets/images/cat_9.jpg';
                        }
                        if (in_array("Щетки", $launch) || in_array("Щётки", $launch) || in_array("Щетка", $launch) || in_array("Щётка", $launch))
                        {
                            $category = 'Щетки';
//                            $img='cat_.jpg';
                        }
                        if (in_array("Лампа", $launch))
                        {
                            $category = 'Лампы';
                            $img='/assets/images/cat_10.jpg';
                        }
                        if (in_array("Жидкость", $launch))
                        {
                            $category = 'Жидкость';
                            $img='/assets/images/cat_3.jpg';
                        }
                        if (in_array("Дворники", $launch))
                        {
                            $category = 'Дворники';
//                            $img='cat_6.jpg';
                        }
                        if (in_array("Выхлоп", $launch))
                        {
                            $category = 'Выхлопы';
                            $img='/assets/images/cat_4.jpg';
                        }
                        if (in_array("Кузов", $launch))
                        {
                            $category = 'Кузовы';
                            $img='/assets/images/cat_7.jpg';
                        }
//                        $pieces = explode(" ", $cell->getValue());
//                        $category = $pieces[0];
//                        if ($pieces[0] == 'Масло') {
//                            if ($pieces[1] == 'трансмиссионное' || $pieces[1] == 'трансмис.' ) {
//                                $category = 'Масло трансмиссионное';
//                                $img='cat_6.jpg';
//                            } else {
//                                $category = 'Масло моторное';
//                                $img='cat_8.jpg';
//                            }
//                        }
//                        if ($pieces[3] == 'трансмиссионное')
//                        {
//                            $category = 'Масло трансмиссионное';
//                            $img='cat_6.jpg';
//                        }
//                        if ($pieces[3] == 'трансмиссионное')
//                        {
//                            $category = 'Масло трансмиссионное';
//                            $img='cat_6.jpg';
//                        }
//                        if ($pieces[0] == 'АКБ')
//                        {
//                            $category = 'АКБ';
//                            $img='cat_9.jpg';
//                        }
//                        if ($pieces[0] == 'Щетки' || $pieces[0] == 'Щётки' || $pieces[0] == 'Щетка' || $pieces[0] == 'Щётка') {
//                            $category = 'Щетки';
////                            $img='cat_.jpg';
//                        }
//                        if ($pieces[0] == 'Лампа') {
//                            $category = 'Лампы';
//                            $img='cat_10.jpg';
//                        }
//                        if ($pieces[0] == 'Жидкость')
//                        {
//                            $category = 'Жидкость';
//                            $img='cat_3.jpg';
//                        }
//                        if ($pieces[0] == 'Дворники')
//                        {
//                            $category = 'Дворники';
////                            $img='cat_6.jpg';
//                        }
//                        if ($pieces[0] == 'Выхлоп')
//                        {
//                            $category = 'Выхлопы';
//                            $img='cat_4.jpg';
//                        }
//                        if ($pieces[0] == 'Кузов')
//                        {
//                            $category = 'Кузовы';
//                            $img='cat_7.jpg';
//                        }
//                        if ($pieces[0] == '')
//                        {
//                            $category = '';
//                        }
                        break;
                    case "E":
                        $data["units"] = $cell->getValue();
                        break;
                    case "F":
                        $data["min_in_pack"] = $cell->getValue();
                        break;
                    case "G":
                        $data["original_number"] = $cell->getValue();
                        if($cell->getValue()==null) {
                            $data["original_number"] = '';
                        }
                        break;
                    case "H":
                        $data["quantity"] = $cell->getValue();
                        break;
                    case "J":
                        $data["price"] = $cell->getValue();
                        break;
                }
            }

            try {
                Product::create([
                    'title' => $data["title"],
                    'brand' => $data["brand"],
                    'price' => $data["price"],
                    'quantity' => $data["quantity"],
                    'manufacturer_number' => $data["manufacturer_number"],
                    'units' => $data["units"],
                    'original_number' => $data["original_number"],
                    'min_in_pack' => $data["min_in_pack"],
//                    'is_top' => 0,
//                    'is_new' => 0,
                    'img' => $img,
                    'description' => '',
                    'category' => $category,
                    'from_vk'=> false,
                    'is_active' => true
//                    'brand_id' => $data["brand_id"],
//                    'weight' => 0,
//                    'width' => 1,
//                    'height' => 1,
//                    'length' => 1
                ]);
                $file = $file->fresh(); //reload from the database
                $file['rows_imported'] = $file['rows_imported'] + 1;
                $file->save();
            } catch (PDOException $e) {

            }

        }
        $file['imported'] = true;
        $file->save();
    }
}
