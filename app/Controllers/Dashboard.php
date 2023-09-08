<?php
namespace App\Controllers;
// require __DIR__ . '/vendor/autoload.php';

use Pusher\Pusher;
use App\Models\VideoCallSessionModel;

class Dashboard extends BaseController {
    protected $session;

    public function __construct() {
        $this->session = session();
    }

    public function index() {
        // Check if the 'user' session is set
        if (!$this->session->has('user')) {
            return redirect()->to(base_url());
        }

        // Get the current user's ID from the session
        $currentUserId = $this->session->get('user')['userid'];

        // Load the UserModel
        $userModel = new \App\Models\UserModel();

        // Get the list of users except the current user
        $usersExceptCurrent = $userModel->getUsersExceptCurrent($currentUserId);

        // Pass the list of users to the view
        // echo json_encode($usersExceptCurrent);
        $data = [
            'usersExceptCurrent' => $usersExceptCurrent
        ];

        return view('dashboard', $data);
    }

    public function createroom() {
        // Include the autoload.php file
        require_once(APPPATH . '../vendor/autoload.php');
        $METERED_DOMAIN = config('Meterca')->METERED_DOMAIN;
        $METERED_SECRET_KEY = config('Meterca')->METERED_SECRET_KEY;

        // $_POST = json_decode(file_get_contents('php://input'), true);
        // print_r($_POST);
        $identity = $_POST['identity']; 
        $receiver = $_POST['receiver']; 

        // Contain the logic to create a new meeting
        $data = ['autoJoin' => true];

        $queryString = http_build_query(['secretKey' => $METERED_SECRET_KEY]);

        $url = "https://{$METERED_DOMAIN}/api/v1/room?$queryString";
        
        $options = [
            'http' => [
                'method' => 'POST',
                'header' => "Content-Type: application/json\r\n",
                'content' => json_encode($data),
            ],
        ];

        $context = stream_context_create($options);
        $response = file_get_contents($url, false, $context);

        $responseData = json_decode($response, true);
        $roomName = $responseData["roomName"];

        $app_id = config('Pusher')->app_id; 
        $app_key = config('Pusher')->key; 
        $app_secret = config('Pusher')->secret; 
        $app_cluster = config('Pusher')->cluster; 

        $videoCallSessionModel = new VideoCallSessionModel();
        $sessionID = $videoCallSessionModel->createSession($this->session->get('user')['userid'], $receiver);

        $pusher = new Pusher($app_key, $app_secret, $app_id, ['cluster' => $app_cluster]);

        $pusherData = array('token' => $roomName,'receiver' => $receiver,'sessionID' => $sessionID, );
        $pusher->trigger('arenatest', 'call_event', $pusherData);

        echo json_encode([
            'token' => $roomName,
            'metered_domain' => $METERED_DOMAIN,
            'username' => $this->session->get('user')['username'],
            'sessionID' => $sessionID,
        ]);
    }

    public function joinroom() {
        // Include the autoload.php file
        require_once(APPPATH . '../vendor/autoload.php');
        $METERED_DOMAIN = config('Meterca')->METERED_DOMAIN;
        $METERED_SECRET_KEY = config('Meterca')->METERED_SECRET_KEY;
        
        // $_POST = json_decode(file_get_contents('php://input'), true);
        $sessionID = $_POST['sessionID'];
        $token = $_POST['token'];

        // Contains logic to validate an existing meeting
        $url = "https://{$METERED_DOMAIN}/api/v1/room/{$token}?secretKey={$METERED_SECRET_KEY}";

        $response = file_get_contents($url);

        if ($response !== false) {
            $responseData = json_decode($response, true);

            if (isset($responseData["roomName"])) {
                $token = $responseData["roomName"];
                echo json_encode(['token' => $token,'metered_domain' => $METERED_DOMAIN,'username' => $this->session->get('user')['username']]);
            }
        }

        $videoCallSessionModel = new VideoCallSessionModel();
        $videoCallSessionModel->startSession($sessionID);

        // echo json_encode([
        //     'token' => $token,
        //     'metered_domain' => $METERED_DOMAIN,
        //     'username' => $this->session->get('user')['username'],
        // ]);
    }

    public function endroom() {
        // Include the autoload.php file
        require_once(APPPATH . '../vendor/autoload.php');
        $METERED_DOMAIN = config('Meterca')->METERED_DOMAIN;
        $METERED_SECRET_KEY = config('Meterca')->METERED_SECRET_KEY;

        $token = $_POST['token'];
        $sessionID = $_POST['sessionID'];

        $videoCallSessionModel = new VideoCallSessionModel();
        $videoCallSessionModel->endSession($sessionID);

        $url = "https://{$METERED_DOMAIN}/api/v1/room/$token";

        // Initialize cURL session
        $ch = curl_init($url);

        // Set cURL options for a DELETE request
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute the cURL request and capture the response
        $response = curl_exec($ch);

        // Check for cURL errors
        if (curl_errno($ch)) {echo 'cURL error: ' . curl_error($ch);}

        // Close the cURL session
        curl_close($ch);

        // Handle the response as needed
        if ($response !== false) {echo $response;} else {echo 'DELETE request failed';}

    }

}
