<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Arena Video Example</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//cdn.metered.ca/sdk/video/1.4.6/sdk.min.js"></script>
    <script>
        tailwind.config = {theme: {screens: {'xs': '300px','sm': '600px','md': '768px','lg': '1024px','xl': '1240px','2xl': '1380px',}}}
    </script>
    <style>
        @keyframes vibrate {
            0% {transform: translateX(0);}
            20% {transform: translateX(-5px);}
            40% {transform: translateX(5px);}
            60% {transform: translateX(-5px);}
            80% {transform: translateX(5px);}
            100% {transform: translateX(0);}
        }

        @keyframes growShrink {
            0% {transform: scale(1);}
            50% {transform: scale(1.1);}
            100% {transform: scale(1);}
        }

        .animated-element {animation: vibrate 1s infinite, growShrink 2s infinite;}
    </style>
</head>
<body>
    <div class="container mx-auto">
        <!-- navbar start -->
        <header>
            <nav class="container px-4 md:px-6 lg:px-6 py-2.5 bg-white border-gray-200 border-b min-h-[10vh]">
                <div class="flex flex-wrap xs:justify-center sm:justify-between md:justify-between lg:justify-between items-center mx-auto max-w-screen-xl">
                    <a href="/" class="flex items-center">
                        <span class="sm:text-center self-center">
                            <h2 class="font-bold text-xl text-[#002D74]">PixelTalk</h2>
                        </span>
                    </a>
                    <ul class="flex items-center lg:order-2">
                        <li class="text-gray-800 font-medium text-sm px-4 lg:px-5 py-2 lg:py-2.5 mr-1 capitalize text-[#002D74]">Welcome <?= session('user')['username'] ?>!</li>
                        <li class="text-white cursor-pointer bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-4 lg:px-5 py-2 lg:py-2.5 mr-2">
                            <a href="<?= base_url('login/logout'); ?>">Logout</a>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <!-- navbar end -->

        <div class="container sm:px-2 md:px-6 lg:px-12 flex items-center min-w-screen min-h-[89vh]" style="font-family: 'Muli', sans-serif;">
            <div class="container ml-auto mr-auto flex sm:flex-row-reverse md: flex-row lg:flex-row flex-wrap items-start">
                <div class="w-full md:w-2/3 lg:w-2/3 pl-5 pr-5 mb-5 lg:pl-2 lg:pr-2">
                    <div class="w-full pl-5 lg:pl-2 my-3">
                        <h2 class="font-bold text-xl text-[#002D74]">Remote</h2>
                    </div>
                    <div class="bg-white rounded-lg m-h-64 p-2">
                        <div class="mb-2" id="remote-video-container">
                            <video id="activeSpeakerVideo" src="" autoplay class=" object-contain w-full rounded-t-3xl"></video>
                <div id="activeSpeakerUsername" class="hidden h-8 w-full bg-gray-700 rounded-b-3xl bottom-0 text-white text-center font-bold pt-1"></div>
                        </div>
                        <div class="rounded-lg p-4 bg-gray-100 flex flex-col">
                            <div id="remote-button" class="w-100">
                                <!-- <div class="mb-2 rounded-md" id="remote-video-container"></div> -->
                                <!-- <input type="text" id="jr" class="appearance-none block w-full bg-white-200 text-gray-700 border rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white" placeholder="Enter Room Name"> -->
                                <input type="hidden" id="idjr" value="<?= session('user')['username'] ?>" readonly class="appearance-none block w-full bg-white-200 text-gray-700 border rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white" placeholder="Enter Room Name">
                                <input type="hidden" value="" id="jrsessionid">
                                <input type="hidden" value="" id="token">
                                <div class="flex">
                                    <button id="joinRoom" type="submit" class="animated-element relative bg-green-600 hover:bg-green-700 transition:hover:300 rounded-md px-4 py-2 text-white w-1/2 m-2">Accept Call
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="w-full border-0 md:border-0 lg:border-l md:w-1/3 lg:w-1/3 pl-5 pr-5 mb-5 lg:pl-2 lg:pr-2">
                    <div class="w-full pl-5 lg:pl-2 my-3">
                        <h2 class="font-bold text-xl text-[#002D74]">Local</h2>
                    </div>
                    <div id="remoteParticipantContainer" class="bg-white rounded-lg m-h-64 p-2">
                        <div class="mb-2" id="local-video-container">
                            <video id='localVideoTag' autoplay muted></video>
                            <div id="localUsername" class="h-8 w-full bg-gray-700 rounded-b-3xl bottom-0 text-white text-center font-bold pt-1">Me</div>
                        </div>
                        <div id="miccam" class="rounded-lg p-4 bg-gray-100 flex space-x-4 mb-4 justify-center">
                            <button id='waitingAreaToggleMicrophone' class="bg-red-600 w-10 h-10 rounded-md p-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" /></svg>
                            </button>
                            <button id='waitingAreaToggleCamera' class="bg-red-600 w-10 h-10 rounded-md p-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>           
                            </button>
                        </div>
                        <div class="rounded-lg p-4 mb-4 bg-gray-100 flex flex-col">
                            <div class="flex space-x-2 items-center">    
                                <label>Camera:<select class="block w-full bg-white-200 text-gray-700 border rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white" id='cameraSelectBox'></select></label>
            
                                <label>Microphone:<select class="block w-full bg-white-200 text-gray-700 border rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white" id='microphoneSelectBox'></select></label>
                            </div>
                        </div>
                        <div class="rounded-lg p-4 bg-gray-100 flex flex-col">
                            <div id="local" class="w-100">
                                <!-- <div id="local-video-container" class="mb-2 rounded-md"></div> -->
                                <!-- <input id="nr" value="newroom" type="text" class="appearance-none block w-full bg-white-200 text-gray-700 border rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white" placeholder="Enter Room Name"> -->
                                <input type="text" readonly class="appearance-none block w-full bg-white-200 text-gray-700 border rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white" placeholder="Enter Room Name" id="idnr" value="<?= session('user')['username'] ?>">
                                <select class="block w-full bg-white-200 text-gray-700 border rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white" id="caller" placeholder="Select User You Wish To Call">
                                    <?php foreach ($usersExceptCurrent as $value) { ?>
                                        <option class="mb-2" value="<?= $value['userid'] ?>"><?= $value['username'] ?></option>
                                    <?php } ?>
                                </select>
                                <div class="flex">
                                    <button id="newRoom" class="bg-green-600 hover:bg-green-700 rounded-md px-4 py-2 text-white w-1/2 m-2">Call</button>
                                    <button id="endRoom" class="bg-red-600 hover:bg-red-700 rounded-md px-4 py-2 text-white w-1/2 m-2">End Call</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            
            </div>
        </div> 
    </div>



<script type="text/javascript">

let meetingJoined = false;
const meeting = new Metered.Meeting();
let cameraOn = false;
let micOn = false;
// let screenSharingOn = false;
let localVideoStream = null;
let activeSpeakerId = null;
let meetingInfo = {};
    
    initializeView();

    $(function() {
        $("#remote-button").hide();
        $("#endRoom").hide();
        $("#miccam").hide();
        
    });

    async function initializeView() {
        /** Populating the cameras */
         const videoInputDevices = await meeting.listVideoInputDevices();
         const videoOptions = [];
         for (let item of videoInputDevices) {
            videoOptions.push(`<option value="${item.deviceId}">${item.label}</option>`)
         }
        $("#cameraSelectBox").html(videoOptions.join(""));

        /** Populating Microphones */
        const audioInputDevices = await meeting.listAudioInputDevices();
        const audioOptions = [];
        for (let item of audioInputDevices) {
            audioOptions.push(`<option value="${item.deviceId}">${item.label}</option>`)
        }
        $("#microphoneSelectBox").html(audioOptions.join(""));  
    }

    async function endroom() {
        await meeting.leaveMeeting();
        let token = $("#token").val();
        let jrsessionid = $("#jrsessionid").val();
        // $("#popup-modal").show(); 

        $.ajax({
            url: '/dashboard/endroom',
            method: 'POST',
            dataType: 'json',
            data: { token: token, sessionID: jrsessionid },
            success: function(data, textStatus, jqXHR) {
                console.log('Request successful');
                console.log('Data:', data);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Request failed');
                console.error('Error:', errorThrown);
            }
        });
    }

    async function setActiveSpeaker(activeSpeaker) {

        if (activeSpeakerId  != activeSpeaker.participantSessionId) {
          $(`#participant-${activeSpeakerId}`).show();
        } 

        activeSpeakerId = activeSpeaker.participantSessionId;
        $(`#participant-${activeSpeakerId}`).hide();

        $("#activeSpeakerUsername").text(activeSpeaker.name || activeSpeaker.participant.name);
        
        if ($(`#video-${activeSpeaker.participantSessionId}`)[0]) {
          let stream = $(
            `#video-${activeSpeaker.participantSessionId}`
          )[0].srcObject;
          $("#activeSpeakerVideo")[0].srcObject = stream.clone();
        }
      
        if (activeSpeaker.participantSessionId === meeting.participantSessionId) {
          let stream = $(`#localVideoTag`)[0].srcObject;
          if (stream) {
            $("#localVideoTag")[0].srcObject = stream.clone();
          }
        }
    }

    async function startroom(username,metered_domain,meeting_id) {
        try {
            meetingInfo = await meeting.join({
                roomURL: `${metered_domain}/${meeting_id}`,
                name: username,
            });
          
            console.log("Meeting joined", meetingInfo);
            $("#meetingAreaUsername").text(username);
            $("#miccam").show();
          /** If camera button is clicked on the meeting view then sharing the camera after joining the meeting.*/

          if (cameraOn) {
            cameraOn = true
            await meeting.startVideo();
            localVideoStream = await meeting.getLocalVideoStream();
            console.log('localVideoStream',localVideoStream);
            $("#localVideoTag")[0].srcObject = await localVideoStream;
            $("#localVideoTag")[0].play();
            $("#toggleCamera").removeClass("bg-red-600");
            $("#toggleCamera").addClass("bg-green-600");
          }

          
          /**Microphone button is clicked on the meeting view then   sharing the microphone after joining the meeting */
          if (micOn) {
            micOn = true;
            $("#toggleMicrophone").removeClass("bg-red-600");
            $("#toggleMicrophone").addClass("bg-green-600");
            await meeting.startAudio();
          }

        } catch (ex) {
          console.log("Error occurred when joining the meeting", ex);
        }
    }


    $("#newRoom").on("click", async function() {
        let participantIdentity = $("#idnr").val(); 
        let receiver = $("#caller").val(); 
        $.ajax({
            url: '<?= base_url("/dashboard/createroom") ?>',
            method: 'POST',
            dataType: 'json',
            data: { identity: participantIdentity, receiver: receiver },
            success: function(data, textStatus, jqXHR) {
                $("#endRoom").show();
                $("#token").val(data.token);
                $("#jrsessionid").val(data.sessionID);
                console.log('Request successful');
                console.log('Data:', data);
                startroom(data.username,data.metered_domain,data.token);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Request failed');
                console.error('Error:', errorThrown);
            }
        });

    });


    $("#joinRoom").on("click", async function() {
        let jrsessionid = $("#jrsessionid").val(); 
        let token = $("#token").val(); 
        $.ajax({
            url: '/dashboard/joinroom',
            method: 'POST',
            dataType: 'json',
            data: { sessionID: jrsessionid, token: token },
            success: function(data, textStatus, jqXHR) {
                console.log('Request successful');
                console.log('Data:', data);
                $("#remote-button").hide();
                $("#endRoom").show();
                startroom(data.username,data.metered_domain,data.token);
                var timer = setTimeout(function() {endroom();console.log('endrooms')}, 60000);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Request failed');
                console.error('Error:', errorThrown);
            }
        });

    });


    $("#endRoom").on("click", async function() {
        // await meeting.leaveMeeting();
        $("#remote-button").hide();
        $("#endRoom").hide();
        endroom();
    });


    /** Mute/Unmute Microphone */
    $("#waitingAreaToggleMicrophone").on("click", async function() {
        if (micOn) {
            micOn = false;
            await meeting.stopAudio();
            $("#waitingAreaToggleMicrophone").removeClass("bg-green-600");
            $("#waitingAreaToggleMicrophone").addClass("bg-red-600");
        } else {
            micOn = true;
            await meeting.startAudio();
            $("#waitingAreaToggleMicrophone").removeClass("bg-red-600");
            $("#waitingAreaToggleMicrophone").addClass("bg-green-600");
        }
    });


    /** Toggle Camera */
    $("#waitingAreaToggleCamera").on("click", async function() {
        if (cameraOn) {
            cameraOn = false;
            $("#waitingAreaToggleCamera").removeClass("bg-green-600");
            $("#waitingAreaToggleCamera").addClass("bg-red-600");
            meeting.stopVideo();
            const tracks = localVideoStream.getTracks();
            tracks.forEach(function (track) {
              track.stop();
            });
            localVideoStream = null;
            $("#localVideoTag")[0].srcObject = null;
        } else {
            cameraOn = true;
            await meeting.startVideo();
            $("#waitingAreaToggleCamera").removeClass("bg-red-600");
            $("#waitingAreaToggleCamera").addClass("bg-green-600");
            localVideoStream = await meeting.getLocalVideoStream();
            $("#localVideoTag")[0].srcObject = await localVideoStream;
        }
    });

    

    /** Adding Event Handlers (select mic and camera) */
    $("#cameraSelectBox").on("change", async function() {
        const deviceId = $("#cameraSelectBox").val();
        await meeting.chooseVideoInputDevice(deviceId);
        if (cameraOn) {
            localVideoStream = await meeting.getLocalVideoStream();
            $("#localVideoTag")[0].srcObject = localVideoStream;
        }
    });
  
    $("#microphoneSelectBox").on("change", async function() {
        const deviceId = $("#microphoneSelectBox").val();
        await meeting.chooseAudioInputDevice(deviceId);
    });

// ===========================
// ===========================
// ===========================
// ===========================
// ===========================
// ===========================
// ===========================
      /**
   * Handling Events
   */
  meeting.on("onlineParticipants", function(participants) {
    
    for (let participantInfo of participants) {
      if (!$(`#participant-${participantInfo._id}`)[0] && participantInfo._id !== meeting.participantInfo._id) {
        $("#remoteParticipantContainer").append(
          `
          <div id="participant-${participantInfo._id}" class="w-48 h-48 rounded-3xl bg-gray-900 relative">
            <video id="video-${participantInfo._id}" src="" autoplay class="object-contain w-full rounded-t-3xl"></video>
            <video id="audio-${participantInfo._id}" src="" autoplay class="hidden"></video>
            <div class="absolute h-8 w-full bg-gray-700 rounded-b-3xl bottom-0 text-white text-center font-bold pt-1">
                ${participantInfo.name}
            </div>
          </div>
          `
        );
      }
    }
  });

  meeting.on("participantLeft", function(participantInfo) {
    // $("#participant-" + participantInfo._id).remove();
    if (participantInfo._id === activeSpeakerId) {
      $("#activeSpeakerUsername").text("");
      $("#activeSpeakerUsername").addClass("hidden");
    }
  });

  meeting.on("remoteTrackStarted", function(remoteTrackItem) {
    $("#activeSpeakerUsername").removeClass("hidden");

    if (remoteTrackItem.type === "video") {
      let mediaStream = new MediaStream();
      mediaStream.addTrack(remoteTrackItem.track);
      if ($("#video-" + remoteTrackItem.participantSessionId)[0]) {
        $("#video-" + remoteTrackItem.participantSessionId)[0].srcObject = mediaStream;
        $("#video-" + remoteTrackItem.participantSessionId)[0].play();
      }
    }

    if (remoteTrackItem.type === "audio") {
      let mediaStream = new MediaStream();
      mediaStream.addTrack(remoteTrackItem.track);
      if ( $("#video-" + remoteTrackItem.participantSessionId)[0]) {
        $("#audio-" + remoteTrackItem.participantSessionId)[0].srcObject = mediaStream;
        $("#audio-" + remoteTrackItem.participantSessionId)[0].play();
      }
    }
    setActiveSpeaker(remoteTrackItem);
  });

  meeting.on("remoteTrackStopped", function(remoteTrackItem) {
    if (remoteTrackItem.type === "video") {
      if ( $("#video-" + remoteTrackItem.participantSessionId)[0]) {
        $("#video-" + remoteTrackItem.participantSessionId)[0].srcObject = null;
        $("#video-" + remoteTrackItem.participantSessionId)[0].pause();
      }
      
      if (remoteTrackItem.participantSessionId === activeSpeakerId) {
        $("#activeSpeakerVideo")[0].srcObject = null;
        $("#activeSpeakerVideo")[0].pause();
      }
    }

    if (remoteTrackItem.type === "audio") {
      if ($("#audio-" + remoteTrackItem.participantSessionId)[0]) {
        $("#audio-" + remoteTrackItem.participantSessionId)[0].srcObject = null;
        $("#audio-" + remoteTrackItem.participantSessionId)[0].pause();
      }
    }
  });


  meeting.on("activeSpeaker", function(activeSpeaker) {
    setActiveSpeaker(activeSpeaker);
  });

// =================================================================
    // Pusher Notification Code
    Pusher.logToConsole = true;

    var pusher = new Pusher('40f0f1503664d8977ab7', {
      cluster: 'ap2'
    });

    var channel = pusher.subscribe('arenatest');
    channel.bind('call_event', function(data) {
        console.log('pusher data',data);
        document.getElementById('jrsessionid').value=data.sessionID;
        document.getElementById('token').value=data.token;
        if (data.receiver== <?= session('user')['userid'] ?> && data.type=='startcall') {
            // document.getElementById('remote-button').style.display = 'block'; 
            $("#remote-button").show();
        }
        if ((data.session.callerid== <?= session('user')['userid'] ?> || data.session.receiverid == <?= session('user')['userid'] ?> )&& data.type=="endcall") {
            alert("Call Ended successfully");
            var timer = setTimeout(function() {location.reload();}, 5000);
        }
        
    });

  </script>
</body>
</html>