<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use OpenAI;
use GuzzleHttp\Client as GuzzleClient;

class ChatGPTController extends Controller
{
    public function handleChat(Request $request)
    {
        $query = $request->input('message');
        $dbResponse = $this->handleDatabaseQuery($query);
        $aiResponse = $this->getAIResponse($query, $dbResponse);

        return response()->json(['response' => $aiResponse]);
    }

    private function handleDatabaseQuery($query)
    {
        // Lógica para analizar la consulta y realizar búsquedas en la base de datos.
        if (str_contains($query, 'clientes')) {
            return DB::table('clientes')->get();
        }

        return [];
    }

    public function listModels()
    {
        $client = OpenAI::client(env('OPENAI_API_KEY'));

        $models = $client->models()->list();

        return response()->json($models);
    }

    private function getAIResponse($query, $dbResponse)
    {
        // Ruta al archivo cacert.pem
        $certPath = 'C:/laragon/bin/php/php-8.1.10-Win32-vs16-x64/extras/ssl/cacert.pem';

        // Crear un cliente Guzzle con la configuración del certificado SSL
        $httpClient = new GuzzleClient([
            'verify' => $certPath, // Ruta al archivo cacert.pem
        ]);

        // Crear cliente OpenAI usando el cliente Guzzle personalizado
        $client = OpenAI::factory()
            ->withHttpClient($httpClient)
            ->withApiKey(env('OPENAI_API_KEY'))
            ->make();

        // Realizar la solicitud al modelo GPT-4
        $response = $client->chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'system', 'content' => 'Eres un asistente que ayuda con consultas de la base de datos.'],
                ['role' => 'user', 'content' => $query],
                ['role' => 'assistant', 'content' => json_encode($dbResponse)],
            ],
        ]);

        return $response['choices'][0]['message']['content'];
    }
}
