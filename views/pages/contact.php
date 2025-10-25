<?php
// contact.php
require_once 'config/helpers.php';

$page_title = "Contact Us - WC Clone";
$current_page = 'contact';
?>

<?php include 'views/partials/header.php'; ?>

<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="<?php echo getBaseUrl(); ?>" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-primary">
                    <i class="fas fa-home mr-2"></i>
                    Home
                </a>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Contact Us</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Contact Us</h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Have questions? We'd love to hear from you. Send us a message and we'll respond as soon as possible.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Contact Information -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Get in Touch</h2>
                    
                    <div class="space-y-6">
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-primary bg-opacity-10 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-map-marker-alt text-primary text-lg"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">Our Address</h3>
                                <p class="text-gray-600 mt-1">
                                    123 Commerce Street, Suite 100<br>
                                    New York, NY 10001
                                </p>
                            </div>
                        </div>
                        
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-primary bg-opacity-10 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-phone text-primary text-lg"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">Phone</h3>
                                <p class="text-gray-600 mt-1">+1 (555) 123-4567</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-primary bg-opacity-10 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-envelope text-primary text-lg"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">Email</h3>
                                <p class="text-gray-600 mt-1">support@wcclone.com</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-primary bg-opacity-10 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-clock text-primary text-lg"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">Business Hours</h3>
                                <p class="text-gray-600 mt-1">
                                    Mon-Fri: 9AM-6PM<br>
                                    Sat: 10AM-4PM<br>
                                    Sun: Closed
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Social Media -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <h3 class="font-semibold text-gray-900 mb-4">Follow Us</h3>
                        <div class="flex space-x-4">
                            <a href="#" class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center hover:bg-primary hover:text-white transition-colors">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center hover:bg-blue-400 hover:text-white transition-colors">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center hover:bg-pink-600 hover:text-white transition-colors">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center hover:bg-blue-700 hover:text-white transition-colors">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Send us a Message</h2>
                    
                    <form class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="firstName" class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                                <input type="text" id="firstName" name="firstName" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                                       placeholder="Your first name" required>
                            </div>
                            <div>
                                <label for="lastName" class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                                <input type="text" id="lastName" name="lastName" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                                       placeholder="Your last name" required>
                            </div>
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                            <input type="email" id="email" name="email" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                                   placeholder="your.email@example.com" required>
                        </div>
                        
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number (Optional)</label>
                            <input type="tel" id="phone" name="phone" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                                   placeholder="+1 (555) 123-4567">
                        </div>
                        
                        <div>
                            <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                            <select id="subject" name="subject" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                                <option value="">Select a subject</option>
                                <option value="general">General Inquiry</option>
                                <option value="support">Technical Support</option>
                                <option value="billing">Billing Question</option>
                                <option value="returns">Returns & Refunds</option>
                                <option value="wholesale">Wholesale Inquiry</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                            <textarea id="message" name="message" rows="6" 
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                                      placeholder="Please describe your inquiry in detail..." required></textarea>
                        </div>
                        
                        <button type="submit" 
                                class="w-full bg-primary text-white py-3 px-6 rounded-lg font-semibold hover:bg-primary-dark transition-colors flex items-center justify-center space-x-2">
                            <i class="fas fa-paper-plane"></i>
                            <span>Send Message</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'views/partials/footer.php'; ?>