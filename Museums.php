<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Museums | SmartTicket</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }

    body {
      background: linear-gradient(to right, #a18cd1, #fbc2eb);
      padding: 20px;
    }

    h1.title {
      text-align: center;
      font-size: 2.5rem;
      margin-bottom: 20px;
      color: #fff;
    }

    .filter-section {
      background: rgba(255, 255, 255, 0.9);
      padding: 15px;
      border-radius: 10px;
      margin-bottom: 20px;
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      justify-content: center;
    }

    .filter-section input,
    .filter-section select {
      padding: 10px;
      border-radius: 5px;
      border: 1px solid #ccc;
      width: 200px;
    }

    .museum-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 30px;
      margin: 40px 0;
      padding: 0 20px;
    }

    .museum-card {
      background: rgba(44, 62, 80, 0.8);
      border-radius: 15px;
      overflow: hidden;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
      transition: transform 0.3s;
      height: 100%;
      display: flex;
      flex-direction: column;
    }

    .museum-card:hover {
      transform: translateY(-5px);
    }

    .museum-card img {
      width: 100%;
      height: 200px;
      object-fit: cover;
    }

    .museum-card-content {
      padding: 20px;
      display: flex;
      flex-direction: column;
      flex-grow: 1;
    }

    .museum-card-content h3 {
      font-size: 18px;
      margin-bottom: 10px;
      color: #ffd369;
    }

    .museum-card-content p {
      flex-grow: 1;
      font-size: 14px;
      color: #ccc;
    }

    .rating {
      font-size: 16px;
      margin: 10px 0;
      color: gold;
    }

    .view-btn {
      padding: 8px 14px;
      background-color: #ffd369;
      color: #1c1e26;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-weight: bold;
      align-self: flex-start;
    }

    @media (max-width: 1024px) {
      .museum-grid {
        grid-template-columns: repeat(2, 1fr);
      }
    }

    @media (max-width: 768px) {
      .museum-grid {
        grid-template-columns: 1fr;
      }
    }

    /* Popup styling */
    .popup {
      display: none;
      position: fixed;
      z-index: 1000;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.8);
      justify-content: center;
      align-items: center;
    }

    .popup-content {
      background: #1c1e26;
      padding: 30px;
      border-radius: 12px;
      width: 90%;
      max-width: 600px;
      color: #fff;
      box-shadow: 0 0 20px rgba(255, 255, 255, 0.1);
      position: relative;
    }

    .popup-content .close-btn {
      position: absolute;
      top: 10px;
      right: 15px;
      font-size: 24px;
      cursor: pointer;
      color: #ffd369;
    }

    iframe {
      margin-top: 15px;
      border-radius: 8px;
    }
  </style>
</head>

<body>

  <h1 class="title">Explore Museums</h1>

  <div class="filter-section">
    <input type="text" placeholder="Search Museum Name" />
    <select>
      <option value="">Select Location</option>
      <option value="Dhaka">Dhaka</option>
      <option value="Chattogram">Chattogram</option>
      <option value="Khulna">Khulna</option>
    </select>
    <select>
      <option value="">Type</option>
      <option value="Art">Art</option>
      <option value="History">History</option>
      <option value="Science">Science</option>
      <option value="Culture">Culture</option>
      <option value="Natural">Natural History</option>
    </select>
    <select>
      <option value="">Open Now?</option>
      <option value="yes">Yes</option>
      <option value="no">No</option>
    </select>
    <select>
      <option value="">Ticket Price</option>
      <option value="low">৳0–100</option>
      <option value="mid">৳101–300</option>
      <option value="high">৳301+</option>
    </select>
  </div>

  <div class="museum-grid">

    <!-- Museum 1 -->
    <div
      class="museum-card"
      data-name="Bangladesh National Museum"
      data-location="Dhaka"
      data-type="Culture"
      data-open="yes"
      data-price="low"
    >
      <img src="BD National Museum.jpg" alt="Bangladesh National Museum" />
      <div class="museum-card-content">
        <h3>Bangladesh National Museum</h3>
        <p>Showcasing history, culture, and heritage of Bangladesh.</p>
        <div class="rating">★★★★☆</div>
        <button class="view-btn" onclick="openPopup('popup1')">View Details</button>
      </div>
    </div>

    <!-- Museum 2 -->
    <div
      class="museum-card"
      data-name="Liberation War Museum"
      data-location="Dhaka"
      data-type="History"
      data-open="yes"
      data-price="low"
    >
      <img src="Liberationwarmuseum.jpg" alt="Liberation War Museum" />
      <div class="museum-card-content">
        <h3>Liberation War Museum</h3>
        <p>Dedicated to Bangladesh's struggle for independence.</p>
        <div class="rating">★★★★★</div>
        <button class="view-btn" onclick="openPopup('popup2')">View Details</button>
      </div>
    </div>

    <!-- Museum 3 -->
    <div
      class="museum-card"
      data-name="National Science & Technology Museum"
      data-location="Dhaka"
      data-type="Science"
      data-open="yes"
      data-price="low"
    >
      <img src="nationalscienceandtech.jpg" alt="National Science & Technology Museum" />
      <div class="museum-card-content">
        <h3>National Science & Technology Museum</h3>
        <p>Interactive exhibits on science, tech, and innovation.</p>
        <div class="rating">★★★★☆</div>
        <button class="view-btn" onclick="openPopup('popup3')">View Details</button>
      </div>
    </div>

        <!-- Museum 4 -->
        <div
          class="museum-card"
          data-name="Bangabandhu Military Museum"
          data-location="Dhaka"
          data-type="Military"
          data-open="yes"
          data-price="low"
        >
          <img src="military museum.jpg" alt="Bangabandhu Military Museum" />
          <div class="museum-card-content">
            <h3>Bangabandhu Military Museum</h3>
            <p>Exhibits on Bangladesh's military history and heritage honoring Bangabandhu Sheikh Mujibur Rahman.</p>
            <div class="rating">★★★★☆</div>
        <button class="view-btn" onclick="openPopup('popup4')">View Details</button>
          </div>
        </div>

        <!-- Museum 5 -->
        <div
          class="museum-card"
          data-name="Ethnological Museum of Chittagong"
          data-location="Chattogram"
          data-type="Culture"
          data-open="yes"
          data-price="low"
        >
          <img src="MuseumChittagong.jpg" alt="Ethnological Museum of Chittagong" />
          <div class="museum-card-content">
            <h3>Ethnological Museum of Chittagong</h3>
            <p>Showcasing the diverse ethnic communities of Bangladesh.</p>
            <div class="rating">★★★☆☆</div>
            <button class="view-btn" onclick="openPopup('popup5')">View Details</button>
          </div>
        </div>

        <!-- Museum 6 -->
        <div
          class="museum-card"
          data-name="Khulna Divisional Museum"
          data-location="Khulna"
          data-type="History"
          data-open="no"
          data-price="low"
        >
          <img src="Khulna Divisional Museum.jpg" alt="Khulna Divisional Museum" />
          <div class="museum-card-content">
            <h3>Khulna Divisional Museum</h3>
            <p>Focuses on the history and culture of the Khulna division.</p>
            <div class="rating">★★★☆☆</div>
            <button class="view-btn" onclick="openPopup('popup6')">View Details</button>
          </div>
        </div>


  </div>

  <!-- Popup 1 -->
  <div class="popup" id="popup1" style="display:none;">
    <div class="popup-content">
      <span class="close-btn" onclick="closePopup('popup1')">&times;</span>
      <h2>Bangladesh National Museum</h2>
      <p><strong>Description:</strong> The museum houses historical artifacts, art pieces, and natural history displays representing Bangladesh’s heritage.</p>
      <p><strong>Address:</strong> Shahbagh, Dhaka</p>
      <p><strong>Opening Hours:</strong> 10:00 AM – 6:00 PM</p>
      <p><strong>Ticket Price:</strong> ৳50</p>
      <p><strong>Contact:</strong> 02-58613500</p>
      <iframe
        src="https://maps.google.com/maps?q=bangladesh%20national%20museum&t=&z=13&ie=UTF8&iwloc=&output=embed"
        width="100%"
        height="200"
        frameborder="0"
        allowfullscreen
      ></iframe>
    </div>
  </div>

  <!-- Popup 2 -->
  <div class="popup" id="popup2" style="display:none;">
    <div class="popup-content">
      <span class="close-btn" onclick="closePopup('popup2')">&times;</span>
      <h2>Liberation War Museum</h2>
      <p><strong>Description:</strong> A tribute to the martyrs and freedom fighters of 1971, with exhibits including photographs, documents, and weapons.</p>
      <p><strong>Address:</strong> Agargaon, Dhaka</p>
      <p><strong>Opening Hours:</strong> 10:00 AM – 5:00 PM</p>
      <p><strong>Ticket Price:</strong> ৳30</p>
      <p><strong>Contact:</strong> 02-9126049</p>
      <iframe
        src="https://maps.google.com/maps?q=liberation%20war%20museum%20dhaka&t=&z=13&ie=UTF8&iwloc=&output=embed"
        width="100%"
        height="200"
        frameborder="0"
        allowfullscreen
      ></iframe>
    </div>
  </div>

  <!-- Popup 3 -->
  <div class="popup" id="popup3" style="display:none;">
    <div class="popup-content">
      <span class="close-btn" onclick="closePopup('popup3')">&times;</span>
      <h2>National Science & Technology Museum</h2>
      <p><strong>Description:</strong> A hub for learning about science and innovation, with interactive displays, experiments, and educational programs.</p>
      <p><strong>Address:</strong> Agargaon, Dhaka</p>
      <p><strong>Opening Hours:</strong> 9:00 AM – 5:00 PM</p>
      <p><strong>Ticket Price:</strong> ৳20</p>
      <p><strong>Contact:</strong> 02-8189020</p>
      <iframe
        src="https://maps.google.com/maps?q=national%20science%20and%20technology%20museum%20dhaka&t=&z=13&ie=UTF8&iwloc=&output=embed"
        width="100%"
        height="200"
        frameborder="0"
        allowfullscreen
      ></iframe>
    </div>
    </div>

    <!-- Popup 4 -->
  <!-- Popup 4 -->
  <div class="popup" id="popup4" style="display:none;">
    <div class="popup-content">
      <span class="close-btn" onclick="closePopup('popup4')">&times;</span>
      <h2>Bangabandhu Military Museum</h2>
      <p><strong>Description:</strong> Exhibits on Bangladesh's military history and heritage honoring Bangabandhu Sheikh Mujibur Rahman.</p>
      <p><strong>Address:</strong> Dhaka Cantonment, Dhaka</p>
      <p><strong>Opening Hours:</strong> 9:00 AM – 5:00 PM</p>
      <p><strong>Ticket Price:</strong> Free</p>
      <p><strong>Contact:</strong> 02-9123456</p>
      <iframe
        src="https://maps.google.com/maps?q=bangabandhu%20military%20museum%20dhaka&t=&z=13&ie=UTF8&iwloc=&output=embed"
        width="100%"
        height="200"
        frameborder="0"
        allowfullscreen
      ></iframe>
    </div>
  </div>


      <!-- Popup 5 -->
        <div class="popup" id="popup5" style="display:none;">
          <div class="popup-content">
            <span class="close-btn" onclick="closePopup('popup5')">&times;</span>
          <h2>Ethnological Museum of Chittagong</h2>
          <p><strong>Description:</strong> Showcasing the diverse ethnic communities of Bangladesh.</p>
          <p><strong>Address:</strong> Nasirabad, Chattogram</p>
          <p><strong>Opening Hours:</strong> 10:00 AM – 6:00 PM</p>
          <p><strong>Ticket Price:</strong> ৳40</p>
          <p><strong>Contact:</strong> 031-6543210</p>
          <iframe src="https://maps.google.com/maps?q=ethnological%20museum%20chattogram&t=&z=13&ie=UTF8&iwloc=&output=embed" width="100%" height="200" frameborder="0" allowfullscreen></iframe>
        </div>
      </div>

      <!-- Popup 6 -->
        <div class="popup" id="popup6" style="display:none;">
          <div class="popup-content">
            <span class="close-btn" onclick="closePopup('popup6')">&times;</span>
          <h2>Khulna Divisional Museum</h2>
          <p><strong>Description:</strong> Focuses on the history and culture of the Khulna division.</p>
          <p><strong>Address:</strong> Khulna City, Khulna</p>
          <p><strong>Opening Hours:</strong> 9:00 AM – 4:00 PM</p>
          <p><strong>Ticket Price:</strong> ৳20</p>
          <p><strong>Contact:</strong> 041-1234567</p>
          <iframe src="https://maps.google.com/maps?q=khulna%20divisional%20museum&t=&z=13&ie=UTF8&iwloc=&output=embed" width="100%" height="200" frameborder="0" allowfullscreen></iframe>
        </div>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const searchInput = document.querySelector(".filter-section input[type='text']");
      const locationSelect = document.querySelectorAll(".filter-section select")[0];
      const typeSelect = document.querySelectorAll(".filter-section select")[1];
      const openSelect = document.querySelectorAll(".filter-section select")[2];
      const priceSelect = document.querySelectorAll(".filter-section select")[3];

      const museumCards = document.querySelectorAll(".museum-card");

      function filterMuseums() {
        const searchText = searchInput.value.toLowerCase();
        const location = locationSelect.value;
        const type = typeSelect.value;
        const open = openSelect.value;
        const price = priceSelect.value;

        museumCards.forEach((card) => {
          const name = card.dataset.name.toLowerCase();
          const cardLocation = card.dataset.location;
          const cardType = card.dataset.type;
          const cardOpen = card.dataset.open;
          const cardPrice = card.dataset.price;

          // Match logic for price ranges
          let priceMatch = true;
          if (price) {
            if (price === "low") priceMatch = cardPrice === "low";
            else if (price === "mid") priceMatch = cardPrice === "mid";
            else if (price === "high") priceMatch = cardPrice === "high";
          }

          const match =
            (!searchText || name.includes(searchText)) &&
            (!location || cardLocation === location) &&
            (!type || cardType === type) &&
            (!open || cardOpen === open) &&
            priceMatch;

          card.style.display = match ? "block" : "none";
        });
      }

      searchInput.addEventListener("input", filterMuseums);
      locationSelect.addEventListener("change", filterMuseums);
      typeSelect.addEventListener("change", filterMuseums);
      openSelect.addEventListener("change", filterMuseums);
      priceSelect.addEventListener("change", filterMuseums);
    });

    function openPopup(id) {
      document.getElementById(id).style.display = "flex";
    }

    function closePopup(id) {
      document.getElementById(id).style.display = "none";
    }
  </script>

</body>


</html>
