# Ollama PHP Integration

A simple web interface with a PHP backend and standard JavaScript for interacting with Ollama AI models.

## 📋 Overview

Users can send prompts to Ollama AI models using simple web interface, and they can receive responses via a PHP API endpoint. This project's goal is to learn how to integrate Ollama with web applications.

## 🚀 Features

- A simple HTML form interface for user input 
- A PHP backend API for Ollama integration
- Basic error handling and validation 
- Real-time response display 
- User feedback and loading states

## 📁 Project Structure

```
├── index.html         # Frontend interface
├── api.php            # Backend API endpoint
├── style.css          # Styling (optional)
└── README.md          # This file
```

## ⚙️ Prerequisites

Before starting this project, ensure that you have:

- **PHP** (version 7.4 or higher) is installed
- **Ollama** is installed and running locally
- A web server (Apache, Nginx, or PHP built-in server)
- **llama3.2:1b** model downloaded in Ollama

## 🔧 Installation

1. **Clone the repository:**
   ```bash
   git clone https://github.com/richa-parekh/ollama.git
   cd ollama
   ```

2. **Install and start Ollama:**
   ```bash
   # Install Ollama (if not already installed)
   curl -fsSL https://ollama.ai/install.sh | sh
   
   # Pull the required model
   ollama pull llama3.2:1b
   
   # Start Ollama service
   ollama serve
   ```

3. **Start the web server:**
   ```bash
   # Using PHP built-in server
   php -S localhost:8000
   
   # Or place files in your web server directory (htdocs, www, etc.)
   ```

4. **Access the application:**
   Open your browser and navigate to `http://localhost:8000`

## 🖥️ Usage

1. Open your browser and go to the web interface.
2. In the textarea, type the request or prompt.
3. Press the "Generate output" button.
4. Check the AI response to show up below.

<!-- ## 🔄 How It Works

### Frontend (index.html)
- Captures user input from textarea
- Sends JSON data to PHP backend using fetch API
- Displays responses and handles errors
- Provides loading states during processing

### Backend (api.php)
- Receives JSON data from frontend
- Validates and processes user input
- Communicates with Ollama API endpoints:
  - `/api/show` - Checks if model exists
  - `/api/generate` - Generates AI response
- Returns JSON response to frontend -->

<!-- ### Data Flow
```
User Input → JavaScript → PHP API → Ollama → PHP API → JavaScript → Display
``` -->

<!-- ## 📝 API Endpoints

### POST /api.php
Processes user prompts and returns AI-generated responses.

**Request:**
```json
{
  "prompt": "Your question here"
}
```

**Response:**
```json
{
  "response": "AI generated response"
}
```

**Error Response:**
```json
{
  "error": "Error message"
}
``` -->

## 🛠️ Configuration

### Change Ollama Model
Edit the model name in `api.php`:
```php
$postData = array(
    'model' => 'your-model-name',  // Change this
    // ...
);
```

### Change Ollama URL
If Ollama is running on different host/port:
```php
$url = "http://your-ollama-host:11434/api/generate";
```

## 🐛 Troubleshooting

**Common Issues:**

1. **"Connection Error"** - Make sure Ollama is running:
   ```bash
   ollama serve
   ```

2. **"Model not found"** - Pull the required model:
   ```bash
   ollama pull llama3.2:1b
   ```

3. **"CORS Error"** - Make sure you're accessing via proper web server, not file:// protocol

4. **"PHP Errors"** - Check PHP error logs and ensure cURL is enabled

<!-- ## 🤝 Contributing

This is a beginner-level project. Feel free to:
- Fork the repository
- Submit pull requests
- Report issues
- Suggest improvements -->

## 📄 License

This project is open source and available under the [MIT License](LICENSE).

## 🔗 Resources

- [Ollama Documentation](https://github.com/ollama/ollama)
- [Ollama API Reference](https://github.com/ollama/ollama/blob/main/docs/api.md)
- [PHP cURL Documentation](https://www.php.net/manual/en/book.curl.php)

## ✨ Next Steps
<!-- - Add conversation history
- Implement streaming responses -->
- Add model selection dropdown
- Improve error handling and validation
<!-- - Add CSS styling and better UI
- Implement authentication
- Add response caching -->