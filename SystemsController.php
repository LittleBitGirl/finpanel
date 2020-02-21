<?php

namespace App\Http\Controllers\Life;

use App\Http\Controllers\Controller;
use App\Http\Customs\Exporter;
use App\Models\Life\LifeC24h;
use App\Models\Life\LifeCloud;
use App\Models\Life\LifeHome;
use App\Models\Life\LifeKaspi;
use App\Models\Life\LifePost;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SystemsController extends Controller {


    public function getPSMergedData(Request $request)
    {
        $ps_id = $request->input('ps_id');
        $origin_ids = $this->getPS($ps_id, $request->input('project_id'));
        $resource = $this->getResource($ps_id);
    }

    public function getPSData(Request $request)
    {
        $paySystems = $this->getPS($request->input('ps_id'), $request->input('project_id'));
    }

    public function getPS($ps_id, $project_id)
    {
        return Comission::where('project_id', $project_id)
            ->where('ps_id', $ps_id)
            ->select('origin_id')->get();
    }


    public function getModelClass($modelName)
    {
        switch ($modelName) {
            case 'Epay':
                return LifeHome::class;
                break;
            case 'Cloudpayments':
                return LifeCloud::class;
                break;
            case 'Kaspi':
                return LifeKaspi::class;
                break;
            case 'Posterminal':
                return LifePost::class;
                break;
            case 'Contact24h':
                return LifeC24h::class;
                break;
                break;
        }

        return abort(404, 'Сорян, но класс не найден');
    }

    public function export(Request $request)
    {
        $psName = $request->input('system');
        $model = $this->getModelClass($psName);
        $from = $request->input('from');
        $to = $request->input('to');
        $from = Carbon::parse($from)->startOfDay()->toDateTimeString();
        $to = Carbon::parse($to)->endOfDay()->toDateTimeString();
        $resource = (new $model)->range([$from, $to])->get();
        $headers = [$psName.' Ref', $psName.' Sum', $psName.' Date', 'lifeRef', 'lifeSum', 'lifeDate'];
        $exporter = new Exporter();
        return $exporter->saveToCsv($resource->toArray(), $headers, $psName.'_'.$from.'-'.$to);
    }

    public static function getPSLife()
    {
        return ['Cloud', 'Kaspi', 'C24h', 'Post', 'Home'];
    }
}
