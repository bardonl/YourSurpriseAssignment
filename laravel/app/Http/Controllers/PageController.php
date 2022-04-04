<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use App\Models\Road;
Use App\Models\RoadProperty;

class PageController extends Controller
{

    public function get()
    {
        $last_updated = Road::max('created_at');

        $data = [
            'jams' => ['name' => 'Files', 'data' => ''],
            'roadworks' => ['name' => 'Wegwerkzaamheden', 'data' => ''],
            'radars' => ['name' => 'Snelheidscontroles', 'data' => '']
        ];

        foreach($data as $key => $value){
            $data[$key]['data'] = RoadProperty::where('created_at',$last_updated)->where('category',$key)->count('category',$key);
        }

        return view('index',['last_updated'=> $last_updated, 'info' => $data]);

    }
}
