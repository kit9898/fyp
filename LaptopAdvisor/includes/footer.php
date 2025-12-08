    </main>
    <footer>
        <div class="container">
            <p>&copy; <?php echo date("Y"); ?> Smart Laptop Advisor by Chan Shao Heng. All Rights Reserved.</p>
        </div>
    </footer>

<!-- ===== CHATBOT HTML (with Enhanced Icons) ===== -->
<div id="chat-widget" class="chat-widget">
    <div class="chat-header">
        <h4>Smart Advisor Chat</h4>
        <div class="chat-header-controls">
            <!-- NEW: Cleaner SVG icons and unified button structure -->
            <button id="maximize-chat" class="chat-header-btn" title="Maximize Chat">
                <svg id="maximize-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M3 3h18v18H3z"/></svg>
                <svg id="restore-icon" style="display: none;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M13 3h8v8h-2V5h-6V3zm8 10v8h-8v-2h6v-6h2zM3 13v8h8v-2H5v-6H3zm0-2h8V3H3v8zm2-6h4v4H5V5z"/></svg>
            </button>
            <button id="minimize-chat" class="chat-header-btn" title="Minimize Chat">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M20 14H4v-4h16v4z"/></svg>
            </button>
            <button id="end-chat" class="chat-header-btn" title="End Chat Session">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
            </button>
        </div>
    </div>
    <div id="chat-body" class="chat-body">
        <!-- Chat history will be loaded here -->
        <div id="typing-indicator" class="typing-indicator">
            <span></span><span></span><span></span>
        </div>
    </div>
    <div class="chat-footer">
        <form id="chat-form" class="chat-form">
            <input type="text" id="chat-input" placeholder="Type a message..." autocomplete="off">
            <button type="submit" aria-label="Send">
                <svg viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"></path></svg>
            </button>
        </form>
    </div>
</div>

<button id="chat-toggle" class="chat-toggle">
    <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24" fill="white"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H6l-2 2V4h16v12z"/></svg>
</button>
<!-- ===== CHATBOT HTML END ===== -->

<!-- Markdown Parser for Chat Messages -->
<script src="https://cdn.jsdelivr.net/npm/marked@9.1.6/marked.min.js"></script>

   <script>
    // --- Mobile Navigation Script ---
    const hamburger = document.querySelector('.hamburger');
    const navLinks = document.querySelector('.nav-links');
    if(hamburger) {
        hamburger.addEventListener('click', () => {
            navLinks.classList.toggle('nav-active');
            hamburger.classList.toggle('toggle');
        });
    }

    // --- OLLAMA-POWERED CHATBOT SCRIPT ---
    const chatWidget = document.getElementById('chat-widget');
    const chatToggle = document.getElementById('chat-toggle');
    const maximizeChat = document.getElementById('maximize-chat');
    const maximizeIcon = document.getElementById('maximize-icon');
    const restoreIcon = document.getElementById('restore-icon');
    const minimizeChat = document.getElementById('minimize-chat');
    const endChat = document.getElementById('end-chat');
    const chatForm = document.getElementById('chat-form');
    const chatInput = document.getElementById('chat-input');
    const chatBody = document.getElementById('chat-body');
    const typingIndicator = document.getElementById('typing-indicator');
    
    let sessionId = null;
    let historyLoaded = false;
    let isMinimized = false;
    let isSending = false;

    // Initialize session ID from localStorage
    const storedSessionId = localStorage.getItem('chat_session_id');
    if (storedSessionId) {
        sessionId = storedSessionId;
    }

    // Functions to open, minimize, and end chat
    function openChat() {
        chatWidget.classList.add('open');
        chatWidget.classList.remove('minimized', 'maximized');
        toggleMaximizeIcon(false);
        chatToggle.style.display = 'none';
        isMinimized = false;
        if (!historyLoaded) {
            initializeChat();
        }
    }

    function minimize() {
        chatWidget.classList.remove('open');
        chatWidget.classList.add('minimized');
        chatWidget.classList.remove('maximized');
        toggleMaximizeIcon(false);
        chatToggle.style.display = 'flex';
        isMinimized = true;
    }

    function end() {
        if (!sessionId) return; // Already ended

        // Clear session
        sessionId = null;
        localStorage.removeItem('chat_session_id');
        historyLoaded = false;

        displayMessage("Chat session ended.", 'system');
    }

    // Toggle maximize state
    function toggleMaximize() {
        chatWidget.classList.toggle('maximized');
        const isMaximized = chatWidget.classList.contains('maximized');
        toggleMaximizeIcon(isMaximized);

        if (isMaximized) {
            chatToggle.style.display = 'none';
        } else {
            if (!chatWidget.classList.contains('open')) {
                 chatToggle.style.display = 'flex';
            }
        }
    }

    function toggleMaximizeIcon(isMaximized) {
        if (isMaximized) {
            maximizeIcon.style.display = 'none';
            restoreIcon.style.display = 'inline-block';
            maximizeChat.title = 'Restore Down';
        } else {
            maximizeIcon.style.display = 'inline-block';
            restoreIcon.style.display = 'none';
            maximizeChat.title = 'Maximize Chat';
        }
    }

    // Event Listeners
    chatToggle.addEventListener('click', () => {
        if (isMinimized) {
            openChat();
        } else {
            chatWidget.classList.remove('maximized');
            toggleMaximizeIcon(false);
            chatWidget.classList.toggle('open');
            if (chatWidget.classList.contains('open')) {
                chatToggle.style.display = 'none';
                if (!historyLoaded) {
                    initializeChat();
                }
            } else {
                chatToggle.style.display = 'flex';
            }
        }
    });

    maximizeChat.addEventListener('click', toggleMaximize);
    minimizeChat.addEventListener('click', minimize);
    endChat.addEventListener('click', end);

    // Chat form submit handler
    chatForm.addEventListener('submit', async (event) => {
        event.preventDefault();
        const userInput = chatInput.value.trim();
        if (userInput === '' || isSending) return;
        
        // Check if we have a session
        if (!sessionId) {
            displayMessage('Initializing chat session...', 'system');
            await startNewSession();
            if (!sessionId) {
                displayMessage('Failed to start chat session. Please try again.', 'system');
                return;
            }
        }
        
        displayMessage(userInput, 'user');
        chatInput.value = '';
        showTypingIndicator();
        isSending = true;
        
        // Get currency info
        const currency = localStorage.getItem('selected_currency') || 'USD';
        let rate = 1;
        try {
            const storedRates = JSON.parse(localStorage.getItem('currency_rates'));
            if (storedRates && storedRates.rates && storedRates.rates[currency]) {
                rate = storedRates.rates[currency];
            }
        } catch (e) {
            console.error('Error parsing currency rates', e);
        }

        try {
            const response = await fetch('chatbot_process.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    action: 'send_message',
                    session_id: sessionId,
                    message: userInput,
                    currency: currency,
                    exchange_rate: rate
                })
            });
            
            const data = await response.json();
            hideTypingIndicator();
            
            if (data.success) {
                displayMessage(data.response, 'bot');
            } else {
                displayMessage('Error: ' + data.error, 'system');
            }
        } catch (error) {
            hideTypingIndicator();
            displayMessage('Sorry, I\'m having trouble connecting right now. Please check that Ollama is running.', 'bot');
            console.error('Error:', error);
        } finally {
            isSending = false;
        }
    });

    // Initialize chat session
    async function initializeChat() {
        if (!sessionId) {
            await startNewSession();
        }
        
        if (sessionId) {
            displayMessage("Hello! I'm your Smart Laptop Advisor. How can I help you find the perfect laptop today?", 'bot');
            historyLoaded = true;
        } else {
            displayMessage('Failed to initialize chat. Please try again.', 'system');
        }
    }

    // Start new chat session
    async function startNewSession() {
        try {
            const response = await fetch('/fyp/LaptopAdvisor/chatbot_process.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    action: 'start_session'
                })
            });
            
            const data = await response.json();
            
            if (data.success) {
                sessionId = data.session_id;
                localStorage.setItem('chat_session_id', sessionId);
                return true;
            } else {
                console.error('Failed to start session:', data.error);
                return false;
            }
        } catch (error) {
            console.error('Error starting session:', error);
            return false;
        }
    }

    // Display message in chat with MARKDOWN RENDERING
    function displayMessage(message, sender) {
        const messageElement = document.createElement('div');
        messageElement.classList.add('chat-message', sender);
        
        // Render markdown for bot messages, plain text for user/system
        if (sender === 'bot' && typeof marked !== 'undefined') {
            // Configure marked.js for better table rendering
            marked.setOptions({
                breaks: true,
                gfm: true, // GitHub Flavored Markdown (supports tables)
                headerIds: false,
                mangle: false
            });
            
            // Render markdown to HTML
            const renderedHTML = marked.parse(message);
            messageElement.innerHTML = renderedHTML;
        } else {
            // For user and system messages, just use plain text with basic formatting
            const formattedMessage = message.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
            messageElement.innerHTML = formattedMessage;
        }
        
        chatBody.insertBefore(messageElement, typingIndicator);
        chatBody.scrollTop = chatBody.scrollHeight;
    }
    
    function clearChatBody() {
        const messages = chatBody.querySelectorAll('.chat-message');
        messages.forEach(msg => msg.remove());
    }

    function showTypingIndicator() {
        typingIndicator.style.display = 'flex';
        chatBody.scrollTop = chatBody.scrollHeight;
    }

    function hideTypingIndicator() {
        typingIndicator.style.display = 'none';
    }
</script>
</body>
</html>
<?php
if(isset($conn)){
    $conn->close();
}
?>