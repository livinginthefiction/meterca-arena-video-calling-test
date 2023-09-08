// let meetingJoined = false;
// const meeting = new Metered.Meeting();
// let cameraOn = false;
// let micOn = false;
// let screenSharingOn = false;
// let localVideoStream = null;
// let activeSpeakerId = null;
// let meetingInfo = {};
    
//     initializeView();

//     $(function() {
//         $("#remote-button").hide();
//         $("#endRoom").hide();
//     });

//     async function initializeView() {
//         /** Populating the cameras */
//          const videoInputDevices = await meeting.listVideoInputDevices();
//          const videoOptions = [];
//          for (let item of videoInputDevices) {
//             videoOptions.push(`<option value="${item.deviceId}">${item.label}</option>`)
//          }
//         $("#cameraSelectBox").html(videoOptions.join(""));

//         /** Populating Microphones */
//         const audioInputDevices = await meeting.listAudioInputDevices();
//         const audioOptions = [];
//         for (let item of audioInputDevices) {
//             audioOptions.push(`<option value="${item.deviceId}">${item.label}</option>`)
//         }
//         $("#microphoneSelectBox").html(audioOptions.join(""));  
//     }

//     function endroom() {
//         let roomSid = $("#roomSid").val();
//         let jrsessionid = $("#jrsessionid").val();

//         $.ajax({
//             url: '/dashboard/endroom',
//             method: 'POST',
//             dataType: 'json',
//             data: { roomSid: roomSid, sessionID: jrsessionid },
//             success: function(data, textStatus, jqXHR) {
//                 console.log('Request successful');
//                 console.log('Data:', data);
//             },
//             error: function(jqXHR, textStatus, errorThrown) {
//                 console.error('Request failed');
//                 console.error('Error:', errorThrown);
//             }
//         });
//     }


//     function startroom(username,metered_domain,meeting_id) {
//         try {
//             meetingInfo = await meeting.join({
//                 roomURL: `${metered_domain}/${meeting_id}`,
//                 name: username,
//             });
          
//             console.log("Meeting joined", meetingInfo);
//             $("#meetingAreaUsername").text(username);

//           /**
//            * If camera button is clicked on the meeting view
//            * then sharing the camera after joining the meeting.
//            */
//           if (cameraOn) {
//             await meeting.startVideo();
//             $("#localVideoTag")[0].srcObject = localVideoStream;
//             $("#localVideoTag")[0].play();
//             $("#toggleCamera").removeClass("bg-gray-400");
//             $("#toggleCamera").addClass("bg-gray-500");
//           }
          
//           /**
//            * Microphone button is clicked on the meeting view then
//            * sharing the microphone after joining the meeting
//            */
//           if (micOn) {
//             $("#toggleMicrophone").removeClass("bg-gray-400");
//             $("#toggleMicrophone").addClass("bg-gray-500");
//             await meeting.startAudio();
//           }

//         } catch (ex) {
//           console.log("Error occurred when joining the meeting", ex);
//         }
//     }


//     $("#newRoom").on("click", async function() {
//         let participantIdentity = $("#idnr").val(); 
//         let receiver = $("#caller").val(); 
//         $.ajax({
//             url: '/dashboard/createroom',
//             method: 'POST',
//             dataType: 'json',
//             data: { identity: participantIdentity, receiver: receiver },
//             success: function(data, textStatus, jqXHR) {
//                 $("#endRoom").show();
//                 $("#roomSid").val(data.roomSid);
//                 console.log('Request successful');
//                 console.log('Data:', data);
//                 startroom(data.username,data.metered_domain,data.token);
//             },
//             error: function(jqXHR, textStatus, errorThrown) {
//                 console.error('Request failed');
//                 console.error('Error:', errorThrown);
//             }
//         });

//     });


//     $("#joinRoom").on("click", async function() {
//         let participantIdentity = $("#idnr").val(); 
//         let receiver = $("#caller").val(); 
//         $.ajax({
//             url: '/dashboard/joinroom',
//             method: 'POST',
//             dataType: 'json',
//             data: { param1: 'value1', param2: 'value2' },
//             success: function(data, textStatus, jqXHR) {
//                 console.log('Request successful');
//                 console.log('Data:', data);
//                 var timer = setTimeout(function() {endroom();}, 60000);
//             },
//             error: function(jqXHR, textStatus, errorThrown) {
//                 console.error('Request failed');
//                 console.error('Error:', errorThrown);
//             }
//         });

//     });


//     $("#endRoom").on("click", async function() {
//         await meeting.leaveMeeting();
//         $("#remote-button").hide();
//         $("#endRoom").hide();
//         endroom();
//     });


//     /** Mute/Unmute Microphone */
//     $("#waitingAreaToggleMicrophone").on("click", function() {
//         if (micOn) {
//             micOn = false;
//             $("#waitingAreaToggleMicrophone").removeClass("bg-gray-500");
//             $("#waitingAreaToggleMicrophone").addClass("bg-gray-400");
//         } else {
//             micOn = true;
//             $("#waitingAreaToggleMicrophone").removeClass("bg-gray-400");
//             $("#waitingAreaToggleMicrophone").addClass("bg-gray-500");
//         }
//     });

//     /** Toggle Camera */
//     $("#waitingAreaToggleCamera").on("click", async function() {
//         if (cameraOn) {
//             cameraOn = false;
//             $("#waitingAreaToggleCamera").removeClass("bg-gray-500");
//             $("#waitingAreaToggleCamera").addClass("bg-gray-400");
//             const tracks = localVideoStream.getTracks();
//             tracks.forEach(function (track) {
//               track.stop();
//             });
//             localVideoStream = null;
//             $("#waitingAreaLocalVideo")[0].srcObject = null;
//         } else {
//             cameraOn = true;
//             $("#waitingAreaToggleCamera").removeClass("bg-gray-400");
//             $("#waitingAreaToggleCamera").addClass("bg-gray-500");
//             localVideoStream = await meeting.getLocalVideoStream();
//             $("#waitingAreaLocalVideo")[0].srcObject = localVideoStream;
//             cameraOn = true;
//         }
//     });

//     /**
//      * Adding Event Handlers
//      */
//          $("#cameraSelectBox").on("change", async function() {
//           const deviceId = $("#cameraSelectBox").val();
//           await meeting.chooseVideoInputDevice(deviceId);
//           if (cameraOn) {
//               localVideoStream = await meeting.getLocalVideoStream();
//               $("#waitingAreaLocalVideo")[0].srcObject = localVideoStream;
//           }
//       });
  
//       $("#microphoneSelectBox").on("change", async function() {
//           const deviceId = $("#microphoneSelectBox").val();
//           await meeting.chooseAudioInputDevice(deviceId);
//       });

// // =================================================================
//     // Pusher Notification Code
//     Pusher.logToConsole = true;

//     var pusher = new Pusher('40f0f1503664d8977ab7', {
//       cluster: 'ap2'
//     });

//     var channel = pusher.subscribe('arenatest');
//     channel.bind('call_event', function(data) {
//         document.getElementById('jrsessionid').value=data.sessionID;
//         document.getElementById('roomSid').value=data.roomSid;
//         if (data.receiver== <?= session('user')['userid'] ?> ) {
//          // data=JSON.parse(data);
//             console.log(data);
//             document.getElementById('remote-button').style.display = 'block';   
//             document.getElementById('jr').value=data.roomname;
//         }
        
//     });