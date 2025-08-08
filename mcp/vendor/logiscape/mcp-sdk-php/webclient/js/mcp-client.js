// Model Context Protocol SDK for PHP
// (c) 2025 Logiscape LLC <https://logiscape.com>
// https://github.com/logiscape/mcp-sdk-php
// MIT License

// MCP Web Client
// mcp-client.js
class McpClientUI {
    constructor() {
        this.sessionId = null;
        this.capabilities = null;
        this.debugPanel = document.getElementById('debug-panel');
        this.addStyles();
        this.setupEventListeners();
        this.initializeDebugPanel();
    }
    
    // Notification Handling
    showNotification(message, type = 'info') {
        const id = 'notification-' + Date.now();
        const html = `
            <div id="${id}" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header bg-${type} text-white">
                    <strong class="me-auto">${type.charAt(0).toUpperCase() + type.slice(1)}</strong>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
                </div>
                <div class="toast-body">
                    ${message}
                </div>
            </div>
        `;
        
        const notificationArea = document.getElementById('notification-area');
        notificationArea.insertAdjacentHTML('beforeend', html);
        
        const toastElement = document.getElementById(id);
        const toast = new bootstrap.Toast(toastElement, {
            delay: 5000,
            autohide: true
        });
        
        toast.show();
        
        // Remove element after it's hidden
        toastElement.addEventListener('hidden.bs.toast', () => {
            toastElement.remove();
        });
    }

    // Add error notification
    showError(message) {
        this.showNotification(message, 'danger');
    }

    // Add success notification
    showSuccess(message) {
        this.showNotification(message, 'success');
    }
    
    // Core Features
    setupEventListeners() {
        // Connection form handling
        document.getElementById('connection-form').addEventListener('submit', (e) => {
            e.preventDefault();
            this.connect();
        });

        // Disconnect button
        document.getElementById('disconnect-btn').addEventListener('click', () => {
            this.disconnect();
        });

        // Capability buttons
        document.getElementById('list-prompts-btn').addEventListener('click', () => {
            this.executeOperation('list_prompts');
        });

        document.getElementById('list-tools-btn').addEventListener('click', () => {
            this.executeOperation('list_tools');
        });

        document.getElementById('list-resources-btn').addEventListener('click', () => {
            this.executeOperation('list_resources');
        });
    }

    async connect() {
        try {
            this.showLoading(true);
        
            const command = document.getElementById('command').value;
            const args = document.getElementById('args').value
                .split('\n')
                .filter(arg => arg.trim() !== '');
                
            // Parse environment variables
            const envText = document.getElementById('env').value;
            const env = {};
            envText.split('\n')
                .filter(line => line.trim() !== '')
                .forEach(line => {
                    const [key, ...valueParts] = line.split('=');
                    if (key && valueParts.length > 0) {
                        env[key.trim()] = valueParts.join('=').trim();
                    }
                });
    
            const response = await fetch('connect.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ 
                    command, 
                    args,
                    env: Object.keys(env).length > 0 ? env : undefined
                })
            });

            const data = await response.json();
            
            if (data.error) {
                throw new Error(data.error);
            }

            this.sessionId = data.data.sessionId;
            this.capabilities = data.data.capabilities;
            
            // Update UI based on capabilities
            this.updateCapabilityPanels();
            
            // Update button states
            document.getElementById('connect-btn').disabled = true;
            document.getElementById('disconnect-btn').disabled = false;
            
            // Update debug panel
            this.appendDebugLog(data.logs);
            
        } catch (error) {
            this.showError(error.message);
        } finally {
            this.showLoading(false);
        }
    }

    async disconnect() {
        if (!this.sessionId) return;

        try {
            await fetch('connect.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ sessionId: this.sessionId })
            });

            // Reset UI
            this.sessionId = null;
            this.capabilities = null;

            this.promptsList = [];
            this.toolsList = [];
            this.resourcesList = [];

            document.getElementById('prompts-result').innerHTML = '';
            document.getElementById('tools-result').innerHTML = '';
            document.getElementById('resources-result').innerHTML = '';

            document.getElementById('prompt-details').classList.add('d-none');
            document.getElementById('tool-details').classList.add('d-none');
            document.getElementById('resource-details').classList.add('d-none');
            
            this.updateCapabilityPanels();
            
            // Update button states
            document.getElementById('connect-btn').disabled = false;
            document.getElementById('disconnect-btn').disabled = true;
            
        } catch (error) {
            this.showError(error.message);
        }
    }

    async executeOperation(operation, params = {}) {
        if (!this.sessionId) return;

        try {
            const response = await fetch('execute.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    sessionId: this.sessionId,
                    operation,
                    params
                })
            });

            const data = await response.json();
            
            if (data.error) {
                throw new Error(data.error);
            }

            // Update result panel
            this.updateResultPanel(operation, data.data);
            
            // Update debug panel
            this.appendDebugLog(data.logs);
            
        } catch (error) {
            this.showError(error.message);
        }
    }

    updateCapabilityPanels() {
        const panels = {
            'prompts-panel': this.capabilities?.prompts,
            'tools-panel': this.capabilities?.tools,
            'resources-panel': this.capabilities?.resources
        };

        for (const [panelId, enabled] of Object.entries(panels)) {
            const panel = document.getElementById(panelId);
            panel.style.display = enabled ? 'block' : 'none';
        }
    }

    updateResultPanel(operation, result) {
        const panelId = `${operation.split('_')[1]}-result`;
        const panel = document.getElementById(panelId);
        
        // Clear previous results
        panel.innerHTML = '';
        
        // Create result display based on operation type
        switch (operation) {
            case 'list_prompts':
                this.displayPrompts(panel, result.prompts);
                break;
            case 'list_tools':
                this.displayTools(panel, result.tools);
                break;
            case 'list_resources':
                this.displayResources(panel, result.resources);
                break;
        }
    }
    
    // Debug Console
    async fetchLogs(since = null) {
        try {
            const params = new URLSearchParams({
                limit: 100
            });
            if (since) {
                params.append('since', since);
            }
            if (this.sessionId) {
                params.append('sessionId', this.sessionId);
            }

            const url = `logs.php?${params.toString()}`;

            const response = await fetch(url);
            const data = await response.json();

            if (data.error) {
                throw new Error(data.error);
            }

            return data.data.entries;
        } catch (error) {
            console.error('Error fetching logs:', error); // Debug line
            this.showError(`Failed to fetch logs: ${error.message}`);
            return [];
        }
    }

    async clearLogs() {
        try {
            const response = await fetch('logs.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                }
            });

            const data = await response.json();

            if (data.error) {
                throw new Error(data.error);
            }

            // Clear the debug panel
            this.debugPanel.innerHTML = '';
            this.showSuccess('Logs cleared successfully');
        } catch (error) {
            this.showError(`Failed to clear logs: ${error.message}`);
        }
    }

    initializeDebugPanel() {
        const panel = document.getElementById('debug-panel');
        const toggleBtn = document.getElementById('toggle-debug');
        let pollInterval = null;

        toggleBtn.addEventListener('click', async () => {
            const isVisible = panel.style.display !== 'none';
            panel.style.display = isVisible ? 'none' : 'block';

            if (isVisible) {
                // Stop polling when hidden
                if (pollInterval) {
                    clearInterval(pollInterval);
                    pollInterval = null;
                }
            } else {
                // Clear and fetch initial logs
                panel.innerHTML = '';
                const logs = await this.fetchLogs();
                // Auto-scroll only on initial display
                this.appendDebugLog(logs, true);

                // Start polling for new logs
                if (!pollInterval) {
                    pollInterval = setInterval(async () => {
                        const lastLog = Array.from(panel.children)
                            .map(el => el.getAttribute('data-timestamp'))
                            .filter(ts => ts)
                            .map(Number)
                            .sort((a, b) => b - a)[0];

                        const newLogs = await this.fetchLogs(lastLog);
                        // Don't auto-scroll for updates
                        this.appendDebugLog(newLogs, false);
                    }, 5000);
                }
            }
        });

        document.getElementById('clear-debug').addEventListener('click', () => {
            this.clearLogs();
        });
    }

    // Prompt Testing

    initializePromptHandlers() {
        // Prompt selection handler
        const promptSelect = document.getElementById('prompt-select');
        promptSelect.addEventListener('change', () => {
            this.handlePromptSelection(promptSelect.value);
        });

        // Prompt form submission
        const promptForm = document.getElementById('prompt-form');
        promptForm.addEventListener('submit', (e) => {
            e.preventDefault();
            this.executeSelectedPrompt();
        });
    }

    handlePromptSelection(promptName) {
        const prompt = this.findPrompt(promptName);
        const form = document.getElementById('prompt-form');
        const submitButton = form.querySelector('button[type="submit"]');
    
        if (!prompt) {
            submitButton.disabled = true;
            return;
        }

        // Generate form based on prompt arguments
        this.generatePromptArgumentsForm(prompt);
        submitButton.disabled = false;
    }

    findPrompt(promptName) {
        return this.promptsList?.find(prompt => prompt.name === promptName);
    }

    generatePromptArgumentsForm(prompt) {
        const container = document.getElementById('prompt-arguments');
        container.innerHTML = ''; // Clear existing form

        if (!prompt.arguments || prompt.arguments.length === 0) {
            return;
        }

        prompt.arguments.forEach(arg => {
            const formGroup = document.createElement('div');
            formGroup.className = 'mb-3';

            const label = document.createElement('label');
            label.className = 'form-label';
            label.htmlFor = `prompt-arg-${arg.name}`;
            label.textContent = `${arg.name}${arg.required ? ' *' : ''}`;

            if (arg.description) {
                label.title = arg.description;
                label.style.cursor = 'help';
            }

            const input = document.createElement('input');
            input.className = 'form-control';
            input.id = `prompt-arg-${arg.name}`;
            input.type = 'text';
            input.required = arg.required || false;

            formGroup.appendChild(label);
            formGroup.appendChild(input);

            if (arg.description) {
                const helpText = document.createElement('div');
                helpText.className = 'form-text';
                helpText.textContent = arg.description;
                formGroup.appendChild(helpText);
            }

            container.appendChild(formGroup);
        });
    }

    async executeSelectedPrompt() {
        const promptSelect = document.getElementById('prompt-select');
        const promptName = promptSelect.value;
        const prompt = this.findPrompt(promptName);

        if (!prompt) {
            this.showError('No prompt selected');
            return;
        }

        // Get the submit button first
        const submitButton = document.querySelector('#prompt-form button[type="submit"]');
        if (!submitButton) {
            this.showError('Submit button not found');
            return;
        }

        // Store original button text before try block
        const originalText = submitButton.textContent;

        try {
            // Collect form data
            const args = {};
            const container = document.getElementById('prompt-arguments');
            const inputs = container.querySelectorAll('input');

            for (const input of inputs) {
                const name = input.id.replace('prompt-arg-', '');
                args[name] = input.value;
            }

            // Show loading state
            submitButton.disabled = true;
            submitButton.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Executing...';

            // Execute the prompt
            const response = await fetch('execute.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    sessionId: this.sessionId,
                    operation: 'get_prompt',
                    params: {
                        name: promptName,
                        arguments: args
                    }
                })
            });

            const result = await response.json();

            if (!result.success) {
                throw new Error(result.error || 'Failed to execute prompt');
            }

            // Display the prompt result in a formatted way
            this.displayPromptResult(result.data);

            // Show success notification
            this.showSuccess(`Successfully executed prompt: ${promptName}`);

        } catch (error) {
            this.showError(`Failed to execute prompt: ${error.message}`);
        } finally {
            // Reset button state
            if (submitButton) {
                submitButton.disabled = false;
                submitButton.textContent = originalText;
            }
        }
    }

    displayPromptResult(result) {
        const resultDiv = document.createElement('div');
        resultDiv.className = 'card mb-3';

        const cardBody = document.createElement('div');
        cardBody.className = 'card-body';

        if (result.description) {
            const description = document.createElement('p');
            description.className = 'card-text';
            description.textContent = result.description;
            cardBody.appendChild(description);
        }

        // Display messages
        if (result.messages && result.messages.length > 0) {
            const messagesContainer = document.createElement('div');
            messagesContainer.className = 'chat-messages';

            result.messages.forEach(message => {
                const messageDiv = document.createElement('div');
                messageDiv.className = `message ${message.role}`;

                // Format the content based on type
                let content = '';
                if (message.content.type === 'text') {
                    content = `<pre class="mb-0">${message.content.text}</pre>`;
                } else if (message.content.type === 'resource') {
                    const resource = message.content.resource;
                    content = `<div class="resource-ref">
                        <strong>Resource:</strong> ${resource.uri}
                        ${resource.text ? `<pre class="mt-2 mb-0">${resource.text}</pre>` : ''}
                    </div>`;
                }

                messageDiv.innerHTML = content;
                messagesContainer.appendChild(messageDiv);
            });

            cardBody.appendChild(messagesContainer);
        }

        resultDiv.appendChild(cardBody);

        // Add to results area
        const resultsArea = document.getElementById('prompts-result');
        resultsArea.insertBefore(resultDiv, resultsArea.firstChild);
    }

    // Update the existing displayPrompts method to store the prompts list and setup handlers
    displayPrompts(panel, prompts) {
        if (!prompts || prompts.length === 0) {
            panel.innerHTML = '<div class="alert alert-info">No prompts available</div>';
            return;
        }

        // Store prompts list for later use
        this.promptsList = prompts;

        // Update prompt select dropdown
        const promptSelect = document.getElementById('prompt-select');
        promptSelect.innerHTML = '<option value="">Select a prompt...</option>';
        prompts.forEach(prompt => {
            const option = document.createElement('option');
            option.value = prompt.name;
            option.textContent = prompt.name;
            promptSelect.appendChild(option);
        });

        // Show prompt details panel
        document.getElementById('prompt-details').classList.remove('d-none');

        // Display prompts list
        const list = document.createElement('div');
        list.className = 'list-group';

        prompts.forEach(prompt => {
            const item = document.createElement('div');
            item.className = 'list-group-item';
            item.innerHTML = `
                <h5 class="mb-1">${prompt.name}</h5>
                <p class="mb-1">${prompt.description || 'No description available'}</p>
                ${this.formatArguments(prompt.arguments)}
            `;
            list.appendChild(item);
        });

        panel.appendChild(list);

        // Initialize prompt handlers if not already done
        if (!this.promptHandlersInitialized) {
            this.initializePromptHandlers();
            this.promptHandlersInitialized = true;
        }
    }

    // Add some CSS styles for the chat messages
    addStyles() {
        const style = document.createElement('style');
        style.textContent = `
            .chat-messages {
                border: 1px solid #dee2e6;
                border-radius: 0.25rem;
                padding: 1rem;
                background: #f8f9fa;
            }
            .message {
                margin-bottom: 1rem;
                padding: 0.5rem;
                border-radius: 0.25rem;
            }
            .message:last-child {
                margin-bottom: 0;
            }
            .message.user {
                background: #e9ecef;
            }
            .message.assistant {
                background: #fff;
                border: 1px solid #dee2e6;
            }
            .message pre {
                white-space: pre-wrap;
                word-wrap: break-word;
            }
            .resource-ref {
                background: #e9ecef;
                padding: 0.5rem;
                border-radius: 0.25rem;
                margin-top: 0.5rem;
            }
        `;
        document.head.appendChild(style);
    }

    // Tool Testing

    initializeToolHandlers() {
        // Tool selection handler
        const toolSelect = document.getElementById('tool-select');
        toolSelect.addEventListener('change', () => {
            this.handleToolSelection(toolSelect.value);
        });

        // Tool form submission
        const toolForm = document.getElementById('tool-form');
        toolForm.addEventListener('submit', (e) => {
            e.preventDefault();
            this.executeSelectedTool();
        });
    }

    handleToolSelection(toolName) {
        const tool = this.findTool(toolName);
        const form = document.getElementById('tool-form');
        const submitButton = form.querySelector('button[type="submit"]');

        if (!tool) {
            submitButton.disabled = true;
            return;
        }

        // Generate form based on tool schema
        this.generateToolArgumentsForm(tool);
        submitButton.disabled = false;
    }

    findTool(toolName) {
        return this.toolsList?.find(tool => tool.name === toolName);
    }

    generateToolArgumentsForm(tool) {
        const container = document.getElementById('tool-arguments');
        container.innerHTML = ''; // Clear existing form

        if (!tool.inputSchema?.properties) {
            return;
        }

        const required = tool.inputSchema.required || [];

        for (const [name, schema] of Object.entries(tool.inputSchema.properties)) {
            const formGroup = document.createElement('div');
            formGroup.className = 'mb-3';

            const label = document.createElement('label');
            label.className = 'form-label';
            label.htmlFor = `tool-arg-${name}`;
            label.textContent = `${name}${required.includes(name) ? ' *' : ''}`;

            if (schema.description) {
                label.title = schema.description;
                label.style.cursor = 'help';
            }

            let input;
            switch (schema.type) {
                case 'boolean':
                    input = this.createBooleanInput(name, schema);
                    break;
                case 'number':
                case 'integer':
                    input = this.createNumberInput(name, schema);
                    break;
                case 'array':
                    input = this.createArrayInput(name, schema);
                    break;
                case 'object':
                    input = this.createObjectInput(name, schema);
                    break;
                default: // string or default
                    input = this.createStringInput(name, schema);
            }

            // Add required attribute if needed
            if (required.includes(name)) {
                input.required = true;
            }

            formGroup.appendChild(label);
            formGroup.appendChild(input);

            if (schema.description) {
                const helpText = document.createElement('div');
                helpText.className = 'form-text';
                helpText.textContent = schema.description;
                formGroup.appendChild(helpText);
            }

            container.appendChild(formGroup);
        }
    }

    createStringInput(name, schema) {
        if (schema.enum) {
            const select = document.createElement('select');
            select.className = 'form-select';
            select.id = `tool-arg-${name}`;

            schema.enum.forEach(value => {
                const option = document.createElement('option');
                option.value = value;
                option.textContent = value;
                select.appendChild(option);
            });

            return select;
        }

        const input = document.createElement('input');
        input.className = 'form-control';
        input.id = `tool-arg-${name}`;
        input.type = 'text';

        if (schema.pattern) {
            input.pattern = schema.pattern;
        }

        return input;
    }

    createNumberInput(name, schema) {
        const input = document.createElement('input');
        input.className = 'form-control';
        input.id = `tool-arg-${name}`;
        input.type = 'number';

        if (schema.minimum !== undefined) input.min = schema.minimum;
        if (schema.maximum !== undefined) input.max = schema.maximum;
        if (schema.type === 'integer') input.step = 1;

        return input;
    }

    createBooleanInput(name, schema) {
        const wrapper = document.createElement('div');
        wrapper.className = 'form-check';

        const input = document.createElement('input');
        input.className = 'form-check-input';
        input.id = `tool-arg-${name}`;
        input.type = 'checkbox';

        const label = document.createElement('label');
        label.className = 'form-check-label';
        label.htmlFor = input.id;
        label.textContent = schema.description || name;

        wrapper.appendChild(input);
        wrapper.appendChild(label);

        return wrapper;
    }

    createArrayInput(name, schema) {
        const wrapper = document.createElement('div');
        wrapper.className = 'array-input-wrapper';

        const textarea = document.createElement('textarea');
        textarea.className = 'form-control';
        textarea.id = `tool-arg-${name}`;
        textarea.rows = 3;
        textarea.placeholder = 'Enter one value per line';

        wrapper.appendChild(textarea);

        const helpText = document.createElement('div');
        helpText.className = 'form-text';
        helpText.textContent = 'Enter multiple values, one per line';
        wrapper.appendChild(helpText);

        return wrapper;
    }

    createObjectInput(name, schema) {
        const textarea = document.createElement('textarea');
        textarea.className = 'form-control';
        textarea.id = `tool-arg-${name}`;
        textarea.rows = 4;
        textarea.placeholder = 'Enter JSON object';

        return textarea;
    }

    async executeSelectedTool() {
        const toolSelect = document.getElementById('tool-select');
        const toolName = toolSelect.value;
        const tool = this.findTool(toolName);

        if (!tool) {
            this.showError('No tool selected');
            return;
        }

        // Get the submit button first
        const submitButton = document.querySelector('#tool-form button[type="submit"]');
        if (!submitButton) {
            this.showError('Submit button not found');
            return;
        }

        // Store original button text before try block
        const originalText = submitButton.textContent;

        try {
            // Collect form data
            const args = {};
            const container = document.getElementById('tool-arguments');
            const inputs = container.querySelectorAll('input, select, textarea');

            for (const input of inputs) {
                const name = input.id.replace('tool-arg-', '');
                let value = input.value;

                // Handle different input types
                if (input.type === 'number') {
                    value = Number(value);
                }

                args[name] = value;
            }

            // Show loading state
            submitButton.disabled = true;
            submitButton.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Executing...';

            // Execute the tool
            const response = await fetch('execute.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    sessionId: this.sessionId,
                    operation: 'call_tool',
                    params: {
                        name: toolName,
                        arguments: args
                    }
                })
            });

            const result = await response.json();

            if (!result.success) {
                throw new Error(result.error || 'Failed to execute tool');
            }

            // Display the tool result
            if (result.data && result.data.content) {
                this.displayToolResult(result.data);
            }

            // Show success notification
            this.showSuccess(`Successfully executed tool: ${toolName}`);

        } catch (error) {
            this.showError(`Failed to execute tool: ${error.message}`);
        } finally {
            // Reset button state
            if (submitButton) {
                submitButton.disabled = false;
                submitButton.textContent = originalText;
            }
        }
    }

    displayToolResult(result) {
        const resultDiv = document.createElement('div');
        resultDiv.className = 'card mb-3';

        const cardBody = document.createElement('div');
        cardBody.className = 'card-body';

        // Handle the content array from the tool result
        if (result.content && result.content.length > 0) {
            const contentContainer = document.createElement('div');
            contentContainer.className = 'tool-result-content';

            result.content.forEach(content => {
                const contentDiv = document.createElement('div');
                contentDiv.className = 'mb-2';

                // Format the content based on type
                switch (content.type) {
                    case 'text':
                        contentDiv.innerHTML = `<pre class="mb-0">${content.text}</pre>`;
                        break;
                    case 'resource':
                        const resource = content.resource;
                        contentDiv.innerHTML = `<div class="resource-ref">
                            <strong>Resource:</strong> ${resource.uri}
                            ${resource.text ? `<pre class="mt-2 mb-0">${resource.text}</pre>` : ''}
                        </div>`;
                        break;
                    case 'image':
                        if (content.data && content.mimeType) {
                            contentDiv.innerHTML = `<img src="data:${content.mimeType};base64,${content.data}" class="img-fluid" alt="Tool result image">`;
                        }
                        break;
                }

                contentContainer.appendChild(contentDiv);
            });

            cardBody.appendChild(contentContainer);
        }

        // Add error state if present
        if (result.isError) {
            resultDiv.classList.add('border-danger');
            const errorBadge = document.createElement('div');
            errorBadge.className = 'badge bg-danger mb-2';
            errorBadge.textContent = 'Error';
            cardBody.insertBefore(errorBadge, cardBody.firstChild);
        }

        resultDiv.appendChild(cardBody);

        // Add to results area
        const resultsArea = document.getElementById('tools-result');
        resultsArea.insertBefore(resultDiv, resultsArea.firstChild);
    }

    // Update the existing displayTools method to store the tools list and setup handlers
    displayTools(panel, tools) {
        if (!tools || tools.length === 0) {
            panel.innerHTML = '<div class="alert alert-info">No tools available</div>';
            return;
        }

        // Store tools list for later use
        this.toolsList = tools;

        // Update tool select dropdown
        const toolSelect = document.getElementById('tool-select');
        toolSelect.innerHTML = '<option value="">Select a tool...</option>';
        tools.forEach(tool => {
            const option = document.createElement('option');
            option.value = tool.name;
            option.textContent = tool.name;
            toolSelect.appendChild(option);
        });

        // Show tool details panel
        document.getElementById('tool-details').classList.remove('d-none');

        // Display tools list
        const list = document.createElement('div');
        list.className = 'list-group';

        tools.forEach(tool => {
            const item = document.createElement('div');
            item.className = 'list-group-item';
            item.innerHTML = `
                <h5 class="mb-1">${tool.name}</h5>
                <p class="mb-1">${tool.description || 'No description available'}</p>
                <small>Schema: <pre>${JSON.stringify(tool.inputSchema, null, 2)}</pre></small>
            `;
            list.appendChild(item);
        });

        panel.appendChild(list);

        // Initialize tool handlers if not already done
        if (!this.toolHandlersInitialized) {
            this.initializeToolHandlers();
            this.toolHandlersInitialized = true;
        }
    }

    initializeResourceHandlers() {
        // Resource selection handler
        const resourceSelect = document.getElementById('resource-select');
        resourceSelect.addEventListener('change', () => {
            this.handleResourceSelection(resourceSelect.value);
        });

        // Resource form submission
        const resourceForm = document.getElementById('resource-form');
        resourceForm.addEventListener('submit', (e) => {
            e.preventDefault();
            this.viewSelectedResource();
        });
    }

    handleResourceSelection(resourceUri) {
        const resource = this.findResource(resourceUri);
        const form = document.getElementById('resource-form');
        const submitButton = form.querySelector('button[type="submit"]');

        submitButton.disabled = !resource;
    }

    findResource(uri) {
        return this.resourcesList?.find(resource => resource.uri === uri);
    }

    async viewSelectedResource() {
        const resourceSelect = document.getElementById('resource-select');
        const resourceUri = resourceSelect.value;
        const resource = this.findResource(resourceUri);

        if (!resource) {
            this.showError('No resource selected');
            return;
        }

        // Get the submit button first
        const submitButton = document.querySelector('#resource-form button[type="submit"]');
        if (!submitButton) {
            this.showError('Submit button not found');
            return;
        }

        // Store original button text before try block
        const originalText = submitButton.textContent;

        try {
            // Show loading state
            submitButton.disabled = true;
            submitButton.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Loading...';

            // Make direct fetch call like prompts and tools do
            const response = await fetch('execute.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    sessionId: this.sessionId,
                    operation: 'read_resource',
                    params: {
                        uri: resourceUri
                    }
                })
            });

            const result = await response.json();

            if (!result.success) {
                throw new Error(result.error || 'Failed to read resource');
            }

            // Additional check for result structure
            if (!result.data?.contents) {
                throw new Error('Invalid response format from server');
            }

            // Display the resource content
            this.displayResourceContent(result.data);

            // Show success notification
            this.showSuccess(`Successfully loaded resource: ${resource.name}`);

        } catch (error) {
            this.showError(`Failed to load resource: ${error.message}`);
        } finally {
            // Reset button state
            if (submitButton) {
                submitButton.disabled = false;
                submitButton.textContent = originalText;
            }
        }
    }

    displayResourceContent(result) {
        const { contents } = result;
        if (!contents || contents.length === 0) {
            this.showError('No content available for this resource');
            return;
        }

        const resultsArea = document.getElementById('resources-result');
        resultsArea.innerHTML = ''; // Clear previous results

        contents.forEach(content => {
            const container = document.createElement('div');
            container.className = 'card mb-3';

            const header = document.createElement('div');
            header.className = 'card-header d-flex justify-content-between align-items-center';
            header.innerHTML = `
                <span>Resource: ${content.uri}</span>
                ${content.mimeType ? `<span class="badge bg-secondary">${content.mimeType}</span>` : ''}
            `;

            const body = document.createElement('div');
            body.className = 'card-body';

            // Handle different content types
            if (content.text !== undefined) {
                body.appendChild(this.formatTextContent(content.text, content.mimeType));
            } else if (content.blob !== undefined) {
                body.appendChild(this.formatBlobContent(content.blob, content.mimeType));
            }

            container.appendChild(header);
            container.appendChild(body);
            resultsArea.appendChild(container);
        });
    }

    formatTextContent(text, mimeType) {
        const container = document.createElement('div');

        // Handle different text-based MIME types
        switch (mimeType) {
            case 'application/json':
                try {
                    const jsonData = JSON.parse(text);
                    const formatted = JSON.stringify(jsonData, null, 2);
                    container.innerHTML = `<pre class="json-content">${this.escapeHtml(formatted)}</pre>`;
                } catch {
                    container.innerHTML = `<pre>${this.escapeHtml(text)}</pre>`;
                }
                break;

            case 'text/html':
                container.innerHTML = `
                    <div class="d-flex justify-content-between mb-2">
                        <button class="btn btn-sm btn-outline-secondary view-source-btn">View Source</button>
                        <button class="btn btn-sm btn-outline-primary preview-btn">Preview</button>
                    </div>
                    <div class="source-view" style="display: none;">
                        <pre>${this.escapeHtml(text)}</pre>
                    </div>
                    <div class="preview-view" style="display: none;">
                        <iframe class="w-100 border-0" style="min-height: 200px;"></iframe>
                    </div>
                `;

                // Add button handlers
                const sourceBtn = container.querySelector('.view-source-btn');
                const previewBtn = container.querySelector('.preview-btn');
                const sourceView = container.querySelector('.source-view');
                const previewView = container.querySelector('.preview-view');
                const iframe = previewView.querySelector('iframe');

                sourceBtn.addEventListener('click', () => {
                    sourceView.style.display = 'block';
                    previewView.style.display = 'none';
                    sourceBtn.classList.add('active');
                    previewBtn.classList.remove('active');
                });

                previewBtn.addEventListener('click', () => {
                    sourceView.style.display = 'none';
                    previewView.style.display = 'block';
                    sourceBtn.classList.remove('active');
                    previewBtn.classList.add('active');

                    // Set iframe content
                    iframe.srcdoc = text;
                });

                // Show preview by default
                previewBtn.click();
                break;

            case 'text/markdown':
                // If you want to add markdown rendering, you could include a library like marked
                container.innerHTML = `<pre>${this.escapeHtml(text)}</pre>`;
                break;

            case 'text/xml':
            case 'application/xml':
                // Pretty print XML
                const formatted = this.formatXml(text);
                container.innerHTML = `<pre>${this.escapeHtml(formatted)}</pre>`;
                break;

            default:
                // Default text display
                container.innerHTML = `<pre>${this.escapeHtml(text)}</pre>`;
        }

        return container;
    }

    formatBlobContent(blob, mimeType) {
        const container = document.createElement('div');

        if (mimeType?.startsWith('image/')) {
            // Handle images
            const img = document.createElement('img');
            img.src = `data:${mimeType};base64,${blob}`;
            img.className = 'img-fluid';
            container.appendChild(img);
        } else {
            // For other binary content, offer download
            const size = Math.round((blob.length * 3 / 4) / 1024); // Approximate size in KB
            container.innerHTML = `
                <div class="text-center">
                    <p>Binary content (${size}KB)</p>
                    <button class="btn btn-primary download-btn">Download</button>
                </div>
            `;

            // Add download handler
            const downloadBtn = container.querySelector('.download-btn');
            downloadBtn.addEventListener('click', () => {
                const link = document.createElement('a');
                link.href = `data:${mimeType};base64,${blob}`;
                link.download = 'download'; // You might want to get a proper filename from the resource
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            });
        }

        return container;
    }

    // Helper methods
    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    formatXml(xml) {
        // Simple XML formatting
        let formatted = '';
        let indent = '';
        xml.split(/>\s*</).forEach(node => {
            if (node.match(/^\/\w/)) indent = indent.substring(2);
            formatted += indent + '<' + node + '>\r\n';
            if (!node.match(/^[?\/]/) && !node.match(/\/[^>]*$/)) indent += '  ';
        });
        return formatted.substring(1, formatted.length - 3);
    }

    // Update the existing displayResources method
    displayResources(panel, resources) {
        if (!resources || resources.length === 0) {
            panel.innerHTML = '<div class="alert alert-info">No resources available</div>';
            return;
        }

        // Store resources list for later use
        this.resourcesList = resources;

        // Update resource select dropdown
        const resourceSelect = document.getElementById('resource-select');
        resourceSelect.innerHTML = '<option value="">Select a resource...</option>';
        resources.forEach(resource => {
            const option = document.createElement('option');
            option.value = resource.uri;
            option.textContent = resource.name;
            resourceSelect.appendChild(option);
        });

        // Show resource details panel
        document.getElementById('resource-details').classList.remove('d-none');

        // Display resources list
        const list = document.createElement('div');
        list.className = 'list-group';

        resources.forEach(resource => {
            const item = document.createElement('div');
            item.className = 'list-group-item';
            item.innerHTML = `
                <h5 class="mb-1">${resource.name}</h5>
                <p class="mb-1">URI: ${resource.uri}</p>
                ${resource.description ? `<p class="mb-1">${resource.description}</p>` : ''}
                ${resource.mimeType ? `<small class="text-muted">MIME Type: ${resource.mimeType}</small>` : ''}
            `;
            list.appendChild(item);
        });

        panel.appendChild(list);

        // Initialize resource handlers if not already done
        if (!this.resourceHandlersInitialized) {
            this.initializeResourceHandlers();
            this.resourceHandlersInitialized = true;
        }
    }

    formatArguments(args) {
        if (!args || args.length === 0) return '<small>No arguments</small>';

        return `
            <small>
                Arguments:
                <ul class="list-unstyled">
                    ${args.map(arg => `
                        <li>
                            ${arg.name} 
                            ${arg.required ? '(required)' : '(optional)'}: 
                            ${arg.description || 'No description'}
                        </li>
                    `).join('')}
                </ul>
            </small>
        `;
    }

    appendDebugLog(logs, autoScroll = false) {
        if (!Array.isArray(logs)) return;

        // Sort logs by timestamp, oldest first
        const sortedLogs = [...logs].sort((a, b) => a.timestamp - b.timestamp);

        sortedLogs.forEach(log => {
            const entry = document.createElement('div');
            entry.setAttribute('data-timestamp', log.timestamp);

            // Format context if present
            let contextStr = '';
            if (log.context && Object.keys(log.context).length > 0) {
                contextStr = ` ${JSON.stringify(log.context)}`;
            }

            entry.innerHTML = `
                <span class="timestamp">[${log.datetime}]</span>
                <span class="level">[${log.level.toUpperCase()}]</span>
                <span class="message">${log.message}${contextStr}</span>
            `;

            this.debugPanel.appendChild(entry);
        });

        // Only auto-scroll if specified
        if (autoScroll) {
            this.debugPanel.scrollTop = this.debugPanel.scrollHeight;
        }
    }

    showLoading(show) {
        const spinner = document.querySelector('.loading-spinner');
        spinner.style.display = show ? 'inline-block' : 'none';
    }

}

// Initialize when the document is ready
document.addEventListener('DOMContentLoaded', () => {
    window.mcpClient = new McpClientUI();
});