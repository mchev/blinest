<template>

    <div>

        <vue-web-cam
            v-if="video"
            ref="webcam"
            :device-id="deviceId"
            width="100%"
            height="200"
            @started="onStarted"
            @stopped="onStopped"
            @error="onError"
            @cameras="onCameras"
            @camera-change="onCameraChange"
        />


        <div class="btn-group">
            <button v-if="video" type="button" class="btn btn-danger" title="Couper la webcam" @click="video = false"><i class="fas fa-video-slash"></i></button>
            <button v-else type="button" class="btn btn-secondary" title="Allumer la webcam" @click="video = true"><i class="fas fa-video"></i></button>
            <button v-if="micro" type="button" class="btn btn-danger" title="Couper le micro" @click="micro = false"><i class="fas fa-microphone-slash"></i></button>
            <button v-else type="button" class="btn btn-secondary" title="Allumer le micro" @click="micro = true"><i class="fas fa-microphone"></i></button>
        </div>

    </div>

</template>

<script>

    import { WebCam } from "vue-web-cam";

    export default {

        name: 'videocam',

        components: {
            WebCam
        },

        data() {
            return {
                video: false,
                micro: false,
                camera: null,
                deviceId: null,
                devices: []
            }
        },

        computed: {
            device: function() {
                return this.devices.find(n => n.deviceId === this.deviceId);
            }
        },

        watch: {
            camera: function(id) {
                this.deviceId = id;
            },
            devices: function() {
                // Once we have a list select the first one
                const [first, ...tail] = this.devices;
                if (first) {
                    this.camera = first.deviceId;
                    this.deviceId = first.deviceId;
                }
            }
        },

        methods: {
            onCapture() {
                this.img = this.$refs.webcam.capture();
            },
            onStarted(stream) {
                console.log("On Started Event", stream);
            },
            onStopped(stream) {
                console.log("On Stopped Event", stream);
            },
            onStop() {
                this.$refs.webcam.stop();
            },
            onStart() {
                this.$refs.webcam.start();
            },
            onError(error) {
                console.log("On Error Event", error);
            },
            onCameras(cameras) {
                this.devices = cameras;
                console.log("On Cameras Event", cameras);
            },
            onCameraChange(deviceId) {
                this.deviceId = deviceId;
                this.camera = deviceId;
                console.log("On Camera Change Event", deviceId);
            }
        }
    };

</script>