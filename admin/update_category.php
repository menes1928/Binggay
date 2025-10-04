<?php

session_start();
require_once('../classes/database.php');
$con = new database();

$message = '';
$category = null;
$categoryFoods = [];
$allMenu = [];

// Get category ID from query string
if (isset($_GET['id'])) {
    $category_id = intval($_GET['id']);
    // Fetch the category details
    $categories = $con->viewCategories();
    foreach ($categories as $cat) {
        if ($cat['category_id'] == $category_id) {
            $category = $cat;
            break;
        }
    }
    if (!$category) {
        $message = "<div class='alert alert-danger'>Category not found.</div>";
    } else {
        $categoryFoods = $con->getFoodsByCategory($category_id);
        $allMenu = $con->getAllMenuItems();
    }
} else {
    $message = "<div class='alert alert-danger'>No category ID provided.</div>";
}

// Handle update form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['category_id']) && !empty($_POST['category_name'])) {
    $category_id = intval($_POST['category_id']);
    $category_name = trim($_POST['category_name']);
    $selected_foods = isset($_POST['foods']) ? $_POST['foods'] : [];

    // Update the category name
    $con_obj = $con->opencon();
    $stmt = $con_obj->prepare("UPDATE category SET category_name = ? WHERE category_id = ?");
    $stmt->execute([$category_name, $category_id]);

    // Clear all foods if requested
    if (isset($_POST['clear_foods'])) {
        $con->updateCategoryFoods($category_id, []);
        $selected_foods = [];
    } else {
        $con->updateCategoryFoods($category_id, $selected_foods);
    }

    $message = "<div class='alert alert-success'>Category updated successfully!</div>";

    // Refresh category data
    $categories = $con->viewCategories();
    foreach ($categories as $cat) {
        if ($cat['category_id'] == $category_id) {
            $category = $cat;
            break;
        }
    }
    $categoryFoods = $con->getFoodsByCategory($category_id);
    $allMenu = $con->getAllMenuItems();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Update Category</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .food-list { max-height: 200px; overflow-y: auto; }
    .food-item { display: flex; align-items: center; justify-content: space-between; }
  </style>
</head>
<body>
<div class="container mt-5">
  <h2>Update Category</h2>
  <?= $message ?>
  <?php if ($category): ?>
  <form method="POST">
    <input type="hidden" name="category_id" value="<?= $category['category_id'] ?>">
    <div class="mb-3">
      <label for="category_name" class="form-label">Category Name</label>
      <input type="text" class="form-control" id="category_name" name="category_name" value="<?= htmlspecialchars($category['category_name']) ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Foods in this Category</label>
      <div class="food-list border p-2 mb-2" id="category-foods-list">
        <?php foreach ($categoryFoods as $food): ?>
          <div class="food-item mb-1" data-id="<?= $food['menu_id'] ?>">
            <span><?= htmlspecialchars($food['menu_name']) ?></span>
            <button type="button" class="btn btn-sm btn-danger remove-food-btn" data-id="<?= $food['menu_id'] ?>">Remove</button>
            <input type="hidden" name="foods[]" value="<?= $food['menu_id'] ?>">
          </div>
        <?php endforeach; ?>
      </div>
      <button type="submit" name="clear_foods" value="1" class="btn btn-warning mb-2" onclick="return confirm('Clear all foods from this category?')">Clear Foods</button>
    </div>
    <div class="mb-3">
      <label for="food-search" class="form-label">Add Food to Category</label>
      <input type="text" class="form-control" id="food-search" placeholder="Search food...">
      <div class="food-list border p-2 mt-2" id="food-search-results"></div>
    </div>
    <button type="submit" class="btn btn-primary">Update Category</button>
    <a href="admin_homepage.php" class="btn btn-secondary">Back to Dashboard</a>
  </form>
  <script>
    // JS for searching and adding/removing foods
    const allMenu = <?= json_encode($allMenu) ?>;
    const searchInput = document.getElementById('food-search');
    const searchResults = document.getElementById('food-search-results');
    const foodsList = document.getElementById('category-foods-list');

    function renderSearchResults(query) {
      searchResults.innerHTML = '';
      if (!query) return;
      const lower = query.toLowerCase();
      const selectedIds = Array.from(foodsList.querySelectorAll('input[name="foods[]"]')).map(i => i.value);
      allMenu.filter(m => m.menu_name.toLowerCase().includes(lower) && !selectedIds.includes(m.menu_id))
        .forEach(m => {
          const div = document.createElement('div');
          div.className = 'food-item mb-1';
          div.innerHTML = `<span>${m.menu_name}</span>
            <button type="button" class="btn btn-sm btn-success add-food-btn" data-id="${m.menu_id}" data-name="${m.menu_name}">Add</button>`;
          searchResults.appendChild(div);
        });
    }

    searchInput.addEventListener('input', e => {
      renderSearchResults(e.target.value);
    });

    searchResults.addEventListener('click', function(e) {
      if (e.target.classList.contains('add-food-btn')) {
        const id = e.target.getAttribute('data-id');
        const name = e.target.getAttribute('data-name');
        // Add to foods list
        const div = document.createElement('div');
        div.className = 'food-item mb-1';
        div.setAttribute('data-id', id);
        div.innerHTML = `<span>${name}</span>
          <button type="button" class="btn btn-sm btn-danger remove-food-btn" data-id="${id}">Remove</button>
          <input type="hidden" name="foods[]" value="${id}">`;
        foodsList.appendChild(div);
        // Remove from search results
        e.target.parentElement.remove();
      }
    });

    foodsList.addEventListener('click', function(e) {
      if (e.target.classList.contains('remove-food-btn')) {
        e.target.parentElement.remove();
      }
    });
  </script>
  <?php else: ?>
    <a href="admin_homepage.php" class="btn btn-secondary">Back to Dashboard</a>
  <?php endif; ?>
</div>
</body>
</html>