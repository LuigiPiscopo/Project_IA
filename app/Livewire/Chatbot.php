<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Illuminate\Support\Facades\Http;

class Chatbot extends Component
{

    public $currentMessage = '';
    public $userPrompt = '';
    public $chatMessages = [];

    protected $rules = [
        'currentMessage' => 'required'
    ];

    protected $messages = [
        'currentMessage.required' => 'Please enter a message'
    ];

    public function ask()
    {
        $this->validate();

        $this->chatMessages[] = [
            'type' => 'human',
            'content' => $this->currentMessage
        ];

        $this->userPrompt = $this->currentMessage;

        $this->currentMessage = '';

        $this->js('$wire.generateResponse');
    }


    public function generateResponse()
    {
        try {
            $response = Http::timeout(120)->post("http://127.0.0.1:8080/chat/travel_agent", [
                'messages' => $this->chatMessages
            ]);
            
    
            $content = $response->json();
    
            // Debug: stampa il contenuto ricevuto
            // dd($response->status(), $content);
    
            if (!is_array($content)) {
                throw new \Exception("Risposta API non valida: " . json_encode($content));
            }
    
            // Verifica i dati ricevuti
            foreach ($content as $message) {
                if (!is_array($message) || !isset($message['type']) || !isset($message['content'])) {
                    throw new \Exception("Formato messaggio non valido: " . json_encode($message));
                }
            }
    
            $this->chatMessages = $content;
        } catch (\Exception $e) {
            Log::error("Errore nella richiesta API: " . $e->getMessage());
    
            $this->chatMessages[] = [
                'type' => 'ai',
                'content' => "⚠️ Errore nel recupero della risposta. Riprova più tardi."
            ];
        }
    }
}    