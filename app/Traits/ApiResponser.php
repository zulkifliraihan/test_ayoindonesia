<?php
namespace App\Traits;

/*
|--------------------------------------------------------------------------
| Api Responser Trait
|--------------------------------------------------------------------------
|
| This trait will be used for any response we sent to clients.
|
*/

trait ApiResponser
{
	/**
     * Return a success JSON response.
     *
     * @param  array|string  $data
     * @param  string  $message
     * @param  int|null  $code
     * @return \Illuminate\Http\JsonResponse
     */
	protected function success($response, int $code = 200)
	{
        if ($response == "created") {
            $message = "Data berhasil di buat";
        }
        elseif ($response == "updated") {
            $message = "Data berhasil di update";
        }
        elseif ($response == "deleted") {
            $message = "Data berhasil di hapus";
        }
        else {
            $message = "Error";
        }

		return response()->json([
			'status' => 'ok',
            'response' => 'successfully-' .  $response,
			'message' => $message,
		], $code);
	}

    /**
     * Return a success JSON response with route.
     *
     * @param  array|string  $data
     * @param  string  $message
     * @param  int|null  $code
     * @return \Illuminate\Http\JsonResponse
     */
	protected function successroute($response, $route, int $code = 200)
	{
        if ($response == "created") {
            $message = "Data berhasil di buat";
        }
        elseif ($response == "updated") {
            $message = "Data berhasil di update";
        }
        elseif ($response == "deleted") {
            $message = "Data berhasil di hapus";
        }
        else {
            $message = "Error";
        }

		return response()->json([
			'status' => 'ok',
            'response' => 'successfully-' .  $response,
			'message' => $message,
            'route' => $route
		], $code);
	}

    /**
     * Return an error JSON response.
     *
     * @param  string  $message
     * @param  int  $code
     * @param  array|string|null  $data
     * @return \Illuminate\Http\JsonResponse
     */
	protected function error(string $message = null, int $code, $data = null)
	{
		return response()->json([
			'status' => 'fail',
			'message' => $message,
			'data' => $data
		], $code);
	}
}
