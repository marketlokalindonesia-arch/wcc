<?php
// register.php
require_once 'config/helpers.php';


// Redirect jika sudah login
if(isset($_SESSION['user_id'])) {
    header('Location: ' . getBaseUrl());
    exit;
}

$page_title = "Register - WC Clone";
$current_page = 'register';
?>

<?php include 'views/partials/header.php'; ?>

<div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="flex justify-center">
            <div class="w-12 h-12 bg-gradient-to-r from-primary to-purple-600 rounded-lg flex items-center justify-center">
                <i class="fas fa-store text-white text-xl"></i>
            </div>
        </div>
        <h2 class="mt-6 text-center text-3xl font-bold text-gray-900">
            Create your account
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600">
            Or
            <a href="<?php echo getBaseUrl(); ?>login.php" class="font-medium text-primary hover:text-primary-dark">
                sign in to your existing account
            </a>
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
            <form class="space-y-6" action="#" method="POST">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="firstName" class="block text-sm font-medium text-gray-700">
                            First name
                        </label>
                        <div class="mt-1">
                            <input id="firstName" name="firstName" type="text" autocomplete="given-name" required 
                                   class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                        </div>
                    </div>

                    <div>
                        <label for="lastName" class="block text-sm font-medium text-gray-700">
                            Last name
                        </label>
                        <div class="mt-1">
                            <input id="lastName" name="lastName" type="text" autocomplete="family-name" required 
                                   class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                        </div>
                    </div>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">
                        Email address
                    </label>
                    <div class="mt-1">
                        <input id="email" name="email" type="email" autocomplete="email" required 
                               class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">
                        Password
                    </label>
                    <div class="mt-1">
                        <input id="password" name="password" type="password" autocomplete="new-password" required 
                               class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                    </div>
                </div>

                <div>
                    <label for="confirmPassword" class="block text-sm font-medium text-gray-700">
                        Confirm password
                    </label>
                    <div class="mt-1">
                        <input id="confirmPassword" name="confirmPassword" type="password" autocomplete="new-password" required 
                               class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                    </div>
                </div>

                <div class="flex items-center">
                    <input id="agree_terms" name="agree_terms" type="checkbox" required
                           class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                    <label for="agree_terms" class="ml-2 block text-sm text-gray-900">
                        I agree to the
                        <a href="#" class="text-primary hover:text-primary-dark">Terms and Conditions</a>
                        and
                        <a href="#" class="text-primary hover:text-primary-dark">Privacy Policy</a>
                    </label>
                </div>

                <div class="flex items-center">
                    <input id="newsletter" name="newsletter" type="checkbox" 
                           class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                    <label for="newsletter" class="ml-2 block text-sm text-gray-900">
                        Subscribe to our newsletter
                    </label>
                </div>

                <div>
                    <button type="submit" 
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                        Create account
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'views/partials/footer.php'; ?>