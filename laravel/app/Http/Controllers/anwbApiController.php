<?php

namespace App\Http\Controllers;

use App\Models\IncidentBound;
use App\Models\IncidentProperty;
use App\Models\Road;
use App\Models\RoadEvent;
use App\Models\RoadProperty;
use Dflydev\DotAccessData\Data;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;
use mysql_xdevapi\Warning;

class anwbApiController extends Controller
{

    /**
     * Get data from API endpoint
     */
    public function getData()
    {
        $ch = curl_init(env('API_URL') . '?apikey=' . env('API_KEY'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_CAINFO, $_SERVER['DOCUMENT_ROOT'] . "/../cacert.pem");

        $response = curl_exec($ch);
        $httpResponse = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($httpResponse >= 400 || $httpResponse < 200) {
            Log::error('API responded with an unexpected HTTP status',['status' => $httpResponse, 'rs' => json_decode($response, true)]);
        } else {
            $this->processData(json_decode($response, true));
        }

        Log:info('getData completed');
    }

    /**
     * Process data retrieved from API
     * @param $data
     */
    private function processData($data)
    {
        foreach ($data['roads'] as $road) {
            $newRoad = new Road();
            $newRoad->fill($road);

            if ($newRoad->save()) {
                foreach ($road['segments'] as $segment) {
                    $newSegment = $this->formatKeys($segment);

                    foreach ($newSegment as $key => $value) {
                        if (is_array($value)) {
                            foreach ($newSegment[$key] as $incident) {
                                $newRoadProperty = new RoadProperty();
                                $newRoadProperty->road_id = $newRoad->id;

                                $newRoadProperty->start = $this->getSegmentStartEnd($newSegment, 'start');
                                $newRoadProperty->end = $this->getSegmentStartEnd($newSegment, 'end');

                                $newIncident = $this->formatKeys($incident);

                                unset($newIncident['start']);
                                unset($newIncident['stop']);
                                $newRoadProperty->fill($newIncident);
                                if(!$newRoadProperty->save()){
                                    Log::warning('Road properties not saved');
                                }

                                $newIndcidentProperty = new IncidentProperty();
                                $newIndcidentProperty->rp_id = $newRoadProperty->id;
                                $newIndcidentProperty->fill($newIncident);
                                if(!$newIndcidentProperty->save()){
                                    Log::warning('Incident properties not saved');
                                }

                                foreach ($newIncident['events'] as $event) {
                                    $newEvent = $this->formatKeys($event);
                                    $newRoadEvent = new RoadEvent();
                                    $newRoadEvent->rp_id = $newRoadProperty->id;
                                    $newRoadEvent->fill($newEvent);
                                    if($newRoadEvent->save()){
                                        Log::warning('Road event not saved');
                                    }
                                }

                                if (key_exists('bounds', $newIncident)) {
                                    $this->saveBounds($newIncident, 'bounds', $newIndcidentProperty->id);
                                }
                                if (key_exists('loc', $newIncident)) {
                                    $arr['data']['loc'] = $newIncident['loc'];
                                    $this->saveBounds($arr, 'data', $newIndcidentProperty->id);
                                }
                            }
                        }
                    }
                }
            } else {
                Log::warning('Road data is not saved');
            }
        }
    }

    private function formatKeys($array)
    {
        $newArray = [];
        foreach ($array as $key => $value) {
            if ($key === 'HM') {
                $newArray['hm'] = $value;
            } else {
                $keyParts = preg_split('/(?=[A-Z])/', $key);
                foreach ($keyParts as &$keyPart) {
                    $keyPart = lcfirst($keyPart);
                }

                $newArray[implode('_', $keyParts)] = $value;
            }
        }

        unset($keyPart);

        return $newArray;
    }

    /**
     * Get the value of start, end or location
     *
     * @param $newSegment
     * @param $key
     * @return mixed|string
     */
    private
    function getSegmentStartEnd($newSegment, $key)
    {
        if (key_exists($key, $newSegment)) {
            $key = $newSegment[$key];
        } elseif (key_exists('location', $newSegment)) {
            $key = $newSegment['location'];
        } else {
            $key = '-';
        }

        return $key;
    }

    private function saveBounds($newIncident, $arrKey, $id)
    {
        foreach ($newIncident[$arrKey] as $key => $value) {
            try {
                DB::insert("INSERT INTO `incident_bounds` (`ip_id`,`key`,`lon_lat`,`created_at`,`updated_at`) VALUES(:ip_id,:key,Point(:lon,:lat),:created_at,:updated_at)", [
                    'ip_id' => $id,
                    'key' => $key,
                    'lon' => $value['lon'],
                    'lat' => $value['lat'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            } catch (Exception $e){
                Log::warning('Bounds not saved');
            }

        }
    }

}
