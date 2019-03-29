<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Restaurant as Resto;

class RestaurantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $restaurantList = [
        //   ['name' => "Bon Appetit Restaurant", 'latitude' => 7.0826, 'longitude' => 125.6136, 'id'=> 0],
        //   ['name' => "Kaizen Davao Japanese Street Dining", 'latitude' => 7.0827, 'longitude' => 125.6115, 'id'=> 1],
        //   ['name' => "REKADO FILIPINO COMFORT CUISINE", 'latitude' => 7.0827, 'longitude' => 125.6115, 'id'=> 2],
        //   ['name' => "Nonki Japanese Restaurant", 'latitude' => 7.0817, 'longitude' => 125.6102, 'id'=> 3],
        //   ['name' => "Tiny Kitchen & Dulce Vida", 'latitude' => 7.0811, 'longitude' => 125.6097, 'id'=> 4],
        //   ['name' => "DIMYUM SEAFOOD RESTAURANT", 'latitude' => 7.0827, 'longitude' => 125.6144, 'id'=> 5],
        //   ['name' => "Ahfat Sea Foods Plaza", 'latitude' => 7.0884, 'longitude' => 125.6127, 'id'=> 6],
        //   ['name' => "Davao Dencia's Restaurant", 'latitude' => 7.0699, 'longitude' => 125.6069, 'id'=> 7],
        //   ['name' => "Bondi&Bourke Davao", 'latitude' => 7.0680, 'longitude' => 125.6062, 'id'=> 8],
        //   ['name' => "Taishozan", 'latitude' => 7.0827, 'longitude' => 125.614, 'id'=> 9]
        // ];
        
        // return $restaurantList;
        
         // $resto = Resto::all('id', 'name', 'address', 'latitude', 'longitude', 'daily_sales', 'monthly_sales', 'specialty', 'category', 'open_hrs');
         $resto = Resto::join('category', 'category.id', '=', 'restaurant.category_id')
                       ->join('specialty', 'specialty.id', '=', 'restaurant.specialty_id')
                       ->select('restaurant.id', 'restaurant.name as name', 'restaurant.address', 'restaurant.latitude', 'restaurant.longitude', 'restaurant.daily_sales', 'restaurant.monthly_sales', 'specialty.name as specialty' , 'category.name as category', 'restaurant.open_hrs')
                       ->get();
         return $resto;       
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
       try {
            $data = array(
                'name' => ucwords($request->name),
                'address' => ucwords($request->address),
                'latitude' => ucwords($request->lat),
                'longitude' => ucwords($request->long),
                'monthly_sales' => ucwords($request->monthly_sales),
                'daily_sales' => ucwords($request->daily_sales),
                'specialty' => ucwords($request->specialty),
                'category' => ucwords($request->category),
                'open_hrs' => ucwords($request->open_hrs)
            );

            if (Resto::create($data)) {
                return redirect('/')->with('success', 'Post Created');
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(array('error' => $e->getMessage()))->withInput();
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
