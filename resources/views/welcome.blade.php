<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>InvoiceFlash — Professional Invoicing for Pakistani Freelancers</title>
    <meta name="description" content="Generate professional invoices in seconds. Built specifically for the Pakistani market with PKR support and local tax standards.">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, .font-display { font-family: 'Outfit', sans-serif; }
        
        .glass {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #FF6B00 0%, #FFB800 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-pattern {
            background-image: radial-gradient(#FF6B00 0.5px, transparent 0.5px);
            background-size: 24px 24px;
            opacity: 0.1;
        }
    </style>
</head>
<body class="antialiased bg-white text-slate-900 overflow-x-hidden">
    
    <!-- Navbar -->
    <nav class="fixed top-0 w-full z-[100] border-b border-slate-100 glass">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <a href="/" class="flex items-center gap-2 group">
                <div class="w-10 h-10 bg-brand rounded-xl flex items-center justify-center shadow-lg shadow-brand/20 group-hover:scale-110 transition-transform">
                    <i data-lucide="zap" class="w-6 h-6 text-white fill-white/20"></i>
                </div>
                <span class="text-xl font-black tracking-tight text-slate-900">Invoice<span class="text-brand">Flash</span></span>
            </a>
            
            <div class="hidden md:flex items-center gap-10">
                <a href="#features" class="text-sm font-semibold text-slate-600 hover:text-brand transition-colors">Features</a>
                <a href="#process" class="text-sm font-semibold text-slate-600 hover:text-brand transition-colors">How it Works</a>
                <a href="#pricing" class="text-sm font-semibold text-slate-600 hover:text-brand transition-colors">Pricing</a>
                <div class="w-px h-4 bg-slate-200"></div>
                @auth
                    <a href="{{ route('dashboard') }}" class="btn-primary btn-sm">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-bold text-slate-600 hover:text-brand transition-colors">Login</a>
                    <a href="{{ route('register') }}" class="btn-primary btn-sm">Get Started</a>
                @endauth
            </div>

            <!-- Mobile Menu Toggle -->
            <button class="md:hidden p-2 text-slate-600">
                <i data-lucide="menu" class="w-6 h-6"></i>
            </button>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 px-6">
        <div class="absolute inset-0 hero-pattern -z-10"></div>
        <div class="max-w-7xl mx-auto grid lg:grid-cols-2 gap-16 items-center">
            <div class="animate-fade-in">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-orange-50 border border-orange-100 text-brand text-xs font-bold uppercase tracking-widest mb-6">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-brand opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-brand"></span>
                    </span>
                    Perfect for Pakistani Freelancers
                </div>
                <h1 class="text-5xl sm:text-7xl font-black text-slate-900 leading-[1.1] mb-8">
                    Invoices that look <span class="gradient-text">expensive.</span>
                </h1>
                <p class="text-xl text-slate-500 mb-10 leading-relaxed max-w-xl">
                    Stop sending messy PDFs. Create stunning, professional invoices in PKR and get paid faster by clients worldwide. Built for the modern Pakistani workforce.
                </p>
                <div class="flex flex-col sm:flex-row items-center gap-4">
                    <a href="{{ route('register') }}" class="btn-primary btn-lg w-full sm:w-auto">
                        Start for Free
                        <i data-lucide="arrow-right" class="w-5 h-5"></i>
                    </a>
                    <a href="#features" class="btn-secondary btn-lg w-full sm:w-auto">
                        See Features
                    </a>
                </div>
                <div class="mt-12 flex items-center gap-6">
                    <div class="flex -space-x-3">
                        <img class="w-10 h-10 rounded-full border-2 border-white bg-slate-100" src="https://api.dicebear.com/7.x/avataaars/svg?seed=Felix" alt="User">
                        <img class="w-10 h-10 rounded-full border-2 border-white bg-slate-100" src="https://api.dicebear.com/7.x/avataaars/svg?seed=Aneka" alt="User">
                        <img class="w-10 h-10 rounded-full border-2 border-white bg-slate-100" src="https://api.dicebear.com/7.x/avataaars/svg?seed=James" alt="User">
                    </div>
                    <p class="text-sm text-slate-500">
                        Joined by <span class="font-bold text-slate-900">500+</span> professionals in Karachi, Lahore & Islamabad.
                    </p>
                </div>
            </div>
            
            <div class="relative animate-fade-in" style="animation-delay: 0.2s">
                <div class="absolute -inset-4 bg-gradient-to-tr from-brand/20 to-amber-500/20 blur-3xl -z-10 rounded-full"></div>
                <div class="relative rounded-2xl border border-slate-200 shadow-2xl overflow-hidden bg-white group">
                    <img src="{{ asset('images/hero-mockup.png') }}" alt="InvoiceFlash Dashboard" class="w-full h-auto transform group-hover:scale-[1.02] transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-white/20 to-transparent pointer-events-none"></div>
                </div>
                
                <!-- Floating Elements -->
                <div class="absolute -bottom-6 -left-6 glass border border-slate-200 p-4 rounded-2xl shadow-xl hidden sm:block animate-bounce" style="animation-duration: 4s">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-emerald-500 rounded-full flex items-center justify-center text-white">
                            <i data-lucide="check-circle" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">Payment Received</p>
                            <p class="text-sm font-black text-slate-900">PKR 45,000.00</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Social Proof -->
    <section class="py-12 border-y border-slate-100 bg-slate-50/50">
        <div class="max-w-7xl mx-auto px-6">
            <p class="text-center text-xs font-bold text-slate-400 uppercase tracking-[0.2em] mb-8">Used by professionals from</p>
            <div class="flex flex-wrap justify-center items-center gap-12 md:gap-20 opacity-40 grayscale hover:grayscale-0 transition-all duration-500">
                <span class="text-2xl font-black text-slate-900 italic tracking-tighter">Upwork</span>
                <span class="text-2xl font-black text-slate-900 tracking-tight underline decoration-brand decoration-4">Fiverr</span>
                <span class="text-2xl font-black text-slate-900 flex items-center gap-1"><i data-lucide="globe" class="w-6 h-6"></i> RemoteBase</span>
                <span class="text-2xl font-black text-slate-900 tracking-widest uppercase">Toptal</span>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-24 lg:py-32 px-6">
        <div class="max-w-7xl mx-auto">
            <div class="text-center max-w-3xl mx-auto mb-20">
                <h2 class="text-4xl sm:text-5xl font-black text-slate-900 mb-6">Everything you need to <span class="text-brand">manage billing.</span></h2>
                <p class="text-lg text-slate-500">We've stripped away the complexity of traditional accounting software to give you exactly what you need.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="p-8 rounded-3xl border border-slate-100 bg-white hover:border-brand/20 hover:shadow-2xl hover:shadow-brand/5 transition-all group">
                    <div class="w-14 h-14 bg-orange-50 rounded-2xl flex items-center justify-center text-brand mb-8 group-hover:scale-110 transition-transform">
                        <i data-lucide="zap" class="w-8 h-8 fill-brand/10"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-4">Instant Generation</h3>
                    <p class="text-slate-500 leading-relaxed">Create a professional invoice in under 30 seconds. Just select a client, add items, and you're done.</p>
                </div>
                <!-- Feature 2 -->
                <div class="p-8 rounded-3xl border border-slate-100 bg-white hover:border-brand/20 hover:shadow-2xl hover:shadow-brand/5 transition-all group">
                    <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600 mb-8 group-hover:scale-110 transition-transform">
                        <i data-lucide="landmark" class="w-8 h-8 fill-emerald-600/10"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-4">PKR Support</h3>
                    <p class="text-slate-500 leading-relaxed">Built-in support for Pakistani Rupee. Handle local taxes and regional formatting effortlessly.</p>
                </div>
                <!-- Feature 3 -->
                <div class="p-8 rounded-3xl border border-slate-100 bg-white hover:border-brand/20 hover:shadow-2xl hover:shadow-brand/5 transition-all group">
                    <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 mb-8 group-hover:scale-110 transition-transform">
                        <i data-lucide="file-text" class="w-8 h-8 fill-blue-600/10"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-4">Professional PDFs</h3>
                    <p class="text-slate-500 leading-relaxed">Download clean, minimalist PDF invoices that reflect your professional standard to global clients.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Process Section -->
    <section id="process" class="py-24 bg-slate-900 text-white px-6 overflow-hidden relative">
        <div class="absolute top-0 right-0 w-96 h-96 bg-brand opacity-10 blur-[120px] rounded-full"></div>
        <div class="max-w-7xl mx-auto">
            <div class="grid lg:grid-cols-2 gap-20 items-center">
                <div>
                    <h2 class="text-4xl font-black mb-12">How it <span class="text-brand">Works</span></h2>
                    <div class="space-y-12">
                        <div class="flex gap-6">
                            <div class="w-12 h-12 rounded-full border-2 border-brand flex items-center justify-center text-brand font-black shrink-0">1</div>
                            <div>
                                <h4 class="text-xl font-bold mb-2 text-white">Create Your Profile</h4>
                                <p class="text-slate-400">Add your business name, contact info, and upload your logo. We'll handle the branding.</p>
                            </div>
                        </div>
                        <div class="flex gap-6">
                            <div class="w-12 h-12 rounded-full border-2 border-brand flex items-center justify-center text-brand font-black shrink-0">2</div>
                            <div>
                                <h4 class="text-xl font-bold mb-2 text-white">Add Your Clients</h4>
                                <p class="text-slate-400">Store client details for quick selection. No more typing the same address over and over.</p>
                            </div>
                        </div>
                        <div class="flex gap-6">
                            <div class="w-12 h-12 rounded-full border-2 border-brand flex items-center justify-center text-brand font-black shrink-0">3</div>
                            <div>
                                <h4 class="text-xl font-bold mb-2 text-white">Generate & Send</h4>
                                <p class="text-slate-400">Enter your services, choose PKR or USD, and download your professional PDF in seconds.</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-16">
                        <a href="{{ route('register') }}" class="btn-primary btn-lg">Start Now — It's Free</a>
                    </div>
                </div>
                <div class="relative">
                    <div class="bg-white/5 border border-white/10 rounded-3xl p-4 backdrop-blur-sm">
                        <img src="https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&q=80&w=800" alt="Process" class="rounded-2xl opacity-80 grayscale hover:grayscale-0 transition-all duration-700">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="py-24 lg:py-32 px-6">
        <div class="max-w-7xl mx-auto">
            <div class="text-center max-w-2xl mx-auto mb-20">
                <h2 class="text-4xl font-black mb-6 text-slate-900">Simple, Transparent <span class="text-brand">Pricing</span></h2>
                <p class="text-lg text-slate-500">Built for freelancers, not large corporations. No hidden fees.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                <!-- Free Plan -->
                <div class="p-10 rounded-3xl border border-slate-200 bg-white flex flex-col items-start hover:shadow-xl transition-shadow">
                    <h3 class="text-xl font-bold text-slate-900 mb-2 font-display">Starter</h3>
                    <p class="text-slate-500 text-sm mb-6">Perfect for new freelancers.</p>
                    <div class="text-5xl font-black text-slate-900 mb-8 font-display">PKR 0<span class="text-sm font-normal text-slate-400">/forever</span></div>
                    <ul class="space-y-4 mb-10 text-slate-600 flex-1">
                        <li class="flex items-center gap-3"><i data-lucide="check" class="w-5 h-5 text-emerald-500"></i> 3 Invoices per month</li>
                        <li class="flex items-center gap-3"><i data-lucide="check" class="w-5 h-5 text-emerald-500"></i> Client Management</li>
                        <li class="flex items-center gap-3"><i data-lucide="check" class="w-5 h-5 text-emerald-500"></i> PDF Downloads</li>
                        <li class="flex items-center gap-3 text-slate-300 line-through"><i data-lucide="x" class="w-5 h-5"></i> Custom Branding</li>
                    </ul>
                    <a href="{{ route('register') }}" class="btn-secondary w-full">Choose Starter</a>
                </div>
                
                <!-- Pro Plan -->
                <div class="p-10 rounded-3xl bg-slate-900 text-white flex flex-col items-start relative overflow-hidden shadow-2xl shadow-brand/20">
                    <div class="absolute top-0 right-0 bg-brand text-white px-6 py-1 text-[10px] font-bold uppercase tracking-[0.2em] rounded-bl-2xl">Most Popular</div>
                    <h3 class="text-xl font-bold mb-2 font-display">Professional</h3>
                    <p class="text-slate-400 text-sm mb-6">For power users and agencies.</p>
                    <div class="text-5xl font-black text-white mb-8 font-display">PKR 999<span class="text-sm font-normal text-slate-500">/month</span></div>
                    <ul class="space-y-4 mb-10 text-slate-300 flex-1">
                        <li class="flex items-center gap-3"><i data-lucide="check" class="w-5 h-5 text-brand"></i> Unlimited Invoices</li>
                        <li class="flex items-center gap-3"><i data-lucide="check" class="w-5 h-5 text-brand"></i> Custom Logo & Branding</li>
                        <li class="flex items-center gap-3"><i data-lucide="check" class="w-5 h-5 text-brand"></i> Priority Support</li>
                        <li class="flex items-center gap-3"><i data-lucide="check" class="w-5 h-5 text-brand"></i> Multiple Currency Support</li>
                    </ul>
                    <a href="{{ route('register') }}" class="btn-primary w-full py-4 text-lg">Go Pro Now</a>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-24 bg-slate-50 px-6">
        <div class="max-w-4xl mx-auto">
            <h2 class="text-4xl font-black text-center mb-16">Frequently Asked <span class="text-brand">Questions</span></h2>
            <div class="space-y-6">
                <div class="p-6 bg-white rounded-2xl border border-slate-200">
                    <h4 class="text-lg font-bold mb-3 text-slate-900">Can I use this for international clients?</h4>
                    <p class="text-slate-600 leading-relaxed text-sm">Absolutely. While we specialize in PKR, you can easily set your invoice currency to USD, EUR, or GBP for your global clients.</p>
                </div>
                <div class="p-6 bg-white rounded-2xl border border-slate-200">
                    <h4 class="text-lg font-bold mb-3 text-slate-900">Is it really free for 3 invoices?</h4>
                    <p class="text-slate-600 leading-relaxed text-sm">Yes! We want to help freelancers just starting out. You can generate 3 invoices every month completely free of charge.</p>
                </div>
                <div class="p-6 bg-white rounded-2xl border border-slate-200">
                    <h4 class="text-lg font-bold mb-3 text-slate-900">Can I add my own logo?</h4>
                    <p class="text-slate-600 leading-relaxed text-sm">Yes, Pro users can upload their business logo and customize the brand colors on all generated invoices.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Final CTA -->
    <section class="py-24 px-6 relative overflow-hidden">
        <div class="max-w-5xl mx-auto bg-brand rounded-[3rem] p-12 lg:p-20 text-center text-white relative overflow-hidden shadow-2xl shadow-brand/40">
            <div class="absolute inset-0 hero-pattern opacity-20"></div>
            <h2 class="text-4xl lg:text-6xl font-black mb-8 relative z-10">Ready to look <br class="hidden md:block"> more professional?</h2>
            <p class="text-xl text-white/80 mb-12 max-w-2xl mx-auto relative z-10">Join 500+ Pakistani freelancers who trust InvoiceFlash to handle their billing.</p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-6 relative z-10">
                <a href="{{ route('register') }}" class="w-full sm:w-auto px-10 py-5 bg-white text-brand font-black rounded-2xl hover:bg-slate-50 transition-colors shadow-xl">
                    Create My First Invoice
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-20 border-t border-slate-100 px-6">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-12">
            <div class="col-span-1 md:col-span-2">
                <a href="/" class="flex items-center gap-2 mb-6">
                    <div class="w-8 h-8 bg-brand rounded-lg flex items-center justify-center">
                        <i data-lucide="zap" class="w-5 h-5 text-white"></i>
                    </div>
                    <span class="text-lg font-black tracking-tight text-slate-900">Invoice<span class="text-brand">Flash</span></span>
                </a>
                <p class="text-slate-500 max-w-xs leading-relaxed mb-8">
                    Empowering the Pakistani freelance community with professional billing tools that just work.
                </p>
                <div class="flex items-center gap-4 text-slate-400">
                    <a href="#" class="hover:text-brand"><i data-lucide="twitter" class="w-5 h-5"></i></a>
                    <a href="#" class="hover:text-brand"><i data-lucide="linkedin" class="w-5 h-5"></i></a>
                    <a href="#" class="hover:text-brand"><i data-lucide="github" class="w-5 h-5"></i></a>
                </div>
            </div>
            <div>
                <h4 class="font-bold text-slate-900 mb-6">Product</h4>
                <ul class="space-y-4 text-sm text-slate-500">
                    <li><a href="#features" class="hover:text-brand">Features</a></li>
                    <li><a href="#pricing" class="hover:text-brand">Pricing</a></li>
                    <li><a href="#" class="hover:text-brand">Roadmap</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold text-slate-900 mb-6">Legal</h4>
                <ul class="space-y-4 text-sm text-slate-500">
                    <li><a href="#" class="hover:text-brand">Privacy Policy</a></li>
                    <li><a href="#" class="hover:text-brand">Terms of Service</a></li>
                </ul>
            </div>
        </div>
        <div class="max-w-7xl mx-auto mt-20 pt-8 border-t border-slate-100 flex flex-col md:flex-row justify-between items-center gap-6">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">&copy; {{ date('Y') }} InvoiceFlash. Built in Pakistan.</p>
            <div class="flex items-center gap-2 px-3 py-1 bg-emerald-50 text-emerald-600 rounded-full text-[10px] font-black uppercase tracking-widest border border-emerald-100">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                </span>
                Systems Operational
            </div>
        </div>
    </footer>

    <!-- Initialize Lucide -->
    <script>
      lucide.createIcons();
    </script>
</body>
</html>
