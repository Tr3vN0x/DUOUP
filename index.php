<?php
// PHP can be used here for server-side logic if needed, 
// but for this example, we'll keep the file extension for completeness 
// while focusing on the client-side Firebase application.
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Duo Up - Find Your Gaming Partner</title>
    <style>
        /*
        ========================================
        NEON THEME STYLES
        ========================================
        */
        :root {
            --neon-blue: #00ffff;
            --neon-pink: #ff00ff;
            --neon-green: #00ff00;
            --bg-dark: #0a0a1a;
            --text-light: #ffffff;
            --border-color: #333366;
        }

        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: 'Consolas', 'Courier New', monospace;
            background-color: var(--bg-dark);
            color: var(--text-light);
            overflow: hidden; /* Prevent scrollbar on main body */
        }

        #app {
            display: flex;
            flex-direction: column;
            height: 100vh;
            max-width: 1200px;
            margin: 0 auto;
            border-left: 1px solid var(--border-color);
            border-right: 1px solid var(--border-color);
            box-shadow: 0 0 50px rgba(0, 255, 255, 0.1);
        }

        /* --- Header/Auth --- */
        header {
            background: rgba(0, 0, 0, 0.5);
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid var(--neon-blue);
            box-shadow: 0 0 15px var(--neon-blue);
        }

        #welcome {
            font-size: 1.2em;
            color: var(--neon-green);
            text-shadow: 0 0 5px var(--neon-green);
        }

        #authContainer {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        /* --- Tabs --- */
        #tabMenu {
            display: flex;
            justify-content: space-around;
            background: rgba(0, 0, 0, 0.7);
            border-bottom: 1px solid var(--neon-pink);
        }

        .tab-btn {
            flex-grow: 1;
            padding: 15px 10px;
            text-align: center;
            cursor: pointer;
            border: none;
            background: none;
            color: var(--text-light);
            font-family: inherit;
            font-size: 1em;
            transition: all 0.3s;
            border-bottom: 3px solid transparent;
        }

        .tab-btn:hover {
            color: var(--neon-blue);
            text-shadow: 0 0 5px var(--neon-blue);
            background: rgba(0, 255, 255, 0.05);
        }

        .tab-btn.active {
            border-bottom: 3px solid var(--neon-pink);
            color: var(--neon-pink);
            text-shadow: 0 0 10px var(--neon-pink);
        }

        /* --- Content Area --- */
        #content {
            flex-grow: 1;
            overflow-y: auto;
            padding: 20px;
            background-color: rgba(0, 0, 0, 0.3);
            /* Scrollbar style */
            scrollbar-width: thin;
            scrollbar-color: var(--neon-blue) var(--bg-dark);
        }

        #content::-webkit-scrollbar {
            width: 8px;
        }

        #content::-webkit-scrollbar-track {
            background: var(--bg-dark);
        }

        #content::-webkit-scrollbar-thumb {
            background-color: var(--neon-blue);
            border-radius: 20px;
            border: 1px solid var(--bg-dark);
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        /* --- NEON BUTTONS --- */
        .neon-btn {
            background: none;
            color: var(--text-light);
            border: 2px solid var(--neon-blue);
            padding: 8px 15px;
            font-family: inherit;
            font-size: 0.9em;
            cursor: pointer;
            text-shadow: 0 0 5px var(--neon-blue);
            box-shadow: 0 0 10px var(--neon-blue);
            transition: all 0.2s;
            margin: 5px;
        }

        .neon-btn:hover {
            color: black;
            background: var(--neon-blue);
            text-shadow: none;
            box-shadow: 0 0 20px var(--neon-blue);
        }

        .neon-btn.add {
            border-color: var(--neon-green);
            box-shadow: 0 0 10px var(--neon-green);
        }
        .neon-btn.add:hover {
            background: var(--neon-green);
        }

        .neon-btn.remove {
            border-color: var(--neon-pink);
            box-shadow: 0 0 10px var(--neon-pink);
        }
        .neon-btn.remove:hover {
            background: var(--neon-pink);
        }

        .neon-btn.pending {
            border-color: yellow;
            color: yellow;
            box-shadow: 0 0 5px yellow;
            cursor: default;
        }
        .neon-btn.pending:hover {
            background: none;
            color: yellow;
            box-shadow: 0 0 5px yellow;
        }

        .neon-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            box-shadow: none;
            text-shadow: none;
        }

        /* --- Form Elements --- */
        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: var(--neon-pink);
            font-size: 0.9em;
        }

        input[type="text"],
        input[type="password"],
        textarea,
        select {
            width: calc(100% - 22px);
            padding: 10px;
            border: 1px solid var(--neon-blue);
            background: rgba(0, 0, 0, 0.5);
            color: var(--text-light);
            font-family: inherit;
            font-size: 1em;
            box-shadow: 0 0 5px rgba(0, 255, 255, 0.3);
        }

        textarea {
            resize: vertical;
            min-height: 80px;
        }

        .hidden {
            display: none !important;
        }

        /* --- Card Styles (User/Duo) --- */
        .user-card, .duo-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px;
            margin-bottom: 15px;
            background: rgba(0, 0, 0, 0.6);
            border: 1px solid var(--border-color);
            box-shadow: 0 0 10px rgba(255, 0, 255, 0.2);
            transition: background 0.2s;
            cursor: pointer;
        }

        .user-card:hover, .duo-card:hover {
            background: rgba(255, 0, 255, 0.1);
        }

        .user-info {
            display: flex;
            align-items: center;
            flex-grow: 1;
        }

        .avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--neon-blue);
            margin-right: 15px;
            box-shadow: 0 0 10px rgba(0, 255, 255, 0.5);
        }

        .details {
            display: flex;
            flex-direction: column;
        }

        .nickname {
            font-weight: bold;
            font-size: 1.1em;
            color: var(--neon-blue);
            text-shadow: 0 0 3px var(--neon-blue);
            display: flex;
            align-items: center;
        }

        .duo-card .nickname {
             color: var(--neon-green);
             text-shadow: 0 0 3px var(--neon-green);
        }

        .bio-snippet {
            font-size: 0.8em;
            color: #ccc;
        }

        .status-indicator {
            font-size: 0.8em;
            padding: 2px 8px;
            border-radius: 10px;
            display: inline-block;
            margin-left: 10px;
        }

        .status-online {
            background: rgba(0, 255, 0, 0.2);
            color: var(--neon-green);
            box-shadow: 0 0 5px var(--neon-green);
        }

        .status-offline {
            background: rgba(100, 100, 100, 0.2);
            color: #aaa;
        }

        .status-lfd {
            background: rgba(255, 0, 255, 0.2);
            color: var(--neon-pink);
            box-shadow: 0 0 5px var(--neon-pink);
        }
        
        /* --- Duo/Friends Tab Specific --- */
        .friend-section {
            margin-top: 30px;
        }
        .friend-section h3 {
            border-bottom: 1px dashed var(--neon-pink);
            padding-bottom: 5px;
            color: var(--neon-pink);
        }
        #pendingRequestsList .user-card {
            border-color: orange;
            box-shadow: 0 0 10px rgba(255, 165, 0, 0.3);
        }
        #friendList .duo-card {
            border-color: var(--neon-green);
            box-shadow: 0 0 10px rgba(0, 255, 0, 0.3);
        }

        /* --- Messaging Tab --- */
        #messagingContainer {
            display: flex;
            height: calc(100vh - 120px); /* Adjust based on header/tab menu height */
        }

        #duoList {
            width: 250px;
            background: rgba(0, 0, 0, 0.7);
            border-right: 1px solid var(--neon-blue);
            padding: 10px 0;
            overflow-y: auto;
        }

        .chat-entry {
            padding: 10px 15px;
            border-bottom: 1px solid rgba(0, 255, 255, 0.1);
            cursor: pointer;
            transition: background 0.2s;
        }

        .chat-entry:hover {
            background: rgba(0, 255, 255, 0.1);
        }

        .chat-entry.active {
            background: rgba(255, 0, 255, 0.2);
            border-left: 5px solid var(--neon-pink);
        }

        #chatWindow {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        #chatHeader {
            padding: 10px;
            background: rgba(0, 255, 255, 0.1);
            border-bottom: 1px solid var(--neon-blue);
            text-align: center;
            color: var(--neon-blue);
        }

        #messageDisplay {
            flex-grow: 1;
            padding: 15px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }

        .message {
            max-width: 80%;
            margin-bottom: 10px;
            padding: 10px 15px;
            border-radius: 15px;
            font-size: 0.9em;
            line-height: 1.4;
            word-wrap: break-word;
        }

        .message span {
            display: block;
            font-size: 0.7em;
            opacity: 0.6;
            margin-top: 5px;
        }

        .message.sent {
            background: rgba(0, 255, 0, 0.15);
            align-self: flex-end;
            border-bottom-right-radius: 3px;
            border: 1px solid var(--neon-green);
        }

        .message.received {
            background: rgba(255, 0, 255, 0.15);
            align-self: flex-start;
            border-bottom-left-radius: 3px;
            border: 1px solid var(--neon-pink);
        }

        #messageInputArea {
            display: flex;
            padding: 10px;
            border-top: 1px solid var(--neon-blue);
            background: rgba(0, 0, 0, 0.5);
        }

        #messageInput {
            flex-grow: 1;
            margin-right: 10px;
            width: auto; /* Override default width */
        }

        /* --- Profile Tab --- */
        #profileTab, #viewProfileContent {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background: rgba(0, 0, 0, 0.4);
            border: 1px solid var(--neon-pink);
            box-shadow: 0 0 20px rgba(255, 0, 255, 0.3);
            text-align: center;
        }

        #profileAvatar {
            margin-bottom: 15px;
        }

        .nickname-group {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }

        .badge {
            font-size: 0.7em;
            padding: 2px 6px;
            border-radius: 3px;
            font-weight: bold;
            display: inline-block;
            margin-left: 5px;
        }

        .badge-cofounder {
            background: var(--neon-pink);
            color: black;
            border: 1px solid var(--neon-pink);
            box-shadow: 0 0 5px var(--neon-pink);
        }

        .badge-custom {
            background: var(--neon-blue);
            color: black;
            border: 1px solid var(--neon-blue);
            box-shadow: 0 0 5px var(--neon-blue);
        }

        .profile-stat {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px dotted rgba(255, 255, 255, 0.2);
        }
        .profile-stat span:first-child {
            color: var(--neon-green);
        }

        /* --- Global Alerts --- */
        #saveMessage {
            margin-top: 10px;
            color: var(--neon-green);
            font-weight: bold;
            text-shadow: 0 0 5px var(--neon-green);
        }

        /* --- Search/Filter Bar --- */
        #filterBar {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            padding: 10px 0;
            margin-bottom: 20px;
            border-bottom: 1px solid var(--neon-blue);
        }

        #filterBar select, #filterBar input {
            padding: 8px;
            border: 1px solid var(--neon-pink);
            background: rgba(0, 0, 0, 0.6);
            color: var(--text-light);
            font-size: 0.9em;
        }
        
    </style>
</head>
<body>

<div id="app">
    <header>
        <div id="welcome">DUO UP - FIND YOUR PARTNER</div>
        <div id="authContainer">
            <span id="userInfo" class="hidden" style="color:var(--neon-green);"></span>
            <button id="loginBtn" class="neon-btn" onclick="showTab('auth')" style="border-color:var(--neon-pink);">Login</button>
            <button id="logoutBtn" class="neon-btn hidden" onclick="logout()" style="border-color:red;">Logout</button>
        </div>
    </header>

    <div id="tabMenu" class="hidden">
        <button class="tab-btn active" data-tab="allUsers" onclick="showTab('allUsers'); loadAllUsers();">Find Duos</button>
        <button class="tab-btn" data-tab="friends" onclick="showTab('friends'); loadFriends();">My Duos</button>
        <button class="tab-btn" data-tab="messaging" onclick="showTab('messaging'); loadChatList();">Messaging</button>
        <button class="tab-btn" data-tab="profile" onclick="showTab('profile'); loadUserProfile();">Profile</button>
    </div>

    <div id="content">

        <div id="authTab" class="tab-content active" style="max-width:400px; margin: 50px auto; text-align:center;">
            <h2>ACCESS TERMINAL</h2>
            <div class="form-group">
                <label for="email">E-Mail Address</label>
                <input type="text" id="email" placeholder="user@network.com">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" placeholder="********">
            </div>
            <button class="neon-btn" onclick="login()" style="border-color:var(--neon-green);">LOGIN</button>
            <button class="neon-btn" onclick="register()" style="border-color:var(--neon-pink);">REGISTER</button>
            <p id="authMessage" style="margin-top:15px; color:yellow;"></p>
        </div>

        <div id="allUsersTab" class="tab-content">
            <h2 style="color:var(--neon-blue); border-bottom:1px solid var(--neon-blue); padding-bottom:10px;">
                <span style="color:var(--neon-pink);">//</span> DUO MATCHING ENGINE
            </h2>
            
            <div id="filterBar">
                <input type="text" id="searchInput" placeholder="Search Nickname or Bio" onkeyup="filterSearchResults()">
                
                <select id="statusFilter" onchange="filterSearchResults()">
                    <option value="">Status (All)</option>
                    <option value="Online">Online</option>
                    <option value="LFD">LFG/LFD</option>
                    <option value="Away">Away</option>
                </select>
                
                <select id="gameCategoryFilter" onchange="filterSearchResults()">
                    <option value="">Game (All)</option>
                    <option value="FPS">FPS</option>
                    <option value="MOBA">MOBA</option>
                    <option value="MMORPG">MMORPG</option>
                    <option value="Strategy">Strategy</option>
                    <option value="Other">Other</option>
                </select>

                <select id="gameRoleFilter" onchange="filterSearchResults()">
                    <option value="">Role (All)</option>
                    <option value="Attacker">Attacker/Carry</option>
                    <option value="Defender">Defender/Tank</option>
                    <option value="Support">Support/Healer</option>
                    <option value="Generalist">Generalist</option>
                </select>

                <select id="lfdCategoryFilter" onchange="filterSearchResults()">
                    <option value="">LFD Goal (All)</option>
                    <option value="Duo">Duo Partner</option>
                    <option value="Team">Team Member</option>
                    <option value="Casual">Casual Play</option>
                </select>
                
                <button class="neon-btn" onclick="loadAllUsers()">Refresh List</button>
            </div>
            
            <div id="searchResults" style="display:flex; flex-direction:column; gap:10px;">
                <p style="width:100%; text-align:center;">Use the filters above to find players.</p>
            </div>
        </div>

        <div id="friendsTab" class="tab-content">
            <h2 style="color:var(--neon-pink); border-bottom:1px solid var(--neon-pink); padding-bottom:10px;">
                <span style="color:var(--neon-blue);">//</span> MY DUO NETWORK
            </h2>

            <div class="friend-section">
                <h3>INCOMING REQUESTS</h3>
                <div id="pendingRequestsList" style="display:flex; flex-direction:column; gap:10px;">
                    </div>
            </div>

            <div class="friend-section">
                <h3>ACTIVE DUO PARTNERS</h3>
                <div id="friendList" style="display:flex; flex-direction:column; gap:10px;">
                    </div>
            </div>
        </div>

        <div id="messagingTab" class="tab-content">
            <div id="messagingContainer">
                <div id="duoList">
                    <h3 style="padding: 10px; margin: 0; color:var(--neon-pink); border-bottom: 1px solid var(--neon-pink);">ACTIVE DUOS</h3>
                    <div id="activeDuoList">
                        </div>
                </div>
                <div id="chatWindow">
                    <div id="chatHeader">Please select a Duo to chat with.</div>
                    <div id="messageDisplay">
                        <p style="text-align:center; color:#888;">Chat history will load here.</p>
                    </div>
                    <div id="messageInputArea" class="hidden">
                        <input type="text" id="messageInput" placeholder="Type your message...">
                        <button id="sendMessageBtn" class="neon-btn" onclick="sendMessage()">SEND</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="profileTab" class="tab-content">
            <h2>EDIT PROFILE DATA</h2>
            <div id="profileEditor" style="text-align:left;">

                <div style="text-align:center; margin-bottom: 20px;">
                    <img id="profileAvatar" src="" class="avatar" style="width:100px; height:100px; border-color:var(--neon-green);">
                </div>

                <div class="form-group">
                    <label for="profileImageUrlInput">Avatar Image URL (External Link)</label>
                    <input type="text" id="profileImageUrlInput" onkeyup="previewUrl(this.value)" placeholder="https://example.com/avatar.jpg">
                </div>

                <div class="form-group">
                    <label for="profileNickname">Nickname</label>
                    <input type="text" id="profileNickname" maxlength="25">
                    <small id="nicknameChangeInfo" style="display:block; color:yellow;"></small>
                </div>
                
                <div class="form-group">
                    <label for="profileBio">Bio/Tagline (Max 100 chars)</label>
                    <textarea id="profileBio" maxlength="100" placeholder="A short description of yourself and what you play."></textarea>
                </div>

                <div class="form-group">
                    <label for="profileSkillRating">Skill Rating (1-5 Stars)</label>
                    <input type="range" id="profileSkillRating" min="1" max="5" value="3" oninput="this.nextElementSibling.textContent = '⭐'.repeat(this.value)">
                    <p style="text-align:center;">⭐⭐⭐</p>
                </div>

                <div class="form-group">
                    <label for="profileGameCategory">Primary Game Category</label>
                    <select id="profileGameCategory">
                        <option value="FPS">FPS</option>
                        <option value="MOBA">MOBA</option>
                        <option value="MMORPG">MMORPG</option>
                        <option value="Strategy">Strategy</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="profileGameRole">Preferred Role/Mode</label>
                    <select id="profileGameRole">
                        <option value="Attacker">Attacker/Carry</option>
                        <option value="Defender">Defender/Tank</option>
                        <option value="Support">Support/Healer</option>
                        <option value="Generalist">Generalist</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="profileStatus">Current Status</label>
                    <select id="profileStatus" onchange="toggleLfdCategory()">
                        <option value="Online">Online</option>
                        <option value="LFD">Looking For Duo (LFD)</option>
                        <option value="Away">Away / Busy</option>
                        <option value="Offline">Offline</option>
                    </select>
                </div>

                <div class="form-group hidden" id="lfdCategoryGroup">
                    <label for="profileLfdCategory">LFD Goal</label>
                    <select id="profileLfdCategory">
                        <option value="Duo">Duo Partner</option>
                        <option value="Team">Team Member</option>
                        <option value="Casual">Casual Play</option>
                    </select>
                </div>
                
                <button class="neon-btn add" style="width:100%; margin-top:15px;" onclick="saveProfile()">SAVE PROFILE</button>
                <p id="saveMessage" style="text-align:center;"></p>
            </div>
        </div>

        <div id="viewProfileTab" class="tab-content">
            <h2 style="color:var(--neon-blue); border-bottom:1px solid var(--neon-blue); padding-bottom:10px;">
                <span style="color:var(--neon-pink);">//</span> DUO PARTNER PROFILE
            </h2>
            <div id="viewProfileContent" style="max-width:500px; margin: 20px auto; padding: 20px; text-align:center;">
                </div>
            <div style="text-align:center; margin-top: 20px;">
                <button class="neon-btn" onclick="showTab('allUsers')">Back to Search</button>
            </div>
        </div>


    </div> </div> <script src="https://www.gstatic.com/firebasejs/8.6.8/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.6.8/firebase-auth.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.6.8/firebase-firestore.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.6.8/firebase-storage.js"></script>

<script>
// ====================================================
// GLOBAL VARIABLES & CONFIG
// ====================================================
let db;
let auth;
let currentUser = null;
let currentProfileData = {};

// Caching variables
let allUsersCache = [];
let requestCache = {}; // { otherUid: { id: canonicalId, status: 'pending'|'accepted', senderId: 'uid'|'otherUid', receiverId: 'uid'|'otherUid' } }

// Chat variables
let chatListener = null; // Holds the unsubscribe function for the current chat listener
let currentChatId = null;
let currentChatPartnerId = null;

const DEFAULT_AVATAR = 'https://via.placeholder.com/150/0000FF/FFFFFF?text=DUO';

// Define the hardcoded custom badges (Co-Founder)
const CUSTOM_BADGES = {
    // Add your UIDs here: 'uid1': 'Role'
    // Example: 
    // 'ABCDEFGHIJK1234567890': 'Co-Founder' 
};

// ====================================================
// CORE FUNCTIONS
// ====================================================

function initFirebase() {
    // Firebase Configuration (Replace with your actual keys)
    const firebaseConfig = {
        apiKey: "YOUR_API_KEY", // Replace
        authDomain: "YOUR_PROJECT_ID.firebaseapp.com", // Replace
        projectId: "YOUR_PROJECT_ID", // Replace
        storageBucket: "YOUR_PROJECT_ID.appspot.com", // Replace
        messagingSenderId: "YOUR_SENDER_ID", // Replace
        appId: "YOUR_APP_ID" // Replace
    };

    // Initialize Firebase
    if (!firebase.apps.length) {
        firebase.initializeApp(firebaseConfig);
    }
    
    auth = firebase.auth();
    db = firebase.firestore();

    auth.onAuthStateChanged(user => {
        if (user) {
            currentUser = user;
            handleLoginSuccess(user);
        } else {
            currentUser = null;
            handleLogoutSuccess();
        }
    });
}

/**
 * Generates a canonical ID for two user IDs by sorting them and joining with '_'.
 * This is CRITICAL for security rules that require a consistent document ID.
 */
function getCanonicalId(uid1, uid2) {
    return [uid1, uid2].sort().join('_');
}

/**
 * Gets the canonical Chat ID, which is the same as the Duo ID.
 */
function getChatId(otherUid) {
    if (!currentUser) return null;
    return getCanonicalId(currentUser.uid, otherUid);
}


function showTab(tabId) {
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.remove('active');
    });
    document.getElementById(tabId + 'Tab').classList.add('active');

    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    const activeBtn = document.querySelector(`.tab-btn[data-tab="${tabId}"]`);
    if (activeBtn) {
        activeBtn.classList.add('active');
    }
}

// ====================================================
// AUTHENTICATION FUNCTIONS
// ====================================================

function handleLoginSuccess(user) {
    document.getElementById('authContainer').classList.remove('hidden');
    document.getElementById('loginBtn').classList.add('hidden');
    document.getElementById('logoutBtn').classList.remove('hidden');
    document.getElementById('tabMenu').classList.remove('hidden');
    
    // Initial data setup on first login/registration
    db.collection('users').doc(user.uid).get().then(doc => {
        if (!doc.exists) {
            // New user setup
            const initialData = {
                uid: user.uid,
                email: user.email,
                nickname: user.email.split('@')[0],
                profileImageUrl: DEFAULT_AVATAR,
                bio: 'New Duo Up User!',
                skillRating: 3,
                gameCategory: 'Other',
                gameRole: 'Generalist',
                status: 'Online',
                nicknameChangeUsed: false,
                createdAt: firebase.firestore.FieldValue.serverTimestamp()
            };
            db.collection('users').doc(user.uid).set(initialData);
            currentProfileData = initialData;
        } else {
            currentProfileData = doc.data();
        }
        
        const nickname = currentProfileData.nickname || user.email.split('@')[0];
        document.getElementById('welcome').textContent = `WELCOME ${nickname.toUpperCase()} TO DUO UP!`;
        
        // Load the default page after successful login/setup
        showTab('allUsers');
        loadAllUsers(); 

    }).catch(e => {
        console.error("Error setting up user data:", e);
        alert("Failed to load user data. Please try again.");
    });
}

function handleLogoutSuccess() {
    currentUser = null;
    allUsersCache = [];
    requestCache = {};
    chatListener = null;
    currentChatId = null;
    currentChatPartnerId = null;

    document.getElementById('welcome').textContent = 'DUO UP - FIND YOUR PARTNER';
    document.getElementById('loginBtn').classList.remove('hidden');
    document.getElementById('logoutBtn').classList.add('hidden');
    document.getElementById('tabMenu').classList.add('hidden');
    showTab('auth');
}


async function login() {
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const msg = document.getElementById('authMessage');
    msg.textContent = 'Logging in...';

    try {
        await auth.signInWithEmailAndPassword(email, password);
        // handleLoginSuccess will be called by onAuthStateChanged listener
        msg.textContent = 'Login Successful!';
    } catch (e) {
        console.error("Login error:", e);
        msg.textContent = `Login failed: ${e.message}`;
    }
}

async function register() {
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const msg = document.getElementById('authMessage');
    msg.textContent = 'Registering...';

    try {
        // 1. Create User
        const userCredential = await auth.createUserWithEmailAndPassword(email, password);
        const user = userCredential.user;

        // 2. Initial Firestore Setup
        const initialData = {
            uid: user.uid,
            email: user.email,
            nickname: user.email.split('@')[0],
            profileImageUrl: DEFAULT_AVATAR,
            bio: 'New Duo Up User! Edit your profile to be seen!',
            skillRating: 3,
            gameCategory: 'Other',
            gameRole: 'Generalist',
            status: 'Online',
            nicknameChangeUsed: false,
            createdAt: firebase.firestore.FieldValue.serverTimestamp()
        };
        await db.collection('users').doc(user.uid).set(initialData);

        msg.textContent = 'Registration Successful! You are now logged in.';
        // handleLoginSuccess will be called by onAuthStateChanged listener

    } catch (e) {
        console.error("Registration error:", e);
        msg.textContent = `Registration failed: ${e.message}`;
    }
}

function logout() {
    auth.signOut();
    // handleLogoutSuccess will be called by onAuthStateChanged listener
}


// ====================================================
// CACHING & UTILITY FUNCTIONS
// ====================================================

function findUserInCache(uid) {
    return allUsersCache.find(u => u.id === uid);
}

function getCoFounderBadge(uid) {
    if (CUSTOM_BADGES[uid] === 'Co-Founder') {
        return '<span class="badge badge-cofounder">CO-FOUNDER</span>';
    }
    return '';
}

function getCustomBadge(uid) {
    // You can extend this for other custom badges
    return '';
}


// ====================================================
// ALL USERS TAB / CACHE REFRESH
// ====================================================

/**
 * Loads all user data and all friend requests/duos into cache.
 */
async function loadAllUsers() {
    if (!currentUser) return;
    const resultsContainer = document.getElementById('searchResults');
    resultsContainer.innerHTML = '<p style="width:100%; text-align:center;">Loading all users and friend data...</p>';

    try {
        // 1. Fetch All Users
        const usersSnapshot = await db.collection('users').get();
        allUsersCache = usersSnapshot.docs.map(doc => ({ id: doc.id, ...doc.data() }));

        // 2. Fetch All Relevant Friend Requests/Duos (where current user is a participant)
        const requestsQuery = db.collection('friendRequests').where('participants', 'array-contains', currentUser.uid);
        const requestsSnapshot = await requestsQuery.get();

        requestCache = {};
        requestsSnapshot.docs.forEach(doc => {
            const data = doc.data();
            const otherUid = data.senderId === currentUser.uid ? data.receiverId : data.senderId;
            
            // Populate the cache keyed by the OTHER user's UID
            requestCache[otherUid] = {
                id: doc.id, // The canonical request ID
                status: data.status,
                senderId: data.senderId,
                receiverId: data.receiverId
            };
        });

        // 3. Update the displayed list using the filter logic
        filterSearchResults();

    } catch (e) {
        console.error("Error loading users/requests:", e);
        resultsContainer.innerHTML = '<p style="width:100%; text-align:center; color:red;">Error loading data.</p>';
    }
}

/**
 * Renders an interactive card for a user in the All Users list.
 * @param {string} uid - The user's ID.
 * @param {object} data - The user's profile data.
 * @param {object|undefined} requestInfo - The existing request status (optional).
 */
function renderUserCard(uid, data, requestInfo) {
    if (uid === currentUser.uid) return null; // Don't show current user
    
    const card = document.createElement('div');
    card.className = 'user-card';
    card.setAttribute('onclick', `viewProfile('${uid}', JSON.parse(decodeURIComponent('${encodeURIComponent(JSON.stringify(data))}')) )`);
    
    const statusClass = (data.status || 'Online').replace(/\s/g,'').toLowerCase();
    const badge = getCoFounderBadge(uid) + getCustomBadge(uid);

    let actionButton;

    if (requestInfo && requestInfo.status === 'accepted') {
        actionButton = `<button class="neon-btn" style="border-color:lime; box-shadow:0 0 10px lime;" disabled>Duo Partner</button>`;
    } else if (requestInfo && requestInfo.status === 'pending') {
        if (requestInfo.receiverId === currentUser.uid) {
            // Incoming Request: I must accept/reject
            actionButton = `<button class="neon-btn add" onclick="event.stopPropagation(); acceptFriendRequest('${requestInfo.id}')">Accept</button>
                            <button class="neon-btn remove" onclick="event.stopPropagation(); rejectFriendRequest('${requestInfo.id}')">Reject</button>`;
        } else {
            // Outgoing Request: Waiting for them
            actionButton = `<button class="neon-btn pending" disabled>Pending Request</button>`;
        }
    } else {
        // No request or rejected/deleted
        actionButton = `<button class="neon-btn add" onclick="event.stopPropagation(); sendFriendRequest('${uid}')">Send Request</button>`;
    }

    card.innerHTML = `
        <div class="user-info">
            <img src="${data.profileImageUrl || DEFAULT_AVATAR}" class="avatar" alt="${data.nickname}">
            <div class="details">
                <div class="nickname">
                    ${data.nickname || 'Unknown Duo'}
                    ${badge}
                </div>
                <div class="bio-snippet">${data.bio ? data.bio.substring(0, 50) + (data.bio.length > 50 ? '...' : '') : 'No bio.'}</div>
            </div>
        </div>
        <div style="text-align:right;">
            <div class="status-indicator status-${statusClass}">${data.status || 'Online'}</div>
            ${actionButton}
        </div>
    `;
    return card;
}

/**
 * Renders a specialized card for an accepted Duo partner (for the Friends tab).
 */
function renderDuoCard(uid, data) {
    const card = document.createElement('div');
    card.className = 'duo-card';
    card.setAttribute('onclick', `viewProfile('${uid}', JSON.parse(decodeURIComponent('${encodeURIComponent(JSON.stringify(data))}')) )`);
    
    const chatId = getChatId(uid);
    const statusClass = (data.status || 'Online').replace(/\s/g,'').toLowerCase();
    const badge = getCoFounderBadge(uid) + getCustomBadge(uid);

    card.innerHTML = `
        <div class="user-info">
            <img src="${data.profileImageUrl || DEFAULT_AVATAR}" class="avatar" style="border-color:lime;" alt="${data.nickname}">
            <div class="details">
                <div class="nickname">
                    ${data.nickname || 'Unknown Duo'}
                    ${badge}
                </div>
            </div>
            <p style="margin:5px 0; font-size:0.9em; color:lime; margin-left: 20px;">DUO PARTNER</p>
            <div class="status-indicator status-${statusClass}">${data.status||'Online'}</div>
        </div>
        <button class="neon-btn" style="border-color:magenta; box-shadow:0 0 10px magenta;" 
                onclick="event.stopPropagation(); showTab('messaging'); selectChat('${chatId}', '${uid}')">
            Message Duo
        </button>
    `;
    return card;
}

// ---------------------------
// PROFILE EDITOR FUNCTIONS
// ---------------------------

// NEW FUNCTION: Updates the profile avatar preview from the URL field
function previewUrl(url) {
    // Basic check to prevent breakage if user clears the field
    document.getElementById('profileAvatar').src = url.trim() || DEFAULT_AVATAR;
}

async function saveProfile() {
    if (!currentUser) return;
    document.getElementById('saveMessage').textContent = 'Saving profile...';
    
    try {
        const nicknameInput = document.getElementById('profileNickname');
        const nickname = nicknameInput.value.trim();
        const bio = document.getElementById('profileBio').value.trim();
        const skillRating = parseInt(document.getElementById('profileSkillRating').value);
        const gameCategory = document.getElementById('profileGameCategory').value;
        const gameRole = document.getElementById('profileGameRole').value;
        const status = document.getElementById('profileStatus').value;
        const lfdCategory = document.getElementById('profileLfdCategory').value;
        
        // 🚨 PROFILE PICTURE INTEGRATION (Updated for URL input)
        // Get the value directly from the new URL input field
        let profileImageUrl = document.getElementById('profileImageUrlInput').value.trim() || DEFAULT_AVATAR;

        const updateData = {
            bio,
            skillRating,
            gameCategory,
            gameRole,
            status,
            profileImageUrl: profileImageUrl, // <--- New URL is saved directly
            lfdCategory: status === 'LFD' ? lfdCategory : null,
        };

        // 2. Handle Nickname Change (if allowed)
        if (nickname !== currentProfileData.nickname) {
            if (currentProfileData.nicknameChangeUsed === true) {
                alert('You have already used your one-time nickname change.');
                nicknameInput.value = currentProfileData.nickname; // Revert change
                document.getElementById('saveMessage').textContent = 'Error: Nickname change limit reached.';
                return;
            }
            updateData.nickname = nickname;
            updateData.nicknameChangeUsed = true;
        }

        // 3. Update Firestore
        await db.collection('users').doc(currentUser.uid).update(updateData);

        // Update local cache and UI
        currentProfileData = { ...currentProfileData, ...updateData };
        document.getElementById('saveMessage').textContent = 'Profile saved successfully! Reloading data...';
        document.getElementById('nicknameChangeInfo').textContent = '(Nickname change is now used.)';
        
        // Update the welcome message immediately with the new nickname
        document.getElementById('welcome').textContent = `WELCOME ${nickname.toUpperCase()} TO DUO UP!`;

        // Re-load to refresh all UI components
        setTimeout(() => {
            showTab('profile'); // Keep user on profile page
            loadAllUsers(); // Refresh the main user list/cache
        }, 1000);

    } catch (e) {
        console.error("Error saving profile: ", e);
        document.getElementById('saveMessage').textContent = `Error saving profile: ${e.message}`;
    }
}

async function loadUserProfile() {
    if (!currentUser) return;

    try {
        const userDoc = await db.collection('users').doc(currentUser.uid).get();
        if (!userDoc.exists) {
            document.getElementById('profileTab').innerHTML = '<p style="color:red;">Profile not found. Please log out and back in.</p>';
            return;
        }

        const data = userDoc.data();
        currentProfileData = data;

        // Update editor fields
        document.getElementById('profileAvatar').src = data.profileImageUrl || DEFAULT_AVATAR;
        
        // === NEW: Set the value of the new input field ===
        document.getElementById('profileImageUrlInput').value = data.profileImageUrl || DEFAULT_AVATAR;
        // ================================================
        
        document.getElementById('profileNickname').value = data.nickname || '';
        document.getElementById('profileBio').value = data.bio || '';
        document.getElementById('profileSkillRating').value = data.skillRating || 3;
        document.getElementById('profileGameCategory').value = data.gameCategory || 'Other';
        document.getElementById('profileGameRole').value = data.gameRole || 'Generalist';
        document.getElementById('profileStatus').value = data.status || 'Online';
        document.getElementById('profileLfdCategory').value = data.lfdCategory || 'Duo';

        // Update nickname info message
        if (data.nicknameChangeUsed) {
            document.getElementById('profileNickname').disabled = true;
            document.getElementById('nicknameChangeInfo').textContent = '(Nickname change has been used. Contact support for further changes.)';
            document.getElementById('nicknameChangeInfo').style.color = 'red';
        } else {
            document.getElementById('profileNickname').disabled = false;
            document.getElementById('nicknameChangeInfo').textContent = '(You get one free nickname change.)';
            document.getElementById('nicknameChangeInfo').style.color = 'yellow';
        }

        toggleLfdCategory();
        document.getElementById('saveMessage').textContent = ''; // Clear previous save messages
        
    } catch (e) {
        console.error("Error loading user profile:", e);
    }
}

function toggleLfdCategory() {
    const status = document.getElementById('profileStatus').value;
    const lfdGroup = document.getElementById('lfdCategoryGroup');
    if (status === 'LFD') {
        lfdGroup.classList.remove('hidden');
    } else {
        lfdGroup.classList.add('hidden');
    }
}

// ---------------------------
// DUO/FRIENDS/MESSAGING FUNCTIONS (IMPLEMENTATION)
// ---------------------------

/**
 * Sends a friend request.
 */
async function sendFriendRequest(receiverId) {
    if (receiverId === currentUser.uid) return alert("Cannot send request to yourself.");

    const receiver = findUserInCache(receiverId);
    if (!receiver) return alert("Error: User not found in cache.");

    // 1. Calculate the required document ID (Canonical ID)
    const canonicalDuoId = getCanonicalId(currentUser.uid, receiverId);

    // Check if a request already exists
    const existingRequest = requestCache[receiverId];
    if (existingRequest) {
        if (existingRequest.status === 'pending') {
            return alert(`A pending request to ${receiver.nickname} already exists.`);
        }
        if (existingRequest.status === 'accepted') {
            return alert(`${receiver.nickname} is already your Duo partner!`);
        }
    }

    try {
        // 2. Use .doc(ID).set() to ensure the document ID is the canonical one
        await db.collection('friendRequests').doc(canonicalDuoId).set({
            status: 'pending',
            senderId: currentUser.uid,
            receiverId: receiverId,
            participants: [currentUser.uid, receiverId],
            // FIX: Using 'createdAt' as required by the security rules
            createdAt: firebase.firestore.FieldValue.serverTimestamp() 
        });

        // ✨ NEW FEATURE: Custom Alert for "Request sent!" in Yellow
        const alertStyle = "background-color: yellow; color: black; border: 2px solid orange; padding: 10px; border-radius: 5px;";
        console.warn(`%cRequest sent to ${receiver.nickname}!`, alertStyle);
        alert(`Request sent to ${receiver.nickname}!`); // Standard alert for immediate user feedback

        // Refresh the current view to update the button status
        loadAllUsers(); 
    } catch (e) {
        console.error("Error sending request:", e);
        alert("Failed to send request: " + e.message);
    }
}

async function acceptFriendRequest(requestId) {
    try {
        await db.collection('friendRequests').doc(requestId).update({
            status: 'accepted',
            acceptedAt: firebase.firestore.FieldValue.serverTimestamp()
        });
        alert('Friend request accepted! You are now a Duo.');
        // Re-load the friends tab
        loadFriends(); 
        // Also refresh the all users list in case this was initiated from there
        loadAllUsers();
    } catch (e) {
        console.error("Error accepting request:", e);
        alert("Failed to accept request: " + e.message);
    }
}

async function rejectFriendRequest(requestId) {
    try {
        // We delete the request upon rejection
        await db.collection('friendRequests').doc(requestId).delete();
        alert('Friend request rejected.');
        // Re-load the friends tab
        loadFriends(); 
        // Also refresh the all users list
        loadAllUsers();
    } catch (e) {
        console.error("Error rejecting request:", e);
        alert("Failed to reject request: " + e.message);
    }
}

async function loadFriends() {
    if (!currentUser) return;
    
    const pendingContainer = document.getElementById('pendingRequestsList');
    const acceptedContainer = document.getElementById('friendList');

    pendingContainer.innerHTML = '<p style="width:100%; text-align:center;">Loading pending requests...</p>';
    acceptedContainer.innerHTML = '<p style="width:100%; text-align:center; color:magenta;">Loading accepted duos...</p>';
    
    // CRITICAL: Ensure all users are loaded into cache first for rendering names/avatars
    if (allUsersCache.length === 0) {
        // Call loadAllUsers to populate both allUsersCache and requestCache
        await loadAllUsers(); 
        // Note: loadAllUsers updates allUsersTab, but we continue here for friendsTab
    }

    pendingContainer.innerHTML = '';
    acceptedContainer.innerHTML = '';
    
    let pendingCount = 0;
    let acceptedCount = 0;

    try {
        // We rely on requestCache being populated by loadAllUsers()
        for (const otherUid in requestCache) {
            const requestInfo = requestCache[otherUid];
            const otherUserData = findUserInCache(otherUid);

            if (!otherUserData) continue; // Skip if user data is missing

            if (requestInfo.status === 'pending' && requestInfo.receiverId === currentUser.uid) {
                // Incoming Pending Request (I must accept/reject)
                // Note: We use renderUserCard here because it has the accept/reject buttons
                const card = renderUserCard(otherUid, otherUserData, requestInfo);
                if (card) {
                    pendingContainer.appendChild(card);
                    pendingCount++;
                }
            } else if (requestInfo.status === 'accepted') {
                // Accepted Duo
                const card = renderDuoCard(otherUid, otherUserData);
                if (card) {
                    acceptedContainer.appendChild(card);
                    acceptedCount++;
                }
            }
        }

        if (pendingCount === 0) {
            pendingContainer.innerHTML = '<p style="width:100%; text-align:center; color:cyan;">No pending requests at this time.</p>';
        }

        if (acceptedCount === 0) {
            acceptedContainer.innerHTML = '<p style="width:100%; text-align:center; color:magenta;">You have no active Duo partners yet. Go find some!</p>';
        }
        
    } catch(e) {
        console.error("Error loading friends:", e);
        pendingContainer.innerHTML = '<p style="width:100%; text-align:center; color:red;">Error loading requests.</p>';
        acceptedContainer.innerHTML = '<p style="width:100%; text-align:center; color:red;">Error loading duos.</p>';
    }
}


async function loadChatList() {
    if (!currentUser) return;
    const duoListContainer = document.getElementById('activeDuoList');
    duoListContainer.innerHTML = '<p style="text-align:center;">Loading Duos...</p>';
    
    // CRITICAL: Ensure all users and requests are loaded/cached
    if (allUsersCache.length === 0) {
        await loadAllUsers(); 
    }

    let chatListContent = '';
    let duoCount = 0;

    try {
        // We rely on requestCache being populated by loadAllUsers()
        for (const otherUid in requestCache) {
            const requestInfo = requestCache[otherUid];
            const partnerData = findUserInCache(otherUid);

            if (requestInfo.status === 'accepted' && partnerData) {
                const chatId = getChatId(otherUid);
                const isActive = chatId === currentChatId ? 'active' : '';

                chatListContent += `
                    <div class="chat-entry ${isActive}" onclick="selectChat('${chatId}', '${otherUid}')">
                        <span style="font-weight:bold; color:lime;">${partnerData.nickname}</span> 
                        <span style="font-size:0.8em; opacity:0.7;">(${partnerData.status || 'Online'})</span>
                    </div>
                `;
                duoCount++;
            }
        }
        
        duoListContainer.innerHTML = chatListContent;

        if (duoCount === 0) {
             duoListContainer.innerHTML = '<p style="text-align:center; color:magenta;">No active Duos to message.</p>';
        }

    } catch(e) {
        console.error("Error loading chat list:", e);
        duoListContainer.innerHTML = '<p style="text-align:center; color:red;">Error loading chat list.</p>';
    }
}


function selectChat(chatId, partnerUid) {
    // 1. Stop previous listener
    if (chatListener) chatListener(); 
    
    // 2. Update list highlighting
    document.querySelectorAll('.chat-entry').forEach(e => e.classList.remove('active'));
    // Simple way to select the entry: find the one with the correct onclick attribute
    const selectedEntry = document.querySelector(`.chat-entry[onclick*="'${chatId}'"]`);
    if (selectedEntry) selectedEntry.classList.add('active');


    // 3. Set new chat info
    currentChatId = chatId;
    currentChatPartnerId = partnerUid;
    const partner = findUserInCache(partnerUid);
    
    document.getElementById('chatHeader').textContent = `Chatting with: ${partner ? partner.nickname : 'Unknown Duo'}`;
    document.getElementById('messageDisplay').innerHTML = 'Connecting...';
    document.getElementById('messageInputArea').classList.remove('hidden');
    
    // 4. Start new listener
    chatListener = db.collection('chats').doc(chatId).collection('messages')
        .orderBy('timestamp', 'asc')
        .onSnapshot(snapshot => {
            // Note: This only clears on first load or manual refresh. 
            // docChanges() handles updates more efficiently but we'll stick to a full refresh for simplicity.
            document.getElementById('messageDisplay').innerHTML = ''; 
            snapshot.docs.forEach(doc => {
                displayMessage(doc.data());
            });
            // Auto-scroll to bottom
            const display = document.getElementById('messageDisplay');
            display.scrollTop = display.scrollHeight;
        }, error => {
            console.error("Chat listener error:", error);
            document.getElementById('messageDisplay').innerHTML = '<p style="color:red;">Error loading messages.</p>';
        });
}

function displayMessage(messageData) {
    const display = document.getElementById('messageDisplay');
    const msgDiv = document.createElement('div');
    const isSent = messageData.senderId === currentUser.uid;
    msgDiv.className = `message ${isSent ? 'sent' : 'received'}`;
    
    const time = messageData.timestamp ? new Date(messageData.timestamp.toMillis()).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) : '...';
    
    msgDiv.innerHTML = `
        ${messageData.text}
        <span>${isSent ? 'You' : findUserInCache(messageData.senderId)?.nickname || 'Duo'} - ${time}</span>
    `;
    display.appendChild(msgDiv);
}

/**
 * Sends a message to the currently selected chat.
 * CRITICAL FIX: Added chatId to match the Firestore Security Rule validation.
 */
async function sendMessage() {
    const input = document.getElementById('messageInput');
    const text = input.value.trim();
    
    if (!text || !currentChatId || !currentUser) {
        console.warn("Message not sent: Input is empty, chat is not selected, or user is not logged in.");
        return;
    }

    // Disable input and button to prevent double send
    input.disabled = true;
    document.getElementById('sendMessageBtn').disabled = true;
    
    try {
        await db.collection('chats').doc(currentChatId).collection('messages').add({
            text: text,
            senderId: currentUser.uid,
            timestamp: firebase.firestore.FieldValue.serverTimestamp(),
            // --- CRITICAL FIX START ---
            chatId: currentChatId // <-- REQUIRED by your Firestore Security Rule
            // --- CRITICAL FIX END ---
        });
        
        // Success: Clear input and re-enable controls
        input.value = ''; 
        input.disabled = false;
        document.getElementById('sendMessageBtn').disabled = false;
        
    } catch (e) {
        // FAILURE: Re-enable controls and show error
        input.disabled = false;
        document.getElementById('sendMessageBtn').disabled = false;

        alert(`Failed to send message. Please check the console for the error. (Error Code: ${e.code || 'UNKNOWN'}). If this is a 'Permission Denied' error, the issue was the missing 'chatId' field!`);
        console.error("!!! FATAL CHAT WRITE ERROR !!!", e);
    }
}


function viewProfile(uid, data) {
    // This is the viewing logic, not the editing logic
    showTab('viewProfile'); 
    
    const container = document.getElementById('viewProfileContent');
    container.innerHTML = 'Loading profile details...';
    
    const statusClass = (data.status || 'Online').replace(/\s/g,'').toLowerCase();
    const gameCategory = data.gameCategory || 'Other';
    const gameCategoryDisplay = gameCategory.replace(/([A-Z])/g, ' $1').trim();
    const gameRoleDisplay = (data.gameRole || 'Generalist').replace(/([A-Z])/g, ' $1').trim();
    const lfdCategory = data.lfdCategory || 'N/A';
    
    // --- COMBINE BADGES ---
    const badge = getCoFounderBadge(uid) + getCustomBadge(uid);
    // ----------------------

    let profileActions = '';
    const requestInfo = requestCache[uid];

    if (requestInfo && requestInfo.status === 'accepted') {
        profileActions = `<button class="neon-btn" style="border-color:lime; color:lime;" onclick="event.stopPropagation(); showTab('messaging'); selectChat('${getChatId(uid)}', '${uid}')">Message Duo</button>`;
    } else if (requestInfo && requestInfo.status === 'pending') {
        // If pending, check if it's an incoming request
        if (requestInfo.receiverId === currentUser.uid) {
            profileActions = `<button class="neon-btn add" onclick="event.stopPropagation(); acceptFriendRequest('${requestInfo.id}')">Accept Request</button>
                              <button class="neon-btn remove" onclick="event.stopPropagation(); rejectFriendRequest('${requestInfo.id}')">Reject</button>`;
        } else {
            profileActions = `<button class="neon-btn pending" disabled>Pending Request</button>`;
        }
    } else {
        profileActions = `<button class="neon-btn add" onclick="event.stopPropagation(); sendFriendRequest('${uid}')">Send Request</button>`;
    }
    
    // Hide actions for own profile
    if (uid === currentUser.uid) {
           profileActions = '<p style="color:cyan;">This is your profile.</p>';
    }


    container.innerHTML = `
        <img src="${data.profileImageUrl||DEFAULT_AVATAR}" class="avatar" style="width:120px; height:120px; border-color:cyan;">
        <div class="nickname-group" style="margin-bottom:15px;">
            <h2 style="margin:0;">${data.nickname||'Unknown Duo'}</h2>
            ${badge}
        </div>
        <p style="font-style:italic; color:#aaa; margin-bottom:20px; text-align:center;">"${data.bio||'No bio provided.'}"</p>
        
        <div class="profile-stat">
            <span>Skill Rating:</span>
            <span>${'⭐'.repeat(data.skillRating||3)}</span>
        </div>
        <div class="profile-stat">
            <span>Primary Game:</span>
            <span>${gameCategoryDisplay}</span>
        </div>
        <div class="profile-stat">
            <span>Role/Mode:</span>
            <span>${gameRoleDisplay}</span>
        </div>
        <div class="profile-stat">
            <span>Status:</span>
            <span class="status-indicator status-${statusClass}">${data.status||'Online'}</span>
        </div>
        ${data.status === 'LFD' ? `<div class="profile-stat"><span>LFD Goal:</span><span>${lfdCategory}</span></div>` : ''}

        <div style="margin-top:30px;">
            ${profileActions}
        </div>
    `;
}

// ----------------------------------------------------
// 3. SEARCH FILTER LOGIC
// ----------------------------------------------------

function filterSearchResults() {
    const resultsContainer = document.getElementById('searchResults');
    resultsContainer.innerHTML = '';
    if (allUsersCache.length === 0) {
        resultsContainer.innerHTML = '<p style="width:100%; text-align:center;">Loading users...</p>';
        return;
    }

    const searchInput = document.getElementById('searchInput').value.toLowerCase();
    const statusFilter = document.getElementById('statusFilter').value;
    const gameCategoryFilter = document.getElementById('gameCategoryFilter').value;
    const gameRoleFilter = document.getElementById('gameRoleFilter').value;
    const lfdCategoryFilter = document.getElementById('lfdCategoryFilter').value;

    const filteredUsers = allUsersCache.filter(user => {
        if (user.id === currentUser.uid) return false;

        // Text Search (Nickname or Bio)
        const matchesText = !searchInput || 
                             user.nickname.toLowerCase().includes(searchInput) || 
                             (user.bio && user.bio.toLowerCase().includes(searchInput));
        if (!matchesText) return false;

        // Status Filter
        const matchesStatus = !statusFilter || user.status === statusFilter;
        if (!matchesStatus) return false;

        // Game/Category Filter
        const matchesGameCategory = !gameCategoryFilter || user.gameCategory === gameCategoryFilter;
        if (!matchesGameCategory) return false;

        // Game Role Filter
        const matchesGameRole = !gameRoleFilter || user.gameRole === gameRoleFilter;
        if (!matchesGameRole) return false;

        // LFD Category Filter (only applies if user status is LFD)
        const matchesLfdCategory = !lfdCategoryFilter || (user.status === 'LFD' && user.lfdCategory === lfdCategoryFilter);
        if (!matchesLfdCategory) return false;

        return true;
    });

    if (filteredUsers.length === 0) {
        resultsContainer.innerHTML = '<p style="width:100%; text-align:center;">No matching players found.</p>';
        return;
    }

    filteredUsers.forEach(user => {
        const card = renderUserCard(user.id, user, requestCache[user.id]);
        if (card) resultsContainer.appendChild(card);
    });
}


// FIX for jQuery style selector not being available (Polyfills for older/non-standard browsers)
if (typeof NodeList.prototype.forEach !== 'function') {
    NodeList.prototype.forEach = Array.prototype.forEach;
}
if (!Element.prototype.matches) {
    Element.prototype.matches = Element.prototype.msMatchesSelector || Element.prototype.webkitMatchesSelector;
}
if (!Element.prototype.closest) {
    Element.prototype.closest = function(s) {
        let el = this;
        do {
            if (el.matches(s)) return el;
            el = el.parentElement || el.parentNode;
        } while (el !== null && el.nodeType === 1);
        return null;
    };
}
// Simple contains polyfill/extension for querySelector (This is often a dependency from a larger missing library, removed for clean, standard JS)
// To keep the code clean and focused on Firebase logic, we will rely on modern browser support for standard selectors.
// document.querySelector = (function(orig) { ... })(document.querySelector);


// ----------------------------------------------------
// 4. START APPLICATION
// ----------------------------------------------------
initFirebase();
</script>

</body>
</html>
