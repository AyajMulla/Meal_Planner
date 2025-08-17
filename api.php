<?php
$apiKey = "79b885b25d3d4baba7a9e89a62503843";
$apiUrl = "https://api.spoonacular.com/mealplanner/generate?timeFrame=week&apiKey=$apiKey";

// Fetch the weekly meal plan
$response = file_get_contents($apiUrl);

if ($response === FALSE) {
    echo json_encode(["error" => "Failed to fetch data from Spoonacular API."]);
    exit;
}

$data = json_decode($response, true);
$meals = [];

// Function to fetch meal details with nutrition
function getMealDetails($mealId, $apiKey) {
    $url = "https://api.spoonacular.com/recipes/$mealId/information?includeNutrition=true&apiKey=$apiKey";
    $response = file_get_contents($url);
    return json_decode($response, true);
}

// Iterate over each meal and fetch detailed nutrition info
foreach ($data['week'] as $day => $dayMeals) {
    foreach ($dayMeals['meals'] as $meal) {
        $mealDetails = getMealDetails($meal['id'], $apiKey);
        $meals[] = [
            "title" => $meal['title'],
            "calories" => $mealDetails['nutrition']['nutrients'][0]['amount'] ?? 0,
            "fat" => $mealDetails['nutrition']['nutrients'][1]['amount'] ?? 0,
            "protein" => $mealDetails['nutrition']['nutrients'][2]['amount'] ?? 0,
            "carbs" => $mealDetails['nutrition']['nutrients'][3]['amount'] ?? 0
        ];
    }
}

header('Content-Type: application/json');
echo json_encode(["meals" => $meals]);
