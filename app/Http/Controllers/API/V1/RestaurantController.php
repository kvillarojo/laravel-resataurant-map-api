<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Restaurant as Resto;
use App\Model\Category;
use App\Model\Specialty;

class RestaurantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        try {

           $resto = Resto::join('category', 'category.id', '=', 'restaurant.category_id')
                       ->join('specialty', 'specialty.id', '=', 'restaurant.specialty_id')
                       ->select('restaurant.id', 'restaurant.name as name', 'restaurant.address', 'restaurant.latitude', 'restaurant.longitude', 'restaurant.daily_sales', 'restaurant.monthly_sales', 'specialty.name as specialty' , 'category.name as category', 'restaurant.open_hrs')
                       ->get();

           $categories = Category::all('id','name'); 
           $specialty = Specialty::all('id','name');
           return $arrayName = array('resto' => $resto, 'categories' => $categories, 'specialty' => $specialty);  

        } catch (Exception $e) {
            return $e;
        }
     
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // return  $request->lat; 

       try {
            $data = array(
                'name' => ucwords($request->name),
                'address' => ucwords($request->address),
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'monthly_sales' => $request->monthly_sales,
                'daily_sales' => $request->daily_sales,
                'specialty_id' => $request->specialty,
                'category_id' => $request->category,
                'open_hrs' => ucwords($request->open_hrs)
            );

            if (Resto::create($data)) {
                return 'Yeay!';
            }
        } catch (\Exception $e) {
            return $e;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
