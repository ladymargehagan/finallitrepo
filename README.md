# LIT - Lost in Translation  

LIT (Lost in Translation) is a modern, interactive web platform designed to make language learning engaging and effective. The platform offers personalized learning experiences through interactive exercises and progress tracking. 

## Features 

- **Interactive Learning**: Engage with native speakers and dynamic lessons
- **Progress Tracking**: Monitor your learning journey with detailed analytics
- **Adaptive Learning**: Personalized exercise difficulty based on performance
- **Multi-language Support**: Learn multiple languages on a single platform
- **Admin Dashboard**: Comprehensive tools for content management
- **Responsive Interface**: Seamlessly adapts to phones, tablets, and desktops

## Tech Stack 

- **Frontend**:
  - HTML5
  - CSS3 (Custom properties, Flexbox, Grid)
  - JavaScript (ES6+)
  - Google Fonts (Poppins, Inter)
  - Font Awesome Icons

- **Backend**:
  - PHP
  - MySQL

## Installation 

1. Clone the repository:

git clone https://github.com/yourusername/lit-platform.git


2. Set up your web server (Apache/Nginx) to point to the project directory

3. Import the database schema (file location: `database/schema.sql`)

4. Configure your database connection in `config/database.php`

5. Ensure proper permissions are set for file uploads and cache directories:


lit-platform/
├── actions/ # PHP action handlers
├── assets/
│ ├── css/ # Stylesheets
│ ├── js/ # JavaScript files
│ └── images/ # Image assets
├── includes/ # PHP includes
├── view/ # View templates
│ ├── admin/ # Admin panel views
│ └── user/ # User views
├── config/ # Configuration files
└── database/ # Database schema and migrations


## Usage 💡

### For Users
1. Register an account
2. Select your target language(s)
3. Complete the placement test
4. Start learning with interactive exercises
5. Track your progress in the dashboard

### For Administrators
1. Access the admin panel at `/admin`
2. Manage languages, exercises, and user content
3. Monitor platform statistics
4. Create and edit learning materials

## Contributing 

We welcome contributions to the LIT platform! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request


## Links 📧
Project Link: [http://169.239.251.102:3341/~lady.hagan/LIT/)
Demo: https://youtu.be/0AYTRR28PXA

---

Made with ❤️ by [Lady-M. Hagan]
