<template>
  <AuthenticatedLayout>
    <div class="chat-container">
      <div class="user-list">
        <h2 class="list-title">Users</h2>
        <ul>
          <li v-for="user in users" :key="user.id" @click="selectUser(user)">
            <img :src="user.avatar" :alt="user.name" class="avatar">
            <span class="user-name">{{ user.name }}</span>
          </li>
        </ul>
      </div>
      <div class="chat-box" v-if="userSelected">
        <h2 class="chat-header">Chatting with {{ userSelected.name }}</h2>
        <div class="messages">
          <div v-for="msg in messages" :key="msg.id" :class="['message', msg.from_id === userSelected.id ? 'message-right' : 'message-left']">
            <p>{{ msg.message }}</p>
          </div>
        </div>
        <div class="text-box">
          <textarea v-model="newMessage" class="message-input" placeholder="Type your message here..."></textarea>
          <button @click="sendMessage" class="send-button">Send</button>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script>
import { ref, nextTick } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

export default {
  props: {
    selectedUser: {
      type: Object,
      required: true,
    },
    messages: {
      type: Array,
      required: true,
    },
    users: {
      type: Array,
      required: true,
    },
  },
  components: {
    AuthenticatedLayout,
  },

  setup(props) {
    const userSelected = ref(props.selectedUser ? props.selectedUser : null);
    const messages = ref(props.messages);
    const newMessage = ref('');

    const selectUser = (user) => {
      userSelected.value = user;
      fetchMessages(user.id);
    };

    const fetchMessages = (receiverId) => {
      fetch(`/messages/${receiverId}`)
        .then((response) => {
          if (!response.ok) {
            throw new Error('Network response was not ok');
          }
          return response.json();
        })
        .then((data) => {
          messages.value = data;
          nextTick(() => {
            scrollToBottom();
          });
        })
        .catch((error) => {
          console.error('Error Fetching Messages:', error);
        });
    };

    const sendMessage = () => {
      if (newMessage.value.trim() === '') return;
      fetch('/send-message', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({
          to_id: userSelected.value.id,
          message: newMessage.value,
        }),
      })
        .then(response => {
          if (!response.ok) {
            throw new Error('Network response was not ok');
          }
          return response.json();
        })
        .then(data => {
          newMessage.value = '';
          nextTick(() => {
            scrollToBottom();
          });
        })
        .catch(error => {
          console.error('There was a problem with your fetch operation:', error);
        });
    };

    window.Pusher = Pusher;

    window.Echo = new Echo({
      broadcaster: 'pusher',
      key: import.meta.env.VITE_PUSHER_APP_KEY,
      wsHost: window.location.hostname,
      wsPort: 6001,
      forceTLS: false,
      disableStats: true,
      cluster: 'eu'
    });

    window.Echo.channel('messages').listen('MessageSent', (e) => {
      messages.value.push(e.message);
      scrollToBottom();
    });

    const scrollToBottom = () => {
      const messagesContainer = document.querySelector('.messages');
      if (messagesContainer) {
        messagesContainer.scrollTop = messagesContainer.scrollHeight + 1;
      }
    };

    return {
      userSelected,
      messages,
      newMessage,
      selectUser,
      sendMessage,
    };
  },
};
</script>

<style scoped>
.chat-container {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
}

.list-title {
  margin-bottom: 10px;
  font-size: 1.2rem;
  color: #285ba9;
}

.user-list {
  width: 30%;
  padding: 20px;
  background-color: #f0f0f0;
}

.user-list ul {
  list-style: none;
  padding: 0;
}

.user-list li {
  display: flex;
  align-items: center;
  cursor: pointer;
  padding: 10px;
  border-radius: 5px;
  margin-bottom: 10px;
}

.user-list li:hover {
  background-color: #e0e0e0;
}

.user-list li img.avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  margin-right: 10px;
}

.chat-box {
  flex: 1;
  padding: 20px;
  background-color: #fff;
}

.chat-header {
  margin-bottom: 20px;
  font-size: 1.5rem;
  font-weight: bold;
  color: #285ba9;
}

.messages {
  max-height: 400px;
  overflow-y: auto;
  display: flex;
  flex-direction: column;
}

.message {
  display: inline-block;
  padding: 8px 12px;
  border-radius: 10px;
  margin-bottom: 8px;
}

.message p {
  margin: 0;
}

.message-right {
  background-color: #f16933;
  color: #fff;
  align-self: flex-end;
}

.message-left {
  background-color: #285ba9;
  color: #fff;
  align-self: flex-start;
}

.message-input {
  width: calc(100% - 90px);
  margin-top: 20px;
  padding: 10px;
  border-radius: 5px;
  border: 1px solid #ccc;
  resize: none;
}

.send-button {
  margin-top: 20px;
  padding: 10px 20px;
  background-color: #285ba9;
  color: #fff;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s;
}

.send-button:hover {
  background-color: #285ba9;
}

.text-box {
  display: flex;
  gap: 2rem;
  margin-top: 2rem;
}

.user-name {
  font-size: 1.1rem;
  color: #333;
  font-weight: bold;
}
</style>
