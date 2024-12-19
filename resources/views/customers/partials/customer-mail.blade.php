<!-- Redesigned HTML inspired by Outlook (without "Para") -->
<div id="customer-mail-modal" class="customer-mail-modal hidden">
    <div class="customer-mail-container">
        <header class="customer-mail-header">
            <div class="header-left">
                <h2>Nuevo Mensaje</h2>
            </div>
            <div class="header-right">
                <button id="close-customer-mail" class="close-button">&times;</button>
            </div>
        </header>

        <div class="customer-mail-body">
            <div class="mail-field">
                <label for="email-cc">CCO:</label>
                <div id="recipients-container" class="recipients-container">
                    <input type="text" id="add-recipient-input" class="recipient-input" placeholder="Añadir destinatarios">
                </div>

            </div>

            <div class="mail-field">
                <label for="email-subject">Asunto:</label>
                <input type="text" id="email-subject" class="email-subject" placeholder="Introduce el asunto">
            </div>

            <div class="mail-field" style="display: none;">
                <label for="email-body">Mensaje:</label>
                <textarea id="email-body" class="email-body" placeholder="Escribe tu mensaje"></textarea>
            </div>
        </div>

        <footer class="customer-mail-footer">
            <div class="footer-left">
                <button id="discard-email" class="cancel-button">Descartar</button>
            </div>
            <div class="footer-right">
                <button id="send-email" class="send-button">Abir Correo</button>
            </div>
        </footer>
    </div>
</div>