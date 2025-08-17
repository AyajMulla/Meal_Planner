<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weekly Meal Planner</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1>Free Weekly Meal Planner</h1>
        <div class="calendar">
            <div class="week-header">
                <span>Monday</span>
                <span>Tuesday</span>
                <span>Wednesday</span>
                <span>Thursday</span>
                <span>Friday</span>
                <span>Saturday</span>
                <span>Sunday</span>
            </div>
            <div class="week-content" id="meal-plan">
                <!-- Meals will be loaded here dynamically -->
            </div>
        </div>
    </div>

    <script>
        async function loadMealPlan() {
            try {
                const response = await fetch('api.php');
                if (!response.ok) throw new Error('Failed to fetch meal plan');
                const data = await response.json();

                let mealPlanHtml = '';
                if (data.meals && data.meals.length > 0) {
                    data.meals.forEach((meal) => {
                        mealPlanHtml += `
                            <div class="day">
                                <h3>${meal.title}</h3>
                                <p><strong>Calories:</strong> ${meal.calories.toFixed(2)}</p>
                                <p><strong>Fat:</strong> ${meal.fat.toFixed(2)}g</p>
                                <p><strong>Protein:</strong> ${meal.protein.toFixed(2)}g</p>
                                <p><strong>Carbs:</strong> ${meal.carbs.toFixed(2)}g</p>
                            </div>`;
                    });
                } else {
                    mealPlanHtml = '<p class="no-meals">No meals available for this week.</p>';
                }

                document.getElementById('meal-plan').innerHTML = mealPlanHtml;
            } catch (error) {
                document.getElementById('meal-plan').innerHTML = '<p class="error">Error loading meal plan.</p>';
                console.error(error);
            }
        }

        window.onload = loadMealPlan;
    </script>
</body>
</html>

