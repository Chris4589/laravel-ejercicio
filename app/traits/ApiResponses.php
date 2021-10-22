<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

trait ApiResponses {
  public function responses($array, $err = false, $status = 200) {
    $response = array(
        'error' => $err,
        'msg' => $array,
    );
    return response()->json($response, $status);
  }

  protected function showAll(Collection $coll, $code = 200) {
    return $this->successResponse(['data' => $coll], $code);
  }

  protected function showOne(Model $mdl, $code = 200) {
    return $this->successResponse(['data' => $mdl], $code);
  }

  //para guardar el cache en el sistema
  protected function responsesCache($data) {
    $url = request()->url();

    return Cache::remember($url, 15/60, function() use($data) {
        return $data;
    });
  }
}