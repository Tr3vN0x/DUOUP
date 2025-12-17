<?php
// index.php
// This PHP file serves as the wrapper for the single-page application (SPA).
// The only changes here are removing the internal JavaScript and adding external links.
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>DUO UP</title>

<link rel="icon" type="image/x-icon" href="https://your-domain.com/favicon.ico"> 
<link rel="apple-touch-icon" href="https://your-domain.com/apple-touch-icon.png">

<meta property="og:title" content="DUO UP (BETA)">
<meta property="og:description" content="DUO UP BETA IS OUT NOW! ⚡ ">
<meta property="og:image" content="https://scontent-iad3-2.xx.fbcdn.net/v/t39.30808-6/598884570_845641984737502_3175907977751628974_n.jpg?_nc_cat=100&ccb=1-7&_nc_sid=127cfc&_nc_ohc=ZuwpdBTHlbQQ7kNvwFLa4-J&_nc_oc=AdnsRNEZ9LGhbLpRqeQnLeM5ZTiGo3THde7Ovkn1ymRwx86CnpNR8dGLxw8Vn8khnNbNbxoPDAEcuzexnVRQJ5Y&_nc_zt=23&_nc_ht=scontent-iad3-2.xx&_nc_gid=3wKM0Dsce2XSdse-2Nk3rw&oh=00_AflRQGBp1QY34jQ0Q1L20G0R8K8Hh3F34y2I2Uq9gDqK4A&oe=6946C99E">
<meta property="og:url" content="https://tr3vn0x.github.io/DUOUP/">
<meta property="og:type" content="website">

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="DUO UP: Cyber Social Platform">
<meta name="twitter:description" content="Connect, duo up, and conquer. Find your next teammate today.">
<meta name="twitter:image" content="https://your-domain.com/duo-up-thumbnail.png">

<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap" rel="stylesheet">

<script src="https://www.gstatic.com/firebasejs/9.0.0/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.0.0/firebase-auth-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.0.0/firebase-firestore-compat.js"></script>

<style>
/* --- Base styles --- */
body { margin:0; font-family:'Orbitron',sans-serif; background:#0b0b0b; color:white; }
.container { width:90%; max-width:1000px; margin:auto; padding:20px; }
.hidden { display:none !important; }
/* Header */
.header { background:#111; border-bottom:3px solid cyan; box-shadow:0 0 20px rgba(0,255,255,0.5); padding:10px 0; margin-bottom:20px; display:flex; align-items:center; justify-content:center; }
.header h1 { font-size:2.5em; color:#fff; text-shadow:0 0 5px cyan,0 0 10px cyan,0 0 15px magenta; margin:0; }
/* Icon Container for Lightning Bolt */
.icon-container { margin-right:15px; font-size:2.5em; color:yellow; text-shadow:0 0 5px yellow,0 0 10px orange; }
/* Buttons */
.neon-btn { border:2px solid cyan; background:transparent; color:white; padding:8px 14px; margin:5px; border-radius:6px; cursor:pointer; box-shadow:0 0 10px cyan; transition:all 0.2s ease-in-out; text-transform:uppercase; font-family:'Orbitron',sans-serif; }
.neon-btn:hover { border-color:magenta; box-shadow:0 0 15px magenta; transform: translateY(-1px); }
.neon-btn:disabled { border-color:#555; box-shadow:none; color:#555; cursor:not-allowed; }
.neon-btn.add { border-color:lime; box-shadow:0 0 10px lime; }
.neon-btn.add:hover { border-color:green; box-shadow:0 0 15px green; }
.neon-btn.remove { border-color:red; box-shadow:0 0 10px red; }
.neon-btn.remove:hover { border-color:orange; box-shadow:0 0 15px orange; }
.neon-btn.pending { border-color:yellow; box-shadow:0 0 10px yellow; color:yellow; cursor:default; }
/* Input fields */
input[type="text"], input[type="number"], textarea, select { flex-grow:1; padding:10px; border:1px solid cyan; background:#181818; color:white; border-radius:6px; font-family:inherit; outline:none; transition:border-color 0.2s, box-shadow 0.2s; }
input:focus, textarea:focus, select:focus { border-color: magenta; box-shadow: 0 0 8px magenta; }
textarea { resize: vertical; }
/* Cards */
.card-container { display:flex; flex-wrap:wrap; gap:20px; justify-content:center; padding-top: 10px; }
.card { width: 100%; max-width:300px; padding:20px; border:3px solid transparent; border-radius:8px; background:#181818; box-shadow:0 0 15px rgba(0,255,255,0.4), inset 0 0 5px rgba(0,255,255,0.2); border-image-source: linear-gradient(45deg, cyan, magenta, cyan); border-image-slice:1; display:flex; flex-direction:column; align-items:center; cursor:pointer; transition: transform 0.2s, box-shadow 0.2s; }
.card:hover { transform: translateY(-5px); box-shadow:0 0 30px magenta, inset 0 0 10px rgba(255,0,255,0.5); }
.request-card { cursor:default; border:3px solid orange; box-shadow:0 0 15px orange; }
.request-card:hover { transform:none; box-shadow:0 0 15px orange; }

.card-content-box { display:flex; flex-direction:column; align-items:center; width:100%; margin-bottom:15px; padding-bottom:15px; border-bottom:1px dashed #333; }
.avatar { width:80px; height:80px; border-radius:50%; border:4px solid magenta; object-fit:cover; margin-bottom:15px; }
.skill-rating { color:gold; font-size:1.4em; margin-bottom:10px; }

/* Game Category (formerly Discipline) styling */
.game-category { font-size:0.9em; padding:4px 10px; border-radius:4px; font-weight:bold; margin-bottom:5px; text-transform:uppercase; color:#0b0b0b; }
.game-category-leagueoflegends { background-color:#0077B6; color:white; }
.game-category-fortnite { background-color:#5a189a; color:white; }
.game-category-valorant { background-color:#FF4655; color:white; }
.game-category-cybersecurity { background-color:lime; color:#0b0b0b; }
.game-category-other { background-color:gray; color:white; }

/* Game Role styling */
.game-role { font-size:0.8em; padding:4px 10px; border-radius:4px; font-weight:bold; margin-bottom:5px; text-transform:uppercase; background-color:cyan; color:#0b0b0b;}

/* Looking For Category styling */
.lfd-category { font-size:0.8em; padding:4px 10px; border-radius:4px; font-weight:bold; margin-bottom:10px; text-transform:uppercase; background-color:#ff00ff; color:white; border:1px solid white;}


.status-indicator { font-size:0.9em; padding:4px 10px; border-radius:4px; font-weight:bold; margin-bottom:10px; text-transform:uppercase; }
.status-online { background-color:#00cc00; color:#0b0b0b; box-shadow:0 0 5px #00ff00; }
.status-ingame { background-color:#ffaa00; color:#0b0b0b; box-shadow:0 0 5px #ffaa00; }
.status-lfd { background-color:#ff00ff; color:white; box-shadow:0 0 5px #ff00ff; border:1px solid #ff00ff; }

/* Co-Founder Badge Style */
.nickname-group { display: flex; align-items: center; justify-content: center; margin-bottom: 5px; }

/* Nav */
#navigationBar { display:flex; justify-content:center; flex-wrap:wrap; padding:10px; background:#151515; border-radius:8px; margin-bottom:30px; border:1px solid #333; }
#navigationBar button.active { background:#00ffcc; color:#0b0b0b; box-shadow:0 0 15px #00ffcc; }
#searchControls { display:flex; gap:15px; margin-bottom:20px; align-items:center; flex-wrap:wrap; background:#1a1a1a; padding:15px; border-radius:8px; border:1px solid #004444; }
/* Messaging */
#messagingTab { display:flex; gap:20px; height:75vh; }
#chatList { width:300px; padding:15px; border:2px solid cyan; border-radius:8px; background:#1a1a1a; overflow-y:auto; }
#activeDuoList { margin-top:10px; }
.chat-entry { padding:10px; border-bottom:1px dashed #333; cursor:pointer; transition: background 0.2s; }
.chat-entry:hover { background:#2a2a2a; }
.chat-entry.active { background:#004444; border-left:5px solid lime; font-weight:bold; }
#chatWindow { flex-grow:1; display:flex; flex-direction:column; border:2px solid magenta; border-radius:8px; background:#1a1a1a; }
#chatHeader { padding:10px; border-bottom:2px solid magenta; margin:0; background:#330033; }
#messageDisplay { flex-grow:1; padding:15px; overflow-y:auto; display:flex; flex-direction:column; gap:15px; }
/* Message Bubbles - Styling the chat */
.message { max-width:70%; padding:10px 15px; border-radius:18px; font-size:0.95em; position:relative; }
.message span { display:block; font-size:0.7em; opacity:0.6; text-align:right; margin-top:3px; }
.message.sent { 
    background:#005555; 
    align-self:flex-end; 
    border-bottom-right-radius:5px;
    box-shadow: 0 0 5px #00ffff;
}
.message.received { 
    background:#333333; 
    align-self:flex-start; 
    border-bottom-left-radius:5px;
    box-shadow: 0 0 5px #ff00ff;
}
#messageInputArea { padding:10px; border-top:2px solid magenta; background:#0b0b0b; display:flex; gap:10px; }
/* Profile view */
#viewProfileTab { display:none; }
/* Refined Profile Content Style */
#viewProfileContent { padding:30px; border:3px solid magenta; border-radius:12px; background:#1a1a1a; margin-top:20px; display:flex; flex-direction:column; align-items:center; }
.profile-stat { width:100%; max-width:400px; display:flex; justify-content:space-between; padding:10px 0; border-bottom:1px dashed #333; margin-bottom:5px; }
.profile-stat span:first-child { color: cyan; font-weight:bold; }
.profile-stat span:last-child { text-align:right; }

/* Loading State */
#loadingOverlay {
    position: fixed; top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0, 0, 0, 0.9);
    z-index: 9999;
    display: flex; justify-content: center; align-items: center;
    font-size: 2em; color: lime; text-shadow: 0 0 10px lime;
}
</style>
</head>
<body onload="initFirebase()"> <div id="loadingOverlay">⚡</div>

<div class="header"><div class="icon-container">⚡</div><h1>DUO UP</h1></div>

<div id="loginPage" class="container hidden">
<p style="text-align:center;">Log in to connect and find your next cyber-duo.</p>
<div style="text-align:center;">
<button class="neon-btn" onclick="login()">Login with Google</button>
</div>
</div>

<div id="dashboard" class="container hidden">
<h2 id="welcome"></h2>
<div id="navigationBar">
<button class="neon-btn" id="tab-allUsers" onclick="showTab('allUsers')">Suggested</button>
<button class="neon-btn" id="tab-search" onclick="showTab('search')">Find Players</button>
<button class="neon-btn" id="tab-friends" onclick="showTab('friends')">Duos</button>
<button class="neon-btn" id="tab-messaging" onclick="showTab('messaging')">Messaging</button>
<button class="neon-btn" id="tab-profile" onclick="showTab('profile')">Profile</button>
<button class="neon-btn remove" onclick="logout()">Logout</button>
</div>

<div id="allUsersTab" class="card-container"></div>

<div id="searchTab" class="hidden container">
<h2>Player Search Filter</h2>
<div id="searchControls">
<input type="text" id="searchInput" placeholder="Search by Nickname or Bio..." onkeyup="filterSearchResults()">
<label style="color:cyan; margin-left:15px;">Status:</label>
<select id="statusFilter" onchange="filterSearchResults()">
<option value="">Any Status</option>
<option value="Online">Online</option>
<option value="In Game">In Game</option>
<option value="LFD">Looking For Duo (LFD)</option>
</select>
<label style="color:cyan; margin-left:15px;">Game/Category:</label>
<select id="gameCategoryFilter" onchange="filterSearchResults()">
<option value="">Any Game/Category</option>
<option value="LeagueOfLegends">League of Legends</option>
<option value="Fortnite">Fortnite</option>
<option value="Valorant">Valorant</option>
<option value="CyberSecurity">Cyber Security</option>
<option value="Other">Other</option>
</select>
<label style="color:cyan; margin-left:15px;">Game Role/Mode:</label>
<select id="gameRoleFilter" onchange="filterSearchResults()">
<option value="">Any Role/Mode</option>
<option value="TopTank">Top Tank (LoL)</option>
<option value="JungleCarry">Jungle Carry (LoL)</option>
<option value="Duo">Duo (Fortnite/Valorant)</option>
<option value="Squad">Squad (Fortnite)</option>
<option value="Support">Support (LoL/Valorant)</option>
<option value="ExploitDev">Exploit Dev (Cyber)</option>
<option value="RedTeamOp">Red Team Op (Cyber)</option>
</select>
<label style="color:cyan; margin-left:15px;">Looking For:</label>
<select id="lfdCategoryFilter" onchange="filterSearchResults()">
<option value="">Any LFD Type</option>
<option value="Duo">Duo (2-person team)</option>
<option value="Squad">Squad (3+ person team)</option>
<option value="Team">Full Team (Permanent)</option>
<option value="Mentor">Mentor/Mentee</option>
</select>
</div>
<div id="searchResults" class="card-container"></div>
</div>

<div id="friendsTab" class="container hidden">
<h2>Incoming Duo Requests</h2>
<div id="pendingRequestsList" class="card-container"></div>
<h2 style="margin-top:40px;">Your Accepted Duos</h2>
<div id="friendList" class="card-container">
    <p style="width:100%; text-align:center; color:magenta;">
    Loading accepted duos...
    </p>
</div>
</div>

<div id="messagingTab" class="hidden">
<div id="chatList">
<h3>Active Duos</h3>
<div id="activeDuoList">No Duos yet.</div>
</div>
<div id="chatWindow">
<h3 id="chatHeader">Select a Duo to start messaging</h3>
<div id="messageDisplay"></div>
<div id="messageInputArea" class="hidden">
<input type="text" id="messageInput" placeholder="Type your message..." onkeyup="if(event.key==='Enter') sendMessage()">
<button class="neon-btn" id="sendMessageBtn" onclick="sendMessage()">Send</button>
</div>
</div>
</div>

<div id="viewProfileTab" class="hidden container">
<button class="neon-btn" onclick="showTab('allUsers')">← Back to Suggested</button>
<div id="viewProfileContent"></div>
</div>

<div id="profileTab" class="hidden container">
<h2>Gamer-Profile Editor</h2>
<div style="padding:20px; border:2px solid magenta; border-radius:10px; background:#1a1a1a;">
<div style="display:flex; align-items:center; margin-bottom:20px; flex-wrap:wrap;">
    <div style="margin-right:20px; display:flex; flex-direction:column; align-items:center;">
        <img id="profileAvatar" class="avatar" src="" style="width:100px;height:100px;border-color:cyan;">
        
        <label style="color: cyan; display:block; margin:10px 0 5px 0;">New Image URL:</label>
        <input type="text" id="profileImageUrlInput" placeholder="Paste image link here" oninput="previewUrl(this.value)" style="width: 250px;">
        </div>
    <div style="flex-grow:1; min-width:200px;">
        <label style="color: cyan; display:block; margin-bottom:5px;">Unique Identifier:</label>
        <input type="text" id="profileNickname" value="User Nickname" placeholder="Your Unique Nickname">
        <p id="nicknameChangeInfo" style="color:yellow; font-size:0.8em; margin:5px 0 0 0;">(You get one free nickname change.)</p>
    </div>
</div>
<div style="margin-bottom:15px;">
<label style="color: cyan; display:block; margin-bottom:5px;">Bio/Tagline:</label>
<textarea id="profileBio" rows="3" style="width:100%;"></textarea>
</div>
<div style="display:flex; gap:30px; margin-bottom:20px; flex-wrap:wrap;">
<div>
    <label style="color: cyan; display:block; margin-bottom:5px;">Skill Rating (1-5):</label>
    <input id="profileSkillRating" type="number" min="1" max="5">
</div>
<div>
    <label style="color: cyan; display:block; margin-bottom:5px;">Primary Game/Category:</label>
    <select id="profileGameCategory">
        <option value="LeagueOfLegends">League of Legends</option>
        <option value="Fortnite">Fortnite</option>
        <option value="Valorant">Valorant</option>
        <option value="CyberSecurity">Cyber Security</option>
        <option value="Other">Other</option>
    </select>
</div>
</div>
<div style="display:flex; gap:30px; margin-bottom:20px; flex-wrap:wrap;">
    <div>
        <label style="color: cyan; display:block; margin-bottom:5px;">Game Role/Mode:</label>
        <select id="profileGameRole">
            <option value="TopTank">Top Tank (LoL)</option>
            <option value="JungleCarry">Jungle Carry (LoL)</option>
            <option value="Duo">Duo (Fortnite/Valorant)</option>
            <option value="Squad">Squad (Fortnite)</option>
            <option value="Support">Support (LoL/Valorant)</option>
            <option value="ExploitDev">Exploit Dev (Cyber)</option>
            <option value="RedTeamOp">Red Team Op (Cyber)</option>
            <option value="Generalist">Generalist/Flex</option>
        </select>
    </div>
    <div>
        <label style="color: cyan; display:block; margin-bottom:5px;">Current Status:</label>
        <select id="profileStatus" onchange="toggleLfdCategory()">
            <option value="Online">Online</option>
            <option value="In Game">In Game</option>
            <option value="LFD">Looking For Duo (LFD)</option>
        </select>
    </div>
    <div id="lfdCategoryGroup" class="hidden">
        <label style="color: cyan; display:block; margin-bottom:5px;">LFD Goal:</label>
        <select id="profileLfdCategory">
            <option value="Duo">Duo (2-person team)</option>
            <option value="Squad">Squad (3+ person team)</option>
            <option value="Team">Full Team (Permanent)</option>
            <option value="Mentor">Mentor/Mentee</option>
        </select>
    </div>
</div>
<button class="neon-btn" style="border-color:#00ff00; box-shadow:0 0 10px #00ff00;" onclick="saveProfile()">Save Profile</button>
<p id="saveMessage" style="color:#00ff00; margin-top:10px;"></p>
</div>
</div>

<script src="config.js"></script>
<script src="app.js"></script>

</body>
</html>
