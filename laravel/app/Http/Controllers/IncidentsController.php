<?php

namespace App\Http\Controllers;

use App\Models\IncidentBound;
use App\Models\IncidentProperty;
use Illuminate\Http\Request;
use App\Models\Road;
use App\Models\RoadProperty;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;

class IncidentsController extends Controller
{
    public function getIncidents(Request $request)
    {
        $roads = [];

        $rules = [
            'category' => 'required|min:0|max:15'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = (array)$validator->messages();
            $error = $messages[array_key_first($messages)];
            return response($error,400);

        } else {
            $created_at = Road::max('created_at');

            if($request['category'] === 'all'){
                $categories = RoadProperty::distinct()->select('category')->get();
                $categories = $categories->toArray();
                if($categories){
                    foreach($categories as $key => $value){
                        $roads[$value['category']] = $this->getData($value['category'],$created_at);
                    }
                }
            } else {
                $category = RoadProperty::where('category',$request['category'])->select('category')->first();
                $category = $category->toArray();

                if($category){
                    $roads = $this->getData($category['category'],$created_at);
                }
            }

            if($roads){
                return response()->json($roads);
            } else {
                return response('Undefined category',400);
            }

        }
    }

    private function getData($category,$created_at)
    {

        if($category){
            $roads = DB::table('roads')
            ->join('road_properties', 'roads.id','=','road_properties.road_id')
            ->where('roads.created_at',$created_at)
            ->where('road_properties.category',$category)
            ->distinct()
            ->select('roads.*')->get();

            $roads = $roads->toArray();

            foreach($roads as &$road){
                $road = json_decode(json_encode($road), true);
                $roadPropterties = RoadProperty::where('road_id',$road['id'])->where('category', $category)->where('created_at',$created_at)->get();
                $segments = $roadPropterties->toArray();

                foreach($segments as &$segment){
                    $incidentProperties = IncidentProperty::where('rp_id',$segment['id'])->get();
                    $segment['incident_properties'] = $incidentProperties->toArray();
                    foreach($segment['incident_properties'] as &$incidentProperty){

                        $incidentBounds = DB::table('incident_bounds')
                        ->select(DB::raw('`key`,ST_X(`lon_lat`) as lat, ST_Y(`lon_lat`) as lon'))
                        ->where('ip_id',$incidentProperty['id'])
                        ->where('key', '!=', 'from_loc')
                        ->where('key', '!=','to_loc')
                        ->get();

                        if($incidentBounds && count($incidentBounds) > 1){
                            $incidentProperty['bounds'][] = $this->getCenter(json_decode(json_encode($incidentBounds), true));
                        } elseif ($incidentBounds) {
                            $incidentProperty['bounds'] = json_decode(json_encode($incidentBounds), true);
                        } else {
                            continue;
                        }
                    }
                }

                $road['segments'] = $segments;
            }

            return $roads;
        }
    }

    private function getCenter($coords)
    {
        $count_coords = count($coords);
        $xcos=0.0;
        $ycos=0.0;
        $zsin=0.0;

            foreach ($coords as $lnglat)
            {
                $lat = $lnglat['lat'] * pi() / 180;
                $lon = $lnglat['lon'] * pi() / 180;

                $acos = cos($lat) * cos($lon);
                $bcos = cos($lat) * sin($lon);
                $csin = sin($lat);
                $xcos += $acos;
                $ycos += $bcos;
                $zsin += $csin;
            }

        $xcos /= $count_coords;
        $ycos /= $count_coords;
        $zsin /= $count_coords;
        $lon = atan2($ycos, $xcos);
        $sqrt = sqrt($xcos * $xcos + $ycos * $ycos);
        $lat = atan2($zsin, $sqrt);

        return ['lat' => $lat * 180 / pi(), 'lon' => $lon * 180 / pi()];
    }

}


