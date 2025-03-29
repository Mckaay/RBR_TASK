<x-base>
    <div class="w-full max-w-md">
        <div class="mb-5">
            <h1 class="text-2xl font-bold text-center text-gray-800">Login</h1>
        </div>
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="p-6">
                @if (Session::has('registered'))
                    <p class="text-green-500 font-medium text-center mb-2">Account created successfully! You can now log in with your credentials.</p>
                @endif
                <form method="POST" action="{{ route('login') }}">
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input name="email" type="email" id="email"
                               value="{{ old('email') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="name@example.com" required>
                    </div>
                    <div class="mb-4">
                        <div class="flex items-center justify-between mb-1">
                            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        </div>
                        <input name="password" type="password" id="password"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               required>
                    </div>
                    @if($errors->any())
                        @foreach($errors->all() as $error)
                            <p class="text-red-500 font-medium text-center mb-2">{{ $error }}</p>
                        @endforeach
                    @endif
                    <button type="submit"
                            class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                        Login
                    </button>
                    @csrf
                </form>
            </div>
            <div class="p-6 bg-gray-50 border-t border-gray-200">
                <p class="text-center text-sm text-gray-600">
                    Don't have an account?
                    <a href="{{ route('register') }}" class="font-medium text-blue-600 hover:underline">
                        Sign up
                    </a>
                </p>
            </div>
        </div>
    </div>
</x-base>
