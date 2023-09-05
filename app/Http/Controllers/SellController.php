<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pos;

class SellController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $breadcrumbs = [
              ['name' => 'Sell']
        ];

        $posSellLists = Pos::all();

        // return $posLists;
        return view('sell.index', compact('breadcrumbs', 'posSellLists'));
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $breadcrumbs = [
            ['name' => 'Sell', 'url' => route('sell.index')],
            ['name' => 'Detail']
        ];

        $sellDetailLists = Pos::join('pos_items', 'pos_items.pos_id', '=', 'pos.id')
        ->join('variations', 'variations.id', 'pos_items.variation_id')
        ->join('products', 'products.id', 'variations.product_id')
        ->where('pos.id', '=', $id)
        ->get();
        // return $sellDetailLists;
        // return $sellDetailLists[0]->invoice_no;

        return view('sell.show', compact('breadcrumbs', 'sellDetailLists'));
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
