let meetingJoined = false;
const meeting = new Metered.Meeting();
let cameraOn = false;
let micOn = false;
let screenSharingOn = false;
let localVideoStream = null;
let activeSpeakerId = null;
let meetingInfo = {};

async function initializeView() {
    /**
     * Populating the cameras
     */
     const videoInputDevices = await meeting.listVideoInputDevices();
     const videoOptions = [];
     for (let item of videoInputDevices) {
        videoOptions.push(
            `<option value="${item.deviceId}">${item.label}</option>`
        )
     }
    $("#cameraSelectBox").html(videoOptions.join(""));

    /**
     * Populating Microphones
     */
    const audioInputDevices = await meeting.listAudioInputDevices();
    const audioOptions = [];
    for (let item of audioInputDevices) {
        audioOptions.push(
            `<option value="${item.deviceId}">${item.label}</option>`
        )
    }
    $("#microphoneSelectBox").html(audioOptions.join(""));
    

    /**
     * Mute/Unmute Camera and Microphone
     */
    $("#waitingAreaToggleMicrophone").on("click", function() {
        if (micOn) {
            micOn = false;
            $("#waitingAreaToggleMicrophone").removeClass("bg-gray-500");
            $("#waitingAreaToggleMicrophone").addClass("bg-gray-400");
        } else {
            micOn = true;
            $("#waitingAreaToggleMicrophone").removeClass("bg-gray-400");
            $("#waitingAreaToggleMicrophone").addClass("bg-gray-500");
        }
    });

    $("#waitingAreaToggleCamera").on("click", async function() {
        if (cameraOn) {
            cameraOn = false;
            $("#waitingAreaToggleCamera").removeClass("bg-gray-500");
            $("#waitingAreaToggleCamera").addClass("bg-gray-400");
            const tracks = localVideoStream.getTracks();
            tracks.forEach(function (track) {
              track.stop();
            });
            localVideoStream = null;
            $("#waitingAreaLocalVideo")[0].srcObject = null;
        } else {
            cameraOn = true;
            $("#waitingAreaToggleCamera").removeClass("bg-gray-400");
            $("#waitingAreaToggleCamera").addClass("bg-gray-500");
            localVideoStream = await meeting.getLocalVideoStream();
            $("#waitingAreaLocalVideo")[0].srcObject = localVideoStream;
            cameraOn = true;
        }
    });

    /**
     * Adding Event Handlers
     */
         $("#cameraSelectBox").on("change", async function() {
          const deviceId = $("#cameraSelectBox").val();
          await meeting.chooseVideoInputDevice(deviceId);
          if (cameraOn) {
              localVideoStream = await meeting.getLocalVideoStream();
              $("#waitingAreaLocalVideo")[0].srcObject = localVideoStream;
          }
      });
  
      $("#microphoneSelectBox").on("change", async function() {
          const deviceId = $("#microphoneSelectBox").val();
          await meeting.chooseAudioInputDevice(deviceId);
      });
  
}
initializeView();

$("#joinMeetingBtn").on("click", async function () {
    var username = $("#username").val();
    if (!username) {
      return alert("Please enter a username");
    }
  
    try {
      meetingInfo = await meeting.join({
        roomURL: `${window.METERED_DOMAIN}/${window.MEETING_ID}`,
        name: username,
      });
      
      console.log("Meeting joined", meetingInfo);
      $("#waitingArea").addClass("hidden");
      $("#meetingView").removeClass("hidden");
      $("#meetingAreaUsername").text(username);

      /**
       * If camera button is clicked on the meeting view
       * then sharing the camera after joining the meeting.
       */
      if (cameraOn) {
        await meeting.startVideo();
        $("#localVideoTag")[0].srcObject = localVideoStream;
        $("#localVideoTag")[0].play();
        $("#toggleCamera").removeClass("bg-gray-400");
        $("#toggleCamera").addClass("bg-gray-500");
      }
      
      /**
       * Microphone button is clicked on the meeting view then
       * sharing the microphone after joining the meeting
       */
      if (micOn) {
        $("#toggleMicrophone").removeClass("bg-gray-400");
        $("#toggleMicrophone").addClass("bg-gray-500");
        await meeting.startAudio();
      }

    } catch (ex) {
      console.log("Error occurred when joining the meeting", ex);
    }
  });

  




  


  
  $("#toggleMicrophone").on("click",  async function() {
    if (micOn) {
      $("#toggleMicrophone").removeClass("bg-gray-500");
      $("#toggleMicrophone").addClass("bg-gray-400");
      micOn = false;
      await meeting.stopAudio();
    } else {
      $("#toggleMicrophone").removeClass("bg-gray-400");
      $("#toggleMicrophone").addClass("bg-gray-500");
      micOn = true;
      await meeting.startAudio();
    }
  });

  
  $("#toggleCamera").on("click",  async function() {
    if (cameraOn) {
      $("#toggleCamera").removeClass("bg-gray-500");
      $("#toggleCamera").addClass("bg-gray-400");
      // $("#toggleScreen").removeClass("bg-gray-500");
      // $("#toggleScreen").addClass("bg-gray-400");
      cameraOn = false;
      await meeting.stopVideo();
      const tracks = localVideoStream.getTracks();
      tracks.forEach(function (track) {
        track.stop();
      });
      localVideoStream = null;
      $("#localVideoTag")[0].srcObject = null;
    } else {
      $("#toggleCamera").removeClass("bg-gray-400");
      $("#toggleCamera").addClass("bg-gray-500");
      cameraOn = true;
      await meeting.startVideo();
      localVideoStream = await meeting.getLocalVideoStream();
      $("#localVideoTag")[0].srcObject = localVideoStream;
    }
  });

  
  // $("#toggleScreen").on("click",  async function() {
  //   if (screenSharingOn) {
  //     $("#toggleScreen").removeClass("bg-gray-500");
  //     $("#toggleScreen").addClass("bg-gray-400");
  //     screenSharingOn = false;
  //     await meeting.stopVideo();
  //     const tracks = localVideoStream.getTracks();
  //     tracks.forEach(function (track) {
  //       track.stop();
  //     });
  //     localVideoStream = null;
  //     $("#localVideoTag")[0].srcObject = null;

  //   } else {
  //     $("#toggleScreen").removeClass("bg-gray-400");
  //     $("#toggleScreen").addClass("bg-gray-500");
  //     $("#toggleCamera").removeClass("bg-gray-500");
  //     $("#toggleCamera").addClass("bg-gray-400");
  //     screenSharingOn = true;
  //     localVideoStream = await meeting.startScreenShare();
  //     $("#localVideoTag")[0].srcObject = localVideoStream;
  //   }
  // });

  
  // $("#leaveMeeting").on("click", async function() {
  //   await meeting.leaveMeeting();
  //   $("#meetingView").addClass("hidden");
  //   $("#leaveMeetingView").removeClass("hidden");
  // });