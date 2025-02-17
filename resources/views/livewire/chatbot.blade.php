<div class="chat-container">
    <div class="chat-header my-3 d-flex flex-column flex-lg-row align-items-lg-center justify-content-between pe-2">
        {{-- <h2 class="fs-4 text-truncate">{{ $chatTitle ?? '' }}</h2> --}}
    </div>

    <div class="chat-box mt-3 mt-md-0">
        @forelse ($chatMessages as $key => $chatMessage)
        @if(is_array($chatMessage) && isset($chatMessage['type']) && isset($chatMessage['content']))
            @if($chatMessage['type'] == 'human')
            <div wire:key="{{ $key }}" class="chat-message sent">
                <div class="chat-message-avatar">
                    <img src="/user.png" alt="Avatar Utente">
                </div>
                <div>
                    <p>{{ $chatMessage['content'] }}</p>
                    @if(isset($chatMessage['timestamp']))
                        <small class="text-muted">{{ $chatMessage['timestamp'] }}</small>
                    @endif
                </div>
            </div>
            @endif
    
            @if($chatMessage['type'] == 'ai' && !empty($chatMessage['content']))
            <div wire:key="{{ $key }}" class="chat-message">
                <div class="chat-message-avatar">
                    <img src="/RagsAI-LOGO.png" alt="Avatar AI">
                </div>
                <div>
                    <x-markdown>{{ $chatMessage['content'] }}</x-markdown>
                    @if(isset($chatMessage['timestamp']))
                        <small class="text-muted">{{ $chatMessage['timestamp'] }}</small>
                    @endif
                </div>
            </div>
            @endif
        @else
            <div class="chat-message">
                <p class="fs-3 text-danger">⚠️ Messaggio non valido</p>
                <pre>{{ print_r($chatMessage, true) }}</pre> {{-- Mostra i dati ricevuti --}}
            </div>
        @endif
    @empty
        <div class="chat-message">
            <div>
                <p class="fs-3">Cosa posso fare per te oggi?</p>
            </div>
        </div>
    @endforelse
    
    </div>

    <form id="submitForm" wire:submit.prevent="ask" class="chat-input py-3 align-items-center">
        <div class="flex-grow-1">
            <div id="inputWrapper" class="w-100">
                <input class="messageInput" placeholder="Scrivi un messaggio..." wire:model.defer="currentMessage" type="text">
                @error('currentMessage') <span class="error">{{ $message }}</span> @enderror
            </div>
        </div>
        
        <button type="submit" class="btn messageBtn">
            <i class="bi bi-send"></i>
        </button>
    </form>
</div>

@script
<script>
document.addEventListener('livewire:load', function () {
    Livewire.on('scrollChatToBottom', () => {
        let chatBox = document.querySelector('.chat-box');
        if (chatBox) {
            setTimeout(() => {
                chatBox.scrollTop = chatBox.scrollHeight;
            }, 500);
        }
    });
});
</script>
@endscript
