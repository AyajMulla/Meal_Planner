<?php
// Define your Spoonacular API key and URL
$apiKey = "79b885b25d3d4baba7a9e89a62503843"; // Replace with your actual API key
$apiUrl = "https://api.spoonacular.com/recipes/complexSearch?query=";

// Get the data from the request (meal data from the form)
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['day'], $data['mealType'], $data['title'])) {
    echo json_encode(["error" => "Invalid data"]);
    exit;
}

// Extract the meal data
$day = $data['day'];
$mealType = $data['mealType'];
$title = urlencode($data['title']); // URL-encode the title for API search

// Build the API request URL for Spoonacular
$requestUrl = $apiUrl . $title . "&apiKey=" . $apiKey;

// Fetch the meal details from the API
$response = file_get_contents($requestUrl);
$mealDetails = json_decode($response, true);

// Check if API call was successful
if ($mealDetails && isset($mealDetails['results'][0])) {
    // Get the first result from the API
    $apiMeal = $mealDetails['results'][0];

    // Prepare the meal data
    $mealData = [
        'day' => $day,
        'mealType' => $mealType,
        'title' => $apiMeal['title'],
        'calories' => $apiMeal['nutrition']['nutrients'][0]['amount'],
        'fat' => $apiMeal['nutrition']['nutrients'][1]['amount'],
        'protein' => $apiMeal['nutrition']['nutrients'][2]['amount'],
        'carbs' => $apiMeal['nutrition']['nutrients'][3]['amount'],
    ];

    // Save the meal data in a file (or use a database)
    $meals = json_decode(file_get_contents('meals.json'), true);
    if (!$meals) $meals = [];
    $meals[] = $mealData;
    file_put_contents('meals.json', json_encode($meals));

    echo json_encode(["success" => "Meal added successfully", "mealData" => $mealData]);
} else {
    echo json_encode(["error" => "Meal not found in the API"]);
}
?>
