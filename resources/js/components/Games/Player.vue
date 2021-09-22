<template>

<div>

    <section>

        <div class="container">

            <div v-show="alertContent" class="alert alert-message" :class="alertClass" role="alert">
                {{ alertContent }}
            </div>


            <div class="player">

                <div class="row">

                    <div class="col-md-12">

                        <div class="row my-2">

                            <div class="col-md-12">

                                <div class="progress">
                                    <transition-group name="list" tag="span" v-if="users">
                                        <span 
                                            v-for="item in users"
                                            v-if="item.score && item.score.total_time"
                                            :key="item.id"
                                            class="bubble" 
                                            :style="'left: calc( '+item.score.total_time+' * 100% / 30 - 16px )'">
                                                {{ item.name }}
                                        </span>
                                    </transition-group>
                                    <div class="progress-bar bg-info" role="progressbar" :style="'width: '+percentage+'%'" :aria-valuenow="percentage" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>

                                <div class="input-group">

                                    <input type="text" onpaste="return false" v-model="userAnswer" v-on:keyup.enter="checkResponse()" :placeholder="placeholder" class="form-control user-input col-md-12" :readonly="waitingTrack == 1" autofocus id="userInput">

                                    <div class="input-group-append">
                                        <button class="btn btn-settings" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-sliders-h"></i></button>
                                        <div class="dropdown-menu dropdown-menu-right p-2">
                                            <settings :game="game"></settings>
                                        </div>
                                        <button class="btn btn-secondary btn-valid" @click="checkResponse()">OK</button>
                                    </div>

                                </div>

                            </div>
  

                            <div class="col-md-12 mt-2">
                                <button v-if="currentTrack && score.bonus !== 0" title="Bonus rapidité (0.5 points)" class="btn btn-warning">
                                    <i class="fas fa-fire"></i>
                                </button>
                                <button v-if="currentTrack && score.custom == 1" title="Titre (1 point)" class="btn btn-success">
                                    <i class="fas fa-film"></i> {{ currentTrack.custom_answer }}
                                </button>
                                <button v-if="currentTrack && score.artist == 0.5" title="Artiste (0.5 points)" class="btn btn-success">
                                    <i class="fas fa-microphone"></i> {{ currentTrack.artist_name }}
                                </button>
                                <button v-if="currentTrack && score.track == 0.5" title="Titre (0.5 points)" class="btn btn-success">
                                    <i class="fas fa-music"></i> {{ currentTrack.track_name }}
                                </button>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>


</div>

</template>

<script>

    export default {

        props:['game', 'track', 'users'],

        data() {
            return {
                waitingTrack: false,
                userAnswer: '',
                currentTrack: null,
                percentage: 0,
                placeholder: 'Le titre et/ou l\'artiste?',
                alertContent: '',
                alertClass: 'alert-primary',
                score: {},
                speedLimit: 25, // For bonus score
                alreadyHadAnError: false,
            }
        },

        mounted() {

            this.game.player = null;
            this.game.player = new Audio();
            // this.game.player.type = 'audio/mpeg'; carefull with itunes m4a files
            this.game.player.crossOrigin = "anonymous";
            this.game.player.play();
            this.game.player.volume = parseFloat(this.game.volume);

            if (this.game.public != 1) {

                Echo.channel('game-' + this.game.id)
                    .listen('PauseTrack', (data) => {
                        this.game.player.pause();
                    })
                    .listen('ResumeTrack', (data) => {
                        this.game.player.play();
                    })

            }

        },

        watch: {

            track: function(newVal, oldVal) {

                this.currentTrack = newVal;

                this.userAnswer = '';

                if(this.game.player) this.game.player.pause();

                if(this.currentTrack) {

                    this.score = {
                        artist: 0,
                        track: 0,
                        custom: 0,
                        bonus: 0,
                        artist_time: 0,
                        track_time: 0,
                        custom_time: 0,
                        total_time: 0
                    }

                    this.sendScore();
                
                    this.playAudio();

                } else {
                    this.waitingTrack = true;
                    this.placeholder = 'En attente du prochain titre...';
                }
            }
        },

        methods: {

            playAudio() {

                // Init
                let vm = this;

                this.percentage = 0;
                this.placeholder = "Le titre et/ou l'artiste ?";

                //if(this.game.player) this.game.player.pause();
                this.waitingTrack = false;

                this.game.player.pause();
                this.game.player.src = this.currentTrack.preview_url;
                this.game.player.play();

                // If the file is not available try again or alert
                this.game.player.onerror = function() {
                    if(vm.alreadyHadAnError) {
                        vm.alreadyHadAnError = false;
                        vm.waitingTrack = true;
                        vm.placeholder = "Erreur de lecture. Nous préparons l'extrait suivant...";
                    } else {
                        vm.alreadyHadAnError = true;
                        vm.playAudio();
                    }
                };

                // Get the current audio percentage
                this.game.player.ontimeupdate = function() {
                    vm.percentage = 100 * vm.game.player.currentTime / vm.game.player.duration;
                };

                this.game.player.onended = function() {
                    vm.waitingTrack = true;
                    vm.placeholder = 'En attente du prochain titre...';
                    vm.$emit('track:end', this.currentTrack); // For custom games
                };

            },

            sendScore() {

                // For anwser list to current user
                this.$emit('score', this.score);

            },


            sanitize(str) {

                // Specials cases
                var map = {
                    'a' : 'á|à|ã|â|À|Á|Ã|Â',
                    'b' : 'ß',
                    'e' : 'é|è|ê|ë|É|È|Ê|Ë',
                    'i' : 'í|ì|ï|î|Í|Ì|Î',
                    'o' : 'ó|ò|ô|õ|ö|Ó|Ò|Ô|Õ',
                    'oe': 'œ',
                    'u' : 'ú|ù|û|ü|Ú|Ù|Û|Ü',
                    'c' : 'ç|Ç',
                    'n' : 'ñ|Ñ',
                    '-' : '&|les|and|the',
                    'pink' : 'p!nk',
                    'korn' : 'koяn',
                    '1' : 'un',
                    '1' : 'one',
                    '2' : 'deux',
                    '3' : 'trois',
                    '4' : 'quatre',
                    '5' : 'cinq',
                    '6' : 'six',
                    '7' : 'sept',
                    '8' : 'huit',
                    '9' : 'neuf',
                    '10' : 'dix',
                    '20' : 'vingt',
                    '30' : 'trente',
                    '40' : 'quarante',
                    '50' : 'cinquante',
                    '50' : 'fifty',
                    '60' : 'soixante',
                    '70' : 'soixantedix',
                    '80' : 'quatrevingt',
                    '90' : 'quatrevingtdix',
                    '100' : 'cent',
                    '1000' : 'mille'
                };

                // Remove () and [] with content
                str = str.replace(/ *\([^)]*\) */g, "").replace(/ *\[[^)]*\] */g, "");

                // Lowercase
                str = str.toLowerCase();

                // Replace specials cases
                for (var pattern in map) {
                    str = str.replace(new RegExp(map[pattern], 'g'), pattern);
                };

                // Removing non-alphanumeric chars
                str = str.replace(/\W/g, '');

                // Return the sanitized string
                return str;

            },

            checkResponse() {

                var title = this.sanitize(this.currentTrack.track_name);
                var artist = this.sanitize(this.currentTrack.artist_name);

                var user = this.sanitize(this.userAnswer);

                var titleSimilarity = this.similarity(title, user);
                var artistSimilarity = this.similarity(artist, user);

                var titleArtistSimilarity = this.similarity(title + ' ' + artist, user);
                var artistTitleSimilarity = this.similarity(artist + ' ' + title, user);

                // Perfect match
                if (user.includes(title)) {
                    titleSimilarity = 1;
                }
                if (user.includes(artist)) {
                    artistSimilarity = 1;
                }
                if (user.includes(title) && user.includes(artist)) {
                    titleSimilarity = 1;
                    artistSimilarity = 1;
                }

                if (this.currentTrack.custom_answer) {
                    var custom = this.sanitize(this.currentTrack.custom_answer);
                    var customScore = this.similarity(custom, user);
                } else {
                    customScore = 0;
                }

                // If the word is too small we accept better tolerance
                var limit = 0.8; // old was 0.85


                if (customScore > titleSimilarity || customScore > artistSimilarity) {

                    if (this.score.track !== 0.5 && customScore < 0.755) {
                        this.alertMessage(1, "track");
                    }
                    if (this.score.track !== 0.5 && customScore >= 0.755 && customScore < limit) {
                        this.alertMessage(2, "track");
                    }                
                    if (this.score.track !== 0.5 && customScore >= limit) {
                        this.alertMessage(3, "track");
                    }
                    if (this.score.track !== 0.5 && customScore >= limit && this.percentage < this.speedLimit) {
                        this.alertMessage(4, "track");
                    }

                } else {

                    if (titleArtistSimilarity > titleSimilarity && titleArtistSimilarity > artistSimilarity && titleArtistSimilarity > artistTitleSimilarity) {
                        if (this.score.track !== 0.5 && titleArtistSimilarity < 0.755) {
                            this.alertMessage(1, "track");
                        }
                        if (this.score.track !== 0.5 && titleArtistSimilarity >= 0.755 && titleArtistSimilarity < limit) {
                            this.alertMessage(2, "track");
                        }                
                        if (this.score.track !== 0.5 && titleArtistSimilarity >= limit) {
                            this.alertMessage(3, "both");
                        }
                        if (this.score.track !== 0.5 && titleArtistSimilarity >= limit && this.percentage < this.speedLimit) {
                            this.alertMessage(4, "track");
                        }   
                    }

                    else if (artistTitleSimilarity > titleSimilarity && artistTitleSimilarity > artistSimilarity &&artistTitleSimilarity > titleArtistSimilarity) {
                        if (this.score.track !== 0.5 && artistTitleSimilarity < 0.755) {
                            this.alertMessage(1, "track");
                        }
                        if (this.score.track !== 0.5 && artistTitleSimilarity >= 0.755 && artistTitleSimilarity < limit) {
                            this.alertMessage(2, "track");
                        }                
                        if (this.score.track !== 0.5 && artistTitleSimilarity >= limit) {
                            this.alertMessage(3, "both");
                        }
                        if (this.score.track !== 0.5 && artistTitleSimilarity >= limit && this.percentage < this.speedLimit) {
                            this.alertMessage(4, "track");
                        }
                    }

                    else if (titleSimilarity > artistSimilarity) {
                        if (this.score.track !== 0.5 && titleSimilarity < 0.755) {
                            this.alertMessage(1, "track");
                        }
                        if (this.score.track !== 0.5 && titleSimilarity >= 0.755 && titleSimilarity < limit) {
                            this.alertMessage(2, "track");
                        }                
                        if (this.score.track !== 0.5 && titleSimilarity >= limit) {
                            this.alertMessage(3, "track");
                        }
                        if (this.score.track !== 0.5 && titleSimilarity >= limit && this.percentage < this.speedLimit) {
                            this.alertMessage(4, "track");
                        }
                    } else {
                        if (this.score.artist !== 0.5 && artistSimilarity < 0.755) {
                            this.alertMessage(1, "artist");
                        }
                        if (this.score.artist !== 0.5 && artistSimilarity >= 0.755 && artistSimilarity < limit) {
                            this.alertMessage(2, "artist");
                        }                
                        if (this.score.artist !== 0.5 && artistSimilarity >= limit) {
                            this.alertMessage(3, "artist");
                        }
                        if (this.score.artist !== 0.5 && artistSimilarity >= limit && this.percentage < this.speedLimit) {
                            this.alertMessage(4, "artist");
                        }
                    }

                }

                /*
                score = {
                    artist: 0,
                    track: 0,
                    custom: 0,
                    bonus: 0,
                    artist_time: 0,
                    track_time: 0,
                    custom_time: 0
                }
                */

                // SCORE
                if (titleArtistSimilarity >= limit || artistTitleSimilarity >= limit) {
                    this.score.track = 0.5;
                    this.score.artist = 0.5;
                    this.score.track_time = this.game.player.currentTime;
                    this.score.artist_time = this.game.player.currentTime;
                    this.sendScore();
                }

                if (this.score.track !== 0.5) {
                    if (titleSimilarity >= limit || user.includes(title)) {
                        this.score.track = 0.5;
                        this.score.track_time = this.game.player.currentTime;
                        this.sendScore();
                    }
                }

                if (this.score.artist !== 0.5) {
                    if (artistSimilarity >= limit || user.includes(artist)) {
                        this.score.artist = 0.5;
                        this.score.artist_time = this.game.player.currentTime;
                        this.sendScore();
                    }
                }

                if (this.score.custom !== 1 && customScore >= limit ) {
                    this.score.custom = 1;
                    this.score.custom_time = this.game.player.currentTime;
                    this.sendScore();
                }


                if (this.score.track == 0.5 && this.score.artist == 0.5 && this.percentage < this.speedLimit) {
                    this.score.bonus = 0.5;
                    this.sendScore();
                }

                if (this.score.custom == 1 && this.percentage < this.speedLimit) {
                    this.score.bonus = 0.5;
                    this.sendScore();
                }



                // PLACEHOLDERS
                if (custom) {

                    // this.placeholder = this.game.placeholder;

                    if (this.score.custom == 1) {
                        this.placeholder = 'L\'artiste et le titre en bonus ?';
                    }
                }

                if (this.score.track == 0.5) {
                    this.placeholder = 'Et l\'artiste?';
                }
                if (this.score.artist == 0.5) {
                    this.placeholder = 'Et le titre?';
                }
                if (this.score.track == 0.5 && this.score.artist == 0.5) {
                    this.placeholder = 'Bien joué!';
                }

                // RESET
                this.userAnswer = '';

            },


            alertMessage(level, type) {

                let vm = this;

                switch (level) {
                    case 1:
                        this.getRandomMessage(level, type);
                        this.alertClass = 'alert-danger';
                        break;
                    case 2:
                        this.getRandomMessage(level, type);
                        this.alertClass = 'alert-warning';
                        break;
                    case 3:
                        this.getRandomMessage(level, type);
                        this.alertClass = 'alert-success';
                        break;
                    case 4:
                        this.getRandomMessage(level, type);
                        this.alertClass = 'alert-success';
                        break;
                }

                setTimeout(function() { 
                    vm.alertContent = '';
                }, 1500);

            },

            getRandomMessage(level, type) {
                 
                let items = {
                    "0": {
                      "afk": [
                        "Il y a quelqu'un ?",
                        "C'est l'heure de la sieste ?",
                        "Il faut jouer pour gagner."
                      ]
                    },
                    "1": {
                      "track": [
                        "La pluie de tes injures n’atteint pas le parapluie de mon indifférence.",
                        "Cela semble toujours impossible, jusqu’à ce qu’on le fasse.",
                        "Peu importe si tu avances lentement, du moment que tu ne t'arrêtes pas",
                        "Non, tu n'as pas épuisé toutes les possibilités",
                        "L’échec, c’est ce qui donne à la réussite sa valeur.",
                        "Pour être un bon vainqueur, il faut être un bon perdant.",
                        "Celui qui tombe et se relève est bien plus fort que celui qui ne tombe jamais.",
                        "Ne te soucies pas du nombre de tes échecs. Tu n'as qu’à réussir une fois.",
                        "Il n'y a pas de réussite facile ni d'échec définitif.",
                        "Besoin de cotons-tiges en bambou ?",
                        "Tu peux vraiment mieux faire.",
                        "N'importe quoi !",
                        "Revois tes classiques !",
                        "C'est tout ce que ça t'inspire ?",
                        "Tu devrais monter le volume...",
                        "Pendant ce temps, à Vera Cruz....",
                        "J'aurais pas fait ça comme ça...",
                        "Faut pas pousser mémé dans les orties !"
                      ],
                      "artist": [
                        "La pluie de tes injures n’atteint pas le parapluie de mon indifférence.",
                        "Cela semble toujours impossible, jusqu’à ce qu’on le fasse.",
                        "Peu importe si tu avances lentement, du moment que tu ne t'arrêtes pas",
                        "Non, tu n'as pas épuisé toutes les possibilités",
                        "L’échec, c’est ce qui donne à la réussite sa valeur.",
                        "Pour être un bon vainqueur, il faut être un bon perdant.",
                        "Celui qui tombe et se relève est bien plus fort que celui qui ne tombe jamais.",
                        "Ne te soucies pas du nombre de tes échecs. Tu n'as qu’à réussir une fois.",
                        "Il n'y a pas de réussite facile ni d'échec définitif.",
                        "Besoin de cotons-tiges en bambou ?",
                        "Tu peux vraiment mieux faire.",
                        "N'importe quoi !",
                        "Revois tes classiques !",
                        "C'est tout ce que ça t'inspire ?",
                        "Tu devrais monter le volume...",
                        "Pendant ce temps, à Vera Cruz....",
                        "J'aurais pas fait ça comme ça...",
                        "Faut pas pousser mémé dans les orties !"
                       ]
                    },
                    "2": {
                      "track": [
                        "Tu y es presque !",
                        "La vérité n'est pas loin.",
                        "Persiste, ça se joue à peu !",
                        "Creuse encore, tu es au bout du tunnel."
                      ],
                      "artist": [
                        "Tu y es presque !",
                        "La vérité n'est pas loin.",
                        "Persiste, ça se joue à peu !",
                        "Creuse encore, tu es au bout du tunnel."
                      ]
                    },
                    "3": {
                      "track": [
                        "Félicitations tu as le titre !",
                        "Tu as trouvé le titre !",
                        "Bravo !"
                      ],
                      "artist": [
                        "Félicitations tu as l'artiste !",
                        "Tu as trouvé l'artiste !",
                        "Bravo !"
                      ],
                      "both": [
                        "Félicitations tu as le titre et l'artiste !",
                        "Tu as trouvé le titre et l'artiste !",
                        "Bravo !"
                      ]
                    },
                    "4": {
                      "track": [
                        "Dis donc tu es un rapide toi !",
                        "Quelle vitesse ! Quelle puissance !",
                        "Speeeeeedyy Gonzales !!!"
                      ],
                      "artist": [
                        "Dis donc tu es un rapide toi !",
                        "Quelle vitesse! Quelle puissance !",
                        "Speeeeeedyy Gonzales !!!"
                      ]
                    }
                }

                this.alertContent = items[level][type][Math.floor(Math.random()*items[level][type].length)];
                
            },


            similarity(s1, s2) {
              var longer = s1;
              var shorter = s2;
              if (s1.length < s2.length) {
                longer = s2;
                shorter = s1;
              }
              var longerLength = longer.length;
              if (longerLength == 0) {
                return 1.0;
              }
              return (longerLength - this.editDistance(longer, shorter)) / parseFloat(longerLength);
            },


            editDistance(s1, s2) {
              s1 = s1.toLowerCase();
              s2 = s2.toLowerCase();

              var costs = new Array();
              for (var i = 0; i <= s1.length; i++) {
                var lastValue = i;
                for (var j = 0; j <= s2.length; j++) {
                  if (i == 0)
                    costs[j] = j;
                  else {
                    if (j > 0) {
                      var newValue = costs[j - 1];
                      if (s1.charAt(i - 1) != s2.charAt(j - 1))
                        newValue = Math.min(Math.min(newValue, lastValue),
                          costs[j]) + 1;
                      costs[j - 1] = lastValue;
                      lastValue = newValue;
                    }
                  }
                }
                if (i > 0)
                  costs[s2.length] = lastValue;
              }
              return costs[s2.length];
            }

        }

    };

</script>

<style scoped>

    .player {
        position: relative;
    }

    .player .user-input {
        font-size: 1.5rem;
        text-transform: uppercase;
        border-top-left-radius: 0;
        border-top-right-radius: 0;
        border: none;
        height: 4rem;
        font-size: 3.5vh;
        font-weight: 200;
    }

    .player .progress-bar {
        transition : width 0.5s ease;
    }

    .player .progress {
        border-bottom-left-radius: 0;
        border-bottom-right-radius: 0;
    }

    .container {
        position: relative;
    }

    .alert.alert-message {
        left: 0;
        right: 0;
        z-index: 40;
        position: absolute;
        bottom: 100%;
        font-size: 130%;
    }

    .list-enter-active, .list-leave-active {
      transition: all .6s;
    }
    .list-enter, .list-leave-to /* .list-leave-active below version 2.1.8 */ {
      opacity: 0;
      transform: translateY(-10px);
    }
</style>
