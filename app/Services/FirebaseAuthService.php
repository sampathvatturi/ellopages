<?php
namespace App\Services;

use App\Repositories\UserRepository;
use Firebase\JWT\JWK;
use Firebase\JWT\JWT;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth;

class FirebaseAuthService
{
    protected $auth;
    protected $serviceAccount;
    protected $userRepository;
    public function __construct()
    {
        $this->serviceAccount = json_decode(
            file_get_contents(storage_path('app/private/google/service-account.json')),
            true
        );
        $factory = (new Factory)->withServiceAccount($this->serviceAccount);
        $this->auth = $factory->createAuth();
        $this->userRepository = new UserRepository();
    }
    

    public function verifyIdToken($idToken)
    {
        try {
            $verifiedIdToken = $this->auth->verifyIdToken($idToken);
            return [
                'success' => true,
                'uid' => $verifiedIdToken->claims()->get('sub'), // User ID
                'email' => $verifiedIdToken->claims()->get('email'), // User Email
                // Add more claims if needed
            ];
        } catch (\Kreait\Firebase\Exception\AuthException $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    public function getUserData($uid)
    {
        try {
            $user = $this->auth->getUser($uid);
            $checkUser = $this->userRepository->getUserByEmail($user->email);
            if($checkUser){
                return 'alreadyExists';
            }

            $newUser = new \stdClass();
            // Assuming $user->displayName contains the display name from Firebase
            $displayName = $user->displayName;

            // Initialize first and last names
            $newUser->u_fname = '';
            $newUser->u_lname = '';

            // Check if display name is not empty
            if (!empty($displayName)) {
                // Split the display name into an array of names
                $nameParts = explode(' ', trim($displayName));

                // Assign first name
                $newUser->u_fname = $nameParts[0]; // The first part is the first name

                // Assign last name based on the number of name parts
                if (count($nameParts) > 2) {
                    // If there are more than 2 parts, join the last parts as last name (e.g., "John Michael Doe" -> "Michael Doe")
                    $newUser->u_lname = implode(' ', array_slice($nameParts, 1));
                } elseif (count($nameParts) === 2) {
                    // If there are exactly 2 parts, the second part is the last name
                    $newUser->u_lname = $nameParts[1];
                }
                // If there's only one name part, u_lname remains an empty string.
            }

            // Optional: Handle cases where display name is empty or null
            if (empty($newUser->u_fname)) {
                // You might want to set defaults or handle this case differently
                // For example:
                $newUser->u_fname = 'Unknown';
                $newUser->u_lname = '';
            }
            $newUser->u_email = $user->email;
            $newUser->u_phone = '';
            $newUser->u_reference_id = 0;
            $newUser->u_password = $uid;
            $newUser->u_profile_picture = $user->photoUrl;
            $this->userRepository->register($newUser);

            return [
                'success' => true,
                'user' => [
                    'uid' => $user->uid,
                    'email' => $user->email,
                    'displayName' => $user->displayName,
                    'phoneNumber' => $user->phoneNumber,
                    'photoUrl' => $user->photoUrl,
                    'disabled' => $user->disabled
                ],
            ];
        } catch (\Kreait\Firebase\Exception\AuthException $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
