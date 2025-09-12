# FRASER Subject Explorer

A simple PHP + Docker application for exploring subjects from the [FRASER](https://fraser.stlouisfed.org/) digital library API.  
It demonstrates **server-side DataTables integration** with paginated results and drill-down detail pages, all powered by the FRASER API.

---

## 🚀 Features

- **PHP 8.3 + Apache** running in Docker
- **cURL-based FRASER API client** (server-side, with API key)
- **DataTables** for fast, paginated tables
- **Bootstrap 5** styling
- **Details view** for individual records
- **Environment isolation**: API key kept out of source control

---

## 🛠️ Tech Stack

- **Language**: PHP 8.3  
- **Web server**: Apache (via official PHP Docker image)  
- **Frontend**: DataTables + Bootstrap 5  
- **Backend**: cURL calls to FRASER API (JSON)  
- **Containerization**: Docker + Docker Compose  

---

## 📦 Project Structure
```text
├── docker-compose.yml
├── Dockerfile
├── src/
│ ├── index.php # Front page with DataTables of all Subjects
│ ├── api.php # Server-side endpoint for DataTables
│ ├── details.php # Record details by ID
│ ├── environment.php # Local secrets (ignored in git)
│ ├── environment.php.example
│ └── ...
└── .gitignore
```

---

## ⚙️ Setup & Usage

### 1. Clone the repo
```bash
git clone git@github.com:yourname/fraser-subject-explorer.git
cd fraser-subject-explorer
```

### 2. Configure environment

Copy the example file and add your API key:

```php
<?php
    define('API_KEY', 'your-real-key-here');
    define('BASE_URL', 'your-domain-here');
?>
```
⚠️ Do not commit environment.php — it is .gitignored.

###  3. Build & run with Docker
```bash
docker compose up --build
```

This will:
- Build the PHP + Apache container from Dockerfile
- Mount src/ into the container

### 4. Open the app

Visit: http://localhost:8080

You should see a searchable table of FRASER subjects.
Clicking Details opens a record’s JSON view.

## 🔐 Security Notes

- API key is server-side only and never exposed to the browser.
- environment.php is ignored in version control.
- Always rotate/regenerate your FRASER API key if exposed.

## 🛤️ Roadmap
- Add more FRASER endpoints (collections, titles, items).
- Build nicer details view (Bootstrap cards instead of raw JSON).
- Add filters/search across multiple fields.
- Deploy to a public host (e.g., Fly.io, Render, or Dockerized VPS).