<?php 
$conn = new mysqli('localhost', 'root', '', 'eapp_db');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$menuQuery = "SELECT * FROM services"; 
$menuResult = $conn->query($menuQuery);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Barbra's Kitchen</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
<style>
* { margin:0; padding:0; box-sizing:border-box; font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif; }
body, html { scroll-behavior: smooth; }

body {
    color:white;
    overflow-x:hidden;
}

.row {
    margin-top: 5%;
    display: flex;
    justify-content: space-between;
    flex-direction: row;
    flex-wrap: wrap;
}


.nav-bar {
    position: fixed; top:0; left:0; width:100%;
    background: rgba(255,255,255,0.05);
    backdrop-filter: blur(1px);
    padding:15px 0; z-index:1000;
    box-shadow: 0 8px 20px rgba(0,0,0,0.4);
}
.nav-bar ul { display:flex; justify-content:center; gap:35px; list-style:none; }
.nav-bar ul li a {
    position:relative; text-decoration:none; color:white; font-weight:bold;
    padding:10px 15px; display:flex; align-items:center; gap:8px;
    transition: all 0.3s ease;letter-spacing: 1px;
    line-height: 1.5;border-right: 2px solid white;border-left: 2px solid white;border-radius: 10px;
}
.nav-bar ul li a::after { content:""; position:absolute; left:0; bottom:-4px; width:0%; height:3px; background:gold; transition: width 0.4s ease; border-radius:2px; }
.nav-bar ul li a:hover::after { width:100%; }
.nav-bar ul li a i { transition: transform 0.4s ease, color 0.3s ease; }
.nav-bar ul li a:hover i { transform: rotate(-15deg) scale(1.2); color:gold; }
.nav-bar ul li a.active { color:gold; }
.nav-bar ul li a.active::after { width:100%; }
@media(max-width:768px) { .nav-bar ul { flex-direction:column; gap:20px; } }

section { padding:100px 20px; min-height:100vh; position:relative; z-index:1; }

#welcome { color:white;background: url('../img/background.jpg');background-position: center;  background-repeat: no-repeat;
background-size: 1600px 870px;padding-bottom: -2px; }
#welcome h1 { margin-top:150px;font-size:60px; color:white; margin-bottom:20px; opacity:0; transform:translateY(-50px); animation:slideDown 1s forwards; }
#welcome hr { width:50%; height:2px; background-color:goldenrod; border:none; margin:20px auto; opacity:0; animation:expand 1.5s forwards; }
#welcome p { letter-spacing: 1px;line-height: 1.5;width:50%;font-size:20px; line-height:1.8; max-width:900px; margin:0 auto; opacity:0; animation:fadeIn 2s 1s forwards; }
.admin-btn { display:inline-block; margin:50px auto; padding:15px 40px; background-color:black; color:goldenrod; border-radius:10px; font-weight:bold; text-decoration:none; font-size:18px; transition:all 0.4s ease; box-shadow:0 5px 15px rgba(0,0,0,0.3); }
.admin-btn i { margin-right:12px; transition:transform 0.4s ease; }
.admin-btn:hover { background-color:goldenrod; color:black; transform:translateY(-5px); box-shadow:0 10px 25px rgba(0,0,0,0.4); }
.admin-btn:hover i { transform:rotate(20deg); }

#about {display: flex;padding-left: 20px;padding-top: 40px;padding-bottom: 0;background-image: linear-gradient(to right,grey,gray,goldenrod);background: goldenrod; }
.about-intro { max-width:900px; margin:0 auto 50px auto; text-align:center; }
.about-gallery {
    display:grid;
    grid-template-columns: repeat(3, 1fr);
    gap:15px;
    max-height:600px;
    overflow-y:auto;
    padding:10px;
}
.about-gallery img {
    width:100%; height:200px; object-fit:cover; border-radius:12px;
    transition: transform 0.5s, box-shadow 0.5s;
}
.about-gallery img:hover { transform: scale(1.08) rotateY(5deg); box-shadow:0 10px 20px rgba(0,0,0,0.5); }

#menu {background: goldenrod;color: black;margin-top: -32px;padding-top: 30px;}
#menu h1 { text-align:left;margin-top: 5px; margin-bottom:40px; color:black; text-shadow:1px 1px 5px rgba(0,0,0,0.7); }
.menu-container { display:flex; flex-wrap:wrap; justify-content:center; gap:25px; }
.menu-card { background:goldenrod; color:black; width:320px; padding:20px; border-radius:20px; box-shadow:0 10px 25px rgba(0,0,0,0.7); text-align:center; transition:all 0.4s ease; opacity:0; transform:translateX(-100px); }
.menu-card h2 { text-align:left;font-size:22px; margin-bottom:10px;letter-spacing: 1px;line-height: 1.8; }
.menu-card p { text-align:left;margin:8px 0; font-size:16px;letter-spacing: 1px;line-height: 1.8; }
.menu-card input, .menu-card textarea { letter-spacing: 1px;line-height: 1.5;width:100%; margin:5px 0; padding:8px; border-radius:5px; border:none; }
.view-btn { letter-spacing: 1px;line-height: 1.8;display:inline-block; margin-top:12px; padding:10px 25px; background-color:black; color:goldenrod; border-radius:10px; text-decoration:none; font-weight:bold; transition:all 0.3s ease; cursor:pointer; }
.view-btn:hover { background-color:goldenrod; color:black; transform:translateY(-3px); box-shadow:0 5px 20px rgba(0,0,0,0.5); }

#contact { background:rgba(0,0,0,0.5); }
.contact-methods { display:flex; flex-wrap:wrap; justify-content:center; gap:60px; margin-bottom:50px; }
.method { display:flex; align-items:center; gap:20px; min-width:250px; flex:1 1 250px; }
.method i { font-size:45px; color:goldenrod; min-width:60px; }
.method div h3 { font-size:20px; margin-bottom:5px; }
.map-section { text-align:center; padding:50px 20px; }
.map-section iframe { width:90%; max-width:600px; height:350px; border:none; border-radius:12px; transform:scale(1); transition:transform 0.3s ease; }

footer { text-align:center; padding:20px; background:rgba(0,0,0,0.7); border-top:3px solid gold; font-size:14px; }

@keyframes slideDown { to { opacity:1; transform:translateY(0); } }
@keyframes expand { to { opacity:1; width:50%; } }
@keyframes fadeIn { to { opacity:1; } }
.reveal { opacity:0; transform:translateX(-100px); transition: all 0.8s ease-out; }
.reveal.active { opacity:1; transform:translateX(0); }

.facilities {
    width: 100%;
    text-align: center;
    padding-top: 20px;
    background-image: linear-gradient(to top,grey, goldenrod, gray,goldenrod);
    background: goldenrod;
    color: black;
}

.facilities-col {
    flex-basis: 31.5%;
    border-radius: 10px;
    margin-bottom: 5%;
    text-align: left;
    
}

.facilities-col img {
    width: 100%;
    border-radius: 10px;
    height: 70%;
}

.facilities-col p {
    padding: 0;
    letter-spacing: 1px;
    line-height: 1.5;
    font-size: 17px;
    background: black;
    padding: 10px;
    border: 2px solid goldenrod;
    border-radius: 10px;
    color: goldenrod;
}

.facilities-col h1 {
    padding: 0;
    letter-spacing: 1px;
    line-height: 1.5;
}

.facilities-col h3 {
    margin-top: 10px;
    margin-bottom: 10px;
    text-align: center;
    letter-spacing: 1px;
    line-height: 1.5;
    color:goldenrod;
    font-weight: bold;
    background: linear-gradient(to right,black,black,goldenrod);
    padding: 10px;
    border: 2px solid goldenrod;
    border-radius: 10px;
}
</style>
</head>
<body>

<!-- NAVIGATION -->
<nav class="nav-bar">
<ul>
<li><a href="#welcome" class="nav-item active"><i class="fa-solid fa-house"></i> HOME</a></li>
<li><a href="#about" class="nav-item"><i class="fa-solid fa-users"></i> ABOUT</a></li>
<li><a href="#menu" class="nav-item"><i class="fa-solid fa-utensils"></i> MENU</a></li>
<li><a href="#contact" class="nav-item"><i class="fa-solid fa-phone-volume"></i> CONTACT</a></li>
</ul>
</nav>

<!-- HOME -->
<section id="welcome" >
<h1 style="color:white;text-align:center;">Welcome to Barbra's Kitchen</h1>
<hr>
<p>Your one-stop destination for delicious homemade meals delivered fresh to your doorstep. Discover a world of flavors crafted with love, using the finest ingredients to bring comfort and joy to every bite. Sit back, relax, and let us serve you the taste of home, anytime, anywhere.</p>
<a class="admin-btn" href="../admin/login.php"><i class="fa-solid fa-user-tie"></i>Login</a>
</section>

<!-- ABOUT -->
<section id="about">
<div class="about-intro">
    <img style="width: 850px; height: 600px;border-radius: 20px;" src="../img/view-vegan-spagetti-with-tomatoes-green-square-shaped-plate-cutlery-set-black-white-colors-background.jpg" alt="Baked Chicken">
</div>
<div class="about-intro" style="margin-left: 40px;">
<h4 style="color: goldenrod;font-weight: bold;border: 2px solid;width: fit-content;background:black;padding: 10px;border-radius: 10px;margin-left: 20px;">ABOUT US</h4>
<p style="font-size: 20px;color: black;gap:15px;margin: 5px;width: 80%;height:2px;text-align: justify;text-indent: 50px;letter-spacing: 1px;line-height:2.5;">Barbra's Kitchen brings the comfort of homemade meals to your doorstep. With fresh ingredients and a passion for cooking, we serve dishes that delight your taste buds and warm your heart.
    Your one-stop destination for delicious homemade meals delivered fresh to your doorstep. Discover a world of flavors crafted with love, using the finest ingredients to bring comfort and joy to every bite. Sit back, relax, and let us serve you the taste of home, anytime, anywhere.
</p>
</div>
</section>

<section class="facilities">
        <h4 style="color: goldenrod;font-weight: bold;border: 2px solid;width: fit-content;background:black;padding: 10px;border-radius: 10px;margin-left: 20px;">OUR SERVICES</h4>
        <p style="letter-spacing: 1px;line-height: 1.8;font-size:20px;margin-top: 10px;text-align:justify;">High quality services delivered by professionals is the foundation of our kitchen replicating growth everyday in our business.
            Discover a world of flavors crafted with love, using the finest ingredients to bring comfort and joy to every bite. Sit back, relax, and let us serve you the taste of home, anytime, anywhere.
        </p>

        <div class="row">
            <div class="facilities-col">
                <img src="../img/fresh-orange-juice-glass-dark-background.jpg" alt="">
                <h3>Delicious Drinks</h3>
                <p>We Serve You With The Best Drinks Made By Natural Fruits From Fertile Farms Countrywise.</p>
            </div>
            <div class="facilities-col">
                <img src="../img/young-smiling-afro-american-cook-chef-uniform-holds-knife-spatula-isolated-green-wall.jpg" alt="">
                <h3>Professional Chefs</h3>
                <p>Our Chefs are qualified to provide high quality services alongside maintaining the reputation of our company.</p>
            </div>
            <div class="facilities-col">
                <img src="../img/baked-chicken-drumsticks-honey-mustard-marinade.jpg" alt="">
                <h3>Taste and Healthy Meals</h3>
                <p>A quality cafeteria to ensure you're well served before, during and after your daily activities.</p>
            </div>
            <div class="facilities-col">
                <img src="../img/sour-curry-with-snakehead-fish-spicy-garden-hot-pot-thai-food.jpg" alt="">
                <h3>Taste and Healthy Meals</h3>
                <p>A quality cafeteria to ensure you're well served before, during and after your daily activities.</p>
            </div>
            <div class="facilities-col">
                <img src="../img/plate-biryani-with-bowl-rice-bowl-food-table.jpg" alt="">
                <h3>Taste and Healthy Meals</h3>
                <p>A quality cafeteria to ensure you're well served before, during and after your daily activities.</p>
            </div>
            <div class="facilities-col">
                <img src="../img/front-view-cup-tea-dark-table-color-dark-tea-ceremony.jpg" alt="">
                <h3>Taste and Healthy Meals</h3>
                <p>A quality cafeteria to ensure you're well served before, during and after your daily activities.</p>
            </div>
        </div>
    </section>


<!-- MENU -->
<section id="menu">
    <h4 style="color: goldenrod; font-weight: bold; border: 2px solid; width: fit-content; background: black; padding: 10px; border-radius: 10px; margin-left: 20px;">OUR MENU</h4>
    <p style="letter-spacing: 1px; line-height: 1.8; font-size: 20px; margin-bottom: 40px; margin-top: 10px; text-align: justify;">
        Feel Free to Navigate Through Our Menu and Enjoy The Best of Our Services.
    </p>

    <div class="menu-container">
        <?php if ($menuResult && $menuResult->num_rows > 0): ?>
            <?php while($row = $menuResult->fetch_assoc()): ?>
                <div class="menu-card reveal" style="border: 2px solid #ddd; padding: 20px; border-radius: 15px; margin: 15px; background: #fff; box-shadow: 0px 2px 6px rgba(0,0,0,0.1);">
                    <h2 style="color: #222;"><?= htmlspecialchars($row['name']); ?></h2>
                    <p><b>Category:</b> <?= htmlspecialchars($row['brand']); ?></p>
                    <p><b>Price:</b> Tsh. <?= number_format($row['price'], 0, '.', ','); ?>/=</p>


                    <input type="text" placeholder="Your Name" class="cust-name" style="width:100%;margin-bottom:5px;padding:8px;border-radius:8px;border:1px solid #ccc;">
                    <input type="text" placeholder="Phone Number" class="cust-phone" style="width:100%;margin-bottom:5px;padding:8px;border-radius:8px;border:1px solid #ccc;">
                    <textarea placeholder="Delivery Location" class="cust-location" style="width:100%;margin-bottom:10px;padding:8px;border-radius:8px;border:1px solid #ccc;"></textarea>

                    <div class="quantity-container" style="display:flex;align-items:center;gap:10px;margin-bottom:10px;">
                        <button class="minus" style="padding:5px 10px;font-size:20px;">−</button>
                        <input type="number" value="1" min="1" class="quantity" 
                               data-food="<?= htmlspecialchars($row['name']); ?>" 
                               data-price="<?= htmlspecialchars($row['price']); ?>"
                               style="width:60px;text-align:center;border:1px solid #ccc;border-radius:8px;padding:5px;">
                        <button class="plus" style="padding:5px 10px;font-size:20px;">+</button>
                    </div>

                            <a href="#" class="order-email-btn" 
        data-food="<?= htmlspecialchars($row['name']); ?>" 
        data-price="<?= htmlspecialchars($row['price']); ?>"
        style="display:inline-block;background:goldenrod;color:white;padding:10px 15px;border-radius:8px;text-decoration:none;font-weight:bold;margin-right:10px;">
        Order via Email
        </a>

                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="text-align:center; font-size:20px;">No menu items found.</p>
        <?php endif; ?>
    </div>
</section>

<!-- ORDER SCRIPT -->
<script>
document.querySelectorAll('.order-email-btn').forEach(btn => {
    btn.addEventListener('click', e => {
        e.preventDefault();
        const card = e.target.closest('.menu-card');

        const foodName = card.querySelector('.quantity').dataset.food;
        const foodPrice = parseFloat(card.querySelector('.quantity').dataset.price);
        const quantity = parseInt(card.querySelector('.quantity').value);
        const custName = card.querySelector('.cust-name').value.trim();
        const custPhone = card.querySelector('.cust-phone').value.trim();
        const custLocation = card.querySelector('.cust-location').value.trim();

        if (!custName || !custPhone || !custLocation) {
            alert('⚠️ Please fill all your details before ordering.');
            return;
        }

        const total = foodPrice * quantity;

        // Send order to database via AJAX
        fetch('insert_order.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `cust_name=${encodeURIComponent(custName)}&cust_phone=${encodeURIComponent(custPhone)}&cust_location=${encodeURIComponent(custLocation)}&food_name=${encodeURIComponent(foodName)}&quantity=${quantity}&total_price=${total}`
        })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success'){
                console.log('Order saved to DB ✅');
            } else {
                console.error('DB Error:', data.msg);
            }
        })
        .catch(err => console.error('Fetch Error:', err));

        // Open email as before
        const subject = encodeURIComponent(`New Order: ${foodName}`);
        const body = encodeURIComponent(
            `Hello Barbra's Kitchen! 👋\n\n` +
            `I would like to order:\n🍽 Food: ${foodName}\n🧮 Quantity: ${quantity}\n💰 Total Price: Tsh. ${total}\n\n` +
            `👤 Name: ${custName}\n📞 Phone: ${custPhone}\n📍 Location: ${custLocation}\n\nThank you!`
        );

        const email = "braycemnyama01@gmail.com"; // YOUR EMAIL
        window.location.href = `mailto:${email}?subject=${subject}&body=${body}`;
    });
});

</script>



<!-- CONTACT -->
<section id="contact" style="background: linear-gradient(to top, black, #2b2b2b); color: white; padding-top: 100px; padding-bottom: 40px;">
<h4 style="color: goldenrod;font-weight: bold;border: 2px solid;width: fit-content;background:black;padding: 10px;border-radius: 10px;margin-left: 20px;margin-bottom: 40px;">CONTACT US</h4>
  <div class="contact-methods" style="display:flex; flex-wrap:wrap; justify-content:center; gap:10px; margin-bottom:10px;padding: 10px;">
    
    <div class="method" style="display:flex; align-items:center; gap:20px; min-width:250px;">
      <i class="fa-solid fa-phone" style="font-size:45px; color:goldenrod;"></i>
      <div>
        <h3 style="font-size:22px; color:goldenrod;">Phone</h3>
        <p style="font-size:17px;">+255 764 857 518</p>
      </div>
    </div>

    <div class="method" style="display:flex; align-items:center; gap:20px; min-width:250px;">
      <i class="fa-brands fa-whatsapp" style="font-size:45px; color:goldenrod;"></i>
      <div>
        <h3 style="font-size:22px; color:goldenrod;">WhatsApp</h3>
        <p style="font-size:17px;"> +255 717 933 892</p>
      </div>
    </div>

    <div class="method" style="display:flex; align-items:center; gap:20px; min-width:250px;">
      <i class="fa-regular fa-envelope" style="font-size:45px; color:goldenrod;"></i>
      <div>
        <h3 style="font-size:22px; color:goldenrod;">Email</h3>
        <p style="font-size:17px;">kitchentz@gmail.com</p>
      </div>
    </div>

    <!-- <div class="method" style="display:flex; align-items:center; gap:20px; min-width:250px;">
      <i class="fa-solid fa-location-dot" style="font-size:45px; color:goldenrod;"></i>
      <div>
        <h3 style="font-size:22px; color:goldenrod;">Address</h3>
        <p style="font-size:17px;">Uhuru Street, Wilolesi, IRINGA</p>
      </div>
    </div> -->

    <div class="method" style="display:flex; align-items:center; gap:20px; min-width:250px;">
      <i class="fa-brands fa-instagram" style="font-size:45px; color:goldenrod;"></i>
      <div>
        <h3 style="font-size:22px; color:goldenrod;">Instagram</h3>
        <p style="font-size:17px;"><a href="https://www.instagram.com/barbraskitchen" target="_blank" style="color:white; text-decoration:none;">@barbraskitchen</a></p>
      </div>
    </div>

    <div class="method" style="display:flex; align-items:center; gap:20px; min-width:250px;">
      <i class="fa-brands fa-tiktok" style="font-size:45px; color:goldenrod;"></i>
      <div>
        <h3 style="font-size:22px; color:goldenrod;">TikTok</h3>
        <p style="font-size:17px;"><a href="https://www.tiktok.com/@barbraskitchen" target="_blank" style="color:white; text-decoration:none;">@barbraskitchen</a></p>
      </div>
    </div>
</div>
  </div>

  <div class="map-section" style="text-align:center;">
    <p style="font-size:18px; color:white; margin-bottom:25px;"><i class="fa-solid fa-location-dot" style="font-size:45px; color:goldenrod;"></i>Uhuru Street, Wilolesi, IRINGA - Near Ruaha Catholic University, Adjacent to ABSA Bank</p>
    <iframe 
      src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3953.1292986887784!2d35.69423677364318!3d-7.776111877142954!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1854162aaa7c8acd%3A0xd0f090f2a6bcb901!2sRuaha%20Catholic%20University%20(RUCU)!5e0!3m2!1sen!2stz!4v1737376710636!5m2!1sen!2stz" 
      allowfullscreen="" 
      loading="lazy" 
      id="mapFrame"
      style="width:90%; max-width:600px; height:350px; border:none; border-radius:15px; box-shadow:0 5px 15px rgba(0,0,0,0.6); transform:scale(1); transition:transform 0.3s ease;">
    </iframe>
  </div>
</section>


<footer>&copy; <?= date('Y'); ?> Barbra's Kitchen. All Rights Reserved.</footer>

<script>
// NAV HIGHLIGHT
const navItems = document.querySelectorAll('.nav-item');
const sections = document.querySelectorAll('section');
window.addEventListener('scroll', () => {
    let current = '';
    sections.forEach(section => {
        const sectionTop = section.offsetTop - 120;
        if (pageYOffset >= sectionTop) current = section.getAttribute('id');
    });
    navItems.forEach(item => {
        item.classList.remove('active');
        if(item.getAttribute('href') === '#' + current) item.classList.add('active');
    });
});

// SCROLL REVEAL
const revealElements = document.querySelectorAll('.reveal');
window.addEventListener('scroll', () => {
    const triggerBottom = window.innerHeight * 0.85;
    revealElements.forEach(el => {
        const elTop = el.getBoundingClientRect().top;
        if(elTop < triggerBottom) el.classList.add('active');
    });
});

// QUANTITY BUTTONS
document.querySelectorAll('.menu-card').forEach(card => {
    const minus = card.querySelector('.minus');
    const plus = card.querySelector('.plus');
    const input = card.querySelector('.quantity');

    minus.addEventListener('click', () => { if(input.value>1) input.value--; });
    plus.addEventListener('click', () => { input.value++; });
});

// ORDER VIA INSTAGRAM
document.querySelectorAll('.order-btn').forEach(btn => {
    btn.addEventListener('click', e => {
        e.preventDefault();
        const card = e.target.closest('.menu-card');

        const foodName = card.querySelector('.quantity').dataset.food;
        const foodPrice = parseFloat(card.querySelector('.quantity').dataset.price);
        const quantity = parseInt(card.querySelector('.quantity').value);
        const custName = card.querySelector('.cust-name').value.trim();
        const custPhone = card.querySelector('.cust-phone').value.trim();
        const custLocation = card.querySelector('.cust-location').value.trim();

        if(!custName || !custPhone || !custLocation){
            alert('Please fill all your details.');
            return;
        }

        const message = `Hello! I want to order:\nFood: ${foodName}\nQuantity: ${quantity}\nTotal Price: Tsh. ${foodPrice*quantity}\nName: ${custName}\nPhone: ${custPhone}\nLocation: ${custLocation}`;

        // Instagram DM link to your username
        const igLink = `https://www.instagram.com/direct/t/${'braycedominic11'}/?text=${encodeURIComponent(message)}`;
        window.open(igLink,'_blank');
    });
});


// MAP ZOOM
const map = document.getElementById('mapFrame');
map.addEventListener('mouseenter', () => { map.style.transform = 'scale(1.05)'; });
map.addEventListener('mouseleave', () => { map.style.transform = 'scale(1)'; });
</script>

</body>
</html>
