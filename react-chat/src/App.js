import React, { useState, useEffect, useRef } from 'react';
import axios from 'axios';
import './App.css';

function App({ groupId }) {
  const [messages, setMessages] = useState([]);
  const [message, setMessage] = useState("");
  const [isTyping, setIsTyping] = useState(false);
  const [typingTimeout, setTypingTimeout] = useState(null);
  const messagesEndRef = useRef(null);
  
  // Changed from userId to studentID to match your PHP session
  const studentId = localStorage.getItem('studentID') || sessionStorage.getItem('studentID');

  const fetchMessages = async () => {
    try {
      const res = await axios.get(`/Study-Hub/php/get_messages.php?groupId=${groupId}`);
      setMessages(res.data);
    } catch (err) {
      console.error("Failed to load messages", err);
    }
  };

  const sendMessage = async (e) => {
    e.preventDefault();
    if (!message.trim()) return;

    try {
      const response = await axios.post(
        '/Study-Hub/php/send_message.php',
        { groupId, message },
        {
          headers: { 'Content-Type': 'application/json' },
          withCredentials: true
        }
      );

      if (response.data.error) {
        console.error("Server error:", response.data.error);
        return;
      }

      setMessage('');
      fetchMessages();
    } catch (err) {
      console.error("Failed to send message", err.response?.data || err.message);
    }
  };

  const handleInputChange = (e) => {
    setMessage(e.target.value);
    if (e.target.value.length > 0) {
      setIsTyping(true);
      if (typingTimeout) clearTimeout(typingTimeout);
      setTypingTimeout(setTimeout(() => setIsTyping(false), 2000));
    } else {
      setIsTyping(false);
    }
  };

  const scrollToBottom = () => {
    messagesEndRef.current?.scrollIntoView({ behavior: "smooth" });
  };

  useEffect(() => {
    fetchMessages();
    const interval = setInterval(fetchMessages, 5000);
    return () => clearInterval(interval);
  }, [groupId]);

  useEffect(() => {
    scrollToBottom();
  }, [messages]);

  return (
    <div className="chat-container">
      <h2>Group Chat</h2>
      
      <div className="messages">
        {messages.map((msg, index) => (
          <div 
            key={index} 
            className={`message ${msg.UserID == studentId ? 'self' : 'other'}`}
          >
            {msg.UserID != studentId && <strong>{msg.Name}: </strong>}
            {msg.Message}
            <div className="message-info">
              <span className="timestamp">
                {new Date(msg.Timestamp).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}
              </span>
              {msg.UserID == studentId && (
                <span className="message-status">
                  {msg.read ? '✓✓' : '✓'}
                </span>
              )}
            </div>
          </div>
        ))}
        
        {isTyping && (
          <div className="typing-indicator">
            <div className="typing-dots">
              <span>.</span><span>.</span><span>.</span>
            </div>
          </div>
        )}
        
        <div ref={messagesEndRef} />
      </div>

      <form onSubmit={sendMessage}>
        <input
          type="text"
          value={message}
          onChange={handleInputChange}
          placeholder="Type your message..."
        />
        <button type="submit">Send</button>
      </form>
    </div>
  );
}

export default App;