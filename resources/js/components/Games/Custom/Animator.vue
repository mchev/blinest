<template>
  <div class="container">
    <div id='videos'>
      <video id='remoteVideo' ref="remoteVideo" autoplay></video>
    </div>
  </div>
</template>

<script>

  export default {

    props: ['game'],

    name: 'visio',

    data() {
      return {
        room: 'visio-' + this.game.id,
        isInitiator: false,
        isStarted: false,
        turnReady: false,
        localStream: {type: Object},
        remoteStream: {type: Object},
        socket: null,
        pc: {type: Object},
        pc_config: {
          'iceServers': [{
            'url': 'stun:stun.l.google.com:19302'
          }]
        },
        sdpConstraints: {
          'mandatory': {
            'OfferToReceiveAudio': true,
            'OfferToReceiveVideo': true
          }
        },
        constraints: {
          video: true,
          audio: true
        }
      }
    },

    mounted() {

        Echo.private(this.room).listenForWhisper('typing', (message) => {
              console.log('Client received message:', message);
              if (message === 'got user media') {
                 this.maybeStart()
              } else if (message.type === 'offer') {
                if (!this.isInitiator && !this.isStarted) {
                  this.maybeStart();
                }
                this.pc.setRemoteDescription(new RTCSessionDescription(message));
                this.doAnswer();
              } else if (message.type === 'answer' && this.isStarted) {
                this.pc.setRemoteDescription(new RTCSessionDescription(message));
              } else if (message.type === 'candidate' && this.isStarted) {
                let candidate = new RTCIceCandidate({
                  sdpMLineIndex: message.label,
                  candidate: message.candidate
                });
                this.pc.addIceCandidate(candidate);
              } else if (message === 'bye' && this.isStarted) {
                this.handleRemoteHangup();
              }
            });

    },

    methods: {

      sendMessage(message) {
        Echo.private(this.room).whisper('typing', message);
      },

      maybeStart() {
        if (!this.isStarted) {
          this.createPeerConnection();
          this.isStarted = true;
        }
      },

      createPeerConnection() {
        try {
          this.pc = new RTCPeerConnection();
          this.pc.onicecandidate = this.handleIceCandidate;
          this.pc.ontrack = this.handleRemoteStreamAdded;
          this.pc.onremovestream = this.handleRemoteStreamRemoved;
          console.log('Created RTCPeerConnnection');
        } catch (e) {
          console.log('Failed to create PeerConnection, exception: ' + e.message);
          alert('Cannot create RTCPeerConnection object.');
          return;
        }
      },

      handleIceCandidate(event) {
        console.log('handleIceCandidate event: ', event);
        if (event.candidate) {
          this.sendMessage({
            type: 'candidate',
            label: event.candidate.sdpMLineIndex,
            id: event.candidate.sdpMid,
            candidate: event.candidate.candidate});
        } else {
          console.log('End of candidates.');
        }
      },

      doAnswer() {
        console.log('Sending answer to peer.');
        this.pc.createAnswer(this.setLocalAndSendMessage, this.handleFailureCallback, this.sdpConstraints);
      },

      handleFailureCallback() {
        console.log('failure');
      },

      setLocalAndSendMessage(sessionDescription) {
        // Set Opus as the preferred codec in SDP if Opus is present.
        this.pc.setLocalDescription(sessionDescription);
        console.log('setLocalAndSendMessage sending message' , sessionDescription);
        this.sendMessage(sessionDescription);
      },

      handleRemoteStreamAdded(event) {
        console.log('Remote stream added.');
        this.$refs.remoteVideo.srcObject = event.stream;
        this.remoteStream = event.stream;
      },

      handleRemoteStreamRemoved(event) {
        console.log('Remote stream removed. Event: ', event);
      },

      hangup() {
        console.log('Hanging up.');
        this.stop();
        this.sendMessage('bye');
      },

      handleRemoteHangup() {
        console.log('Session terminated.');
        this.stop();
        this.isInitiator = false;
      },

      stop() {
        this.isStarted = false;
        // isAudioMuted = false;
        // isVideoMuted = false;
        this.pc.close();
        this.pc = null;
      },

    }

  };

</script>

<style scoped>

  video {
    max-width: 100%;
  }
</style>