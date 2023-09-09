<x-guest-layout>
    <div class="w-full md:w-1/2">
        <div class="bg-white rounded-lg shadow-lg">
            <div class="bg-blue-500 text-black font-semibold py-2 px-4">
                Authorization Request
            </div>
            <div class="p-4">
                <!-- Introduction -->
                <p class="mb-4"><strong>{{ $client->name }}</strong> is requesting permission to access your account.</p>

                <!-- Scope List -->
                @if (count($scopes) > 0)
                    <div class="mb-4">
                        <p class="font-semibold">This application will be able to:</p>
                        <ul>
                            @foreach ($scopes as $scope)
                                <li>{{ $scope->description }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="text-center mt-4">
                    <!-- Authorize Button -->
                    <form method="post" action="{{ route('passport.authorizations.approve') }}">
                        @csrf
                        <input type="hidden" name="state" value="{{ $request->state }}">
                        <input type="hidden" name="client_id" value="{{ $client->getKey() }}">
                        <input type="hidden" name="auth_token" value="{{ $authToken }}">
                        <button type="submit"
                                style="margin-bottom: 3px"
                                class="bg-gray-500 hover:bg-gray-600 font-semibold py-2 px-4 rounded mr-2">
                            Authorize</button>
                    </form>

                    <!-- Cancel Button -->
                    <form method="post" action="{{ route('passport.authorizations.deny') }}">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="state" value="{{ $request->state }}">
                        <input type="hidden" name="client_id" value="{{ $client->getKey() }}">
                        <input type="hidden" name="auth_token" value="{{ $authToken }}">
                        <button class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
