document.addEventListener("DOMContentLoaded", () => {
  // Load initial data
  loadCategories();
  loadCourses();

  // Event delegation for category clicks
  document
    .getElementById("categories-container")
    .addEventListener("click", (e) => {
      if (e.target.classList.contains("category-btn")) {
        const categoryId = e.target.dataset.categoryId;
        loadCourses(categoryId);

        // Update active state
        document
          .querySelectorAll(".category-btn")
          .forEach((b) => b.classList.remove("active"));
        e.target.classList.add("active");
      }
    });
});

// Load and render categories
function loadCategories() {
  fetch("http://api.cc.localhost:8080/categories")
    .then((response) => response.json())
    .then((categories) => renderCategories(categories));
}

function renderCategories(categories, parentElement = null, level = 0) {
  const container =
    parentElement || document.getElementById("categories-container");
  const ul = document.createElement("ul");
  ul.className = `category-level-${level}`;

  categories.forEach((category) => {
    const li = document.createElement("li");
    const button = document.createElement("button");

    button.className = "category-btn";
    button.dataset.categoryId = category.id;
    button.innerHTML = `
            ${category.name}
            ${
              category.course_count > 0
                ? `<span class="count">(${category.course_count})</span>`
                : ""
            }
        `;

    li.appendChild(button);

    if (category.children && category.children.length > 0 && level < 3) {
      renderCategories(category.children, li, level + 1);
    }

    ul.appendChild(li);
  });

  if (parentElement) {
    parentElement.appendChild(ul);
  } else {
    container.appendChild(ul);
  }
}

function loadCourses(categoryId = null) {
  const url = categoryId
    ? `http://api.cc.localhost:8080/courses?category=${categoryId}`
    : "http://api.cc.localhost:8080/courses";

  fetch(url)
    .then((response) => response.json())
    .then((courses) => renderCourses(courses));
}

function renderCourses(courses) {
  const container = document.getElementById("courses-container");
  container.innerHTML = courses
    .map(
      (course) => `
        <div class="course-card">
            <h3 class="truncate">${course.title}</h3>
            <img src="${course.image_preview}" alt="${course.title}" class="course-image">
            <p class="truncate">${course.description}</p>
        </div>
    `
    )
    .join("");
}
