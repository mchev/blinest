<template>

    <div class="h-100 d-flex" style="flex-direction: column;">

      <span @click="hideSidebar" class="btn toggle-chat-sidebar badge badge-secondary p-2" title="Masquer le chat">Chat <i class="fas fa-chevron-right"></i></span>


      <div class="chat-header p-2 mb-2">
        <span class="badge badge-secondary p-2 mb-2">En ligne : {{ usersCount }}</span>
        <span class="badge badge-info p-2 mb-2">Animateur : {{ game.user.name }}</span>
        <a href="" class="badge badge-info p-2" title="Partager cette page"><i class="fas fa-share-alt"></i></a>
      </div>

      <div class="d-flex h-100 p-2 message-box" ref="messageBox">
          <div v-for="message in orderedMessages">
            <div class="message">
              <p><span class="user">{{ message.sender_name }}</span>: {{ message.message }}</p>
            </div>
          </div>
      </div>

      <div class="d-block p-2">

        <div class="textarea-emoji-picker">

          <picker
            v-show="showEmojiPicker"
            :showPreview="false"
            :showSearch="false"
            :i18n="i18n"
            @select="addEmoji"
          />

          <span class="emoji-trigger" :class="{ 'triggered': showEmojiPicker }" @mousedown.prevent="toggleEmojiPicker">
            <i class="far fa-smile-wink"></i>
          </span>

          <textarea
            ref="textarea"
            class="textarea"
            v-model="newMessage"
            @keydown.enter.exact.prevent="sendMessage"
            @keydown.enter.shift.exact="newline"
          ></textarea>

        </div>

      </div>

    </div>

</template>

<script>

  import { Picker } from 'emoji-mart-vue'

  export default {

    name: 'customChat',

    props: ['game'],

    components: { Picker },

    data: () => {
      return {
        users: [],
        usersCount: 0,
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

      hideSidebar() {
        $('.sidebar.sidebar-right').toggleClass('hide');
      },

      toggleEmojiPicker () {
        this.showEmojiPicker = !this.showEmojiPicker
      },

      addEmoji (emoji) {
        const textarea = this.$refs.textarea
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
            this.messages.push(data.message)
          })
        Echo.join('chat-' + this.game.id)
          .here((users) => {
            this.users = users;
            this.usersCount = users.length;
          })
          .joining((user) => {
            this.users.push(user);
            this.usersCount = this.usersCount+1;
          })
          .leaving((user) => {
            this.users.splice(user.index, 1);
            this.usersCount = this.usersCount-1;
          });
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

      sendMessage () {
        let app = this
        console.log(this.newMessage);
        if (app.newMessage !== '') {
          axios.post('/messages/send', {
            game_id: app.game.id,
            message: app.newMessage
          }).then((resp) => {
            //app.messages.push(resp.data)
            app.newMessage = ''
          })
        }
      },

    },

    computed: {
      orderedMessages: function () {
        return _.orderBy(this.messages, 'created_at', 'asc')
      }
    }

  };

</script>

<style scoped>

  .toggle-chat-sidebar {
    position: absolute;
    right: 100%;
    top: 8%;
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
  }

  .chat-header {
    border-bottom: 1px solid rgba(255,255,255,0.5);
  }

  .textarea-emoji-picker {
    position: relative;
    margin: 0 auto;
    color: #FFF;
  }

  .textarea {
    width: 100%;
    outline: none;
    box-shadow: none;
    padding: 10px 28px 10px 10px;
    border: 0;
    color: #333;
    border-radius: 2px;
    resize: none;
    background: #3A3A3D;
    color: #FFF;
    height: 4rem;
    overflow: hidden;
    font-weight: 100;
    font-size: inherit;
  }
  .emoji-mart {
    position: absolute;
    bottom: 106%;
    right: 0;
    left: auto;
    top: auto;
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

  .message-box {
    flex-grow: 1;
    flex-direction: column;
    flex-wrap: nowrap;
    overflow-y: auto;
    max-height: calc(100vh - 180px);
  }

  .message {
    position: relative;
    margin: 0;
  }

  .message p {
    margin: 0;
    padding: 0.3rem;   
    color: white;
    font-weight: 100;
    font-size: 0.8rem;
  }

  .message .user {
    font-weight: bold;
  }

  .message.from-them {
    float: left;
  }

  .from-them p {
    background: #E5E5EA;
    color: black;
  }

  .message p:after {
    content: '';
    display: block;
    clear: both;
  }

</style>