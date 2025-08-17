<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['day'], $data['meal'])) {
        $day = $data['day'];
        $meal = $data['meal'];

        // Database connection (replace with your own)
        $pdo = new PDO('mysql:host=localhost;dbname=meal_planner', 'username', 'password');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Insert meal into the database
        $stmt = $pdo->prepare("INSERT INTO meals (day, name, quantity, calories, fat, protein, carbs, cost) VALUES (:day, :name, :quantity, :calories, :fat, :protein, :carbs, :cost)");
        $stmt->execute([
            ':day' => $day,
            ':name' => $meal['name'],
            ':quantity' => $meal['quantity'],
            ':calories' => $meal['calories'],
            ':fat' => $meal['fat'],
            ':protein' => $meal['protein'],
            ':carbs' => $meal['carbs'],
            ':cost' => $meal['cost']
        ]);

        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
    }
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
}
?>
