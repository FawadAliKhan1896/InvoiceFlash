# InvoiceFlash ⚡️

**InvoiceFlash** is a high-performance, minimalist invoicing SaaS designed specifically for freelancers and small businesses in Pakistan. It simplifies the billing process, allowing you to create professional PDF invoices in seconds, manage clients, and track payments with ease.

![Landing Page Mockup](public/images/hero-mockup.png)

## ✨ Key Features

- **🚀 Instant Invoicing:** Generate professional invoices in under 30 seconds.
- **🇵🇰 Localized for Pakistan:** Built-in support for PKR, local tax standards, and regional formatting.
- **📁 Client Management:** Securely store and manage client details for faster recurring billing.
- **📄 Professional PDFs:** Download clean, elegant PDF invoices ready for your global clients.
- **📊 Payment Tracking:** Keep track of paid, pending, and overdue invoices at a glance.
- **🎨 Custom Branding:** Upload your logo and customize invoice colors to match your brand (Pro).

## 🛠 Tech Stack

- **Framework:** [Laravel 11](https://laravel.com/)
- **Frontend:** [Tailwind CSS 4.0](https://tailwindcss.com/), [Alpine.js](https://alpinejs.dev/)
- **Build Tool:** [Vite](https://vitejs.dev/)
- **Database:** MySQL / PostgreSQL
- **Icons:** [Lucide Icons](https://lucide.dev/)

## 🚀 Getting Started

### Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js & NPM
- MySQL / PostgreSQL

### Installation

1. **Clone the repository:**
   ```bash
   git clone https://github.com/FawadAliKhan1896/InvoiceFlash
   cd invoiceflash
   ```

2. **Install dependencies:**
   ```bash
   composer install
   npm install
   ```

3. **Environment Setup:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *Configure your database settings in the `.env` file.*

4. **Run Migrations:**
   ```bash
   php artisan migrate
   ```

5. **Build Assets:**
   ```bash
   npm run dev
   ```

6. **Start the Server:**
   ```bash
   php artisan serve
   ```

## 📸 Screenshots

![Landing Page Mockup](public/images/1.png)

![Landing Page Mockup](public/images/2.png)

## 📄 License

This project is open-source and available under the [MIT License](LICENSE).

---

Built with ❤️ for the Pakistani Freelance Community.
