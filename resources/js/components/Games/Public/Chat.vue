<template>

  <div class="d-flex flex-column flex-grow-1">

    <div class="row" style="min-height: 35vh; max-height: 200px; overflow-y: auto;" ref="messageBox">
        <div class="col-12" v-for="message in orderedMessages">
          <div v-if="message.sender_name === 'blinest'">
            <small>{{ message.message }}</small>
          </div>
          <div v-else class="message" :class="{'from-them': message.sender_id !== game.currentUser.id}">
            <p class="message-text">
              <span class="message-votes">
                <i @click="rateDown(message.id)" class="text-danger fas fa-thumbs-down pointer" title="Signaler ce message"></i>
                <template v-for="moderator in game.moderators" v-if="moderator.id === game.currentUser.id">
                  <i @click="blockUser(message.sender_id, message.sender_name)" class="text-danger fas fa-ban pointer" title="Bloquer l'utilisateur pour 24 heures"></i>
                  <i @click="deleteMessage(message.id)" class="text-danger fas fa-trash pointer" title="Supprimer ce message"></i>
                </template>
              </span>
              {{ message.message }}
            </p>
            <small>{{ message.created_at | moment("HH:mm") }}<span v-if="message.sender_id !== game.currentUser.id"> - {{ message.sender_name }}</span></small>
          </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="input-group mt-3">
                <input type="text"
                        id="chatInput"
                        ref="textarea"
                        maxlength="255"
                        class="form-control"
                        placeholder=""
                        v-on:keyup.enter="sendMessage"
                        aria-label="New message"
                        aria-describedby="button-addon2"
                        v-model="newMessage"
                        v-on:click="hideEmojiPicker">
                <div class="input-group-append">
                    <button class="btn btn-secondary" :class="{ 'triggered': showEmojiPicker }" @mousedown.prevent="toggleEmojiPicker">
                        <i class="far fa-smile-wink"></i>
                    </button>
                </div>

                <picker
                  v-show="showEmojiPicker"
                  :showPreview="false"
                  :showSearch="false"
                  :sheetSize="32"
                  :i18n="i18n"
                  @select="addEmoji"
                />

            </div>
        </div>
    </div>

  </div>

</template>

<script>

  import { Picker } from 'emoji-mart-vue'

  export default {

    name: 'ChatApplication',

    props: ['game'],

    components: { Picker },

    data: () => {
      return {
        users: [],
        messages: [],
        chatOpen: false,
        chatUserID: null,
        loadingMessages: false,
        newMessage: '',
        showEmojiPicker: false,
        i18n: {
          search: 'Recherche',
          notfound: 'Aucune Emoji trouvé',
          categories: {
            search: 'Résultats',
            recent: 'Favoris',
            people: 'Smileys & Personnes',
            nature: 'Animaux & Nature',
            foods: 'Nourriture & Boisson',
            activity: 'Activités',
            places: 'Voyage & Lieux',
            objects: 'Objets',
            symbols: 'Symboles',
            flags: 'Drapeaux',
            custom: 'Personnalisés',
          }
        }

      }
    },
    created () {
      let app = this
      app.loadMessages()
    },
    mounted() {
      this.listenForNewMessage();
    },
    updated() {
      let element = this.$refs.messageBox;
      element.scrollTop = element.scrollHeight;
    },
    methods: {

      toggleEmojiPicker () {
        this.showEmojiPicker = !this.showEmojiPicker
      },

      hideEmojiPicker() {
        if (this.showEmojiPicker)
        this.showEmojiPicker = false;
      },

      addEmoji (emoji) {
        const textarea = this.$refs.textarea;
        const cursorPosition = textarea.selectionEnd
        const start = this.newMessage.substring(0, textarea.selectionStart)
        const end = this.newMessage.substring(textarea.selectionStart)
        const text = start + emoji.native + end
        this.newMessage = text;
        textarea.focus();
        this.toggleEmojiPicker()
      },

      newline() {
        this.value = `${this.value}\n`;
      },

      listenForNewMessage() {
        Echo.channel('chat-' + this.game.id)
          .listen('MessageSent', (data) => {
            $("#chatInput").attr("disabled", false);
            this.messages.push(data.message)
          })
          .listen('MessageDelete', (data) => {
            console.log(data);
            this.messages.splice(this.messages.findIndex(f => f.id === data.message.id), 1);
          })
      },

      loadMessages () {
        let app = this
        app.loadingMessages = true
        app.messages = []
        axios.post('/messages', {
          game_id: app.game.id
        }).then((resp) => {
          app.messages = resp.data
          app.loadingMessages = false
        })
      },

      sendMessage (blinest) {
        let app = this;
        $("#chatInput").attr("disabled", true);
        if (app.newMessage !== '') {
          axios.post('/messages/send', {
            bot: (blinest == true) ? true : false,
            game_id: app.game.id,
            message: app.newMessage
          }).then((resp) => {
            //app.messages.push(resp.data)
            app.newMessage = ''
          })
        }
      },

      rateDown(message_id) {
        axios.post('/messages/vote', {
          message_id: message_id,
          type: 'report'
        }).then((resp) => {
          console.log(resp.data);
        })
      },

      deleteMessage(message_id) {
        axios.post('/messages/delete', {
          message_id: message_id,
        }).then((resp) => {
          console.log("Message supprimé");
        })
      },

      blockUser(user_id, user_name) {
        if (window.confirm("Attention, si tu confirme, l'utilisateur sera banni pendant une heure. Les seuls motifs pour bannir un utilisateur sont le non respect de la loi, les propos haineux, le harcelement, les menaces ou l'usurpation d'identité.")) {
          axios.get('/moderator/user/' + user_id + '/block').then((resp) => {
            console.log(resp.data);
            this.newMessage = "L'utilisateur " + user_name + " a été banni.";
            this.sendMessage(true);
          });
        }
      }

    },

    computed: {
      orderedMessages: function () {
        return _.orderBy(this.messages, 'created_at', 'asc')
      }
    }
  };

</script>

<style scoped>

  .emoji-mart {
    position: absolute;
    bottom: 106%;
    right: 0;
    left: auto;
    bottom: 100%;
    z-index: 40;
  }
  .emoji-trigger {
    position: absolute;
    top: 10px;
    right: 10px;
    cursor: pointer;
    height: 20px;
  }
  .emoji-trigger path {
    transition: 0.1s all;
  }
  .emoji-trigger:hover path {
    fill: #000000;
  }
  .emoji-trigger.triggered path {
    fill: darken(#FEC84A, 15%);
  }

  .message {
    position: relative;
    margin: 0 0 10px 0;
    float: right;
    word-break: break-all;
  }

  .message p {
    margin: 0;
    padding: 6px 10px;   
    color: white;
    background: #0B93F6;
    border-radius: 4px;
  }

  .message.from-them {
    float: left;
  }

  .from-them p {
    background: #FFF;
    color: black;
  }

  .message p:after {
    content: '';
    display: block;
    clear: both;
  }

</style>