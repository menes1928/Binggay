<?php
session_start();
require_once __DIR__ . '/classes/database.php';

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);

    if ($email === '' || $password === '') {
        $error = 'Email and password are required';
    } else {
        try {
            $db = new database();
            $pdo = $db->opencon();
            $stmt = $pdo->prepare('SELECT user_id, user_fn, user_ln, user_username, user_email, user_password, user_type, user_photo FROM users WHERE user_email = ? LIMIT 1');
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            $isValid = false;
            $needsRehash = false;
            if ($user) {
                $stored = (string)$user['user_password'];
                // Primary: verify bcrypt/argon hash
                if (preg_match('/^\$2y\$/', $stored) || preg_match('/^\$argon2/', $stored)) {
                    $isValid = password_verify($password, $stored);
                    if ($isValid && password_needs_rehash($stored, PASSWORD_BCRYPT)) {
                        $needsRehash = true;
                    }
                } else {
                    // Legacy plaintext fallback: if stored equals provided password, accept and mark for rehash
                    if ($stored !== '' && hash_equals($stored, $password)) {
                        $isValid = true;
                        $needsRehash = true;
                    }
                }

                if ($isValid) {
                    // Optionally rehash to bcrypt for legacy/plaintext or weaker hashes
                    if ($needsRehash) {
                        try {
                            $newHash = password_hash($password, PASSWORD_BCRYPT);
                            $up = $pdo->prepare('UPDATE users SET user_password = ?, updated_at = NOW() WHERE user_id = ?');
                            $up->execute([$newHash, (int)$user['user_id']]);
                        } catch (Throwable $rehashErr) {
                            // Do not block login if rehash fails; optionally log
                        }
                    }

                    // Harden session
                    session_regenerate_id(true);
                    $_SESSION['user_id'] = (int)$user['user_id'];
                    // Store separate name parts and username for display in navbar
                    $_SESSION['user_fn'] = (string)($user['user_fn'] ?? '');
                    $_SESSION['user_ln'] = (string)($user['user_ln'] ?? '');
                    $_SESSION['user_username'] = (string)($user['user_username'] ?? '');
                    $_SESSION['user_name'] = trim(($_SESSION['user_fn'] ?? '') . ' ' . ($_SESSION['user_ln'] ?? ''));
                    $_SESSION['user_email'] = $user['user_email'];
                    $_SESSION['user_type'] = (int)$user['user_type'];
                    $_SESSION['user_photo'] = isset($user['user_photo']) ? (string)$user['user_photo'] : null;

                    // Simple role-based redirect: 1 = admin per sample data
                    if ((int)$user['user_type'] === 1) {
                        header('Location: admin/admin');
                    } else {
                        header('Location: user/home');
                    }
                    exit;
                }
            }
            $error = 'Invalid email or password';
        } catch (Throwable $e) {
            $error = 'Login failed: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sandok ni Binggay</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #1B4332;
            --primary-dark: #0d2419;
            --accent: #D4AF37;
            --accent-dark: #b8941f;
            --background: #fefffe;
            --muted: #f8f8f6;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background: linear-gradient(135deg, #fefffe 0%, #f8f8f6 100%);
        }

        .bg-primary { background-color: var(--primary); }
        .bg-accent { background-color: var(--accent); }
        .text-primary { color: var(--primary); }
        .text-accent { color: var(--accent); }
        .border-primary { border-color: var(--primary); }
        
        /* Gradient Background Animation */
        .gradient-bg {
            background: linear-gradient(135deg, var(--primary) 0%, #2d5a3d 50%, #1B4332 100%);
            background-size: 200% 200%;
            animation: gradientShift 15s ease infinite;
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Floating Animation */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        .float-animation {
            animation: float 6s ease-in-out infinite;
        }

        /* Fade In Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }

        .animate-fade-in-left {
            animation: fadeInLeft 0.8s ease-out;
        }

        .animate-fade-in-right {
            animation: fadeInRight 0.8s ease-out;
        }

        /* Delay classes */
        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
        .delay-300 { animation-delay: 0.3s; }
        .delay-400 { animation-delay: 0.4s; }
        .delay-500 { animation-delay: 0.5s; }

        /* Input Focus Effects */
        .input-field {
            transition: all 0.3s ease;
        }

        .input-field:focus {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(27, 67, 50, 0.15);
        }

        /* Button Hover Effects */
        .btn-primary {
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn-primary:hover::before {
            left: 100%;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(27, 67, 50, 0.3);
        }

        /* Decorative Elements */
        .decorative-circle {
            position: absolute;
            border-radius: 50%;
            background: rgba(212, 175, 55, 0.1);
            animation: pulse 4s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                opacity: 0.5;
            }
            50% {
                transform: scale(1.1);
                opacity: 0.3;
            }
        }

        /* Checkbox Custom Style */
        .custom-checkbox:checked {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        /* Shimmer effect for logo */
        @keyframes shimmer {
            0% { background-position: -1000px 0; }
            100% { background-position: 1000px 0; }
        }

        .shimmer {
            background: linear-gradient(90deg, transparent, rgba(212, 175, 55, 0.4), transparent);
            background-size: 1000px 100%;
            animation: shimmer 3s infinite;
        }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': '#1B4332',
                        'primary-dark': '#0d2419',
                        'accent': '#D4AF37',
                        'accent-dark': '#b8941f',
                        'muted': '#f8f8f6'
                    }
                }
            }
        }

        // Toggle password visibility
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }

        // Form validation
        function validateForm(event) {
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            let isValid = true;

            // Reset error messages
            document.getElementById('email-error').classList.add('hidden');
            document.getElementById('password-error').classList.add('hidden');

            // Email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!email || !emailRegex.test(email)) {
                document.getElementById('email-error').classList.remove('hidden');
                isValid = false;
            }

            // Password validation
            if (!password || password.length < 6) {
                document.getElementById('password-error').classList.remove('hidden');
                isValid = false;
            }

            if (!isValid) {
                event.preventDefault();
            }
        }

        // Add floating label effect
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('.input-field');
            
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.querySelector('label').classList.add('text-primary');
                });
                
                input.addEventListener('blur', function() {
                    if (!this.value) {
                        this.parentElement.querySelector('label').classList.remove('text-primary');
                    }
                });
            });
        });
    </script>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <!-- Background Decorative Elements -->
    <div class="decorative-circle" style="width: 400px; height: 400px; top: -200px; right: -200px;"></div>
    <div class="decorative-circle" style="width: 300px; height: 300px; bottom: -150px; left: -150px;"></div>

    <!-- Main Container -->
    <div class="w-full max-w-6xl">
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden grid md:grid-cols-2 min-h-[600px]">
            <!-- Left Side - Branding -->
            <div class="gradient-bg p-12 flex flex-col justify-center items-center text-white relative overflow-hidden hidden md:flex">
                <!-- Decorative Pattern -->
                <div class="absolute inset-0 opacity-10">
                    <div class="absolute top-0 left-0 w-full h-full" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
                </div>

                <div class="relative z-10 text-center animate-fade-in-left">
                    <!-- Logo -->
                    <div class="mb-8 float-animation">
                        <img src="https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=150&h=150&fit=crop&crop=center" 
                             alt="Sandok ni Binggay" 
                             class="w-32 h-32 mx-auto rounded-full border-4 border-white/30 shadow-2xl object-cover">
                    </div>

                    <!-- Branding Text -->
                    <h1 class="text-4xl font-bold mb-4 text-white">Sandok ni Binggay</h1>
                    <div class="h-1 w-24 bg-accent mx-auto mb-6 rounded-full"></div>
                    <p class="text-xl text-white/90 mb-8">Nothing Beats Home-Cooked Meals</p>

                    <!-- Features -->
                    <div class="space-y-4 text-left max-w-sm mx-auto">
                        <div class="flex items-center gap-3 text-white/90 animate-fade-in-left delay-100">
                            <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                                <i class="fas fa-utensils"></i>
                            </div>
                            <span>Authentic Filipino Cuisine</span>
                        </div>
                        <div class="flex items-center gap-3 text-white/90 animate-fade-in-left delay-200">
                            <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                                <i class="fas fa-star"></i>
                            </div>
                            <span>Premium Catering Services</span>
                        </div>
                        <div class="flex items-center gap-3 text-white/90 animate-fade-in-left delay-300">
                            <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                                <i class="fas fa-heart"></i>
                            </div>
                            <span>Made with Love & Care</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Login Form -->
            <div class="p-12 flex flex-col justify-center bg-white">
                <div class="max-w-md mx-auto w-full animate-fade-in-right">
                    <!-- Mobile Logo -->
                    <div class="md:hidden text-center mb-8">
                        <img src="../images/logo.png" 
                             alt="Sandok ni Binggay" 
                             class="w-20 h-20 mx-auto rounded-full border-4 border-primary shadow-lg object-cover mb-4">
                        <h2 class="text-2xl font-bold text-primary">Sandok ni Binggay</h2>
                    </div>

                    <!-- Welcome Text -->
                    <div class="mb-8 animate-fade-in-up">
                        <h2 class="text-3xl font-bold text-primary mb-2">Welcome Back!</h2>
                        <p class="text-gray-600">Sign in to access your account</p>
                    </div>

                    <?php if (!empty($_SESSION['registration_success'])): ?>
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-center gap-2 animate-fade-in-up">
                        <i class="fas fa-check-circle"></i>
                        <span>Registration successful. Please sign in.</span>
                    </div>
                    <?php unset($_SESSION['registration_success']); endif; ?>

                    <?php if (isset($error)): ?>
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg flex items-center gap-2 animate-fade-in-up">
                        <i class="fas fa-exclamation-circle"></i>
                        <span><?php echo htmlspecialchars($error); ?></span>
                    </div>
                    <?php endif; ?>

                    <!-- Login Form -->
                    <form method="POST" action="" onsubmit="validateForm(event)" class="space-y-6">
                        <!-- Email Input -->
                        <div class="animate-fade-in-up delay-100">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2 transition-colors">
                                <i class="fas fa-envelope mr-2 text-primary"></i>Email Address
                            </label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   class="input-field w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                   placeholder="you@example.com"
                                   required>
                            <p id="email-error" class="hidden text-red-500 text-sm mt-1">
                                <i class="fas fa-exclamation-circle mr-1"></i>Please enter a valid email address
                            </p>
                        </div>

                        <!-- Password Input -->
                        <div class="animate-fade-in-up delay-200">
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2 transition-colors">
                                <i class="fas fa-lock mr-2 text-primary"></i>Password
                            </label>
                            <div class="relative">
                                <input type="password" 
                                       id="password" 
                                       name="password" 
                                       class="input-field w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent pr-12"
                                       placeholder="Enter your password"
                                       required>
                                <button type="button" 
                                        onclick="togglePassword()" 
                                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-primary transition-colors">
                                    <i id="eye-icon" class="fas fa-eye"></i>
                                </button>
                            </div>
                            <p id="password-error" class="hidden text-red-500 text-sm mt-1">
                                <i class="fas fa-exclamation-circle mr-1"></i>Password must be at least 6 characters
                            </p>
                        </div>

                        <!-- Remember Me & Forgot Password -->
                        <div class="flex items-center justify-between animate-fade-in-up delay-300">
                            <label class="flex items-center cursor-pointer group">
                                <input type="checkbox" 
                                       name="remember" 
                                       class="custom-checkbox w-5 h-5 text-primary border-gray-300 rounded focus:ring-primary focus:ring-2 cursor-pointer">
                                <span class="ml-2 text-sm text-gray-600 group-hover:text-primary transition-colors">Remember me</span>
                            </label>
                            <a href="#" class="text-sm text-accent hover:text-accent-dark transition-colors font-medium">
                                Forgot Password?
                            </a>
                        </div>

                        <!-- Login Button -->
                        <button type="submit" 
                                name="login"
                                class="btn-primary w-full bg-primary hover:bg-primary-dark text-white font-medium py-3 rounded-lg transition-all animate-fade-in-up delay-400">
                            <i class="fas fa-sign-in-alt mr-2"></i>Sign In
                        </button>

                        <!-- Divider -->
                        <div class="relative my-6 animate-fade-in-up delay-500">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-300"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="px-4 bg-white text-gray-500">Or continue with</span>
                            </div>
                        </div>

                        <!-- Social Login Buttons -->
                        <div class="grid grid-cols-2 gap-4 animate-fade-in-up delay-500">
                            <button type="button" 
                                    class="flex items-center justify-center gap-2 px-4 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition-all hover:shadow-md">
                                <i class="fab fa-google text-red-500"></i>
                                <span class="text-sm font-medium text-gray-700">Google</span>
                            </button>
                            <button type="button" 
                                    class="flex items-center justify-center gap-2 px-4 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition-all hover:shadow-md">
                                <i class="fab fa-facebook text-blue-600"></i>
                                <span class="text-sm font-medium text-gray-700">Facebook</span>
                            </button>
                        </div>
                    </form>

                    <!-- Sign Up Link -->
                    <div class="mt-8 text-center animate-fade-in-up delay-500">
                        <p class="text-gray-600">
                            Don't have an account? 
                            <a href="registration" class="text-accent hover:text-accent-dark font-medium transition-colors">
                                Sign up now
                            </a>
                        </p>
                    </div>

                    <!-- Contact Info -->
                    <div class="mt-8 pt-6 border-t border-gray-200 text-center animate-fade-in-up delay-500">
                        <p class="text-sm text-gray-500 mb-2">Need help?</p>
                        <div class="flex justify-center gap-4 text-sm">
                            <a href="tel:0919-230-8344" class="text-primary hover:text-accent transition-colors">
                                <i class="fas fa-phone mr-1"></i>0919-230-8344
                            </a>
                            <a href="mailto:riatriumfo06@gmail.com" class="text-primary hover:text-accent transition-colors">
                                <i class="fas fa-envelope mr-1"></i>Email Us
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-8 text-gray-600 text-sm animate-fade-in-up delay-500">
            <p>&copy; <?php echo date('Y'); ?> Sandok ni Binggay. All rights reserved.</p>
            <p class="mt-2">
                <a href="#" class="hover:text-primary transition-colors">Privacy Policy</a>
                <span class="mx-2">•</span>
                <a href="#" class="hover:text-primary transition-colors">Terms of Service</a>
            </p>
        </div>
    </div>
</body>
</html>