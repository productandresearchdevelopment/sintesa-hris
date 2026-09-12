<?php

namespace App\Controllers\Front\Chatbots;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Validator;

class ChatbotIncomming extends Controller
{
    protected static $client;
    public function __construct()
    {
        self::$client = new Client([
            'base_uri' => env('AI_API_URL'),
        ]);
    }

    public function processChatbot(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Input tidak valid.',
                'errors' => $validator->errors()
            ], 422);
        }

        $userInput = $request->input('message');

        try {
            $response = self::$client->post('/chat', [
                'json' => ['message' => $userInput]
            ]);

            $responseBody = json_decode($response->getBody(), true);

            return response()->json([
                'status' => 'success',
                'response' => $responseBody['response'] ?? 'Tidak ada respons.'
            ]);
        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal terhubung ke server AI.',
                'details' => $e->getMessage()
            ], 500);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Server AI merespons dengan kesalahan.',
                'details' => $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan internal.',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    public function test()
    {
        try {
            $response = self::$client->get('/');

            $responseBody = json_decode($response->getBody(), true);

            return response()->json([
                'status' => 'success',
                'response' => $responseBody['response'] ?? 'Tidak ada respons.'
            ]);
        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            // Tangani kesalahan koneksi
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal terhubung ke server AI.',
                'details' => $e->getMessage()
            ], 500);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            // Tangani error HTTP dari API
            return response()->json([
                'status' => 'error',
                'message' => 'Server AI merespons dengan kesalahan.',
                'details' => $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            // Tangani error umum lainnya
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan internal.',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}
