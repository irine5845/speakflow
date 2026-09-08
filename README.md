# 🎙️ SpeakFlow

## Text-to-Speech Converter System

SpeakFlow is a web-based **Text-to-Speech (TTS) Converter System** that allows users to enter or save text and convert it into spoken speech using the browser's Speech Synthesis API.

The system provides customizable speech controls, document management, conversion history, user authentication, and a dashboard for managing user activity.

---

## 📌 Features

### 🔐 User Authentication

* User registration
* Secure login
* Password hashing
* Session management
* Secure logout
* Account status checking
* Role-based user accounts

---

### 🔊 Text-to-Speech Converter

* Convert written text into speech
* Multiple voice selection
* Language selection
* Speech speed control
* Pitch control
* Volume control
* Play speech
* Pause speech
* Resume speech
* Stop speech
* Clear text

The system uses the browser's built-in **Web Speech API**.

---

### 📄 Document Management

Users can:

* Create and save documents
* Enter document titles
* View saved documents
* Edit documents
* Delete documents
* View word counts
* View character counts

---

### 🕘 Conversion History

SpeakFlow records text-to-speech conversion activity.

Each conversion can store:

* Language
* Selected voice
* Speech speed
* Pitch
* Volume
* Character count
* Conversion status
* Date and time

---

### 📊 User Dashboard

The user dashboard provides an overview of:

* Total conversions
* Saved documents
* Characters converted
* Quick access to the Text-to-Speech Converter

---

## 🛠️ Technologies Used

| Technology     | Purpose                       |
| -------------- | ----------------------------- |
| PHP            | Backend development           |
| MySQL          | Database management           |
| HTML5          | Page structure                |
| CSS3           | Styling                       |
| JavaScript     | Client-side functionality     |
| Bootstrap 5    | Responsive user interface     |
| Web Speech API | Text-to-Speech functionality  |
| XAMPP          | Local development environment |
| Apache         | Web server                    |

---

# 📁 Project Structure

```text
speakflow/
│
├── index.php
│
├── config/
│   └── database.php
│
├── assets/
│   ├── css/
│   │   └── style.css
│   │
│   ├── js/
│   │   └── app.js
│   │
│   └── images/
│
├── includes/
│   └── auth.php
│
├── auth/
│   ├── login.php
│   ├── register.php
│   └── logout.php
│
├── dashboard/
│   └── index.php
│
├── converter/
│   └── index.php
│
├── documents/
│   ├── index.php
│   ├── save.php
│   ├── edit.php
│   └── delete.php
│
├── history/
│   ├── index.php
│   └── save_conversion.php
│
├── profile/
│   └── index.php
│
├── admin/
│   ├── index.php
│   ├── users.php
│   ├── conversions.php
│   ├── reports.php
│   └── settings.php
│
├── uploads/
│
└── database/
    └── speakflow.sql
```

---

# ⚙️ Installation

## 1. Clone or Download the Project

Place the project inside your XAMPP `htdocs` directory:

```text
C:\xampp\htdocs\speakflow
```

---

## 2. Start XAMPP

Open the XAMPP Control Panel and start:

* Apache
* MySQL

---

## 3. Create the Database

Open phpMyAdmin:

```text
http://localhost/phpmyadmin
```

Create a database named:

```text
speakflow
```

---

## 4. Import the Database

Import the SQL file:

```text
database/speakflow.sql
```

Alternatively, create the required tables manually.

The system uses the following main tables:

* `users`
* `documents`
* `conversions`
* `notifications`
* `activity_logs`

---

## 5. Configure the Database Connection

Open:

```text
config/database.php
```

Configure your database credentials:

```php
<?php

$host = "localhost";
$dbname = "speakflow";
$username = "root";
$password = "";

try {

    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password
    );

    $pdo->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

    $pdo->setAttribute(
        PDO::ATTR_DEFAULT_FETCH_MODE,
        PDO::FETCH_ASSOC
    );

} catch (PDOException $e) {

    die("Database connection failed: " . $e->getMessage());

}
```

---

## 🚀 Running the Application

After starting Apache and MySQL, open your browser and visit:

```text
http://localhost/speakflow/
```

---

# 👤 How to Use SpeakFlow

### Step 1: Create an Account

Navigate to:

```text
/auth/register.php
```

Create a new user account.

---

### Step 2: Login

Navigate to:

```text
/auth/login.php
```

Enter your email address and password.

---

### Step 3: Open the Dashboard

After successful login, users are redirected to the dashboard.

The dashboard displays:

* Total conversions
* Saved documents
* Characters converted

---

### Step 4: Convert Text to Speech

Open the **Text-to-Speech Converter**.

1. Enter or paste text.
2. Select a language.
3. Choose a voice.
4. Adjust speed, pitch, and volume.
5. Click **Speak**.

Use the available controls to:

* Pause
* Resume
* Stop
* Clear

---

### Step 5: Save a Document

Click:

```text
💾 Save Document
```

Enter a document title and save it.

The document will appear under:

```text
My Documents
```

---

### Step 6: View Conversion History

Each conversion is recorded in the database.

Users can view previous conversions under:

```text
Conversion History
```

---

# 🗄️ Database Structure

## Users

Stores user account information.

| Field      | Description        |
| ---------- | ------------------ |
| id         | User ID            |
| full_name  | User's full name   |
| email      | User email address |
| password   | Hashed password    |
| role       | User role          |
| status     | Account status     |
| created_at | Registration date  |

---

## Documents

Stores user text documents.

| Field           | Description          |
| --------------- | -------------------- |
| id              | Document ID          |
| user_id         | Document owner       |
| title           | Document title       |
| content         | Text content         |
| word_count      | Number of words      |
| character_count | Number of characters |
| created_at      | Creation date        |
| updated_at      | Last update date     |

---

## Conversions

Stores Text-to-Speech conversion records.

| Field           | Description          |
| --------------- | -------------------- |
| id              | Conversion ID        |
| user_id         | User ID              |
| language        | Selected language    |
| voice           | Selected voice       |
| speed           | Speech speed         |
| pitch           | Speech pitch         |
| volume          | Speech volume        |
| character_count | Number of characters |
| status          | Conversion status    |
| created_at      | Conversion date      |

---

# 🔒 Security Features

SpeakFlow implements several security practices:

* Password hashing using `password_hash()`
* Password verification using `password_verify()`
* PDO prepared statements
* Session-based authentication
* Session regeneration after login
* Role-based access
* Account status validation
* User ownership checks when editing or deleting documents
* Protected dashboard pages

---

# 🌐 Browser Compatibility

SpeakFlow uses the **Web Speech API**.

For the best experience, use modern browsers such as:

* Google Chrome
* Microsoft Edge
* Mozilla Firefox
* Safari

Available voices depend on the browser and operating system.

---

# 📈 Future Improvements

The following features are planned for future versions:

### 🎵 Audio Download

* Generate MP3 files
* Generate WAV files
* Download generated speech

### 📁 File Upload

Support for:

* PDF documents
* Microsoft Word documents
* Text files

The system could extract text and convert it into speech.

### 🤖 AI Features

* AI-powered natural voices
* AI summarization
* Text translation
* Smart pronunciation
* Language detection

### 👨‍💼 Admin Panel

* User management
* Conversion monitoring
* Usage analytics
* Reports
* Activity logs
* System settings

### 📱 Mobile Application

A mobile version could be developed using:

* Flutter
* Android Studio

---

# 🔮 Future System Architecture

```text
                    ┌───────────────┐
                    │   SpeakFlow   │
                    └───────┬───────┘
                            │
            ┌───────────────┼───────────────┐
            │               │               │
       Authentication    Converter      Documents
            │               │               │
            │          Text-to-Speech       │
            │               │               │
            └───────────────┼───────────────┘
                            │
                        MySQL Database
                            │
                 ┌──────────┼──────────┐
                 │          │          │
              History    Dashboard    Admin
```

---

# 🤝 Contributing

Contributions and improvements are welcome.

To contribute:

1. Fork the repository.
2. Create a new branch.
3. Make your changes.
4. Commit your changes.
5. Push the branch.
6. Create a Pull Request.

---

# 📝 License

This project is intended for educational and development purposes.

You may modify and improve the project according to your requirements.

---

# 👨‍💻 Developer

Developed as a web-based **Text-to-Speech Converter System** using:

**PHP | MySQL | JavaScript | Bootstrap | Web Speech API**

---

## ⭐ SpeakFlow

**Turn your text into speech.**

🎙️ **Speak. Listen. Save. Manage.**
