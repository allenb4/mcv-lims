<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Test;
use App\Models\Culture;
use App\Models\Package;
use App\Http\Controllers\Api\Response;

class TestsLibraryController extends Controller
{
    public function tests(Request $request)
    {
        $tests=Test::where(function($query){
            return $query->where('parent_id',0)
                         ->orWhere('separated',true);
        });
        
        if($request->has('query'))
        {
           $tests->where('name','like','%'.$request['query'].'%');
        }

        $tests=$tests->take(10)->get();

        return Response::response(200,'success',['tests'=>$tests]);
    }

    public function cultures(Request $request)
    {
        $cultures=Culture::query();

        if($request->has('query'))
        {
            $cultures->where('name','like','%'.$request['query'].'%');
        }

        $cultures=$cultures->take(10)->get();

        return Response::response(200,'success',['cultures'=>$cultures]);
    }

    public function packages(Request $request)
    {
        $packages=Package::query();

        if($request->has('query'))
        {
            $packages->where('name','like','%'.$request['query'].'%');
        }

        $packages=$packages->take(10)->get();

        return Response::response(200,'success',['packages'=>$packages]);
    }
}
