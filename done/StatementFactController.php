<?php

namespace App\Http\Controllers;

use App\StatementFact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StatementFactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    
        $error = false;
        if(count($request->input('item_value')) > 0 ){
            
            foreach($request->input('item_value') as $key => $val) {
                if(!empty($request->input("item_value.$key"))) {

                        $date      =    Carbon::parse($request->input("date.$key")); //'2019-01-01';
                        $statement =    StatementFact::updateOrCreate(
                                    ['statement_item_id' => $request->input("statement_item_id.$key"),
                                     'company_id'        => $request->input("company_id"),
                                     'date'              => $date->format('Y-m-d')
                                    ],
                                    ['item_value'        => $request->input("item_value.$key")]
                        );

                }
            }
            return response()->json(['status'=>200,'mesg'=>'Setting Update Success']);
       } else {
            return response()->json(['status'=>400,'mesg'=>'Failed!']);
       } 
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\StatementFact  $statementFact
     * @return \Illuminate\Http\Response
     */
    public function show(StatementFact $statementFact)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\StatementFact  $statementFact
     * @return \Illuminate\Http\Response
     */
    public function edit(StatementFact $statementFact, $companyid)
    {
        //
        $items = DB::table('statement_facts')->where('company_id', $companyid)->get();
       
        return response()->json([
            'fact_values'=> $items
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\StatementFact  $statementFact
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StatementFact $statementFact)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\StatementFact  $statementFact
     * @return \Illuminate\Http\Response
     */
    public function destroy(StatementFact $statementFact)
    {
        //
    }
}
