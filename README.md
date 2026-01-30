# AI Writing Assistant - Backend API

A robust RESTful API built with Laravel 12, providing AI-powered text processing services via Google Gemini and secure user management.

## Features

* **AI Text Processing**: Integrated with Google Gemini API for:
    * Text Improvement.
    * Summarization.
    * Professional Tone Transformation.
    * Content-to-Bullet Point Conversion.
* **Authentication**: Secure token-based auth using **Laravel Sanctum**.
* **Email Engine**: Transactional emails (Verification & Password Reset) powered by **Brevo** via Laravel Mail.
* **Background Tasks**: Asynchronous email dispatching using **Redis Queues** for high performance.
* **Security & Monitoring**:
    * **Rate Limiting**: Custom per-endpoint throttle logic.
    * **Database Logging**: Comprehensive tracking of all AI API transactions for audit trails.
* **Email Verification**: Strictly enforced verification flow with resend capabilities.

## Tech Stack

* **Framework**: Laravel 12
* **Database**: MySQL 
* **Cache/Queue**: Redis
* **AI Provider**: Google Gemini AI
* **Email Provider**: Brevo 

## Installation & Setup

1. **Clone the repo:**
   ```bash
   git clone [YOUR_REPO_LINK]
   cd [YOUR_REPO_FOLDER]

2. **Install dependencies:**
   ```bash
   composer install

3. **Environment Configuration:**
   ```bash
   cp .env.example .env
   php artisan key:generate

4. **Configure your .env:**
   ```bash
   DB_DATABASE=your_db
   
   REDIS_HOST=127.0.0.1
   
   MAIL_MAILER=brevo
   MAIL_FROM_ADDRESS="hello@yourdomain.com"
   
   GEMINI_API_KEY=your_gemini_key
   FRONTEND_URL=your_frontend_url
   BREVO_API_KEY=your_brevo_key

5. **Run Migrations & Queue:**
   ```bash
   php artisan migrate
   php artisan queue:work
