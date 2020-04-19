<template>

<div class="w-100 text-center">

    <section class="w-100">

        <div class="container">

            <div class="player">

                <div class="row">

                    <div v-if="alertContent" class="alert" :class="alertClass" role="alert">
                        {{ alertContent }}
                    </div>

                    <div class="col-md-12">

                        <div class="row mt-4">

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><i class="fas fa-volume-up"></i> Volume</label>
                                    <input type="range" class="form-control-range" id="formControlRange" v-model="volume" min="0" max="1" step="0.01" @change="changeVolume" @input="changeVolume">
                                </div>
                            </div>

                            <div class="col-md-12">

                                <div class="progress">
                                    <div class="progress-bar bg-info" role="progressbar" :style="'width: '+percentage+'%'" :aria-valuenow="percentage" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>

                            </div>

                        </div>

                        <div class="row my-2">

                            <div class="input-group input-group-lg col-md-12">
                                <input type="text" onpaste="return false" v-model="userAnswer" v-on:keyup.enter="checkResponse()" :placeholder="placeholder" class="form-control user-input col-md-12" :disabled="waitingTrack == 1" autofocus>
                                <div class="input-group-append">
                                    <button class="btn btn-success" @click="checkResponse()">OK</button>
                                </div>
                            </div>

                            <div class="col-md-12 mt-2">
                                <button v-if="currentTrack && currentTrack.bonus_score !== 0" title="Bonus rapidité (0.5 points)" class="btn btn-warning">
                                    <i class="fas fa-fire"></i>
                                </button>
                                <button v-if="currentTrack && currentTrack.custom_score == 1" title="Titre (1 point)" class="btn btn-success">
                                    <i class="fas fa-tv"></i> {{ currentTrack.custom_answer }}
                                </button>
                                <button v-if="currentTrack && currentTrack.artist_score == 0.5" title="Artiste (0.5 points)" class="btn btn-success">
                                    Artiste : {{ currentTrack.artist_name }}
                                </button>
                                <button v-if="currentTrack && currentTrack.track_score == 0.5" title="Titre (0.5 points)" class="btn btn-success">
                                    Titre : {{ currentTrack.track_name }}
                                </button>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>


    <!-- START MODAL -->
    <div class="modal fade" id="startModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content text-dark">
                <div class="modal-body text-center">
                    <h3>{{ game.title }}</h3>
                    <button class="btn btn-success btn-lg mb-2" @click="startGame()">
                        C'est parti! <i class="fas fa-rocket"></i>
                    </button>
                    <p>Partie animée par : {{ game.user.name }}</p>
                </div>
            </div>
        </div>
    </div>


    <!-- FINNISH MODAL -->
    <div class="modal fade" id="finnish" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-secondary text-white">
                <h5 class="modal-title">La partie est terminée</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <p>Mon score : {{ score }}</p>
                <h3>Nouvelle partie dans</h3>
                <h1>{{ countdown }}</h1>
                <div style="margin-top: 2rem;" v-html="adsenseContent"></div>
            </div>
            <div class="modal-footer">
              <a href="/" class="btn btn-primary">Rejoindre d'autres parties</a>
            </div>
          </div>
        </div>
    </div>

</div>

</template>

<script>
    export default {

        name: 'customPlayer',

        props:['game'],

        data() {
            return {
                player: null,
                waiting: false,
                waitingTrack: false,
                listening: null,
                ended: null,
                countdown: 0,
                answers: [],
                users: [],
                userAnswer: '',
                currentTrack: null,
                counter: 0,
                percentage: 0,
                score: 0,
                placeholder: 'Le titre et/ou l\'artiste?',
                alertContent: '',
                alertClass: 'alert-primary',
                adsenseContent: null,
                volume: 1,
            }
        },
        mounted() {
            //this.startGame();
            $("#startModal").modal('show');
            //this.adsenseContent = document.getElementById('divadsensedisplaynone').innerHTML
        },
        created() {
            //
        },

        methods: {

            changeVolume() {
                this.player.volume = this.volume;
            },

            startGame() {

                this.counter = 0;
                this.score = 0;
                this.answers = [];
                this.users = [];
                this.ended = null;
                this.currentTrack = null;

                this.player = new Audio();
                this.player.play();

                // Update answers component
                this.$emit('updateAnswers', this.answers);

                // Reset score list
                this.$emit('updateUsers', this.users);


                this.sendNewScore();
                $('#startModal').modal('hide');
                $('#finnish').modal('hide');

                if (!this.listening) {
                    this.listen();
                    this.listening = true;
                }

            },

            listen() {

                this.waiting = true;

                Echo.channel('game-' + this.game.id)
                    .listen('PlayTrack', (data) => {
                        console.log('track event');
                        if (this.ended) this.startGame();
                        if (this.currentTrack) this.nextTrack();
                        this.currentTrack = data.track;
                        this.currentTrack.track_score = 0;
                        this.currentTrack.artist_score = 0;
                        this.currentTrack.custom_score = 0;
                        this.currentTrack.bonus_score = 0;
                        this.playAudio();
                        this.waiting = false;
                    })
                    .listen('PauseTrack', (data) => {
                        this.player.pause();
                    })
                    .listen('ResumeTrack', (data) => {
                        this.player.play();
                    })
                    .listen('StopGame', (data) => {
                        this.endGame();
                    })

            },


            playAudio() {

                // Init
                let vm = this;

                this.percentage = 0;
                this.placeholder = "Le titre et/ou l'artiste?";

                //if(this.player) this.player.pause();
                this.waitingTrack = false;

                this.player.src = this.currentTrack.preview_url;
                this.player.play();

                // If the file is not available get another track
                this.player.onerror = function() {
                    axios.post('/tracks/' + vm.currentTrack.id + '/updatetrackrate').then((response) => {
                        console.log('La rapport d\'erreur a été envoyé sur la piste défaillante')
                    }).catch((error) => {
                        console.warn(error);
                    });
                };

                // Get the current audio percentage
                this.player.ontimeupdate = function() {
                    vm.percentage = 100 * vm.player.currentTime / vm.player.duration;
                };

                this.player.onended = function() {

                    vm.waitingTrack = true;
                    vm.placeholder = 'En attente du prochain titre...';
                    vm.endTrack();

                };

            },



            nextTrack() {

                // Display the last track on answers
                this.answers.unshift(this.currentTrack);

                // Increment track counter
                this.counter++;

                // Update answers component
                this.$emit('updateAnswers', this.answers);

                // UPDATE SCORE
                this.sendNewScore(true);

                this.currentTrack = null;

            },

            endTrack() {

                this.nextTrack();

                this.$emit('track:end', 1);

            },


            endGame() {

                this.player.pause();
                this.ended = true;
                this.countdown = 30;
                this.countDownTimer();

                $('#finnish').modal('show');
                this.$emit('game:end', null);

                // Save score
                if (this.score > 0) {
                    axios.post('/games/' + this.game.id + '/score', {score: this.score}).then((response) => {
                        //console.log(response.data);
                    }).catch((error) => {
                        console.warn(error);
                    });
                }

            },

            countDownTimer() {
                if(this.countdown > 0) {
                    setTimeout(() => {
                        this.countdown -= 1
                        this.countDownTimer()
                    }, 1000)
                }
            },


            sanitize(string) {
              const a = 'àáâäæãåāăąçćčđďèéêëēėęěğǵḧîïíīįìłḿñńǹňôöòóœøōõőṕŕřßśšşșťțûüùúūǘůűųẃẍÿýžźżЯ·/_,:;!()[]'
              const b = 'aaaaaaaaaacccddeeeeeeeegghiiiiiilmnnnnoooooooooprrsssssttuuuuuuuuuwxyyzzzr-----------'
              const p = new RegExp(a.split('').join('|'), 'g')

              return string.toString().toLowerCase()
                .replace(/ *\([^)]*\) */g, "") // Remove string inside parentheses
                .replace(/ *\[[^)]*\] */g, "") // Remove string inside brackets
                .replace('les ','') // Remove pronoums THE/LES
                .replace('the ','') // Remove pronoums THE/LES
                .replace('& ','') // Remove pronoums THE/LES
                .replace('and ','') // Remove AND/&
                .replace('p!nk','pink') // Special Pink
                .replace(/\s+/g, '-') // Replace spaces with -
                .replace(p, c => b.charAt(a.indexOf(c))) // Replace special characters
                .replace(/[^\w\-]+/g, '') // Remove all non-word characters
                .replace(/\-\-+/g, '-') // Replace multiple - with single -
                .replace(/^-+/, '') // Trim - from start of text
                .replace(/-+$/, '') // Trim - from end of text
            },

            checkResponse() {

                var title = this.sanitize(this.currentTrack.track_name);
                var artist = this.sanitize(this.currentTrack.artist_name);

                var user = this.sanitize(this.userAnswer);

                var titleSimilarity = this.similarity(title, user);
                var artistSimilarity = this.similarity(artist, user);

                var titleArtistSimilarity = this.similarity(title + ' ' + artist, user);
                var artistTitleSimilarity = this.similarity(artist + ' ' + title, user);

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

                var limit = 0.85;


                if (customScore > titleSimilarity || customScore > artistSimilarity) {

                    if (this.currentTrack.track_score !== 0.5 && customScore < 0.755) {
                        this.alertMessage(1, "track");
                    }
                    if (this.currentTrack.track_score !== 0.5 && customScore >= 0.755 && customScore < limit) {
                        this.alertMessage(2, "track");
                    }                
                    if (this.currentTrack.track_score !== 0.5 && customScore >= limit) {
                        this.alertMessage(3, "track");
                    }
                    if (this.currentTrack.track_score !== 0.5 && customScore >= limit && this.percentage < 25) {
                        this.alertMessage(4, "track");
                    }

                } else {

                    if (titleArtistSimilarity > titleSimilarity && titleArtistSimilarity > artistSimilarity && titleArtistSimilarity > artistTitleSimilarity) {
                        if (this.currentTrack.track_score !== 0.5 && titleArtistSimilarity < 0.755) {
                            this.alertMessage(1, "track");
                        }
                        if (this.currentTrack.track_score !== 0.5 && titleArtistSimilarity >= 0.755 && titleArtistSimilarity < limit) {
                            this.alertMessage(2, "track");
                        }                
                        if (this.currentTrack.track_score !== 0.5 && titleArtistSimilarity >= limit) {
                            this.alertMessage(3, "both");
                        }
                        if (this.currentTrack.track_score !== 0.5 && titleArtistSimilarity >= limit && this.percentage < 25) {
                            this.alertMessage(4, "track");
                        }   
                    }

                    else if (artistTitleSimilarity > titleSimilarity && artistTitleSimilarity > artistSimilarity &&artistTitleSimilarity > titleArtistSimilarity) {
                        if (this.currentTrack.track_score !== 0.5 && artistTitleSimilarity < 0.755) {
                            this.alertMessage(1, "track");
                        }
                        if (this.currentTrack.track_score !== 0.5 && artistTitleSimilarity >= 0.755 && artistTitleSimilarity < limit) {
                            this.alertMessage(2, "track");
                        }                
                        if (this.currentTrack.track_score !== 0.5 && artistTitleSimilarity >= limit) {
                            this.alertMessage(3, "both");
                        }
                        if (this.currentTrack.track_score !== 0.5 && artistTitleSimilarity >= limit && this.percentage < 25) {
                            this.alertMessage(4, "track");
                        }
                    }

                    else if (titleSimilarity > artistSimilarity) {
                        if (this.currentTrack.track_score !== 0.5 && titleSimilarity < 0.755) {
                            this.alertMessage(1, "track");
                        }
                        if (this.currentTrack.track_score !== 0.5 && titleSimilarity >= 0.755 && titleSimilarity < limit) {
                            this.alertMessage(2, "track");
                        }                
                        if (this.currentTrack.track_score !== 0.5 && titleSimilarity >= limit) {
                            this.alertMessage(3, "track");
                        }
                        if (this.currentTrack.track_score !== 0.5 && titleSimilarity >= limit && this.percentage < 25) {
                            this.alertMessage(4, "track");
                        }
                    } else {
                        if (this.currentTrack.artist_score !== 0.5 && artistSimilarity < 0.755) {
                            this.alertMessage(1, "artist");
                        }
                        if (this.currentTrack.artist_score !== 0.5 && artistSimilarity >= 0.755 && artistSimilarity < limit) {
                            this.alertMessage(2, "artist");
                        }                
                        if (this.currentTrack.artist_score !== 0.5 && artistSimilarity >= limit) {
                            this.alertMessage(3, "artist");
                        }
                        if (this.currentTrack.artist_score !== 0.5 && artistSimilarity >= limit && this.percentage < 25) {
                            this.alertMessage(4, "artist");
                        }
                    }

                }

                /*
                console.log('Artist : ' + title);
                console.log('Titre : ' + artist);
                console.log('User : ' + user);
                console.log('Pourcentage : ' + this.percentage);
                */

                // SCORE
                if (titleArtistSimilarity >= limit || artistTitleSimilarity >= limit) {
                    this.currentTrack.track_score = 0.5;
                    this.currentTrack.artist_score = 0.5;
                }

                if (this.currentTrack.track_score !== 0.5)
                    this.currentTrack.track_score = (titleSimilarity >= limit || user.includes(title)) ? 0.5 : 0;

                if (this.currentTrack.artist_score !== 0.5)
                    this.currentTrack.artist_score = (artistSimilarity >= limit || user.includes(artist)) ? 0.5 : 0;

                if (this.currentTrack.custom_score !== 1)
                    this.currentTrack.custom_score = (customScore >= limit) ? 1 : 0;


                if (this.currentTrack.track_score == 0.5 && this.currentTrack.artist_score == 0.5 && this.percentage < 25) {
                    this.currentTrack.bonus_score = 0.5;
                }

                if (this.currentTrack.custom_score == 1 && this.percentage < 25) {
                    this.currentTrack.bonus_score = 0.5;
                }

                // UPDATE SCORE
                this.sendNewScore();


                // PLACEHOLDERS
                if (custom) {

                    // this.placeholder = this.game.placeholder;

                    if (this.currentTrack.custom_score == 1) {
                        this.placeholder = 'L\'artiste et le titre en bonus?';
                    }
                }

                if (this.currentTrack.track_score == 0.5) {
                    this.placeholder = 'Et l\'artiste?';
                }
                if (this.currentTrack.artist_score == 0.5) {
                    this.placeholder = 'Et le titre?';
                }
                if (this.currentTrack.track_score == 0.5 && this.currentTrack.artist_score == 0.5) {
                    this.placeholder = 'Bien joué!';
                }

                // RESET
                this.userAnswer = '';

            },

            sendNewScore(end = false) {

                this.countScore(end);

                axios.post('/games/' + this.game.id + '/update/score', {score: this.score}).then((response) => {
                    this.$emit('updateScore', response.data);
                }).catch((error) => {
                    console.warn(error);
                });

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
                        "Il y a quelqu'un?",
                        "C'est l'heure de la sieste?",
                        "Il faut jouer pour gagner."
                      ]
                    },
                    "1": {
                      "track": [
                        "La pluie de tes injures n’atteint pas le parapluie de mon indifférence.",
                        "Cela semble toujours impossible, jusqu’à ce qu’on le fasse.",
                        "Peu importe si tu avances lentement, du moment que tu ne t'arrête pas",
                        "Non, tu n'as pas épuisé toutes les possibilités",
                        "L’échec, c’est ce qui donne à la réussite sa valeur.",
                        "Pour être un bon vainqueur, il faut être un bon perdant.",
                        "Celui qui tombe et se relève est bien plus fort que celui qui ne tombe jamais.",
                        "Ne te soucis pas du nombre de tes échecs. Tu n'a qu’à réussir une fois.",
                        "Il n'y a pas de réussite facile ni d'échecs définitifs.",
                        "Besoin de cotons-tiges en bambou?",
                        "Tu peux vraiment mieux faire.",
                        "N'importe quoi!",
                        "Revois tes classiques!",
                        "C'est tout ce que ça t'inspire?",
                        "Tu devrais monter le volume...",
                        "Pendant ce temps, à Vera Cruz....",
                        "J'aurais pas fais ça comme ça...",
                        "Faut pas pousser mémé dans les orties!"
                      ],
                      "artist": [
                        "La pluie de tes injures n’atteint pas le parapluie de mon indifférence.",
                        "Cela semble toujours impossible, jusqu’à ce qu’on le fasse.",
                        "Peu importe si tu avances lentement, du moment que tu ne t'arrête pas",
                        "Non, tu n'as pas épuisé toutes les possibilités",
                        "L’échec, c’est ce qui donne à la réussite sa valeur.",
                        "Pour être un bon vainqueur, il faut être un bon perdant.",
                        "Celui qui tombe et se relève et bien plus fort que celui qui ne tombe jamais.",
                        "Ne te soucis pas du nombre de tes échecs. Tu n'a qu’à réussir une fois.",
                        "Il n'y a pas de réussite facile ni d'échecs définitifs.",
                        "Besoin de cotons-tiges en bambou?",
                        "Tu peux vraiment mieux faire.",
                        "N'importe quoi!",
                        "Revois tes classiques!",
                        "C'est tout ce que ça t'inspire?",
                        "Tu devrais monter le volume...",
                        "Pendant ce temps, à Vera Cruz....",
                        "J'aurais pas fais ça comme ça...",
                        "Faut pas pousser mémé dans les orties!"
                       ]
                    },
                    "2": {
                      "track": [
                        "Tu y es presque!",
                        "La vérité n'est pas loin.",
                        "Persiste, ça se joue à peu!",
                        "Creuse encore, tu es au bout du tunnel."
                      ],
                      "artist": [
                        "Tu y est presque!",
                        "La vérité n'est pas loin.",
                        "Persiste, ça se joue à peu!",
                        "Creuse encore, tu es au bout du tunnel."
                      ]
                    },
                    "3": {
                      "track": [
                        "Félicitations tu a le titre!",
                        "Tu as trouvé le titre!",
                        "Bravo!"
                      ],
                      "artist": [
                        "Félicitations tu a l'artiste!",
                        "Tu as trouvé l'artiste!",
                        "Bravo!"
                      ],
                      "both": [
                        "Félicitations tu a le titre et l'artiste!",
                        "Tu as trouvé le titre et l'artiste!",
                        "Bravo!"
                      ]
                    },
                    "4": {
                      "track": [
                        "Dis donc tu es un rapide toi!",
                        "Quelle vitesse! Quelle puissance!",
                        "Speeeeeedyy Gonzales!!!"
                      ],
                      "artist": [
                        "Dis donc tu es un rapide toi!",
                        "Quelle vitesse! Quelle puissance!",
                        "Speeeeeedyy Gonzales!!!"
                      ]
                    }
                }

                this.alertContent = items[level][type][Math.floor(Math.random()*items[level][type].length)];
                
            },

            countScore(end) {

                this.score = 0;

                for (var i = 0; i <  this.answers.length; i++) {
                    this.score += this.answers[i].track_score;
                    this.score += this.answers[i].artist_score;
                    this.score += this.answers[i].custom_score;
                    this.score += this.answers[i].bonus_score;
                };

                if (this.currentTrack && !end) {
                    this.score += this.currentTrack.track_score;
                    this.score += this.currentTrack.artist_score;
                    this.score += this.currentTrack.custom_score;
                    this.score += this.currentTrack.bonus_score;
                }
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

    .player .game-title {
        text-transform: capitalize;
        font-style: italic;
        font-size: 2rem;
        color: #ffc907;
        font-weight: 800;
        display: inline-block;
    }

    .player .user-input {
        font-size: 1.5rem;
        text-transform: uppercase;
    }

    .player .form .btn {
        position: absolute;
        right: 20px;
        height: 65px;
        width: 80px;
        cursor: pointer;
        border-radius: 0;
        top: 5px;
        background: #fed251;
        background: -moz-linear-gradient(-45deg, #fed251 1%, #feb208 100%);
        background: -webkit-linear-gradient(-45deg, #fed251 1%, #feb208 100%);
        background: linear-gradient(135deg, #fed251 1%, #feb208 100%);
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#fed251', endColorstr='#feb208', GradientType=1);
        border: 0;
    }
    .progress-bar {
        transition : width 0.5s ease;
    }
    .answers {
        font-size: 120%;
    }
    .alert {
        width: 100%;
        z-index: 40;
        position: absolute;
        top: -20px;
        font-size: 130%;
    }
    .modal-footer {
        padding: 1rem 1rem;
    }

</style>