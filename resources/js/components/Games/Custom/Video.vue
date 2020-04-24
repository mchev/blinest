<template>
  <div class="container">
    <div id='videos'>
      <video id='localVideo' ref="localVideo" autoplay muted></video>
    </div>
    <button class="btn btn-secondary" @click="startVideo">Click</button>
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
        isChannelReady: true,
        turnReady: false,
        localStream: {type: Object},
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


    },

    methods: {

      startVideo() {


        this.isInitiator = true;

        let _this = this;

        if (this.room !== '') {
          console.log('Create or join room', this.room);
          //this.socket.emit('create or join', this.room);
        }

        Echo.join(this.room)
          .joining((user) => {
            console.log('Another peer made a request to join room ' + room);
            console.log('This peer is the initiator of room ' + room + '!');
            _this.isChannelReady = true;
            this.doCall();
          });


        Echo.private(this.room)

          .listenForWhisper('typing', (message) => {

              console.log('Message pour l\'animateur: ', message);

              if (message === 'got user media') {

                this.maybeStart()

              }

              else if (message.type === 'answer' && this.isStarted) {

                this.pc.setRemoteDescription(new RTCSessionDescription(message));

              }

              else if (message.type === 'candidate' && this.isStarted) {

                let candidate = new RTCIceCandidate({
                  sdpMLineIndex: message.label,
                  candidate: message.candidate
                });
                this.pc.addIceCandidate(candidate);

              }

              else if (message === 'bye' && this.isStarted) {

                this.handleRemoteHangup();

              }

            });


        ////////////////////////////////////////////////////////////////////////

        navigator.mediaDevices.getUserMedia(this.constraints).then(this.handleUserMedia, this.handleUserMediaError);

        _this.maybeStart()

        console.log('Getting user media with constraints', this.constraints);
        if (location.hostname != "localhost") {
          this.requestTurn('https://computeengineondemand.appspot.com/turn?username=41784574&key=4080218913');
        }

        window.onbeforeunload = function (e) {
          _this.sendMessage('bye');
        }

      },

      sendMessage(message) {
          
        console.log('Animateur sending message: ', message);
        Echo.private(this.room).whisper('typing', message);

      },

      maybeStart() {

        if (!this.isStarted && this.localStream.active === true && this.isChannelReady) {
          this.createPeerConnection();
          this.pc.addStream(this.localStream);
          this.isStarted = true;            
          this.doCall();
        }

      },

      handleUserMedia(stream) {

        console.log('Adding local stream.');

        this.$refs.localVideo.srcObject = stream;
        this.localStream = stream;

        this.sendMessage('got user media');

        console.log("isInitiator(handleUserMedia): ", this.isInitiator);

        if (this.isInitiator) {
          this.maybeStart();
        }

      },

      handleUserMediaError(error){
        console.log('getUserMedia error: ', error);
      },

      createPeerConnection() {    //创建对等连接并设置回调
        try {
          this.pc = new RTCPeerConnection(null);
          this.pc.onicecandidate = this.handleIceCandidate;  //向对等端发送各个ICE候选项
          this.pc.ontrack = this.handleRemoteStreamAdded;   //处理添加的远端流
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
      handleCreateOfferError(event){
        console.log('createOffer() error: ', event);
      },
      doCall() {
        console.log('Sending offer to peer');
        this.pc.createOffer(this.setLocalAndSendMessage, this.handleCreateOfferError);
      },
      doAnswer() {
        console.log('Sending answer to peer.');
        this.pc.createAnswer(this.setLocalAndSendMessage, null, this.sdpConstraints);
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