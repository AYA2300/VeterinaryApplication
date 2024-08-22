<?php

namespace App\Http\Controllers;

use App\Http\Requests\Add_CategoreyRequest;
use App\Http\Resources\Categorie_Resource;
use App\Models\AnimalCategorie;
use App\Services\Category\Categorey_Services;
use Illuminate\Http\Request;

class AnimalCategorieController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     public function __construct(protected Categorey_Services $category_service)
     {}


    public function index()
    {
       //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function add_categorey( Add_CategoreyRequest $request)
    {
        $input_data=$request->validate();
        $result=$this->category_service->add_categorey($input_data);
        $output=[];
        if ($result['status_code'] == 200) {
            $result_data = $result['data'];
            // response data preparation:
            $output['Category']= new Categorie_Resource($result_data['Category']);


    }
    return $this->send_response($output, $result['msg'], $result['status_code']);

    }

    /**
     * Display the specified resource.
     */
    public function show(AnimalCategorie $animalCategorie)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AnimalCategorie $animalCategorie)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AnimalCategorie $animalCategorie)
    {
        //
    }
}
