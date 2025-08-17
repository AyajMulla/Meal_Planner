
document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('calculate-calories').addEventListener('click', async () => {
        const height = parseFloat(document.getElementById('height').value);
        const weight = parseFloat(document.getElementById('weight').value);
        const age = parseInt(document.getElementById('age').value);
        const gender = document.getElementById('gender').value;
        
        if (!height || !weight || !age || !gender) {
            alert('Please enter all details correctly.');
            return;
        }
        
        const dailyCalories = calculateBMR(height, weight, age, gender);
        const weeklyCalories = dailyCalories * 7;
        
        document.getElementById('daily-calories').textContent = `Daily Caloric Requirement: ${dailyCalories.toFixed(2)} kcal`;
        document.getElementById('weekly-calories').textContent = `Weekly Caloric Goal: ${weeklyCalories.toFixed(2)} kcal`;
    });
});

function calculateBMR(height, weight, age, gender) {
    if (gender === 'male') {
        return 10 * weight + 6.25 * height - 5 * age + 5;
    } else {
        return 10 * weight + 6.25 * height - 5 * age - 161;
    }
    
}

        //
        document.getElementById('download-pdf').addEventListener('click', async () => {
    const username = "<?= $username ?>";
    const { jsPDF } = window.jspdf;
    const pdf = new jsPDF();
    let yOffset = 20;

    // Title and Header
    pdf.setFontSize(16);
    pdf.text('Meal Plan for the Week', 10, yOffset);
    yOffset += 10;
    pdf.setFontSize(13);
    pdf.text(`Username: ${username}`, 10, yOffset);
    yOffset += 10;
    pdf.text(`Week: <?= $current_week_start->format('F jS') ?> - <?= $week_end->format('F jS') ?>`, 10, yOffset);
    yOffset += 10;

    // Iterate through each day and add meals with nutrition info
    document.querySelectorAll('.day-content').forEach(day => {
        const dayHeader = day.querySelector('.day-header').textContent;
        const mealItems = day.querySelectorAll('.meal-item');

        if (mealItems.length > 0) {
            pdf.setFontSize(14);
            yOffset += 10;
            pdf.text(dayHeader, 10, yOffset);  // Day header

            mealItems.forEach(meal => {
                const mealTitle = meal.querySelector('span').textContent;
                yOffset += 10;
                pdf.setFontSize(12);
                pdf.text(`- ${mealTitle}`, 15, yOffset);  // Meal title

                // Get corresponding nutrition info
                const nutritionInfo = meal.nextElementSibling;
                if (nutritionInfo && nutritionInfo.classList.contains('nutrition-info')) {
                    const nutritionText = nutritionInfo.innerHTML.replace(/<br>/g, '\n').trim();
                    const wrappedText = pdf.splitTextToSize(nutritionText, 180);
                    yOffset += 6;
                    wrappedText.forEach(line => {
                        pdf.text(20, yOffset, line);
                        yOffset += 6;
                    });
                }

                // Add new page if necessary
                if (yOffset > 270) {
                    pdf.addPage();
                    yOffset = 20;
                }
            });
        }
    });

    // Save the PDF
    pdf.save(`${username}-meal-plan.pdf`);
});



        document.querySelectorAll('.add-meal').forEach(button => {
            button.addEventListener('click', async (e) => {
                const day = e.target.closest('.day-content');
                const existingSearchBox = day.querySelector('.search-box');
                if (existingSearchBox) existingSearchBox.remove();

                let searchBox = document.createElement('div');
                searchBox.classList.add('search-box');
                searchBox.innerHTML = `
                    <input type="text" placeholder="Search for a meal..." class="meal-search" style="width: 100%; padding: 8px; border: 1px solid #cbd5e0; border-radius: 4px;">
                    <div class="meal-results" style="max-height: 150px; overflow-y: auto; border: 1px solid #cbd5e0; border-top: none; background: #fff; position: relative; z-index: 10; display: none;"></div>
                `;
                day.prepend(searchBox);

                const searchInput = searchBox.querySelector('.meal-search');
                const resultsBox = searchBox.querySelector('.meal-results');

                searchInput.addEventListener('input', async () => {
                    const query = searchInput.value.trim();
                    if (query.length < 3) return;

                    const meals = await searchMeals(query);
                    resultsBox.innerHTML = '';

                    if (meals.length > 0) {
                        resultsBox.style.display = 'block';
                        meals.forEach(meal => {
                            let mealItem = document.createElement('div');
                            mealItem.classList.add('meal-result-item');
                            mealItem.innerHTML = `
                                <img src="${meal.image}" alt="${meal.title}" width="30" height="30" style="border-radius: 4px; margin-right: 10px;">
                                <span>${meal.title}</span>
                            `;
                            mealItem.addEventListener('click', () => {
                                addMealToDay(day, meal);
                                searchBox.remove();
                            });
                            resultsBox.appendChild(mealItem);
                        });
                    } else {
                        resultsBox.style.display = 'none';
                    }
                });
            });
        });

        async function searchMeals(query) {
            const apiKey = '4a75880c34a4423c86f940366f63a7bc';
            const url = `https://api.spoonacular.com/recipes/complexSearch?query=${encodeURIComponent(query)}&addRecipeNutrition=true&apiKey=${apiKey}`;
            const response = await fetch(url);
            if (!response.ok) {
                console.error('Failed to fetch meals');
                return [];
            }
            const data = await response.json();
            return data.results;
        }

        function addMealToDay(day, meal) {
    const nutritionBox = day.querySelector('.nutrition-info');
    const mealItem = document.createElement('div');
    mealItem.classList.add('meal-item');
    
    const quantityInGrams = meal?.nutrition?.weightPerServing?.amount || 100; // Default to 100g if not available

    mealItem.innerHTML = `
        <div style="display: flex; align-items: center;">
            <img src="${meal.image}" alt="${meal.title}" width="20" height="20" />
            <span style="margin-left: 10px;">${meal.title}</span>
            <span style="margin-left: auto;">(${quantityInGrams}g)</span>
            <button class="remove-meal" style="margin-left: 10px; background: none; border: none; color: red; cursor: pointer;">✕</button>
        </div>
    `;

    mealItem.querySelector('.remove-meal').addEventListener('click', () => {
        removeMealFromDay(day, mealItem);
    });

    nutritionBox.parentNode.insertBefore(mealItem, nutritionBox);
    updateNutrition(day, meal, true);
}


function removeMealFromDay(day, mealItem) {
    mealItem.remove();
    const remainingMeals = day.querySelectorAll('.meal-item');
    const nutritionBox = day.querySelector('.nutrition-info');
    
    if (remainingMeals.length === 0) {
        nutritionBox.innerHTML = 'Calories: 0 | Protein: 0g | Carbs: 0g';
    } else {
        updateNutrition(day, null, false);
    }
}


        function updateNutrition(day, meal, isAdding) {
    const nutritionBox = day.querySelector('.nutrition-info');
    
    let calories = parseFloat(nutritionBox.getAttribute('data-calories')) || 0;
    let fat = parseFloat(nutritionBox.getAttribute('data-fat')) || 0;
    let protein = parseFloat(nutritionBox.getAttribute('data-protein')) || 0;
    let carbs = parseFloat(nutritionBox.getAttribute('data-carbs')) || 0;
    let cost = parseFloat(nutritionBox.getAttribute('data-cost')) || 0;
    let totalQuantity = parseFloat(nutritionBox.getAttribute('data-quantity')) || 0;

    const multiplier = isAdding ? 1 : -1;
    const quantityInGrams = meal?.nutrition?.weightPerServing?.amount || 100;

    calories += multiplier * (meal?.nutrition?.nutrients?.find(n => n.name === 'Calories')?.amount || 0);
    fat += multiplier * (meal?.nutrition?.nutrients?.find(n => n.name === 'Fat')?.amount || 0);
    protein += multiplier * (meal?.nutrition?.nutrients?.find(n => n.name === 'Protein')?.amount || 0);
    carbs += multiplier * (meal?.nutrition?.nutrients?.find(n => n.name === 'Carbohydrates')?.amount || 0);
    cost += multiplier * 5.00;
    totalQuantity += multiplier * quantityInGrams;

    nutritionBox.setAttribute('data-calories', calories.toFixed(2));
    nutritionBox.setAttribute('data-fat', fat.toFixed(2));
    nutritionBox.setAttribute('data-protein', protein.toFixed(2));
    nutritionBox.setAttribute('data-carbs', carbs.toFixed(2));
    nutritionBox.setAttribute('data-cost', cost.toFixed(2));
    nutritionBox.setAttribute('data-quantity', totalQuantity.toFixed(2));

    nutritionBox.innerHTML = `
        Calories: ${calories.toFixed(0)}<br>
        Fat: ${fat.toFixed(1)}g<br>
        Protein: ${protein.toFixed(1)}g<br>
        Carbs: ${carbs.toFixed(1)}g<br>
        Est. Cost: $${cost.toFixed(2)}<br>
        Total Quantity: ${totalQuantity.toFixed(0)}g<br>
    `;
}



document.addEventListener('DOMContentLoaded', () => {
    const toggleDarkModeButton = document.getElementById('toggle-dark-mode');
    const body = document.body;

    // Dark Mode Toggle
    toggleDarkModeButton.addEventListener('click', () => {
        body.classList.toggle('dark-mode');
    });

    // BMR Calculator
    document.getElementById('calculate-calories').addEventListener('click', () => {
        const height = parseFloat(document.getElementById('height').value);
        const weight = parseFloat(document.getElementById('weight').value);
        const age = parseInt(document.getElementById('age').value);
        const gender = document.getElementById('gender').value;
        
        if (!height || !weight || !age || !gender) {
            alert('Please fill out all fields.');
            return;
        }

        const dailyCalories = gender === 'male'
            ? 10 * weight + 6.25 * height - 5 * age + 5
            : 10 * weight + 6.25 * height - 5 * age - 161;

        document.getElementById('daily-calories').textContent = `Daily Caloric Requirement: ${dailyCalories.toFixed(2)} kcal`;
        document.getElementById('weekly-calories').textContent = `Weekly Caloric Goal: ${(dailyCalories * 7).toFixed(2)} kcal`;
    });

    // Generate Shopping List
    document.getElementById('generate-shopping-list').addEventListener('click', () => {
        alert('Shopping list feature coming soon!');
    });

    // Drag and Drop for Meals (Placeholder for future feature)
    document.querySelectorAll('.day-content').forEach(dayContent => {
        dayContent.addEventListener('dragover', e => e.preventDefault());
        dayContent.addEventListener('drop', () => {
            alert('Meal dropped! Feature under development.');
        });
    });
});
