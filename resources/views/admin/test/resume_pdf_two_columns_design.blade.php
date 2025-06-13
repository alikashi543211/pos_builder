<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Developer Resume</title>
    <style>
        *{
            padding: 0;
            margin: 0;
        }
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 5px;
            background-color: #fff;
        }
        .container {
            width: 100%;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0;
            font-size: 14px;
            color: #555;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        td {
            vertical-align: top;
            padding: 10px;
        }
        h2 {
            font-size: 18px;
            margin-bottom: 10px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        p, ul {
            margin: 10px 0;
        }
        ul {
            list-style-type: disc;
            padding-left: 20px;
        }
        ul li {
            margin-bottom: 5px;
        }
        .links a {
            color: #007BFF;
            text-decoration: none;
        }
        .links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>John Doe</h1>
            <p>Web Developer | johndoe@example.com | (123) 456-7890 | <a href="https://www.linkedin.com/in/johndoe" target="_blank">LinkedIn</a></p>
        </div>

        <!-- Two-column layout -->
        <table>
            <tr>
                <!-- Left Column -->
                <td style="width: 50%; border-right: 1px solid #ddd;">
                    <!-- Summary -->
                    <h2>Summary</h2>
                    <p>Experienced Web Developer with 5+ years of building dynamic websites and web applications. Proficient in modern web technologies like React, Laravel, and Next.js. Skilled in creating scalable and user-friendly solutions.</p>

                    <!-- Experience -->
                    <h2>Experience</h2>
                    <p><strong>Senior Web Developer</strong> - TechCorp Solutions (2020 - Present)</p>
                    <ul>
                        <li>Designed and developed responsive web applications using React and Laravel.</li>
                        <li>Optimized website performance, reducing load time by 30%.</li>
                        <li>Collaborated with cross-functional teams to implement new features.</li>
                    </ul>
                    <p><strong>Junior Web Developer</strong> - Web Innovators (2017 - 2020)</p>
                    <ul>
                        <li>Built 15+ client websites with custom features using JavaScript and PHP.</li>
                        <li>Fixed bugs and improved website functionality for better user experience.</li>
                    </ul>

                    <!-- Education -->
                    <h2>Education</h2>
                    <p><strong>Bachelor of Science in Computer Science</strong> - University of Web Development (2013 - 2017)</p>
                    <ul>
                        <li>Graduated with honors (3.8 GPA).</li>
                        <li>Relevant coursework: Web Development, Algorithms, Databases.</li>
                    </ul>
                </td>

                <!-- Right Column -->
                <td style="width: 50%; padding-left: 10px;">
                    <!-- Projects -->
                    <h2>Projects</h2>
                    <p><strong>Portfolio Website</strong></p>
                    <ul>
                        <li>Designed and developed a personal portfolio website showcasing projects and skills.</li>
                        <li>Technologies: React, Tailwind CSS, Next.js.</li>
                    </ul>
                    <p><strong>E-commerce Platform</strong></p>
                    <ul>
                        <li>Built a fully functional e-commerce platform with cart, payment gateway, and admin panel.</li>
                        <li>Technologies: Laravel, Vue.js, Stripe API.</li>
                    </ul>

                    <!-- Skills -->
                    <h2>Skills</h2>
                    <ul>
                        <li>Languages: HTML, CSS, JavaScript, PHP</li>
                        <li>Frameworks: React, Next.js, Laravel</li>
                        <li>Tools: Git, Docker, Webpack</li>
                        <li>Other: Responsive Design, REST APIs, Agile Methodology</li>
                    </ul>

                    <!-- Interests -->
                    <h2>Interests</h2>
                    <ul>
                        <li>Exploring new web technologies</li>
                        <li>Open-source contributions</li>
                        <li>Gaming and problem-solving</li>
                        <li>Photography and traveling</li>
                    </ul>

                    <!-- Live Links -->
                    <h2>Live Links</h2>
                    <ul class="links">
                        <li><a href="https://www.johndoeportfolio.com" target="_blank">Portfolio Website</a></li>
                        <li><a href="https://github.com/johndoe" target="_blank">GitHub Profile</a></li>
                        <li><a href="https://ecommerce.johndoe.com" target="_blank">E-commerce Project</a></li>
                    </ul>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
