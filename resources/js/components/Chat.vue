<template>
    <div>

      <div class="row" style="min-height: 200px; max-height: 200px; overflow-y: auto;" ref="messageBox">
          <div class="col-12" v-for="message in orderedMessages">
            <div class="message" :class="{'from-them': message.sender_id !== user.id}">
              <p>{{ message.message }}</p>
              <small>{{ message.created_at | moment("HH:mm") }}<span v-if="message.sender_id !== user.id"> - {{ message.sender_name }}</span></small>
            </div>
          </div>
      </div>
      <div class="row">
          <div class="col-12">
              <div class="input-group mt-3">
                  <input type="text"
                          id="chatInput"
                         class="form-control"
                         placeholder=""
                         v-on:keyup.enter="sendMessage"
                         aria-label="New message"
                         aria-describedby="button-addon2" v-model="newMessage">
                  <div class="input-group-append">
                      <button class="btn btn-outline-secondary"
                              type="button"
                              id="button-addon2"
                              @click="sendMessage">
                          Envoyer
                      </button>
                  </div>
              </div>
          </div>
      </div>

    </div>
</template>

<script>
  export default {
    name: 'ChatApplication',
    props: ['game', 'user'],
    data: () => {
      return {
        users: [],
        messages: [],
        chatOpen: false,
        chatUserID: null,
        loadingMessages: false,
        newMessage: ''
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
      listenForNewMessage() {
        Echo.channel('chat-' + this.game.id)
          .listen('MessageSent', (data) => {
            $("#chatInput").attr("disabled", false);
            this.messages.push(data.message)
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
      sendMessage () {
        let app = this;
        $("#chatInput").attr("disabled", true);
        if (app.newMessage !== '') {
          axios.post('/messages/send', {
            game_id: app.game.id,
            message: app.newMessage
          }).then((resp) => {
            //app.messages.push(resp.data)
            app.newMessage = ''
          })
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

  .message {
    position: relative;
    margin: 0 0 10px 0;
    float: right;
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
    background: #E5E5EA;
    color: black;
  }

  .message p:after {
    content: '';
    display: block;
    clear: both;
  }

</style>