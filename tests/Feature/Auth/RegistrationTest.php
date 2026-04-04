<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        Storage::fake('public');

        $response = $this->post('/register', [
            'full_name' => 'TestUser',
            'email' => 'test@example.com',
            'avatar' => UploadedFile::fake()->image('avatar.png'),
            'password' => 'Password!123',
            'cnic' => '1234567890123',
            'study_program' => 'BSCS',
            'about_me' => 'Hello, this is my about me.',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('otp.verify-page', absolute: false));

        $user = User::query()->where('email', 'test@example.com')->firstOrFail();

        $this->assertSame('TestUser', $user->first_name);
        $this->assertSame('', $user->last_name);
        $this->assertSame('1234567890123', $user->cnic);
        $this->assertSame('BSCS', $user->study_program);
        $this->assertSame('Hello, this is my about me.', $user->about_me);
        $this->assertNotNull($user->avatar_path);

        Storage::disk('public')->assertExists($user->avatar_path);
    }
}
