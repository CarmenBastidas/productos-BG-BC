<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Exception;
use App\Models\Constant;
use App\Models\User;
use App\Traits\ApiCallResponse;
use App\Traits\ApiResponse;
use App\Traits\ValidatorEc;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response;

class AccessTokenController extends Controller
{

    use ApiResponse;
    use ApiCallResponse;
    use ValidatorEc;

    /**
     * Handle permission of this resource controller.
     *
     * @return void
     */
    public function __construct()
    {
    }


    /**
     * Login de Usuarios
     * @param Request $request
     * @return JsonResponse
     * @response array{ user: array{ user_id: integer, name: string, last_name: string, email: string, type_identified: string, identified: string, date_birthday: string, gender: string, city_id: integer, zone_id: integer, level_id: integer, address: string, phone: string, phone_home: string, invoice: integer, inv_name: string, inv_last_name: string, inv_email: string, inv_type_identified: string, inv_identified: string, inv_address: string, inv_phone: string, account_update: boolean} , token: string}
     */
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                /**
                 * @example xxxxxxxx@gmail.com
                 */
                'email' => 'required|email',
                /**
                 * @example password123
                 */
                'password' => 'required',
                'device' => ''
            ]);

            $device = isset($request->device) ? $request->device : Constant::$device;

            if ($validator->fails()) {
                return $this->responseWithError(Lang::get('Message Info ERROR'), $validator->messages(), Response::HTTP_BAD_REQUEST);
            }

            if (!Auth::attempt($request->only('email', 'password'))) {
                return $this->responseWithError(Lang::get('Message Info ERROR'), 'Usuario no existe.', Response::HTTP_BAD_REQUEST);
            }

            if (($request->user()->role == Constant::$role) && !empty($request->user()->account_verified_at)) {
                $auth = $request->user()->createToken(Constant::$guard_name, ['*'], NULL)->plainTextToken;

                return response()->json([
                    'user' => [
                        'user_id' => $request->user()->id,

                        'name' => $request->user()->name,
                        'last_name' => $request->user()->last_name,
                        'email' => $request->user()->email,
                        'type_identified' => $request->user()->type_identified,
                        'identified' => $request->user()->identified
                    ],
                    'token' => $auth
                ], Response::HTTP_OK);
            } else {
                return $this->responseWithError(Lang::get('Message Info ERROR'), 'Usuario no válido.', Response::HTTP_BAD_REQUEST);
            }
        } catch (\Exception $e) {
            //$this->saveAudit('LOGIN', '', '', Response::HTTP_INTERNAL_SERVER_ERROR, $request->email, $e->getMessage());
            return $this->responseWithError(Lang::get('Message Info ERROR'), Lang::get('ErrorException'), Response::HTTP_BAD_REQUEST);
        }
    }

}
