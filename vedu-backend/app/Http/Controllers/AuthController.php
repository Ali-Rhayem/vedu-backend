<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\StreamService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    protected $streamService;

    public function __construct(StreamService $streamService)
    {
        $this->streamService = $streamService;
    }

    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = auth()->user();

        $streamToken = $this->streamService->generateToken($user->id);

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'user' => $user,
            'stream_token' => $streamToken,
        ]);
    }



    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(JWTAuth::refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer'
        ]);
    }

    /**
     * Register a new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            // 'role' => 'required|string|max:50',
            'profile_image' => 'nullable|string',
            'country' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'code' => 'nullable|string|max:10',
            'phone_number' => 'nullable|string|max:20',
            'bio' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            // 'role' => 'Student',
            'profile_image' => $request->profile_image,
            'country' => $request->country,
            'city' => $request->city,
            'code' => $request->code,
            'phone_number' => $request->phone_number,
            'bio' => $request->bio,
        ]);


        $token = JWTAuth::fromUser($user);

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60
        ], 201);
    }

    public function getUserCourses()
    {
        $user = Auth::user();

        if (!$user instanceof User) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $courseStudentController = new CourseStudentController();
        $courseInstructorController = new CourseInstructorController();

        $studentCourses = $courseStudentController->getStudentCourses($user->id)->original;

        $instructorCourses = $courseInstructorController->getInstructorCourses($user->id)->original;

        return response()->json([
            'student_courses' => $studentCourses,
            'instructor_courses' => $instructorCourses,
        ]);
    }

    public function updatePersonalInfo(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'bio' => 'nullable|string',
            'password' => 'nullable|string|min:8|confirmed',
            'phone_number' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user->name = $request->first_name . ' ' . $request->last_name;
        $user->email = $request->email;
        $user->bio = $request->bio;
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        $user->phone_number = $request->phone_number;
        $user->save();

        return response()->json(['message' => 'Personal information updated successfully'], 200);
    }


    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/profile'), $imageName);

            $user->profile_image = 'images/profile/' . $imageName;
        }


        $user->name = $request->name;
        $user->save();

        return response()->json(['message' => 'Profile updated successfully', 'user' => $user], 200);
    }





    public function updateAddress(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'country' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'code' => 'nullable|string|max:10',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user->country = $request->country;
        $user->city = $request->city;
        $user->code = $request->code;
        $user->save();

        return response()->json(['message' => 'Address updated successfully'], 200);
    }

    public function getUserIdByEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        return response()->json(['user_id' => $user->id], 200);
    }

    public function getAllUsers()
    {
        $users = User::all(); // Fetch all users from the database
        return response()->json($users);
    }
}
