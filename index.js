// app.js
// Contains all Firebase interaction and DOM manipulation logic, including polyfills.

// ----------------------------------------------------
// 0. BROWSER COMPATIBILITY POLYFILLS
// ----------------------------------------------------
// FIX for jQuery style selector not being available
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
// Simple contains polyfill/extension for querySelector
if (window.Element && !Element.prototype.matches) {
    Element.prototype.matches = Element.prototype.msMatchesSelector || Element.prototype.webkitMatchesSelector;
}
document.querySelector = (function(orig) {
    return function(selector) {
        if (selector.includes(':contains')) {
            const [base, text] = selector.split(':contains');
            const target = text.match(/\((.*?)\)/)[1].replace(/['"]/g, '');
            // We use Array.from and find for a safer check instead of NodeList iteration
            return Array.from(document.querySelectorAll(base)).find(e => e.textContent.includes(target));
        }
        return orig.call(this, selector);
    };
})(document.querySelector);


// ----------------------------------------------------
// 1. INITIALIZATION & AUTHENTICATION ENTRY POINT
// ----------------------------------------------------
function initFirebase() {
    if (!firebase.apps.length) {
        firebase.initializeApp(firebaseConfig);
    }
    auth = firebase.auth();
    db = firebase.firestore();

    // Listener is the main entry point to ensure user is ready
    auth.onAuthStateChanged(user => {
        currentUser = user;
        document.getElementById('loadingOverlay').classList.add('hidden');
        
        const loginPage = document.getElementById('loginPage');
        const dashboard = document.getElementById('dashboard');
        
        if (currentUser) {
            loginPage.classList.add('hidden');
            dashboard.classList.remove('hidden');
            
            showTab('allUsers'); 
            checkAndCreateUserProfile();
        } else {
            loginPage.classList.remove('hidden');
            dashboard.classList.add('hidden');
        }
    });
}

async function checkAndCreateUserProfile() {
    if (!currentUser) return;
    const userDocRef = db.collection('users').doc(currentUser.uid);
    const userDoc = await userDocRef.get();

    if (!userDoc.exists) {
        const defaultNickname = currentUser.displayName || 'New Cyber Warrior';
        await userDocRef.set({
            nickname: defaultNickname,
            profileImageUrl: currentUser.photoURL || DEFAULT_AVATAR,
            createdAt: firebase.firestore.FieldValue.serverTimestamp(),
            status: 'Online',
            bio: 'Ready to connect!',
            skillRating: 3,
            gameCategory: 'Other',
            gameRole: 'Generalist',
            nicknameChangeUsed: false
        });
        
        document.getElementById('welcome').textContent = `WELCOME ${defaultNickname.toUpperCase()} TO DUO UP!`;
        showTab('profile'); 
    } else {
        const data = userDoc.data();
        const nickname = data.nickname || currentUser.displayName || 'Cyber Warrior';
        document.getElementById('welcome').textContent = `WELCOME ${nickname.toUpperCase()} TO DUO UP!`;
    }
}


// ---------------------------
// Authentication
// ---------------------------
function login(){ auth.signInWithPopup(new firebase.auth.GoogleAuthProvider()); }

function logout(){ 
    if(chatListener) chatListener(); 
    auth.signOut(); 
}

// ---------------------------
// Tab switching
// ---------------------------
function showTab(tab){
    document.querySelectorAll('#allUsersTab,#searchTab,#friendsTab,#messagingTab,#profileTab,#viewProfileTab').forEach(e=>e.classList.add('hidden'));

    if(tab!=='messaging' && chatListener){
        chatListener();
        chatListener=null;
        currentChatId=null;
        currentChatPartnerId = null;
        document.getElementById('messageDisplay').innerHTML = '';
        document.getElementById('chatHeader').textContent = 'Select a Duo to start messaging';
        document.getElementById('messageInputArea').classList.add('hidden');
    }

    document.querySelectorAll('#navigationBar button').forEach(btn => btn.classList.remove('active'));
    document.getElementById('tab-' + tab)?.classList.add('active');


    switch(tab){
        case 'allUsers': loadAllUsers(); break;
        case 'search': filterSearchResults(); break;
        case 'friends': loadFriends(); break;
        case 'messaging': loadChatList(); break;
        case 'profile': loadUserProfile(); toggleLfdCategory(); break;
        default: break;
    }
    document.getElementById(tab+'Tab')?.classList.remove('hidden'); 
}

// ----------------------------------------------------
// 2. PROFILE MANAGEMENT
// ----------------------------------------------------

function previewUrl(url) {
    const avatarImg = document.getElementById('profileAvatar');
    avatarImg.src = url || DEFAULT_AVATAR;
    avatarImg.onerror = () => { avatarImg.src = DEFAULT_AVATAR; };
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

async function loadUserProfile() {
    if (!currentUser) return;

    const userDocRef = db.collection('users').doc(currentUser.uid);
    const doc = await userDocRef.get();
    
    if (doc.exists) {
        currentProfileData = doc.data();
        
        document.getElementById('profileNickname').value = currentProfileData.nickname || '';
        document.getElementById('profileBio').value = currentProfileData.bio || '';
        document.getElementById('profileSkillRating').value = currentProfileData.skillRating || 3;
        document.getElementById('profileGameCategory').value = currentProfileData.gameCategory || 'Other';
        document.getElementById('profileGameRole').value = currentProfileData.gameRole || 'Generalist';
        document.getElementById('profileStatus').value = currentProfileData.status || 'Online';
        document.getElementById('profileLfdCategory').value = currentProfileData.lfdCategory || 'Duo';
        document.getElementById('profileImageUrlInput').value = currentProfileData.profileImageUrl || '';
        document.getElementById('profileAvatar').src = currentProfileData.profileImageUrl || DEFAULT_AVATAR;

        const nicknameChangeInfo = document.getElementById('nicknameChangeInfo');
        if (currentProfileData.nicknameChangeUsed) {
            document.getElementById('profileNickname').disabled = true;
            nicknameChangeInfo.textContent = '(Nickname change already used.)';
            nicknameChangeInfo.style.color = 'red';
        } else {
            document.getElementById('profileNickname').disabled = false;
            nicknameChangeInfo.textContent = '(You get one free nickname change.)';
            nicknameChangeInfo.style.color = 'yellow';
        }

        toggleLfdCategory();
    }
}

async function saveProfile() {
    if (!currentUser) return;
    const saveMessage = document.getElementById('saveMessage');
    saveMessage.textContent = 'Saving...';
    saveMessage.style.color = 'yellow';

    const newNickname = document.getElementById('profileNickname').value.trim();
    const oldNickname = currentProfileData.nickname;
    const isNicknameChanged = newNickname !== oldNickname;
    const isChangeUsed = currentProfileData.nicknameChangeUsed;

    if (isNicknameChanged && isChangeUsed) {
        saveMessage.textContent = 'Error: You have already used your one free nickname change.';
        saveMessage.style.color = 'red';
        return;
    }

    if (isNicknameChanged && newNickname.length < 3) {
        saveMessage.textContent = 'Error: Nickname must be at least 3 characters long.';
        saveMessage.style.color = 'red';
        return;
    }

    try {
        const profileData = {
            nickname: newNickname,
            bio: document.getElementById('profileBio').value.trim(),
            skillRating: parseInt(document.getElementById('profileSkillRating').value),
            gameCategory: document.getElementById('profileGameCategory').value,
            gameRole: document.getElementById('profileGameRole').value,
            status: document.getElementById('profileStatus').value,
            profileImageUrl: document.getElementById('profileImageUrlInput').value || DEFAULT_AVATAR,
            
        };

        if (profileData.status === 'LFD') {
            profileData.lfdCategory = document.getElementById('profileLfdCategory').value;
        } else {
            profileData.lfdCategory = firebase.firestore.FieldValue.delete();
        }

        if (isNicknameChanged && !isChangeUsed) {
            profileData.nicknameChangeUsed = true;
        }

        await db.collection('users').doc(currentUser.uid).update(profileData);
        
        const doc = await db.collection('users').doc(currentUser.uid).get();
        currentProfileData = doc.data();
        document.getElementById('welcome').textContent = `WELCOME ${currentProfileData.nickname.toUpperCase()} TO DUO UP!`;
        loadUserProfile();

        saveMessage.textContent = 'Profile successfully saved!';
        saveMessage.style.color = '#00ff00';
        setTimeout(() => { saveMessage.textContent = ''; }, 3000);

    } catch (e) {
        console.error("Error saving profile: ", e);
        saveMessage.textContent = 'Error saving profile: ' + e.message;
        saveMessage.style.color = 'red';
    }
}


// ----------------------------------------------------
// 3. USER CARDS & RENDERING
// ----------------------------------------------------

/**
 * Creates the HTML element for a single user profile card.
 * NOTE: This renderUserCard is slightly different from the previous version 
 * because the logic for the final filterSearchResults relied on it returning an element, 
 * not just an HTML string. We'll ensure it returns a DOM Element.
 * @param {object} user - The user object from Firestore with an 'id' property (uid).
 * @param {string} mode - 'suggested', 'friend', 'request', 'search'
 * @returns {HTMLElement | null} The DOM element for the card.
 */
function renderUserCard(user, mode) {
    const isOwnProfile = user.id === currentUser.uid;
    const isDuos = mode === 'friend' || mode === 'messaging';
    const isPending = requestCache[user.id] && requestCache[user.id].status === 'pending';
    const hasSentRequest = requestCache[user.id] && requestCache[user.id].senderUid === currentUser.uid;

    if (isOwnProfile) return null;

    let buttonHTML = '';
    
    if (isDuos) {
        buttonHTML = `<button class="neon-btn remove" onclick="removeDuo('${user.id}')">Remove Duo</button>`;
    } else if (isPending && !hasSentRequest) {
        buttonHTML = `
            <button class="neon-btn add" onclick="acceptDuoRequest('${user.id}')">Accept Duo</button>
            <button class="neon-btn remove" onclick="rejectDuoRequest('${user.id}')">Reject</button>
        `;
    } else if (isPending && hasSentRequest) {
        buttonHTML = `<button class="neon-btn pending" disabled>Request Sent</button>`;
    } else if (requestCache[user.id] && requestCache[user.id].status === 'accepted') {
        buttonHTML = `<button class="neon-btn pending" disabled>Already Duos</button>`;
    } else {
        buttonHTML = `<button class="neon-btn add" onclick="sendDuoRequest('${user.id}')">Send Duo Request</button>`;
    }

    const statusClass = `status-${user.status.toLowerCase().replace(/\s/g, '')}`;
    const categoryClass = `game-category-${user.gameCategory.toLowerCase()}`;
    const cardClass = mode === 'request' ? 'card request-card' : 'card';
    const lfdGoal = user.status === 'LFD' && user.lfdCategory ? `<div class="lfd-category">${user.lfdCategory} LFD</div>` : '';

    const htmlString = `
        <div class="${cardClass}" onclick="viewProfile('${user.id}')">
            <div class="card-content-box">
                <img class="avatar" src="${user.profileImageUrl || DEFAULT_AVATAR}" onerror="this.src='${DEFAULT_AVATAR}'">
                <div class="nickname-group">
                    <h3>${user.nickname}</h3>
                    ${getCoFounderBadge(user.id)}
                    ${getCustomBadge(user.id)}
                </div>
                <div class="${statusClass} status-indicator">${user.status}</div>
                ${lfdGoal}
            </div>
            
            <div class="card-content-box">
                <div class="${categoryClass} game-category">${user.gameCategory}</div>
                <div class="game-role">${user.gameRole}</div>
                <div class="skill-rating">${'★'.repeat(user.skillRating)}</div>
                <p style="font-size:0.9em; text-align:center; opacity:0.8;">${user.bio || 'No bio provided.'}</p>
            </div>

            <div style="display:flex; justify-content:center; gap:10px;">
                ${buttonHTML}
            </div>
        </div>
    `;

    // Create a DOM element from the HTML string
    const tempDiv = document.createElement('div');
    tempDiv.innerHTML = htmlString.trim();
    return tempDiv.firstChild;
}


async function fetchAllUsers() {
    if (!currentUser) return;
    try {
        const snapshot = await db.collection('users').get();
        allUsersCache = snapshot.docs.map(doc => ({ id: doc.id, ...doc.data() }));
    } catch (e) {
        console.error("Error fetching users: ", e);
    }
}

async function loadAllUsers() {
    await fetchAllUsers();
    await fetchFriendRequestsAndDuos();
    renderUserList('allUsersTab', allUsersCache.filter(u => u.id !== currentUser.uid));
}

function renderUserList(containerId, users) {
    const container = document.getElementById(containerId);
    if (!container) return;
    container.innerHTML = ''; // Clear previous content

    if (users.length === 0) {
        container.innerHTML = '<p style="width:100%; text-align:center; color:gray;">No users match the criteria.</p>';
        return;
    }
    
    users.forEach(user => {
        const card = renderUserCard(user, 'suggested');
        if (card) container.appendChild(card);
    });
}

function filterSearchResults() {
    const searchInput = document.getElementById('searchInput').value.toLowerCase();
    const statusFilter = document.getElementById('statusFilter').value;
    const gameCategoryFilter = document.getElementById('gameCategoryFilter').value;
    const gameRoleFilter = document.getElementById('gameRoleFilter').value;
    const lfdCategoryFilter = document.getElementById('lfdCategoryFilter').value;
    const resultsContainer = document.getElementById('searchResults');
    resultsContainer.innerHTML = ''; // Clear container at start

    const filteredUsers = allUsersCache.filter(user => {
        // 1. Own profile exclusion
        if (user.id === currentUser.uid) return false;

        // 2. Search Text
        const textMatch = searchInput === '' ||
            (user.nickname && user.nickname.toLowerCase().includes(searchInput)) ||
            (user.bio && user.bio.toLowerCase().includes(searchInput));
        if (!textMatch) return false;

        // 3. Status Filter
        if (statusFilter && user.status !== statusFilter) return false;

        // 4. Game/Category Filter
        if (gameCategoryFilter && user.gameCategory !== gameCategoryFilter) return false;
        
        // 5. Game Role Filter
        if (gameRoleFilter && user.gameRole !== gameRoleFilter) return false;
        
        // 6. LFD Category Filter
        if (lfdCategoryFilter && user.status === 'LFD' && user.lfdCategory !== lfdCategoryFilter) return false;
        if (lfdCategoryFilter && user.status !== 'LFD' && lfdCategoryFilter !== '') return false;

        return true;
    });

    // --- LOGIC FROM THE FINAL PART YOU PROVIDED ---
    if (filteredUsers.length === 0) {
        resultsContainer.innerHTML = '<p style="width:100%; text-align:center; color:gray;">No matching players found.</p>';
        return;
    }

    filteredUsers.forEach(user => {
        const card = renderUserCard(user, 'search'); // Pass the user object
        if (card) resultsContainer.appendChild(card);
    });
    // --- END LOGIC FROM THE FINAL PART ---
}


// ----------------------------------------------------
// 4. FRIENDS/REQUESTS
// ----------------------------------------------------

async function fetchFriendRequestsAndDuos() {
    if (!currentUser) return;
    requestCache = {};

    try {
        const requests = await db.collection('friendRequests')
            .where('participants', 'array-contains', currentUser.uid)
            .get();

        requests.docs.forEach(doc => {
            const data = doc.data();
            const partnerId = data.senderUid === currentUser.uid ? data.receiverUid : data.senderUid;
            requestCache[partnerId] = data;
        });

    } catch (e) {
        console.error("Error fetching friend requests: ", e);
    }
}

async function loadFriends() {
    await fetchAllUsers();
    await fetchFriendRequestsAndDuos();

    const pendingRequests = [];
    const acceptedDuos = [];
    
    for (const partnerId in requestCache) {
        const request = requestCache[partnerId];
        const user = findUserInCache(partnerId);

        if (!user) continue;

        if (request.status === 'pending') {
            if (request.receiverUid === currentUser.uid) {
                pendingRequests.push(user);
            }
        } else if (request.status === 'accepted') {
            acceptedDuos.push(user);
        }
    }

    const pendingContainer = document.getElementById('pendingRequestsList');
    pendingContainer.innerHTML = '';
    if (pendingRequests.length === 0) {
        pendingContainer.innerHTML = '<p style="width:100%; text-align:center; color:magenta;">No incoming requests.</p>';
    } else {
        pendingRequests.forEach(user => {
            const card = renderUserCard(user, 'request');
            if (card) pendingContainer.appendChild(card);
        });
    }

    const friendContainer = document.getElementById('friendList');
    friendContainer.innerHTML = '';
    if (acceptedDuos.length === 0) {
        friendContainer.innerHTML = '<p style="width:100%; text-align:center; color:magenta;">You have no active Duos.</p>';
    } else {
        acceptedDuos.forEach(user => {
            const card = renderUserCard(user, 'friend');
            if (card) friendContainer.appendChild(card);
        });
    }
}

async function sendDuoRequest(receiverUid) {
    if (!currentUser) return;

    try {
        const requestId = getCanonicalId(currentUser.uid, receiverUid);

        await db.collection('friendRequests').doc(requestId).set({
            senderUid: currentUser.uid,
            receiverUid: receiverUid,
            participants: [currentUser.uid, receiverUid],
            status: 'pending',
            createdAt: firebase.firestore.FieldValue.serverTimestamp()
        });

        alert(`Duo request sent to ${findUserInCache(receiverUid)?.nickname || receiverUid}!`);
        // Use loadFriends to refresh relationships across tabs
        loadFriends(); 

    } catch (e) {
        console.error("Error sending request: ", e);
        alert("Failed to send request. Check console.");
    }
}

async function acceptDuoRequest(senderUid) {
    if (!currentUser) return;

    try {
        const requestId = getCanonicalId(currentUser.uid, senderUid);

        await db.collection('friendRequests').doc(requestId).update({
            status: 'accepted',
            acceptedAt: firebase.firestore.FieldValue.serverTimestamp()
        });

        alert(`You are now Duos with ${findUserInCache(senderUid)?.nickname || senderUid}!`);
        loadFriends(); 

    } catch (e) {
        console.error("Error accepting request: ", e);
        alert("Failed to accept request. Check console.");
    }
}

async function rejectDuoRequest(senderUid) {
    if (!currentUser) return;

    try {
        const requestId = getCanonicalId(currentUser.uid, senderUid);
        await db.collection('friendRequests').doc(requestId).delete();

        alert(`Duo request from ${findUserInCache(senderUid)?.nickname || senderUid} rejected.`);
        loadFriends(); 

    } catch (e) {
        console.error("Error rejecting request: ", e);
        alert("Failed to reject request. Check console.");
    }
}

async function removeDuo(partnerUid) {
    if (!currentUser) return;

    if (!confirm(`Are you sure you want to remove ${findUserInCache(partnerUid)?.nickname || partnerUid} as a Duo? This will clear your chat history.`)) {
        return;
    }

    try {
        const requestId = getCanonicalId(currentUser.uid, partnerUid);
        const chatId = getCanonicalId(currentUser.uid, partnerUid);

        await db.collection('friendRequests').doc(requestId).delete();

        // Batch deletion of chat messages (Good for small chats, but keep the Cloud Function warning in mind)
        const messagesSnapshot = await db.collection('chats').doc(chatId).collection('messages').get();
        const batch = db.batch();
        messagesSnapshot.docs.forEach(doc => {
            batch.delete(doc.ref);
        });
        await batch.commit();

        alert(`Duo relationship with ${findUserInCache(partnerUid)?.nickname || partnerUid} removed.`);
        loadFriends(); 
        
    } catch (e) {
        console.error("Error removing duo: ", e);
        alert("Failed to remove Duo. Check console.");
    }
}


// ----------------------------------------------------
// 5. VIEW PROFILE
// ----------------------------------------------------

function viewProfile(uid) {
    const user = findUserInCache(uid);
    if (!user) {
        alert("User profile data not found in cache.");
        return;
    }
    renderFullProfile(user);
    
    document.querySelectorAll('#allUsersTab,#searchTab,#friendsTab,#messagingTab,#profileTab,#viewProfileTab').forEach(e=>e.classList.add('hidden'));
    document.getElementById('viewProfileTab').classList.remove('hidden');
}

function renderFullProfile(user) {
    const container = document.getElementById('viewProfileContent');
    if (!container) return;

    const isDuos = requestCache[user.id] && requestCache[user.id].status === 'accepted';
    let buttonHTML = '';

    if (isDuos) {
        buttonHTML = `<button class="neon-btn remove" onclick="removeDuo('${user.id}')">Remove Duo</button>`;
    } else if (requestCache[user.id] && requestCache[user.id].status === 'pending') {
        buttonHTML = `<button class="neon-btn pending" disabled>Request Pending</button>`;
    } else {
        buttonHTML = `<button class="neon-btn add" onclick="sendDuoRequest('${user.id}')">Send Duo Request</button>`;
    }

    const statusClass = `status-${user.status.toLowerCase().replace(/\s/g, '')}`;
    const categoryClass = `game-category-${user.gameCategory.toLowerCase()}`;
    const lfdGoal = user.status === 'LFD' && user.lfdCategory ? `<div class="lfd-category">${user.lfdCategory} LFD</div>` : '';


    container.innerHTML = `
        <img class="avatar" src="${user.profileImageUrl || DEFAULT_AVATAR}" style="width:120px; height:120px; border-color:cyan;" onerror="this.src='${DEFAULT_AVATAR}'">
        <div class="nickname-group" style="margin-top:15px;">
            <h2 style="color:cyan;">${user.nickname}</h2>
            ${getCoFounderBadge(user.id)}
            ${getCustomBadge(user.id)}
        </div>
        <div class="${statusClass} status-indicator">${user.status}</div>
        ${lfdGoal}
        
        <p style="text-align:center; font-style:italic; max-width:500px; margin-top:20px;">"${user.bio || 'No bio provided.'}"</p>

        <div style="width:100%; max-width:400px; margin-top:20px;">
            <div class="profile-stat"><span>Skill Rating:</span><span>${'★'.repeat(user.skillRating)} (${user.skillRating}/5)</span></div>
            <div class="profile-stat"><span>Primary Category:</span><span class="${categoryClass} game-category" style="box-shadow:none;">${user.gameCategory}</span></div>
            <div class="profile-stat"><span>Game Role:</span><span class="game-role" style="box-shadow:none;">${user.gameRole}</span></div>
            <div class="profile-stat"><span>Joined:</span><span>${user.createdAt ? new Date(user.createdAt.seconds * 1000).toLocaleDateString() : 'N/A'}</span></div>
        </div>

        <div style="display:flex; justify-content:center; gap:10px; margin-top:30px;">
            ${buttonHTML}
            ${isDuos ? `<button class="neon-btn" onclick="startChat('${user.id}')">Message Duo</button>` : ''}
        </div>
    `;
}


// ----------------------------------------------------
// 6. MESSAGING
// ----------------------------------------------------

async function loadChatList() {
    if (!currentUser) return;
    
    await fetchFriendRequestsAndDuos();
    await fetchAllUsers();

    const activeDuos = [];
    for (const partnerId in requestCache) {
        const request = requestCache[partnerId];
        if (request.status === 'accepted') {
            const user = findUserInCache(partnerId);
            if (user) activeDuos.push(user);
        }
    }

    const listContainer = document.getElementById('activeDuoList');
    listContainer.innerHTML = activeDuos.length > 0
        ? activeDuos.map(user => `
            <div class="chat-entry" data-uid="${user.id}" onclick="startChat('${user.id}')">
                ${user.nickname}
            </div>
          `).join('')
        : '<p style="color:gray;">You have no active Duos to message.</p>';
}

function startChat(partnerUid) {
    if (!currentUser || !partnerUid) return;

    if (chatListener) {
        chatListener(); 
        chatListener = null;
    }

    currentChatPartnerId = partnerUid;
    currentChatId = getCanonicalId(currentUser.uid, partnerUid);
    const partner = findUserInCache(partnerUid);

    showTab('messaging'); 
    document.getElementById('chatHeader').textContent = `Chatting with: ${partner?.nickname || 'Duo'}`;
    document.getElementById('messageDisplay').innerHTML = '';
    document.getElementById('messageInputArea').classList.remove('hidden');
    document.querySelectorAll('.chat-entry').forEach(e => e.classList.remove('active'));
    document.querySelector(`.chat-entry[data-uid="${partnerUid}"]`)?.classList.add('active');


    const messagesRef = db.collection('chats').doc(currentChatId).collection('messages').orderBy('timestamp');

    chatListener = messagesRef.onSnapshot(snapshot => {
        const messageDisplay = document.getElementById('messageDisplay');
        let newMessages = '';
        
        snapshot.docChanges().forEach(change => {
            if (change.type === 'added') {
                const message = change.doc.data();
                const isSent = message.senderUid === currentUser.uid;
                const time = message.timestamp ? new Date(message.timestamp.seconds * 1000).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) : '';
                
                newMessages += `
                    <div class="message ${isSent ? 'sent' : 'received'}">
                        ${message.content}
                        <span>${time}</span>
                    </div>
                `;
            }
        });

        messageDisplay.innerHTML += newMessages;
        messageDisplay.scrollTop = messageDisplay.scrollHeight;

    }, error => {
        console.error("Error setting up chat listener: ", error);
        document.getElementById('messageDisplay').textContent = "Failed to load messages.";
    });
}


async function sendMessage() {
    if (!currentUser || !currentChatId || !currentChatPartnerId) return;

    const messageInput = document.getElementById('messageInput');
    const content = messageInput.value.trim();

    if (content === '') return;

    try {
        await db.collection('chats').doc(currentChatId).collection('messages').add({
            senderUid: currentUser.uid,
            receiverUid: currentChatPartnerId,
            content: content,
            timestamp: firebase.firestore.FieldValue.serverTimestamp()
        });

        messageInput.value = '';

    } catch (e) {
        console.error("Error sending message: ", e);
        alert("Failed to send message. Check console.");
    }
}


// ----------------------------------------------------
// 7. START APPLICATION
// ----------------------------------------------------
initFirebase();
