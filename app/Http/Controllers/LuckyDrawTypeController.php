<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\LuckyDrawType;
use Yajra\DataTables\DataTables;

class LuckyDrawTypeController extends Controller
{
    protected function connection()
    {
        return new LuckyDrawType();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            return view('lucky_draw_types.index');
        }catch (\Exception $e) {
            Log::debug($e->getMessage());
            return redirect()
                ->intended(route("home"))
                ->with('error', 'Fail to load Data!');
        }
    }

    public function search_result(Request $request)
    {
        try{
            $lucky_draw_type_name=  (!empty($_GET["lucky_draw_type_name"])) ? ($_GET["lucky_draw_type_name"]) : ('');
            $lucky_draw_type_status=  (!empty($_GET["lucky_draw_type_status"])) ? ($_GET["lucky_draw_type_status"]) : ('');
            $result =  $this->connection();
            if($lucky_draw_type_name != ""){
                $result = $result->where('name', 'like', '%' . $lucky_draw_type_name . '%');
            }
            if($lucky_draw_type_status != ""){
                $result = $result->where('status', $lucky_draw_type_status);
            }
            $result = $result->get();
            return DataTables::of($result)
                ->addIndexColumn()
                ->make(true);
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            return redirect()
                ->intended(route("lucky_draw_types.index"))
                ->with('error', 'Fail to Search Lucky Draw Type!');
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('lucky_draw_types.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'name' => 'required',
            'description' => 'required',
            'status' => 'required',
        ]);
        $lucky_draw_type['uuid'] = (string) Str::uuid();
        $lucky_draw_type['name'] =  $request->name;
        $lucky_draw_type['description'] =  $request->description;
        $lucky_draw_type['status'] =  $request->status;
        $document = LuckyDrawType::create($lucky_draw_type);
        return redirect()->route('lucky_draw_types.index')
        ->with('success', 'Lucky Draw created successfully');
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LuckyDrawType  $luckyDrawType
     * @return \Illuminate\Http\Response
     */
    public function show(LuckyDrawType $luckyDrawType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LuckyDrawType  $luckyDrawType
     * @return \Illuminate\Http\Response
     */
    public function edit(LuckyDrawType $luckyDrawType)
    {
        // try {
            return view('lucky_draw_types.edit', compact('luckyDrawType'));
        // } catch (\Exception $e) {
        //     Log::debug($e->getMessage());
        //     return redirect()
        //         ->intended(route("users.index"))
        //         ->with('error', 'Fail to Load Edit Form!');
        // }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LuckyDrawType  $luckyDrawType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$uuid)
    {
        request()->validate([
            'name' => 'required',
            'description' => 'required',
            'status' => 'required',
        ]);
        $input['name'] =  $request->name;
        $input['description'] =  $request->description;
        $input['status'] =  $request->status;
        $lucky_draw_type = LuckyDrawType::find($uuid);
        $lucky_draw_type->update($input);
        return redirect()->route('lucky_draw_types.index')
        ->with('success', 'Lucky Draw updated successfully');
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LuckyDrawType  $luckyDrawType
     * @return \Illuminate\Http\Response
     */
    public function destroy($uuid)
    {
        try {
            LuckyDrawType::find($uuid)->delete();
            return response()->json([
                'success', 'Lucky Draw deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            return redirect()
                ->intended(route("lucky_draw_types.index"))
                ->with('error', 'Fail to delete Lucky Draw!');
        }
    }
}
