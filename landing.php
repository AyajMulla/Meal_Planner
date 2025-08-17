<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "meal_planner"; // Replace with your actual DB name

$conn = new mysqli($servername, $username, $password, $dbname, 3307);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$message = ""; // Initialize message variable

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Validate the email
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Prepare the SQL query to insert the email
        $sql = "INSERT INTO subscribers (email) VALUES ('$email')";

        // Execute the query
        if ($conn->query($sql) === TRUE) {
            $message = "Thank you for subscribing! You will receive the latest updates in your inbox.";
        } else {
            $message = "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        $message = "Please enter a valid email address.";
    }
}


$conn->close();



// Simulating login status
$isLoggedIn = false;

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    if ($email) {
        // In a real application, you would save this email to a database
        $message = "Thank you for subscribing!";
    }
}

// Function to handle meal plan creation
function handleCreateMealPlan() {
    global $isLoggedIn;
    if ($isLoggedIn) {
        header("Location: meal.php");
        exit;
    } else {
        echo "<script>alert('You need to log in first.'); window.location.href='login.php';</script>";
    }
}

// Check if the form was submitted
if (isset($_POST['create_meal_plan'])) {
    handleCreateMealPlan();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meal Maestro</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');

        :root {
            --purple-400: #9f7aea;
            --pink-500: #ed64a6;
            --red-500: #f56565;
            --yellow-400: #fbbf24;
            --purple-800: #553c9a;
        }

        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            background: linear-gradient(135deg, var(--purple-400), var(--pink-500), var(--red-500));
            color: white;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
        }

        header {
            position: sticky;
            top: 0;
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            z-index: 40;
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: bold;
        }

        .btn {
            background-color: var(--yellow-400);
            color: var(--purple-800);
            padding: 0.5rem 1rem;
            border-radius: 9999px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        main {
            padding: 4rem 0;
        }

        .hero {
            text-align: center;
            margin-bottom: 4rem;
        }

        h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .hero p {
            font-size: 1.25rem;
            margin-bottom: 2rem;
        }

        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 4rem;
        }

        .feature-card {
            background-color: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 0.5rem;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .feature-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .how-it-works {
            background-color: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 0.5rem;
            padding: 2rem;
            margin-bottom: 4rem;
        }

        .steps {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
        }

        .step {
            text-align: center;
        }

        .step-number {
            width: 4rem;
            height: 4rem;
            background-color: var(--yellow-400);
            color: var(--purple-800);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: bold;
            margin: 0 auto 1rem;
        }

        footer {
            background-color: var(--purple-800);
            padding: 3rem 0;
        }

        .footer-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }

        @media (max-width: 768px) {
            .footer-content {
                grid-template-columns: 1fr;
            }
        }

        form {
            display: flex;
        }

        input[type="email"] {
            flex-grow: 1;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 9999px 0 0 9999px;
        }

        button[type="submit"] {
            background-color: var(--yellow-400);
            color: var(--purple-800);
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 0 9999px 9999px 0;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <header>
        <nav class="container">
            <div class="logo">🍽️ Meal Maestro</div>
            <div>
                <?php if (!$isLoggedIn): ?>
                    <a href="login.php" class="btn">Login</a>
                    <a href="signup.php" class="btn" style="background-color: var(--purple-800); color: white;">Sign Up</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>

    <main class="container">
        <section class="hero">
            <h1>Delicious Meals, Zero Stress</h1>
            <p>Personalized meal plans that make healthy eating a breeze!</p>
            <form method="post">
                <button type="submit" name="create_meal_plan" class="btn">Start Planning Now</button>
            </form>
        </section>

        <section class="features">
            <?php
            $features = [
                ['title' => 'Save Time', 'icon' => '⏱️', 'description' => 'Pre-planned meals at your fingertips'],
                ['title' => 'Eat Healthy', 'icon' => '🥗', 'description' => 'Nutritionally balanced recipes'],
                ['title' => 'Reduce Waste', 'icon' => '🌱', 'description' => 'Precise ingredients, no leftovers'],
                ['title' => 'Discover Flavors', 'icon' => '🌶️', 'description' => 'Explore new cuisines weekly']
            ];

            foreach ($features as $feature):
            ?>
                <div class="feature-card">
                    <div class="feature-icon"><?php echo $feature['icon']; ?></div>
                    <h3><?php echo $feature['title']; ?></h3>
                    <p><?php echo $feature['description']; ?></p>
                </div>
            <?php endforeach; ?>
        </section>

        <section class="how-it-works">
            <h2>How It Works</h2>
            <div class="steps">
                <?php
                $steps = [
                    ['step' => 1, 'title' => 'Sign Up', 'description' => 'Create your account and tell us about your dietary preferences.'],
                    ['step' => 2, 'title' => 'Get Your Plan', 'description' => 'Receive a customized weekly meal plan tailored to your needs.'],
                    ['step' => 3, 'title' => 'Cook & Enjoy', 'description' => 'Follow easy recipes and savor delicious, healthy meals.']
                ];

                foreach ($steps as $step):
                ?>
                    <div class="step">
                        <div class="step-number"><?php echo $step['step']; ?></div>
                        <h3><?php echo $step['title']; ?></h3>
                        <p><?php echo $step['description']; ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>

    <footer>
    <div class="container footer-content">
    <div>
        <h3>Stay Updated</h3>
        <p>Get the latest recipes and nutrition tips delivered to your inbox.</p>
        <form method="post">
            <input type="email" name="email" placeholder="Your email address" required>
            <button type="submit">Subscribe</button>
        </form>
        <?php
        if (isset($message)) {
            echo "<p>$message</p>";
        }
        ?>
    
    
</div>
                <p>&copy; 2025 Meal Maestro <br>
                Your personal meal planning assistant</p>
            </div>
        </div>
        </div>
    </footer>
</body>
</html>