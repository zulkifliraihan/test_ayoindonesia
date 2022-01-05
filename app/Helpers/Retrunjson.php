<?php
namespace App\Helpers;

class Retrunjson
{
    public static function main($response)
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
            'message' => $message
        ], 200);
    }

    public static function route($response, $route)
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
        ], 200);
    }
}
