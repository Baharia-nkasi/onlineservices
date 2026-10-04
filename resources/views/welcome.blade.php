cat > resources/views/welcome.blade.php <<'EOF'
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Services</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-800">

    <div class="min-h-screen flex items-center justify-center px-6">
        <div class="max-w-3xl w-full text-center bg-white rounded-2xl shadow-lg p-10">

            <h1 class="text-4xl font-bold text-blue-600">
                Online Services
            </h1>

            <p class="mt-4 text-lg text-gray-600">
                Welcome to our Online Services platform.
                Access services, submit applications and track your applications online.
            </p>

            <div class="mt-8 flex justify-center gap-4">
                <a href="{{ route('login') }}"
                   class="px-6 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700">
                    Log In
                </a>

                <a href="{{ route('register') }}"
                   class="px-6 py-3 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700">
                    Register
                </a>
            </div>

            <p class="mt-8 text-sm text-gray-500">
                Fast, simple and secure online services.
            </p>

        </div>
    </div>

</body>
</html>
EOF
