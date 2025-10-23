<?php
// Подключение к базе данных
$conn = new mysqli('localhost', 'root', '', 'flower_shop');
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

// Получаем все товары
$result = $conn->query("SELECT * FROM products");
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Play:wght@700&display=swap" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://fonts.googleapis.com/css2?family=Encode+Sans+Semi+Condensed&display=swap" rel="stylesheet">
    <!--Библиотека jQuery-->
    <script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>

    <title>Buketeria</title>
</head>
<body>


    <body id="bgImage">


     <div class="container">
        <header>
      <div class="logo">BUKETERIA</div>
      <div class="location">📍 Казань ▼</div>
      <nav class="nav">
        <a href="contact.html">Контакты</a>
        <a href="about.html">О сервисе</a>
        <a href="#">Доставка</a>
        <a href="cart.html">Корзина</a>
        <a href="register.html">Вход</a>
      </nav>


      <!-- Бургер-меню для мобильных -->
    <div class="burger-wrapper">
      <button class="burger-menu" aria-label="Меню">&#9776;</button>
      <nav class="burger-nav">
        <ul>
          <li><a href="contact.html">Контакты</a></li>
          <li><a href="about.html">О сервисе</a></li>
          <li><a href="#">Доставка</a></li>
          <li><a href="cart.html">Корзина</a></li>
          <li><a href="register.html">Вход</a></li>
        </ul>
      </nav>
    </div>

         <script>
      const burgerBtn = document.querySelector('.burger-menu');
      const burgerNav = document.querySelector('.burger-nav');

      burgerBtn.addEventListener('click', () => {
        burgerNav.classList.toggle('active');
        burgerBtn.classList.toggle('active');

        // Меняем иконку
        if (burgerBtn.classList.contains('active')) {
          burgerBtn.innerHTML = '&#10005;'; // крестик ×
        } else {
          burgerBtn.innerHTML = '&#9776;'; // бургер ≡
        }
      });

      // Закрытие меню при клике вне его
      document.addEventListener('click', (e) => {
        if (!burgerBtn.contains(e.target) && !burgerNav.contains(e.target)) {
          burgerNav.classList.remove('active');
          burgerBtn.classList.remove('active');
          burgerBtn.innerHTML = '&#9776;'; // вернуть иконку бургер
        }
      });
    </script>
      </header>
    </div>



    <div class="season-banner"></div>
    <script>
        const banner = document.querySelector('.season-banner');
    const month = new Date().getMonth() + 1; // месяц 1–12

    let seasonImg = '';

    if (month >= 3 && month <= 5) {
      seasonImg = 'images/spring.jpg';  // Весна: март, апрель, май
    } else if (month >= 6 && month <= 8) {
      seasonImg = 'images/summer.jpg';  // Лето: июнь, июль, август
    } else if (month >= 9 && month <= 11) {
      seasonImg = 'images/autumn.jpg';  // Осень: сентябрь, октябрь, ноябрь
    } else {
      seasonImg = 'images/winter.jpg';  // Зима: декабрь, январь, февраль
    }

    banner.style.backgroundImage = `url(${seasonImg})`;
    </script>

    <section class="intro">
    <div class="container">
    <h1>Доставка цветов в Казани</h1>
    <div class="product-filters">
      <input type="text" id="filter-search" placeholder="Поиск по названию..." />
      <input type="number" id="filter-price-min" placeholder="Мин. цена" min="0" />
      <input type="number" id="filter-price-max" placeholder="Макс. цена" min="0" />
      <button id="apply-filter-button">Применить</button>
    </div>
    <script>
      document.addEventListener("DOMContentLoaded", function () {
        const cards = document.querySelectorAll(".card");
        const searchInput = document.getElementById("filter-search");
        const minPriceInput = document.getElementById("filter-price-min");
        const maxPriceInput = document.getElementById("filter-price-max");
        const applyButton = document.getElementById("apply-filter-button");

        function getPriceValue(priceText) {
          return parseInt(priceText.replace(/[^\d]/g, "")) || 0;
        }

        function filterCards() {
          const searchQuery = searchInput.value.trim().toLowerCase();
          const minPrice = parseInt(minPriceInput.value) || 0;
          const maxPrice = parseInt(maxPriceInput.value) || Infinity;

          cards.forEach(card => {
            const title = card.querySelector("h4").textContent.toLowerCase();
            const priceText = card.querySelector(".price").textContent;
            const price = getPriceValue(priceText);

            const matchSearch = title.includes(searchQuery);
            const matchPrice = price >= minPrice && price <= maxPrice;

            if (matchSearch && matchPrice) {
              card.style.display = "flex";
            } else {
              card.style.display = "none";
            }
          });
        }

        applyButton.addEventListener("click", filterCards);
      });
    </script>


    <div class="filters">
      <button data-filter="all" class="active">Все</button>
      <button data-filter="roses">Розы</button>
      <button data-filter="summer">Лето</button>
      <button data-filter="premium">Премиум</button>
      <button data-filter="holiday">Праздники</button>
    </div>

    <div class="catalog" id="catalog">
    <?php while($row = $result->fetch_assoc()): ?>
    <div class="card" data-category="<?= htmlspecialchars($row['category']) ?>">
        <div>
            <img src="<?= htmlspecialchars($row['image_url']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
            <h4><?= htmlspecialchars($row['name']) ?></h4>
        </div>
        <div>
            <div class="price"><?= number_format($row['price'], 2, ',', ' ') ?> ₽</div>
            <div class="actions">
                <button title="В корзину">Купить</button>
            </div>
        </div>
    </div>
<?php endwhile; ?>
</div>

<?php $conn->close(); ?>
<!--
      <div class="card" data-category="roses">
       <div>
        <img src="assets/img/kenia.jpg" alt="Кенийские Розы" />
        <h4>Кенийские Розы</h4>
        </div>
        <div>
        <div class="price">3290 ₽</div>
        <div class="actions">
          <button title="В корзину">Купить</button>
        </div>
        </div>
      </div>

      <div class="card" data-category="summer">
       <div>
        <img src="assets/img/leto.jpg" alt="Цветы Лета" />
        <h4>Цветы Лета</h4>
        </div>
        <div>
        <div class="price">4290 ₽</div>
        <div class="actions">
          <button title="В корзину">Купить</button>
          </div>
        </div>
      </div>

      <div class="card" data-category="premium">
       <div>
        <img src="assets/img/esmiralda.jpg" alt="Эсмеральда" />
        <h4>Эсмеральда</h4>
        </div>
        <div>
        <div class="price">7990 ₽</div>
        <div class="actions">
          <button title="В корзину">Купить</button>
        </div>
        </div>
      </div>

      <div class="card" data-category="roses">
       <div>
        <img src="assets/img/kenia.jpg" alt="Кенийские Розы" />
        <h4>Кенийские Розы</h4>
        </div>
        <div>
        <div class="price">3290 ₽</div>
        <div class="actions">
          <button title="В корзину">Купить</button>
        </div>
        </div>
      </div>

      <div class="card" data-category="roses">
       <div>
        <img src="assets/img/kenia.jpg" alt="Кенийские Розы" />
        <h4>Кенийские Розы</h4>
        </div>
        <div>
        <div class="price">3290 ₽</div>
        <div class="actions">
          <button title="В корзину">Купить</button>
        </div>
        </div>
      </div>

      <div class="card" data-category="summer">
       <div>
        <img src="assets/img/leto.jpg" alt="Цветы Лета" />
        <h4>Цветы Лета</h4>
        </div>
        <div>
        <div class="price">4290 ₽</div>
        <div class="actions">
          <button title="В корзину">Купить</button>
        </div>
        </div>
      </div>

      <div class="card" data-category="premium">
       <div>
        <img src="assets/img/esmiralda.jpg" alt="Эсмеральда" />
        <h4>Эсмеральда</h4>
        </div>
        <div>
        <div class="price">7990 ₽</div>
        <div class="actions">
          <button title="В корзину">Купить</button>
        </div>
        </div>
      </div>

      <div class="card" data-category="roses">
       <div>
        <img src="assets/img/rozi.jpg" alt="Красочные Розы" />
        <h4>Красочные Розы</h4>
        </div>
        <div>
        <div class="price">3290 ₽</div>
        <div class="actions">
          <button title="В корзину">Купить</button>
        </div>
        </div>
      </div>
-->
    </div>
    <!-- Иконка корзины в виде SVG -->
    <a href="cart.html" id="cart-container" style="position:fixed; bottom:20px; right:20px; cursor:pointer; display:flex; align-items:center;">
      <!-- Новая SVG-иконка корзины -->
      <svg id="cart-icon" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="9" cy="21" r="1"></circle>
        <circle cx="20" cy="21" r="1"></circle>
        <path d="M1 1h4l2.68 13.39a1 1 0 0 0 1 .61h9.72a1 1 0 0 0 1-.76l1.38-7.21A1 1 0 0 0 20 6H6"></path>
      </svg>
      <div id="cart-count" style="margin-left:8px; font-weight:bold; font-size:18px;">0</div>
    </a>
    <!-- корзина  -->
    <script>
      // Инициализация корзины из Local Storage
      let cartItems = [];

      function loadCart() {
        const data = localStorage.getItem('cart');
        if (data) {
          return JSON.parse(data);
        }
        return [];
      }

      function saveCart() {
        localStorage.setItem('cart', JSON.stringify(cartItems));
      }

      // Обновление счетчика в иконке корзины
      function updateCartCount() {
        document.getElementById('cart-count').textContent = cartItems.length;
      }

      document.addEventListener('DOMContentLoaded', () => {
        // Загрузить корзину и обновить счетчик
        cartItems = loadCart();
        updateCartCount();

        // Обработчик для кнопок "Купить"
        const buyButtons = document.querySelectorAll('button[title="В корзину"]');

        // Обработчик для кнопок "Купить"
    buyButtons.forEach(btn => {
      btn.addEventListener('click', () => {
        const card = btn.closest('.card');
        if (!card) return;

        const title = card.querySelector('h4')?.textContent.trim() || 'Без названия';
        const priceText = card.querySelector('.price')?.textContent.trim() || 'Цена не указана';
        const imgSrc = card.querySelector('img')?.getAttribute('src') || '';

        const product = { title, price: priceText, img: imgSrc };

        // Добавляем товар в корзину
        cartItems.push(product);
        saveCart();
        updateCartCount();
      });
    });
      });
    </script>



    <!-- сезоны  -->
    <script>
      const buttons = document.querySelectorAll('.filters button');
      const cards = document.querySelectorAll('.catalog .card');

      buttons.forEach(button => {
        button.addEventListener('click', () => {
          buttons.forEach(btn => btn.classList.remove('active'));
          button.classList.add('active');
          const filter = button.getAttribute('data-filter');
          cards.forEach(card => {
            if (filter === 'all' || card.getAttribute('data-category') === filter) {
              card.style.display = 'flex';
            } else {
              card.style.display = 'none';
            }
          });
        });
      });
    </script>
    </div>
    </section>

    <script>
      function setSeasonClass() {
        const body = document.body;
        const month = new Date().getMonth() + 1; // 1..12

        // Снимаем все темы, если были
        body.classList.remove('spring', 'summer', 'autumn', 'winter');

        if (month >= 3 && month <= 5) {
          body.classList.add('spring');
        } else if (month >= 6 && month <= 8) {
          body.classList.add('summer');
        } else if (month >= 9 && month <= 11) {
          body.classList.add('autumn');
        } else {
          body.classList.add('winter');
        }
      }

      document.addEventListener('DOMContentLoaded', setSeasonClass);
    </script>



    </body>



    <script src="assets/js/app.min.js"></script>

</body>
</html>
