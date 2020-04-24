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

        this.socket = Echo.private(this.room)

        Echo.join(this.room)
          .joining((user) => {
            console.log('Another peer made a request to join room ' + this.room);
            console.log('This peer is the initiator of room ' + this.room + '!');
            this.isChannelReady = true;
          });

        this.socket.listenForWhisper('typing', (message) => {
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
          
        console.log('Client sending message: ', message);
        this.socket.whisper('typing', message);

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
        sessionDescription.sdp = this.preferOpus(sessionDescription.sdp);
        this.pc.setLocalDescription(sessionDescription);
        console.log('setLocalAndSendMessage sending message' , sessionDescription);
        this.sendMessage(sessionDescription);
      },

      requestTurn(turn_url) {
        let turnExists = false;
        for (let i in this.pc_config.iceServers) {
          if (this.pc_config.iceServers[i].url.substr(0, 5) === 'turn:') {
            turnExists = true;
            this.turnReady = true;
            break;
          }
        }
        if (!turnExists) {
          console.log('Getting TURN server from ', turn_url);
          // No TURN server. Get one from computeengineondemand.appspot.com:
          let xhr = new XMLHttpRequest();
          xhr.onreadystatechange = function(){
            if (xhr.readyState === 4 && xhr.status === 200) {
              let turnServer = JSON.parse(xhr.responseText);
              console.log('Got TURN server: ', turnServer);
              this.pc_config.iceServers.push({
                'url': 'turn:' + turnServer.username + '@' + turnServer.turn,
                'credential': turnServer.password
              });
              this.turnReady = true;
            }
          };
          xhr.open('GET', turn_url, true);
          xhr.send();
        }
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
//  console.log('Session terminated.');
        // stop();
        // isInitiator = false;
      },

      stop() {
        this.isStarted = false;
        // isAudioMuted = false;
        // isVideoMuted = false;
        this.pc.close();
        this.pc = null;
      },

      preferOpus(sdp) {
        // Set Opus as the default audio codec if it's present.
        let sdpLines = sdp.split('\r\n');
        let mLineIndex;
        // Search for m line.
        for (let i = 0; i < sdpLines.length; i++) {
          if (sdpLines[i].search('m=audio') !== -1) {
            mLineIndex = i;
            break;
          }
        }
        if (mLineIndex === null) {
          return sdp;
        }
        // If Opus is available, set it as the default in m line.
        for (let i = 0; i < sdpLines.length; i++) {
          if (sdpLines[i].search('opus/48000') !== -1) {
            let opusPayload = this.extractSdp(sdpLines[i], /:(\d+) opus\/48000/i);
            if (opusPayload) {
              sdpLines[mLineIndex] = this.setDefaultCodec(sdpLines[mLineIndex], opusPayload);
            }
            break;
          }
        }
        // Remove CN in m line and sdp.
        sdpLines = this.removeCN(sdpLines, mLineIndex);
        sdp = sdpLines.join('\r\n');
        return sdp;
      },

      extractSdp(sdpLine, pattern) {
        let result = sdpLine.match(pattern);
        return result && result.length === 2 ? result[1] : null;
      },

      setDefaultCodec(mLine, payload) {
        // Set the selected codec to the first in m line.
        let elements = mLine.split(' ');
        let newLine = [];
        let index = 0;
        for (let i = 0; i < elements.length; i++) {
          if (index === 3) { // Format of media starts from the fourth.
            newLine[index++] = payload; // Put target payload to the first.
          }
          if (elements[i] !== payload) {
            newLine[index++] = elements[i];
          }
        }
        return newLine.join(' ');
      },

      removeCN(sdpLines, mLineIndex) {
        // Strip CN from sdp before CN constraints is ready.
        console.log(sdpLines[mLineIndex]);
        let mLineElements = sdpLines[mLineIndex].split(' ');
        // Scan from end for the convenience of removing an item.
        for (let i = sdpLines.length-1; i >= 0; i--) {
          let payload = this.extractSdp(sdpLines[i], /a=rtpmap:(\d+) CN\/\d+/i);
          if (payload) {
            let cnPos = mLineElements.indexOf(payload);
            if (cnPos !== -1) {
              // Remove CN payload from m line.
              mLineElements.splice(cnPos, 1);
            }
            // Remove CN line in sdp
            sdpLines.splice(i, 1);
          }
        }
        sdpLines[mLineIndex] = mLineElements.join(' ');
        return sdpLines;
      }

    }
  };
</script>

<style scoped>

  video {
    max-width: 100%;
  }
</style>