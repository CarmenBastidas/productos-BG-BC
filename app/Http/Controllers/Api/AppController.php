<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\BranchSchedule;
use App\Models\Constant;
use App\Models\Contents;
use App\Models\News;
use App\Models\NewsUserDelete;
use App\Models\Terms;
use App\Models\TypeContent;
use App\Models\User;
use App\Models\UserPayment;
use App\Models\Vehiculos;
use App\Models\VehiculosDeseo;
use App\Traits\ApiCallResponse;
use App\Traits\ApiResponse;
use App\Traits\UserResponse;
use App\Traits\ValidatorEc;
use Exception;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\MultipartStream;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpFoundation\Response;
use Tavo\ValidadorEc;


class AppController extends Controller
{
    use ApiResponse;


    /**
     * Handle permission of this resource controller.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('app');
    }

    /**
     * @param Request $request
     * @return array
     */
    public function searchProductos(Request $request)
    {
        try {

            $q = $request->get('filtro');
            $perPage = $request->get('page');
            $sort = $request->get('rows');

            $lstVehiculos = Vehiculos::where('marca', 'like', "%$q%")
                ->paginate($sort);

            $pages = ceil($lstVehiculos->total() / $sort);

            if (!empty($lstVehiculos)) {
                return ['codigo' => 1, 'mensaje' => 'Se ha encontrado registros', 'data' => $lstVehiculos->items(),
                    'rows' => $lstVehiculos->total(),
                    'error' => null,
                    'warning' => null,
                    'pages' => $pages,
                    'records' => $lstVehiculos->total(),
                    'page' => $lstVehiculos->currentPage()];
            }
            return $this->responseWithError('NOT RESULT', '', Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            return $this->responseWithError('ERROR', $e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }


    /**
     * @param Request $request
     * @return array
     */
    public function saveProductos(Request $request)
    {
        try {
            $vehiculo = $request->codigo;
            $estado = $request->estado;

            $objDeseo = VehiculosDeseo::where('vehiculo_id', $vehiculo)->where('user', 1)->first();
            if (!empty($objDeseo)) {
                $objDeseo->estado = $estado;
                $objDeseo->save();
            } else {
                $objDeseo = new VehiculosDeseo();
                $objDeseo->vehiculo_id = $vehiculo;
                $objDeseo->user = 1;
                $objDeseo->estado = $estado;
                $objDeseo->save();
            }

            return ['codigo' => 1, 'mensaje' => 'Se ha registrado sus preferencias'];

        } catch (\Exception $e) {
            return $this->responseWithError('ERROR', $e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }



    /**
     * @param Request $request
     * @return array
     */
    public function searchProductosPrefer(Request $request)
    {
        try {

            $q = $request->get('filtro');
            $perPage = $request->get('page');
            $sort = $request->get('rows');

            $lstVehiculos = DB::table('vehiculos as c')->
                    join('vehiculos_deseo as ca', function ($join) {
                        $join->on('c.id', '=', 'ca.vehiculo_id')
                            ->where('ca.estado', 1);
                    })
                    ->where('marca', 'like', "%$q%")
                ->paginate($sort);

            $pages = ceil($lstVehiculos->total() / $sort);

            if (!empty($lstVehiculos)) {
                return ['codigo' => 1, 'mensaje' => 'Se ha encontrado registros', 'data' => $lstVehiculos->items(),
                    'rows' => $lstVehiculos->total(),
                    'error' => null,
                    'warning' => null,
                    'pages' => $pages,
                    'records' => $lstVehiculos->total(),
                    'page' => $lstVehiculos->currentPage()];
            }
            return $this->responseWithError('NOT RESULT', '', Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            return $this->responseWithError('ERROR', $e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }



}
