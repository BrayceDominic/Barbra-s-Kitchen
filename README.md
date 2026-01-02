# Babras Kitchen

## Overview

Babras Kitchen is a static web-based food ordering website built to simplify how customers place food orders and locate a food business. Instead of using forms, databases, or user accounts, the system relies entirely on **URL redirection** to external services such as WhatsApp and Google Maps.

This design choice ensures the application is fast, easy to maintain, and suitable for small food businesses or MVP demonstrations.

---

## Core Concept: Redirection-Based Interaction

All user actions in Babras Kitchen are handled through **client-side redirects**. When a user clicks a button, they are redirected to an external service with pre-filled information.

No data is stored, processed, or validated on a backend server.

---

## Functional Components

### 1. Menu Presentation

* Food items are displayed using static HTML
* Each item includes name, description, and price
* Menu updates are done directly in the HTML file

---

### 2. WhatsApp Order Redirection

Food orders are placed using WhatsApp Click-to-Chat redirection.

When a user clicks the order button:

1. JavaScript constructs a WhatsApp URL
2. The URL contains a pre-filled message with order details
3. The browser redirects the user to WhatsApp Web or the WhatsApp mobile app

#### WhatsApp Redirect Format

```
https://wa.me/<PHONE_NUMBER>?text=<ENCODED_MESSAGE>
```

The message is URL-encoded to ensure compatibility across browsers and devices.

---

### 3. Google Maps Location Redirection

The website provides a location button that redirects users to Google Maps.

When clicked:

* The browser opens Google Maps
* Navigation is automatically set to the kitchen’s predefined address or coordinates

#### Google Maps Redirect Format

```
https://www.google.com/maps?q=<LATITUDE>,<LONGITUDE>
```

This approach works on both desktop and mobile platforms.

---

## Technical Stack

### Frontend

* HTML for structure
* CSS for layout and responsiveness
* JavaScript for dynamic link generation and redirection

### Backend

* Not implemented
* No server-side logic or database

---

## Project Structure

```
Babras-Kitchen/
│
├── index.html        # Home page
├── menu.html         # Menu and ordering logic
├── about.html        # Business information
├── contact.html      # Contact and location links
│
├── css/
│   └── style.css     # Styling and responsive rules
│
├── js/
│   └── script.js     # Redirect and message generation logic
│
└── assets/
    └── images/       # Static assets
```

---

## Execution Flow

1. User opens the website in a browser
2. User views the available menu
3. User clicks the order button
4. JavaScript generates a redirect URL
5. User is redirected to WhatsApp with a pre-filled message
6. User sends the message manually

For location access:

1. User clicks the location button
2. Browser redirects to Google Maps
3. Navigation is displayed automatically

---

## Deployment

Because the project is fully static, it can be deployed on any static hosting service:

* GitHub Pages
* Netlify
* Vercel
* Shared hosting

No environment variables or server configuration are required.

---

## System Limitations

* No order persistence or history
* No payment processing
* No authentication or authorization
* Relies on third-party service availability

---

## Extension Possibilities

* Add backend API for order storage
* Integrate mobile money payments
* Build an admin dashboard
* Add order confirmation and tracking
* Convert to a Progressive Web App (PWA)

---

## Author

Brayce Dominic
Bachelor of Computer Science with Software Engineering
Tanzania

---

## License

This project is intended for educational use and small business demonstrations. Redistribution and modification are permitted.
