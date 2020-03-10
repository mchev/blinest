<template>

<div>

    <section class="bg-primary text-white">

        <div class="container">

            <div class="player p-4">

                <div class="row">

                    <div v-if="gameStopped" class="col-md-12 text-center p-2">
                        <button class="btn btn-success btn-lg" @click="startGame()"><i class="fas fa-play"></i> Commencer la partie</button>
                    </div>

                    <div v-if="alertContent" class="alert" :class="alertClass" role="alert">
                        {{ alertContent }}
                    </div>

                    <div v-if="gameStopped == false" class="col-md-12">

                        <div class="row mt-4">

                            <div class="col-md-12">

                                <div class="progress">
                                    <div class="progress-bar bg-info" role="progressbar" :style="'width: '+percentage+'%'" :aria-valuenow="percentage" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>

                            </div>

                        </div>

                        <div class="row my-2">

                            <div class="input-group input-group-lg col-md-12">
                                <input type="text" onpaste="return false" v-model="userAnswer" v-on:keyup.enter="checkResponse()" :placeholder="placeholder" class="form-control user-input col-md-12" autofocus>
                                <div class="input-group-append">
                                    <button v-if="!micro"class="btn btn-outline-info bg-white" @click="speech()"><i class="fas fa-microphone"></i></button>
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

    <!-- FINNISH MODAL -->
    <div class="modal" id="finnish" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-secondary text-white">
                <h5 class="modal-title">Partie terminée</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <p>Mon score : {{ score }}</p>
                <button type="button" class="btn btn-success btn-lg" @click="startGame()"><i class="fas fa-play"></i> Nouvelle partie</button>
            </div>
            <div class="modal-footer bg-light">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
              <a href="/" class="btn btn-primary">Voir d'autres parties</a>
            </div>
          </div>
        </div>
    </div>

</div>

</template>

<script>
    export default {
        props:['game'],
        data() {
            return {
                answers: [],
                userAnswer: '',
                currentTrack: null,
                counter: 0,
                gameStopped: true,
                percentage: 0,
                score: 0,
                placeholder: 'Le titre ou l\'artiste?',
                alertContent: '',
                alertClass: 'alert-primary',
                micro: false
            }
        },
        mounted() {
            console.log('Component mounted.')
        },
        created() {
            //
        },

        methods: {

            startGame() {

                this.counter = 0;
                this.score = 0;
                this.answers = [];
                this.gameStopped = false;    

                this.sendNewScore();
                this.hideModal();

                this.getTrack();

            },

            getTrack() {
                axios.post('/games/' + this.game.id + '/track', {answers: this.answers}).then((response) => {
                    this.currentTrack = response.data;
                    this.currentTrack.track_score = 0;
                    this.currentTrack.artist_score = 0;
                    this.currentTrack.custom_score = 0;
                    this.currentTrack.bonus_score = 0;
                    this.playAudio();
                }).catch((error) => {
                    console.warn(error);
                });
            },

            playAudio() {

                // Init
                this.percentage = 0;
                this.placeholder = "Le titre ou l'artiste?";

                let audio = new Audio(this.currentTrack.preview_url);
                let vm = this;

                audio.play();

                // If the file is not available get another track
                audio.onerror = function() {
                    axios.post('/tracks/' + vm.currentTrack.id + '/updatetrackrate').then((response) => {
                      vm.getTrack();
                    }).catch((error) => {
                        console.warn(error);
                    });
                };

                // Get the current audio percentage
                audio.ontimeupdate = function() {
                    vm.percentage = 100 * audio.currentTime / audio.duration;
                };

                // Actions at the end of audio
                audio.onended = function() {

                    vm.answers.unshift(vm.currentTrack);
                    vm.counter++;
                    vm.$emit('updateAnswers', vm.answers);

                    // UPDATE SCORE
                    vm.sendNewScore(true);

                    if(vm.counter < vm.game.tracks_number) {

                        // GET NEXT TRACK
                        vm.getTrack();

                    } else {

                        vm.gameStopped = true;

                        vm.showModal();

                        // Save score
                        if (vm.score > 0) {
                            axios.post('/games/' + vm.game.id + '/score', {score: vm.score}).then((response) => {
                                console.log(response.data);
                            }).catch((error) => {
                                console.warn(error);
                            });
                        }
                    }
                    
                }; 

            },

            showModal() {
                $('#finnish').modal('show');
            },

            hideModal() {
                $('#finnish').modal('hide');
            },

            sanitize(string) {
                // Remove parentheses
                var sanitized = string.replace(/ *\([^)]*\) */g, "");

                // Remove brackets
                var sanitized = sanitized.replace(/ *\[[^)]*\] */g, "");

                // Replace accents by standard chars
                var sanitized = this.replaceAccents(sanitized);

                // EXTRA
                var sanitized = sanitized.replace('Pink','P!nk');

                // SPECIAL CHARS
                var sanitized = sanitized.replace(/[^a-zA-ZÀ-ú0-9 ']/g,'');

                // PRONOUNS
                var sanitized = sanitized.replace(/les |the /g,'');

                return sanitized;
            },

            checkResponse() {

                var title = this.sanitize(this.currentTrack.track_name);
                var artist = this.sanitize(this.currentTrack.artist_name);

                var user = this.sanitize(this.userAnswer);

                var titleScore = this.similarity(title, user);
                var artistScore = this.similarity(artist, user);

                if (this.currentTrack.custom_answer) {
                    var custom = this.sanitize(this.currentTrack.custom_answer);
                    var customScore = this.similarity(custom, user);
                } else {
                    customScore = 0;
                }

                var limit = 0.85;


                if (customScore > titleScore || customScore > artistScore) {

                    if (this.currentTrack.track_score !== 0.5 && customScore < 0.755) {
                        this.alertMessage(1, "track");
                    }
                    if (this.currentTrack.track_score !== 0.5 && customScore >= 0.755 && customScore < limit) {
                        this.alertMessage(2, "track");
                    }                
                    if (this.currentTrack.track_score !== 0.5 && customScore >= limit) {
                        this.alertMessage(3, "track");
                    }
                    if (this.currentTrack.track_score !== 0.5 && customScore >= limit && this.percentage < 20) {
                        this.alertMessage(4, "track");
                    }

                } else {

                    if (titleScore > artistScore) {
                        if (this.currentTrack.track_score !== 0.5 && titleScore < 0.755) {
                            this.alertMessage(1, "track");
                        }
                        if (this.currentTrack.track_score !== 0.5 && titleScore >= 0.755 && titleScore < limit) {
                            this.alertMessage(2, "track");
                        }                
                        if (this.currentTrack.track_score !== 0.5 && titleScore >= limit) {
                            this.alertMessage(3, "track");
                        }
                        if (this.currentTrack.track_score !== 0.5 && titleScore >= limit && this.percentage < 20) {
                            this.alertMessage(4, "track");
                        }
                    } else {
                        if (this.currentTrack.artist_score !== 0.5 && artistScore < 0.755) {
                            this.alertMessage(1, "artist");
                        }
                        if (this.currentTrack.artist_score !== 0.5 && artistScore >= 0.755 && artistScore < limit) {
                            this.alertMessage(2, "artist");
                        }                
                        if (this.currentTrack.artist_score !== 0.5 && artistScore >= limit) {
                            this.alertMessage(3, "artist");
                        }
                        if (this.currentTrack.artist_score !== 0.5 && artistScore >= limit && this.percentage < 20) {
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
                if (this.currentTrack.track_score !== 0.5)
                    this.currentTrack.track_score = (titleScore >= limit) ? 0.5 : 0;

                if (this.currentTrack.artist_score !== 0.5)
                    this.currentTrack.artist_score = (artistScore >= limit) ? 0.5 : 0;

                if (this.currentTrack.custom_score !== 1)
                    this.currentTrack.custom_score = (customScore >= limit) ? 1 : 0;


                if (this.currentTrack.track_score == 0.5 && this.currentTrack.artist_score == 0.5 && this.percentage < 20) {
                    this.currentTrack.bonus_score = 0.5;
                }

                if (this.currentTrack.custom_score == 1 && this.percentage < 20) {
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
                this.$emit('updateScore', this.score);

                axios.post('/games/' + this.game.id + '/update/score', {score: this.score}).then((response) => {
                    //console.log(response.data);
                }).catch((error) => {
                    console.warn(error);
                });

            },

            speech() {

                this.micro = false;
                this.userAnswer = "";
                let vm = this;

                var recognition = new window.webkitSpeechRecognition;
                recognition.continuous = true;
                recognition.interimResults = true;
             
                recognition.onresult = function (e) {
                    for (var i = e.resultIndex; i < e.results.length; ++i) {
                        if (e.results[i].isFinal) {
                            console.log(e.results[i][0].transcript);
                            vm.userAnswer = e.results[i][0].transcript;
                            vm.checkResponse();
                            vm.userAnswer = e.results[i][0].transcript;
                        }
                    }
                }
             
                // start listening
                recognition.start();
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

            replaceAccents(str) {

                var alphabet = { 
                    a:/[\u0061\u24D0\uFF41\u1E9A\u00E0\u00E1\u00E2\u1EA7\u1EA5\u1EAB\u1EA9\u00E3\u0101\u0103\u1EB1\u1EAF\u1EB5\u1EB3\u0227\u01E1\u00E4\u01DF\u1EA3\u00E5\u01FB\u01CE\u0201\u0203\u1EA1\u1EAD\u1EB7\u1E01\u0105\u2C65\u0250]/ig,
                    aa:/[\uA733]/ig,
                    ae:/[\u00E6\u01FD\u01E3]/ig,
                    ao:/[\uA735]/ig,
                    au:/[\uA737]/ig,
                    av:/[\uA739\uA73B]/ig,
                    ay:/[\uA73D]/ig,
                    b:/[\u0062\u24D1\uFF42\u1E03\u1E05\u1E07\u0180\u0183\u0253]/ig,
                    c:/[\u0063\u24D2\uFF43\u0107\u0109\u010B\u010D\u00E7\u1E09\u0188\u023C\uA73F\u2184]/ig,
                    d:/[\u0064\u24D3\uFF44\u1E0B\u010F\u1E0D\u1E11\u1E13\u1E0F\u0111\u018C\u0256\u0257\uA77A]/ig,
                    dz:/[\u01F3\u01C6]/ig,
                    e:/[\u0065\u24D4\uFF45\u00E8\u00E9\u00EA\u1EC1\u1EBF\u1EC5\u1EC3\u1EBD\u0113\u1E15\u1E17\u0115\u0117\u00EB\u1EBB\u011B\u0205\u0207\u1EB9\u1EC7\u0229\u1E1D\u0119\u1E19\u1E1B\u0247\u025B\u01DD]/ig,
                    f:/[\u0066\u24D5\uFF46\u1E1F\u0192\uA77C]/ig,
                    g:/[\u0067\u24D6\uFF47\u01F5\u011D\u1E21\u011F\u0121\u01E7\u0123\u01E5\u0260\uA7A1\u1D79\uA77F]/ig,
                    h:/[\u0068\u24D7\uFF48\u0125\u1E23\u1E27\u021F\u1E25\u1E29\u1E2B\u1E96\u0127\u2C68\u2C76\u0265]/ig,
                    hv:/[\u0195]/ig,
                    i:/[\u0069\u24D8\uFF49\u00EC\u00ED\u00EE\u0129\u012B\u012D\u00EF\u1E2F\u1EC9\u01D0\u0209\u020B\u1ECB\u012F\u1E2D\u0268\u0131]/ig,
                    j:/[\u006A\u24D9\uFF4A\u0135\u01F0\u0249]/ig,
                    k:/[\u006B\u24DA\uFF4B\u1E31\u01E9\u1E33\u0137\u1E35\u0199\u2C6A\uA741\uA743\uA745\uA7A3]/ig,
                    l:/[\u006C\u24DB\uFF4C\u0140\u013A\u013E\u1E37\u1E39\u013C\u1E3D\u1E3B\u017F\u0142\u019A\u026B\u2C61\uA749\uA781\uA747]/ig,
                    lj:/[\u01C9]/ig,
                    m:/[\u006D\u24DC\uFF4D\u1E3F\u1E41\u1E43\u0271\u026F]/ig,
                    n:/[\u006E\u24DD\uFF4E\u01F9\u0144\u00F1\u1E45\u0148\u1E47\u0146\u1E4B\u1E49\u019E\u0272\u0149\uA791\uA7A5]/ig,
                    nj:/[\u01CC]/ig,
                    o:/[\u006F\u24DE\uFF4F\u00F2\u00F3\u00F4\u1ED3\u1ED1\u1ED7\u1ED5\u00F5\u1E4D\u022D\u1E4F\u014D\u1E51\u1E53\u014F\u022F\u0231\u00F6\u022B\u1ECF\u0151\u01D2\u020D\u020F\u01A1\u1EDD\u1EDB\u1EE1\u1EDF\u1EE3\u1ECD\u1ED9\u01EB\u01ED\u00F8\u01FF\u0254\uA74B\uA74D\u0275]/ig,
                    oi:/[\u01A3]/ig,
                    ou:/[\u0223]/ig,
                    oo:/[\uA74F]/ig,
                    oe:/[\u0153]/ig,
                    p:/[\u0070\u24DF\uFF50\u1E55\u1E57\u01A5\u1D7D\uA751\uA753\uA755]/ig,
                    q:/[\u0071\u24E0\uFF51\u024B\uA757\uA759]/ig,
                    r:/[\u0072\u24E1\uFF52\u0155\u1E59\u0159\u0211\u0213\u1E5B\u1E5D\u0157\u1E5F\u024D\u027D\uA75B\uA7A7\uA783]/ig,
                    s:/[\u0073\u24E2\uFF53\u015B\u1E65\u015D\u1E61\u0161\u1E67\u1E63\u1E69\u0219\u015F\u023F\uA7A9\uA785\u1E9B]/ig,
                    ss:/[\u00DF\u1E9E]/ig,
                    t:/[\u0074\u24E3\uFF54\u1E6B\u1E97\u0165\u1E6D\u021B\u0163\u1E71\u1E6F\u0167\u01AD\u0288\u2C66\uA787]/ig,
                    tz:/[\uA729]/ig,
                    u:/[\u0075\u24E4\uFF55\u00F9\u00FA\u00FB\u0169\u1E79\u016B\u1E7B\u016D\u00FC\u01DC\u01D8\u01D6\u01DA\u1EE7\u016F\u0171\u01D4\u0215\u0217\u01B0\u1EEB\u1EE9\u1EEF\u1EED\u1EF1\u1EE5\u1E73\u0173\u1E77\u1E75\u0289]/ig,
                    v:/[\u0076\u24E5\uFF56\u1E7D\u1E7F\u028B\uA75F\u028C]/ig,
                    vy:/[\uA761]/ig,
                    w:/[\u0077\u24E6\uFF57\u1E81\u1E83\u0175\u1E87\u1E85\u1E98\u1E89\u2C73]/ig,
                    x:/[\u0078\u24E7\uFF58\u1E8B\u1E8D]/ig,
                    y:/[\u0079\u24E8\uFF59\u1EF3\u00FD\u0177\u1EF9\u0233\u1E8F\u00FF\u1EF7\u1E99\u1EF5\u01B4\u024F\u1EFF]/ig,
                    z:/[\u007A\u24E9\uFF5A\u017A\u1E91\u017C\u017E\u1E93\u1E95\u01B6\u0225\u0240\u2C6C\uA763]/ig,
                    '':/[\u0300\u0301\u0302\u0303\u0308]/ig
                };

                for (var letter in alphabet) {
                  str = str.replace(alphabet[letter], letter);
                }

                return str;

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
                        "Peu importe si vous avancez lentement, du moment que vous ne vous arrêtez pas",
                        "Quand vous aurez épuisé toutes les possibilités, souvenez-vous de ceci: non, vous ne les avez pas toutes épuisées",
                        "L’échec, c’est ce qui donne à la réussite sa valeur.",
                        "Pour être un bon vainqueur, il faut être un bon perdant.",
                        "Celui qui tombe et se relève et bien plus fort que celui qui ne tombe jamais.",
                        "Ne vous souciez pas du nombre de vos échecs. Vous n’avez qu’à réussir une fois.",
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
                        "Peu importe si vous avancez lentement, du moment que vous ne vous arrêtez pas",
                        "Quand vous aurez épuisé toutes les possibilités, souvenez-vous de ceci: non, vous ne les avez pas toutes épuisées",
                        "L’échec, c’est ce qui donne à la réussite sa valeur.",
                        "Pour être un bon vainqueur, il faut être un bon perdant.",
                        "Celui qui tombe et se relève et bien plus fort que celui qui ne tombe jamais.",
                        "Ne vous souciez pas du nombre de vos échecs. Vous n’avez qu’à réussir une fois.",
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

    }
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