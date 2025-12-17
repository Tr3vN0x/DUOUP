<?php
// index.php
// This PHP file serves as the wrapper for the single-page application (SPA).
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DUO UP - Find Your Game Partner</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap" rel="stylesheet">

    <script src="https://www.gstatic.com/firebasejs/9.0.0/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.0.0/firebase-auth-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.0.0/firebase-firestore-compat.js"></script>

    <style>
        /* Base Reset and Body */
        :root {
            --neon-green: #00ff00;
            --neon-blue: #00ffff;
            --neon-pink: #ff00ff;
            --background-dark: #111111;
            --card-dark: #1b1b1b;
            --text-light: #eeeeee;
        }

        body {
            font-family: 'Orbitron', sans-serif;
            background-color: var(--background-dark);
            color: var(--text-light);
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }

        /* Loading Overlay */
        #loadingOverlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(17, 17, 17, 0.95);
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 10em;
            color: var(--neon-green);
            z-index: 1000;
            transition: opacity 0.5s ease;
        }

        .hidden {
            display: none !important;
        }

        /* Header */
        .header {
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 15px 0;
            margin-bottom: 20px;
            background: linear-gradient(90deg, #111, rgba(0, 255, 0, 0.1), #111);
            border-bottom: 2px solid var(--neon-green);
            box-shadow: 0 0 20px rgba(0, 255, 0, 0.3);
        }

        .icon-container {
            font-size: 2em;
            color: var(--neon-green);
            text-shadow: 0 0 10px var(--neon-green);
            margin-right: 10px;
        }

        h1 {
            margin: 0;
            color: var(--text-light);
            text-shadow: 0 0 5px var(--neon-green);
            font-size: 2.5em;
        }

        /* Main Containers */
        #loginPage, #dashboard {
            width: 95%;
            max-width: 1200px;
            padding: 20px;
            box-sizing: border-box;
            background-color: #1a1a1a;
            border: 1px solid var(--neon-blue);
            box-shadow: 0 0 25px rgba(0, 255, 255, 0.2);
            border-radius: 8px;
        }

        /* Login Page */
        #loginPage {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            min-height: 400px;
            justify-content: center;
        }

        #loginPage p {
            font-size: 1.2em;
            color: var(--neon-pink);
            margin-bottom: 30px;
            text-shadow: 0 0 5px var(--neon-pink);
        }

        /* Neon Button Style */
        .neon-btn {
            background: transparent;
            color: var(--neon-green);
            border: 2px solid var(--neon-green);
            padding: 10px 20px;
            cursor: pointer;
            text-transform: uppercase;
            font-weight: bold;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            box-shadow: 0 0 10px rgba(0, 255, 0, 0.5);
            margin: 5px;
            border-radius: 4px;
        }

        .neon-btn:hover:not(:disabled) {
            background-color: var(--neon-green);
            color: var(--background-dark);
            box-shadow: 0 0 20px var(--neon-green);
        }

        .neon-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .neon-btn.add {
            border-color: var(--neon-blue);
            color: var(--neon-blue);
            box-shadow: 0 0 10px rgba(0, 255, 255, 0.5);
        }
        .neon-btn.add:hover:not(:disabled) {
            background-color: var(--neon-blue);
            color: var(--background-dark);
            box-shadow: 0 0 20px var(--neon-blue);
        }
        .neon-btn.remove {
            border-color: var(--neon-pink);
            color: var(--neon-pink);
            box-shadow: 0 0 10px rgba(255, 0, 255, 0.5);
        }
        .neon-btn.remove:hover:not(:disabled) {
            background-color: var(--neon-pink);
            color: var(--background-dark);
            box-shadow: 0 0 20px var(--neon-pink);
        }
        .neon-btn.pending {
            border-color: orange;
            color: orange;
            box-shadow: 0 0 10px rgba(255, 165, 0, 0.5);
            opacity: 0.8;
        }
        .neon-btn.message {
            border-color: var(--neon-green);
            color: var(--neon-green);
            box-shadow: 0 0 10px rgba(0, 255, 0, 0.5);
        }

        /* Dashboard */
        #welcome {
            text-align: center;
            color: var(--neon-pink);
            margin-bottom: 20px;
            font-size: 1.1em;
            text-shadow: 0 0 5px var(--neon-pink);
        }

        /* Navigation Bar */
        #navigationBar {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
            padding: 10px;
            border-bottom: 1px dashed var(--neon-green);
        }

        #navigationBar button {
            flex-grow: 1;
            max-width: 180px;
            border-color: #333;
            color: #ccc;
            box-shadow: none;
        }

        #navigationBar button.active {
            border-color: var(--neon-green);
            background-color: var(--neon-green);
            color: var(--background-dark);
            box-shadow: 0 0 15px var(--neon-green);
        }

        /* Tab Content */
        .tab-content {
            padding: 20px;
            border-top: 1px solid var(--neon-blue);
            min-height: 400px;
        }

        /* User Card List Layout */
        #allUsersTab, #searchResults, #friendList, #pendingRequestsList {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            padding: 0;
            list-style: none;
            justify-content: center;
        }

        /* Card Style */
        .card {
            background-color: var(--card-dark);
            border: 1px solid #333;
            border-radius: 6px;
            padding: 15px;
            transition: all 0.3s ease;
            box-shadow: 0 0 5px rgba(0, 255, 255, 0.1);
            cursor: pointer;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .card:hover {
            border-color: var(--neon-blue);
            box-shadow: 0 0 15px rgba(0, 255, 255, 0.5);
            transform: translateY(-2px);
        }
        
        .card-content-box {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            margin-bottom: 10px;
            border-bottom: 1px dashed #333;
            padding-bottom: 10px;
        }

        .avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--neon-green);
            box-shadow: 0 0 10px var(--neon-green);
        }

        .nickname-group {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 10px;
        }

        .card h3 {
            margin: 5px 0 0;
            font-size: 1.2em;
            color: var(--neon-blue);
        }

        .status-indicator {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 0.8em;
            margin-top: 5px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-online { background-color: #008800; color: #00ff00; box-shadow: 0 0 5px #00ff00; }
        .status-offline { background-color: #555555; color: #ccc; }
        .status-lfd { background-color: #880088; color: #ff00ff; box-shadow: 0 0 5px #ff00ff; }

        .game-category {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 0.7em;
            margin-top: 5px;
            font-weight: bold;
            text-transform: uppercase;
            display: inline-block;
            box-shadow: 0 0 5px;
        }

        /* Specific Category Colors - Use a few distinct ones */
        .game-category-fps { background-color: #0055ff; color: #fff; box-shadow: 0 0 5px #0055ff; }
        .game-category-mmo { background-color: #ffaa00; color: #111; box-shadow: 0 0 5px #ffaa00; }
        .game-category-moba { background-color: #ff0055; color: #fff; box-shadow: 0 0 5px #ff0055; }
        .game-category-rpg { background-color: #00ffaa; color: #111; box-shadow: 0 0 5px #00ffaa; }
        .game-category-other { background-color: #555555; color: #fff; box-shadow: none; }

        .game-role {
            font-size: 0.8em;
            color: #aaa;
            margin: 5px 0;
        }

        .skill-rating {
            color: gold;
            text-shadow: 0 0 3px #ffdf00;
            font-size: 1.2em;
        }

        .lfd-category {
            background-color: #ff00ff;
            color: #111;
            padding: 2px 8px;
            border-radius: 3px;
            font-size: 0.7em;
            font-weight: bold;
            margin-top: 5px;
            box-shadow: 0 0 5px #ff00ff;
        }


        /* Profile Page */
        #profileTab, #viewProfileTab {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .profile-container {
            width: 100%;
            max-width: 600px;
            padding: 20px;
            background-color: #222;
            border: 1px solid var(--neon-green);
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 255, 0, 0.3);
        }

        .profile-group {
            margin-bottom: 15px;
        }

        .profile-group label {
            display: block;
            margin-bottom: 5px;
            color: var(--neon-blue);
            font-size: 0.9em;
        }

        .profile-group input, .profile-group select, .profile-group textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #555;
            background-color: #333;
            color: var(--text-light);
            border-radius: 4px;
            box-sizing: border-box;
            font-family: 'Orbitron', sans-serif;
        }
        
        #saveMessage {
            text-align: center;
            margin-top: 10px;
            font-weight: bold;
        }

        .profile-stat {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px dashed #333;
        }

        .profile-stat span:first-child {
            color: var(--neon-green);
        }

        /* Search Tab */
        #searchFilters {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 20px;
            justify-content: center;
            padding: 10px;
            background-color: #1a1a1a;
            border: 1px dashed var(--neon-blue);
            border-radius: 4px;
        }

        #searchFilters input, #searchFilters select {
            padding: 8px;
            border: 1px solid #555;
            background-color: #333;
            color: var(--text-light);
            border-radius: 4px;
            font-family: 'Orbitron', sans-serif;
            min-width: 150px;
            flex-grow: 1;
        }

        /* Messaging Tab */
        #messagingTab {
            display: flex;
            gap: 20px;
            min-height: 500px;
        }

        #activeDuoList {
            width: 250px;
            min-width: 200px;
            border-right: 1px solid var(--neon-pink);
            padding-right: 10px;
        }

        .chat-entry {
            padding: 10px;
            margin-bottom: 5px;
            background-color: #222;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.2s, border-left 0.2s;
            color: var(--text-light);
        }

        .chat-entry:hover, .chat-entry.active {
            background-color: #333;
            border-left: 3px solid var(--neon-pink);
            color: var(--neon-pink);
        }

        #chatWindow {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        #chatHeader {
            padding: 10px;
            background-color: #222;
            border-bottom: 1px solid var(--neon-pink);
            text-align: center;
            color: var(--neon-pink);
            margin-bottom: 10px;
        }

        #messageDisplay {
            flex-grow: 1;
            background-color: #151515;
            border: 1px solid #333;
            padding: 10px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 8px;
            border-radius: 4px;
        }

        .message {
            max-width: 70%;
            padding: 8px 12px;
            border-radius: 15px;
            font-size: 0.9em;
            word-wrap: break-word;
            display: flex;
            flex-direction: column;
        }

        .message span {
            font-size: 0.6em;
            opacity: 0.7;
            margin-top: 2px;
        }

        .sent {
            align-self: flex-end;
            background-color: var(--neon-blue);
            color: var(--background-dark);
            border-bottom-right-radius: 3px;
            text-align: right;
        }

        .received {
            align-self: flex-start;
            background-color: var(--neon-green);
            color: var(--background-dark);
            border-bottom-left-radius: 3px;
        }

        #messageInputArea {
            display: flex;
            margin-top: 10px;
        }

        #messageInput {
            flex-grow: 1;
            padding: 10px;
            border: 1px solid var(--neon-pink);
            background-color: #333;
            color: var(--text-light);
            border-radius: 4px 0 0 4px;
            font-family: 'Orbitron', sans-serif;
        }

        #messageInputArea button {
            border-radius: 0 4px 4px 0;
        }

        /* Friends Tab - Group Headers */
        .group-header {
            color: var(--neon-pink);
            font-size: 1.5em;
            text-align: center;
            margin: 20px 0 10px;
            padding-bottom: 5px;
            border-bottom: 2px solid var(--neon-pink);
            text-shadow: 0 0 5px rgba(255, 0, 255, 0.5);
        }
        
        .request-card {
            border-color: var(--neon-pink) !important;
            box-shadow: 0 0 15px rgba(255, 0, 255, 0.5) !important;
        }
    </style>
</head>
<body onload="initFirebase()">

<div id="loadingOverlay">⚡</div>

<div class="header"><div class="icon-container">⚡</div><h1>DUO UP</h1></div>

<div id="loginPage" class="hidden">
    <h2>Welcome to the Cyber Arena</h2>
    <p>Sign in to find your perfect Duo partner.</p>
    <button class="neon-btn add" onclick="login()">Login with Google</button>
</div>

<div id="dashboard" class="hidden">
    <h2 id="welcome">WELCOME CYBER WARRIOR TO DUO UP!</h2>

    <div id="navigationBar">
        <button id="tab-allUsers" class="neon-btn active" onclick="showTab('allUsers')">Suggested Duos</button>
        <button id="tab-search" class="neon-btn" onclick="showTab('search')">Find Player</button>
        <button id="tab-friends" class="neon-btn" onclick="showTab('friends')">My Duos</button>
        <button id="tab-messaging" class="neon-btn" onclick="showTab('messaging')">Messaging</button>
        <button id="tab-profile" class="neon-btn" onclick="showTab('profile')">Profile</button>
        <button class="neon-btn remove" onclick="logout()">Logout</button>
    </div>

    <div id="allUsersTab" class="tab-content">
        <h2>Suggested Duos</h2>
        </div>

    <div id="searchTab" class="tab-content hidden">
        <h2>Find a Duo</h2>
        <div id="searchFilters">
            <input type="text" id="searchInput" placeholder="Search nickname or bio..." onkeyup="filterSearchResults()">
            <select id="statusFilter" onchange="filterSearchResults()">
                <option value="">Any Status</option>
                <option value="Online">Online</option>
                <option value="Offline">Offline</option>
                <option value="LFD">LFD (Looking for Duo)</option>
            </select>
            <select id="gameCategoryFilter" onchange="filterSearchResults()">
                <option value="">Any Category</option>
                <option value="FPS">FPS</option>
                <option value="MMO">MMO</option>
                <option value="MOBA">MOBA</option>
                <option value="RPG">RPG</option>
                <option value="Other">Other</option>
            </select>
            <select id="gameRoleFilter" onchange="filterSearchResults()">
                <option value="">Any Role</option>
                <option value="Support">Support</option>
                <option value="Tank">Tank</option>
                <option value="DPS">DPS</option>
                <option value="Healer">Healer</option>
                <option value="Generalist">Generalist</option>
            </select>
            <select id="lfdCategoryFilter" onchange="filterSearchResults()">
                <option value="">LFD Goal (Any)</option>
                <option value="Duo">Duo</option>
                <option value="Team">Team</option>
            </select>
        </div>
        <div id="searchResults">
            </div>
    </div>

    <div id="friendsTab" class="tab-content hidden">
        <h2 class="group-header">Pending Duo Requests (Incoming)</h2>
        <div id="pendingRequestsList">
            </div>

        <h2 class="group-header">My Active Duos</h2>
        <div id="friendList">
            </div>
    </div>

    <div id="messagingTab" class="tab-content hidden">
        <div id="activeDuoList">
            <p style="color:var(--neon-blue); text-align:center;">Select a Duo:</p>
        </div>
        <div id="chatWindow">
            <h3 id="chatHeader">Select a Duo to start messaging</h3>
            <div id="messageDisplay">
                </div>
            <div id="messageInputArea" class="hidden">
                <input type="text" id="messageInput" placeholder="Send a message..." onkeypress="if(event.keyCode==13) sendMessage()">
                <button class="neon-btn message" onclick="sendMessage()">Send</button>
            </div>
        </div>
    </div>

    <div id="profileTab" class="tab-content hidden">
        <div class="profile-container">
            <div style="text-align: center; margin-bottom: 20px;">
                <img id="profileAvatar" class="avatar" src="https://cdn-icons-png.flaticon.com/512/149/149071.png" style="width:100px; height:100px;">
            </div>
            
            <div class="profile-group">
                <label for="profileNickname">Nickname (One change only)</label>
                <input type="text" id="profileNickname" maxlength="20">
                <p id="nicknameChangeInfo" style="font-size:0.75em; margin:5px 0 0; color:yellow;">(You get one free nickname change.)</p>
            </div>

            <div class="profile-group">
                <label for="profileBio">Bio / Slogan</label>
                <textarea id="profileBio" rows="3" maxlength="150"></textarea>
            </div>

            <div class="profile-group">
                <label for="profileImageUrlInput">Avatar Image URL</label>
                <input type="url" id="profileImageUrlInput" oninput="previewUrl(this.value)">
            </div>
            
            <div class="profile-group">
                <label for="profileStatus">Status</label>
                <select id="profileStatus" onchange="toggleLfdCategory()">
                    <option value="Online">Online</option>
                    <option value="Offline">Offline</option>
                    <option value="LFD">LFD (Looking for Duo)</option>
                </select>
            </div>
            
            <div class="profile-group hidden" id="lfdCategoryGroup">
                <label for="profileLfdCategory">LFD Goal</label>
                <select id="profileLfdCategory">
                    <option value="Duo">Duo</option>
                    <option value="Team">Team/Group</option>
                </select>
            </div>

            <div class="profile-group">
                <label for="profileSkillRating">Skill Rating (1-5)</label>
                <select id="profileSkillRating">
                    <option value="1">★</option>
                    <option value="2">★★</option>
                    <option value="3">★★★</option>
                    <option value="4">★★★★</option>
                    <option value="5">★★★★★</option>
                </select>
            </div>
            
            <div class="profile-group">
                <label for="profileGameCategory">Primary Game Category</label>
                <select id="profileGameCategory">
                    <option value="FPS">FPS</option>
                    <option value="MMO">MMO</option>
                    <option value="MOBA">MOBA</option>
                    <option value="RPG">RPG</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            
            <div class="profile-group">
                <label for="profileGameRole">Primary Game Role</label>
                <select id="profileGameRole">
                    <option value="Support">Support</option>
                    <option value="Tank">Tank</option>
                    <option value="DPS">DPS</option>
                    <option value="Healer">Healer</option>
                    <option value="Generalist">Generalist</option>
                </select>
            </div>

            <button class="neon-btn add" onclick="saveProfile()" style="width:100%;">Save Profile</button>
            <p id="saveMessage"></p>
        </div>
    </div>

    <div id="viewProfileTab" class="tab-content hidden" style="text-align:center;">
        <button class="neon-btn" onclick="showTab('allUsers')" style="margin-bottom:20px;">← Back to Users</button>
        <div id="viewProfileContent" class="profile-container" style="display:flex; flex-direction:column; align-items:center; background-color: #1a1a1a; border-color:cyan; box-shadow: 0 0 15px rgba(0, 255, 255, 0.5);">
            </div>
    </div>

</div> <script src="config.js"></script>
<script src="app.js"></script>

</body>
</html>
